<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class GalleryManageController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $items = $db->query("SELECT * FROM gallery ORDER BY sort_order, created_at DESC")->fetchAll();
        $this->renderWithLayout('admin.gallery.index', ['pageTitle' => 'Gallery', 'items' => $items], 'layouts.admin');
    }
    public function upload(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/gallery'); return; }
        $data = $this->getAllInput(); unset($data['_token']);
        $db = Database::getInstance()->getConnection();
        if (!empty($_FILES['image']['name'])) {
            $path = uploadFile($_FILES['image'], 'gallery', ['image/jpeg','image/png','image/webp']);
            if ($path) $data['file_path'] = $path;
        }
        $db->prepare("INSERT INTO gallery (title, file_path, file_type, category, villa_id, sort_order, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())")
            ->execute([$data['title'] ?? '', $data['file_path'] ?? '', $data['file_type'] ?? 'image', $data['category'] ?? 'general', $data['villa_id'] ?? null, $data['sort_order'] ?? 0]);
        $this->setFlash('success', 'Gallery item added.');
        $this->redirect('/admin/gallery');
    }
    public function delete(string $id): void
    {
        $db = Database::getInstance()->getConnection();
        $db->prepare("DELETE FROM gallery WHERE id = ?")->execute([$id]);
        $this->setFlash('success', 'Gallery item deleted.');
        $this->redirect('/admin/gallery');
    }
}
