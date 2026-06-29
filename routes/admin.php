<?php

declare(strict_types=1);

use App\Core\Router;

/** @var Router $router */
$router = $this->router;

$router->group(['prefix' => '/admin', 'middleware' => [App\Middleware\AdminMiddleware::class]], function ($router) {
    // Dashboard
    $router->get('/dashboard', [App\Controllers\Admin\DashboardController::class, 'index']);

    // Villa Management
    $router->get('/villas', [App\Controllers\Admin\VillaManageController::class, 'index']);
    $router->get('/villas/create', [App\Controllers\Admin\VillaManageController::class, 'create']);
    $router->post('/villas/store', [App\Controllers\Admin\VillaManageController::class, 'store']);
    $router->get('/villas/edit/{id}', [App\Controllers\Admin\VillaManageController::class, 'edit']);
    $router->post('/villas/update/{id}', [App\Controllers\Admin\VillaManageController::class, 'update']);
    $router->post('/villas/delete/{id}', [App\Controllers\Admin\VillaManageController::class, 'delete']);

    // Booking Management
    $router->get('/bookings', [App\Controllers\Admin\BookingManageController::class, 'index']);
    $router->get('/bookings/create', [App\Controllers\Admin\BookingManageController::class, 'create']);
    $router->post('/bookings/store', [App\Controllers\Admin\BookingManageController::class, 'store']);
    $router->get('/bookings/view/{id}', [App\Controllers\Admin\BookingManageController::class, 'view']);
    $router->post('/bookings/approve/{id}', [App\Controllers\Admin\BookingManageController::class, 'approve']);
    $router->post('/bookings/cancel/{id}', [App\Controllers\Admin\BookingManageController::class, 'cancel']);
    $router->post('/bookings/checkin/{id}', [App\Controllers\Admin\BookingManageController::class, 'checkin']);
    $router->post('/bookings/checkout/{id}', [App\Controllers\Admin\BookingManageController::class, 'checkout']);

    // Payments
    $router->get('/payments', [App\Controllers\Admin\PaymentController::class, 'index']);
    $router->get('/payments/view/{id}', [App\Controllers\Admin\PaymentController::class, 'view']);
    $router->post('/payments/approve/{id}', [App\Controllers\Admin\PaymentController::class, 'approve']);

    // Users
    $router->get('/users', [App\Controllers\Admin\UserController::class, 'index']);
    $router->get('/users/create', [App\Controllers\Admin\UserController::class, 'create']);
    $router->post('/users/store', [App\Controllers\Admin\UserController::class, 'store']);
    $router->get('/users/edit/{id}', [App\Controllers\Admin\UserController::class, 'edit']);
    $router->post('/users/update/{id}', [App\Controllers\Admin\UserController::class, 'update']);
    $router->post('/users/delete/{id}', [App\Controllers\Admin\UserController::class, 'delete']);

    // Reviews
    $router->get('/reviews', [App\Controllers\Admin\ReviewController::class, 'index']);
    $router->post('/reviews/approve/{id}', [App\Controllers\Admin\ReviewController::class, 'approve']);
    $router->post('/reviews/reply/{id}', [App\Controllers\Admin\ReviewController::class, 'reply']);

    // Housekeeping
    $router->get('/housekeeping', [App\Controllers\Admin\HousekeepingController::class, 'index']);
    $router->post('/housekeeping/store', [App\Controllers\Admin\HousekeepingController::class, 'store']);
    $router->post('/housekeeping/update/{id}', [App\Controllers\Admin\HousekeepingController::class, 'update']);

    // Maintenance
    $router->get('/maintenance', [App\Controllers\Admin\MaintenanceController::class, 'index']);
    $router->post('/maintenance/store', [App\Controllers\Admin\MaintenanceController::class, 'store']);
    $router->post('/maintenance/update/{id}', [App\Controllers\Admin\MaintenanceController::class, 'update']);

    // Blog
    $router->get('/blog', [App\Controllers\Admin\BlogManageController::class, 'index']);
    $router->get('/blog/create', [App\Controllers\Admin\BlogManageController::class, 'create']);
    $router->post('/blog/store', [App\Controllers\Admin\BlogManageController::class, 'store']);
    $router->get('/blog/edit/{id}', [App\Controllers\Admin\BlogManageController::class, 'edit']);
    $router->post('/blog/update/{id}', [App\Controllers\Admin\BlogManageController::class, 'update']);
    $router->post('/blog/delete/{id}', [App\Controllers\Admin\BlogManageController::class, 'delete']);

    // Gallery
    $router->get('/gallery', [App\Controllers\Admin\GalleryManageController::class, 'index']);
    $router->post('/gallery/upload', [App\Controllers\Admin\GalleryManageController::class, 'upload']);
    $router->post('/gallery/delete/{id}', [App\Controllers\Admin\GalleryManageController::class, 'delete']);

    // Reports
    $router->get('/reports', [App\Controllers\Admin\ReportController::class, 'index']);
    $router->get('/reports/revenue', [App\Controllers\Admin\ReportController::class, 'revenue']);
    $router->get('/reports/occupancy', [App\Controllers\Admin\ReportController::class, 'occupancy']);
    $router->get('/reports/bookings', [App\Controllers\Admin\ReportController::class, 'bookings']);

    // Settings
    $router->get('/settings', [App\Controllers\Admin\SettingsController::class, 'index']);
    $router->post('/settings/update', [App\Controllers\Admin\SettingsController::class, 'update']);
    $router->get('/settings/company', [App\Controllers\Admin\SettingsController::class, 'company']);
    $router->post('/settings/company/update', [App\Controllers\Admin\SettingsController::class, 'updateCompany']);

    // Coupons
    $router->get('/coupons', [App\Controllers\Admin\CouponController::class, 'index']);
    $router->post('/coupons/store', [App\Controllers\Admin\CouponController::class, 'store']);
    $router->post('/coupons/update/{id}', [App\Controllers\Admin\CouponController::class, 'update']);
    $router->post('/coupons/delete/{id}', [App\Controllers\Admin\CouponController::class, 'delete']);

    // Notifications
    $router->get('/notifications', [App\Controllers\Admin\NotificationController::class, 'index']);

    // Activity Logs
    $router->get('/activity-logs', [App\Controllers\Admin\ActivityLogController::class, 'index']);
});
