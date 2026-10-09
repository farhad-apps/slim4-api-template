<?php

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Logging\LoggerFactory;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    'db' => function () {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'slim_api'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    },
    'logger' => function () {
        $config = require __DIR__ . '/../config/logging.php';
        return LoggerFactory::create($config);
    },
]);

return $containerBuilder->build();
