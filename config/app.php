<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'Juney Villa Limited',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost/juney-villa',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'key' => $_ENV['APP_KEY'] ?? '',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Africa/Dar_es_Salaam',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'supported_locales' => ['en', 'sw', 'fr', 'de', 'it', 'ar'],
    'currency' => 'USD',
    'supported_currencies' => ['TZS', 'USD', 'EUR', 'GBP'],
    'version' => '1.0.0',
    'company' => [
        'name' => 'Juney Villa Limited',
        'email' => 'info@juneyvillaszanzibar.co.tz',
        'phone' => '+255 777 000 000',
        'address' => 'Zanzibar, Tanzania',
        'website' => 'https://juneyvillaszanzibar.co.tz',
    ],
];
