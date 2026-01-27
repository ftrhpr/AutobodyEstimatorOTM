<!DOCTYPE html>
<html lang="<?= Lang::getLocale() ?? 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f5576c">
    <title>500 - <?= Lang::getLocale() === 'ka' ? 'შეცდომა' : 'Server Error' ?></title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .btn-primary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        <div class="error-code">500</div>
        <h4 class="mt-3 fw-bold"><?= Lang::getLocale() === 'ka' ? 'სერვერის შეცდომა' : 'Server Error' ?></h4>
        <p class="text-muted mb-4">
            <?= Lang::getLocale() === 'ka' ? 'რაღაც შეცდა. სცადეთ მოგვიანებით.' : 'Something went wrong. Please try again later.' ?>
        </p>
        <a href="/" class="btn btn-primary btn-lg w-100"><?= Lang::getLocale() === 'ka' ? 'მთავარი' : 'Go Home' ?></a>
    </div>
</body>
</html>
