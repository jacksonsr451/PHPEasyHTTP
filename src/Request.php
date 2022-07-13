<?php 

namespace PhpEasyHttp\HTTP\Message;

use PhpEasyHttp\Http\Message\Interfaces\MessageInterface;
use PhpEasyHttp\Http\Message\Interfaces\RequestInterface;
use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;
use PhpEasyHttp\Http\Message\Interfaces\UriInterface;

class Request implements RequestInterface
{

	public function getRequestTarget(): string 
    {
	}
	
	public function withRequestTarget($requestTarget):  
    {
	}
	
	public function getMethod(): string 
    {
	}
	
	public function withMethod($method):  
    {
	}
	
	public function getUri(): UriInterface 
    {
	}
	
	public function withUri(UriInterface $uri, $preserveHost = false):  
    {
	}
	
	public function getProtocolVersion(): string 
    {
	}
	
	public function withProtocolVersion($version): MessageInterface 
    {
	}
	
	public function getHeaders(): mixed 
    {
	}
	
	public function hasHeader($name): bool 
    {
	}
	
	public function getHeader($name): mixed 
    {
	}
	
	public function getHeaderLine($name): string 
    {
	}
	
	public function withHeader($name, $value): MessageInterface 
    {
	}
	
	public function withAddedHeader($name, $value): MessageInterface 
    {
	}
	
	public function withoutHeader($name): MessageInterface 
    {
	}
	
	public function getBody(): StreamInterface 
    {
	}
	
	public function withBody(StreamInterface $body): MessageInterface 
    {
	}
}