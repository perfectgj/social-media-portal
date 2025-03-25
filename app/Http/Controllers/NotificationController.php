<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        return response()->json($notifications);
    }

    public function respondNotification(Request $request, $id)
    {
        $notification = Notification::find($id);

        if (!$notification || $notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $status = $request->status;
        $friendRequest = Friend::where([
            ['user_id', $notification->sender_id],
            ['friend_id', Auth::id()]
        ])->first();

        if ($friendRequest) {
            $friendRequest->update(['status' => $status]);
        }

        // Update notification status
        $notification->update(['status' => $status]);

        return response()->json(['message' => 'Friend request ' . $status]);
    }
}
