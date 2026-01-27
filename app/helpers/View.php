<?php
/**
 * View Helper Class
 * Handles template rendering
 */

class View
{
    private static array $data = [];
    private static ?string $layout = 'main';

    public static function render(string $view, array $data = [], ?string $layout = null): void
    {
        self::$data = array_merge(self::$data, $data);
        self::$layout = $layout ?? self::$layout;

        // Extract data to make variables available in view
        extract(self::$data);

        // Start output buffering for the view content
        ob_start();
        $viewPath = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception("View not found: {$view}");
        }

        require $viewPath;
        $content = ob_get_clean();

        // If layout is specified, wrap content in layout
        if (self::$layout !== null) {
            $layoutPath = APP_PATH . '/views/layouts/' . self::$layout . '.php';

            if (!file_exists($layoutPath)) {
                throw new Exception("Layout not found: " . self::$layout);
            }

            require $layoutPath;
        } else {
            echo $content;
        }
    }

    public static function partial(string $partial, array $data = []): void
    {
        extract(array_merge(self::$data, $data));

        $partialPath = APP_PATH . '/views/partials/' . str_replace('.', '/', $partial) . '.php';

        if (!file_exists($partialPath)) {
            throw new Exception("Partial not found: {$partial}");
        }

        require $partialPath;
    }

    public static function noLayout(): void
    {
        self::$layout = null;
    }

    public static function setLayout(string $layout): void
    {
        self::$layout = $layout;
    }

    public static function share(string $key, mixed $value): void
    {
        self::$data[$key] = $value;
    }

    public static function escape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error(string $message, int $statusCode = 400): void
    {
        self::json(['error' => $message], $statusCode);
    }

    public static function success(string $message, array $data = []): void
    {
        self::json(array_merge(['success' => true, 'message' => $message], $data));
    }
}

// Helper functions for views
function e(mixed $value): string
{
    return View::escape($value);
}

function old(string $key, mixed $default = ''): mixed
{
    return Session::getFlash('old')[$key] ?? $default;
}

function error(string $key): ?string
{
    $errors = Session::getFlash('errors') ?? [];
    return $errors[$key][0] ?? null;
}

function hasError(string $key): bool
{
    $errors = Session::get('_flash')['errors'] ?? [];
    return isset($errors[$key]);
}

function csrf_field(): string
{
    return CSRF::tokenField();
}

function csrf_token(): string
{
    return CSRF::getToken();
}

function url(string $path = ''): string
{
    $base = rtrim(config('app.url', ''), '/');
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function config(string $key, mixed $default = null): mixed
{
    global $config;

    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }

    return $value;
}

function __($key, array $replacements = []): string
{
    return Lang::get($key, $replacements);
}

function isActiveRoute(string $route): bool
{
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $currentUri === $route;
}

function formatDate(string $date, string $format = 'd/m/Y H:i'): string
{
    return date($format, strtotime($date));
}

function formatMoney(float $amount, string $currency = 'GEL'): string
{
    return number_format($amount, 2) . ' ' . $currency;
}
