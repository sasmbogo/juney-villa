<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(): void
    {
        $model = new Review();
        $reviews = $model->rawQuery("SELECT r.*, v.name as villa_name FROM reviews r JOIN villas v ON r.villa_id = v.id ORDER BY r.created_at DESC");
        $this->renderWithLayout('admin.reviews.index', ['pageTitle' => 'Reviews', 'reviews' => $reviews], 'layouts.admin');
    }
    public function approve(string $id): void
    {
        (new Review())->update((int)$id, ['is_approved' => 1]);
        $this->setFlash('success', 'Review approved.');
        $this->redirect('/admin/reviews');
    }
    public function reply(string $id): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/reviews'); return; }
        (new Review())->update((int)$id, ['admin_reply' => $this->getInput('reply'), 'admin_reply_at' => date('Y-m-d H:i:s')]);
        $this->setFlash('success', 'Reply posted.');
        $this->redirect('/admin/reviews');
    }
}
