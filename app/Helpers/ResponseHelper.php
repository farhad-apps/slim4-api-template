<?php

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseHelper
{
    public static function success(
        Response $response,
        array $data = [],
        string $message = 'Success',
        int $status = 200
    ): Response {
        $payload = json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    public static function error(
        Response $response,
        string $message = 'Error',
        array $errors = [],
        int $status = 400
    ): Response {
        $payload = json_encode([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
