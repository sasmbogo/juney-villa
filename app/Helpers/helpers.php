<?php

declare(strict_types=1);

/**
 * Global Helper Functions
 */

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $url = rtrim($_ENV['APP_URL'] ?? '', '/');
        return $path ? $url . '/' . ltrim($path, '/') : $url;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return base_url($path) . '?v=' . ($_ENV['APP_VERSION'] ?? '1.0');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        $token = $_SESSION['csrf_token'] ?? '';
        if (empty($token)) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $token;
        }
        return '<input type="hidden" name="_token" value="' . htmlspecialchars($token) . '">';
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        return htmlspecialchars($_SESSION['old_input'][$key] ?? $default);
    }
}

if (!function_exists('flash')) {
    function flash(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('auth')) {
    function auth(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role_id' => $_SESSION['user_role_id'] ?? 9,
            'role' => $_SESSION['user_role'] ?? 'guest',
            'avatar' => $_SESSION['user_avatar'] ?? null,
        ];
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] <= 8;
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission(string $permission): bool
    {
        return in_array($permission, $_SESSION['user_permissions'] ?? []);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency(float $amount, string $currency = 'USD'): string
    {
        $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'TZS' => 'TSh'];
        $symbol = $symbols[$currency] ?? $currency . ' ';
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('formatDate')) {
    function formatDate(?string $date, string $format = 'M d, Y'): string
    {
        if (!$date) {
            return '';
        }
        return date($format, strtotime($date));
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo(string $datetime): string
    {
        $now = new DateTime();
        $past = new DateTime($datetime);
        $diff = $now->diff($past);

        if ($diff->y > 0) {
            return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
        }
        if ($diff->m > 0) {
            return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        }
        if ($diff->d > 0) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        }
        if ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        }
        if ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        }
        return 'Just now';
    }
}

if (!function_exists('generateBookingNumber')) {
    function generateBookingNumber(): string
    {
        return 'JV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }
}

if (!function_exists('generateTicketNumber')) {
    function generateTicketNumber(): string
    {
        return 'TKT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}

if (!function_exists('truncate')) {
    function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('setting')) {
    function setting(string $key, string $default = ''): string
    {
        static $settings = null;
        if ($settings === null) {
            try {
                $db = \App\Core\Database::getInstance()->getConnection();
                $stmt = $db->query("SELECT `group`, `key`, `value` FROM settings");
                $rows = $stmt->fetchAll();
                $settings = [];
                foreach ($rows as $row) {
                    $settings[$row['group'] . '.' . $row['key']] = $row['value'];
                }
            } catch (\Exception $e) {
                $settings = [];
            }
        }
        return $settings[$key] ?? $default;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile(array $file, string $directory = 'uploads', array $allowedTypes = []): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!empty($allowedTypes)) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedTypes)) {
                return null;
            }
        }

        $uploadDir = PUBLIC_PATH . '/' . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        $filepath = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $directory . '/' . $filename;
        }

        return null;
    }
}
