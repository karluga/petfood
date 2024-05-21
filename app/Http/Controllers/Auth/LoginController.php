<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        $preferredLocale = \Auth::user()->preferred_language ?? app()->getLocale();
        return '/' . $preferredLocale . '/home';
    }

    protected function validateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ], [
            'email.required' => __('The email field is required.'),
            'email.email' => __('Invalid email format.'),
            'email.max' => __('The email must not exceed 255 characters.'),
            'password.required' => __('The password field is required.'),
            'password.max' => __('The password must not exceed 255 characters.'),
        ]);

        if ($validator->fails()) {
            Session::flash('login_errors', $validator->errors());
            return redirect()->route('welcome', app()->getLocale())
                             ->withInput($request->all());
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [
            $this->username() => __('auth.failed'),
        ];

        Session::flash('login_errors', $errors);

        return redirect()->route('welcome', app()->getLocale())
                         ->withInput($request->except('password'));
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
