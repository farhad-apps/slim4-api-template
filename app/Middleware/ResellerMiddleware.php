<?php

namespace App\Middleware;

use App\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResellerMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $role = $this->getRole($request);

        if ($role !== 'reseller') {
            throw new ApiException('فقط Reseller می‌تواند این کار را انجام دهد.', 403);
        }

        return $handler->handle($request);
    }

    private function getRole(Request $request): ?string
    {
        return 'reseller'; // نمونه
    }
}
