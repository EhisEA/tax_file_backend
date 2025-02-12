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
use Illuminate\Support\Facades\Log;

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

    public function delete(Request $request): JsonResponse
    {
        /* @var User $user */
        $user = Auth::user();

        $notification_ids = $request->array("notifications");

        $errors = collect();
        $notifications = collect();

        foreach ($notification_ids as $notification_id) {
            $notification = $user->notifications->first(
                fn($notification) => $notification->id === $notification_id
            );

            if ($notification === null) {
                $errors->push($notification_id);
                continue;
            }

            $notifications->push($notification->id);
        }

        if ($errors->isNotEmpty()) {
            return response()->json(
                [
                    "message" => "The following notifications are not found",
                    "errors" => $errors->toArray(),
                ],
                404
            );
        }

        $rows = DatabaseNotification::query()
            ->whereIn("id", $notifications->toArray())
            ->delete();

        return response()->json([
            "message" => "Deleted {$rows} Notification(s)",
        ]);
    }
}
