<?php

namespace App\Http\Controllers;

use App\Mail\FriendRequestMail;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification;

class FriendController extends Controller
{
    public function addFriend($id)
    {
        $receiver = User::find($id);

        if (!$receiver) {
            return redirect()->back()->with('error', 'User not found.');
        }

        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'You cannot send a friend request to yourself.');
        }

        $existingRequest = Friend::where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())->where('friend_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('friend_id', Auth::id())->where('user_id', $id);
        })->first();

        if ($existingRequest) {
            if ($existingRequest->status == 'accepted') {
                return redirect()->back()->with('error', 'You are already friends.');
            } elseif ($existingRequest->status == 'pending') {
                return redirect()->back()->with('error', 'Friend request already sent.');
            } elseif ($existingRequest->status == 'rejected') {
                // ✅ Update rejected request to pending
                $existingRequest->update(['status' => 'pending']);

                // ✅ Send email again when resending request
                Mail::to($receiver->email)->queue(new FriendRequestMail(Auth::user()));

                return redirect()->back()->with('success', 'Friend request resent.');
            }
        } else {
            // ✅ Create a new friend request
            Friend::create([
                'user_id' => Auth::id(),
                'friend_id' => $id,
                'status' => 'pending',
            ]);

            // ✅ Send email for the new request
            Mail::to($receiver->email)->queue(new FriendRequestMail(Auth::user()));
        }

        // ✅ Create a notification for the receiver
        Notification::create([
            'user_id' => $id,
            'sender_id' => Auth::id(),
            'type' => 'friend_request',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Friend request sent!');
    }

    public function respondFriendRequest(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:accepted,rejected']);

        $friendRequest = Friend::where([
            ['friend_id', Auth::id()],
            ['id', $id]
        ])->first();

        if (!$friendRequest) {
            return redirect()->back()->with('error', 'Friend request not found.');
        }

        $friendRequest->update(['status' => $request->status]);

        if ($request->status === 'accepted') {
            $sender = User::find($friendRequest->user_id);
            Mail::to($sender->email)->queue(new FriendRequestMail(Auth::user()));
        }

        // Update notification
        Notification::where('sender_id', $friendRequest->user_id)
            ->where('user_id', Auth::id())
            ->where('type', 'friend_request')
            ->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Friend request ' . $request->status);
    }

    public function removeFriend($id)
    {
        $friend = Friend::where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())->where('friend_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('friend_id', Auth::id())->where('user_id', $id);
        })->first();

        if (!$friend) {
            return redirect()->back()->with('error', 'Friend not found.');
        }

        $friend->delete();

        return redirect()->back()->with('success', 'Friend removed successfully.');
    }
}
