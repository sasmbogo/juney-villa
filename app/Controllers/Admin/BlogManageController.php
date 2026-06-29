<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\Database;

class BlogManageController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $blogs = $db->query("SELECT b.*, bc.name as category_name FROM blogs b LEFT JOIN blog_categories bc ON b.category_id = bc.id ORDER BY b.created_at DESC")->fetchAll();
        $this->renderWithLayout('admin.blog.index', ['pageTitle' => 'Blog Management', 'blogs' => $blogs], 'layouts.admin');
    }
    public function create(): void
    {
        $db = Database::getInstance()->getConnection();
        $categories = $db->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
        $this->renderWithLayout('admin.blog.create', ['pageTitle' => 'New Blog Post', 'categories' => $categories], 'layouts.admin');
    }
    public function store(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/blog'); return; }
        $data = $this->getAllInput(); unset($data['_token']);
        $data['slug'] = slugify($data['title']);
        $data['author_id'] = $_SESSION['user_id'];
        $data['published_at'] = $data['status'] === 'published' ? date('Y-m-d H:i:s') : null;
        $db = Database::getInstance()->getConnection();
        $cols = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $db->prepare("INSERT INTO blogs ({$cols}, created_at) VALUES ({$placeholders}, NOW())")->execute(array_values($data));
        $this->setFlash('success', 'Blog post created.');
        $this->redirect('/admin/blog');
    }
    public function edit(string $id): void
    {
        $db = Database::getInstance()->getConnection();
        $blog = $db->prepare("SELECT * FROM blogs WHERE id = ?"); $blog->execute([$id]);
        $categories = $db->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
        $this->renderWithLayout('admin.blog.edit', ['pageTitle' => 'Edit Post', 'blog' => $blog->fetch(), 'categories' => $categories], 'layouts.admin');
    }
    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/blog'); return; }
        $data = $this->getAllInput(); unset($data['_token']);
        $db = Database::getInstance()->getConnection();
        $sets = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $db->prepare("UPDATE blogs SET {$sets}, updated_at = NOW() WHERE id = ?")->execute([...array_values($data), $id]);
        $this->setFlash('success', 'Post updated.');
        $this->redirect('/admin/blog');
    }
    public function delete(string $id): void
    {
        $db = Database::getInstance()->getConnection();
        $db->prepare("DELETE FROM blogs WHERE id = ?")->execute([$id]);
        $this->setFlash('success', 'Post deleted.');
        $this->redirect('/admin/blog');
    }
}
