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
        // admin role == 1
        // user role == 0
        if(\Auth::check()) {
            if(\Auth::user()->role == 1) {
                // dd('logged in as admin');
                return $next($request);
            } else {
                // dd('logged in as user');
                return redirect('/home')->with('message', 'Access Denied as you are not Admin!');
            }
        } else {
            // Not logged in
            return redirect('/login')->with('message', 'Log in to access the website info.');
        }
        return $next($request); // Don't worry about this, any other return statement will do its job
    }
}