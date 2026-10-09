<?php

namespace App\Middleware;

use App\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AdminMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        // از token یا session دریافت کنید
        // فرض می‌کنیم role = 'admin' است
        $role = $this->getRole($request);

        if ($role !== 'admin') {
            throw new ApiException('فقط Admin می‌تواند این کار را انجام دهد.', 403);
        }

        return $handler->handle($request);
    }

    private function getRole(Request $request): ?string
    {
        // در واقعیت از JWT token یا session دریافت می‌شود
        return 'admin'; // نمونه
    }
}
