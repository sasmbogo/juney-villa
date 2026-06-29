<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class ActivityLogController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $logs = $db->query("SELECT al.*, u.first_name, u.last_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 200")->fetchAll();
        $this->renderWithLayout('admin.activity-logs.index', ['pageTitle' => 'Activity Logs', 'logs' => $logs], 'layouts.admin');
    }
}
