<?php
/**
 * Simple Router
 */

namespace Core;

class Router
{
    private array $routes = [];
    private string $method;
    private string $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->path = str_replace('/public', '', $this->path);
        if ($this->path === '') {
            $this->path = '/';
        }
    }

    public function get(string $path, string|callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string|callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function match(array $methods, string $path, string|callable $handler): void
    {
        foreach ($methods as $method) {
            $this->routes[$method][$path] = $handler;
        }
    }

    public function dispatch(): mixed
    {
        $handlers = $this->routes[$this->method] ?? [];

        foreach ($handlers as $route => $handler) {
            if ($this->match($route)) {
                return is_callable($handler) ? $handler() : $this->callHandler($handler);
            }
        }

        return $this->notFound();
    }

    private function match(string $route): bool
    {
        $routePattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route);
        $routePattern = '#^' . $routePattern . '$#';
        return (bool)preg_match($routePattern, $this->path);
    }

    private function callHandler(string $handler): mixed
    {
        [$class, $method] = explode('@', $handler);
        $class = 'Controllers\\' . $class;
        return (new $class())->$method();
    }

    private function notFound(): never
    {
        http_response_code(404);
        \Helpers\response(['error' => 'Route not found']);
    }
}
