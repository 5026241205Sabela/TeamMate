<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JoinRequest;
use App\Models\Notification;
use App\Models\Post;
use App\Models\RequirementFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class JoinRequestController extends Controller
{
    /**
     * Show the application form for applying to another student's post.
     */
    public function create(Request $request, Post $post): View|RedirectResponse
    {
        /** @var Account $account */
        $account = $request->user();

        if ($redirect = $this->ensureEligibleToApply($account, $post)) {
            return $redirect;
        }

        $post->load(['account:account_id,full_name,university', 'category:category_id,category_name']);

        return view('posts.apply', [
            'post' => $post,
            'account' => $account,
        ]);
    }

    /**
     * Store a new join request along with its uploaded requirement file.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        /** @var Account $account */
        $account = $request->user();

        if ($redirect = $this->ensureEligibleToApply($account, $post)) {
            return $redirect;
        }

        $request->validate(
            [
                'requirement_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            ],
            [
                'requirement_file.required' => 'Semua kolom wajib diisi, termasuk file persyaratan.',
                'requirement_file.file' => 'File persyaratan tidak valid.',
                'requirement_file.mimes' => 'Format file persyaratan harus PDF, DOC, DOCX, JPG, atau PNG.',
                'requirement_file.max' => 'Ukuran file persyaratan maksimal 5 MB.',
            ]
        );

        $file = $request->file('requirement_file');
        $storedPath = $file->store('requirements', 'local');

        if (! $storedPath) {
            return back()->with('error', 'File persyaratan gagal diunggah. Silakan coba lagi.');
        }

        try {
            DB::transaction(function () use ($account, $post, $file, $storedPath): void {
                $joinRequest = JoinRequest::create([
                    'account_id' => $account->account_id,
                    'post_id' => $post->post_id,
                    'request_date' => now(),
                    'request_status' => 'pending',
                ]);

                RequirementFile::create([
                    'request_id' => $joinRequest->request_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_location' => $storedPath,
                    'upload_date' => now(),
                ]);

                Notification::create([
                    'account_id' => $post->account_id,
                    'message' => "Permintaan bergabung baru dari {$account->full_name} untuk postingan \"{$post->title}\".",
                    'time_created' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($storedPath);
            report($exception);

            return back()->with('error', 'Permintaan bergabung gagal disimpan. Silakan coba lagi.');
        }

        return redirect()
            ->route('home')
            ->with('success', 'Application is successfully submitted and recorded');
    }

    /**
     * Display incoming join requests for posts owned by the authenticated user.
     */
    public function index(Request $request): View
    {
        /** @var Account $account */
        $account = $request->user();
        $postId = $request->integer('post_id');
        $filteredPost = null;

        if ($postId > 0) {
            $filteredPost = Post::query()
                ->where('post_id', $postId)
                ->where('account_id', $account->account_id)
                ->first();
        }

        $joinRequests = JoinRequest::with([
            'account',
            'post',
            'requirementFile',
        ])
            ->whereHas('post', fn (Builder $query) => $query->where('account_id', $account->account_id))
            ->when($postId > 0, fn (Builder $query) => $query->where('post_id', $postId))
            ->orderByDesc('request_date')
            ->get();

        $pendingRequests = $joinRequests->where('request_status', 'pending');
        $reviewedRequests = $joinRequests->whereIn('request_status', ['accepted', 'declined']);

        return view('posts.join-requests', [
            'joinRequests' => $joinRequests,
            'pendingRequests' => $pendingRequests,
            'reviewedRequests' => $reviewedRequests,
            'totalApplicants' => $joinRequests->count(),
            'filteredPost' => $filteredPost,
        ]);
    }

    /**
     * Accept or decline a join request owned by the authenticated post author.
     */
    public function updateStatus(Request $request, JoinRequest $joinRequest): RedirectResponse
    {
        $joinRequest->loadMissing(['post', 'account']);

        abort_unless(
            $joinRequest->post && (int) $joinRequest->post->account_id === (int) $request->user()->account_id,
            403
        );

        $validated = $request->validate([
            'request_status' => [
                'required',
                'in:accepted,declined',
            ],
        ]);

        if ($joinRequest->request_status !== 'pending') {
            return back()->with(
                'error',
                'This request has already been reviewed.'
            );
        }

        $status = $validated['request_status'];

        DB::transaction(function () use ($joinRequest, $status): void {
            $joinRequest->update([
                'request_status' => $status,
            ]);

            $statusLabel = $status === 'accepted' ? 'diterima' : 'ditolak';

            Notification::create([
                'account_id' => $joinRequest->account_id,
                'message' => "Permintaan bergabung kamu pada postingan \"{$joinRequest->post->title}\" telah {$statusLabel}.",
                'time_created' => now(),
            ]);
        });

        $flashMessage = $status === 'accepted'
            ? 'Join request berhasil diterima.'
            : 'Join request berhasil ditolak.';

        $postId = $request->integer('post_id');

        return redirect()
            ->route('join-requests.index', $postId > 0 ? ['post_id' => $postId] : [])
            ->with('success', $flashMessage);
    }

    /**
     * Allow the post owner or applicant to view/download the uploaded requirement file.
     */
    public function downloadFile(Request $request, JoinRequest $joinRequest): BinaryFileResponse
    {
        $joinRequest->loadMissing(['post', 'requirementFile']);

        $currentAccountId = (int) $request->user()->account_id;
        $ownerId = (int) ($joinRequest->post?->account_id ?? 0);
        $applicantId = (int) $joinRequest->account_id;

        abort_unless(in_array($currentAccountId, [$ownerId, $applicantId], true), 403);

        $file = $joinRequest->requirementFile;
        abort_unless($file && filled($file->file_location) && Storage::disk('local')->exists($file->file_location), 404);

        return response()->file(Storage::disk('local')->path($file->file_location), [
            'Content-Disposition' => 'inline; filename="'.addslashes($file->file_name).'"',
        ]);
    }

    /**
     * Validate that the authenticated student can apply to the target post.
     */
    private function ensureEligibleToApply(Account $account, Post $post): ?RedirectResponse
    {
        if ((int) $post->account_id === (int) $account->account_id) {
            return redirect()
                ->route('posts.show', $post->post_id)
                ->with('error', 'You cannot apply to your own post.');
        }

        if ($post->post_status !== 'active') {
            return redirect()
                ->route('posts.show', $post->post_id)
                ->with('error', 'This post is no longer accepting applications.');
        }

        $alreadyApplied = JoinRequest::query()
            ->where('post_id', $post->post_id)
            ->where('account_id', $account->account_id)
            ->exists();

        if ($alreadyApplied) {
            return redirect()
                ->route('posts.show', $post->post_id)
                ->with('info', 'You have already submitted an application for this post.');
        }

        return null;
    }
}
