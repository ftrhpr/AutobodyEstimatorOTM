<?php
/**
 * Authentication Controller
 * Handles user registration, login, OTP verification, and password recovery
 */

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/OtpCode.php';

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        View::setLayout('auth');
        $this->render('auth/login', ['title' => __('auth.login')]);
    }

    public function login(): void
    {
        CSRF::checkOrFail();

        $phone = Validator::sanitizePhone($this->input('phone', ''));
        $password = $this->input('password', '');

        $user = User::findByPhone($phone);

        if (!$user || !User::verifyPassword($user, $password)) {
            $this->withError(__('auth.invalid_credentials'));
            $this->withErrors([], ['phone' => $phone]);
            $this->redirect('/login');
        }

        if ($user['status'] === 'blocked') {
            $this->withError(__('errors.forbidden'));
            $this->redirect('/login');
        }

        if ($user['status'] === 'pending') {
            // User needs to verify OTP
            Session::set('pending_user_id', $user['id']);
            Session::set('pending_phone', $user['phone']);

            // Generate and send OTP
            $otp = OtpCode::generate($user['phone'], OtpCode::TYPE_VERIFICATION);

            try {
                SMS::sendOTP($user['phone'], $otp);
            } catch (Exception $e) {
                // Log error but continue
                error_log('SMS Error: ' . $e->getMessage());
            }

            $this->redirect('/verify-otp');
        }

        // Login successful
        Session::setUser($user);
        $this->withSuccess(__('auth.login_success'));
        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        View::setLayout('auth');
        $this->render('auth/register', ['title' => __('auth.register')]);
    }

    public function register(): void
    {
        CSRF::checkOrFail();

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
            $this->redirect('/register');
        }

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

        // Generate and send OTP
        $phone = Validator::sanitizePhone($data['phone']);
        $otp = OtpCode::generate($phone, OtpCode::TYPE_REGISTRATION);

        try {
            SMS::sendOTP($phone, $otp);
            $this->withSuccess(__('auth.otp_sent'));
        } catch (Exception $e) {
            error_log('SMS Error: ' . $e->getMessage());
            // Still redirect to OTP page, user can resend
            $this->withError('SMS could not be sent. Please try resending.');
        }

        $this->redirect('/verify-otp');
    }

    public function showVerifyOtp(): void
    {
        if (!Session::has('pending_phone')) {
            $this->redirect('/register');
        }

        View::setLayout('auth');
        $this->render('auth/verify-otp', [
            'title' => __('auth.verify_otp'),
            'phone' => Session::get('pending_phone'),
        ]);
    }

    public function verifyOtp(): void
    {
        CSRF::checkOrFail();

        $phone = Session::get('pending_phone');
        $userId = Session::get('pending_user_id');
        $code = $this->input('otp', '');

        if (!$phone || !$userId) {
            $this->redirect('/register');
        }

        // Determine OTP type
        $type = Session::has('password_reset')
            ? OtpCode::TYPE_PASSWORD_RESET
            : (User::find($userId)['status'] === 'pending' ? OtpCode::TYPE_REGISTRATION : OtpCode::TYPE_VERIFICATION);

        if (!OtpCode::verify($phone, $code, $type)) {
            $this->withError(__('auth.invalid_otp'));
            $this->redirect('/verify-otp');
        }

        // Handle password reset flow
        if (Session::has('password_reset')) {
            $token = bin2hex(random_bytes(32));
            Database::insert('password_resets', [
                'phone' => $phone,
                'token' => $token,
                'expires_at' => date('Y-m-d H:i:s', time() + 3600),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Session::set('reset_token', $token);
            Session::remove('pending_phone');
            Session::remove('pending_user_id');
            Session::remove('password_reset');

            $this->redirect('/reset-password');
        }

        // Activate user
        User::activate($userId);

        // Get fresh user data and log them in
        $user = User::find($userId);
        Session::setUser($user);

        // Check if coming from estimate flow
        $fromEstimate = Session::get('from_estimate_flow', false);
        $guestPhotos = Session::get('guest_photos', []);
        $vehicleData = Session::get('guest_vehicle_data', []);

        // Clean up session
        Session::remove('pending_phone');
        Session::remove('pending_user_id');
        Session::remove('from_estimate_flow');
        Session::remove('guest_vehicle_data');

        // If from estimate flow with photos, create a report
        if ($fromEstimate && !empty($guestPhotos)) {
            $this->createReportFromEstimate($user, $guestPhotos, $vehicleData);
            Session::remove('guest_photos');
            $this->withSuccess(__('auth.registration_success') . ' ' . __('report.report_submitted'));
            $this->redirect('/reports');
        }

        $this->withSuccess(__('auth.registration_success'));
        $this->redirect('/dashboard');
    }

    /**
     * Create report from estimate flow
     */
    private function createReportFromEstimate(array $user, array $photos, array $vehicleData): void
    {
        require_once APP_PATH . '/models/Vehicle.php';
        require_once APP_PATH . '/models/DamageReport.php';
        require_once APP_PATH . '/models/ReportPhoto.php';

        $vehicleId = null;

        // Create vehicle if info provided
        if (!empty($vehicleData['make']) || !empty($vehicleData['model'])) {
            $vehicleId = Vehicle::create([
                'user_id' => $user['id'],
                'make' => $vehicleData['make'] ?? '',
                'model' => $vehicleData['model'] ?? '',
                'year' => $vehicleData['year'] ?? null,
            ]);
        }

        // Create the report
        $reportId = DamageReport::create([
            'user_id' => $user['id'],
            'vehicle_id' => $vehicleId,
            'description' => $vehicleData['description'] ?? 'Uploaded via quick estimate',
            'status' => 'pending',
        ]);

        // Move photos from temp to reports directory and save
        foreach ($photos as $photo) {
            $tempPath = PUBLIC_PATH . $photo['path'];
            if (file_exists($tempPath)) {
                // Create new filename and move to reports directory
                $newFilename = 'report_' . $reportId . '_' . uniqid() . '_' . pathinfo($photo['filename'], PATHINFO_EXTENSION);
                $newDir = PUBLIC_PATH . '/uploads/reports/' . $reportId;
                
                if (!is_dir($newDir)) {
                    mkdir($newDir, 0755, true);
                }
                
                $newPath = $newDir . '/' . $newFilename;
                rename($tempPath, $newPath);

                // Save to database
                ReportPhoto::create([
                    'report_id' => $reportId,
                    'filename' => $newFilename,
                    'original_name' => $photo['original_name'] ?? $newFilename,
                ]);
            }
        }

        // Clean up temp directory
        $tempDir = PUBLIC_PATH . '/uploads/temp/' . session_id();
        if (is_dir($tempDir)) {
            @rmdir($tempDir);
        }
    }

    public function resendOtp(): void
    {
        CSRF::checkOrFail();

        $phone = Session::get('pending_phone');

        if (!$phone) {
            $this->json(['error' => 'Invalid session'], 400);
        }

        if (!OtpCode::canResend($phone)) {
            $this->json(['error' => 'Please wait before requesting another code'], 429);
        }

        $type = Session::has('password_reset') ? OtpCode::TYPE_PASSWORD_RESET : OtpCode::TYPE_REGISTRATION;
        $otp = OtpCode::generate($phone, $type);

        try {
            SMS::sendOTP($phone, $otp);
            $this->json(['success' => true, 'message' => __('auth.otp_sent')]);
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showForgotPassword(): void
    {
        View::setLayout('auth');
        $this->render('auth/forgot-password', ['title' => __('auth.forgot_password')]);
    }

    public function forgotPassword(): void
    {
        CSRF::checkOrFail();

        $phone = Validator::sanitizePhone($this->input('phone', ''));
        $user = User::findByPhone($phone);

        if (!$user) {
            // Don't reveal if user exists
            $this->withSuccess(__('auth.otp_sent'));
            $this->redirect('/verify-otp');
        }

        // Generate OTP for password reset
        $otp = OtpCode::generate($phone, OtpCode::TYPE_PASSWORD_RESET);

        Session::set('pending_phone', $phone);
        Session::set('pending_user_id', $user['id']);
        Session::set('password_reset', true);

        try {
            SMS::sendOTP($phone, $otp);
        } catch (Exception $e) {
            error_log('SMS Error: ' . $e->getMessage());
        }

        $this->withSuccess(__('auth.otp_sent'));
        $this->redirect('/verify-otp');
    }

    public function showResetPassword(): void
    {
        $token = Session::get('reset_token');

        if (!$token) {
            $this->redirect('/forgot-password');
        }

        // Verify token is valid
        $reset = Database::fetchOne(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() AND used_at IS NULL",
            [$token]
        );

        if (!$reset) {
            Session::remove('reset_token');
            $this->withError(__('auth.otp_expired'));
            $this->redirect('/forgot-password');
        }

        View::setLayout('auth');
        $this->render('auth/reset-password', ['title' => __('auth.reset_password')]);
    }

    public function resetPassword(): void
    {
        CSRF::checkOrFail();

        $token = Session::get('reset_token');

        if (!$token) {
            $this->redirect('/forgot-password');
        }

        $reset = Database::fetchOne(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() AND used_at IS NULL",
            [$token]
        );

        if (!$reset) {
            Session::remove('reset_token');
            $this->withError(__('auth.otp_expired'));
            $this->redirect('/forgot-password');
        }

        $data = $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Update password
        $user = User::findByPhone($reset['phone']);
        if ($user) {
            User::update($user['id'], ['password' => $data['password']]);

            // Mark reset token as used
            Database::update('password_resets', ['used_at' => date('Y-m-d H:i:s')], 'id = ?', [$reset['id']]);
        }

        Session::remove('reset_token');
        $this->withSuccess(__('auth.password_changed'));
        $this->redirect('/login');
    }

    public function logout(): void
    {
        Session::destroy();
        Session::start(config('session'));
        $this->withSuccess(__('auth.logout_success'));
        $this->redirect('/login');
    }
}
