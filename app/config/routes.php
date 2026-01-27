<?php
/**
 * Application Routes
 * Define all URL routes and their handlers
 */

return [
    // Public Routes
    'GET /' => ['HomeController', 'index'],
    'GET /language/{lang}' => ['HomeController', 'setLanguage'],

    // Guest Estimate Flow (Upload photos first, then register)
    'GET /estimate' => ['EstimateController', 'index'],
    'POST /estimate/upload' => ['EstimateController', 'upload'],
    'POST /estimate/remove-photo' => ['EstimateController', 'removePhoto'],
    'GET /estimate/register' => ['EstimateController', 'showRegister'],
    'POST /estimate/register' => ['EstimateController', 'register'],

    // Authentication Routes
    'GET /login' => ['AuthController', 'showLogin'],
    'POST /login' => ['AuthController', 'login'],
    'GET /register' => ['AuthController', 'showRegister'],
    'POST /register' => ['AuthController', 'register'],
    'GET /verify-otp' => ['AuthController', 'showVerifyOtp'],
    'POST /verify-otp' => ['AuthController', 'verifyOtp'],
    'POST /resend-otp' => ['AuthController', 'resendOtp'],
    'GET /forgot-password' => ['AuthController', 'showForgotPassword'],
    'POST /forgot-password' => ['AuthController', 'forgotPassword'],
    'GET /reset-password' => ['AuthController', 'showResetPassword'],
    'POST /reset-password' => ['AuthController', 'resetPassword'],
    'GET /logout' => ['AuthController', 'logout'],

    // User Dashboard Routes (Protected)
    'GET /dashboard' => ['DashboardController', 'index'],

    // Vehicle Routes (Protected)
    'GET /vehicles' => ['VehicleController', 'index'],
    'GET /vehicles/add' => ['VehicleController', 'showAdd'],
    'POST /vehicles/add' => ['VehicleController', 'add'],
    'GET /vehicles/edit/{id}' => ['VehicleController', 'showEdit'],
    'POST /vehicles/edit/{id}' => ['VehicleController', 'edit'],
    'POST /vehicles/delete/{id}' => ['VehicleController', 'delete'],

    // Damage Report Routes (Protected)
    'GET /reports' => ['ReportController', 'index'],
    'GET /reports/new' => ['ReportController', 'showNew'],
    'POST /reports/new' => ['ReportController', 'create'],
    'GET /reports/{id}' => ['ReportController', 'view'],
    'GET /reports/{id}/pdf' => ['ReportController', 'downloadPdf'],
    'POST /reports/{id}/photos' => ['ReportController', 'addPhotos'],

    // User Profile Routes (Protected)
    'GET /profile' => ['ProfileController', 'index'],
    'POST /profile' => ['ProfileController', 'update'],
    'POST /profile/password' => ['ProfileController', 'changePassword'],

    // Admin Routes
    'GET /admin/login' => ['Admin\\AuthController', 'showLogin'],
    'POST /admin/login' => ['Admin\\AuthController', 'login'],
    'GET /admin/logout' => ['Admin\\AuthController', 'logout'],

    // Admin Dashboard (Protected)
    'GET /admin' => ['Admin\\DashboardController', 'index'],
    'GET /admin/dashboard' => ['Admin\\DashboardController', 'index'],

    // Admin Report Management (Protected)
    'GET /admin/reports' => ['Admin\\ReportController', 'index'],
    'GET /admin/reports/{id}' => ['Admin\\ReportController', 'view'],
    'POST /admin/reports/{id}/assess' => ['Admin\\ReportController', 'assess'],
    'POST /admin/reports/{id}/status' => ['Admin\\ReportController', 'updateStatus'],

    // Admin User Management (Protected)
    'GET /admin/users' => ['Admin\\UserController', 'index'],
    'GET /admin/users/{id}' => ['Admin\\UserController', 'view'],
    'POST /admin/users/{id}/status' => ['Admin\\UserController', 'updateStatus'],

    // API Routes (for AJAX)
    'GET /api/vehicles' => ['Api\\VehicleController', 'list'],
    'GET /api/reports/stats' => ['Api\\ReportController', 'stats'],
];
