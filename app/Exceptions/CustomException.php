<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomException extends Exception
{
    const DEFAULT_MESSAGE = 'Something went wrong';

    public function __construct(string $message = "", int $code = 500, ?Throwable $previous = null)
    {
        if ($code == 500) {
            $message = self::DEFAULT_MESSAGE;
        }

        parent::__construct($message, $code, $previous);
    }
}