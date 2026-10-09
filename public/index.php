<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use App\Exceptions\ApiException;
use App\Helpers\ResponseHelper;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create container
$container = require __DIR__ . '/../bootstrap/container.php';

// Create app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add middleware
$app->addErrorMiddleware(true, true, true);

// Load routes
require __DIR__ . '/../app/Routes/api.php';

// Custom error handler
$app->add(function ($request, $handler) {
    try {
        return $handler->handle($request);
    } catch (ApiException $e) {
        $response = $handler->handle($request)->withStatus($e->getStatus());
        return ResponseHelper::error(
            $response,
            $e->getMessage(),
            $e->getErrors(),
            $e->getStatus()
        );
    } catch (\Throwable $e) {
        $response = $handler->handle($request)->withStatus(500);
        return ResponseHelper::error(
            $response,
            'Internal Server Error',
            [],
            500
        );
    }
});

$app->run();
