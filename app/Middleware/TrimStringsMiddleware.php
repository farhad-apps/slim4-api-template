<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TrimStringsMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $parsedBody = $request->getParsedBody();

        if (is_array($parsedBody)) {
            $parsedBody = $this->trimArray($parsedBody);
            $request = $request->withParsedBody($parsedBody);
        }

        return $handler->handle($request);
    }

    private function trimArray(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $result[$key] = trim($value);
            } elseif (is_array($value)) {
                $result[$key] = $this->trimArray($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
