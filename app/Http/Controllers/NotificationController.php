<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display notifications belonging to the authenticated account.
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->orderByDesc('time_created')
            ->orderByDesc('notification_id')
            ->get();

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }
}
