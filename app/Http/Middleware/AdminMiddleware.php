<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (\Auth::check()) {
            $user = \Auth::user();
            
            // Check if the user has admin role
            $isAdmin = \DB::table('role_user')
                        ->where('user_id', $user->id)
                        ->where('role_id', 1)
                        ->exists();

            if ($isAdmin) {
                // If the user is an admin, allow access to the requested route
                return $next($request);
            } else {
                $preferredLocale = $user->preferred_language ?? app()->getLocale();
                return redirect("/$preferredLocale/home")->with('message', 'Access Denied as you are not Admin!');
            }
        } else {
            return redirect('/login')->with('message', 'Log in to access the website info.');
        }
    }
}