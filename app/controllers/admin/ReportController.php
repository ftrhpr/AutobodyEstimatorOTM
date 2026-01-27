<?php
/**
 * Admin Report Controller
 * Handles report viewing and assessment
 */

namespace Admin;

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Vehicle.php';
require_once APP_PATH . '/models/DamageReport.php';
require_once APP_PATH . '/models/ReportPhoto.php';
require_once APP_PATH . '/models/Assessment.php';
require_once APP_PATH . '/models/AssessmentItem.php';

class ReportController extends \BaseController
{
    public function __construct()
    {
        $this->requireAdmin();
    }

    public function index(): void
    {
        // Get filters from query string
        $filters = [
            'status' => $this->input('status'),
            'search' => $this->input('search'),
            'date_from' => $this->input('date_from'),
            'date_to' => $this->input('date_to'),
            'limit' => 50,
        ];

        $reports = \DamageReport::all(array_filter($filters));

        \View::setLayout('admin');
        $this->render('admin/reports/index', [
            'title' => __('admin.all_reports'),
            'reports' => $reports,
            'statuses' => \DamageReport::getStatuses(),
            'filters' => $filters,
        ]);
    }

    public function view(string $id): void
    {
        $report = \DamageReport::findWithDetails((int) $id);

        if (!$report) {
            $this->withError(__('errors.not_found'));
            $this->redirect('/admin/reports');
        }

        $vehicle = \Vehicle::find($report['vehicle_id']);
        $photos = \ReportPhoto::findByReport((int) $id);
        $assessment = \Assessment::findWithItems((int) $id);

        \View::setLayout('admin');
        $this->render('admin/reports/view', [
            'title' => 'Report #' . $report['ticket_number'],
            'report' => $report,
            'vehicle' => $vehicle,
            'photos' => $photos,
            'assessment' => $assessment,
            'statuses' => \DamageReport::getStatuses(),
            'damageLocations' => \DamageReport::getDamageLocations(),
        ]);
    }

    public function assess(string $id): void
    {
        \CSRF::checkOrFail();

        $report = \DamageReport::find((int) $id);

        if (!$report) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Report not found'], 404);
            }
            $this->withError(__('errors.not_found'));
            $this->redirect('/admin/reports');
        }

        // Get assessment data
        $items = [];
        $descriptions = $this->input('item_description', []);
        $costs = $this->input('item_cost', []);

        for ($i = 0; $i < count($descriptions); $i++) {
            if (!empty($descriptions[$i]) && isset($costs[$i])) {
                $items[] = [
                    'description' => trim($descriptions[$i]),
                    'cost' => (float) $costs[$i],
                ];
            }
        }

        if (empty($items)) {
            if ($this->isAjax()) {
                $this->json(['error' => 'At least one repair item is required'], 400);
            }
            $this->withError('At least one repair item is required');
            $this->redirect('/admin/reports/' . $id);
        }

        \Database::beginTransaction();

        try {
            // Create or update assessment
            $assessmentId = \Assessment::createOrUpdate((int) $id, $this->adminId(), [
                'comments' => $this->input('comments', ''),
                'estimated_days' => $this->input('estimated_days') ?: null,
            ]);

            // Replace assessment items
            \AssessmentItem::replaceAll($assessmentId, $items);

            // Update report status
            \DamageReport::updateStatus((int) $id, 'assessed');

            \Database::commit();

            // Send SMS notification to user
            try {
                $user = \User::find($report['user_id']);
                $assessment = \Assessment::find($assessmentId);
                \SMS::sendAssessmentComplete($user['phone'], $report['ticket_number'], $assessment['total_cost']);
            } catch (\Exception $e) {
                error_log('SMS Error: ' . $e->getMessage());
            }

            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Assessment saved successfully']);
            }

            $this->withSuccess('Assessment saved successfully. User has been notified via SMS.');
            $this->redirect('/admin/reports/' . $id);

        } catch (\Exception $e) {
            \Database::rollback();
            error_log('Assessment error: ' . $e->getMessage());

            if ($this->isAjax()) {
                $this->json(['error' => 'Failed to save assessment'], 500);
            }

            $this->withError('Failed to save assessment');
            $this->redirect('/admin/reports/' . $id);
        }
    }

    public function updateStatus(string $id): void
    {
        \CSRF::checkOrFail();

        $report = \DamageReport::find((int) $id);

        if (!$report) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Report not found'], 404);
            }
            $this->withError(__('errors.not_found'));
            $this->redirect('/admin/reports');
        }

        $status = $this->input('status');
        $validStatuses = array_keys(\DamageReport::getStatuses());

        if (!in_array($status, $validStatuses)) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Invalid status'], 400);
            }
            $this->withError('Invalid status');
            $this->redirect('/admin/reports/' . $id);
        }

        \DamageReport::updateStatus((int) $id, $status);

        if ($this->isAjax()) {
            $this->json(['success' => true, 'status' => $status]);
        }

        $this->withSuccess('Report status updated');
        $this->redirect('/admin/reports/' . $id);
    }
}
