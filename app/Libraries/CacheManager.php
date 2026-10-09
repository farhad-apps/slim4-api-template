<?php

namespace App\Libraries;

class CacheManager
{
    private string $cacheDir = '';

    public function __construct(string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?? sys_get_temp_dir() . '/cache';
        $this->ensureCacheDir();
    }

    public function get(string $key): mixed
    {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return null;
        }

        $data = json_decode(file_get_contents($file), true);

        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public function put(string $key, mixed $value, int $minutes = 60): void
    {
        $file = $this->getFilePath($key);
        $data = [
            'value' => $value,
            'expires' => time() + ($minutes * 60),
        ];

        file_put_contents($file, json_encode($data));
    }

    public function forget(string $key): void
    {
        $file = $this->getFilePath($key);

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function flush(): void
    {
        $files = glob($this->cacheDir . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private function getFilePath(string $key): string
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }

    private function ensureCacheDir(): void
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }
}
