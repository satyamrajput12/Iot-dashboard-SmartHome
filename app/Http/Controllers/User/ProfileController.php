<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new photo
            $path = $request->file('photo')->store('profile-photos', 'public');
            
            $user->profile_photo_path = $path;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully!');
    }
}
