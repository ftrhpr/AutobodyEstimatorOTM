<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= e($title ?? 'Admin Panel') ?> - <?= __('app_name') ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Georgian:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">

    <style>
        :root {
            --admin-sidebar-bg: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            --admin-sidebar-width: 260px;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            width: var(--admin-sidebar-width);
            background: var(--admin-sidebar-bg);
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        .sidebar-sticky {
            height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.875rem 1.5rem;
            border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
            margin-right: 1rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: var(--primary);
            box-shadow: var(--shadow-primary);
        }
        .sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }
        .main-content {
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
            background: var(--gray-50);
        }
        .admin-header {
            background: white;
            padding: 1.25rem 0;
            border-bottom: 1px solid var(--gray-100);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .sidebar-brand {
            padding: 1.5rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        .sidebar-brand i {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
        .sidebar-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }
        .sidebar-footer {
            margin-top: auto;
            padding: 1.25rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .admin-header .mobile-toggle {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <nav class="sidebar" id="adminSidebar">
        <div class="sidebar-sticky">
            <a href="/admin" class="sidebar-brand">
                <i class="bi bi-car-front-fill"></i>Admin Panel
            </a>

            <div class="sidebar-section"><?= Lang::getLocale() === 'ka' ? 'მთავარი' : 'Main' ?></div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a class="nav-link <?= isActiveRoute('/admin') || isActiveRoute('/admin/dashboard') ? 'active' : '' ?>"
                       href="/admin">
                        <i class="bi bi-grid-1x2-fill"></i><?= __('admin.dashboard') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'active' : '' ?>"
                       href="/admin/reports">
                        <i class="bi bi-file-earmark-text-fill"></i><?= __('admin.all_reports') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'active' : '' ?>"
                       href="/admin/users">
                        <i class="bi bi-people-fill"></i><?= __('admin.users') ?>
                    </a>
                </li>
            </ul>

            <div class="sidebar-section"><?= Lang::getLocale() === 'ka' ? 'სხვა' : 'Other' ?></div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="/" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i><?= Lang::getLocale() === 'ka' ? 'საიტის ნახვა' : 'View Site' ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/admin/logout">
                        <i class="bi bi-box-arrow-right"></i><?= __('auth.logout') ?>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="d-flex align-items-center">
                    <div class="admin-avatar me-3">
                        <?= strtoupper(substr(Session::getAdmin()['name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="text-white fw-semibold small"><?= e(Session::getAdmin()['name'] ?? 'Admin') ?></div>
                        <div class="text-white-50 small"><?= Lang::getLocale() === 'ka' ? 'ადმინისტრატორი' : 'Administrator' ?></div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="admin-header">
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-light me-3 d-lg-none mobile-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                            <i class="bi bi-list"></i>
                        </button>
                        <h5 class="mb-0 fw-bold"><?= e($title ?? 'Dashboard') ?></h5>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-globe me-1"></i>
                            <?= Lang::getLocale() === 'ka' ? '🇬🇪' : '🇬🇧' ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/language/ka">🇬🇪 ქართული</a></li>
                            <li><a class="dropdown-item" href="/language/en">🇬🇧 English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="container-fluid px-4 py-4">
            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= e(Session::getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= e(Session::getFlash('error')) ?>
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
