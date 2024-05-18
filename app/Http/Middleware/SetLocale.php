<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

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
    
        // Check if the user is authenticated
        if (Auth::check()) {
            // Update the preferred language if the user is authenticated
            $user = Auth::user();
            if ($user->preferred_language !== $requestedLocale) {
                $user->preferred_language = $requestedLocale;
                $user->save();
            }
        } else {
            // If the user is not authenticated, check local storage for the language preference
            $localStorageLanguage = $request->cookie('preferred_language');
            if ($localStorageLanguage !== $requestedLocale) {
                // Update the language in local storage if it's different from the requested locale
                cookie()->queue(cookie('preferred_language', $requestedLocale, 1440)); // Store preferred language in local storage for 24 hours
            }
        }
    
        // Check if the current locale is already set to avoid unnecessary redirection
        if ($requestedLocale !== App::getLocale()) {
            app()->setLocale($requestedLocale);
            
            \URL::defaults(['locale' => $requestedLocale]);
            // dd('Setting locale to: ' . $requestedLocale . " New locale:  " . App::getLocale());
    
        }
    
        return $next($request);
    }
}