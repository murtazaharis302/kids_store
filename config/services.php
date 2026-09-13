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
            'title' => env('JAZZCASH_ACCOUNT_TITLE', 'AH Kids Store'),
            'number' => env('JAZZCASH_ACCOUNT_NUMBER', '03001234567'),
        ],
        'easypaisa' => [
            'title' => env('EASYPAISA_ACCOUNT_TITLE', 'AH Kids Store'),
            'number' => env('EASYPAISA_ACCOUNT_NUMBER', '03007654321'),
        ],
        'bank' => [
            'bank_name' => env('BANK_NAME', 'Meezan Bank Limited'),
            'title' => env('BANK_ACCOUNT_TITLE', 'AH Kids Store'),
            'account_number' => env('BANK_ACCOUNT_NUMBER', '01020304050607'),
            'iban' => env('BANK_IBAN', 'PK36MEZN0001020304050607'),
        ],
        'stripe' => [
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'currency' => env('STRIPE_CURRENCY', 'PKR'),
        ],
    ],

];
