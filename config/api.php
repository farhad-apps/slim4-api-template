<?php

return [
    'version' => env('API_VERSION', 'v1'),
    'prefix' => env('API_PREFIX', '/api'),
    'pagination_limit' => (int) env('API_PAGINATION_LIMIT', 15),
];
