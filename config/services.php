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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'payment' => [
        'jazzcash' => [
            'title' => env('JAZZCASH_ACCOUNT_TITLE', 'Al Hayat Kids'),
            'number' => env('JAZZCASH_ACCOUNT_NUMBER', '03249171213'),
        ],
        'easypaisa' => [
            'title' => env('EASYPAISA_ACCOUNT_TITLE', 'Al Hayat Kids'),
            'number' => env('EASYPAISA_ACCOUNT_NUMBER', '03249171213'),
        ],
        'bank' => [
            'bank_name' => env('BANK_NAME', 'Meezan Bank Limited'),
            'title' => env('BANK_ACCOUNT_TITLE', 'Al Hayat Kids'),
            'account_number' => env('BANK_ACCOUNT_NUMBER', '03249171213'),
            'iban' => env('BANK_IBAN', 'PK36MEZN03249171213'),
        ],
        'stripe' => [
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'currency' => env('STRIPE_CURRENCY', 'PKR'),
        ],
    ],

];
