<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

    // Custom error messages
    $messages = [
        'username.required_without_all' => 'Either email or username must be filled.',
        'email.required_without_all' => 'Either email or username must be filled.',
        'email.email' => 'The email must be a valid email address.',
        'full_name.required_if' => 'Full name is required when display name is set.',
    ];

    // Validation rules
    $request->validate([
        'username' => 'required_without_all:email',
        'email' => 'required_without_all:username|email',
        'full_name' => 'required_if:display_name,true',
    ], $messages);

    $user->username = $request->input('username');
    $user->name = $request->input('full_name');
    $user->email = $request->input('email');

    // Check if the email is being updated
    if ($user->email !== $request->input('email')) {
        $user->email_verified_at = null; // Reset email verification status
    }

    $user->display_name = $request->has('display_name');

    // Additional check for display_name and full_name
    if ($user->display_name && empty($user->name)) {
        return redirect()->back()->with('error', 'Full name is required when display name is set.');
    }

    // Additional check for either email or username filled
    if (empty($user->email) && empty($user->username)) {
        return redirect()->back()->with('error', 'Either email or username must be filled.');
    }

    $user->save();

    return redirect()->route('profile', $locale)->with('success', 'Profile updated successfully.');
}


    public function uploadImage(Request $request)
    {
        try {
            $this->validate($request, [
                'new_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Maximum size in kilobytes (2MB)
            ], [
                'new_image.image' => __('The uploaded file must be an image.'),
                'new_image.mimes' => __('Only JPEG, PNG, JPG, SVG, and GIF formats are allowed.'),
                'new_image.max' => __('The uploaded file may not be greater than 2MB.'),
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->getMessageBag()->getMessages();
            if (isset($errors['new_image'])) {
                return redirect()->route('profile', $request->segment(1))->with('error', $errors['new_image'][0]);
            }
        }
    
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
    

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the form input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        // Verify current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update the user's password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
    public function delete(Request $request)
    {
        // Check if the confirmation phrase is provided and matches
        $confirmation = $request->input('confirmation');
        if ($confirmation !== 'petfood') {
            throw ValidationException::withMessages(['confirmation' => 'Invalid confirmation phrase.']);
        }
    
        $user = Auth::user();
    
        // Delete user's profile picture if it exists
        if ($user->filename && Storage::exists('public/profile_pictures/' . $user->filename)) {
            Storage::delete('public/profile_pictures/' . $user->filename);
        }
    
        // Delete the user
        $user->delete();
    
        // Logout the user and redirect
        Auth::logout();

        return redirect()->route('welcome', ['locale' => app()->getLocale()])->with('success', 'Account deleted.');
    }
}
