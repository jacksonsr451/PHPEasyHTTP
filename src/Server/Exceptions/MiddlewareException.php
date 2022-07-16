<?php

namespace PhpEasyHttp\Http\Server\Exceptions;

use Exception;

class MiddlewareException extends Exception
{
    public function __construct($message = "Exception in middleware")
    {
        $this->message = $message;
    }
}
