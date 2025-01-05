<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function allNotifications()
    {
        $user = Auth::user();

        $notifications = $user->notifications->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($notifications);
    }

    public function getSingleNotification($notificationId)
    {
        $user = Auth::user();

        // $notification = $user->notifications()->find($notificationId);
        $notification = $user->notifications->find($notificationId);

        if ($notification) {
            return response()->json([
                'message' => 'Notification found',
                'notification' => $notification
            ], 200);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function markSingleNotificationAsRead($notificationId)
    {
        $user = Auth::user();

        $user_notification = $user->notifications;

        $notification = $user_notification->find($notificationId);

        if ($notification) {

            $notification->markAsRead();

            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }


}
