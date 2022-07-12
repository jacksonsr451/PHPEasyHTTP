<?php

namespace App\Http\Message;

interface ServerRequestInterface 
{
    public function getServerParams(): array;

    public function getCookieParams(): array;

    public function withCookieParams(array $cookies): static;

    public function getQueryParams(): array;

    public function withQueryParams(array $query): static;

    public function getUploadedFiles(): array;

    public function withUploadedFiles(array $uploadedFiles): static;

    public function getParsedBody(): null|array|object;

    public function withParsedBody($data): static;

    public function getAttributes(): mixed;

    public function getAttribute($name, $default = null): mixed;

    public function withAttribute($name, $value): static;

    public function withoutAttribute($name): static;
}