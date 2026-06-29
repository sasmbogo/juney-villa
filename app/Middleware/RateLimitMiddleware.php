<?php

declare(strict_types=1);

namespace App\Middleware;

class RateLimitMiddleware
{
    private int $maxAttempts = 60;
    private int $decayMinutes = 1;

    public function handle(): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $key = 'rate_limit_' . md5($ip);
        $cacheFile = STORAGE_PATH . '/cache/' . $key . '.json';

        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            $elapsed = time() - $data['timestamp'];

            if ($elapsed < ($this->decayMinutes * 60)) {
                if ($data['attempts'] >= $this->maxAttempts) {
                    http_response_code(429);
                    header('Retry-After: ' . (($this->decayMinutes * 60) - $elapsed));
                    echo json_encode(['error' => 'Too many requests. Please try again later.']);
                    exit;
                }
                $data['attempts']++;
            } else {
                $data = ['attempts' => 1, 'timestamp' => time()];
            }
        } else {
            $data = ['attempts' => 1, 'timestamp' => time()];
        }

        file_put_contents($cacheFile, json_encode($data));
        return true;
    }
}
