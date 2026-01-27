<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= e($title ?? 'Admin Panel') ?> - <?= __('app_name') ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">

    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            width: 250px;
            background: linear-gradient(135deg, #1a1c2e 0%, #2d3154 100%);
        }
        .sidebar-sticky {
            height: calc(100vh - 48px);
            overflow-x: hidden;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.15);
            border-left-color: #667eea;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 20px;
        }
        .admin-header {
            background: white;
            padding: 1rem 0;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
        }
        .sidebar-brand {
            padding: 1rem 1.5rem;
            color: white;
            text-decoration: none;
            display: block;
            font-size: 1.25rem;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-top: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="position-sticky sidebar-sticky">
            <a href="/admin" class="sidebar-brand">
                <i class="bi bi-car-front me-2"></i>Admin Panel
            </a>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= isActiveRoute('/admin') || isActiveRoute('/admin/dashboard') ? 'active' : '' ?>"
                       href="/admin">
                        <i class="bi bi-speedometer2"></i><?= __('admin.dashboard') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'active' : '' ?>"
                       href="/admin/reports">
                        <i class="bi bi-file-earmark-text"></i><?= __('admin.all_reports') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'active' : '' ?>"
                       href="/admin/users">
                        <i class="bi bi-people"></i><?= __('admin.users') ?>
                    </a>
                </li>
            </ul>

            <hr class="my-3 border-secondary">

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="/" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i>View Site
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/admin/logout">
                        <i class="bi bi-box-arrow-right"></i><?= __('auth.logout') ?>
                    </a>
                </li>
            </ul>

            <div class="px-3 mt-auto py-3 text-muted small">
                Logged in as:<br>
                <strong class="text-white"><?= e(Session::getAdmin()['name'] ?? 'Admin') ?></strong>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="admin-header">
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= e($title ?? 'Dashboard') ?></h4>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-globe me-1"></i>
                            <?= Lang::getLocale() === 'ka' ? 'ქარ' : 'EN' ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/language/ka">ქართული</a></li>
                            <li><a class="dropdown-item" href="/language/en">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="container-fluid px-4">
            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?= e(Session::getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i><?= e(Session::getFlash('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>

    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>
