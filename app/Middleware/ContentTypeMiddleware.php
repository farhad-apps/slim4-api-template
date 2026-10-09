<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContentTypeMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $response = $handler->handle($request);

        return $response
            ->withHeader('Content-Type', 'application/json; charset=UTF-8')
            ->withHeader('X-Content-Type-Options', 'nosniff');
    }
}
