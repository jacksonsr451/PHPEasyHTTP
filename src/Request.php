<?php 

namespace PhpEasyHttp\HTTP\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\MessageInterface;
use PhpEasyHttp\Http\Message\Interfaces\RequestInterface;
use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;
use PhpEasyHttp\Http\Message\Interfaces\UriInterface;

class Request implements RequestInterface
{
    private MessageInterface $message;
    private string $requestTarget;
    private string $method;
    private UriInterface $uri;

    private const VALID_METHODS = [
        'post', 'get', 'delete', 'put', 'patch', 'head', 'options'
    ];

    public function __construct(string $method, $uri, array $headers = [], $body = null, string $version = "1.1")
    {
        $this->message = new Message();
        $this->method = strtolower($method);
        $this->message->protocol = $version;
        $this->setUri($uri);
        $this->setHeaders($headers);
        $this->setBody($body);
    }

	public function getRequestTarget(): string 
    {
        return $this->requestTarget;
	}
	
	public function withRequestTarget($requestTarget): self 
    {
        if ($this->requestTarget === $requestTarget) return $this;
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
	}
	
	public function getMethod(): string 
    {
        return $this->method;
	}
	
	public function withMethod($method): self 
    {
        if ($this->method === $method) return $this;
        if (! in_array($method, self::VALID_METHODS)) {
            throw new InvalidArgumentException("Only " . implode(', ', self::VALID_METHODS) . ' are acceptable');
        }
        $clone = clone $this;
        $clone->method = strtolower($method);
        return $clone;
	}
	
	public function getUri(): UriInterface 
    {
        return $this->uri;
	}
	
	public function withUri(UriInterface $uri, $preserveHost = false): self 
    {
        $clone = clone $this;
        
        if ($preserveHost) {
            $newUri = $uri->withHost($this->uri->getHost());
        }

        return $clone;
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
        return $this->message->hasHeader($name);
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
        return $this->message->withAddedHeader($name, $value);
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
        $this->message->setHeaders($headers);
    }

    public function setBody($body): void
    {
        $this->message->setBody($body);
    }

    private function setUri($uri): void
    {
        if (is_string($uri)) $uri = new Uri($uri);
        $this->uri = $uri;
    }

}