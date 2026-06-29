<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class GalleryController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();
        $category = $this->getInput('category', 'all');

        $query = "SELECT g.*, v.name as villa_name FROM gallery g LEFT JOIN villas v ON g.villa_id = v.id";
        $params = [];

        if ($category !== 'all') {
            $query .= " WHERE g.category = ?";
            $params[] = $category;
        }

        $query .= " ORDER BY g.sort_order ASC, g.created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        $data = [
            'pageTitle' => 'Gallery - Juney Villa Limited',
            'metaDescription' => 'Browse our gallery of luxury villas, beaches, and experiences in Zanzibar.',
            'items' => $stmt->fetchAll(),
            'category' => $category,
        ];
        $this->renderWithLayout('public.gallery.index', $data, 'layouts.public');
    }
}
