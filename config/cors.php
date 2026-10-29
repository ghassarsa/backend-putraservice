<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Cross-Origin Resource Sharing
    | (CORS). This determines whether cross-origin requests should be allowed
    | and what headers/methods are permitted. By default, CORS is disabled.
    |
    */

    'paths' => [
        'api/*',
        'sanctum/*',
        'graphql/*',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 86400,

    'supports_credentials' => true,

];