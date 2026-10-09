<?php

return [
    'default' => env('CACHE_DRIVER', 'file'),
    'ttl' => (int) env('CACHE_TTL', 60),
];
