<?php
/**
 * Session Helper Class
 * Manages user sessions securely
 */

class Session
{
    private static bool $started = false;

    public static function start(array $config = []): void
    {
        if (self::$started) {
            return;
        }

        // Session configuration
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_httponly', $config['httponly'] ?? true);
        ini_set('session.cookie_secure', $config['secure'] ?? false);
        ini_set('session.gc_maxlifetime', $config['lifetime'] ?? 7200);

        session_name($config['name'] ?? 'app_session');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        self::$started = true;

        // Regenerate session ID periodically for security
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = time();
        } elseif (time() - $_SESSION['_created'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['_created'] = time();
        }

        // Check session timeout
        if (isset($_SESSION['_last_activity'])) {
            $lifetime = $config['lifetime'] ?? 7200;
            if (time() - $_SESSION['_last_activity'] > $lifetime) {
                self::destroy();
                Router::redirect('/login?timeout=1');
            }
        }
        $_SESSION['_last_activity'] = time();
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        self::$started = false;
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    public static function setUser(array $user): void
    {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = true;
    }

    public static function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function isLoggedIn(): bool
    {
        return $_SESSION['logged_in'] ?? false;
    }

    public static function setAdmin(array $admin): void
    {
        $_SESSION['admin'] = $admin;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_logged_in'] = true;
    }

    public static function getAdmin(): ?array
    {
        return $_SESSION['admin'] ?? null;
    }

    public static function getAdminId(): ?int
    {
        return $_SESSION['admin_id'] ?? null;
    }

    public static function isAdminLoggedIn(): bool
    {
        return $_SESSION['admin_logged_in'] ?? false;
    }

    public static function clearAdmin(): void
    {
        unset($_SESSION['admin'], $_SESSION['admin_id'], $_SESSION['admin_logged_in']);
    }
}
