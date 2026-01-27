<?php
/**
 * Admin Dashboard Controller
 */

namespace Admin;

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/DamageReport.php';

class DashboardController extends \BaseController
{
    public function __construct()
    {
        $this->requireAdmin();
    }

    public function index(): void
    {
        // Calculate statistics
        $stats = [
            'total_reports' => \DamageReport::count(),
            'pending_reports' => \DamageReport::count(['status' => 'pending']),
            'under_review' => \DamageReport::count(['status' => 'under_review']),
            'assessed_reports' => \DamageReport::count(['status' => 'assessed']),
            'today_reports' => \DamageReport::count(['today' => true]),
            'total_users' => \User::count(),
            'active_users' => \User::count(['status' => 'active']),
        ];

        // Get recent reports
        $recentReports = \DamageReport::all(['limit' => 10]);

        // Get pending reports for quick access
        $pendingReports = \DamageReport::all(['status' => 'pending', 'limit' => 5]);

        \View::setLayout('admin');
        $this->render('admin/dashboard', [
            'title' => __('admin.dashboard'),
            'stats' => $stats,
            'recentReports' => $recentReports,
            'pendingReports' => $pendingReports,
        ]);
    }
}
