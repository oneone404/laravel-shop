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
    // 'google' => [
    //     'client_id' => config_get('login_social.google.client_id', ''),
    //     'client_secret' => config_get('login_social.google.client_secret', ''),
    //     'redirect' => config_get('login_social.google.redirect', ''),
    // ],
    // 'facebook' => [
    //     'client_id' => config_get('login_social.facebook.client_id', ''),
    //     'client_secret' => config_get('login_social.facebook.client_secret', ''),
    //     'redirect' => config_get('login_social.facebook.redirect', ''),
    // ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT', 'https://accone.vn/auth/google/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT', 'https://accone.vn/auth/facebook/callback'),
    ],

    'pay2s' => [
        'api_base' => env('PAY2S_API_BASE', 'https://my.pay2s.vn'),
        'transactions_path' => env('PAY2S_TRANSACTIONS_PATH', '/userapi/transactions'),
        'min_amount' => env('PAY2S_MIN_AMOUNT', 10000),
        'timezone' => env('PAY2S_TZ', 'Asia/Ho_Chi_Minh'),
        'token' => env('PAY2S_TOKEN'), // <-- token user-level
    ],

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],
];
