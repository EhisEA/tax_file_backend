<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserNotificationCollection;
use App\Http\Resources\UserNotificationResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function index(): UserNotificationCollection
    {
        /* @var User $user */
        $user = Auth::user();

        return new UserNotificationCollection($user->notifications);
    }

    public function show(DatabaseNotification $notification): JsonResponse|UserNotificationResource
    {
        /* @var User $user */
        $user = Auth::user();

        if ($user->notifications->contains($notification)) {
            $notification->markAsRead();

            return new UserNotificationResource($notification->refresh());
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function markAllAsRead(): JsonResponse
    {
        /* @var User $user */
        $user = Auth::user();

        $user->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function markNotificationAsRead(DatabaseNotification $notification): JsonResponse
    {
        /* @var User $user */
        $user = Auth::user();

        if ($user->notifications->contains($notification)) {
            $notification->markAsRead();

            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }

}
