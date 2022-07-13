<?php

namespace PhpEasyHttp\HTTP\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\MessageInterface;
use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;

class Message implements MessageInterface 
{
    private string $protocol = "1.1";
    private mixed $headers = [];
    private StreamInterface $body;
    
	function getProtocolVersion(): string 
    {
        return $this->protocol;
	}
	
	function withProtocolVersion($version): self
    {
        if ($this->protocol === $version) return $this;

        $clone = clone $this;
        $clone->protocol = $version;
        return $clone;
	}
	
	function getHeaders(): mixed 
    {
        return $this->headers;
	}
	
	function hasHeader($name): bool 
    {
        $name = strtolower($name);
        return isset($this->headers[$name]);
	}
	
	function getHeader($name): mixed 
    {
        $name = strtolower($name);
        if (! $this->hasHeader($name)) return [];
        return $this->headers[$name];
	}
	
	function getHeaderLine($name): string 
    {
        return implode(',', $this->getHeader($name));
	}
	
	function withHeader($name, $value): self 
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
	
	function withAddedHeader($name, $value): self  
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
	
	function withoutHeader($name): self 
    {
        $clone = clone $this;
        unset($clone->headers[$name]);
        return $clone;
	}
	
	function getBody(): StreamInterface 
    {
        return $this->body;
	}
	
	function withBody(StreamInterface $body): self
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
	}
}