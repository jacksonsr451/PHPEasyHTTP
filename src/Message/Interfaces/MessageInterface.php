<?php

namespace PhpEasyHttp\Http\Message\Interfaces;

interface MessageInterface
{

    public function getProtocolVersion(): string;

    public function withProtocolVersion($version): self;

    public function getHeaders(): mixed;

    public function hasHeader($name): bool;

    public function getHeader($name): mixed;

    public function getHeaderLine($name): string;

    public function withHeader($name, $value): self;

    public function withAddedHeader($name, $value): self;

    public function withoutHeader($name): self;

    public function getBody(): StreamInterface;

    public function withBody(StreamInterface $body): self;

    public function setHeaders(array $headers): void;

    public function setBody($body): void;
}
