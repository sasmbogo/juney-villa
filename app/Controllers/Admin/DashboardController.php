<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Booking;
use App\Models\Villa;

class DashboardController extends Controller
{
    public function index(): void
    {
        $bookingModel = new Booking();
        $villaModel = new Villa();
        $db = Database::getInstance()->getConnection();

        $stats = $bookingModel->getDashboardStats();
        $stats['total_villas'] = $villaModel->count(['status' => 'active']);
        $stats['total_guests'] = $db->query("SELECT COUNT(*) FROM users WHERE role_id = 9")->fetchColumn();

        // Monthly revenue chart data
        $monthlyRevenue = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[] = $bookingModel->getMonthlyRevenue((int)date('Y'), $m);
        }

        // Recent bookings
        $recentBookings = $bookingModel->getRecent(10);

        // Today's check-ins/outs
        $todayCheckins = $bookingModel->getTodayCheckins();
        $todayCheckouts = $bookingModel->getTodayCheckouts();

        // Occupancy rates
        $occupancy = [];
        $villas = $villaModel->getActive();
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        foreach ($villas as $villa) {
            $occupancy[] = [
                'name' => $villa['name'],
                'rate' => $bookingModel->getOccupancyRate((int)$villa['id'], $monthStart, $monthEnd),
            ];
        }

        $data = [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'monthlyRevenue' => $monthlyRevenue,
            'recentBookings' => $recentBookings,
            'todayCheckins' => $todayCheckins,
            'todayCheckouts' => $todayCheckouts,
            'occupancy' => $occupancy,
        ];

        $this->renderWithLayout('admin.dashboard.index', $data, 'layouts.admin');
    }
}
