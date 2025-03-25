<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $friends = Friend::where('status', 'accepted')
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere('friend_id', Auth::id());
            })
            ->paginate(5);

        return view('dashboard', compact('friends'));
    }

    public function searchUsers(Request $request)
    {
        if (strlen($request->search) < 3) {
            return response()->json([]);
        }

        $users = User::where('full_name', 'LIKE', '%' . $request->search . '%')
            ->where('id', '!=', Auth::id())
            ->limit(10)
            ->get();

        foreach ($users as $user) {
            $friendship = Friend::where(function ($query) use ($user) {
                $query->where('user_id', Auth::id())->where('friend_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('friend_id', Auth::id())->where('user_id', $user->id);
            })->first();

            if ($friendship) {
                if ($friendship->status == 'pending') {
                    $user->friend_status = 'Request Sent';
                } elseif ($friendship->status == 'accepted') {
                    $user->friend_status = 'Already Friends';
                } elseif ($friendship->status == 'rejected') {
                    $user->friend_status = 'Add Friend';
                }
            } else {
                $user->friend_status = 'Add Friend';
            }
        }

        return response()->json([
            'users' => $users,
            'message' => $users->isEmpty() ? 'No results found' : null,
        ]);
    }
}
