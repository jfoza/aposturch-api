<?php

return [
    'jwt' => [
        'ttl' =>  env('JWT_TTL', 2880),
    ],

    'rate_limiting' => [
        'max_attempts' => env('RATE_LIMITING', 100),
    ],

    'app_url' => env('APP_URL'),
    'app_web_url' => env('APP_WEB_URL'),
];
