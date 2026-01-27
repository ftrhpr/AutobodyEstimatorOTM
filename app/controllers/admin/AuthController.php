<?php
/**
 * Admin Authentication Controller
 */

namespace Admin;

require_once APP_PATH . '/models/Admin.php';

class AuthController extends \BaseController
{
    public function showLogin(): void
    {
        if (\Session::isAdminLoggedIn()) {
            $this->redirect('/admin');
        }

        \View::setLayout('auth');
        $this->render('admin/login', ['title' => __('admin.admin_panel')]);
    }

    public function login(): void
    {
        \CSRF::checkOrFail();

        $username = $this->input('username', '');
        $password = $this->input('password', '');

        $admin = \Admin::findByUsername($username);

        if (!$admin || !\Admin::verifyPassword($admin, $password)) {
            $this->withError(__('auth.invalid_credentials'));
            $this->redirect('/admin/login');
        }

        // Update last login
        \Admin::updateLastLogin($admin['id']);

        // Set admin session
        \Session::setAdmin($admin);

        $this->withSuccess('Welcome back, ' . $admin['name']);
        $this->redirect('/admin');
    }

    public function logout(): void
    {
        \Session::clearAdmin();
        $this->withSuccess(__('auth.logout_success'));
        $this->redirect('/admin/login');
    }
}
