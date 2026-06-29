<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Villa;
use App\Models\Booking;

class BookingController extends Controller
{
    private Villa $villaModel;
    private Booking $bookingModel;

    public function __construct()
    {
        $this->villaModel = new Villa();
        $this->bookingModel = new Booking();
    }

    public function index(): void
    {
        $villas = $this->villaModel->getActive();
        $data = [
            'pageTitle' => 'Book Your Villa - Juney Villa Limited',
            'metaDescription' => 'Book your luxury villa in Zanzibar. Check availability and make a reservation.',
            'villas' => $villas,
        ];
        $this->renderWithLayout('public.booking.index', $data, 'layouts.public');
    }

    public function search(): void
    {
        $checkIn = $this->getInput('check_in');
        $checkOut = $this->getInput('check_out');
        $guests = (int)$this->getInput('guests', '2');

        $villas = $this->villaModel->getActive();
        $available = [];

        foreach ($villas as $villa) {
            if ($villa['max_guests'] >= $guests) {
                if (!$checkIn || !$checkOut || $this->villaModel->checkAvailability((int)$villa['id'], $checkIn, $checkOut)) {
                    $villa['calculated_price'] = $checkIn && $checkOut
                        ? $this->villaModel->getPriceForDates((int)$villa['id'], $checkIn, $checkOut)
                        : (float)$villa['base_price'];
                    $available[] = $villa;
                }
            }
        }

        $data = [
            'pageTitle' => 'Available Villas - Juney Villa Limited',
            'villas' => $available,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
        ];
        $this->renderWithLayout('public.booking.search', $data, 'layouts.public');
    }

    public function checkAvailability(): void
    {
        $villaId = (int)$this->getInput('villa_id');
        $checkIn = $this->getInput('check_in');
        $checkOut = $this->getInput('check_out');

        if (!$villaId || !$checkIn || !$checkOut) {
            $this->json(['available' => false, 'message' => 'Please provide all required fields.']);
            return;
        }

        $available = $this->villaModel->checkAvailability($villaId, $checkIn, $checkOut);
        $price = $available ? $this->villaModel->getPriceForDates($villaId, $checkIn, $checkOut) : 0;
        $nights = (new \DateTime($checkIn))->diff(new \DateTime($checkOut))->days;

        $this->json([
            'available' => $available,
            'price' => $price,
            'nights' => $nights,
            'price_per_night' => $nights > 0 ? round($price / $nights, 2) : 0,
            'message' => $available ? 'Villa is available!' : 'Villa is not available for selected dates.',
        ]);
    }

    public function create(string $slug): void
    {
        $villa = $this->villaModel->getBySlugWithDetails($slug);
        if (!$villa) {
            throw new \App\Core\Exceptions\NotFoundException('Villa not found.');
        }

        $checkIn = $this->getInput('check_in', date('Y-m-d', strtotime('+1 day')));
        $checkOut = $this->getInput('check_out', date('Y-m-d', strtotime('+3 days')));
        $guests = (int)$this->getInput('guests', '2');

        $nights = (new \DateTime($checkIn))->diff(new \DateTime($checkOut))->days;
        $basePrice = $this->villaModel->getPriceForDates((int)$villa['id'], $checkIn, $checkOut);

        // Get extras
        $db = \App\Core\Database::getInstance()->getConnection();
        $extras = $db->query("SELECT * FROM extras_catalog WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

        $data = [
            'pageTitle' => 'Book ' . $villa['name'] . ' - Juney Villa Limited',
            'villa' => $villa,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
            'nights' => $nights,
            'basePrice' => $basePrice,
            'extras' => $extras,
        ];
        $this->renderWithLayout('public.booking.create', $data, 'layouts.public');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['error' => 'Invalid token'], 419);
            return;
        }

        $data = $this->getAllInput();
        $errors = $this->validate($data, [
            'villa_id' => 'required|integer',
            'guest_name' => 'required|min:2',
            'guest_email' => 'required|email',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'adults' => 'required|integer',
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', 'Please fill in all required fields correctly.');
            $_SESSION['old_input'] = $data;
            $this->back();
            return;
        }

        $villaId = (int)$data['villa_id'];
        $checkIn = $data['check_in'];
        $checkOut = $data['check_out'];

        // Verify availability
        if (!$this->villaModel->checkAvailability($villaId, $checkIn, $checkOut)) {
            $this->setFlash('error', 'Sorry, this villa is no longer available for the selected dates.');
            $this->back();
            return;
        }

        $villa = $this->villaModel->find($villaId);
        $nights = (new \DateTime($checkIn))->diff(new \DateTime($checkOut))->days;
        $basePrice = $this->villaModel->getPriceForDates($villaId, $checkIn, $checkOut);
        $cleaningFee = (float)$villa['cleaning_fee'];

        // Calculate extras
        $extrasTotal = 0;
        $bookingExtras = [];
        if (isset($data['extras']) && is_array($data['extras'])) {
            $db = \App\Core\Database::getInstance()->getConnection();
            foreach ($data['extras'] as $extraId => $qty) {
                if ((int)$qty > 0) {
                    $stmt = $db->prepare("SELECT * FROM extras_catalog WHERE id = ?");
                    $stmt->execute([$extraId]);
                    $extra = $stmt->fetch();
                    if ($extra) {
                        $extraPrice = (float)$extra['price'] * (int)$qty;
                        $extrasTotal += $extraPrice;
                        $bookingExtras[] = [
                            'name' => $extra['name'],
                            'description' => $extra['description'],
                            'quantity' => (int)$qty,
                            'unit_price' => (float)$extra['price'],
                            'total_price' => $extraPrice,
                        ];
                    }
                }
            }
        }

        // Apply discount
        $discountAmount = 0;
        $discountCode = $data['promo_code'] ?? '';
        if ($discountCode) {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1 AND (start_date IS NULL OR start_date <= CURDATE()) AND (end_date IS NULL OR end_date >= CURDATE()) AND (usage_limit IS NULL OR usage_count < usage_limit)");
            $stmt->execute([$discountCode]);
            $coupon = $stmt->fetch();
            if ($coupon) {
                if ($coupon['type'] === 'percentage') {
                    $discountAmount = $basePrice * ((float)$coupon['value'] / 100);
                    if ($coupon['max_discount'] && $discountAmount > (float)$coupon['max_discount']) {
                        $discountAmount = (float)$coupon['max_discount'];
                    }
                } else {
                    $discountAmount = (float)$coupon['value'];
                }
            }
        }

        // Calculate tax
        $subtotal = $basePrice + $extrasTotal + $cleaningFee - $discountAmount;
        $taxRate = 18.0; // VAT
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $totalAmount = $subtotal + $taxAmount;

        // Create booking
        $bookingData = [
            'booking_number' => generateBookingNumber(),
            'villa_id' => $villaId,
            'user_id' => $_SESSION['user_id'] ?? null,
            'guest_name' => $data['guest_name'],
            'guest_email' => $data['guest_email'],
            'guest_phone' => $data['guest_phone'] ?? '',
            'guest_country' => $data['guest_country'] ?? '',
            'adults' => (int)$data['adults'],
            'children' => (int)($data['children'] ?? 0),
            'infants' => (int)($data['infants'] ?? 0),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'nights' => $nights,
            'base_price' => $basePrice,
            'extras_total' => $extrasTotal,
            'cleaning_fee' => $cleaningFee,
            'discount_amount' => $discountAmount,
            'discount_code' => $discountCode,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'currency' => 'USD',
            'status' => 'pending',
            'payment_status' => 'pending',
            'special_requests' => $data['special_requests'] ?? '',
            'source' => 'website',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];

        try {
            $bookingId = $this->bookingModel->create($bookingData);

            // Add extras
            if (!empty($bookingExtras)) {
                $this->bookingModel->addExtras($bookingId, $bookingExtras);
            }

            // Update coupon usage
            if ($discountCode && $discountAmount > 0) {
                $db = \App\Core\Database::getInstance()->getConnection();
                $db->prepare("UPDATE coupons SET usage_count = usage_count + 1 WHERE code = ?")->execute([$discountCode]);
            }

            $this->redirect('/booking/confirmation/' . $bookingData['booking_number']);
        } catch (\Exception $e) {
            $this->setFlash('error', 'An error occurred while processing your booking. Please try again.');
            $this->back();
        }
    }

    public function confirmation(string $number): void
    {
        $booking = $this->bookingModel->findByNumber($number);
        if (!$booking) {
            throw new \App\Core\Exceptions\NotFoundException('Booking not found.');
        }

        $villa = $this->villaModel->find((int)$booking['villa_id']);
        $extras = $this->bookingModel->getExtras((int)$booking['id']);

        $data = [
            'pageTitle' => 'Booking Confirmation - Juney Villa Limited',
            'booking' => $booking,
            'villa' => $villa,
            'extras' => $extras,
        ];
        $this->renderWithLayout('public.booking.confirmation', $data, 'layouts.public');
    }

    public function invoice(string $number): void
    {
        $booking = $this->bookingModel->findByNumber($number);
        if (!$booking) {
            throw new \App\Core\Exceptions\NotFoundException('Booking not found.');
        }

        $villa = $this->villaModel->find((int)$booking['villa_id']);
        $extras = $this->bookingModel->getExtras((int)$booking['id']);

        $data = [
            'booking' => $booking,
            'villa' => $villa,
            'extras' => $extras,
        ];
        $this->view('public.booking.invoice', $data);
    }
}
