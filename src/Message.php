<?php

namespace PhpEasyHttp\HTTP\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\MessageInterface;
use PhpEasyHttp\HTTP\Message\Traits\MessageTrait;

class Message implements MessageInterface 
{
    use MessageTrait;
}