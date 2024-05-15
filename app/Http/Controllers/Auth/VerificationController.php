<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        // Your custom verification logic here

        $user = $request->user();

        // Check if the URL is valid
        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            abort(403, 'Invalid user ID');
        }

        // Check if the hash is valid
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification hash');
        }

        // Check if the user is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        // Mark the user as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Your custom redirect logic after verification
        return redirect($this->redirectPath())->with('verified', true);
    }
}
