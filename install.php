<?php
/**
 * Juney Villa Limited - Installation Wizard
 * Run this file once to set up the database and configuration.
 * DELETE THIS FILE AFTER INSTALLATION.
 */

session_start();
$step = $_GET['step'] ?? '1';
$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'check_requirements') {
        $step = '2';
    }

    if ($action === 'setup_database') {
        $host = $_POST['db_host'] ?? 'localhost';
        $port = $_POST['db_port'] ?? '3306';
        $name = $_POST['db_name'] ?? 'juney_villa';
        $user = $_POST['db_user'] ?? 'root';
        $pass = $_POST['db_pass'] ?? '';

        try {
            $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            // Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$name}`");

            // Run schema
            $schema = file_get_contents(__DIR__ . '/database/schema.sql');
            // Split by semicolons but be careful with content inside quotes
            $statements = array_filter(array_map('trim', explode(';', $schema)));
            foreach ($statements as $stmt) {
                if (!empty($stmt) && stripos($stmt, 'CREATE DATABASE') === false && stripos($stmt, 'USE ') === false) {
                    $pdo->exec($stmt);
                }
            }

            // Run seed
            $seed = file_get_contents(__DIR__ . '/database/seeds/seed.sql');
            $statements = array_filter(array_map('trim', explode(';', $seed)));
            foreach ($statements as $stmt) {
                if (!empty($stmt) && stripos($stmt, 'USE ') === false) {
                    $pdo->exec($stmt);
                }
            }

            // Create .env file
            $envContent = file_get_contents(__DIR__ . '/.env.example');
            $envContent = str_replace('DB_HOST=localhost', "DB_HOST={$host}", $envContent);
            $envContent = str_replace('DB_PORT=3306', "DB_PORT={$port}", $envContent);
            $envContent = str_replace('DB_DATABASE=juney_villa', "DB_DATABASE={$name}", $envContent);
            $envContent = str_replace('DB_USERNAME=root', "DB_USERNAME={$user}", $envContent);
            $envContent = str_replace('DB_PASSWORD=', "DB_PASSWORD={$pass}", $envContent);
            $envContent = str_replace('APP_KEY=your-secret-key-here', 'APP_KEY=' . bin2hex(random_bytes(32)), $envContent);

            $appUrl = $_POST['app_url'] ?? 'http://localhost/juney-villa';
            $envContent = str_replace('APP_URL=http://localhost/juney-villa', "APP_URL={$appUrl}", $envContent);

            file_put_contents(__DIR__ . '/.env', $envContent);

            $success = 'Installation completed successfully!';
            $step = '4';
        } catch (PDOException $e) {
            $errors[] = 'Database Error: ' . $e->getMessage();
            $step = '3';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Juney Villa Limited</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f6fa; min-height: 100vh; display: flex; align-items: center; }
        .install-card { max-width: 700px; margin: 0 auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 30px rgba(0,0,0,0.1); overflow: hidden; }
        .install-header { background: #1a1a2e; color: #fff; padding: 30px; text-align: center; }
        .install-header h1 { font-family: 'Playfair Display', serif; font-size: 2rem; }
        .text-gold { color: #C8A45C; }
        .btn-gold { background: #C8A45C; color: #fff; border: none; }
        .btn-gold:hover { background: #9E7B3C; color: #fff; }
        .step-indicator { display: flex; justify-content: center; gap: 10px; padding: 20px; background: #f8f9fa; }
        .step-dot { width: 12px; height: 12px; border-radius: 50%; background: #ddd; }
        .step-dot.active { background: #C8A45C; }
        .step-dot.done { background: #28a745; }
        .req-item { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .req-item i { font-size: 1.2rem; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="install-card">
        <div class="install-header">
            <h1>JUNEY<span class="text-gold">VILLA</span></h1>
            <p class="mb-0 opacity-75">Installation Wizard</p>
        </div>

        <div class="step-indicator">
            <div class="step-dot <?= $step >= 1 ? ($step > 1 ? 'done' : 'active') : '' ?>"></div>
            <div class="step-dot <?= $step >= 2 ? ($step > 2 ? 'done' : 'active') : '' ?>"></div>
            <div class="step-dot <?= $step >= 3 ? ($step > 3 ? 'done' : 'active') : '' ?>"></div>
            <div class="step-dot <?= $step >= 4 ? 'active' : '' ?>"></div>
        </div>

        <div class="p-4">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"><?php foreach ($errors as $e) echo '<p class="mb-0">' . htmlspecialchars($e) . '</p>'; ?></div>
            <?php endif; ?>

            <?php if ($step === '1'): ?>
            <!-- Step 1: Welcome -->
            <h4 class="fw-bold mb-3">Welcome</h4>
            <p class="text-muted">Welcome to the Juney Villa Limited installation wizard. This will guide you through setting up the application.</p>
            <h6 class="fw-semibold mt-4">Requirements:</h6>
            <ul class="text-muted">
                <li>PHP 8.3 or higher</li>
                <li>MySQL 8.0 or higher</li>
                <li>Apache with mod_rewrite enabled</li>
                <li>PDO MySQL extension</li>
                <li>Composer dependencies installed</li>
            </ul>
            <form method="POST">
                <input type="hidden" name="action" value="check_requirements">
                <button type="submit" class="btn btn-gold px-4 py-2 mt-3">Next: Check Requirements <i class="bi bi-arrow-right"></i></button>
            </form>

            <?php elseif ($step === '2'): ?>
            <!-- Step 2: Requirements Check -->
            <h4 class="fw-bold mb-3">System Requirements</h4>
            <?php
            $checks = [
                ['PHP Version >= 8.3', version_compare(PHP_VERSION, '8.3.0', '>=')],
                ['PDO Extension', extension_loaded('pdo')],
                ['PDO MySQL', extension_loaded('pdo_mysql')],
                ['mbstring Extension', extension_loaded('mbstring')],
                ['JSON Extension', extension_loaded('json')],
                ['cURL Extension', extension_loaded('curl')],
                ['GD Extension', extension_loaded('gd')],
                ['OpenSSL Extension', extension_loaded('openssl')],
                ['storage/ Writable', is_writable(__DIR__ . '/storage')],
                ['public/uploads/ Writable', is_writable(__DIR__ . '/public/uploads') || @mkdir(__DIR__ . '/public/uploads', 0755, true)],
            ];
            $allPassed = true;
            foreach ($checks as [$label, $passed]):
                if (!$passed) $allPassed = false;
            ?>
            <div class="req-item">
                <i class="bi <?= $passed ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?>"></i>
                <span><?= $label ?></span>
                <span class="ms-auto badge <?= $passed ? 'bg-success' : 'bg-danger' ?>"><?= $passed ? 'Pass' : 'Fail' ?></span>
            </div>
            <?php endforeach; ?>
            <form method="GET" class="mt-4">
                <input type="hidden" name="step" value="3">
                <button type="submit" class="btn btn-gold px-4 py-2" <?= !$allPassed ? 'disabled' : '' ?>>
                    Next: Database Setup <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <?php elseif ($step === '3'): ?>
            <!-- Step 3: Database -->
            <h4 class="fw-bold mb-3">Database Configuration</h4>
            <form method="POST">
                <input type="hidden" name="action" value="setup_database">
                <div class="row g-3">
                    <div class="col-md-8"><label class="form-label">Database Host</label><input type="text" name="db_host" class="form-control" value="localhost" required></div>
                    <div class="col-md-4"><label class="form-label">Port</label><input type="text" name="db_port" class="form-control" value="3306" required></div>
                    <div class="col-12"><label class="form-label">Database Name</label><input type="text" name="db_name" class="form-control" value="juney_villa" required></div>
                    <div class="col-md-6"><label class="form-label">Username</label><input type="text" name="db_user" class="form-control" value="root" required></div>
                    <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="db_pass" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Application URL</label><input type="url" name="app_url" class="form-control" value="http://localhost/juney-villa" required></div>
                </div>
                <button type="submit" class="btn btn-gold px-4 py-2 mt-4">Install <i class="bi bi-check-lg"></i></button>
            </form>

            <?php elseif ($step === '4'): ?>
            <!-- Step 4: Complete -->
            <div class="text-center py-4">
                <div class="mb-3"><i class="bi bi-check-circle-fill text-success display-3"></i></div>
                <h4 class="fw-bold">Installation Complete!</h4>
                <p class="text-muted">Juney Villa Limited has been installed successfully.</p>
                <div class="bg-light rounded p-3 mt-3 text-start">
                    <h6 class="fw-semibold">Admin Login:</h6>
                    <p class="mb-1 small"><strong>Email:</strong> admin@juneyvillaszanzibar.co.tz</p>
                    <p class="mb-0 small"><strong>Password:</strong> Tanzania12$</p>
                    <p class="mt-2 text-danger small"><strong>Important:</strong> You will be required to change the password on first login.</p>
                </div>
                <div class="alert alert-warning mt-3 small"><i class="bi bi-exclamation-triangle me-2"></i>Please delete <code>install.php</code> for security.</div>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="/" class="btn btn-outline-dark px-4">Visit Website</a>
                    <a href="/admin/dashboard" class="btn btn-gold px-4">Admin Dashboard</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
