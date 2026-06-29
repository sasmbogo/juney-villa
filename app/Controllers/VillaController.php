<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Villa;

class VillaController extends Controller
{
    private Villa $villaModel;

    public function __construct()
    {
        $this->villaModel = new Villa();
    }

    public function index(): void
    {
        $villas = $this->villaModel->getActive();

        $data = [
            'pageTitle' => 'Our Luxury Villas - Juney Villa Limited',
            'metaDescription' => 'Browse our collection of 5 luxury villas in Zanzibar. Each offering unique experiences with world-class amenities.',
            'villas' => $villas,
        ];

        $this->renderWithLayout('public.villas.index', $data, 'layouts.public');
    }

    public function show(string $slug): void
    {
        $villa = $this->villaModel->getBySlugWithDetails($slug);

        if (!$villa) {
            throw new \App\Core\Exceptions\NotFoundException('Villa not found.');
        }

        $data = [
            'pageTitle' => $villa['meta_title'] ?: $villa['name'] . ' - Juney Villa Limited',
            'metaDescription' => $villa['meta_description'] ?: $villa['short_description'],
            'villa' => $villa,
        ];

        $this->renderWithLayout('public.villas.show', $data, 'layouts.public');
    }
}
