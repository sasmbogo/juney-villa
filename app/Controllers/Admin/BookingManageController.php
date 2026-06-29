<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Booking;
use App\Models\Villa;
use App\Core\Database;

class BookingManageController extends Controller
{
    private Booking $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
    }

    public function index(): void
    {
        $status = $this->getInput('status', '');
        $bookings = $status ? $this->bookingModel->getByStatus($status) : $this->bookingModel->getRecent(50);
        $data = ['pageTitle' => 'Booking Management', 'bookings' => $bookings, 'currentStatus' => $status];
        $this->renderWithLayout('admin.bookings.index', $data, 'layouts.admin');
    }

    public function view(string $id): void
    {
        $booking = $this->bookingModel->getWithDetails((int)$id);
        if (!$booking) {
            $this->setFlash('error', 'Booking not found.');
            $this->redirect('/admin/bookings');
            return;
        }

        $extras = $this->bookingModel->getExtras((int)$id);
        $payments = (new \App\Models\Payment())->getByBooking((int)$id);

        $data = ['pageTitle' => 'Booking #' . $booking['booking_number'], 'booking' => $booking, 'extras' => $extras, 'payments' => $payments];
        $this->renderWithLayout('admin.bookings.view', $data, 'layouts.admin');
    }

    public function create(): void
    {
        $villaModel = new Villa();
        $villas = $villaModel->getActive();
        $data = ['pageTitle' => 'Create Booking', 'villas' => $villas];
        $this->renderWithLayout('admin.bookings.create', $data, 'layouts.admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->redirect('/admin/bookings');
            return;
        }
        $data = $this->getAllInput();
        $data['booking_number'] = generateBookingNumber();
        $data['source'] = 'admin';
        $data['status'] = 'confirmed';
        $data['confirmed_at'] = date('Y-m-d H:i:s');
        $data['confirmed_by'] = $_SESSION['user_id'];

        $this->bookingModel->create($data);
        $this->setFlash('success', 'Booking created successfully.');
        $this->redirect('/admin/bookings');
    }

    public function approve(string $id): void
    {
        $this->bookingModel->update((int)$id, [
            'status' => 'confirmed',
            'confirmed_at' => date('Y-m-d H:i:s'),
            'confirmed_by' => $_SESSION['user_id'],
        ]);
        $this->setFlash('success', 'Booking confirmed.');
        $this->redirect('/admin/bookings/view/' . $id);
    }

    public function cancel(string $id): void
    {
        $reason = $this->getInput('reason', 'Cancelled by admin');
        $this->bookingModel->update((int)$id, [
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => date('Y-m-d H:i:s'),
        ]);
        $this->setFlash('success', 'Booking cancelled.');
        $this->redirect('/admin/bookings/view/' . $id);
    }

    public function checkin(string $id): void
    {
        $this->bookingModel->update((int)$id, [
            'status' => 'checked_in',
            'checked_in_at' => date('Y-m-d H:i:s'),
        ]);
        $this->setFlash('success', 'Guest checked in.');
        $this->redirect('/admin/bookings/view/' . $id);
    }

    public function checkout(string $id): void
    {
        $this->bookingModel->update((int)$id, [
            'status' => 'checked_out',
            'checked_out_at' => date('Y-m-d H:i:s'),
        ]);
        $this->setFlash('success', 'Guest checked out.');
        $this->redirect('/admin/bookings/view/' . $id);
    }
}
