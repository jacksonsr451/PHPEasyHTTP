<?php

namespace App\Http\Message;

interface ResponseInterface extends MessageInterface
{
    public function getStatusCode(): int;

    public function withStatus($code, $reasonPhrase = ''): static;

    public function getReasonPhrase(): string;
}