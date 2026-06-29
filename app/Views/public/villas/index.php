<!-- Page Header -->
<section class="page-hero" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=1920') center/cover; min-height: 40vh; display:flex; align-items:center; padding-top: 80px;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold font-playfair">Our Luxury Villas</h1>
        <p class="lead">Five exclusive properties, each offering a unique Zanzibar experience</p>
    </div>
</section>

<!-- Villas Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($villas as $villa): ?>
            <div class="col-lg-6" data-aos="fade-up">
                <div class="villa-card h-100">
                    <div class="row g-0 h-100">
                        <div class="col-md-6">
                            <div class="villa-card-img h-100" style="min-height: 280px;">
                                <img src="<?= e($villa['featured_image'] ?? 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=600') ?>" alt="<?= e($villa['name']) ?>">
                                <span class="villa-badge">From $<?= number_format((float)$villa['base_price']) ?>/night</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 d-flex flex-column h-100">
                                <h4 class="fw-bold font-playfair mb-2"><?= e($villa['name']) ?></h4>
                                <p class="text-muted small flex-grow-1"><?= e(truncate($villa['short_description'] ?? '', 150)) ?></p>
                                <div class="villa-card-features mb-3">
                                    <span><i class="bi bi-door-open"></i> <?= $villa['bedrooms'] ?> Beds</span>
                                    <span><i class="bi bi-droplet"></i> <?= $villa['bathrooms'] ?> Baths</span>
                                    <span><i class="bi bi-people"></i> <?= $villa['max_guests'] ?> Guests</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="<?= url('/villas/' . e($villa['slug'])) ?>" class="btn btn-gold btn-sm flex-grow-1">View Details</a>
                                    <a href="<?= url('/booking/create/' . e($villa['slug'])) ?>" class="btn btn-outline-dark btn-sm">Book</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
