<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class SettingsController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $settings = $db->query("SELECT * FROM settings ORDER BY setting_group, id")->fetchAll();
        $grouped = [];
        foreach ($settings as $s) { $grouped[$s['setting_group']][] = $s; }
        $data = ['pageTitle' => 'System Settings', 'settings' => $grouped];
        $this->renderWithLayout('admin.settings.index', $data, 'layouts.admin');
    }

    public function update(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/settings'); return; }
        $db = Database::getInstance()->getConnection();
        $data = $this->getAllInput();
        unset($data['_token']);
        foreach ($data as $key => $value) {
            $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?")->execute([$value, $key]);
        }
        $this->setFlash('success', 'Settings updated successfully.');
        $this->redirect('/admin/settings');
    }

    public function company(): void
    {
        $db = Database::getInstance()->getConnection();
        $company = $db->query("SELECT * FROM company LIMIT 1")->fetch();
        $data = ['pageTitle' => 'Company Profile', 'company' => $company];
        $this->renderWithLayout('admin.settings.company', $data, 'layouts.admin');
    }

    public function updateCompany(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/settings/company'); return; }
        $db = Database::getInstance()->getConnection();
        $data = $this->getAllInput();
        unset($data['_token']);
        $sets = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $db->prepare("UPDATE company SET {$sets} WHERE id = 1")->execute(array_values($data));
        $this->setFlash('success', 'Company profile updated.');
        $this->redirect('/admin/settings/company');
    }
}
