<section class="page-hero" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920') center/cover; min-height: 40vh; display:flex; align-items:center; padding-top: 80px;">
    <div class="container text-center text-white"><h1 class="display-4 fw-bold font-playfair">Blog & Travel Guide</h1></div>
</section>
<section class="py-5"><div class="container"><div class="row g-4">
<?php foreach ($blogs as $blog): ?>
<div class="col-md-4"><div class="card border-0 shadow-sm rounded-3 h-100"><img src="<?= e($blog['featured_image'] ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400') ?>" class="card-img-top" style="height:200px;object-fit:cover"><div class="card-body"><h5 class="fw-bold"><?= e($blog['title']) ?></h5><p class="text-muted small"><?= e(truncate($blog['excerpt'] ?? '', 100)) ?></p><a href="/blog/<?= e($blog['slug']) ?>" class="btn btn-sm btn-outline-dark">Read More</a></div></div></div>
<?php endforeach; ?>
<?php if (empty($blogs)): ?><div class="col-12 text-center py-5"><p class="text-muted">No posts yet</p></div><?php endif; ?>
</div></div></section>
