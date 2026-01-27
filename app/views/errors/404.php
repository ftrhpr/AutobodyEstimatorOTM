<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#667eea">
    <title>404 - <?= __('errors.not_found') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Georgian:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Noto Sans Georgian', sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem;
        }
        .error-card {
            background: white;
            border-radius: 24px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
        }
        @media (min-width: 768px) {
            .error-card {
                padding: 4rem 3rem;
            }
            .error-code {
                font-size: 8rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">404</div>
        <h4 class="mt-3 fw-bold"><?= __('errors.not_found') ?></h4>
        <p class="text-muted mb-4">
            <?= Lang::getLocale() === 'ka' ? 'გვერდი ვერ მოიძებნა' : 'The page you are looking for does not exist' ?>
        </p>
        <a href="/" class="btn btn-primary btn-lg w-100"><?= __('home') ?></a>
    </div>
</body>
</html>
