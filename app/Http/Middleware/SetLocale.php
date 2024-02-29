<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $requestedLocale = $request->segment(1);

        // Get all supported languages from translations.php
        $supportedLanguages = array_keys(Config::get('languages'));

        if (!in_array($requestedLocale, $supportedLanguages)) {
            // If the requested language is not supported, redirect to the default locale
            if ($requestedLocale !== App::getLocale()) {
                return redirect(App::getLocale());
            }
        }

        // Check if the current locale is already set to avoid unnecessary redirection
        if ($requestedLocale !== App::getLocale()) {
            app()->setLocale($requestedLocale);
            \URL::defaults(['locale' => $requestedLocale]);
        }

        return $next($request);
    }
}