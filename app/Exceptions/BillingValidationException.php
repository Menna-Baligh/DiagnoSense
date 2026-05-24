<?php

namespace App\Exceptions;

use Exception;

class BillingValidationException extends Exception
{
    public function __construct(
        string $message,
        int $code = 403,
    ) {
        parent::__construct($message, $code);
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }
}
