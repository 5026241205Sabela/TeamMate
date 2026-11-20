<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportModerationController extends Controller
{
    /**
     * Display the Admin Report List moderation queue and dismissed history.
     */
    public function index(Request $request): View
    {
        $tab = $request->string('tab', 'pending')->toString();

        if (! in_array($tab, ['pending', 'history'], true)) {
            $tab = 'pending';
        }

        $pendingPostsCount = Post::query()
            ->whereHas('reports', fn (Builder $query) => $query->where('report_status', 'pending'))
            ->count();

        $dismissedReportsCount = Report::query()
            ->where('report_status', 'dismissed')
            ->count();

        $reportedPosts = $tab === 'pending'
            ? Post::query()
                ->with([
                    'account:account_id,full_name,email,nim,university',
                    'category:category_id,category_name',
                    'reports' => fn (BuilderContract $query) => $query->where('report_status', 'pending')->orderByDesc('report_date'),
                ])
                ->whereHas('reports', fn (Builder $query) => $query->where('report_status', 'pending'))
                ->orderByDesc('post_id')
                ->get()
            : collect();

        $dismissedReports = $tab === 'history'
            ? Report::query()
                ->with(['post.account:account_id,full_name,email,nim,university', 'post.category:category_id,category_name'])
                ->where('report_status', 'dismissed')
                ->orderByDesc('report_date')
                ->get()
            : collect();

        return view('admin.reports', [
            'reportedPosts' => $reportedPosts,
            'dismissedReports' => $dismissedReports,
            'tab' => $tab,
            'pendingPostsCount' => $pendingPostsCount,
            'dismissedReportsCount' => $dismissedReportsCount,
        ]);
    }

    /**
     * Remove a reported post and notify its owner.
     */
    public function removePost(Post $post): RedirectResponse
    {
        $ownerId = $post->account_id;
        $title = $post->title;

        DB::transaction(function () use ($post, $ownerId, $title): void {
            Notification::create([
                'account_id' => $ownerId,
                'message' => "Postingan kamu \"{$title}\" telah dihapus oleh Admin karena tidak sesuai ketentuan komunitas.",
                'time_created' => now(),
            ]);

            $post->deleteWithRelations();
        });

        return redirect()
            ->route('admin.reports.index')
            ->with('success', "Postingan \"{$title}\" berhasil dihapus dan notifikasi telah dikirim ke pemilik postingan.");
    }

    /**
     * Dismiss all pending reports for a post and keep the post active.
     */
    public function keepPost(Post $post): RedirectResponse
    {
        Report::query()
            ->where('post_id', $post->post_id)
            ->where('report_status', 'pending')
            ->update(['report_status' => 'dismissed']);

        return redirect()
            ->route('admin.reports.index')
            ->with('success', "Laporan untuk postingan \"{$post->title}\" telah diabaikan (Keep Post). Postingan tetap dipertahankan.");
    }
}
