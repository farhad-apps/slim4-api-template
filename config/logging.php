<?php

return [
    'default' => env('LOG_CHANNEL', 'default'),
    'channels' => [
        'default' => [
            'name' => 'slim-api',
            'path' => PATH_LOGS . DS . 'app.log',
            'level' => env('LOG_LEVEL', 'debug'),
            'max_files' => 30,
        ],
    ],
];
