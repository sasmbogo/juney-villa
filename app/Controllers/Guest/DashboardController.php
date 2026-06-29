<?php

declare(strict_types=1);

namespace App\Controllers\Guest;

use App\Core\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index(): void
    {
        $bookingModel = new Booking();
        $bookings = $bookingModel->getByUser((int)$_SESSION['user_id']);
        $upcoming = array_filter($bookings, fn($b) => strtotime($b['check_in']) >= time() && $b['status'] !== 'cancelled');

        $data = [
            'pageTitle' => 'My Dashboard',
            'bookings' => $bookings,
            'upcoming' => array_values($upcoming),
        ];
        $this->renderWithLayout('guest.dashboard', $data, 'layouts.public');
    }

    public function bookings(): void
    {
        $bookingModel = new Booking();
        $bookings = $bookingModel->getByUser((int)$_SESSION['user_id']);
        $data = ['pageTitle' => 'My Bookings', 'bookings' => $bookings];
        $this->renderWithLayout('guest.bookings', $data, 'layouts.public');
    }

    public function profile(): void
    {
        $userModel = new \App\Models\User();
        $user = $userModel->find((int)$_SESSION['user_id']);
        $data = ['pageTitle' => 'My Profile', 'user' => $user];
        $this->renderWithLayout('guest.profile', $data, 'layouts.public');
    }

    public function updateProfile(): void
    {
        if (!$this->verifyCsrf()) { $this->back(); return; }
        $userModel = new \App\Models\User();
        $data = $this->getAllInput();

        $updateData = [
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'phone' => $data['phone'] ?? '',
            'nationality' => $data['nationality'] ?? '',
            'address' => $data['address'] ?? '',
        ];

        // Password change
        if (!empty($data['new_password'])) {
            $user = $userModel->find((int)$_SESSION['user_id']);
            if (!password_verify($data['current_password'] ?? '', $user['password'])) {
                $this->setFlash('error', 'Current password is incorrect.');
                $this->back();
                return;
            }
            $updateData['password'] = password_hash($data['new_password'], PASSWORD_BCRYPT, ['cost' => 12]);
            $updateData['must_change_password'] = 0;
        }

        $userModel->update((int)$_SESSION['user_id'], $updateData);
        $_SESSION['user_name'] = $updateData['first_name'] . ' ' . $updateData['last_name'];
        $this->setFlash('success', 'Profile updated successfully.');
        $this->back();
    }

    public function wishlist(): void
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT v.* FROM wishlists w JOIN villas v ON w.villa_id = v.id WHERE w.user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $data = ['pageTitle' => 'My Wishlist', 'villas' => $stmt->fetchAll()];
        $this->renderWithLayout('guest.wishlist', $data, 'layouts.public');
    }

    public function reviews(): void
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT r.*, v.name as villa_name FROM reviews r JOIN villas v ON r.villa_id = v.id WHERE r.user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $data = ['pageTitle' => 'My Reviews', 'reviews' => $stmt->fetchAll()];
        $this->renderWithLayout('guest.reviews', $data, 'layouts.public');
    }

    public function notifications(): void
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$_SESSION['user_id']]);
        $data = ['pageTitle' => 'Notifications', 'notifications' => $stmt->fetchAll()];
        $this->renderWithLayout('guest.notifications', $data, 'layouts.public');
    }
}
