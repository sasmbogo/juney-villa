<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\Villa;

class VillaApiController extends Controller
{
    public function index(): void
    {
        $villaModel = new Villa();
        $villas = $villaModel->getActive();
        $this->json(['success' => true, 'data' => $villas]);
    }

    public function show(string $slug): void
    {
        $villaModel = new Villa();
        $villa = $villaModel->getBySlugWithDetails($slug);
        if (!$villa) {
            $this->json(['success' => false, 'message' => 'Villa not found'], 404);
            return;
        }
        $this->json(['success' => true, 'data' => $villa]);
    }
}
