<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // latest 5 unread notifications (CORRECT)
    $notifications = $user->unreadNotifications
        ->sortByDesc('created_at')
        ->take(5);

    $unreadCount = $user->unreadNotifications->count();

    // mark all as read
    $user->unreadNotifications->markAsRead();

    return view('notifications.index', compact('notifications', 'unreadCount'));
}
}
