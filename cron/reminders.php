<?php
/**
 * Cron Job: Send booking reminders
 * Schedule: 0 8 * * * (Daily at 8:00 AM EAT)
 */

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Check-in reminders (1 day before)
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $stmt = $db->prepare("SELECT b.*, v.name as villa_name FROM bookings b JOIN villas v ON b.villa_id = v.id WHERE b.check_in = ? AND b.status = 'confirmed'");
    $stmt->execute([$tomorrow]);
    $checkins = $stmt->fetchAll();

    foreach ($checkins as $booking) {
        // Create notification
        $db->prepare("INSERT INTO notifications (user_id, type, title, message, created_at) VALUES (?, 'reminder', ?, ?, NOW())")
            ->execute([$booking['user_id'], 'Check-in Tomorrow', "Your check-in at {$booking['villa_name']} is tomorrow. We look forward to welcoming you!"]);

        // TODO: Send email/SMS
        echo "Reminder sent for booking {$booking['booking_number']}\n";
    }

    // Check-out reminders (day of checkout)
    $today = date('Y-m-d');
    $stmt = $db->prepare("SELECT b.*, v.name as villa_name FROM bookings b JOIN villas v ON b.villa_id = v.id WHERE b.check_out = ? AND b.status = 'checked_in'");
    $stmt->execute([$today]);
    $checkouts = $stmt->fetchAll();

    foreach ($checkouts as $booking) {
        $db->prepare("INSERT INTO notifications (user_id, type, title, message, created_at) VALUES (?, 'reminder', ?, ?, NOW())")
            ->execute([$booking['user_id'], 'Check-out Today', "Your check-out from {$booking['villa_name']} is today. Thank you for staying with us!"]);

        echo "Checkout reminder sent for booking {$booking['booking_number']}\n";
    }

    echo "Reminders cron completed at " . date('Y-m-d H:i:s') . "\n";
} catch (\Exception $e) {
    error_log("Cron Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}
