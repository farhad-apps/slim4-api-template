<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonParserMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (strpos($contentType, 'application/json') !== false) {
            $body = (string) $request->getBody();
            $parsedBody = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $parsedBody = [];
            }

            $request = $request->withParsedBody($parsedBody ?? []);
        }

        return $handler->handle($request);
    }
}
