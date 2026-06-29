<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class ReportController extends Controller
{
    public function index(): void { $this->renderWithLayout('admin.reports.index', ['pageTitle' => 'Reports'], 'layouts.admin'); }
    public function revenue(): void
    {
        $db = Database::getInstance()->getConnection();
        $year = (int)($_GET['year'] ?? date('Y'));
        $data = $db->prepare("SELECT MONTH(paid_at) as month, SUM(amount) as total FROM payments WHERE status = 'completed' AND YEAR(paid_at) = ? GROUP BY MONTH(paid_at)");
        $data->execute([$year]);
        $this->renderWithLayout('admin.reports.revenue', ['pageTitle' => 'Revenue Report', 'revenue' => $data->fetchAll(), 'year' => $year], 'layouts.admin');
    }
    public function occupancy(): void
    {
        $db = Database::getInstance()->getConnection();
        $data = $db->query("SELECT v.name, COUNT(b.id) as bookings, SUM(b.nights) as total_nights FROM villas v LEFT JOIN bookings b ON v.id = b.villa_id AND b.status NOT IN ('cancelled','refunded') GROUP BY v.id, v.name ORDER BY total_nights DESC")->fetchAll();
        $this->renderWithLayout('admin.reports.occupancy', ['pageTitle' => 'Occupancy Report', 'data' => $data], 'layouts.admin');
    }
    public function bookings(): void
    {
        $db = Database::getInstance()->getConnection();
        $data = $db->query("SELECT status, COUNT(*) as count FROM bookings GROUP BY status")->fetchAll();
        $this->renderWithLayout('admin.reports.bookings', ['pageTitle' => 'Booking Report', 'data' => $data], 'layouts.admin');
    }
}
