<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class NotificationController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $notifications = $db->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 100")->fetchAll();
        $this->renderWithLayout('admin.notifications.index', ['pageTitle' => 'Notifications', 'notifications' => $notifications], 'layouts.admin');
    }
}
