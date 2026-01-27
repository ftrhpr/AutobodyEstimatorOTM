<?php
/**
 * Admin User Controller
 * Handles user management
 */

namespace Admin;

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Vehicle.php';
require_once APP_PATH . '/models/DamageReport.php';

class UserController extends \BaseController
{
    public function __construct()
    {
        $this->requireAdmin();
    }

    public function index(): void
    {
        $filters = [
            'status' => $this->input('status'),
            'search' => $this->input('search'),
            'limit' => 50,
        ];

        $users = \User::all(array_filter($filters));

        // Get report counts for each user
        foreach ($users as &$user) {
            $user['report_count'] = \DamageReport::count(['user_id' => $user['id']]);
            $user['vehicle_count'] = \Vehicle::countByUser($user['id']);
        }

        \View::setLayout('admin');
        $this->render('admin/users/index', [
            'title' => __('admin.user_management'),
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    public function view(string $id): void
    {
        $user = \User::find((int) $id);

        if (!$user) {
            $this->withError(__('errors.not_found'));
            $this->redirect('/admin/users');
        }

        $vehicles = \Vehicle::findByUser((int) $id);
        $reports = \DamageReport::findByUser((int) $id);

        \View::setLayout('admin');
        $this->render('admin/users/view', [
            'title' => 'User: ' . $user['name'],
            'user' => $user,
            'vehicles' => $vehicles,
            'reports' => $reports,
        ]);
    }

    public function updateStatus(string $id): void
    {
        \CSRF::checkOrFail();

        $user = \User::find((int) $id);

        if (!$user) {
            if ($this->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            }
            $this->withError(__('errors.not_found'));
            $this->redirect('/admin/users');
        }

        $action = $this->input('action');

        if ($action === 'block') {
            \User::block((int) $id);
            $message = __('admin.user_blocked');
        } elseif ($action === 'unblock') {
            \User::unblock((int) $id);
            $message = __('admin.user_unblocked');
        } else {
            if ($this->isAjax()) {
                $this->json(['error' => 'Invalid action'], 400);
            }
            $this->withError('Invalid action');
            $this->redirect('/admin/users/' . $id);
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => $message]);
        }

        $this->withSuccess($message);
        $this->redirect('/admin/users/' . $id);
    }
}
