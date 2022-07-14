<?php 

namespace PhpEasyHttp\HTTP\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\RequestInterface;
use PhpEasyHttp\HTTP\Message\Traits\MessageTrait;
use PhpEasyHttp\HTTP\Message\Traits\RequestTrait;

class Request implements RequestInterface
{
    use MessageTrait;
    use RequestTrait;

    public function __construct(string $method, $uri, array $headers = [], $body = null, string $version = "1.1")
    {
        $this->method = strtolower($method);
        $this->protocol = $version;
        $this->setUri($uri);
        $this->setHeaders($headers);
        $this->setBody($body);
    }
}