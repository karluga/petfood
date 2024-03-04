<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $locale = $request->segment(1);

        $user->username = $request->input('username');
        $user->name = $request->input('full_name');
        $user->email = $request->input('email');

        $user->display_name = $request->has('display_name');

        $user->save();

        return redirect()->route('profile', $locale)->with('success', 'Profile updated successfully.');
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'new_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = Auth::user();
        $locale = $request->segment(1);
    
        if ($request->hasFile('new_image')) {
            // Store the new image in the storage directory
            $filename = 'profile_picture_' . time() . '.' . $request->file('new_image')->getClientOriginalExtension();
            $path = $request->file('new_image')->storeAs('profile_pictures', $filename, 'public');
    
            // Check if the new image was stored successfully
            if ($path) {    
                if ($user->filename) {
                    if (Storage::exists('public/profile_pictures/' . $user->filename)) {    
                        Storage::delete('public/profile_pictures/' . $user->filename);
                    } else {
                        Log::warning("Old image not found: " . $user->filename);
                    }
                }

                $user->filename = $filename;
                $user->save();
    
                return redirect()->route('profile', $locale)->with('success', 'Profile picture updated successfully.');
            } else {
                Log::error("Failed to upload new image.");
            }
        }
    
        return redirect()->route('profile', $locale)->with('error', 'Image upload failed.');
    }

    public function changePassword()
    {
        //
    }
}
