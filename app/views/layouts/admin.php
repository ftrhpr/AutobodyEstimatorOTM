<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="theme-color" content="#0f172a">
    <title><?= e($title ?? 'Admin Panel') ?> - <?= __('app_name') ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+Georgian:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS v3.0 -->
    <link href="<?= asset('css/style.css') ?>?v=3.0.<?= time() ?>" rel="stylesheet">
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <nav class="sidebar" id="adminSidebar">
        <div class="sidebar-sticky">
            <a href="/admin" class="sidebar-brand">
                <i class="bi bi-car-front-fill"></i>
                <span class="fw-bold">Admin Panel</span>
            </a>

            <div class="sidebar-section"><?= Lang::getLocale() === 'ka' ? 'მთავარი' : 'Main' ?></div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a class="nav-link <?= isActiveRoute('/admin') || isActiveRoute('/admin/dashboard') ? 'active' : '' ?>"
                       href="/admin">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span><?= __('admin.dashboard') ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'active' : '' ?>"
                       href="/admin/reports">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span><?= __('admin.all_reports') ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'active' : '' ?>"
                       href="/admin/users">
                        <i class="bi bi-people-fill"></i>
                        <span><?= __('admin.users') ?></span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-section"><?= Lang::getLocale() === 'ka' ? 'სისტემა' : 'System' ?></div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a class="nav-link" href="/" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span><?= Lang::getLocale() === 'ka' ? 'საიტის ნახვა' : 'View Site' ?></span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="admin-avatar me-3">
                            <?= strtoupper(substr(Session::getAdmin()['name'] ?? 'A', 0, 1)) ?>
                        </div>
                        <div>
                            <div class="text-white fw-semibold" style="font-size: 0.875rem;"><?= e(Session::getAdmin()['name'] ?? 'Admin') ?></div>
                            <div class="text-white-50" style="font-size: 0.75rem;"><?= Lang::getLocale() === 'ka' ? 'ადმინი' : 'Admin' ?></div>
                        </div>
                    </div>
                    <a href="/admin/logout" class="btn btn-sm btn-link text-danger p-0" title="<?= __('auth.logout') ?>">
                        <i class="bi bi-box-arrow-right" style="font-size: 1.125rem;"></i>
                    </a>
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
                        <button class="btn btn-ghost btn-icon btn-sm me-3 d-lg-none" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                            <i class="bi bi-list fs-5"></i>
                        </button>
                        <div>
                            <h5 class="mb-0 fw-bold"><?= e($title ?? 'Dashboard') ?></h5>
                            <nav aria-label="breadcrumb" class="d-none d-md-block">
                                <ol class="breadcrumb mb-0" style="font-size: 0.8125rem;">
                                    <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
                                    <li class="breadcrumb-item active"><?= e($title ?? 'Dashboard') ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <span style="font-size: 1.125rem;"><?= Lang::getLocale() === 'ka' ? '🇬🇪' : '🇬🇧' ?></span>
                                <span class="d-none d-sm-inline"><?= Lang::getLocale() === 'ka' ? 'ქართული' : 'English' ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item <?= Lang::getLocale() === 'ka' ? 'active' : '' ?>" href="/language/ka"><span class="me-2">🇬🇪</span> ქართული</a></li>
                                <li><a class="dropdown-item <?= Lang::getLocale() === 'en' ? 'active' : '' ?>" href="/language/en"><span class="me-2">🇬🇧</span> English</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid px-4 py-4">
            <!-- Flash Messages -->
            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show slide-up" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <div><?= e(Session::getFlash('success')) ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show slide-up" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div><?= e(Session::getFlash('error')) ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay d-lg-none" onclick="document.getElementById('adminSidebar').classList.remove('show')"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>?v=3.0"></script>

    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>
