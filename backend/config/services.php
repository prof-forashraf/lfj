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
    'metal_api' => [
        'key' => env('METAL_API_KEY', env('GOLD_API_KEY')),
        'base_url' => env('GOLDAPI_BASE_URL', 'https://www.goldapi.io/api'),
        'circuit_breaker' => [
            'failure_threshold' => env('METAL_API_CIRCUIT_BREAKER_FAILURE_THRESHOLD', 5),
            'cooldown_minutes' => env('METAL_API_CIRCUIT_BREAKER_COOLDOWN_MINUTES', 15),
        ],
    ],
    'metal_sync' => [
        'stale_threshold_minutes' => env('METAL_SYNC_STALE_THRESHOLD_MINUTES', 30),
        'alert_webhook' => env('METAL_SYNC_ALERT_WEBHOOK'),
        'queue_name' => env('METAL_SYNC_QUEUE_NAME', 'metal-sync'),
        'maintenance_queue' => env('METAL_SYNC_MAINTENANCE_QUEUE', 'maintenance'),
        'lock_ttl_seconds' => env('METAL_SYNC_LOCK_TTL_SECONDS', 900),
        'lock_wait_seconds' => env('METAL_SYNC_LOCK_WAIT_SECONDS', 10),
        'retention' => [
            'daily_metal_prices_days' => env('METAL_SYNC_RETENTION_DAILY_METAL_PRICES_DAYS', 365),
            'gold_prices_days' => env('METAL_SYNC_RETENTION_GOLD_PRICES_DAYS', 90),
        ],
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

];
