<?php

declare(strict_types=1);

namespace App\Middleware;

class AdminMiddleware
{
    private array $adminRoles = [1, 2, 3, 4, 5, 6, 7, 8]; // All staff roles

    public function handle(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if (!in_array($_SESSION['user_role_id'] ?? 0, $this->adminRoles)) {
            http_response_code(403);
            include BASE_PATH . '/app/Views/errors/403.php';
            exit;
        }

        return true;
    }
}
