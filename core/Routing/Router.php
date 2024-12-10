<?php

namespace Framework\Routing;

class Router
{
    protected array $routes = [];

    public function add(string $method, string $uri, callable $action): void
    {
        $uri = rtrim($uri, '/');
        $this->routes[] = compact('method', 'uri', 'action');
    }

    public function dispatch(string $method, string $uri)
    {
        $uri = rtrim($uri, '/');
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['uri']);
            $pattern = "#^{$pattern}$#";

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return call_user_func_array($route['action'], $matches);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
