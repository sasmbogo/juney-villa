<?php

declare(strict_types=1);

namespace App\Middleware;

class AuthMiddleware
{
    public function handle(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
        return true;
    }
}
