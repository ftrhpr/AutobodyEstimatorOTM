<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="theme-color" content="#5b6cf2">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?= e($title ?? __('app_name')) ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts - Extended -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+Georgian:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Custom CSS v3.0 -->
    <link href="<?= asset('css/style.css') ?>?v=3.0.<?= time() ?>" rel="stylesheet">
</head>
<body class="auth-page">
    <!-- Language Switcher -->
    <div class="position-fixed top-0 end-0 p-4" style="z-index: 100;">
        <div class="dropdown">
            <button class="btn btn-sm d-flex align-items-center gap-2 px-3 py-2" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: var(--radius-full);" data-bs-toggle="dropdown">
                <span style="font-size: 1.125rem;"><?= Lang::getLocale() === 'ka' ? '🇬🇪' : '🇬🇧' ?></span>
                <span class="fw-medium"><?= Lang::getLocale() === 'ka' ? 'ქარ' : 'EN' ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item <?= Lang::getLocale() === 'ka' ? 'active' : '' ?>" href="/language/ka"><span class="me-2">🇬🇪</span> ქართული</a></li>
                <li><a class="dropdown-item <?= Lang::getLocale() === 'en' ? 'active' : '' ?>" href="/language/en"><span class="me-2">🇬🇧</span> English</a></li>
            </ul>
        </div>
    </div>

    <!-- Back to Home -->
    <div class="position-fixed top-0 start-0 p-4" style="z-index: 100;">
        <a href="/" class="btn btn-sm d-flex align-items-center gap-2 px-3 py-2" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: var(--radius-full);">
            <i class="bi bi-arrow-left"></i>
            <span class="fw-medium"><?= __('home') ?></span>
        </a>
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
                    <div class="alert alert-success alert-dismissible fade show mb-4 slide-up" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <div><?= e(Session::getFlash('success')) ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (Session::hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4 slide-up" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div><?= e(Session::getFlash('error')) ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>

        <!-- Copyright -->
        <p class="text-center mt-4 mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.8125rem;">
            &copy; <?= date('Y') ?> OTOMOTORS. <?= Lang::getLocale() === 'ka' ? 'ყველა უფლება დაცულია.' : 'All rights reserved.' ?>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
