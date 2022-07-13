<?php 

namespace PhpEasyHttp\HTTP\Message;

use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;

class Stream implements StreamInterface
{

	function __toString(): string 
    {
	}
	
	function close(): void 
    {
	}
	
	function detach(): mixed 
    {
	}
	
	function getSize(): int|null 
    {
	}
	
	function tell(): int 
    {
	}
	
	function eof(): bool 
    {
	}
	
	function isSeekable(): bool 
    {
	}
	
	function seek($offset, $whence = SEEK_SET): void 
    {
	}
	
    function rewind(): void 
    {
	}
	
	function isWritable(): bool 
    {
	}
	
	function write($string): int 
    {
	}
	
	function isReadable(): bool 
    {
	}
	
	function read($length): string 
    {
	}
	
	function getContents(): string 
    {
	}
	
	function getMetadata($key = null): mixed 
    {
	}
}