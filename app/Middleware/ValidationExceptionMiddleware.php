<?php

namespace App\Middleware;

use App\Exceptions\ApiException;
use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        try {
            return $handler->handle($request);
        } catch (ApiException $e) {
            return ResponseHelper::error(
                $handler->handle($request),
                $e->getMessage(),
                $e->getErrors(),
                $e->getStatus()
            );
        }
    }
}
