<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showProfile()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation Rules
        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'bio' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'interests' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update User Fields
        $user->full_name = $request->full_name;
        $user->date_of_birth = $request->date_of_birth;
        $user->bio = $request->bio;
        $user->location = $request->location;
        $user->interests = explode(',', $request->interests); // Convert to array

        // ✅ Only Update Password if Provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Upload & Update Profile Picture
        if ($request->hasFile('profile_picture')) {
            // Delete Old Profile Picture if Exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('user_profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
