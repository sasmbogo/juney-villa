<?php

declare(strict_types=1);

/**
 * Juney Villa Limited - Luxury Villa Rental & Booking Management System
 * Entry Point
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', BASE_PATH . '/storage');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Autoload (Composer or fallback)
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
} else {
    require_once BASE_PATH . '/autoload.php';
}

// Load environment variables
if (class_exists('Dotenv\Dotenv') && file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
} elseif (file_exists(BASE_PATH . '/.env')) {
    // Manual .env parsing fallback
    $lines = file(BASE_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, ' "\'');
            putenv(trim($key) . '=' . trim($value, ' "\''));
        }
    }
}

// Error handling
if (filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Set timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Africa/Dar_es_Salaam');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

// Bootstrap application
$app = new App\Core\Application();
$app->run();
