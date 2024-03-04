<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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


    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'new_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = Auth::user();
        $locale = $request->segment(1); // Get the first segment of the URL as $locale
    
        if ($request->hasFile('new_image')) {
            // Delete the old image only if it exists
            if ($user->filename) {
                // Debug: Output the filename before deletion
                echo "Deleting old image: " . $user->filename . PHP_EOL;
    
                // Delete the old image
                Storage::delete('profile_pictures/' . $user->filename);
    
                // Debug: Output a message after deletion
                dd( "Old image deleted." . PHP_EOL);
            }
    
            // Store the new image in the storage disk
            $filename = 'profile_picture_' . time() . '.' . $request->file('new_image')->getClientOriginalExtension();
            $path = $request->file('new_image')->storeAs('profile_pictures', $filename, 'public');
    
            // Update the user's filename with the new one
            $user->filename = $filename;
            $user->save();
    
            return redirect()->route('profile', $locale)->with('success', 'Profile picture updated successfully.');
        }
    
        return redirect()->route('profile', $locale)->with('error', 'Image upload failed.');
    }
    

    

    public function changePassword()
    {
        // Implementation for changing password
    }
}
