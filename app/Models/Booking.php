<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Booking extends Model
{
    protected static string $table = 'bookings';
    protected static array $fillable = [
        'booking_number', 'villa_id', 'user_id', 'guest_name', 'guest_email',
        'guest_phone', 'guest_country', 'guest_id_type', 'guest_id_number',
        'adults', 'children', 'infants', 'check_in', 'check_out', 'nights',
        'base_price', 'extras_total', 'cleaning_fee', 'discount_amount',
        'discount_code', 'tax_amount', 'total_amount', 'currency', 'exchange_rate',
        'status', 'payment_status', 'special_requests', 'internal_notes',
        'cancellation_reason', 'cancelled_at', 'checked_in_at', 'checked_out_at',
        'confirmed_at', 'confirmed_by', 'source', 'ip_address', 'user_agent'
    ];

    public function findByNumber(string $number): ?array
    {
        return $this->findBy('booking_number', $number);
    }

    public function getWithDetails(int $id): ?array
    {
        $result = $this->rawQuery(
            "SELECT b.*, v.name as villa_name, v.slug as villa_slug, v.featured_image as villa_image FROM bookings b JOIN villas v ON b.villa_id = v.id WHERE b.id = :id",
            ['id' => $id]
        );
        return $result[0] ?? null;
    }

    public function getRecent(int $limit = 10): array
    {
        return $this->rawQuery(
            "SELECT b.*, v.name as villa_name FROM bookings b JOIN villas v ON b.villa_id = v.id ORDER BY b.created_at DESC LIMIT {$limit}"
        );
    }

    public function getTodayCheckins(): array
    {
        return $this->rawQuery(
            "SELECT b.*, v.name as villa_name FROM bookings b JOIN villas v ON b.villa_id = v.id WHERE b.check_in = CURDATE() AND b.status = 'confirmed'"
        );
    }

    public function getTodayCheckouts(): array
    {
        return $this->rawQuery(
            "SELECT b.*, v.name as villa_name FROM bookings b JOIN villas v ON b.villa_id = v.id WHERE b.check_out = CURDATE() AND b.status = 'checked_in'"
        );
    }

    public function getByStatus(string $status): array
    {
        return $this->rawQuery(
            "SELECT b.*, v.name as villa_name FROM bookings b JOIN villas v ON b.villa_id = v.id WHERE b.status = :status ORDER BY b.created_at DESC",
            ['status' => $status]
        );
    }

    public function getByUser(int $userId): array
    {
        return $this->rawQuery(
            "SELECT b.*, v.name as villa_name, v.featured_image as villa_image FROM bookings b JOIN villas v ON b.villa_id = v.id WHERE b.user_id = :user_id ORDER BY b.created_at DESC",
            ['user_id' => $userId]
        );
    }

    public function getMonthlyRevenue(int $year, int $month): float
    {
        $result = $this->rawQuery(
            "SELECT COALESCE(SUM(total_amount), 0) as revenue FROM bookings WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month AND status NOT IN ('cancelled', 'refunded')",
            ['year' => $year, 'month' => $month]
        );
        return (float)($result[0]['revenue'] ?? 0);
    }

    public function getOccupancyRate(int $villaId, string $startDate, string $endDate): float
    {
        $totalDays = (new \DateTime($startDate))->diff(new \DateTime($endDate))->days;
        if ($totalDays <= 0) {
            return 0;
        }

        $result = $this->rawQuery(
            "SELECT COALESCE(SUM(nights), 0) as booked_nights FROM bookings WHERE villa_id = :villa_id AND check_in >= :start AND check_out <= :end AND status NOT IN ('cancelled', 'refunded')",
            ['villa_id' => $villaId, 'start' => $startDate, 'end' => $endDate]
        );

        $bookedNights = (int)($result[0]['booked_nights'] ?? 0);
        return round(($bookedNights / $totalDays) * 100, 1);
    }

    public function getDashboardStats(): array
    {
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');

        return [
            'total_bookings' => $this->count(),
            'pending_bookings' => $this->count(['status' => 'pending']),
            'confirmed_bookings' => $this->count(['status' => 'confirmed']),
            'today_checkins' => count($this->getTodayCheckins()),
            'today_checkouts' => count($this->getTodayCheckouts()),
            'monthly_revenue' => $this->getMonthlyRevenue((int)date('Y'), (int)date('m')),
            'cancelled_bookings' => $this->count(['status' => 'cancelled']),
        ];
    }

    public function addExtras(int $bookingId, array $extras): void
    {
        foreach ($extras as $extra) {
            $this->rawExecute(
                "INSERT INTO booking_extras (booking_id, name, description, quantity, unit_price, total_price, created_at) VALUES (:booking_id, :name, :desc, :qty, :unit_price, :total_price, NOW())",
                [
                    'booking_id' => $bookingId,
                    'name' => $extra['name'],
                    'desc' => $extra['description'] ?? '',
                    'qty' => $extra['quantity'],
                    'unit_price' => $extra['unit_price'],
                    'total_price' => $extra['total_price'],
                ]
            );
        }
    }

    public function getExtras(int $bookingId): array
    {
        return $this->rawQuery(
            "SELECT * FROM booking_extras WHERE booking_id = :id",
            ['id' => $bookingId]
        );
    }
}
