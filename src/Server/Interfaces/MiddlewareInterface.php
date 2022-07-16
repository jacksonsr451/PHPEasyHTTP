<?php

namespace PhpEasyHttp\Http\Server\Interfaces;

use PhpEasyHttp\Http\Message\Interfaces\ServerRequestInterface;
use PhpEasyHttp\Http\Message\Interfaces\ResponseInterface;

interface MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): null|ResponseInterface;
}
