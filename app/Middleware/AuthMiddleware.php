<?php

namespace App\Middleware;

use App\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader)) {
            throw new ApiException('بدون توکن ارسال نشده است.', 401);
        }

        // Bearer token
        if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
            throw new ApiException('فرمت توکن نادرست است.', 401);
        }

        $token = $matches[1];

        // اینجا می‌تونی توکن رو verify کنی
        if (!$this->validateToken($token)) {
            throw new ApiException('توکن معتبر نیست.', 401);
        }

        return $handler->handle($request);
    }

    private function validateToken(string $token): bool
    {
        // می‌تونی از JWT استفاده کنی
        // برای نمونه: \Firebase\JWT\JWT::decode($token, ...)
        // اینجا فقط نمونه ساده است
        return !empty($token) && strlen($token) > 10;
    }
}
