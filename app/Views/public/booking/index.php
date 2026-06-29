<section class="page-hero" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920') center/cover; min-height: 40vh; display:flex; align-items:center; padding-top: 80px;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold font-playfair">Book Your Villa</h1>
        <p class="lead">Search availability and reserve your luxury experience</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <!-- Search Form -->
        <div class="card border-0 shadow rounded-3 p-4 mb-5" style="margin-top: -60px; position: relative; z-index: 10;">
            <form action="/booking/search" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Check In</label>
                    <input type="text" name="check_in" class="form-control flatpickr-date" placeholder="Select date" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Check Out</label>
                    <input type="text" name="check_out" class="form-control flatpickr-date" placeholder="Select date" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Guests</label>
                    <select name="guests" class="form-select">
                        <option value="2">2 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="6">6 Guests</option>
                        <option value="8">8 Guests</option>
                        <option value="10">10+ Guests</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Promo Code</label>
                    <input type="text" name="promo_code" class="form-control" placeholder="Optional">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-gold w-100 py-2"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>
        </div>

        <!-- Available Villas -->
        <h3 class="fw-bold font-playfair mb-4">Our Villas</h3>
        <div class="row g-4">
            <?php foreach ($villas as $villa): ?>
            <div class="col-md-6 col-lg-4">
                <div class="villa-card">
                    <div class="villa-card-img">
                        <img src="<?= e($villa['featured_image'] ?? 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400') ?>" alt="<?= e($villa['name']) ?>">
                        <span class="villa-badge">$<?= number_format((float)$villa['base_price']) ?>/night</span>
                    </div>
                    <div class="villa-card-body">
                        <h5 class="fw-bold"><?= e($villa['name']) ?></h5>
                        <div class="villa-card-features mb-3">
                            <span><i class="bi bi-door-open"></i> <?= $villa['bedrooms'] ?></span>
                            <span><i class="bi bi-droplet"></i> <?= $villa['bathrooms'] ?></span>
                            <span><i class="bi bi-people"></i> <?= $villa['max_guests'] ?></span>
                        </div>
                        <a href="/booking/create/<?= e($villa['slug']) ?>" class="btn btn-gold w-100">Book Now</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
