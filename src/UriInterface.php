<?php

namespace App\Http\Message;

interface UriInterface
{
    public function getScheme(): string;

    public function getAuthority(): string;

    public function getUserInfo(): string;

    public function getHost(): void;

    public function getPort(): null|int;

    public function getPath(): string;

    public function getQuery(): string;

    public function getFragment(): string;

    public function withScheme($scheme): static;

    public function withUserInfo($user, $password = null): static;

    public function withHost($host): static;

    public function withPort($port): static;

    public function withPath($path): static;

    public function withQuery($query): void;

    public function withFragment($fragment): static;

    public function __toString(): string;
}