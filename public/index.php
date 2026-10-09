<?php

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

define('PATH', __DIR__ . '/..');
require __DIR__ . '/../bootstrap/constants.php';
require __DIR__ . '/../bootstrap/helpers.php';

use Slim\Factory\AppFactory;
use App\Exceptions\ApiException;
use App\Helpers\ResponseHelper;

$container = require __DIR__ . '/../bootstrap/container.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$routeLoader = require __DIR__ . '/../app/Routes/index.php';
$routeLoader($app);

$app->add(function ($request, $handler) {
    $logger = logger();
    $logger->info('Request received', [
        'method' => $request->getMethod(),
        'uri' => (string) $request->getUri(),
        'ip' => getIpAddress(),
    ]);

    try {
        $response = $handler->handle($request);
        $logger->info('Request completed', [
            'status' => $response->getStatusCode(),
        ]);
        return $response;
    } catch (ApiException $e) {
        $logger->error('API exception', [
            'message' => $e->getMessage(),
            'status' => $e->getStatus(),
            'errors' => $e->getErrors(),
        ]);

        $response = $handler->handle($request)->withStatus($e->getStatus());
        return ResponseHelper::error(
            $response,
            $e->getMessage(),
            $e->getErrors(),
            $e->getStatus()
        );
    } catch (\Throwable $e) {
        $logger->critical('Unhandled exception', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'ip' => getIpAddress(),
        ]);

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
