<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="mb-0"><?= count($villas) ?> Villas</h6>
    <a href="/admin/villas/create" class="btn btn-gold btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Villa</a>
</div>

<div class="row g-4">
    <?php foreach ($villas as $v): ?>
    <div class="col-md-6 col-xl-4">
        <div class="admin-card h-100">
            <img src="<?= e($v['featured_image'] ?? 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400') ?>" alt="" class="w-100" style="height: 200px; object-fit: cover; border-radius: 12px 12px 0 0;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0"><?= e($v['name']) ?></h6>
                    <span class="badge <?= $v['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($v['status']) ?></span>
                </div>
                <p class="small text-muted mb-2"><?= $v['bedrooms'] ?> Beds • <?= $v['bathrooms'] ?> Baths • <?= $v['max_guests'] ?> Guests</p>
                <p class="fw-bold text-gold mb-3">$<?= number_format((float)$v['base_price']) ?>/night</p>
                <div class="d-flex gap-2">
                    <a href="/admin/villas/edit/<?= $v['id'] ?>" class="btn btn-sm btn-outline-dark flex-grow-1"><i class="bi bi-pencil"></i> Edit</a>
                    <form action="/admin/villas/delete/<?= $v['id'] ?>" method="POST" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm-delete><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
