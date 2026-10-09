<?php

namespace App\Libraries;

class ArrayHelper
{
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function set(array &$array, string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        $current = $value;
    }

    public static function only(array $array, array $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }

    public static function except(array $array, array $keys): array
    {
        return array_diff_key($array, array_flip($keys));
    }

    public static function flatten(array $array, string $separator = '.'): array
    {
        $result = [];
        $iterate = function ($array, $prefix = '') use (&$result, $separator, &$iterate) {
            foreach ($array as $key => $value) {
                $newKey = $prefix ? $prefix . $separator . $key : $key;
                if (is_array($value)) {
                    $iterate($value, $newKey);
                } else {
                    $result[$newKey] = $value;
                }
            }
        };

        $iterate($array);
        return $result;
    }

    public static function merge(array ...$arrays): array
    {
        $result = [];

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
