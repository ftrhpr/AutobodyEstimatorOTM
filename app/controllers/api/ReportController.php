<?php
/**
 * API Report Controller
 * Handles AJAX requests for reports
 */

namespace Api;

require_once APP_PATH . '/models/DamageReport.php';

class ReportController extends \BaseController
{
    public function stats(): void
    {
        if (\Session::isAdminLoggedIn()) {
            // Admin stats
            $stats = [
                'total' => \DamageReport::count(),
                'pending' => \DamageReport::count(['status' => 'pending']),
                'under_review' => \DamageReport::count(['status' => 'under_review']),
                'assessed' => \DamageReport::count(['status' => 'assessed']),
                'today' => \DamageReport::count(['today' => true]),
            ];
        } elseif (\Session::isLoggedIn()) {
            // User stats
            $userId = $this->userId();
            $stats = [
                'total' => \DamageReport::count(['user_id' => $userId]),
                'pending' => \DamageReport::count(['user_id' => $userId, 'status' => 'pending']),
                'assessed' => \DamageReport::count(['user_id' => $userId, 'status' => 'assessed']),
            ];
        } else {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $this->json(['success' => true, 'stats' => $stats]);
    }
}
