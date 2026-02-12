<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications;

        // mark unread as read
        Auth::user()->unreadNotifications->markAsRead();

        return view("utilisateur.notifications", compact("notifications"));
    }
}
