<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @tags Notifications
 */
class NotificationController extends Controller
{
    /**
     * List the authenticated user's notifications.
     *
     * Returns notifications ordered by most recent first.
     *
     * @queryParam unread_only boolean Show only unread notifications. Example: true
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->when(
                $request->boolean('unread_only'),
                fn ($q) => $q->where('is_read', false)
            )
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'data' => NotificationResource::collection($notifications->items()),
            'meta' => [
                'total'        => $notifications->total(),
                'unread_count' => $request->user()->notifications()->where('is_read', false)->count(),
                'per_page'     => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
            ],
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * Sets is_read to true and records the read timestamp.
     */
    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read.',
            'data'    => new NotificationResource($notification),
        ]);
    }

    /**
     * Mark all notifications as read.
     *
     * Bulk updates all unread notifications for the authenticated user.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $count = $request->user()
            ->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'message'       => 'All notifications marked as read.',
            'updated_count' => $count,
        ]);
    }
}
