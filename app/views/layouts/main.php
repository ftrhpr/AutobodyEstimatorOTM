<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?= e($title ?? __('app_name')) ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts for Georgian -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Georgian:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">

    <style>
        body { font-family: 'Noto Sans Georgian', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    </style>

    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-car-front me-2"></i><?= __('app_name') ?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <?php if (Session::isLoggedIn()): ?>
                    <!-- Desktop Navigation -->
                    <ul class="navbar-nav me-auto d-none d-lg-flex">
                        <li class="nav-item">
                            <a class="nav-link <?= isActiveRoute('/dashboard') ? 'active' : '' ?>" href="/dashboard">
                                <i class="bi bi-grid-1x2 me-1"></i><?= __('dashboard') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActiveRoute('/vehicles') ? 'active' : '' ?>" href="/vehicles">
                                <i class="bi bi-car-front me-1"></i><?= __('vehicle.vehicles') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActiveRoute('/reports') ? 'active' : '' ?>" href="/reports">
                                <i class="bi bi-file-text me-1"></i><?= __('report.reports') ?>
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="d-none d-md-inline"><?= e(Session::getUser()['name'] ?? '') ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="/profile">
                                        <i class="bi bi-person me-2"></i><?= __('profile.my_profile') ?>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/logout">
                                        <i class="bi bi-box-arrow-right me-2"></i><?= __('auth.logout') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Language Switcher -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-globe2"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item <?= Lang::getLocale() === 'ka' ? 'active' : '' ?>" href="/language/ka">🇬🇪 ქართული</a></li>
                                <li><a class="dropdown-item <?= Lang::getLocale() === 'en' ? 'active' : '' ?>" href="/language/en">🇬🇧 English</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="bi bi-box-arrow-in-right me-1"></i><?= __('auth.login') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">
                                <i class="bi bi-person-plus me-1"></i><?= __('auth.register') ?>
                            </a>
                        </li>
                        <!-- Language Switcher -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-globe2"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/language/ka">🇬🇪 ქართული</a></li>
                                <li><a class="dropdown-item" href="/language/en">🇬🇧 English</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show m-3 mb-0 slide-up" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= e(Session::getFlash('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show m-3 mb-0 slide-up" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= e(Session::getFlash('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-3 py-lg-4">
        <?= $content ?>
    </main>

    <!-- Footer (Desktop only) -->
    <footer class="mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-1"><?= __('app_name') ?></h5>
                    <p class="mb-0 small">პროფესიონალური ავტო შეფასება</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small">&copy; <?= date('Y') ?> OTOMOTORS</p>
                </div>
            </div>
        </div>
    </footer>

    <?php if (Session::isLoggedIn()): ?>
    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-nav">
        <a href="/dashboard" class="bottom-nav-item <?= isActiveRoute('/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2<?= isActiveRoute('/dashboard') ? '-fill' : '' ?>"></i>
            <span><?= __('dashboard') ?></span>
        </a>
        <a href="/vehicles" class="bottom-nav-item <?= isActiveRoute('/vehicles') ? 'active' : '' ?>">
            <i class="bi bi-car-front<?= isActiveRoute('/vehicles') ? '-fill' : '' ?>"></i>
            <span><?= __('vehicle.vehicles') ?></span>
        </a>
        <a href="/reports/new" class="bottom-nav-item <?= isActiveRoute('/reports/new') ? 'active' : '' ?>">
            <i class="bi bi-plus-circle<?= isActiveRoute('/reports/new') ? '-fill' : '' ?>"></i>
            <span><?= __('report.new_report') ?></span>
        </a>
        <a href="/reports" class="bottom-nav-item <?= isActiveRoute('/reports') ? 'active' : '' ?>">
            <i class="bi bi-file-text<?= isActiveRoute('/reports') ? '-fill' : '' ?>"></i>
            <span><?= __('report.reports') ?></span>
        </a>
        <a href="/profile" class="bottom-nav-item <?= isActiveRoute('/profile') ? 'active' : '' ?>">
            <i class="bi bi-person<?= isActiveRoute('/profile') ? '-fill' : '' ?>"></i>
            <span><?= __('profile.profile') ?></span>
        </a>
    </nav>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= asset('js/app.js') ?>"></script>

    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>
