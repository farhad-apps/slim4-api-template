<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use App\Exceptions\ApiException;
use App\Helpers\ResponseHelper;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$container = require __DIR__ . '/../bootstrap/container.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    $logger = $this->get('logger');
    $logger->info('Root route accessed');

    $response->getBody()->write(json_encode([
        'status' => 'success',
        'message' => 'API is running',
    ]));

    return $response->withHeader('Content-Type', 'application/json');
});

$routeFiles = [
    __DIR__ . '/../app/Routes/api.php',
    __DIR__ . '/../app/Routes/admin.php',
    __DIR__ . '/../app/Routes/reseller.php',
    __DIR__ . '/../app/Routes/customer.php',
];

foreach ($routeFiles as $routeFile) {
    if (file_exists($routeFile)) {
        $routeLoader = require $routeFile;
        $routeLoader($app);
    }
}

$app->add(function ($request, $handler) {
    $logger = $this->get('logger');
    $logger->info('Request received', [
        'method' => $request->getMethod(),
        'uri' => (string) $request->getUri(),
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
