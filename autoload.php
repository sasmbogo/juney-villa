<?php
/**
 * PSR-4 Autoloader
 * This file provides autoloading if Composer is not available.
 * For production, run `composer install` instead.
 */

spl_autoload_register(function (string $class): void {
    $prefixes = [
        'App\\' => __DIR__ . '/app/',
        'Config\\' => __DIR__ . '/config/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Load helper functions
require_once __DIR__ . '/app/Helpers/helpers.php';
