<?php

return [
    'name' => 'slim-api',
    'path' => __DIR__ . '/../storage/logs/app.log',
    'level' => \Monolog\Logger::DEBUG,
    'max_files' => 30,
];
