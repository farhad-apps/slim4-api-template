<?php

namespace App\Libraries;

class StringHelper
{
    public static function slug(string $text, string $separator = '-'): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', $separator, $text);
        return trim($text, $separator);
    }

    public static function camelCase(string $text, string $separator = '_'): string
    {
        $words = explode($separator, strtolower($text));
        $result = array_shift($words);

        foreach ($words as $word) {
            $result .= ucfirst($word);
        }

        return $result;
    }

    public static function snakeCase(string $text): string
    {
        $text = preg_replace('/([A-Z])/', '_$1', $text);
        return strtolower(trim($text, '_'));
    }

    public static function truncate(string $text, int $length, string $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length - strlen($suffix)) . $suffix;
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) === 0;
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }

    public static function sanitize(string $text): string
    {
        return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
    }
}
