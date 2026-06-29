<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\NotFoundException;

class Router
{
    private array $routes = [];
    private array $middleware = [];
    private string $prefix = '';
    private array $groupMiddleware = [];

    public function get(string $path, array|string $action, array $middleware = []): self
    {
        return $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post(string $path, array|string $action, array $middleware = []): self
    {
        return $this->addRoute('POST', $path, $action, $middleware);
    }

    public function put(string $path, array|string $action, array $middleware = []): self
    {
        return $this->addRoute('PUT', $path, $action, $middleware);
    }

    public function delete(string $path, array|string $action, array $middleware = []): self
    {
        return $this->addRoute('DELETE', $path, $action, $middleware);
    }

    public function group(array $options, callable $callback): void
    {
        $previousPrefix = $this->prefix;
        $previousMiddleware = $this->groupMiddleware;

        $this->prefix = $previousPrefix . ($options['prefix'] ?? '');
        $this->groupMiddleware = array_merge($previousMiddleware, $options['middleware'] ?? []);

        $callback($this);

        $this->prefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    private function addRoute(string $method, string $path, array|string $action, array $middleware = []): self
    {
        $fullPath = $this->prefix . $path;
        $allMiddleware = array_merge($this->groupMiddleware, $middleware);

        $this->routes[$method][$fullPath] = [
            'action' => $action,
            'middleware' => $allMiddleware,
        ];

        return $this;
    }

    public function dispatch(string $method, string $url): void
    {
        // Handle PUT/DELETE via POST with _method field
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $url = '/' . ltrim($url, '/');

        foreach ($this->routes[$method] ?? [] as $route => $config) {
            $pattern = $this->convertToRegex($route);

            if (preg_match($pattern, $url, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Run middleware
                foreach ($config['middleware'] as $middlewareClass) {
                    $middlewareInstance = new $middlewareClass();
                    if (!$middlewareInstance->handle()) {
                        return;
                    }
                }

                // Execute action
                $this->executeAction($config['action'], $params);
                return;
            }
        }

        throw new NotFoundException("Route not found: {$method} {$url}");
    }

    private function convertToRegex(string $route): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    private function executeAction(array|string $action, array $params): void
    {
        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
        } else {
            [$controller, $method] = $action;
        }

        if (!class_exists($controller)) {
            throw new NotFoundException("Controller not found: {$controller}");
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $method)) {
            throw new NotFoundException("Method not found: {$controller}@{$method}");
        }

        call_user_func_array([$controllerInstance, $method], $params);
    }
}
