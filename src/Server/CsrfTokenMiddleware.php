<?php

namespace PhpEasyHttp\Http\Server;

use ArrayAccess;
use PhpEasyHttp\Http\Message\Interfaces\ResponseInterface;
use PhpEasyHttp\Http\Message\Interfaces\ServerRequestInterface;
use PhpEasyHttp\Http\Server\Exceptions\InvalidCsrfException;
use PhpEasyHttp\Http\Server\Exceptions\NoCsrfException;
use PhpEasyHttp\Http\Server\Exceptions\TypeError;
use PhpEasyHttp\Http\Server\Interfaces\MiddlewareInterface;
use PhpEasyHttp\Http\Server\Interfaces\RequestHandlerInterface;

class CsrfTokenMiddleware implements MiddlewareInterface
{
    private array|ArrayAccess $session;
    private int $limit;
    private string $sessionKey;
    private string $formKey;

    public function __construct(array|ArrayAccess &$session, int $limit = 50, string $sessionKey = 'csrf_tokens', string $formKey = '_csrf')
    {
        $this->validateSession($session);
        $this->session = $session;
        $this->limit = $limit;
        $this->sessionKey = $sessionKey;
        $this->formKey = $formKey;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['post', 'delete', 'put'])) {
            $params = $request->getParsedBody() ?? [];
            if (! array_key_exists($this->formKey, $params)) {
                throw new NoCsrfException("csrf token is required!");
            } elseif (! in_array($params[$this->formKey], $this->session[$this->sessionKey])) {
                throw new InvalidCsrfException("This csrf token is invalid!");
            }
            $this->removeToken($params[$this->formKey]);
        }
        return $handler->handle($request);
    }

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));
        $tokens = $this->session[$this->sessionKey] ?? [];
        $tokens[] = $token;
        $this->session[$this->sessionKey] = $this->limitToken($tokens);
        return $token;
    }

    public function validateSession(array|ArrayAccess $session): void
    {
        if (! is_array($session) && ! $session instanceof ArrayAccess) {
            throw new TypeError('Session is not in array!');
        }
    }

    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    public function getFormKey(): string
    {
        return $this->formKey;
    }

    public function removeToken(string $token): void
    {
        unset($this->session[$this->sessionKey][$token]);
    }

    public function limitToken(array $tokens): array
    {
        if (count($tokens) > $this->limit) {
            array_shift($tokens);
        }
        return $tokens;
    }
}
