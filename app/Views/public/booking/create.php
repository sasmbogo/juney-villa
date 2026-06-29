<!-- Booking Header -->
<section class="py-4 bg-light" style="margin-top: 76px;">
    <div class="container">
        <h2 class="fw-bold font-playfair mb-0">Book <?= e($villa['name']) ?></h2>
        <p class="text-muted">Complete your reservation details below</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <!-- Booking Steps -->
        <div class="booking-steps mb-5">
            <div class="booking-step active"><span class="step-number">1</span> Details</div>
            <div class="booking-step"><span class="step-number">2</span> Extras</div>
            <div class="booking-step"><span class="step-number">3</span> Payment</div>
            <div class="booking-step"><span class="step-number">4</span> Confirm</div>
        </div>

        <form action="/booking/store" method="POST" id="bookingForm">
            <?= csrf_field() ?>
            <input type="hidden" name="villa_id" value="<?= $villa['id'] ?>">
            <input type="hidden" name="check_in" value="<?= e($checkIn) ?>">
            <input type="hidden" name="check_out" value="<?= e($checkOut) ?>">

            <div class="row g-4">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <!-- Guest Information -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold"><i class="bi bi-person text-gold me-2"></i>Guest Information</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="guest_name" class="form-control" value="<?= e($_SESSION['user_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="guest_email" class="form-control" value="<?= e($_SESSION['user_email'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="guest_phone" class="form-control" placeholder="+255...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="guest_country" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Adults *</label>
                                    <select name="adults" class="form-select" required>
                                        <?php for ($i = 1; $i <= $villa['max_guests']; $i++): ?>
                                        <option value="<?= $i ?>" <?= $i == $guests ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Children</label>
                                    <select name="children" class="form-select">
                                        <?php for ($i = 0; $i <= 5; $i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Infants</label>
                                    <select name="infants" class="form-select">
                                        <?php for ($i = 0; $i <= 3; $i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Extras -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold"><i class="bi bi-plus-circle text-gold me-2"></i>Add-on Services</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="row g-3">
                                <?php foreach ($extras as $extra): ?>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 d-flex align-items-center gap-3">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 small fw-semibold"><?= e($extra['name']) ?></h6>
                                            <small class="text-muted">$<?= number_format((float)$extra['price'], 2) ?> / <?= str_replace('_', ' ', $extra['price_type']) ?></small>
                                        </div>
                                        <select name="extras[<?= $extra['id'] ?>]" class="form-select form-select-sm" style="width: 70px;">
                                            <option value="0">0</option>
                                            <?php for ($i = 1; $i <= 10; $i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Special Requests -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold"><i class="bi bi-chat-dots text-gold me-2"></i>Special Requests</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <textarea name="special_requests" class="form-control" rows="4" placeholder="Any special requests or notes for your stay..."></textarea>
                        </div>
                    </div>

                    <!-- Promo Code -->
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="input-group">
                                <input type="text" name="promo_code" class="form-control" placeholder="Enter promo code">
                                <button type="button" class="btn btn-outline-secondary" id="applyPromo">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary Sidebar -->
                <div class="col-lg-4">
                    <div class="price-card">
                        <h5 class="fw-bold mb-3">Booking Summary</h5>
                        
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <img src="<?= e($villa['featured_image'] ?? '') ?>" alt="" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0 small fw-semibold"><?= e($villa['name']) ?></h6>
                                <small class="text-muted"><?= e($villa['address'] ?? 'Zanzibar') ?></small>
                            </div>
                        </div>

                        <div class="mb-3 small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Check-in</span>
                                <span class="fw-semibold"><?= formatDate($checkIn) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Check-out</span>
                                <span class="fw-semibold"><?= formatDate($checkOut) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Nights</span>
                                <span class="fw-semibold"><?= $nights ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Guests</span>
                                <span class="fw-semibold"><?= $guests ?></span>
                            </div>
                        </div>

                        <hr>

                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span>$<?= number_format($basePrice / max($nights, 1), 2) ?> × <?= $nights ?> nights</span>
                                <span>$<?= number_format($basePrice, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Cleaning fee</span>
                                <span>$<?= number_format((float)$villa['cleaning_fee'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (18% VAT)</span>
                                <span>$<?= number_format(($basePrice + (float)$villa['cleaning_fee']) * 0.18, 2) ?></span>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-gold fs-5">$<?= number_format(($basePrice + (float)$villa['cleaning_fee']) * 1.18, 2) ?></span>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 py-3 mt-4 fw-semibold">
                            <i class="bi bi-lock me-2"></i>Confirm Booking
                        </button>

                        <p class="text-center text-muted small mt-3 mb-0">
                            <i class="bi bi-shield-check me-1"></i>Your booking is secure and encrypted
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
