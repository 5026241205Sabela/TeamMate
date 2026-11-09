<?php

namespace App\Http\Controllers;

use App\Models\JoinRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JoinRequestController extends Controller
{
    /**
     * Display all join requests.
     */
    public function index(): View
    {
        $joinRequests = JoinRequest::with([
            'account',
            'post',
            'requirementFile'
        ])
        ->orderByDesc('request_date')
        ->get();

        $pendingRequests = $joinRequests
            ->where('request_status', 'pending');

        $reviewedRequests = $joinRequests
            ->whereIn('request_status', ['accepted', 'declined']);

        return view('posts.join-requests', [
            'joinRequests' => $joinRequests,
            'pendingRequests' => $pendingRequests,
            'reviewedRequests' => $reviewedRequests,
            'totalApplicants' => $joinRequests->count(),
        ]);
    }

    /**
     * Accept or decline a join request.
     */
    public function updateStatus(
        Request $request,
        JoinRequest $joinRequest
    ): RedirectResponse {

        $validated = $request->validate([
            'request_status' => [
                'required',
                'in:accepted,declined'
            ],
        ]);

        if ($joinRequest->request_status !== 'pending') {
            return back()->with(
                'error',
                'This request has already been reviewed.'
            );
        }

        $joinRequest->update([
            'request_status' => $validated['request_status'],
        ]);

        return redirect()
            ->route('join-requests.index')
            ->with('success', 'Join request status updated successfully.');
    }
}