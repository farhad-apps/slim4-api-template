<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;

class BaseController
{
    protected function success(
        Response $response,
        array $data = [],
        string $message = 'Success',
        int $status = 200
    ): Response {
        return ResponseHelper::success($response, $data, $message, $status);
    }

    protected function error(
        Response $response,
        string $message = 'Error',
        array $errors = [],
        int $status = 400
    ): Response {
        return ResponseHelper::error($response, $message, $errors, $status);
    }
}
