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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'tiktok' => [
        'pixel_id'     => env('TIKTOK_PIXEL_ID'),
        'access_token' => env('TIKTOK_ACCESS_TOKEN'),
        'endpoint'     => env('TIKTOK_API_ENDPOINT', 'https://business-api.tiktok.com/open_api/v1.3/event/track/'),
        // Set TIKTOK_TEST_EVENT_CODE while validating in Events Manager > Test Events.
        'test_event_code' => env('TIKTOK_TEST_EVENT_CODE'),
        'enabled'      => env('TIKTOK_EVENTS_ENABLED', true),
    ],

    'meta' => [
        'pixel_id' => env('META_PIXEL_ID', '1675737636873475'),
        // Every fbq event that carries a value must declare the same currency the
        // ad account is billed in, or Meta rejects the conversion.
        'currency' => env('META_CURRENCY', 'USD'),
    ],

];
