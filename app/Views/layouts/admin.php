<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= e($pageTitle ?? 'Dashboard') ?> - Juney Villa Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= base_url('css/admin.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="<?= url('/admin/dashboard') ?>" class="sidebar-brand">
                    <span class="brand-text">JUNEY<span class="text-gold">VILLA</span></span>
                </a>
                <button class="btn btn-sm sidebar-toggle d-lg-none" id="closeSidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/dashboard') ? 'active' : '' ?>" href="<?= url('/admin/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/bookings') ? 'active' : '' ?>" href="<?= url('/admin/bookings') ?>">
                            <i class="bi bi-calendar-check"></i><span>Bookings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/villas') ? 'active' : '' ?>" href="<?= url('/admin/villas') ?>">
                            <i class="bi bi-house-heart"></i><span>Villas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/payments') ? 'active' : '' ?>" href="<?= url('/admin/payments') ?>">
                            <i class="bi bi-credit-card"></i><span>Payments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/users') ? 'active' : '' ?>" href="<?= url('/admin/users') ?>">
                            <i class="bi bi-people"></i><span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/reviews') ? 'active' : '' ?>" href="<?= url('/admin/reviews') ?>">
                            <i class="bi bi-star"></i><span>Reviews</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/housekeeping') ? 'active' : '' ?>" href="<?= url('/admin/housekeeping') ?>">
                            <i class="bi bi-house-check"></i><span>Housekeeping</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/maintenance') ? 'active' : '' ?>" href="<?= url('/admin/maintenance') ?>">
                            <i class="bi bi-tools"></i><span>Maintenance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/blog') ? 'active' : '' ?>" href="<?= url('/admin/blog') ?>">
                            <i class="bi bi-journal-text"></i><span>Blog</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/gallery') ? 'active' : '' ?>" href="<?= url('/admin/gallery') ?>">
                            <i class="bi bi-images"></i><span>Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/coupons') ? 'active' : '' ?>" href="<?= url('/admin/coupons') ?>">
                            <i class="bi bi-ticket-perforated"></i><span>Coupons</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/reports') ? 'active' : '' ?>" href="<?= url('/admin/reports') ?>">
                            <i class="bi bi-graph-up"></i><span>Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/settings') ? 'active' : '' ?>" href="<?= url('/admin/settings') ?>">
                            <i class="bi bi-gear"></i><span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="d-flex align-items-center gap-2 px-3 py-2">
                    <div class="avatar-sm bg-gold text-white rounded-circle d-flex align-items-center justify-content-center">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="flex-grow-1">
                        <small class="d-block fw-semibold"><?= e($_SESSION['user_name'] ?? 'Admin') ?></small>
                        <small class="text-muted"><?= e($_SESSION['user_role'] ?? '') ?></small>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Navbar -->
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm d-lg-none" id="openSidebar">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <h5 class="mb-0 fw-semibold"><?= e($pageTitle ?? 'Dashboard') ?></h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm" id="themeToggle" title="Toggle theme">
                        <i class="bi bi-moon"></i>
                    </button>
                    <a href="<?= url('/admin/notifications') ?>" class="btn btn-sm position-relative">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem">3</span>
                    </a>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline"><?= e($_SESSION['user_name'] ?? 'Admin') ?></span>
                            <i class="bi bi-chevron-down small"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= url('/guest/profile') ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="<?= url('/admin/settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="admin-content">
                <?php if ($flash = flash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i><?= e($flash) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($flash = flash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle me-2"></i><?= e($flash) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('js/admin.js') ?>"></script>
</body>
</html>
