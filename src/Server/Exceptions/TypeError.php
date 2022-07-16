<?php

namespace PhpEasyHttp\Http\Server\Exceptions;

use Exception;

class TypeError extends Exception
{
    public function __construct($message = "Type Error message")
    {
        $this->message = $message;
    }
}
