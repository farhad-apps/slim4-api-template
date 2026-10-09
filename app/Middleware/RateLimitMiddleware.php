<?php

namespace App\Middleware;

use App\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RateLimitMiddleware implements MiddlewareInterface
{
    private int $maxRequests = 100;
    private int $windowSeconds = 3600;

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $ip = $this->getClientIp($request);
        $cacheKey = 'rate_limit:' . $ip;

        $current = $this->getRequestCount($cacheKey);

        if ($current >= $this->maxRequests) {
            throw new ApiException(
                'محدودیت درخواست‌ها فراتر رفت.',
                429
            );
        }

        $this->incrementRequestCount($cacheKey);

        return $handler->handle($request);
    }

    private function getClientIp(Request $request): string
    {
        if ($request->hasHeader('CF-Connecting-IP')) {
            return $request->getHeaderLine('CF-Connecting-IP');
        }

        if ($request->hasHeader('X-Forwarded-For')) {
            return explode(',', $request->getHeaderLine('X-Forwarded-For'))[0];
        }

        return $request->getServerParams()['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function getRequestCount(string $key): int
    {
        // می‌تونی از Redis یا Memcached استفاده کنی
        // برای نمونه، از فایل استفاده می‌کنیم
        $file = sys_get_temp_dir() . '/' . md5($key);

        if (!file_exists($file)) {
            return 0;
        }

        $data = unserialize(file_get_contents($file));

        if ($data['expires'] < time()) {
            unlink($file);
            return 0;
        }

        return $data['count'] ?? 0;
    }

    private function incrementRequestCount(string $key): void
    {
        $file = sys_get_temp_dir() . '/' . md5($key);
        $data = [];

        if (file_exists($file)) {
            $data = unserialize(file_get_contents($file));
        }

        $data['count'] = ($data['count'] ?? 0) + 1;
        $data['expires'] = time() + $this->windowSeconds;

        file_put_contents($file, serialize($data));
    }
}
