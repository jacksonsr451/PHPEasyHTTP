<?php

namespace App\Http\Message;

interface MessageInterface 
{

    public function getProtocolVersion(): string;

    public function withProtocolVersion($version): static;

    public function getHeaders(): mixed;

    public function hasHeader($name): bool;

    public function getHeader($name): mixed;

    public function getHeaderLine($name): string;

    public function withHeader($name, $value): static;

    public function withAddedHeader($name, $value): static;

    public function withoutHeader($name): static;

    public function getBody(): StreamInterface;

    public function withBody(StreamInterface $body): static;

}