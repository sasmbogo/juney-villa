<?php

declare(strict_types=1);

namespace App\Core;

class Application
{
    private Router $router;
    private static ?Application $instance = null;

    public function __construct()
    {
        self::$instance = $this;
        $this->router = new Router();
        $this->loadRoutes();
    }

    public static function getInstance(): ?Application
    {
        return self::$instance;
    }

    public function run(): void
    {
        try {
            $url = $this->parseUrl();
            $method = $_SERVER['REQUEST_METHOD'];
            $this->router->dispatch($method, $url);
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function parseUrl(): string
    {
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url ?: '/';
    }

    private function loadRoutes(): void
    {
        require_once BASE_PATH . '/routes/web.php';
        require_once BASE_PATH . '/routes/api.php';
        require_once BASE_PATH . '/routes/admin.php';
    }

    private function handleException(\Throwable $e): void
    {
        $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($e instanceof \App\Core\Exceptions\NotFoundException) {
            http_response_code(404);
            if ($debug) {
                echo '<h1>404 - Page Not Found</h1><p>' . $e->getMessage() . '</p>';
            } else {
                include BASE_PATH . '/app/Views/errors/404.php';
            }
        } elseif ($e instanceof \App\Core\Exceptions\ForbiddenException) {
            http_response_code(403);
            include BASE_PATH . '/app/Views/errors/403.php';
        } else {
            http_response_code(500);
            if ($debug) {
                echo '<h1>Error</h1>';
                echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            } else {
                include BASE_PATH . '/app/Views/errors/500.php';
            }
            // Log error
            error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    public function getRouter(): Router
    {
        return $this->router;
    }
}
