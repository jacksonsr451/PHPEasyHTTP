<?php

namespace PhpEasyHttp\Http\Server;

use Exception;
use PhpEasyHttp\Http\Message\Interfaces\ServerRequestInterface;
use PhpEasyHttp\Http\Message\Interfaces\UriInterface;
use PhpEasyHttp\Http\Message\ServerRequest;
use PhpEasyHttp\Http\Message\Uri;
use PhpEasyHttp\Http\Server\Exceptions\ClassDontExistException;
use PhpEasyHttp\Http\Server\Exceptions\MethodDontExistException;
use PhpEasyHttp\Http\Server\Exceptions\RouteDontExistException;
use Throwable;

class Route
{
    private static array $routes;
    private static array $params = [];

    private static ServerRequestInterface $request;

    public static function load(): void
    {
        $path = self::getUri()->getPath();
        $request = self::getRequest();
        try {
            if (! array_key_exists($path, self::$routes)) {
                if (! self::validateUriWithParams($path)) {
                    throw new RouteDontExistException("Route dont exists {$path}");
                } else {
                    foreach (self::validateUriWithParams($path) as $key => $value) {
                        $route = $value;
                    }
                }
            } else {
                $route = self::$routes[$path];
            }

            $controller = self::getController($route);
            $controller = new $controller();
            $action = self::getMethod($controller, $route);
            
            (new RequestHandler($route['middlewares'], function () use ($controller, $action, $request) {
                self::loadMethod($controller, $action, $request);
            }, []))->handle($request);
        } catch (Throwable $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "<pre>";
        }
    }

    private static function getUri(): UriInterface
    {
        $scheme = "http";
        if ($_SERVER[ "SERVER_PORT" ] === 443) {
            $scheme = "https";
        }
        if (in_array('QUERY_STING', $_SERVER)) {
            return new Uri(sprintf('%s://%s%s%s', $scheme, $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'], $_SERVER['QUERY_STING'] === '' ? '' : '?'.$_SERVER['QUERY_STING']));
        }
        return new Uri(sprintf('%s://%s%s%s', $scheme, $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'], ''));
    }

    private static function getRequest(): ServerRequestInterface
    {
        return new ServerRequest(
            $_SERVER['REQUEST_METHOD'],
            self::getUri(),
            headers_list(),
            $_SERVER,
            $_COOKIE
        );
    }

    private static function validateUriWithParams($uri): array
    {
        $matcheUri = array_filter(
            self::$routes,
            function ($value) use ($uri) {
                $regex = str_replace('/', '\/', ltrim($value, '/'));
                return preg_match("/^{$regex}$/", ltrim($uri, '/'));
            },
            ARRAY_FILTER_USE_KEY
        );
        self::setParams($uri, key($matcheUri));
        return $matcheUri;
    }

    private static function setParams($uri, $route): void
    {
        $uri = explode('/', $uri);
        $route = explode('/', $route);
        self::$params = array_diff($uri, $route);
    }

    private static function loadMethod($controller, $action, $request): bool
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $controller->$action($request);
                break;

            case 'PUT':
                $controller->$action(self::$params, $request);
                break;

            case 'DELETE';
                $controller->$action(self::$params);
                break;

            default:
                if (empty(self::$params)) {
                    $controller->$action();
                } else {
                    $controller->$action(self::$params);
                }
                break;
        }
        return true;
    }

    private static function getController($routes): string
    {
        $classes = explode("@", $routes['controller']);
        $namespace = "App\\Controllers\\{$classes[0]}";

        if (!class_exists($namespace)) {
            throw new ClassDontExistException("Class dont exists {$classes[0]}");
        }

        return $namespace;
    }

    private static function getMethod($classes, $routes): string
    {
        $method = explode("@", $routes['controller']);

        if (!method_exists($classes, $method[1])) {
            throw new MethodDontExistException("Method dont exists {$method[1]}");
        }

        return $method[1];
    }

    public static function get($route, $controller, array $middlewares = []): void
    {
        self::$routes[$route] = array('controller' => $controller, 'middlewares' => $middlewares);
    }

    public static function post($route, $controller, array $middlewares = []): void
    {
        self::$routes[$route] = array('controller' => $controller, 'middlewares' => $middlewares);
    }

    public static function delete($route, $controller, array $middlewares = []): void
    {
        self::$routes[$route] = array('controller' => $controller, 'middlewares' => $middlewares);
    }

    public static function put($route, $controller, array $middlewares = []): void
    {
        self::$routes[$route] = array('controller' => $controller, 'middlewares' => $middlewares);
    }
}
