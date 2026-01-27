<?php
/**
 * English Language Translations
 */

return [
    // General
    'app_name' => 'Auto Damage Assessment',
    'welcome' => 'Welcome',
    'home' => 'Home',
    'dashboard' => 'Dashboard',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'view' => 'View',
    'back' => 'Back',
    'submit' => 'Submit',
    'search' => 'Search',
    'filter' => 'Filter',
    'actions' => 'Actions',
    'status' => 'Status',
    'date' => 'Date',
    'download' => 'Download',
    'yes' => 'Yes',
    'no' => 'No',
    'loading' => 'Loading...',
    'error' => 'Error',
    'success' => 'Success',
    'warning' => 'Warning',
    'or' => 'or',

    // Authentication
    'auth' => [
        'login' => 'Login',
        'logout' => 'Logout',
        'register' => 'Register',
        'phone' => 'Phone Number',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'password_confirmation' => 'Confirm Password',
        'name' => 'Full Name',
        'email' => 'Email (optional)',
        'forgot_password' => 'Forgot Password?',
        'reset_password' => 'Reset Password',
        'new_password' => 'New Password',
        'verify_otp' => 'Verify SMS Code',
        'otp_code' => 'Verification Code',
        'otp_sent' => 'Verification code has been sent to your phone',
        'resend_otp' => 'Resend Code',
        'invalid_credentials' => 'Invalid phone number or password',
        'invalid_otp' => 'Invalid verification code',
        'otp_expired' => 'Verification code has expired',
        'phone_exists' => 'This phone number is already registered',
        'registration_success' => 'Registration completed successfully',
        'login_success' => 'Successfully logged in',
        'logout_success' => 'Successfully logged out',
        'password_changed' => 'Password changed successfully',
        'session_timeout' => 'Your session has expired. Please login again.',
        'remember_me' => 'Remember me',
    ],

    // Vehicles
    'vehicle' => [
        'vehicles' => 'Vehicles',
        'my_vehicles' => 'My Vehicles',
        'add_vehicle' => 'Add Vehicle',
        'edit_vehicle' => 'Edit Vehicle',
        'make' => 'Make',
        'model' => 'Model',
        'year' => 'Year',
        'plate_number' => 'License Plate',
        'vin' => 'VIN',
        'select_vehicle' => 'Select Vehicle',
        'add_new' => 'Add New',
        'no_vehicles' => 'You haven\'t added any vehicles yet',
        'vehicle_added' => 'Vehicle added successfully',
        'vehicle_updated' => 'Vehicle information updated',
        'vehicle_deleted' => 'Vehicle deleted',
        'confirm_delete' => 'Are you sure you want to delete this vehicle?',
    ],

    // Reports
    'report' => [
        'reports' => 'Reports',
        'my_reports' => 'My Reports',
        'new_report' => 'New Report',
        'submit_report' => 'Submit Report',
        'ticket_number' => 'Ticket Number',
        'description' => 'Damage Description',
        'damage_location' => 'Damage Location',
        'urgency' => 'Urgency',
        'photos' => 'Photos',
        'upload_photos' => 'Upload Photos',
        'add_more_photos' => 'Add More Photos',
        'max_photos' => 'Maximum :count photos',
        'max_size' => 'Maximum :size MB each',
        'no_reports' => 'You haven\'t submitted any reports yet',
        'report_submitted' => 'Report submitted successfully',
        'download_pdf' => 'Download PDF',

        // Status
        'status_pending' => 'Pending',
        'status_under_review' => 'Under Review',
        'status_assessed' => 'Assessed',
        'status_closed' => 'Closed',

        // Damage locations
        'location_front' => 'Front',
        'location_rear' => 'Rear',
        'location_left' => 'Left Side',
        'location_right' => 'Right Side',
        'location_roof' => 'Roof',
        'location_hood' => 'Hood',
        'location_trunk' => 'Trunk',
        'location_windshield' => 'Windshield',
        'location_other' => 'Other',

        // Urgency
        'urgency_standard' => 'Standard',
        'urgency_urgent' => 'Urgent',
    ],

    // Assessment
    'assessment' => [
        'assessment' => 'Assessment',
        'total_cost' => 'Total Cost',
        'repair_items' => 'Repair Items',
        'item_description' => 'Description',
        'item_cost' => 'Cost',
        'comments' => 'Comments',
        'recommendations' => 'Recommendations',
        'estimated_days' => 'Estimated Time (days)',
        'assessed_by' => 'Assessed By',
        'assessed_at' => 'Assessment Date',
        'no_assessment' => 'Assessment not yet available',
        'add_item' => 'Add Item',
        'save_assessment' => 'Save Assessment',
    ],

    // Profile
    'profile' => [
        'profile' => 'Profile',
        'my_profile' => 'My Profile',
        'edit_profile' => 'Edit Profile',
        'change_password' => 'Change Password',
        'current_password' => 'Current Password',
        'profile_updated' => 'Profile updated successfully',
    ],

    // Admin
    'admin' => [
        'admin_panel' => 'Admin Panel',
        'dashboard' => 'Dashboard',
        'statistics' => 'Statistics',
        'total_reports' => 'Total Reports',
        'pending_reports' => 'Pending',
        'today_reports' => 'Today',
        'total_users' => 'Total Users',
        'users' => 'Users',
        'all_reports' => 'All Reports',
        'user_management' => 'User Management',
        'block_user' => 'Block',
        'unblock_user' => 'Unblock',
        'user_blocked' => 'User has been blocked',
        'user_unblocked' => 'User has been unblocked',
    ],

    // Estimate Flow
    'estimate' => [
        'upload_photos' => 'Upload Damage Photos',
        'upload_first' => 'Please upload photos first',
        'max_photos' => 'Maximum 10 photos allowed',
        'photos_uploaded' => 'photos uploaded',
        'continue_register' => 'Continue to Register',
        'start_estimate' => 'Start Estimate',
        'get_started' => 'Get Started Now',
    ],

    // SMS Messages
    'sms' => [
        'otp_message' => 'Your verification code is: :otp. Code is valid for 5 minutes.',
        'report_submitted' => 'Your report #:ticket has been received. We will notify you once assessed.',
        'assessment_complete' => 'Your report #:ticket has been assessed. Total cost: :total GEL. Visit your dashboard for details.',
    ],

    // Errors
    'errors' => [
        'not_found' => 'Page not found',
        'unauthorized' => 'Unauthorized access',
        'forbidden' => 'Access forbidden',
        'server_error' => 'Server error. Please try again later.',
        'validation_failed' => 'The submitted data is invalid',
    ],
];
