<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;

class GoogleController extends Controller
{
    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Check if the user with the same email exists and is verified
            $existingUser = User::where('email', $user->getEmail())->whereNotNull('email_verified_at')->first();
    
            if (!$existingUser) {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'email_verified_at' => now(),
                    'password' => Hash::make($user->getName() . '@' . $user->getId()),
                    'google_id' => $user->getId(),
                ]);
    
                Auth::login($newUser);
    
                return redirect()->route('welcome', app()->getLocale());
            } else {
                // Update the existing user's google_id
                $existingUser->update(['google_id' => $user->getId()]);
    
                Auth::login($existingUser);
    
                return redirect()->route('welcome', app()->getLocale());
            }
        } catch (\Throwable $th) {
            // Handle the exception
            throw $th;
        }
    }    
}