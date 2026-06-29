<section class="py-5" style="margin-top: 76px;"><div class="container"><div class="row justify-content-center"><div class="col-lg-8">
<nav aria-label="breadcrumb"><ol class="breadcrumb small"><li class="breadcrumb-item"><a href="/blog">Blog</a></li><li class="breadcrumb-item active"><?= e($blog['title']) ?></li></ol></nav>
<h1 class="fw-bold font-playfair mb-3"><?= e($blog['title']) ?></h1>
<div class="d-flex gap-3 text-muted small mb-4"><span><i class="bi bi-person"></i> <?= e($blog['author_name'] ?? 'Admin') ?></span><span><i class="bi bi-calendar"></i> <?= formatDate($blog['published_at']) ?></span><span><i class="bi bi-eye"></i> <?= $blog['view_count'] ?> views</span></div>
<?php if ($blog['featured_image']): ?><img src="<?= e($blog['featured_image']) ?>" class="img-fluid rounded-3 mb-4 w-100" style="max-height:400px;object-fit:cover"><?php endif; ?>
<div class="blog-content"><?= $blog['content'] ?></div>
</div></div></div></section>
