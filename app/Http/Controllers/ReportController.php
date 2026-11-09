<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function create(Post $post): View
    {
        return view('reports.create', compact('post'));
    }

    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                Rule::in([
                    'Spam or misleading',
                    'Inappropriate content',
                    'Harassment or bullying',
                    'Scam or fraud',
                    'Other',
                ]),
            ],
            'description' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $post->reports()->create([
            'reason' => $validated['reason'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('reports.create', $post->post_id)
            ->with('success', 'Report submitted successfully.');
    }
}
