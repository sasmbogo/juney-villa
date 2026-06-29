<?php

declare(strict_types=1);

use App\Core\Router;

/** @var Router $router */
$router = $this->router;

// Public Pages
$router->get('/', [App\Controllers\HomeController::class, 'index']);
$router->get('/about', [App\Controllers\HomeController::class, 'about']);
$router->get('/contact', [App\Controllers\HomeController::class, 'contact']);
$router->post('/contact', [App\Controllers\HomeController::class, 'sendContact']);
$router->get('/gallery', [App\Controllers\GalleryController::class, 'index']);

// Villas
$router->get('/villas', [App\Controllers\VillaController::class, 'index']);
$router->get('/villas/{slug}', [App\Controllers\VillaController::class, 'show']);

// Booking
$router->get('/booking', [App\Controllers\BookingController::class, 'index']);
$router->get('/booking/search', [App\Controllers\BookingController::class, 'search']);
$router->post('/booking/check-availability', [App\Controllers\BookingController::class, 'checkAvailability']);
$router->get('/booking/create/{slug}', [App\Controllers\BookingController::class, 'create']);
$router->post('/booking/store', [App\Controllers\BookingController::class, 'store']);
$router->get('/booking/confirmation/{number}', [App\Controllers\BookingController::class, 'confirmation']);
$router->get('/booking/invoice/{number}', [App\Controllers\BookingController::class, 'invoice']);

// Blog
$router->get('/blog', [App\Controllers\BlogController::class, 'index']);
$router->get('/blog/{slug}', [App\Controllers\BlogController::class, 'show']);

// Newsletter
$router->post('/newsletter/subscribe', [App\Controllers\HomeController::class, 'subscribe']);

// Authentication
$router->get('/login', [App\Controllers\Auth\AuthController::class, 'loginForm']);
$router->post('/login', [App\Controllers\Auth\AuthController::class, 'login']);
$router->get('/register', [App\Controllers\Auth\AuthController::class, 'registerForm']);
$router->post('/register', [App\Controllers\Auth\AuthController::class, 'register']);
$router->get('/logout', [App\Controllers\Auth\AuthController::class, 'logout']);
$router->get('/forgot-password', [App\Controllers\Auth\AuthController::class, 'forgotPasswordForm']);
$router->post('/forgot-password', [App\Controllers\Auth\AuthController::class, 'forgotPassword']);
$router->get('/reset-password/{token}', [App\Controllers\Auth\AuthController::class, 'resetPasswordForm']);
$router->post('/reset-password', [App\Controllers\Auth\AuthController::class, 'resetPassword']);
$router->get('/verify-email/{token}', [App\Controllers\Auth\AuthController::class, 'verifyEmail']);

// Guest Dashboard
$router->group(['prefix' => '/guest', 'middleware' => [App\Middleware\AuthMiddleware::class]], function ($router) {
    $router->get('/dashboard', [App\Controllers\Guest\DashboardController::class, 'index']);
    $router->get('/bookings', [App\Controllers\Guest\DashboardController::class, 'bookings']);
    $router->get('/profile', [App\Controllers\Guest\DashboardController::class, 'profile']);
    $router->post('/profile', [App\Controllers\Guest\DashboardController::class, 'updateProfile']);
    $router->get('/wishlist', [App\Controllers\Guest\DashboardController::class, 'wishlist']);
    $router->get('/reviews', [App\Controllers\Guest\DashboardController::class, 'reviews']);
    $router->get('/notifications', [App\Controllers\Guest\DashboardController::class, 'notifications']);
});
