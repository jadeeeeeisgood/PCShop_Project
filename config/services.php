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

    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', 'V22NS9SB'),
        'hash_secret' => env('VNPAY_HASH_SECRET', 'Z4WJQGCQF2FNJL8A4V23K4TQF3FQ5FG4'),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', env('APP_URL', 'http://localhost') . '/payment/vnpay/callback'),
    ],

    'pusher' => [
        'app_id' => env('PUSHER_APP_ID', ''),
        'key' => env('PUSHER_APP_KEY', ''),
        'secret' => env('PUSHER_APP_SECRET', ''),
        'cluster' => env('PUSHER_APP_CLUSTER', 'ap1'),
    ],

];
