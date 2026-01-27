<?php
/**
 * Report Controller
 * Handles damage report submission and viewing
 */

require_once APP_PATH . '/models/Vehicle.php';
require_once APP_PATH . '/models/DamageReport.php';
require_once APP_PATH . '/models/ReportPhoto.php';
require_once APP_PATH . '/models/Assessment.php';
require_once APP_PATH . '/models/AssessmentItem.php';

class ReportController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $reports = DamageReport::findByUser($this->userId());

        // Get photo count for each report
        foreach ($reports as &$report) {
            $report['photo_count'] = ReportPhoto::countByReport($report['id']);
            if ($report['status'] === 'assessed') {
                $report['assessment'] = Assessment::findByReport($report['id']);
            }
        }

        $this->render('user/reports/index', [
            'title' => __('report.my_reports'),
            'reports' => $reports,
            'statuses' => DamageReport::getStatuses(),
        ]);
    }

    public function showNew(): void
    {
        $vehicles = Vehicle::findByUser($this->userId());

        // If no vehicles, redirect to add vehicle first
        if (empty($vehicles)) {
            Session::set('redirect_after_vehicle', '/reports/new');
            $this->withError('Please add a vehicle first before submitting a report');
            $this->redirect('/vehicles/add');
        }

        $this->render('user/reports/new', [
            'title' => __('report.new_report'),
            'vehicles' => $vehicles,
            'damageLocations' => DamageReport::getDamageLocations(),
            'urgencyLevels' => DamageReport::getUrgencyLevels(),
            'maxPhotos' => ReportPhoto::getMaxPhotosPerReport(),
            'maxSize' => config('upload.max_size', 5 * 1024 * 1024),
        ]);
    }

    public function create(): void
    {
        CSRF::checkOrFail();

        $data = $this->validate([
            'vehicle_id' => 'required|integer',
            'description' => 'required|min:10|max:2000',
            'damage_location' => 'required|in:front,rear,left,right,roof,hood,trunk,windshield,other',
            'urgency' => 'required|in:standard,urgent',
        ]);

        // Verify vehicle belongs to user
        $vehicle = Vehicle::findByIdAndUser((int) $data['vehicle_id'], $this->userId());
        if (!$vehicle) {
            $this->withError('Invalid vehicle selected');
            $this->redirect('/reports/new');
        }

        // Handle file uploads
        if (!$this->hasFile('photos')) {
            $this->withError('Please upload at least one photo of the damage');
            $this->withErrors([], $_POST);
            $this->redirect('/reports/new');
        }

        // Start transaction
        Database::beginTransaction();

        try {
            // Create report
            $reportId = DamageReport::create([
                'user_id' => $this->userId(),
                'vehicle_id' => $data['vehicle_id'],
                'description' => $data['description'],
                'damage_location' => $data['damage_location'],
                'urgency' => $data['urgency'],
            ]);

            // Get report for ticket number
            $report = DamageReport::find($reportId);

            // Handle photo uploads
            $uploads = FileUpload::handleMultiple($_FILES['photos']);
            $photoDirectory = 'reports/' . $report['ticket_number'];
            $uploadedPhotos = [];

            foreach ($uploads as $upload) {
                if ($upload->validate()) {
                    $path = $upload->store($photoDirectory);
                    if ($path) {
                        $uploadedPhotos[] = [
                            'path' => $path,
                            'original_name' => $_FILES['photos']['name'][array_search($upload, $uploads)],
                            'size' => $_FILES['photos']['size'][array_search($upload, $uploads)],
                        ];
                    }
                }
            }

            if (empty($uploadedPhotos)) {
                throw new Exception('Failed to upload photos');
            }

            // Save photo records
            ReportPhoto::createMany($reportId, $uploadedPhotos);

            Database::commit();

            // Send SMS notification
            try {
                $user = $this->user();
                SMS::sendReportConfirmation($user['phone'], $report['ticket_number']);
            } catch (Exception $e) {
                error_log('SMS Error: ' . $e->getMessage());
            }

            $this->withSuccess(__('report.report_submitted') . ' Ticket: ' . $report['ticket_number']);
            $this->redirect('/reports/' . $reportId);

        } catch (Exception $e) {
            Database::rollback();
            error_log('Report creation error: ' . $e->getMessage());
            $this->withError('Failed to submit report. Please try again.');
            $this->redirect('/reports/new');
        }
    }

    public function view(string $id): void
    {
        $report = DamageReport::findByIdAndUser((int) $id, $this->userId());

        if (!$report) {
            $this->withError(__('errors.not_found'));
            $this->redirect('/reports');
        }

        // Get vehicle details
        $vehicle = Vehicle::find($report['vehicle_id']);

        // Get photos
        $photos = ReportPhoto::findByReport((int) $id);

        // Get assessment if exists
        $assessment = null;
        $assessmentItems = [];
        if ($report['status'] === 'assessed') {
            $assessment = Assessment::findWithItems((int) $id);
            if ($assessment) {
                $assessmentItems = $assessment['items'] ?? [];
            }
        }

        $this->render('user/reports/view', [
            'title' => 'Report #' . $report['ticket_number'],
            'report' => $report,
            'vehicle' => $vehicle,
            'photos' => $photos,
            'assessment' => $assessment,
            'assessmentItems' => $assessmentItems,
            'statuses' => DamageReport::getStatuses(),
            'damageLocations' => DamageReport::getDamageLocations(),
        ]);
    }

    public function addPhotos(string $id): void
    {
        CSRF::checkOrFail();

        $report = DamageReport::findByIdAndUser((int) $id, $this->userId());

        if (!$report) {
            $this->json(['error' => 'Report not found'], 404);
        }

        // Check if report can be modified
        if (!in_array($report['status'], ['pending', 'under_review'])) {
            $this->json(['error' => 'Cannot add photos to assessed reports'], 400);
        }

        // Check current photo count
        $currentCount = ReportPhoto::countByReport((int) $id);
        $maxPhotos = ReportPhoto::getMaxPhotosPerReport();

        if ($currentCount >= $maxPhotos) {
            $this->json(['error' => "Maximum {$maxPhotos} photos allowed"], 400);
        }

        if (!$this->hasFile('photos')) {
            $this->json(['error' => 'No photos provided'], 400);
        }

        $uploads = FileUpload::handleMultiple($_FILES['photos']);
        $photoDirectory = 'reports/' . $report['ticket_number'];
        $uploadedPhotos = [];

        $remainingSlots = $maxPhotos - $currentCount;

        foreach (array_slice($uploads, 0, $remainingSlots) as $upload) {
            if ($upload->validate()) {
                $path = $upload->store($photoDirectory);
                if ($path) {
                    $uploadedPhotos[] = ['path' => $path];
                }
            }
        }

        if (!empty($uploadedPhotos)) {
            ReportPhoto::createMany((int) $id, $uploadedPhotos);
            $this->json(['success' => true, 'count' => count($uploadedPhotos)]);
        } else {
            $this->json(['error' => 'Failed to upload photos'], 500);
        }
    }

    public function downloadPdf(string $id): void
    {
        $report = DamageReport::findByIdAndUser((int) $id, $this->userId());

        if (!$report || $report['status'] !== 'assessed') {
            $this->withError('Assessment not available for download');
            $this->redirect('/reports/' . $id);
        }

        $vehicle = Vehicle::find($report['vehicle_id']);
        $assessment = Assessment::findWithItems((int) $id);

        if (!$assessment) {
            $this->withError('Assessment not found');
            $this->redirect('/reports/' . $id);
        }

        // Generate PDF
        require_once APP_PATH . '/helpers/PDF.php';

        $pdf = new PDF();
        $pdf->generateAssessmentReport($report, $vehicle, $assessment);
        $pdf->output('Assessment_' . $report['ticket_number'] . '.pdf');
        exit;
    }
}
