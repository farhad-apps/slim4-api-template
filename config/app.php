<?php

return [
    'app' => [
        'name' => env('APP_NAME', 'Slim API Template'),
        'env' => env('APP_ENV', 'local'),
        'debug' => filter_var(env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
        'url' => env('APP_URL', 'http://localhost:8008'),
        'timezone' => env('APP_TIMEZONE', 'UTC'),
        'locale' => env('APP_LOCALE', 'en'),
    ],
    'database' => [
        'default' => env('DB_CONNECTION', 'mysql'),
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', 3306),
                'database' => env('DB_DATABASE', 'slim_api'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ],
        ],
    ],
    'logging' => [
        'default' => env('LOG_CHANNEL', 'default'),
        'channels' => [
            'default' => [
                'name' => 'slim-api',
                'path' => PATH_LOGS . DS . 'app.log',
                'level' => env('LOG_LEVEL', 'debug'),
                'max_files' => 30,
            ],
        ],
    ],
    'cache' => [
        'default' => env('CACHE_DRIVER', 'file'),
        'ttl' => (int) env('CACHE_TTL', 60),
    ],
    'auth' => [
        'jwt_secret' => env('JWT_SECRET', 'default-secret'),
        'jwt_ttl' => (int) env('JWT_TTL', 3600),
    ],
    'api' => [
        'version' => env('API_VERSION', 'v1'),
        'prefix' => env('API_PREFIX', '/api'),
        'pagination_limit' => (int) env('API_PAGINATION_LIMIT', 15),
    ],
];
