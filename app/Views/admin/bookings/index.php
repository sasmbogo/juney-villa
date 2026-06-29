<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="btn-group btn-group-sm">
            <a href="<?= url('/admin/bookings') ?>" class="btn <?= !$currentStatus ? 'btn-dark' : 'btn-outline-dark' ?>">All</a>
            <a href="<?= url('/admin/bookings?status=pending') ?>" class="btn <?= $currentStatus === 'pending' ? 'btn-dark' : 'btn-outline-dark' ?>">Pending</a>
            <a href="<?= url('/admin/bookings?status=confirmed') ?>" class="btn <?= $currentStatus === 'confirmed' ? 'btn-dark' : 'btn-outline-dark' ?>">Confirmed</a>
            <a href="<?= url('/admin/bookings?status=checked_in') ?>" class="btn <?= $currentStatus === 'checked_in' ? 'btn-dark' : 'btn-outline-dark' ?>">Checked In</a>
            <a href="<?= url('/admin/bookings?status=cancelled') ?>" class="btn <?= $currentStatus === 'cancelled' ? 'btn-dark' : 'btn-outline-dark' ?>">Cancelled</a>
        </div>
    </div>
    <div class="d-flex gap-2">
        <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Search..." style="width: 200px;">
        <a href="<?= url('/admin/bookings/create') ?>" class="btn btn-gold btn-sm"><i class="bi bi-plus-lg me-1"></i>New Booking</a>
    </div>
</div>

<div class="admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-admin mb-0">
                <thead>
                    <tr><th>Booking #</th><th>Guest</th><th>Villa</th><th>Check In</th><th>Check Out</th><th>Amount</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><a href="<?= url('/admin/bookings/view/<?= $b['id'] ?>') ?>" class="fw-semibold text-gold"><?= e($b['booking_number']) ?></a></td>
                        <td><?= e($b['guest_name']) ?></td>
                        <td><?= e($b['villa_name']) ?></td>
                        <td><?= formatDate($b['check_in']) ?></td>
                        <td><?= formatDate($b['check_out']) ?></td>
                        <td class="fw-semibold"><?= formatCurrency((float)$b['total_amount']) ?></td>
                        <td>
                            <?php $cls = match($b['status']){ 'pending'=>'badge-pending','confirmed'=>'badge-confirmed','checked_in'=>'badge-checked-in','cancelled'=>'badge-cancelled',default=>'badge-completed'}; ?>
                            <span class="badge <?= $cls ?>"><?= ucfirst(str_replace('_',' ',$b['status'])) ?></span>
                        </td>
                        <td>
                            <a href="<?= url('/admin/bookings/view/<?= $b['id'] ?>') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($bookings)): ?><tr><td colspan="8" class="text-center py-4 text-muted">No bookings found</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
