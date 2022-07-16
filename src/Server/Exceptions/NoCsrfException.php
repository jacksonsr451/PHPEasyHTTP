<?php

namespace PhpEasyHttp\Http\Server\Exceptions;

use Exception;

class NoCsrfException extends Exception
{
    public function __construct($message)
    {
        $this->message = $message;
    }
}
