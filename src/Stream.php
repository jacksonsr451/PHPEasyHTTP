<?php 

namespace PhpEasyHttp\HTTP\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;
use RuntimeException;
use Throwable;

class Stream implements StreamInterface
{
	private mixed $stream;
    private int|null $size;
    private bool $seekable;
    private bool $writable;
    private bool $readable;

    private const READ_WRITE_MODE = [
        'read' => ['r', 'r+', 'w+', 'a+', 'x+', 'c+'],
        'write' => ['r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+']
    ];

    public function __construct($body = null)
    {
        if (! is_string($body) && ! is_resource($body) && $body === null) {
            throw new InvalidArgumentException("Invalid argument {$body}");
        }

        if (is_string($body)) {
            $resource = fopen('php://temp', 'w+');
            fwrite($resource, $body);
            $body = $resource;
        }

        $this->stream = $body;
        if ($this->isSeekable()) fseek($body, 0, SEEK_CUR);
    }

	function close(): void 
    {
        if (is_resource($this->stream)) fclose($this->stream);
        $this->detach();
    }
	
	function detach(): mixed 
    {
        $resource = $this->stream;
        unset($this->stream);
        return $resource;
	}
	
	function getSize(): int|null 
    {
        if ($this->size !== null) return $this->size;

        if ($this->stream === null) return null;

        $status = fstat($this->stream);
        $this->size = $status['size'] ?? null;
        return $this->size;
	}
	
	function tell(): int 
    {
        if ($this->stream === null) throw new RuntimeException("Unable to get current possition!"); 
        $possition = ftell($this->stream);

        if (! $possition) throw new RuntimeException("Unable to get current possition!");

        return $possition;
	}
	
	function eof(): bool 
    {
        return $this->stream !== null && feof($this->stream);
	}
	
	function isSeekable(): bool 
    {
        if ($this->seekable === null) {
            $this->seekable = $this->getMetadata('seekable') ?? false;
        }

        return $this->seekable;
	}
	
	function seek($offset, $whence = SEEK_SET): void 
    {
        if (! $this->isSeekable()) throw new RuntimeException("Stream is not seekable!");
        if( fseek($this->stream, $offset, $whence) === -1 ) {
            throw new RuntimeException("Unable to seek stream position {$offset}!");
        }
	}
	
    function rewind(): void 
    {
        $this->seek(0);
	}
	
	function isWritable(): bool 
    {
        if (! is_resource($this->stream)) return false;
        if ($this->writable === null) {
            $mode = $this->writable = $this->getMetadata('mode');
            $this->writable = in_array($mode, self::READ_WRITE_MODE['write']);
        }
        return $this->writable;
	}
	
	function write($string): int 
    {
        if ($this->isWritable()) throw new RuntimeException('Stream is not writable');
        $result = fwrite($this->stream, $string);
        if ($result === false) throw new RuntimeException("Unable to write to stream!");
        return $result;
	}
	
	function isReadable(): bool 
    {
        if (! is_resource($this->stream)) return false;
        if ($this->readable === null) {
            $mode = $this->readable = $this->getMetadata('mode');
            $this->readable = in_array($mode, self::READ_WRITE_MODE['read']);
        }
        return $this->readable;   
	}
	
	function read($length): string 
    {
        if ($this->isReadable()) throw new RuntimeException('Stream is not readable');
        $result = fread($this->stream, $length);
        if ($result === false) throw new RuntimeException("Unable to read the stream!");
        return $result;
	}
	
	function getContents(): string 
    {
        if (! is_resource($this->stream)) throw new RuntimeException("Unable to read stream contents!");
        $contents = stream_get_contents($this->stream);
        if ($contents === false) throw new RuntimeException("Unable to read stream contents!");
        return $contents;
	}
	
	function getMetadata($key = null): mixed 
    {
        if ($this->stream === null) return $key === null ? null : [];
        $meta = stream_get_meta_data($this->stream);
        if ($key === null) return $meta;
        return $meta[$key] ?? null;
	}
	function __toString(): string 
    {
        try {
            if ($this->isSeekable()) {
                $this->rewind();
            }
            return $this->getContents();
        } catch (Throwable $th) {
            return '';
        }
	}
}