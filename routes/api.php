<?php

declare(strict_types=1);

use App\Core\Router;

/** @var Router $router */
$router = $this->router;

$router->group(['prefix' => '/api/v1'], function ($router) {
    // Public API
    $router->get('/villas', [App\Controllers\Api\VillaApiController::class, 'index']);
    $router->get('/villas/{slug}', [App\Controllers\Api\VillaApiController::class, 'show']);
    $router->get('/availability/{villaId}', [App\Controllers\Api\BookingApiController::class, 'availability']);
    $router->post('/check-availability', [App\Controllers\Api\BookingApiController::class, 'checkAvailability']);
    $router->post('/validate-coupon', [App\Controllers\Api\BookingApiController::class, 'validateCoupon']);
    $router->get('/reviews/{villaId}', [App\Controllers\Api\ReviewApiController::class, 'index']);

    // Payment callbacks
    $router->post('/payment/callback/mpesa', [App\Controllers\Api\PaymentCallbackController::class, 'mpesa']);
    $router->post('/payment/callback/stripe', [App\Controllers\Api\PaymentCallbackController::class, 'stripe']);
    $router->post('/payment/callback/paypal', [App\Controllers\Api\PaymentCallbackController::class, 'paypal']);
    $router->post('/payment/callback/flutterwave', [App\Controllers\Api\PaymentCallbackController::class, 'flutterwave']);
    $router->post('/payment/callback/pesapal', [App\Controllers\Api\PaymentCallbackController::class, 'pesapal']);
});
