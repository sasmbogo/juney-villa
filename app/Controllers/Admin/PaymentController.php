<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(): void
    {
        $paymentModel = new Payment();
        $payments = $paymentModel->rawQuery("SELECT p.*, b.booking_number, b.guest_name, pm.name as method_name FROM payments p JOIN bookings b ON p.booking_id = b.id LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id ORDER BY p.created_at DESC LIMIT 100");
        $data = ['pageTitle' => 'Payments', 'payments' => $payments];
        $this->renderWithLayout('admin.payments.index', $data, 'layouts.admin');
    }

    public function view(string $id): void
    {
        $paymentModel = new Payment();
        $payment = $paymentModel->find((int)$id);
        $data = ['pageTitle' => 'Payment Details', 'payment' => $payment];
        $this->renderWithLayout('admin.payments.view', $data, 'layouts.admin');
    }

    public function approve(string $id): void
    {
        $paymentModel = new Payment();
        $paymentModel->update((int)$id, ['status' => 'completed', 'paid_at' => date('Y-m-d H:i:s')]);
        $this->setFlash('success', 'Payment approved.');
        $this->redirect('/admin/payments');
    }
}
