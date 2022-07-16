<?php

namespace PhpEasyHttp\Http\Message\Interfaces;

interface UploadFileInterface
{
    public function getStream(): StreamInterface;

    public function moveTo($targetPath): void;

    public function getSize(): int|null;

    public function getError(): int;

    public function getClientFilename(): string|null;

    public function getClientMediaType(): string|null;
}
