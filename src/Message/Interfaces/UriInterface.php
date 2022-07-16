<?php

namespace PhpEasyHttp\Http\Message\Interfaces;

interface UriInterface
{
    public function getScheme(): string;

    public function getAuthority(): string;

    public function getUserInfo(): string;

    public function getHost(): string;

    public function getPort(): null|int;

    public function getPath(): string;

    public function getQuery(): string;

    public function getFragment(): string;

    public function withScheme($scheme): self;

    public function withUserInfo($user, $password = null): self;

    public function withHost($host): self;

    public function withPort($port): self;

    public function withPath($path): self;

    public function withQuery($query): self;

    public function withFragment($fragment): self;

    public function __toString(): string;
}
