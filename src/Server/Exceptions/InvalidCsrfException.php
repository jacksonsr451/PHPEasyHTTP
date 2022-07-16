<?php

namespace PhpEasyHttp\Http\Server\Exceptions;

use Exception;

class InvalidCsrfException extends Exception
{
    public function __construct($message)
    {
        $this->message = $message;
    }
}
