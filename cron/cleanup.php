<?php
/**
 * Cron Job: Clean up expired sessions, cache, and old logs
 * Schedule: 0 0 * * * (Daily at midnight)
 */

// Clean expired rate limit files
$cacheDir = __DIR__ . '/../storage/cache/';
$files = glob($cacheDir . 'rate_limit_*');
$now = time();

foreach ($files as $file) {
    if (filemtime($file) < ($now - 3600)) {
        unlink($file);
    }
}

// Clean old log files (older than 30 days)
$logDir = __DIR__ . '/../storage/logs/';
$files = glob($logDir . '*.log');
foreach ($files as $file) {
    if (filemtime($file) < ($now - 2592000)) {
        unlink($file);
    }
}

// Auto-cancel expired pending bookings (older than 48 hours)
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $db = App\Core\Database::getInstance()->getConnection();
    $db->exec("UPDATE bookings SET status = 'cancelled', cancellation_reason = 'Auto-cancelled: Payment not received within 48 hours', cancelled_at = NOW() WHERE status = 'pending' AND created_at < DATE_SUB(NOW(), INTERVAL 48 HOUR)");

    echo "Cleanup completed at " . date('Y-m-d H:i:s') . "\n";
} catch (\Exception $e) {
    error_log("Cleanup Cron Error: " . $e->getMessage());
}
