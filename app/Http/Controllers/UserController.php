<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'user_image' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('user_image')) {
            if ($user->profile_image) {
                Storage::delete($user->profile_image);
            }

            $path = $request->file('user_image')->store('profile_images');

            $user->profile_image = $path;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile image updated successfully.');
    }
}
