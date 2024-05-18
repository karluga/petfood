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
        $preferredLocale =  \Auth::user()->preferred_language ?? app()->getLocale();
        return '/' . $preferredLocale . '/home';
    }
    protected function validateLogin(Request $request)
    {
        Session::forget('errors');

        $validator = $this->validator($request->all(), 'login');

        if ($validator->fails()) {
            return redirect()->route('welcome', app()->getLocale())
                             ->withErrors($validator, 'login')
                             ->withInput($request->all());
        }
    }

    protected function validator(array $data, $type)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ];

        $messages = [
            'email.required' => __('The email field is required.'),
            'email.email' => __('Invalid email format.'),
            'email.max' => __('The email must not exceed 255 characters.'),
            'password.required' => __('The password field is required.'),
            'password.max' => __('The password must not exceed 255 characters.'),
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        Session::forget('errors');

        $errors = [
            $this->username() => __('auth.failed'),
        ];
    
        return redirect()->route('welcome', app()->getLocale())
            ->withErrors($errors, 'login')
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
