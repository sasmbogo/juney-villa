<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\Villa;
use App\Core\Database;

class BookingApiController extends Controller
{
    public function availability(string $villaId): void
    {
        $villaModel = new Villa();
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT check_in, check_out FROM bookings WHERE villa_id = ? AND status NOT IN ('cancelled','refunded') AND check_out >= CURDATE()");
        $stmt->execute([$villaId]);
        $bookings = $stmt->fetchAll();

        $blockedDates = [];
        foreach ($bookings as $booking) {
            $start = new \DateTime($booking['check_in']);
            $end = new \DateTime($booking['check_out']);
            while ($start < $end) {
                $blockedDates[] = $start->format('Y-m-d');
                $start->modify('+1 day');
            }
        }

        $this->json(['success' => true, 'blocked_dates' => $blockedDates]);
    }

    public function checkAvailability(): void
    {
        $villaId = (int)$this->getInput('villa_id');
        $checkIn = $this->getInput('check_in');
        $checkOut = $this->getInput('check_out');

        $villaModel = new Villa();
        $available = $villaModel->checkAvailability($villaId, $checkIn, $checkOut);
        $price = $available ? $villaModel->getPriceForDates($villaId, $checkIn, $checkOut) : 0;
        $nights = (new \DateTime($checkIn))->diff(new \DateTime($checkOut))->days;

        $this->json([
            'success' => true,
            'available' => $available,
            'price' => $price,
            'nights' => $nights,
        ]);
    }

    public function validateCoupon(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $code = $input['code'] ?? '';

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1 AND (start_date IS NULL OR start_date <= CURDATE()) AND (end_date IS NULL OR end_date >= CURDATE()) AND (usage_limit IS NULL OR usage_count < usage_limit)");
        $stmt->execute([$code]);
        $coupon = $stmt->fetch();

        if (!$coupon) {
            $this->json(['valid' => false, 'message' => 'Invalid or expired coupon code.']);
            return;
        }

        $this->json([
            'valid' => true,
            'type' => $coupon['type'],
            'value' => (float)$coupon['value'],
            'max_discount' => $coupon['max_discount'] ? (float)$coupon['max_discount'] : null,
            'message' => "Coupon applied: {$coupon['value']}" . ($coupon['type'] === 'percentage' ? '% off' : ' USD off'),
        ]);
    }
}
