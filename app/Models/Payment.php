<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Payment extends Model
{
    protected static string $table = 'payments';
    protected static array $fillable = [
        'booking_id', 'payment_method_id', 'transaction_id', 'reference_number',
        'amount', 'currency', 'exchange_rate', 'fee', 'net_amount', 'status',
        'gateway_response', 'paid_at', 'refunded_at', 'refund_reason', 'notes', 'ip_address'
    ];

    public function getByBooking(int $bookingId): array
    {
        return $this->rawQuery(
            "SELECT p.*, pm.name as method_name FROM payments p LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id WHERE p.booking_id = :id ORDER BY p.created_at DESC",
            ['id' => $bookingId]
        );
    }

    public function getTotalPaid(int $bookingId): float
    {
        $result = $this->rawQuery(
            "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE booking_id = :id AND status = 'completed'",
            ['id' => $bookingId]
        );
        return (float)($result[0]['total'] ?? 0);
    }

    public function getRevenueByPeriod(string $startDate, string $endDate): float
    {
        $result = $this->rawQuery(
            "SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed' AND paid_at BETWEEN :start AND :end",
            ['start' => $startDate, 'end' => $endDate]
        );
        return (float)($result[0]['total'] ?? 0);
    }

    public function getMonthlyRevenue(int $year): array
    {
        return $this->rawQuery(
            "SELECT MONTH(paid_at) as month, COALESCE(SUM(amount), 0) as revenue FROM payments WHERE status = 'completed' AND YEAR(paid_at) = :year GROUP BY MONTH(paid_at) ORDER BY month",
            ['year' => $year]
        );
    }

    public function getPaymentMethods(): array
    {
        return $this->rawQuery("SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY sort_order");
    }
}
