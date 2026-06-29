<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Core\Database;

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $users = $this->userModel->rawQuery("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.role_id, u.first_name");
        $data = ['pageTitle' => 'User Management', 'users' => $users];
        $this->renderWithLayout('admin.users.index', $data, 'layouts.admin');
    }

    public function create(): void
    {
        $db = Database::getInstance()->getConnection();
        $roles = $db->query("SELECT * FROM roles ORDER BY id")->fetchAll();
        $data = ['pageTitle' => 'Create User', 'roles' => $roles];
        $this->renderWithLayout('admin.users.create', $data, 'layouts.admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/users'); return; }
        $data = $this->getAllInput();
        $this->userModel->createUser($data);
        $this->setFlash('success', 'User created successfully.');
        $this->redirect('/admin/users');
    }

    public function edit(string $id): void
    {
        $user = $this->userModel->find((int)$id);
        $db = Database::getInstance()->getConnection();
        $roles = $db->query("SELECT * FROM roles ORDER BY id")->fetchAll();
        $data = ['pageTitle' => 'Edit User', 'user' => $user, 'roles' => $roles];
        $this->renderWithLayout('admin.users.edit', $data, 'layouts.admin');
    }

    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) { $this->redirect('/admin/users'); return; }
        $data = $this->getAllInput();
        unset($data['_token']);
        if (empty($data['password'])) { unset($data['password']); } else { $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]); }
        $this->userModel->update((int)$id, $data);
        $this->setFlash('success', 'User updated.');
        $this->redirect('/admin/users');
    }

    public function delete(string $id): void
    {
        $this->userModel->update((int)$id, ['is_active' => 0]);
        $this->setFlash('success', 'User deactivated.');
        $this->redirect('/admin/users');
    }
}
