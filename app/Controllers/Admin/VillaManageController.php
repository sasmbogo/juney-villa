<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Villa;
use App\Core\Database;

class VillaManageController extends Controller
{
    private Villa $villaModel;

    public function __construct()
    {
        $this->villaModel = new Villa();
    }

    public function index(): void
    {
        $villas = $this->villaModel->all('sort_order', 'ASC');
        $data = ['pageTitle' => 'Villa Management', 'villas' => $villas];
        $this->renderWithLayout('admin.villas.index', $data, 'layouts.admin');
    }

    public function create(): void
    {
        $db = Database::getInstance()->getConnection();
        $amenities = $db->query("SELECT * FROM amenities WHERE is_active = 1 ORDER BY sort_order")->fetchAll();
        $data = ['pageTitle' => 'Add New Villa', 'amenities' => $amenities];
        $this->renderWithLayout('admin.villas.create', $data, 'layouts.admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->redirect('/admin/villas');
            return;
        }

        $data = $this->getAllInput();
        $data['slug'] = slugify($data['name']);

        $villaId = $this->villaModel->create($data);

        // Attach amenities
        if (isset($data['amenities']) && is_array($data['amenities'])) {
            $db = Database::getInstance()->getConnection();
            foreach ($data['amenities'] as $amenityId) {
                $db->prepare("INSERT INTO villa_amenities (villa_id, amenity_id) VALUES (?, ?)")->execute([$villaId, $amenityId]);
            }
        }

        $this->setFlash('success', 'Villa created successfully.');
        $this->redirect('/admin/villas');
    }

    public function edit(string $id): void
    {
        $villa = $this->villaModel->getWithDetails((int)$id);
        if (!$villa) {
            $this->setFlash('error', 'Villa not found.');
            $this->redirect('/admin/villas');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $amenities = $db->query("SELECT * FROM amenities WHERE is_active = 1 ORDER BY sort_order")->fetchAll();
        $villaAmenityIds = array_column($villa['amenities'], 'id');

        $data = ['pageTitle' => 'Edit Villa', 'villa' => $villa, 'amenities' => $amenities, 'villaAmenityIds' => $villaAmenityIds];
        $this->renderWithLayout('admin.villas.edit', $data, 'layouts.admin');
    }

    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->redirect('/admin/villas');
            return;
        }

        $data = $this->getAllInput();
        $this->villaModel->update((int)$id, $data);

        // Update amenities
        $db = Database::getInstance()->getConnection();
        $db->prepare("DELETE FROM villa_amenities WHERE villa_id = ?")->execute([$id]);
        if (isset($data['amenities']) && is_array($data['amenities'])) {
            foreach ($data['amenities'] as $amenityId) {
                $db->prepare("INSERT INTO villa_amenities (villa_id, amenity_id) VALUES (?, ?)")->execute([$id, $amenityId]);
            }
        }

        $this->setFlash('success', 'Villa updated successfully.');
        $this->redirect('/admin/villas');
    }

    public function delete(string $id): void
    {
        $this->villaModel->delete((int)$id);
        $this->setFlash('success', 'Villa deleted successfully.');
        $this->redirect('/admin/villas');
    }
}
