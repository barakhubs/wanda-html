<?php

namespace App\Core;

/**
 * Simple path-based router.
 *
 * Routes are registered with add() and dispatched with dispatch().
 * Supports static paths and named captures: /blog/{slug}
 */
class Router
{
    /** @var array<int, array{method: string, pattern: string, handler: callable|array}> */
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $this->pathToRegex($path),
            'handler' => $handler,
        ];
    }

    /**
     * Dispatch the current request. Sends a 404 response if nothing matches.
     */
    public function dispatch(string $method, string $uri): void
    {
        // Strip query string and trailing slash (except root "/")
        $path = parse_url($uri, PHP_URL_PATH);
        $path = ($path !== '/') ? rtrim($path, '/') : '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches)) {
                // Collect named captures only
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $handler = $route['handler'];

                if (is_array($handler)) {
                    [$class, $action] = $handler;
                    (new $class())->$action($params);
                } else {
                    $handler($params);
                }
                return;
            }
        }

        // No route matched — 404
        http_response_code(404);
        echo '<h1>404 — Page Not Found</h1>';
    }

    /**
     * Convert a route path like /blog/{slug} into a named-capture regex.
     */
    private function pathToRegex(string $path): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
