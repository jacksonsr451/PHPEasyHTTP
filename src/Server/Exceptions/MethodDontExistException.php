<?php

namespace PhpEasyHttp\Http\Server\Exceptions;

use Exception;

class MethodDontExistException extends Exception
{
    public function __construct($message = "")
    {
        $this->message = $message;
    }
}
