<?php
/**
 * Auto Damage Assessment Platform - Installation Script
 *
 * Run this script once to set up the database and create necessary directories.
 * Delete this file after installation for security.
 */

// Check if already installed
if (file_exists(__DIR__ . '/.installed')) {
    die('Application is already installed. Delete .installed file to reinstall.');
}

$errors = [];
$success = [];

// Check PHP version
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    $errors[] = 'PHP 8.0 or higher is required. Current version: ' . PHP_VERSION;
}

// Check required extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'curl', 'gd', 'mbstring'];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "PHP extension '{$ext}' is required but not loaded.";
    }
}

// Check write permissions
$writableDirs = [
    __DIR__ . '/public_html/uploads',
    __DIR__ . '/storage/logs',
    __DIR__ . '/storage/cache',
    __DIR__ . '/storage/sessions',
];

foreach ($writableDirs as $dir) {
    if (!is_dir($dir)) {
        if (!@mkdir($dir, 0755, true)) {
            $errors[] = "Cannot create directory: {$dir}";
        } else {
            $success[] = "Created directory: {$dir}";
        }
    }

    if (is_dir($dir) && !is_writable($dir)) {
        $errors[] = "Directory is not writable: {$dir}";
    }
}

// Database setup
if (empty($errors) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = $_POST['db_host'] ?? 'localhost';
    $dbName = $_POST['db_name'] ?? 'otoexpre_est';
    $dbUser = $_POST['db_user'] ?? 'otoexpre_est';
    $dbPass = $_POST['db_pass'] ?? '';
    $adminUser = $_POST['admin_user'] ?? 'admin';
    $adminPass = $_POST['admin_pass'] ?? '';
    $adminName = $_POST['admin_name'] ?? 'Administrator';

    try {
        // Connect to MySQL server
        $pdo = new PDO(
            "mysql:host={$dbHost}",
            $dbUser,
            $dbPass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$dbName}`");

        // Run schema
        $schema = file_get_contents(__DIR__ . '/database/schema.sql');

        // Remove CREATE DATABASE and USE statements from schema
        $schema = preg_replace('/CREATE DATABASE.*?;/is', '', $schema);
        $schema = preg_replace('/USE.*?;/is', '', $schema);

        // Split and execute statements
        $statements = array_filter(array_map('trim', explode(';', $schema)));
        foreach ($statements as $statement) {
            if (!empty($statement) && !str_starts_with($statement, '--')) {
                $pdo->exec($statement);
            }
        }

        // Update admin credentials
        if (!empty($adminUser) && !empty($adminPass)) {
            $hash = password_hash($adminPass, PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE admins SET username = ?, password_hash = ?, name = ? WHERE id = 1")
                ->execute([$adminUser, $hash, $adminName]);
        }

        // Update config file
        $configFile = __DIR__ . '/app/config/config.php';
        $config = file_get_contents($configFile);

        $config = preg_replace("/'host' => '.*?'/", "'host' => '{$dbHost}'", $config);
        $config = preg_replace("/'database' => '.*?'/", "'database' => '{$dbName}'", $config);
        $config = preg_replace("/'username' => '.*?'/", "'username' => '{$dbUser}'", $config);
        $config = preg_replace("/'password' => '.*?'/", "'password' => '{$dbPass}'", $config);

        file_put_contents($configFile, $config);

        // Create .installed file
        file_put_contents(__DIR__ . '/.installed', date('Y-m-d H:i:s'));

        $success[] = 'Database created and configured successfully!';
        $success[] = 'Admin user created with provided credentials.';
        $success[] = '<strong>Installation complete!</strong> Please delete this file (install.php) for security.';

    } catch (PDOException $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
    } catch (Exception $e) {
        $errors[] = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - Auto Damage Assessment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .install-card { max-width: 600px; margin: 50px auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-card">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">Auto Damage Assessment Platform</h3>
                    <p class="mb-0 opacity-75">Installation Wizard</p>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h5>Errors:</h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <ul class="mb-0">
                                <?php foreach ($success as $msg): ?>
                                    <li><?= $msg ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!file_exists(__DIR__ . '/.installed')): ?>
                        <form method="POST">
                            <h5 class="mb-3">Database Configuration</h5>

                            <div class="mb-3">
                                <label class="form-label">Database Host</label>
                                <input type="text" class="form-control" name="db_host" value="localhost" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Database Name</label>
                                <input type="text" class="form-control" name="db_name" value="otoexpre_est" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Database Username</label>
                                <input type="text" class="form-control" name="db_user" value="otoexpre_est" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Database Password</label>
                                <input type="password" class="form-control" name="db_pass">
                            </div>

                            <hr>
                            <h5 class="mb-3">Admin Account</h5>

                            <div class="mb-3">
                                <label class="form-label">Admin Username</label>
                                <input type="text" class="form-control" name="admin_user" value="admin" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Admin Password</label>
                                <input type="password" class="form-control" name="admin_pass" required minlength="6">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Admin Name</label>
                                <input type="text" class="form-control" name="admin_name" value="Administrator" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Install Application
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="mb-4">Installation is complete. You can now:</p>
                            <a href="/admin/login" class="btn btn-primary btn-lg">Go to Admin Panel</a>
                            <a href="/" class="btn btn-outline-primary btn-lg ms-2">View Website</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
