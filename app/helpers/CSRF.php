<?php
/**
 * CSRF Protection Helper
 * Prevents Cross-Site Request Forgery attacks
 */

class CSRF
{
    private const TOKEN_NAME = '_csrf_token';
    private const TOKEN_LENGTH = 32;

    public static function generateToken(): string
    {
        if (!Session::has(self::TOKEN_NAME)) {
            $token = bin2hex(random_bytes(self::TOKEN_LENGTH));
            Session::set(self::TOKEN_NAME, $token);
        }

        return Session::get(self::TOKEN_NAME);
    }

    public static function getToken(): string
    {
        return Session::get(self::TOKEN_NAME) ?? self::generateToken();
    }

    public static function tokenField(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::TOKEN_NAME . '" value="' . htmlspecialchars($token) . '">';
    }

    public static function tokenMeta(): string
    {
        $token = self::getToken();
        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }

    public static function verify(?string $token = null): bool
    {
        $token = $token ?? ($_POST[self::TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        $sessionToken = Session::get(self::TOKEN_NAME);

        if ($token === null || $sessionToken === null) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function checkOrFail(): void
    {
        if (!self::verify()) {
            http_response_code(403);
            if (self::isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid CSRF token']);
            } else {
                Session::flash('error', 'Session expired. Please try again.');
                Router::back();
            }
            exit;
        }
    }

    public static function regenerate(): string
    {
        Session::remove(self::TOKEN_NAME);
        return self::generateToken();
    }

    private static function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
