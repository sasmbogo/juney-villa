<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Login') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
    <style>
        .auth-wrapper { min-height: 100vh; display: flex; }
        .auth-left { flex: 1; background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200') center/cover; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .auth-right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; background: #f8f9fa; }
        .auth-card { width: 100%; max-width: 440px; }
        @media (max-width: 768px) { .auth-left { display: none; } .auth-right { flex: 1; } }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left d-none d-md-flex">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bold font-playfair">JUNEY<span class="text-gold">VILLA</span></h1>
                <p class="lead">Luxury Living in Paradise</p>
                <p class="text-white-50">Experience world-class hospitality in Zanzibar, Tanzania</p>
            </div>
        </div>
        <div class="auth-right">
            <div class="auth-card">
                <?php if ($flash = flash('success')): ?>
                    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= e($flash) ?></div>
                <?php endif; ?>
                <?php if ($flash = flash('error')): ?>
                    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= e($flash) ?></div>
                <?php endif; ?>
                <?= $content ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
