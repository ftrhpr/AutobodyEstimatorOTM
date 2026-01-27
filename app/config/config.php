<?php
/**
 * Application Configuration
 * Auto Damage Assessment Platform
 */

return [
    // Application Settings
    'app' => [
        'name' => 'Auto Damage Assessment',
        'url' => 'http://localhost',
        'timezone' => 'Asia/Tbilisi',
        'locale' => 'ka', // Georgian
        'debug' => true,
    ],

    // Database Configuration
    'database' => [
        'host' => 'localhost',
        'database' => 'otoexpre_est',
        'username' => 'otoexpre_est',
        'password' => '', // REQUIRED: Add your database password from cPanel here
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'debug' => true, // Enable to see detailed error messages
    ],

    // Session Configuration
    'session' => [
        'lifetime' => 7200, // 2 hours in seconds
        'name' => 'auto_damage_session',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true,
    ],

    // SMS Configuration
    'sms' => [
        'provider' => 'gosms', // twilio, messagebird, magti, gosms
        'gosms' => [
            'api_key' => '5c88b0316e44d076d4677a4860959ef71ce049ce704b559355568a362f40ade1',
            'brand_name' => 'OTOMOTORS',
        ],
        'twilio' => [
            'sid' => 'YOUR_TWILIO_SID',
            'token' => 'YOUR_TWILIO_TOKEN',
            'from' => '+1234567890',
        ],
        'messagebird' => [
            'api_key' => 'YOUR_MESSAGEBIRD_KEY',
            'originator' => 'AutoDamage',
        ],
        'magti' => [
            'username' => 'YOUR_MAGTI_USERNAME',
            'password' => 'YOUR_MAGTI_PASSWORD',
            'client_id' => 'YOUR_CLIENT_ID',
        ],
    ],

    // File Upload Configuration
    'upload' => [
        'max_files' => 10,
        'max_size' => 5 * 1024 * 1024, // 5MB in bytes
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    ],

    // Security Configuration
    'security' => [
        'otp_expiry' => 300, // 5 minutes
        'otp_length' => 6,
        'max_otp_attempts' => 3,
        'rate_limit_sms' => 5, // max SMS per hour
        'password_min_length' => 8,
    ],
];
