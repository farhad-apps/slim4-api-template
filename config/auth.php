<?php

return [
    'jwt_secret' => env('JWT_SECRET', 'default-secret'),
    'jwt_ttl' => (int) env('JWT_TTL', 3600),
];
