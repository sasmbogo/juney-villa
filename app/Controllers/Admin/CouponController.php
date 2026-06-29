<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class CouponController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $coupons = $db->query("SELECT * FROM coupons ORDER BY created_at DESC")->fetchAll();
        $this->renderWithLayout('admin.coupons.index', ['pageTitle' => 'Coupons', 'coupons' => $coupons], 'layouts.admin');
    }
    public function store(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/coupons'); return; }
        $data = $this->getAllInput(); unset($data['_token']);
        $db = Database::getInstance()->getConnection();
        $db->prepare("INSERT INTO coupons (code, type, value, max_discount, min_booking, usage_limit, start_date, end_date, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())")
            ->execute([$data['code'], $data['type'], $data['value'], $data['max_discount'] ?? null, $data['min_booking'] ?? null, $data['usage_limit'] ?? null, $data['start_date'] ?? null, $data['end_date'] ?? null]);
        $this->setFlash('success', 'Coupon created.');
        $this->redirect('/admin/coupons');
    }
    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/coupons'); return; }
        $data = $this->getAllInput(); unset($data['_token']);
        $db = Database::getInstance()->getConnection();
        $sets = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $db->prepare("UPDATE coupons SET {$sets} WHERE id = ?")->execute([...array_values($data), $id]);
        $this->setFlash('success', 'Coupon updated.');
        $this->redirect('/admin/coupons');
    }
    public function delete(string $id): void
    {
        $db = Database::getInstance()->getConnection();
        $db->prepare("DELETE FROM coupons WHERE id = ?")->execute([$id]);
        $this->setFlash('success', 'Coupon deleted.');
        $this->redirect('/admin/coupons');
    }
}
