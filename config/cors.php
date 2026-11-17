<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_origins' => array_values(array_filter(array_map('trim', explode(',',
        env('CORS_ALLOWED_ORIGINS', env('APP_ENV', 'local') === 'local' ? '*' : env('APP_URL', ''))
    )))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Accept', 'Authorization', 'Content-Type', 'Origin', 'X-Requested-With', 'X-XSRF-TOKEN'],
    'exposed_headers' => ['Content-Disposition'],
    'max_age' => 3600,
    'supports_credentials' => false,
];
