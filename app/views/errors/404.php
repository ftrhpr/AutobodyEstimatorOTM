<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - <?= __('errors.not_found') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .error-card {
            background: white;
            border-radius: 20px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #667eea;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">404</div>
        <h2 class="mt-4"><?= __('errors.not_found') ?></h2>
        <p class="text-muted mb-4">The page you are looking for does not exist.</p>
        <a href="/" class="btn btn-primary btn-lg"><?= __('home') ?></a>
    </div>
</body>
</html>
