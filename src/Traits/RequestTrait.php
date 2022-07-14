<?php 

namespace PhpEasyHttp\HTTP\Message\Traits;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\UriInterface;
use PhpEasyHttp\HTTP\Message\Uri;

trait RequestTrait 
{
    protected string $requestTarget;
    protected string $method;
    protected UriInterface $uri;

    protected array $validMethods = [
        'post', 'get', 'delete', 'put', 'patch', 'head', 'options'
    ];
    
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
        if (! in_array($method, $this->validMethods)) {
            throw new InvalidArgumentException("Only " . implode(', ', $this->validMethods) . ' are acceptable');
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

    private function setUri($uri): void
    {
        if (is_string($uri)) $uri = new Uri($uri);
        $this->uri = $uri;
    }
}