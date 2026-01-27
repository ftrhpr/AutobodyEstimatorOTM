<?php
/**
 * Estimate Controller
 * Handles guest estimate flow - Upload photos first, then register
 */

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/OtpCode.php';

class EstimateController extends BaseController
{
    /**
     * Show the photo upload page (Step 1)
     */
    public function index(): void
    {
        // If already logged in, redirect to regular report page
        if (Session::isLoggedIn()) {
            $this->redirect('/reports/new');
        }

        View::setLayout('estimate');
        
        // Get previously uploaded photos from session
        $uploadedPhotos = Session::get('guest_photos', []);
        
        $this->render('estimate/upload', [
            'title' => __('estimate.upload_photos'),
            'photos' => $uploadedPhotos,
            'step' => 1,
        ]);
    }

    /**
     * Handle photo upload via AJAX
     */
    public function upload(): void
    {
        CSRF::checkOrFail();

        header('Content-Type: application/json');

        if (!isset($_FILES['photos'])) {
            echo json_encode(['success' => false, 'message' => 'No files uploaded']);
            exit;
        }

        $uploadedPhotos = Session::get('guest_photos', []);
        
        // Check if limit reached
        if (count($uploadedPhotos) >= 10) {
            echo json_encode(['success' => false, 'message' => __('estimate.max_photos')]);
            exit;
        }

        // Handle the uploads
        $files = FileUpload::handleMultiple($_FILES['photos']);
        $newPhotos = [];
        
        foreach ($files as $file) {
            if (count($uploadedPhotos) + count($newPhotos) >= 10) {
                break;
            }

            $filename = $file->store('temp/' . session_id());
            
            if ($filename) {
                $photoData = [
                    'id' => uniqid(),
                    'filename' => $filename,
                    'path' => '/uploads/temp/' . session_id() . '/' . $filename,
                    'original_name' => $_FILES['photos']['name'][array_search($file, $files)] ?? 'photo.jpg',
                    'uploaded_at' => date('Y-m-d H:i:s'),
                ];
                $uploadedPhotos[] = $photoData;
                $newPhotos[] = $photoData;
            }
        }

        // Save to session
        Session::set('guest_photos', $uploadedPhotos);

        echo json_encode([
            'success' => true,
            'photos' => $newPhotos,
            'total' => count($uploadedPhotos),
            'message' => count($newPhotos) . ' ' . __('estimate.photos_uploaded'),
        ]);
        exit;
    }

    /**
     * Remove a photo
     */
    public function removePhoto(): void
    {
        CSRF::checkOrFail();

        header('Content-Type: application/json');

        $photoId = $this->input('photo_id');
        $uploadedPhotos = Session::get('guest_photos', []);
        
        $updatedPhotos = [];
        foreach ($uploadedPhotos as $photo) {
            if ($photo['id'] !== $photoId) {
                $updatedPhotos[] = $photo;
            } else {
                // Delete the actual file
                $filePath = PUBLIC_PATH . $photo['path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
        
        Session::set('guest_photos', $updatedPhotos);

        echo json_encode([
            'success' => true,
            'total' => count($updatedPhotos),
        ]);
        exit;
    }

    /**
     * Show registration form (Step 2)
     */
    public function showRegister(): void
    {
        // Must have photos first
        $photos = Session::get('guest_photos', []);
        
        if (empty($photos)) {
            $this->withError(__('estimate.upload_first'));
            $this->redirect('/estimate');
        }

        View::setLayout('estimate');
        
        $this->render('estimate/register', [
            'title' => __('auth.register'),
            'photos' => $photos,
            'step' => 2,
        ]);
    }

    /**
     * Handle registration with photos
     */
    public function register(): void
    {
        CSRF::checkOrFail();

        // Must have photos
        $photos = Session::get('guest_photos', []);
        if (empty($photos)) {
            $this->redirect('/estimate');
        }

        $data = $this->validate([
            'name' => 'required|min:2|max:100',
            'phone' => 'required|phone',
            'email' => 'email|max:100',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if phone already exists
        if (User::phoneExists($data['phone'])) {
            $this->withError(__('auth.phone_exists'));
            $this->withErrors(['phone' => [__('auth.phone_exists')]], $_POST);
            $this->redirect('/estimate/register');
        }

        // Store vehicle info in session for later
        $vehicleData = [
            'make' => $this->input('vehicle_make', ''),
            'model' => $this->input('vehicle_model', ''),
            'year' => $this->input('vehicle_year', ''),
            'description' => $this->input('damage_description', ''),
        ];
        Session::set('guest_vehicle_data', $vehicleData);

        // Create user with pending status
        $userId = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'password' => $data['password'],
            'status' => 'pending',
        ]);

        // Store for OTP verification
        Session::set('pending_user_id', $userId);
        Session::set('pending_phone', Validator::sanitizePhone($data['phone']));
        Session::set('from_estimate_flow', true);

        // Generate and send OTP
        $phone = Validator::sanitizePhone($data['phone']);
        $otp = OtpCode::generate($phone, OtpCode::TYPE_REGISTRATION);

        try {
            SMS::sendOTP($phone, $otp);
            $this->withSuccess(__('auth.otp_sent'));
        } catch (Exception $e) {
            error_log('SMS Error: ' . $e->getMessage());
            $this->withError('SMS could not be sent. Please try resending.');
        }

        $this->redirect('/verify-otp');
    }
}
