<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '737570168440813',
        'client_secret' => '41e2e65660a3427b278ab62c868cd18d',
        'redirect' => 'https://2607-2a03-ec00-b19b-19d4-14d2-8d38-f5fa-4400.ngrok-free.app/facebook/callback/'
    ],

    'google' => [
        'client_id' => '155991691238-3n721gc012t2q7co6dlj3ds86eh48fod.apps.googleusercontent.com',
        'client_secret' => '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', 
        'redirect' => 'https://2607-2a03-ec00-b19b-19d4-14d2-8d38-f5fa-4400.ngrok-free.app/google/callback/'
    ],

];
