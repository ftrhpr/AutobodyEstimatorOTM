<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?= e($title ?? __('app_name')) ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Georgian:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS with cache busting -->
    <link href="<?= asset('css/style.css') ?>?v=2.0.<?= time() ?>" rel="stylesheet">
</head>
<body class="auth-page">
    <!-- Language Switcher -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 100;">
        <div class="dropdown">
            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                <?= Lang::getLocale() === 'ka' ? '🇬🇪 ქარ' : '🇬🇧 EN' ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item <?= Lang::getLocale() === 'ka' ? 'active' : '' ?>" href="/language/ka">🇬🇪 ქართული</a></li>
                <li><a class="dropdown-item <?= Lang::getLocale() === 'en' ? 'active' : '' ?>" href="/language/en">🇬🇧 English</a></li>
            </ul>
        </div>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="bi bi-car-front-fill"></i>
                <h3><?= __('app_name') ?></h3>
                <p><?= Lang::getLocale() === 'ka' ? 'პროფესიონალური ავტო შეფასება' : 'Professional Auto Assessment' ?></p>
            </div>

            <div class="auth-body">
                <!-- Flash Messages -->
                <?php if (Session::hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <div><?= e(Session::getFlash('success')) ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (Session::hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div><?= e(Session::getFlash('error')) ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="/" class="text-white text-decoration-none opacity-75 d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> <?= __('home') ?>
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
