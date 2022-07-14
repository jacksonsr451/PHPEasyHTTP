<?php 

namespace PhpEasyHttp\HTTP\Message;

use PhpEasyHttp\Http\Message\Interfaces\ServerRequestInterface;
use PhpEasyHttp\HTTP\Message\Traits\MessageTrait;
use PhpEasyHttp\HTTP\Message\Traits\RequestTrait;

class ServerRequest implements ServerRequestInterface
{
	use MessageTrait;
    use RequestTrait;
}