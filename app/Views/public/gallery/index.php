<section class="page-hero" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1920') center/cover; min-height: 40vh; display:flex; align-items:center; padding-top: 80px;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold font-playfair">Gallery</h1>
        <p class="lead">Explore our stunning properties</p>
    </div>
</section>
<section class="py-5"><div class="container"><div class="row g-3" id="galleryGrid">
<?php foreach ($items as $item): ?>
<div class="col-md-4 col-6"><img src="<?= e($item['file_path'] ?? 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400') ?>" alt="<?= e($item['title'] ?? '') ?>" class="img-fluid rounded-3 w-100" style="height: 250px; object-fit: cover;"></div>
<?php endforeach; ?>
<?php if (empty($items)): ?><div class="col-12 text-center py-5"><p class="text-muted">Gallery coming soon</p></div><?php endif; ?>
</div></div></section>
