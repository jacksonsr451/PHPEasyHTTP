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
		
	}

	public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
	{
		
	}

	public function getParsedBody(): null|array|object
	{
		if ($this->inPost()) return $_POST;
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

	public function withParsedBody($data): ServerRequestInterface
	{
		
	}

	public function getAttributes(): mixed
	{
		
	}

	public function getAttribute($name, $default = null): mixed
	{
		
	}
}