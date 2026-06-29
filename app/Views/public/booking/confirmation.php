<section class="py-5" style="margin-top: 76px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width:80px;height:80px">
                            <i class="bi bi-check-circle-fill text-success display-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold font-playfair">Booking Confirmed!</h2>
                    <p class="text-muted">Your booking has been received and is pending confirmation.</p>
                    <p class="fw-semibold">Booking Number: <span class="text-gold fs-5"><?= e($booking['booking_number']) ?></span></p>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Booking Details</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><small class="text-muted">Villa</small><p class="fw-semibold mb-0"><?= e($villa['name']) ?></p></div>
                            <div class="col-md-6"><small class="text-muted">Guest</small><p class="fw-semibold mb-0"><?= e($booking['guest_name']) ?></p></div>
                            <div class="col-md-3"><small class="text-muted">Check-in</small><p class="fw-semibold mb-0"><?= formatDate($booking['check_in']) ?></p></div>
                            <div class="col-md-3"><small class="text-muted">Check-out</small><p class="fw-semibold mb-0"><?= formatDate($booking['check_out']) ?></p></div>
                            <div class="col-md-3"><small class="text-muted">Nights</small><p class="fw-semibold mb-0"><?= $booking['nights'] ?></p></div>
                            <div class="col-md-3"><small class="text-muted">Guests</small><p class="fw-semibold mb-0"><?= $booking['adults'] ?> Adults<?= $booking['children'] ? ', ' . $booking['children'] . ' Children' : '' ?></p></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Amount</small>
                                <h4 class="text-gold fw-bold mb-0"><?= formatCurrency((float)$booking['total_amount']) ?></h4>
                            </div>
                            <span class="badge bg-warning text-dark px-3 py-2">Pending Payment</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">A confirmation email has been sent to <strong><?= e($booking['guest_email']) ?></strong></p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/booking/invoice/<?= e($booking['booking_number']) ?>" class="btn btn-outline-dark">
                            <i class="bi bi-download me-1"></i>Download Invoice
                        </a>
                        <a href="/" class="btn btn-gold">
                            <i class="bi bi-house me-1"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
