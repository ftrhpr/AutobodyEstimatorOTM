<?php
/**
 * Base Controller
 * Parent class for all controllers
 */

abstract class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        View::json($data, $statusCode);
    }

    protected function redirect(string $url): void
    {
        Router::redirect($url);
    }

    protected function back(): void
    {
        Router::back();
    }

    protected function withErrors(array $errors, array $oldInput = []): void
    {
        Session::flash('errors', $errors);
        Session::flash('old', $oldInput);
    }

    protected function withSuccess(string $message): void
    {
        Session::flash('success', $message);
    }

    protected function withError(string $message): void
    {
        Session::flash('error', $message);
    }

    protected function validate(array $rules): array
    {
        $validator = Validator::make($_POST)->validate($rules);

        if ($validator->fails()) {
            $this->withErrors($validator->errors(), $_POST);
            $this->back();
        }

        return $validator->validated();
    }

    protected function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', __('errors.unauthorized'));
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        if (!Session::isAdminLoggedIn()) {
            $this->redirect('/admin/login');
        }
    }

    protected function user(): ?array
    {
        return Session::getUser();
    }

    protected function userId(): ?int
    {
        return Session::getUserId();
    }

    protected function admin(): ?array
    {
        return Session::getAdmin();
    }

    protected function adminId(): ?int
    {
        return Session::getAdminId();
    }

    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    protected function has(string $key): bool
    {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }

    protected function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    protected function hasFile(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }
}
