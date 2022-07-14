<?php

namespace PhpEasyHttp\Http\Message\Interfaces;

interface ResponseInterface extends MessageInterface
{
    public function getStatusCode(): int;

    public function withStatus($code, $reasonPhrase = ''): self;

    public function getReasonPhrase(): string;
}