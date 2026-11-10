<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class CompetitionPostController extends Controller
{
    /**
     * Fallback testing account when no user is authenticated in isolated tests.
     */
    private const TEST_ACCOUNT_ID = 2;

    private function activeAccountId(): int
    {
        return (int) (Auth::id() ?? self::TEST_ACCOUNT_ID);
    }

    private function activeAccount(): ?Account
    {
        return Auth::user() ?? Account::find(self::TEST_ACCOUNT_ID);
    }

    public function index(): View
    {
        $accountId = $this->activeAccountId();
        $account = $this->activeAccount();

        $posts = Post::with('category')
            ->where('account_id', $accountId)
            ->orderByDesc('date_created')
            ->get();

        return view('posts.my-posts', [
            'posts' => $posts,
            'testingAccount' => $account,
        ]);
    }

    public function edit(int $id): View
    {
        $post = Post::with('category')
            ->where('post_id', $id)
            ->where('account_id', $this->activeAccountId())
            ->firstOrFail();

        return view('posts.edit-post-status', [
            'post' => $post,
            'testingAccount' => $this->activeAccount(),
        ]);
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,closed,completed'],
        ]);

        $post = Post::where('post_id', $id)
            ->where('account_id', $this->activeAccountId())
            ->firstOrFail();

        $post->post_status = $validated['status'];

        if ($validated['status'] === 'completed') {
            $post->status_change_time = now();
            $post->deletion_schedule = now()->addDays(2);
        } else {
            $post->status_change_time = null;
            $post->deletion_schedule = null;
        }

        $post->save();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Status successfully updated');
    }

    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('category_name')
            ->get();

        return view('posts.create', [
            'categories' => $categories,
            'testingAccount' => $this->activeAccount(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'category_id' => [
                    'required',
                    'integer',
                    'exists:categories,category_id',
                ],
                'description' => ['required', 'string'],
                'poster' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],
                'member_criteria' => ['nullable', 'string'],
            ],
            [
                'title.required' => 'Competition title wajib diisi.',
                'title.max' => 'Competition title maksimal 255 karakter.',
                'category_id.required' => 'Silakan pilih kategori kompetisi.',
                'category_id.exists' => 'Kategori yang dipilih tidak tersedia.',
                'description.required' => 'Competition description wajib diisi.',
                'poster.required' => 'Poster kompetisi wajib diunggah.',
                'poster.image' => 'File poster harus berupa gambar.',
                'poster.mimes' => 'Format poster harus JPG, JPEG, PNG, atau WEBP.',
                'poster.max' => 'Ukuran poster maksimal 2 MB.',
            ]
        );

        $account = $this->activeAccount();

        if (! $account) {
            return back()
                ->withInput($request->except('poster'))
                ->with('error', 'Akun pengguna tidak ditemukan. Silakan login kembali.');
        }

        $posterPath = $request->file('poster')->store('posters', 'public');

        try {
            DB::transaction(function () use ($validated, $posterPath, $account) {
                Post::create([
                    'account_id' => $account->account_id,
                    'category_id' => $validated['category_id'],
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'poster' => $posterPath,
                    'member_criteria' => $validated['member_criteria'] ?? null,
                    'post_status' => 'active',
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($posterPath);

            report($exception);

            return back()
                ->withInput($request->except('poster'))
                ->with('error', 'Postingan gagal disimpan. Periksa kembali koneksi database dan struktur tabel, lalu coba lagi.');
        }

        return redirect()
            ->route('posts.create')
            ->with('success', 'Competition post berhasil dipublikasikan!');
    }
}
