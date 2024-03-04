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
        $locale = $request->segment(1); // Get the first segment of the URL as $locale

        $user->username = $request->input('username');
        $user->name = $request->input('full_name');
        $user->email = $request->input('email');

        $user->display_name = $request->has('display_name');

        $user->save();

        return redirect()->route('profile', $locale)->with('success', 'Profile updated successfully.');
    }


    use Illuminate\Support\Facades\Log;

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'new_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = Auth::user();
        $locale = $request->segment(1); // Get the first segment of the URL as $locale
    
        if ($request->hasFile('new_image')) {
            // Store the new image in the storage disk
            $filename = 'profile_picture_' . time() . '.' . $request->file('new_image')->getClientOriginalExtension();
            $path = $request->file('new_image')->storeAs('profile_pictures', $filename, 'public');
    
            // Check if the new image was stored successfully
            if ($path) {
                // Log success message
                Log::info("New image uploaded successfully: $filename");
    
                // Delete the old image only if it exists
                if ($user->filename) {
                    // Check if the old image exists before deletion
                    if (Storage::exists('public/profile_pictures/' . $user->filename)) {
                        // Log message before deletion
                        Log::info("Deleting old image: " . $user->filename);
    
                        // Delete the old image
                        Storage::delete('public/profile_pictures/' . $user->filename);
    
                        // Log message after deletion
                        Log::info("Old image deleted: " . $user->filename);
                    } else {
                        // Log message if the old image doesn't exist
                        Log::warning("Old image not found: " . $user->filename);
                    }
                }
    
                // Update the user's filename with the new one
                $user->filename = $filename;
                $user->save();
    
                return redirect()->route('profile', $locale)->with('success', 'Profile picture updated successfully.');
            } else {
                // Log error message if new image upload fails
                Log::error("Failed to upload new image.");
            }
        }
    
        return redirect()->route('profile', $locale)->with('error', 'Image upload failed.');
    }

    public function changePassword()
    {
        // Implementation for changing password
    }
}
