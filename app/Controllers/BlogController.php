<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class BlogController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $page = max(1, (int)($this->getInput('page', '1')));
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        $total = $db->query("SELECT COUNT(*) FROM blogs WHERE status = 'published'")->fetchColumn();
        $blogs = $db->prepare("SELECT b.*, bc.name as category_name, u.first_name as author_name FROM blogs b LEFT JOIN blog_categories bc ON b.category_id = bc.id LEFT JOIN users u ON b.author_id = u.id WHERE b.status = 'published' ORDER BY b.published_at DESC LIMIT ? OFFSET ?");
        $blogs->execute([$perPage, $offset]);

        $data = [
            'pageTitle' => 'Blog & Travel Guide - Juney Villa Limited',
            'metaDescription' => 'Read our latest travel guides, Zanzibar tips, and news from Juney Villa.',
            'blogs' => $blogs->fetchAll(),
            'total' => $total,
            'currentPage' => $page,
            'lastPage' => (int)ceil($total / $perPage),
        ];
        $this->renderWithLayout('public.blog.index', $data, 'layouts.public');
    }

    public function show(string $slug): void
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT b.*, bc.name as category_name, u.first_name as author_name FROM blogs b LEFT JOIN blog_categories bc ON b.category_id = bc.id LEFT JOIN users u ON b.author_id = u.id WHERE b.slug = ? AND b.status = 'published'");
        $stmt->execute([$slug]);
        $blog = $stmt->fetch();

        if (!$blog) {
            throw new \App\Core\Exceptions\NotFoundException('Blog post not found.');
        }

        // Increment view
        $db->prepare("UPDATE blogs SET view_count = view_count + 1 WHERE id = ?")->execute([$blog['id']]);

        // Related posts
        $related = $db->prepare("SELECT * FROM blogs WHERE status = 'published' AND id != ? ORDER BY published_at DESC LIMIT 3");
        $related->execute([$blog['id']]);

        $data = [
            'pageTitle' => ($blog['meta_title'] ?: $blog['title']) . ' - Juney Villa Blog',
            'metaDescription' => $blog['meta_description'] ?: $blog['excerpt'],
            'blog' => $blog,
            'related' => $related->fetchAll(),
        ];
        $this->renderWithLayout('public.blog.show', $data, 'layouts.public');
    }
}
