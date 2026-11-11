<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Show the authenticated application homepage (recruitment feed + filters).
     */
    public function home(Request $request): View|RedirectResponse
    {
        $account = $request->user();

        if ($account?->isAdmin()) {
            return to_route('admin.verifications.index');
        }

        if ($account && ! $account->isApprovedByAdmin()) {
            return to_route('verification.notice');
        }

        $categories = Category::query()
            ->orderBy('category_name')
            ->get();

        $posts = $this->queryActivePosts();

        return view('home', [
            'categories' => $categories,
            'posts' => $posts,
        ]);
    }

    /**
     * Render the active open team posts as an HTML fragment for the homepage feed.
     */
    public function latest(Request $request): View
    {
        $keyword = trim($request->string('q')->toString());
        $categoryId = $request->integer('category_id');

        $posts = $this->queryActivePosts($keyword, $categoryId);

        return view('posts.partials.latest', ['posts' => $posts]);
    }

    /**
     * Fetch active recruitment posts with optional filters and pagination.
     */
    private function queryActivePosts(string $keyword = '', int $categoryId = 0): LengthAwarePaginator
    {
        return Post::query()
            ->with(['account:account_id,full_name,university', 'category:category_id,category_name'])
            ->where('post_status', 'active')
            ->when($categoryId > 0, fn (Builder $query) => $query->where('category_id', $categoryId))
            ->when($keyword !== '', function (Builder $query) use ($keyword): void {
                $like = '%'.mb_strtolower($keyword).'%';
                $query->where(function (Builder $nested) use ($like): void {
                    $nested->whereRaw('LOWER(title) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(COALESCE(description, \'\')) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(COALESCE(member_criteria, \'\')) LIKE ?', [$like]);
                });
            })
            ->latest('date_created')
            ->orderByDesc('post_id')
            ->paginate(9)
            ->withQueryString();
    }
}
