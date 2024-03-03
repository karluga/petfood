<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\d\s]+$/u',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNotNull('email_verified_at'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => __('The name field is required.'),
            'name.max' => __('The name must not exceed 255 characters.'),
            'name.regex' => __('The name can only contain letters, numbers, and spaces.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('Invalid email format.'),
            'email.max' => __('The email must not exceed 255 characters.'),
            'email.unique' => __('This email is already taken.'),
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least 8 characters long.'),
            'password.confirmed' => __('The password confirmation does not match.'),
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->route('welcome', app()->getLocale())
                            ->withErrors($validator, 'register')
                            ->withInput($request->all());
        }
        $this->create($request->all());
    }
}