<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $status;
    protected $errors;

    public function __construct(
        string $message = 'Api Error',
        int $status = 400,
        array $errors = [],
        int $code = 0
    ) {
        parent::__construct($message, $code);
        $this->status = $status;
        $this->errors = $errors;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
