<?php
/**
 * Profile Controller
 * Handles user profile management
 */

require_once APP_PATH . '/models/User.php';

class ProfileController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $this->render('user/profile', [
            'title' => __('profile.my_profile'),
            'user' => $this->user(),
        ]);
    }

    public function update(): void
    {
        CSRF::checkOrFail();

        $data = $this->validate([
            'name' => 'required|min:2|max:100',
            'email' => 'email|max:100',
        ]);

        User::update($this->userId(), $data);

        // Update session
        $user = User::find($this->userId());
        Session::setUser($user);

        $this->withSuccess(__('profile.profile_updated'));
        $this->redirect('/profile');
    }

    public function changePassword(): void
    {
        CSRF::checkOrFail();

        $currentPassword = $this->input('current_password', '');
        $user = User::find($this->userId());

        if (!User::verifyPassword($user, $currentPassword)) {
            $this->withError('Current password is incorrect');
            $this->redirect('/profile');
        }

        $data = $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        User::update($this->userId(), ['password' => $data['password']]);

        $this->withSuccess(__('auth.password_changed'));
        $this->redirect('/profile');
    }
}
