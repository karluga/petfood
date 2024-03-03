<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        Session::forget('errors');

        $locale = $request->segment(1); // Get the locale from the URL

        // Redirect to the 'welcome' route with the locale
        return $request->expectsJson() ? null : route('welcome', $locale);
    }
}
