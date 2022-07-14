<?php

namespace PhpEasyHttp\Http\Message\Interfaces;

interface ServerRequestInterface extends RequestInterface
{
    public function getServerParams(): array;

    public function getCookieParams(): array;

    public function withCookieParams(array $cookies): self;

    public function getQueryParams(): array;

    public function withQueryParams(array $query): self;

    public function getUploadedFiles(): array;

    public function withUploadedFiles(array $uploadedFiles): self;

    public function getParsedBody(): null|array|object;

    public function withParsedBody($data): self;

    public function getAttributes(): mixed;

    public function getAttribute($name, $default = null): mixed;

    public function withAttribute($name, $value): self;

    public function withoutAttribute($name): self;
}