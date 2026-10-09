<?php

namespace App\Libraries;

class Validator
{
    private array $errors = [];

    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function ip(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public static function numeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    public static function integer(mixed $value): bool
    {
        return is_int($value) || (is_string($value) && ctype_digit($value));
    }

    public static function min(mixed $value, int $min): bool
    {
        if (is_string($value)) {
            return strlen($value) >= $min;
        }

        return $value >= $min;
    }

    public static function max(mixed $value, int $max): bool
    {
        if (is_string($value)) {
            return strlen($value) <= $max;
        }

        return $value <= $max;
    }

    public static function between(mixed $value, int $min, int $max): bool
    {
        return self::min($value, $min) && self::max($value, $max);
    }

    public static function regex(string $value, string $pattern): bool
    {
        return preg_match($pattern, $value) > 0;
    }

    public static function uuid(string $value): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        return self::regex($value, $pattern);
    }

    public static function phone(string $phone): bool
    {
        $pattern = '/^[0-9+\-\s()]{7,}$/';
        return self::regex($phone, $pattern);
    }
}
