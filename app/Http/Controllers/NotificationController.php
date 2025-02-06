<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserNotificationCollection;
use App\Http\Resources\UserNotificationResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request): UserNotificationCollection
    {
        /* @var User $user */
        $user = Auth::user();

        switch ($request->string("status")) {
            case "read":
                return new UserNotificationCollection(
                    $user->readNotifications()->paginate()
                );
            case "unread":
                return new UserNotificationCollection(
                    $user->unreadNotifications()->paginate()
                );
            default:
                return new UserNotificationCollection(
                    $user->notifications()->paginate()
                );
        }
    }

    public function show(
        DatabaseNotification $notification
    ): JsonResponse|UserNotificationResource {
        /* @var User $user */
        $user = Auth::user();

        if ($user->notifications->contains($notification)) {
            $notification->markAsRead();

            return new UserNotificationResource($notification->refresh());
        }

        return response()->json(["message" => "Notification not found"], 404);
    }

    public function markAllAsRead(): JsonResponse
    {
        /* @var User $user */
        $user = Auth::user();

        $user->unreadNotifications->markAsRead();

        return response()->json([
            "message" => "All notifications marked as read",
        ]);
    }

    public function markAsRead(DatabaseNotification $notification): JsonResponse
    {
        /* @var User $user */
        $user = Auth::user();

        if ($user->notifications->contains($notification)) {
            $notification->markAsRead();

            return response()->json([
                "message" => "Notification marked as read",
            ]);
        }

        return response()->json(["message" => "Notification not found"], 404);
    }

    public function delete(DatabaseNotification $notification): JsonResponse
    {
        /* @var User $user */
        $user = Auth::user();

        if ($user->notifications->contains($notification)) {
            $notification->delete();

            return response()->json([
                "message" => "Notification deleted",
            ]);
        }

        return response()->json(["message" => "Notification not found"], 404);
    }

    public function deleteMany(Request $request): JsonResponse
    {
        /* @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            "notifications" => ["array"],
            "notifications.*" => ["uuid", "exists:notifications,id"],
        ]);

        $errors = collect();
        $notifications = collect();

        // make sure all notifications exist
        foreach ($data["notifications"] as $notification_id) {
            $notification = DatabaseNotification::query()->firstWhere(
                "id",
                "=",
                $notification_id
            );

            if ($user->notifications->doesntContain($notification)) {
                $errors->push([
                    $notification->id => "not found",
                ]);
            }

            $notifications->push($notification);
        }

        if ($errors->empty()) {
            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return response()->json([
                "message" => "Notifications deleted",
            ]);
        }

        return response()->json(
            [
                "message" => "Error deleting notifications",
                "errors" => $errors->toArray(),
            ],
            404
        );
    }
}
