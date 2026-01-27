<?php
/**
 * API Vehicle Controller
 * Handles AJAX requests for vehicles
 */

namespace Api;

require_once APP_PATH . '/models/Vehicle.php';

class VehicleController extends \BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function list(): void
    {
        $vehicles = \Vehicle::findByUser($this->userId());

        $this->json([
            'success' => true,
            'vehicles' => array_map(function($v) {
                return [
                    'id' => $v['id'],
                    'make' => $v['make'],
                    'model' => $v['model'],
                    'year' => $v['year'],
                    'plate_number' => $v['plate_number'],
                    'display_name' => \Vehicle::getDisplayName($v),
                ];
            }, $vehicles)
        ]);
    }
}
