<?php

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        if (strtolower((string) $value) === 'true') {
            return true;
        }

        if (strtolower((string) $value) === 'false') {
            return false;
        }

        if (is_numeric($value)) {
            return $value + 0;
        }

        return $value;
    }
}

if (!function_exists('config')) {
    function config(string $key = null, mixed $default = null): mixed
    {
        static $configs = [];

        if (empty($configs)) {
            $configDir = __DIR__ . '/../config';
            $files = glob($configDir . '/*.php');

            foreach ($files as $file) {
                $name = basename($file, '.php');
                $configs[$name] = require $file;
            }
        }

        if ($key === null) {
            return $configs;
        }

        $segments = explode('.', $key);
        $value = $configs;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('configs')) {
    function configs(): array
    {
        return config();
    }
}

if (!function_exists('path')) {
    function path(string $key, mixed $default = null): mixed
    {
        $paths = [
            'root' => PATH_ROOT,
            'app' => PATH_APP,
            'assets' => PATH_ASSETS,
            'config' => PATH_CONFIGS,
            'storage' => PATH_STORAGE,
            'public' => PATH_PUBLIC,
            'bootstrap' => PATH_BOOTSTRAP,
            'logs' => PATH_LOGS,
            'cache' => PATH_CACHE,
            'temp' => PATH_TEMP,
            'sessions' => PATH_SESSIONS,
        ];

        return $paths[$key] ?? $default;
    }
}

if (!function_exists('getIpAddress')) {
    function getIpAddress(): string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                return $ip;
            }
        }

        return '0.0.0.0';
    }
}

if (!function_exists('now')) {
    function now(): int
    {
        return time();
    }
}

if (!function_exists('uuid')) {
    function uuid(): string
    {
        return strtolower(str_replace(['-', ' '], '', preg_replace('/[^A-Za-z0-9]/', '', microtime() . uniqid('', true))));
    }
}

if (!function_exists('logger')) {
    function logger(): mixed
    {
        static $logger = null;

        if ($logger === null) {
            $config = config('logging');
            $logger = new \App\Logging\LoggerFactory();
            $logger = $logger::create($config['channels'][$config['default']] ?? []);
        }

        return $logger;
    }
}

if (!function_exists('container')) {
    function container(): mixed
    {
        static $container = null;

        if ($container === null) {
            $container = require __DIR__ . '/../bootstrap/container.php';
        }

        return $container;
    }
}
