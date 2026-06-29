<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class HousekeepingController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $tasks = $db->query("SELECT h.*, v.name as villa_name, u.first_name as staff_name FROM housekeeping h JOIN villas v ON h.villa_id = v.id LEFT JOIN users u ON h.assigned_to = u.id ORDER BY h.scheduled_date DESC")->fetchAll();
        $villas = $db->query("SELECT id, name FROM villas WHERE status = 'active'")->fetchAll();
        $staff = $db->query("SELECT id, first_name, last_name FROM users WHERE role_id = 5")->fetchAll();
        $this->renderWithLayout('admin.housekeeping.index', ['pageTitle' => 'Housekeeping', 'tasks' => $tasks, 'villas' => $villas, 'staff' => $staff], 'layouts.admin');
    }
    public function store(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/housekeeping'); return; }
        $db = Database::getInstance()->getConnection();
        $data = $this->getAllInput(); unset($data['_token']);
        $db->prepare("INSERT INTO housekeeping (villa_id, assigned_to, task_type, priority, scheduled_date, notes, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())")
            ->execute([$data['villa_id'], $data['assigned_to'] ?? null, $data['task_type'], $data['priority'] ?? 'normal', $data['scheduled_date'], $data['notes'] ?? '']);
        $this->setFlash('success', 'Task created.');
        $this->redirect('/admin/housekeeping');
    }
    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/housekeeping'); return; }
        $db = Database::getInstance()->getConnection();
        $status = $this->getInput('status', 'completed');
        $db->prepare("UPDATE housekeeping SET status = ?, completed_at = IF(? = 'completed', NOW(), NULL), updated_at = NOW() WHERE id = ?")->execute([$status, $status, $id]);
        $this->setFlash('success', 'Task updated.');
        $this->redirect('/admin/housekeeping');
    }
}
