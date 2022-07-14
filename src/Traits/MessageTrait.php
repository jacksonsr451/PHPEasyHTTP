<?php 

namespace PhpEasyHttp\HTTP\Message\Traits;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;
use PhpEasyHttp\HTTP\Message\Stream;

trait MessageTrait 
{
    private string $protocol = "1.1";
    private mixed $headers = [];
    private StreamInterface $body;
    
	public function getProtocolVersion(): string 
    {
        return $this->protocol;
	}
	
	public function withProtocolVersion($version): self
    {
        if ($this->protocol === $version) return $this;

        $clone = clone $this;
        $clone->protocol = $version;
        return $clone;
	}
	
	public function getHeaders(): mixed 
    {
        return $this->headers;
	}
	
	public function hasHeader($name): bool 
    {
        $name = strtolower($name);
        return isset($this->headers[$name]);
	}
	
	public function getHeader($name): mixed 
    {
        $name = strtolower($name);
        if (! $this->hasHeader($name)) return [];
        return $this->headers[$name];
	}
	
	public function getHeaderLine($name): string 
    {
        return implode(',', $this->getHeader($name));
	}
	
	public function withHeader($name, $value): self 
    {
        if (! is_string($name)) throw new InvalidArgumentException("Argument {$name} must be a string!");
        if (! is_string($name) && ! is_array($value)) {
            throw new InvalidArgumentException("Argument {$value} must be a string!");
        }
        $name = strtolower($name);
        if (is_string($value)) $value = array($value);
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
	}
	
	public function withAddedHeader($name, $value): self  
    {
        if (! is_string($name)) throw new InvalidArgumentException("Argument {$name} must be a string!");
        if (! is_string($name) && ! is_array($value)) {
            throw new InvalidArgumentException("Argument {$value} must be a string!");
        }
        $name = strtolower($name);
        if (is_string($value)) $value = array($value);
        $clone = clone $this;
        $clone->headers[$name] = array_merge($clone->headers, $value);
        return $clone;
	}
	
	public function withoutHeader($name): self 
    {
        $clone = clone $this;
        unset($clone->headers[$name]);
        return $clone;
	}
	
	public function getBody(): StreamInterface 
    {
        return $this->body;
	}
	
	public function withBody(StreamInterface $body): self
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
	}

    public function setHeaders(array $headers): void
    {
        foreach ($headers as $key => $value) {
            $this->headers[strtolower($key)] = $value;
            if (is_string($value)) $this->headers[strtolower($key)] = explode(', ', $value);
        }
    }

    public function setBody($body): void
    {
        if ($body instanceof StreamInterface) $body = new Stream();
        $this->body = $body;
    }

    protected function inHeader(string $name, string $value): bool 
    {
        $headers = $this->getHeader($name);
        return in_array($value, $headers);
    }
}