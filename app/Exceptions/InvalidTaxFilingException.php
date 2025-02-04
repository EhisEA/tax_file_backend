<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidTaxFilingException extends Exception
{
    protected  array $errors;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, $errors = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function report() {
        return response()->json($this->errors, 422);
    }
}
