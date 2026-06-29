<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;

class PaymentCallbackController extends Controller
{
    public function mpesa(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        $this->processCallback('mpesa', $payload);
    }

    public function stripe(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        $this->processCallback('stripe', $payload);
    }

    public function paypal(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        $this->processCallback('paypal', $payload);
    }

    public function flutterwave(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        $this->processCallback('flutterwave', $payload);
    }

    public function pesapal(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        $this->processCallback('pesapal', $payload);
    }

    private function processCallback(string $gateway, ?array $payload): void
    {
        try {
            $db = Database::getInstance()->getConnection();

            // Log the callback
            $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, created_at) VALUES (NULL, ?, ?, ?, NOW())")
                ->execute(["payment_callback_{$gateway}", json_encode($payload), $_SERVER['REMOTE_ADDR'] ?? '']);

            // Process based on gateway
            $transactionId = $payload['transaction_id'] ?? $payload['TransID'] ?? $payload['id'] ?? '';
            $status = $payload['status'] ?? $payload['ResultCode'] ?? '';

            if ($transactionId) {
                $stmt = $db->prepare("SELECT * FROM payments WHERE transaction_id = ?");
                $stmt->execute([$transactionId]);
                $payment = $stmt->fetch();

                if ($payment) {
                    $newStatus = $this->mapStatus($gateway, $status);
                    $db->prepare("UPDATE payments SET status = ?, gateway_response = ?, paid_at = IF(? = 'completed', NOW(), paid_at), updated_at = NOW() WHERE id = ?")
                        ->execute([$newStatus, json_encode($payload), $newStatus, $payment['id']]);

                    if ($newStatus === 'completed') {
                        $db->prepare("UPDATE bookings SET payment_status = 'paid' WHERE id = ?")
                            ->execute([$payment['booking_id']]);
                    }
                }
            }

            $this->json(['status' => 'success']);
        } catch (\Exception $e) {
            $this->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function mapStatus(string $gateway, mixed $status): string
    {
        return match (true) {
            $status === '0' && $gateway === 'mpesa' => 'completed',
            $status === 'success' || $status === 'COMPLETED' || $status === 'paid' => 'completed',
            $status === 'failed' || $status === 'FAILED' => 'failed',
            default => 'pending',
        };
    }
}
