<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;

class LoggerFactory
{
    public static function create(array $config = []): Logger
    {
        $config = array_merge([
            'name' => 'slim-api',
            'path' => __DIR__ . '/../../storage/logs/app.log',
            'level' => Logger::DEBUG,
            'max_files' => 30,
        ], $config);

        $path = $config['path'];
        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $logger = new Logger($config['name']);
        $logger->pushProcessor(new UidProcessor());

        $handler = new RotatingFileHandler($path, $config['max_files'], $config['level']);
        $handler->setFormatter(new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            'Y-m-d H:i:s',
            true,
            true
        ));

        $logger->pushHandler($handler);

        return $logger;
    }
}
