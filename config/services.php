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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'zeptomail' => [
        'api_key' => env('ZEPTOMAIL_API_KEY'),
        'template_key' => env('ZEPTOMAIL_TEMPLATE_KEY', '2518b.1f7e2d7d3e304a0f.k1.4601c050-492a-11f1-b079-62df313bf14d.19dfc8600d5'),
        'from_address' => env('ZEPTOMAIL_FROM_ADDRESS', 'noreply@clonzainfotech.com'),
        'from_name' => env('ZEPTOMAIL_FROM_NAME', 'CISETU'),
        'to_address' => env('ZEPTOMAIL_TO_ADDRESS', 'clonzainfotech@gmail.com'),
        'to_name' => env('ZEPTOMAIL_TO_NAME', 'Clonza'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'timeout' => env('OPENAI_TIMEOUT', 120),
    ],

];
