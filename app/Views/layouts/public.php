<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($metaDescription ?? 'Luxury Villa Rental in Zanzibar, Tanzania - Juney Villa Limited') ?>">
    <meta name="keywords" content="luxury villa, zanzibar, tanzania, rental, holiday, vacation, beach villa">
    <meta name="author" content="Juney Villa Limited">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= e($pageTitle ?? 'Juney Villa Limited') ?>">
    <meta property="og:description" content="<?= e($metaDescription ?? '') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($_ENV['APP_URL'] ?? '') ?>">
    <meta property="og:image" content="<?= base_url('images/og-image.jpg') ?>">
    
    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LodgingBusiness",
        "name": "Juney Villa Limited",
        "description": "Luxury Villa Rental in Zanzibar, Tanzania",
        "url": "https://juneyvillaszanzibar.co.tz",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Zanzibar",
            "addressCountry": "TZ"
        }
    }
    </script>

    <title><?= e($pageTitle ?? 'Juney Villa Limited - Luxury Villa Rental Zanzibar') ?></title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- AOS Animations -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="me-3"><i class="bi bi-telephone"></i> +255 777 000 000</span>
                    <span><i class="bi bi-envelope"></i> info@juneyvillaszanzibar.co.tz</span>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-inline-flex align-items-center gap-3">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                                <i class="bi bi-globe"></i> English
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?lang=en">English</a></li>
                                <li><a class="dropdown-item" href="?lang=sw">Kiswahili</a></li>
                                <li><a class="dropdown-item" href="?lang=fr">Français</a></li>
                                <li><a class="dropdown-item" href="?lang=de">Deutsch</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                                <i class="bi bi-currency-dollar"></i> USD
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?currency=USD">$ USD</a></li>
                                <li><a class="dropdown-item" href="?currency=TZS">TSh TZS</a></li>
                                <li><a class="dropdown-item" href="?currency=EUR">€ EUR</a></li>
                                <li><a class="dropdown-item" href="?currency=GBP">£ GBP</a></li>
                            </ul>
                        </div>
                        <a href="https://facebook.com/juneyvillaszanzibar" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="https://instagram.com/juneyvillaszanzibar" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="https://twitter.com/juneyvillas" target="_blank"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top main-nav" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="/">
                <span class="brand-text">JUNEY<span class="text-gold">VILLA</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/villas">Villas</a></li>
                    <li class="nav-item"><a class="nav-link" href="/booking">Book Now</a></li>
                    <li class="nav-item"><a class="nav-link" href="/gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= isAdmin() ? '/admin/dashboard' : '/guest/dashboard' ?>" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-person"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline-light btn-sm">Login</a>
                        <a href="/register" class="btn btn-gold btn-sm">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="offcanvas offcanvas-end" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">JUNEY<span class="text-gold">VILLA</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/villas">Villas</a></li>
                <li class="nav-item"><a class="nav-link" href="/booking">Book Now</a></li>
                <li class="nav-item"><a class="nav-link" href="/gallery">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h4 class="brand-text mb-3">JUNEY<span class="text-gold">VILLA</span></h4>
                    <p class="text-muted">Experience the ultimate luxury villa rental in Zanzibar, Tanzania. Five exclusive villas offering world-class amenities and unforgettable experiences.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-youtube"></i></a>
                        <a href="#"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="text-uppercase mb-3">Quick Links</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="/villas">Our Villas</a></li>
                        <li><a href="/booking">Book Now</a></li>
                        <li><a href="/gallery">Gallery</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/about">About Us</a></li>
                        <li><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="text-uppercase mb-3">Our Villas</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="/villas/villa-ocean-paradise">Villa Ocean Paradise</a></li>
                        <li><a href="/villas/villa-sunset">Villa Sunset</a></li>
                        <li><a href="/villas/villa-palm">Villa Palm</a></li>
                        <li><a href="/villas/villa-coral">Villa Coral</a></li>
                        <li><a href="/villas/villa-royal-zanzibar">Villa Royal Zanzibar</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="text-uppercase mb-3">Contact Info</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="bi bi-geo-alt text-gold me-2"></i>Zanzibar, Tanzania</li>
                        <li class="mb-2"><i class="bi bi-telephone text-gold me-2"></i>+255 777 000 000</li>
                        <li class="mb-2"><i class="bi bi-envelope text-gold me-2"></i>info@juneyvillaszanzibar.co.tz</li>
                        <li class="mb-2"><i class="bi bi-clock text-gold me-2"></i>24/7 Available</li>
                    </ul>
                    <!-- Newsletter -->
                    <form id="newsletterForm" class="mt-3">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="Your email" required>
                            <button class="btn btn-gold btn-sm" type="submit"><i class="bi bi-send"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0 small">&copy; <?= date('Y') ?> Juney Villa Limited. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted small me-3">Privacy Policy</a>
                    <a href="#" class="text-muted small me-3">Terms of Service</a>
                    <a href="#" class="text-muted small">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/255777000000" target="_blank" class="whatsapp-float" title="Chat with us on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- Back to Top -->
    <button id="backToTop" class="btn btn-gold btn-back-to-top" title="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('js/main.js') ?>"></script>
</body>
</html>
