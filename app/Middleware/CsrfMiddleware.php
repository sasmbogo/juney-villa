<?php

declare(strict_types=1);

namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }

        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(419);
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'CSRF token mismatch']);
            } else {
                echo '<h1>419 - Page Expired</h1><p>Your session has expired. Please refresh and try again.</p>';
            }
            exit;
        }

        return true;
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
