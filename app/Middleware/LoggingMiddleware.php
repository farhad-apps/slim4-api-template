<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoggingMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();
        $startTime = microtime(true);

        $response = $handler->handle($request);

        $duration = microtime(true) - $startTime;
        $statusCode = $response->getStatusCode();

        // لاگ کردن
        $this->log(sprintf(
            '[%s] %s %s - Status: %d - Duration: %.3fs',
            date('Y-m-d H:i:s'),
            $method,
            $path,
            $statusCode,
            $duration
        ));

        return $response;
    }

    private function log(string $message): void
    {
        $logFile = __DIR__ . '/../../storage/logs/api.log';
        $dir = dirname($logFile);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($logFile, $message . PHP_EOL, FILE_APPEND);
    }
}
