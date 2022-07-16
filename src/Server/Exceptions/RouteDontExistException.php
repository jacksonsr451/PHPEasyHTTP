<?php

namespace PhpEasyHttp\Http\Server\Exceptions;

use Exception;

class RouteDontExistException extends Exception
{
    public function __construct($message = "Route dont exist exception!")
    {
        $this->message = $message;
    }
}
