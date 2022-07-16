<?php

namespace PhpEasyHttp\Http\Server;

use Closure;
use PhpEasyHttp\Http\Message\Interfaces\ResponseInterface;
use PhpEasyHttp\Http\Message\Interfaces\ServerRequestInterface;
use PhpEasyHttp\Http\Message\Response;
use PhpEasyHttp\Http\Server\Exceptions\MiddlewareException;
use PhpEasyHttp\Http\Server\Interfaces\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [];
    private Closure $controller;
    private array $args = [];

    private static array $map = [];
    private static array $default = [];

    public function __construct(array $middleware, Closure $controller, array $args)
    {
        $this->middleware = array_merge(self::$default, $middleware);
        $this->controller = $controller;
        $this->args = $args;
    }

    public static function setMap(array $map = []): void
    {
        // $tokens = new CsrfTokenMiddleware($_SESSION, getenv("LIMIT_TOKEN"));
        // self::$map = array_merge(['_csrf_tokens' => $tokens], $map);
        self::$map = $map;
    }

    public static function setDefault(array $default = []): void
    {
        // self::$default = array_merge(['_csrf_tokens'], $default);
        self::$default = $default;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middleware)) {
            return new Response(200, call_user_func_array($this->controller, $this->args), []);
        }

        $middleware = array_shift($this->middleware);

        if (!isset(self::$map[$middleware])) {
            throw new MiddlewareException("Error this middleware {$middleware} dont exist in map!", 500);
        }

        $handle = clone $this;
        return new Response(200, (new self::$map[$middleware]())->process($request, $handle), []);
    }
}
