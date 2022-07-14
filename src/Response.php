<?php 

namespace PhpEasyHttp\HTTP\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\MessageInterface;
use PhpEasyHttp\Http\Message\Interfaces\ResponseInterface;
use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;

class Response implements ResponseInterface
{

    private MessageInterface $message;
    private int $code;
    private const REASON_PHRASE = [
        200 => 'Ok',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found',
    ];

    public function __construct(int $code, $body = null, array $headers = [], string $version = "1.1")
    {
        $this->message = new Message();
        $this->code = $code;
        $this->setBody($body);
        $this->setHeaders($headers);
        $this->message->protocol = $version;
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
	
	public function getProtocolVersion(): string 
    {
        return $this->message->getProtocolVersion();
	}
	
	public function withProtocolVersion($version): MessageInterface 
    {
        return $this->message->withProtocolVersion($version);
	}
	
	public function getHeaders(): mixed 
    {
        return $this->message->getHeaders();
	}
	
	public function hasHeader($name): bool 
    {
        return $this->hasHeader($name);
	}
	
	public function getHeader($name): mixed 
    {
        return $this->message->getHeader($name);
	}
	
	public function getHeaderLine($name): string 
    {
        return $this->message->getHeaderLine($name);
	}
	
	public function withHeader($name, $value): MessageInterface 
    {
        return $this->message->withHeader($name, $value);
	}
	
	public function withAddedHeader($name, $value): MessageInterface 
    {
        return $this->withAddedHeader($name, $value);
	}
	
	public function withoutHeader($name): MessageInterface 
    {
        return $this->message->withoutHeader($name);
	}
	
	public function getBody(): StreamInterface 
    {
        return $this->message->getBody();
	}
	
	public function withBody(StreamInterface $body): MessageInterface 
    {
        return $this->message->withBody($body);
	}
	
	public function setHeaders(array $headers): void 
    {
        return $this->message->setHeaders($headers);
	}
	
	public function setBody($body): void 
    {
        return $this->message->setBody($body);
	}

}