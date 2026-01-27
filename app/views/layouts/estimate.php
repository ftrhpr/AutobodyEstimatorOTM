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
<body class="estimate-page">
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

    <div class="estimate-container">
        <!-- Header with Logo -->
        <div class="estimate-header">
            <a href="/" class="text-decoration-none d-inline-flex align-items-center gap-2 mb-4">
                <div class="logo-icon">
                    <i class="bi bi-car-front-fill"></i>
                </div>
                <span class="logo-text"><?= __('app_name') ?></span>
            </a>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step <?= ($step ?? 1) >= 1 ? 'active' : '' ?> <?= ($step ?? 1) > 1 ? 'completed' : '' ?>">
                    <div class="step-number">
                        <?php if (($step ?? 1) > 1): ?>
                            <i class="bi bi-check-lg"></i>
                        <?php else: ?>
                            1
                        <?php endif; ?>
                    </div>
                    <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'ფოტოები' : 'Photos' ?></span>
                </div>
                <div class="step-line"></div>
                <div class="step <?= ($step ?? 1) >= 2 ? 'active' : '' ?>">
                    <div class="step-number">2</div>
                    <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'რეგისტრაცია' : 'Register' ?></span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="estimate-content">
            <!-- Flash Messages -->
            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= e(Session::getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= e(Session::getFlash('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-4">
            <a href="/" class="btn btn-link text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i><?= __('home') ?>
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>?v=2.0.<?= time() ?>"></script>
</body>
</html>
