<?php 

namespace PhpEasyHttp\HTTP\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\ResponseInterface;
use PhpEasyHttp\HTTP\Message\Traits\MessageTrait;

class Response implements ResponseInterface
{
    use MessageTrait;

    private int $code;
    private const REASON_PHRASE = [
        200 => 'Ok',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found',
    ];

    public function __construct(int $code, $body = null, array $headers = [], string $version = "1.1")
    {
        $this->code = $code;
        $this->setBody($body);
        $this->setHeaders($headers);
        $this->protocol = $version;
    }

	public function getStatusCode(): int 
    {
        return $this->code;
	}
	
	public function withStatus($code, $reasonPhrase = ''): self
    {
        if (! is_int($code)) throw new InvalidArgumentException("Argument {$code} must be integer only!");
        if ($this->code === $code) return $this;
        $clone = clone $this;
        $clone->code = $code;
        return $clone;
	}
	
	public function getReasonPhrase(): string 
    {
        return self::REASON_PHRASE[$this->code] ?? '';
	}

}