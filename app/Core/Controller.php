<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        echo $content;
    }

    protected function renderWithLayout(string $view, array $data = [], string $layout = 'layouts.main'): void
    {
        extract($data);
        $viewPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $layoutPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $layout) . '.php';
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function getInput(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function getAllInput(): array
    {
        $input = array_merge($_GET, $_POST);
        $jsonInput = json_decode(file_get_contents('php://input'), true);
        if ($jsonInput) {
            $input = array_merge($input, $jsonInput);
        }
        return $input;
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $ruleSet) {
            $fieldRules = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                $error = $this->applyRule($field, $value, $rule, $params, $data);
                if ($error) {
                    $errors[$field][] = $error;
                }
            }
        }
        return $errors;
    }

    private function applyRule(string $field, mixed $value, string $rule, array $params, array $data): ?string
    {
        $label = ucfirst(str_replace('_', ' ', $field));

        return match ($rule) {
            'required' => empty($value) && $value !== '0' ? "{$label} is required." : null,
            'email' => $value && !filter_var($value, FILTER_VALIDATE_EMAIL) ? "{$label} must be a valid email." : null,
            'min' => $value && strlen((string)$value) < (int)$params[0] ? "{$label} must be at least {$params[0]} characters." : null,
            'max' => $value && strlen((string)$value) > (int)$params[0] ? "{$label} must not exceed {$params[0]} characters." : null,
            'numeric' => $value && !is_numeric($value) ? "{$label} must be numeric." : null,
            'integer' => $value && !filter_var($value, FILTER_VALIDATE_INT) ? "{$label} must be an integer." : null,
            'confirmed' => $value !== ($data[$field . '_confirmation'] ?? null) ? "{$label} confirmation does not match." : null,
            'date' => $value && !strtotime($value) ? "{$label} must be a valid date." : null,
            'url' => $value && !filter_var($value, FILTER_VALIDATE_URL) ? "{$label} must be a valid URL." : null,
            default => null,
        };
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf(): bool
    {
        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }
}
