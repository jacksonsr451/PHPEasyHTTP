<?php

namespace PhpEasyHttp\Http\Message\Interfaces;

interface RequestInterface extends MessageInterface
{
    public function getRequestTarget(): string;

    public function withRequestTarget($requestTarget): static;

    public function getMethod(): string;

    public function withMethod($method): static;

    public function getUri(): UriInterface;

    public function withUri(UriInterface $uri, $preserveHost = false): static;
}