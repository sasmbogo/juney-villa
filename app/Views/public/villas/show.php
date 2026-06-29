<!-- Villa Hero -->
<section class="villa-hero" style="background-image: url('<?= e($villa['featured_image'] ?? 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=1920') ?>')">
    <div class="villa-hero-content">
        <div class="container">
            <nav aria-label="breadcrumb"><ol class="breadcrumb small"><li class="breadcrumb-item"><a href="/villas" class="text-gold">Villas</a></li><li class="breadcrumb-item active text-white"><?= e($villa['name']) ?></li></ol></nav>
            <h1 class="display-4 fw-bold font-playfair"><?= e($villa['name']) ?></h1>
            <p class="lead mb-0"><?= e($villa['tagline'] ?? '') ?></p>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Key Features -->
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <span class="amenity-badge"><i class="bi bi-door-open text-gold"></i> <?= $villa['bedrooms'] ?> Bedrooms</span>
                    <span class="amenity-badge"><i class="bi bi-droplet text-gold"></i> <?= $villa['bathrooms'] ?> Bathrooms</span>
                    <span class="amenity-badge"><i class="bi bi-people text-gold"></i> Up to <?= $villa['max_guests'] ?> Guests</span>
                    <?php if ($villa['size_sqm']): ?><span class="amenity-badge"><i class="bi bi-arrows-fullscreen text-gold"></i> <?= $villa['size_sqm'] ?> sqm</span><?php endif; ?>
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <h4 class="fw-bold font-playfair mb-3">About This Villa</h4>
                    <p class="text-muted"><?= nl2br(e($villa['description'])) ?></p>
                </div>

                <!-- Amenities -->
                <div class="mb-5">
                    <h4 class="fw-bold font-playfair mb-3">Amenities</h4>
                    <div class="row g-2">
                        <?php foreach ($villa['amenities'] as $amenity): ?>
                        <div class="col-md-4 col-6">
                            <span class="amenity-badge w-100 justify-content-start">
                                <i class="<?= e($amenity['icon'] ?? 'bi bi-check-circle') ?> text-gold"></i>
                                <?= e($amenity['name']) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Gallery -->
                <?php if (!empty($villa['images'])): ?>
                <div class="mb-5">
                    <h4 class="fw-bold font-playfair mb-3">Photo Gallery</h4>
                    <div class="row g-2">
                        <?php foreach (array_slice($villa['images'], 0, 6) as $image): ?>
                        <div class="col-md-4 col-6">
                            <img src="<?= e($image['image_path']) ?>" alt="<?= e($image['alt_text'] ?? $villa['name']) ?>" class="img-fluid rounded-3" style="height: 200px; width: 100%; object-fit: cover;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Rules -->
                <?php if ($villa['rules']): ?>
                <div class="mb-5">
                    <h4 class="fw-bold font-playfair mb-3">House Rules</h4>
                    <div class="bg-light rounded-3 p-4">
                        <p class="text-muted mb-0"><?= nl2br(e($villa['rules'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Cancellation Policy -->
                <?php if ($villa['cancellation_policy']): ?>
                <div class="mb-5">
                    <h4 class="fw-bold font-playfair mb-3">Cancellation Policy</h4>
                    <div class="bg-light rounded-3 p-4">
                        <p class="text-muted mb-0"><?= nl2br(e($villa['cancellation_policy'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews -->
                <?php if (!empty($villa['reviews'])): ?>
                <div class="mb-5">
                    <h4 class="fw-bold font-playfair mb-3">Guest Reviews</h4>
                    <?php foreach ($villa['reviews'] as $review): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong><?= e($review['guest_name']) ?></strong>
                                <div class="text-gold small"><?php for($i=0;$i<$review['rating'];$i++) echo '<i class="bi bi-star-fill"></i>'; ?></div>
                            </div>
                            <small class="text-muted"><?= formatDate($review['created_at']) ?></small>
                        </div>
                        <p class="text-muted mt-2 mb-0"><?= e($review['comment']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Location -->
                <div class="mb-5">
                    <h4 class="fw-bold font-playfair mb-3">Location</h4>
                    <p class="text-muted"><?= e($villa['address'] ?? 'Zanzibar, Tanzania') ?></p>
                    <?php if ($villa['latitude'] && $villa['longitude']): ?>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5000!2d<?= $villa['longitude'] ?>!3d<?= $villa['latitude'] ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKw!5e0!3m2!1sen!2stz!4v1" width="100%" height="300" style="border:0; border-radius: 12px;" loading="lazy"></iframe>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar - Booking Card -->
            <div class="col-lg-4">
                <div class="price-card">
                    <div class="text-center mb-3">
                        <span class="display-6 fw-bold text-gold">$<?= number_format((float)$villa['base_price']) ?></span>
                        <span class="text-muted">/ night</span>
                    </div>

                    <?php if ($villa['average_rating'] > 0): ?>
                    <div class="text-center mb-3">
                        <span class="text-gold"><?php for($i=0;$i<round($villa['average_rating']);$i++) echo '<i class="bi bi-star-fill"></i>'; ?></span>
                        <span class="small text-muted">(<?= $villa['average_rating'] ?>/5)</span>
                    </div>
                    <?php endif; ?>

                    <form action="/booking/create/<?= e($villa['slug']) ?>" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Check In</label>
                            <input type="text" name="check_in" class="form-control flatpickr-date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Check Out</label>
                            <input type="text" name="check_out" class="form-control flatpickr-date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Guests</label>
                            <select name="guests" class="form-select">
                                <?php for ($i = 1; $i <= $villa['max_guests']; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-gold w-100 py-2 mb-2">
                            <i class="bi bi-calendar-check me-2"></i>Book Now
                        </button>
                    </form>

                    <hr>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2"><span>Cleaning fee</span><span>$<?= number_format((float)$villa['cleaning_fee']) ?></span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Security deposit</span><span>$<?= number_format((float)$villa['security_deposit']) ?></span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Check-in</span><span><?= date('g:i A', strtotime($villa['check_in_time'])) ?></span></div>
                        <div class="d-flex justify-content-between"><span>Check-out</span><span><?= date('g:i A', strtotime($villa['check_out_time'])) ?></span></div>
                    </div>

                    <hr>
                    <div class="text-center">
                        <p class="small text-muted mb-2">Need help booking?</p>
                        <a href="https://wa.me/255777000000" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-whatsapp me-1"></i>Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
