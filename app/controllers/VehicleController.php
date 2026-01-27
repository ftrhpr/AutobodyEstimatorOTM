<?php
/**
 * Vehicle Controller
 * Handles vehicle CRUD operations
 */

require_once APP_PATH . '/models/Vehicle.php';

class VehicleController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $vehicles = Vehicle::findByUser($this->userId());

        $this->render('user/vehicles/index', [
            'title' => __('vehicle.my_vehicles'),
            'vehicles' => $vehicles,
        ]);
    }

    public function showAdd(): void
    {
        $this->render('user/vehicles/form', [
            'title' => __('vehicle.add_vehicle'),
            'vehicle' => null,
            'makes' => Vehicle::getMakes(),
            'years' => Vehicle::getYearRange(),
        ]);
    }

    public function add(): void
    {
        CSRF::checkOrFail();

        $data = $this->validate([
            'make' => 'required|max:50',
            'model' => 'required|max:50',
            'year' => 'required|year',
            'plate_number' => 'max:20',
            'vin' => 'max:17',
        ]);

        $data['user_id'] = $this->userId();

        Vehicle::create($data);

        $this->withSuccess(__('vehicle.vehicle_added'));

        // If coming from report creation, redirect back
        if (Session::has('redirect_after_vehicle')) {
            $redirect = Session::get('redirect_after_vehicle');
            Session::remove('redirect_after_vehicle');
            $this->redirect($redirect);
        }

        $this->redirect('/vehicles');
    }

    public function showEdit(string $id): void
    {
        $vehicle = Vehicle::findByIdAndUser((int) $id, $this->userId());

        if (!$vehicle) {
            $this->withError(__('errors.not_found'));
            $this->redirect('/vehicles');
        }

        $this->render('user/vehicles/form', [
            'title' => __('vehicle.edit_vehicle'),
            'vehicle' => $vehicle,
            'makes' => Vehicle::getMakes(),
            'years' => Vehicle::getYearRange(),
        ]);
    }

    public function edit(string $id): void
    {
        CSRF::checkOrFail();

        $vehicle = Vehicle::findByIdAndUser((int) $id, $this->userId());

        if (!$vehicle) {
            $this->withError(__('errors.not_found'));
            $this->redirect('/vehicles');
        }

        $data = $this->validate([
            'make' => 'required|max:50',
            'model' => 'required|max:50',
            'year' => 'required|year',
            'plate_number' => 'max:20',
            'vin' => 'max:17',
        ]);

        Vehicle::update((int) $id, $data);

        $this->withSuccess(__('vehicle.vehicle_updated'));
        $this->redirect('/vehicles');
    }

    public function delete(string $id): void
    {
        CSRF::checkOrFail();

        $vehicle = Vehicle::findByIdAndUser((int) $id, $this->userId());

        if (!$vehicle) {
            $this->withError(__('errors.not_found'));
            $this->redirect('/vehicles');
        }

        // Check if vehicle has reports
        if (Vehicle::hasReports((int) $id)) {
            $this->withError('Cannot delete vehicle with existing reports');
            $this->redirect('/vehicles');
        }

        Vehicle::delete((int) $id);

        $this->withSuccess(__('vehicle.vehicle_deleted'));
        $this->redirect('/vehicles');
    }
}
