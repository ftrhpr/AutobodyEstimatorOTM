<?php
/**
 * Dashboard Controller
 * User dashboard with reports overview
 */

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Vehicle.php';
require_once APP_PATH . '/models/DamageReport.php';
require_once APP_PATH . '/models/Assessment.php';

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $userId = $this->userId();

        // Get user's vehicles
        $vehicles = Vehicle::findByUser($userId);

        // Get user's reports
        $reports = DamageReport::findByUser($userId);

        // Calculate statistics
        $stats = [
            'total_reports' => count($reports),
            'pending' => count(array_filter($reports, fn($r) => $r['status'] === 'pending')),
            'under_review' => count(array_filter($reports, fn($r) => $r['status'] === 'under_review')),
            'assessed' => count(array_filter($reports, fn($r) => $r['status'] === 'assessed')),
            'vehicles' => count($vehicles),
        ];

        // Get recent reports (last 5)
        $recentReports = array_slice($reports, 0, 5);

        // Get assessments for assessed reports
        foreach ($recentReports as &$report) {
            if ($report['status'] === 'assessed') {
                $report['assessment'] = Assessment::findByReport($report['id']);
            }
        }

        $this->render('user/dashboard', [
            'title' => __('dashboard'),
            'stats' => $stats,
            'vehicles' => $vehicles,
            'recentReports' => $recentReports,
        ]);
    }
}
