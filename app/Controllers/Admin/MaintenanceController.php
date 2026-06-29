<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class MaintenanceController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $tasks = $db->query("SELECT m.*, v.name as villa_name, u.first_name as tech_name FROM maintenance m JOIN villas v ON m.villa_id = v.id LEFT JOIN users u ON m.assigned_to = u.id ORDER BY m.created_at DESC")->fetchAll();
        $villas = $db->query("SELECT id, name FROM villas WHERE status = 'active'")->fetchAll();
        $staff = $db->query("SELECT id, first_name, last_name FROM users WHERE role_id = 6")->fetchAll();
        $this->renderWithLayout('admin.maintenance.index', ['pageTitle' => 'Maintenance', 'tasks' => $tasks, 'villas' => $villas, 'staff' => $staff], 'layouts.admin');
    }
    public function store(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/maintenance'); return; }
        $db = Database::getInstance()->getConnection();
        $data = $this->getAllInput(); unset($data['_token']);
        $db->prepare("INSERT INTO maintenance (villa_id, assigned_to, title, description, priority, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())")
            ->execute([$data['villa_id'], $data['assigned_to'] ?? null, $data['title'], $data['description'] ?? '', $data['priority'] ?? 'normal']);
        $this->setFlash('success', 'Maintenance request created.');
        $this->redirect('/admin/maintenance');
    }
    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/maintenance'); return; }
        $db = Database::getInstance()->getConnection();
        $status = $this->getInput('status', 'completed');
        $db->prepare("UPDATE maintenance SET status = ?, completed_at = IF(? = 'completed', NOW(), NULL), updated_at = NOW() WHERE id = ?")->execute([$status, $status, $id]);
        $this->setFlash('success', 'Request updated.');
        $this->redirect('/admin/maintenance');
    }
}
