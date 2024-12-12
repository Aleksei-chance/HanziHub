<?php

namespace Framework\Routing;

class Router
{
    protected array $routes = [];

    public function add(string $method, string $uri, callable $action, array $middleware = []): void
    {
        $uri = rtrim($uri, '/');
        $this->routes[] = compact('method', 'uri', 'action', 'middleware');
    }

    public function dispatch(string $method, string $uri)
    {
        $uri = rtrim($uri, '/');
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['uri']);
            $pattern = "#^{$pattern}$#";

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $request = $_SERVER;
                $middlewareStack = $route['middleware'];

                $action = function ($request) use ($route, $matches) {
                    return call_user_func_array($route['action'], $matches);
                };

                while ($middleware = array_pop($middlewareStack)) {
                    $middlewareClass = new $middleware();
                    $action = function ($request) use ($middlewareClass, $action) {
                        return $middlewareClass->handle($request, $action);
                    };
                }

                return $action($request);
            }
        }

        http_response_code(404);
        throw new \Exception("404 Not Found");
    }
}
