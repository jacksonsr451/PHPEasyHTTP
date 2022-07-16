<?php

namespace PhpEasyHttp\Http\Server\Exceptions;

use Exception;

class ClassDontExistException extends Exception
{
    public function __construct($message = "")
    {
        $this->message = $message;
    }
}
