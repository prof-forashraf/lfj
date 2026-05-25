<?php // config/cors.php

$defaultOrigins = array_filter(
    array_unique(
        array_map(
            'trim',
            explode(',', env('CORS_ALLOWED_ORIGINS', env('APP_URL', 'http://localhost:8000')))
        )
    )
);

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // ✅ RESTRICTED: Only allow specific safe HTTP methods
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // ✅ CRITICAL: Configure allowed origins from environment (MUST be set in .env)
    'allowed_origins' => $defaultOrigins,

    'allowed_origins_patterns' => [],

    // ✅ RESTRICTED: Only allow essential headers
    'allowed_headers' => ['Content-Type', 'X-XSRF-TOKEN', 'Authorization', 'Accept', 'X-Requested-With', 'Origin'],

    'exposed_headers' => ['X-Total-Count', 'X-Page-Count'],

    // ✅ Cache preflight requests for 1 hour for better performance
    'max_age' => 3600,

    // ✅ IMPORTANT for Sanctum SPA authentication
    'supports_credentials' => true,

];
