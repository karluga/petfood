<?php

return [
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'inputs' => [
        'text_fields' => [
            'email' => [
                'placeholder' => 'Enter your email',
                'name' => 'Email',
            ],
            'password' => [
                'placeholder' => 'Enter your password',
                'name' => 'Password',
            ],
            'password_repeat' => [
                'placeholder' => 'Repeat Password...',
                'name' => 'Repeat Password...',
            ],
            'name' => [
                'placeholder' => 'Enter your name',
                'name' => 'Name',
            ]
        ],
        'checkboxes' => [
            'remember_me' => 'Remember me',
        ],
    ],
    'buttons' => [
        'login' => 'Log In',
        'signup' => 'Sign Up'
    ],
    'links' => [
        'forgot_password' => 'Forgot your password?',
    ],
];
