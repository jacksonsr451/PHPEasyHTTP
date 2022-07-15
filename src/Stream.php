<?php 

namespace PhpEasyHttp\Http\Message;

use InvalidArgumentException;
use PhpEasyHttp\Http\Message\Interfaces\StreamInterface;
use RuntimeException;
use Throwable;

class Stream implements StreamInterface
{
	private mixed $stream;
    private int|null $size;
    private null|bool $seekable;
    private null|bool $writable;
    private null|bool $readable;

    private const READ_WRITE_MODE = [
        'read' => ['r', 'r+', 'w+', 'a+', 'x+', 'c+'],
        'write' => ['r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+']
    ];

    public function __construct($body = null)
    {
        if (! is_string($body) && ! is_resource($body) && $body !== null) {
            throw new InvalidArgumentException("Invalid argument {$body}");
        }

        if (is_string($body)) {
            $resource = fopen('php://temp', 'w+');
            fwrite($resource, $body);
            $body = $resource;
        }

        $this->seekable = false;
        $this->writable = false;
        $this->readable = false;

        $this->stream = $body;
        if ($this->isSeekable()) fseek($body, 0, SEEK_CUR);
    }

	public function close(): void 
    {
        if (is_resource($this->stream)) fclose($this->stream);
        $this->detach();
    }
	
	public function detach(): mixed 
    {
        $resource = $this->stream;
        unset($this->stream);
        return $resource;
	}
	
	public function getSize(): int|null 
    {
        if ($this->size !== null) return $this->size;

        if ($this->stream === null) return null;

        $status = fstat($this->stream);
        $this->size = $status['size'] ?? null;
        return $this->size;
	}
	
	public function tell(): int 
    {
        if ($this->stream === null) throw new RuntimeException("Unable to get current possition!"); 
        $possition = ftell($this->stream);

        if (! $possition) throw new RuntimeException("Unable to get current possition!");

        return $possition;
	}
	
	public function eof(): bool 
    {
        return $this->stream !== null && feof($this->stream);
	}
	
	public function isSeekable(): bool 
    {
        if ($this->seekable === null) {
            $this->seekable = $this->getMetadata('seekable') ?? false;
        }

        return $this->seekable;
	}
	
	public function seek($offset, $whence = SEEK_SET): void 
    {
        if (! $this->isSeekable()) throw new RuntimeException("Stream is not seekable!");
        if( fseek($this->stream, $offset, $whence) === -1 ) {
            throw new RuntimeException("Unable to seek stream position {$offset}!");
        }
	}
	
    public function rewind(): void 
    {
        $this->seek(0);
	}
	
	public function isWritable(): bool 
    {
        if (! is_resource($this->stream)) return false;
        if ($this->writable === null) {
            $mode = $this->writable = $this->getMetadata('mode');
            $this->writable = in_array($mode, self::READ_WRITE_MODE['write']);
        }
        return $this->writable;
	}
	
	public function write($string): int 
    {
        if ($this->isWritable()) throw new RuntimeException('Stream is not writable');
        $result = fwrite($this->stream, $string);
        if ($result === false) throw new RuntimeException("Unable to write to stream!");
        return $result;
	}
	
	public function isReadable(): bool 
    {
        if (! is_resource($this->stream)) return false;
        if ($this->readable === null) {
            $mode = $this->readable = $this->getMetadata('mode');
            $this->readable = in_array($mode, self::READ_WRITE_MODE['read']);
        }
        return $this->readable;   
	}
	
	public function read($length): string 
    {
        if ($this->isReadable()) throw new RuntimeException('Stream is not readable');
        $result = fread($this->stream, $length);
        if ($result === false) throw new RuntimeException("Unable to read the stream!");
        return $result;
	}
	
	public function getContents(): string 
    {
        if (! is_resource($this->stream)) throw new RuntimeException("Unable to read stream contents!");
        $contents = stream_get_contents($this->stream);
        if ($contents === false) throw new RuntimeException("Unable to read stream contents!");
        return $contents;
	}
	
	public function getMetadata($key = null): mixed 
    {
        if ($this->stream === null) return $key === null ? null : [];
        $meta = stream_get_meta_data($this->stream);
        if ($key === null) return $meta;
        return $meta[$key] ?? null;
	}
	public function __toString(): string 
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