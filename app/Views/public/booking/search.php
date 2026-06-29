<section class="py-5" style="margin-top: 76px;"><div class="container">
<h2 class="fw-bold font-playfair mb-2">Available Villas</h2>
<p class="text-muted mb-4"><?php if ($checkIn && $checkOut): ?>Showing availability for <?= formatDate($checkIn) ?> to <?= formatDate($checkOut) ?> (<?= $guests ?> guests)<?php else: ?>All villas<?php endif; ?></p>
<div class="row g-4">
<?php foreach ($villas as $villa): ?>
<div class="col-md-6 col-lg-4"><div class="villa-card">
<div class="villa-card-img"><img src="<?= e($villa['featured_image'] ?? 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400') ?>" alt="<?= e($villa['name']) ?>"><span class="villa-badge">$<?= number_format($villa['calculated_price'] ?? (float)$villa['base_price']) ?><?= $checkIn ? ' total' : '/night' ?></span></div>
<div class="villa-card-body"><h5 class="fw-bold"><?= e($villa['name']) ?></h5><div class="villa-card-features mb-3"><span><i class="bi bi-door-open"></i> <?= $villa['bedrooms'] ?></span><span><i class="bi bi-people"></i> <?= $villa['max_guests'] ?></span></div>
<a href="<?= url('/booking/create/' . e($villa['slug']) . '?check_in=' . e($checkIn ?? '') . '&check_out=' . e($checkOut ?? '') . '&guests=' . $guests) ?>" class="btn btn-gold w-100">Book Now</a></div>
</div></div>
<?php endforeach; ?>
<?php if (empty($villas)): ?><div class="col-12 text-center py-5"><p class="text-muted">No villas available for your selected criteria.</p><a href="<?= url('/booking') ?>" class="btn btn-outline-dark">Try Different Dates</a></div><?php endif; ?>
</div></div></section>
