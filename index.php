<?php
/**
 * Auto Damage Assessment Platform
 * Main Entry Point
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH);
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Autoload helpers and config
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/Session.php';
require_once APP_PATH . '/helpers/Router.php';
require_once APP_PATH . '/helpers/CSRF.php';
require_once APP_PATH . '/helpers/Validator.php';
require_once APP_PATH . '/helpers/View.php';
require_once APP_PATH . '/helpers/Lang.php';
require_once APP_PATH . '/helpers/SMS.php';
require_once APP_PATH . '/helpers/FileUpload.php';
require_once APP_PATH . '/controllers/BaseController.php';

// Load configuration
$config = require APP_PATH . '/config/config.php';

// Set timezone
date_default_timezone_set($config['app']['timezone']);

// Initialize database
Database::init($config['database']);

// Start session
Session::start($config['session']);

// Initialize SMS
SMS::init($config['sms']);

// Initialize file upload
FileUpload::init($config['upload']);

// Initialize language (check session for user preference)
$locale = Session::get('locale', $config['app']['locale']);
Lang::init($locale);

// Generate CSRF token
CSRF::generateToken();

// Share common data with views
View::share('currentUser', Session::getUser());
View::share('isLoggedIn', Session::isLoggedIn());
View::share('locale', Lang::getLocale());

// Load routes
$routes = require APP_PATH . '/config/routes.php';

// Create router and dispatch
$router = new Router($routes);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

    // Show error page
    if ($config['app']['debug']) {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        require_once APP_PATH . '/views/errors/500.php';
    }
}
