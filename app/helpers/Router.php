<?php
/**
 * Router Class
 * Handles URL routing and dispatching to controllers
 */

class Router
{
    private array $routes = [];
    private array $params = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = $this->normalizeUri($uri);
        $method = strtoupper($method);

        foreach ($this->routes as $route => $handler) {
            [$routeMethod, $routeUri] = explode(' ', $route, 2);

            if ($routeMethod !== $method) {
                continue;
            }

            $pattern = $this->convertRouteToRegex($routeUri);

            if (preg_match($pattern, $uri, $matches)) {
                $this->params = $this->extractParams($matches);
                $this->callHandler($handler);
                return;
            }
        }

        // No route found
        $this->handleNotFound();
    }

    private function normalizeUri(string $uri): string
    {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // Remove trailing slash (except for root)
        $uri = rtrim($uri, '/');

        // Default to root if empty
        return $uri ?: '/';
    }

    private function convertRouteToRegex(string $route): string
    {
        // Escape forward slashes
        $pattern = preg_quote($route, '#');

        // Convert {param} to named capture groups
        $pattern = preg_replace('/\\\{([a-zA-Z_]+)\\\}/', '(?P<$1>[^/]+)', $pattern);

        return '#^' . $pattern . '$#';
    }

    private function extractParams(array $matches): array
    {
        return array_filter($matches, function ($key) {
            return is_string($key);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function callHandler(array $handler): void
    {
        [$controllerName, $method] = $handler;

        // Handle namespaced controllers (Admin\)
        if (str_contains($controllerName, '\\')) {
            $parts = explode('\\', $controllerName);
            $namespace = $parts[0];
            $className = $parts[1];
            $controllerFile = APP_PATH . '/controllers/' . strtolower($namespace) . '/' . $className . '.php';
        } else {
            $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
        }

        if (!file_exists($controllerFile)) {
            throw new Exception("Controller file not found: {$controllerFile}");
        }

        require_once $controllerFile;

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            throw new Exception("Method {$method} not found in {$controllerName}");
        }

        // Call the controller method with params
        call_user_func_array([$controller, $method], $this->params);
    }

    private function handleNotFound(): void
    {
        http_response_code(404);
        require_once APP_PATH . '/views/errors/404.php';
        exit;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public static function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    public static function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirect($referer);
    }
}
