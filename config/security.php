<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | This file is for storing security related configuration values.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Password Settings
    |--------------------------------------------------------------------------
    |
    | Here you may configure the password settings for your application.
    |
    */
    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 12),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_symbols' => env('PASSWORD_REQUIRE_SYMBOLS', true),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'expire_days' => env('PASSWORD_EXPIRE_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Login Settings
    |--------------------------------------------------------------------------
    |
    | Here you may configure the login settings for your application.
    |
    */
    'login' => [
        'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
        'throttle_minutes' => env('LOGIN_THROTTLE_MINUTES', 15),
        'throttle_key' => 'login',
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Here you may configure the two-factor authentication settings.
    |
    */
    '2fa' => [
        'enabled' => env('2FA_ENABLED', true),
        'issuer' => env('APP_NAME', 'Laravel'),
        'window' => 4, // Allow 4 codes before/after current time
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the security headers for your application.
    |
    */
    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'strict_transport_security' => 'max-age=31536000; includeSubDomains',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'geolocation=(), microphone=()',
        'content_security_policy' => [
            'default-src' => ["'self'"],
            'script-src' => ["'self'"],
            'style-src' => ["'self'"],
            'img-src' => ["'self'"],
            'font-src' => ["'self'"],
            'connect-src' => ["'self'"],
            'media-src' => ["'self'"],
            'object-src' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'none'"],
            'upgrade-insecure-requests' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Settings
    |--------------------------------------------------------------------------
    |
    | Here you may configure the session settings for your application.
    |
    */
    'session' => [
        'lifetime' => env('SESSION_LIFETIME', 120),
        'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
        'encrypt' => env('SESSION_ENCRYPT', false),
        'secure' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => env('SESSION_HTTP_ONLY', true),
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Settings
    |--------------------------------------------------------------------------
    |
    | Here you may configure CORS settings for your application.
    |
    */
    'cors' => [
        'paths' => ['api/*'],
        'allowed_methods' => ['*'],
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '')),
        'allowed_origins_patterns' => [],
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => false,
    ],
];
