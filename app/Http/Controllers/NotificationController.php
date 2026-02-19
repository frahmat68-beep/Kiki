<?php

namespace App\Http\Controllers;

use App\Models\OrderNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(Request $request, OrderNotification $notification): JsonResponse
    {
        if ((int) $notification->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        if ($notification->read_at === null) {
            $notification->forceFill([
                'read_at' => now(),
            ])->save();
        }

        $unreadCount = (int) OrderNotification::query()
            ->where('user_id', (int) $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'ok' => true,
            'unread_count' => $unreadCount,
        ]);
    }
}
