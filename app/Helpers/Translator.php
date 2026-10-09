<?php

namespace App\Helpers;

class Translator
{
    public static function getLocale(?string $locale = null): string
    {
        $headerLocale = null;

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $headerLocale = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
        }

        $candidate = $locale ?? $headerLocale ?? 'en';

        return in_array($candidate, ['en', 'fa'], true) ? $candidate : 'en';
    }

    public static function trans(string $key, ?string $locale = null, array $replace = []): string
    {
        $locale = self::getLocale($locale);
        $path = __DIR__ . '/../Lang/' . $locale . '/messages.php';

        if (!file_exists($path)) {
            $path = __DIR__ . '/../Lang/en/messages.php';
        }

        $messages = require $path;
        $message = $messages[$key] ?? $key;

        foreach ($replace as $search => $value) {
            $message = str_replace(':' . $search, (string) $value, $message);
        }

        return $message;
    }
}
