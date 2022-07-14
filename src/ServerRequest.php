<?php 

namespace PhpEasyHttp\HTTP\Message;

use PhpEasyHttp\Http\Message\Interfaces\ServerRequestInterface;
use PhpEasyHttp\HTTP\Message\Traits\MessageTrait;
use PhpEasyHttp\HTTP\Message\Traits\RequestTrait;

class ServerRequest implements ServerRequestInterface
{
	use MessageTrait;
    use RequestTrait;

	private array $servers;
	private array $cookies;
	private array $queries;
	private array $uploadFiles;
	private null|array|object $parsedBody;
	private mixed $attributes;

	public function getServerParams(): array
	{
		return $this->servers;
	}

	public function getCookieParams(): array
	{
		return $this->cookies;
	}

	public function withCookieParams(array $cookies): self
	{
		$clone = clone $this;
		$clone->cookies = $cookies;
		return $clone;
	}

	public function getQueryParams(): array
	{
		if (! empty($this->queries)) return $this->queries;
		$queries = [];
		parse_str($this->getUri()->getQuery(), $queries);
		return $queries;
	}

	public function withQueryParams(array $query): self
	{
		$clone = clone $this;
		$clone->queries = $query;
		return $clone;
	}

	public function getUploadedFiles(): array
	{
		return $this->uploadFiles;
	}

	public function withUploadedFiles(array $uploadedFiles): self
	{
		$clone = clone $this;
		$clone->uploadFiles = $uploadedFiles;
		return $clone;
	}

	public function getParsedBody(): null|array|object
	{
		if ($this->parsedBody !== null) return $this->parsedBody;
		if ($this->inPost()) return $_POST;
		if ($this->inHeader('content-type', 'application/json')) {
			return json_decode($this->getBody());
		}
		return $this->body;
	}

	public function inPost(): bool
	{
		$postHeaders = ['application/x-www-form-urlencoded', 'multpart/form-data'];
		$headersValues = $this->getHeader('content-type');
		foreach ($headersValues as $value) {
			if (in_array($value, $postHeaders)) return true;
		}
		return false;
	}

	public function withParsedBody($data): self
	{
		$clone = clone $this;
		$clone->parsedBody = $data;
		return $clone;
	}

	public function getAttributes(): mixed
	{
		return $this->attributes;
	}

	public function getAttribute($name, $default = null): mixed
	{
		return $this->attributes[$name] ?? $default;
	}

	public function withAttribute($name, $value): self
	{
		$clone = clone $this;
		$clone->attributes[$name] = $value;
		return $clone;
	}

	public function withoutAttribute($name): self
	{
		$clone = clone $this;
		unset($clone->attributes[$name]);
		return $clone;
	}
}