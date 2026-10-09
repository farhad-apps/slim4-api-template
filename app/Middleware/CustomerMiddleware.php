<?php

namespace App\Middleware;

use App\Exceptions\ApiException;
use App\Helpers\Translator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomerMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $role = $this->getRole($request);

        if ($role !== 'customer') {
            throw new ApiException(Translator::trans('auth.customer_only'), 403);
        }

        return $handler->handle($request);
    }

    private function getRole(Request $request): ?string
    {
        return 'customer';
    }
}
