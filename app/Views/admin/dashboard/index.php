<!-- Dashboard Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p>Today's Bookings</p>
                    <h3><?= $stats['today_checkins'] ?? 0 ?></h3>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p>Monthly Revenue</p>
                    <h3><?= formatCurrency($stats['monthly_revenue'] ?? 0) ?></h3>
                </div>
                <div class="stat-icon" style="background:rgba(200,164,92,0.1);color:#C8A45C"><i class="bi bi-currency-dollar"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p>Pending Bookings</p>
                    <h3><?= $stats['pending_bookings'] ?? 0 ?></h3>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p>Total Guests</p>
                    <h3><?= $stats['total_guests'] ?? 0 ?></h3>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <h6>Revenue Overview <?= date('Y') ?></h6>
                <a href="<?= url('/admin/reports/revenue') ?>" class="btn btn-sm btn-outline-secondary">View Report</a>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart" data-revenue='<?= json_encode($monthlyRevenue ?? []) ?>'></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="card-header">
                <h6>Occupancy Rate</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="occupancyChart" data-occupancy='<?= json_encode(["labels" => array_column($occupancy ?? [], "name"), "values" => array_column($occupancy ?? [], "rate")]) ?>'></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings & Quick Actions -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <h6>Recent Bookings</h6>
                <a href="<?= url('/admin/bookings') ?>" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-admin mb-0">
                        <thead>
                            <tr>
                                <th>Booking #</th>
                                <th>Guest</th>
                                <th>Villa</th>
                                <th>Check In</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recentBookings ?? [], 0, 8) as $booking): ?>
                            <tr>
                                <td><a href="<?= url('/admin/bookings/view/<?= $booking['id'] ?>') ?>" class="fw-semibold"><?= e($booking['booking_number']) ?></a></td>
                                <td><?= e($booking['guest_name']) ?></td>
                                <td><?= e($booking['villa_name']) ?></td>
                                <td><?= formatDate($booking['check_in']) ?></td>
                                <td><?= formatCurrency((float)$booking['total_amount']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match($booking['status']) {
                                        'pending' => 'badge-pending',
                                        'confirmed' => 'badge-confirmed',
                                        'checked_in' => 'badge-checked-in',
                                        'cancelled' => 'badge-cancelled',
                                        default => 'badge-completed'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst(str_replace('_', ' ', $booking['status'])) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentBookings)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No bookings yet</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="card-header"><h6>Quick Actions</h6></div>
            <div class="card-body">
                <div class="d-flex flex-column gap-2">
                    <a href="<?= url('/admin/bookings/create') ?>" class="quick-action"><i class="bi bi-plus-circle"></i><span>New Booking</span></a>
                    <a href="<?= url('/admin/villas/create') ?>" class="quick-action"><i class="bi bi-house-add"></i><span>Add Villa</span></a>
                    <a href="<?= url('/admin/blog/create') ?>" class="quick-action"><i class="bi bi-pencil-square"></i><span>New Blog Post</span></a>
                    <a href="<?= url('/admin/reports') ?>" class="quick-action"><i class="bi bi-file-earmark-bar-graph"></i><span>Generate Report</span></a>
                    <a href="<?= url('/admin/settings') ?>" class="quick-action"><i class="bi bi-gear"></i><span>System Settings</span></a>
                </div>
            </div>
        </div>

        <!-- Today's Activity -->
        <div class="admin-card">
            <div class="card-header"><h6>Today's Activity</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success" style="width:36px;height:36px;font-size:0.9rem"><i class="bi bi-arrow-down-left"></i></div>
                    <div><small class="fw-semibold">Check-ins</small><br><small class="text-muted"><?= count($todayCheckins ?? []) ?> guests arriving</small></div>
                </div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning" style="width:36px;height:36px;font-size:0.9rem"><i class="bi bi-arrow-up-right"></i></div>
                    <div><small class="fw-semibold">Check-outs</small><br><small class="text-muted"><?= count($todayCheckouts ?? []) ?> guests departing</small></div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info" style="width:36px;height:36px;font-size:0.9rem"><i class="bi bi-house-heart"></i></div>
                    <div><small class="fw-semibold">Active Villas</small><br><small class="text-muted"><?= $stats['total_villas'] ?? 5 ?> properties</small></div>
                </div>
            </div>
        </div>
    </div>
</div>
