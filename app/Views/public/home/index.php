<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-slider">
        <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=1920')"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1920')"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1920')"></div>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold font-playfair mb-3" data-aos="fade-up">Luxury Villas in<br><span class="text-gold">Zanzibar Paradise</span></h1>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Experience the finest villa rental in Tanzania's most beautiful island</p>
            
            <!-- Booking Search Form -->
            <div class="booking-search-card" data-aos="fade-up" data-aos-delay="200">
                <form action="/booking/search" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-start d-block">Check In</label>
                        <input type="text" name="check_in" class="form-control flatpickr-date" placeholder="Select date" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-start d-block">Check Out</label>
                        <input type="text" name="check_out" class="form-control flatpickr-date" placeholder="Select date" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-start d-block">Guests</label>
                        <select name="guests" class="form-select">
                            <option value="2">2 Guests</option>
                            <option value="4">4 Guests</option>
                            <option value="6">6 Guests</option>
                            <option value="8">8 Guests</option>
                            <option value="10">10+ Guests</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-start d-block">Promo Code</label>
                        <input type="text" name="promo_code" class="form-control" placeholder="Code">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-gold w-100 py-2">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <a href="#about-section"><i class="bi bi-chevron-double-down"></i></a>
    </div>
</section>

<!-- About Section -->
<section id="about-section" class="py-5 bg-light">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="text-gold text-uppercase fw-semibold small letter-spacing-2">Welcome to Paradise</span>
                <h2 class="display-5 fw-bold font-playfair mt-2 mb-4">Discover the Magic of Zanzibar</h2>
                <p class="text-muted lead">Juney Villa Limited offers an exclusive collection of five luxury villas nestled along Zanzibar's most pristine coastlines. Each property is a masterpiece of design, combining traditional Swahili architecture with modern luxury.</p>
                <p class="text-muted">From private infinity pools overlooking the Indian Ocean to personal chef services and dedicated butlers, every detail is crafted to exceed your expectations.</p>
                <div class="row g-3 mt-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon bg-gold-soft"><i class="bi bi-house-heart text-gold"></i></div>
                            <div><h4 class="mb-0">5</h4><small class="text-muted">Luxury Villas</small></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon bg-gold-soft"><i class="bi bi-star text-gold"></i></div>
                            <div><h4 class="mb-0">4.9</h4><small class="text-muted">Guest Rating</small></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon bg-gold-soft"><i class="bi bi-people text-gold"></i></div>
                            <div><h4 class="mb-0">500+</h4><small class="text-muted">Happy Guests</small></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon bg-gold-soft"><i class="bi bi-trophy text-gold"></i></div>
                            <div><h4 class="mb-0">10+</h4><small class="text-muted">Awards</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600" class="img-fluid rounded-4 shadow-lg" alt="Zanzibar Villa">
                    <div class="experience-badge">
                        <span class="display-6 fw-bold text-gold">10+</span>
                        <small class="d-block">Years of Excellence</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Villas -->
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="text-gold text-uppercase fw-semibold small letter-spacing-2">Our Collection</span>
            <h2 class="display-5 fw-bold font-playfair mt-2">Featured Luxury Villas</h2>
            <p class="text-muted mx-auto" style="max-width: 600px">Discover our handpicked selection of premium villas, each offering a unique and unforgettable experience</p>
        </div>
        <div class="row g-4">
            <?php foreach (($villas ?? []) as $index => $villa): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="villa-card">
                    <div class="villa-card-img">
                        <img src="<?= e($villa['featured_image'] ?? 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=600') ?>" alt="<?= e($villa['name']) ?>">
                        <div class="villa-card-overlay">
                            <a href="/villas/<?= e($villa['slug']) ?>" class="btn btn-gold">View Details</a>
                        </div>
                        <span class="villa-badge">From $<?= number_format((float)$villa['base_price']) ?>/night</span>
                    </div>
                    <div class="villa-card-body">
                        <h5 class="fw-bold font-playfair"><?= e($villa['name']) ?></h5>
                        <p class="text-muted small mb-3"><?= e(truncate($villa['short_description'] ?? '', 100)) ?></p>
                        <div class="villa-card-features">
                            <span><i class="bi bi-door-open"></i> <?= e((string)$villa['bedrooms']) ?> Beds</span>
                            <span><i class="bi bi-droplet"></i> <?= e((string)$villa['bathrooms']) ?> Baths</span>
                            <span><i class="bi bi-people"></i> <?= e((string)$villa['max_guests']) ?> Guests</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="/villas" class="btn btn-outline-dark btn-lg px-5">View All Villas</a>
        </div>
    </div>
</section>

<!-- Services/Amenities -->
<section class="py-5 bg-dark text-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="text-gold text-uppercase fw-semibold small letter-spacing-2">Premium Services</span>
            <h2 class="display-5 fw-bold font-playfair mt-2">World-Class Amenities</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up"><div class="service-card text-center p-4"><i class="bi bi-egg-fried display-4 text-gold mb-3"></i><h6>Private Chef</h6><p class="small text-muted">Personal chef preparing gourmet meals daily</p></div></div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100"><div class="service-card text-center p-4"><i class="bi bi-water display-4 text-gold mb-3"></i><h6>Private Pool</h6><p class="small text-muted">Infinity pools with ocean views</p></div></div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200"><div class="service-card text-center p-4"><i class="bi bi-droplet display-4 text-gold mb-3"></i><h6>Spa & Wellness</h6><p class="small text-muted">In-villa spa treatments and massages</p></div></div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300"><div class="service-card text-center p-4"><i class="bi bi-airplane display-4 text-gold mb-3"></i><h6>Airport Transfer</h6><p class="small text-muted">Private luxury airport pickup service</p></div></div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up"><div class="service-card text-center p-4"><i class="bi bi-compass display-4 text-gold mb-3"></i><h6>Guided Tours</h6><p class="small text-muted">Explore Zanzibar's hidden gems</p></div></div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100"><div class="service-card text-center p-4"><i class="bi bi-car-front display-4 text-gold mb-3"></i><h6>Car Rental</h6><p class="small text-muted">Luxury vehicles with professional drivers</p></div></div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200"><div class="service-card text-center p-4"><i class="bi bi-wifi display-4 text-gold mb-3"></i><h6>High-Speed WiFi</h6><p class="small text-muted">Stay connected throughout your stay</p></div></div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300"><div class="service-card text-center p-4"><i class="bi bi-shield-check display-4 text-gold mb-3"></i><h6>24/7 Security</h6><p class="small text-muted">Round-the-clock security and concierge</p></div></div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="text-gold text-uppercase fw-semibold small letter-spacing-2">Testimonials</span>
            <h2 class="display-5 fw-bold font-playfair mt-2">What Our Guests Say</h2>
        </div>
        <div class="row g-4">
            <?php foreach (($testimonials ?? []) as $testimonial): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="testimonial-card p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="stars text-gold mb-3">
                        <?php for ($i = 0; $i < ($testimonial['rating'] ?? 5); $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                    </div>
                    <p class="text-muted mb-3">"<?= e(truncate($testimonial['content'] ?? '', 200)) ?>"</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm bg-gold text-white rounded-circle d-flex align-items-center justify-content-center">
                            <?= strtoupper(substr($testimonial['name'] ?? 'G', 0, 1)) ?>
                        </div>
                        <div>
                            <h6 class="mb-0 small fw-semibold"><?= e($testimonial['name'] ?? '') ?></h6>
                            <small class="text-muted"><?= e($testimonial['country'] ?? '') ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920') center/cover fixed">
    <div class="container text-center text-white py-5">
        <h2 class="display-4 fw-bold font-playfair mb-3" data-aos="fade-up">Ready to Experience Paradise?</h2>
        <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Book your dream villa in Zanzibar today and create memories that last a lifetime</p>
        <a href="/booking" class="btn btn-gold btn-lg px-5 py-3" data-aos="fade-up" data-aos-delay="200">
            <i class="bi bi-calendar-check me-2"></i>Book Now
        </a>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="text-gold text-uppercase fw-semibold small letter-spacing-2">FAQ</span>
            <h2 class="display-5 fw-bold font-playfair mt-2">Frequently Asked Questions</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                        <h2 class="accordion-header"><button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faq1">How do I book a villa?</button></h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body text-muted">You can book directly through our website by selecting your preferred villa, choosing dates, and completing the booking form. Alternatively, contact us via WhatsApp or email for personalized assistance.</div></div>
                    </div>
                    <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">What payment methods do you accept?</button></h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body text-muted">We accept M-Pesa, Airtel Money, bank transfers (CRDB, NMB, NBC), Visa/Mastercard, PayPal, Stripe, Flutterwave, and Pesapal. A 50% deposit confirms your booking.</div></div>
                    </div>
                    <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">What is the cancellation policy?</button></h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body text-muted">Free cancellation up to 14 days before check-in. 50% refund for cancellations 7-14 days before. No refund for cancellations less than 7 days before check-in. Policies vary by villa.</div></div>
                    </div>
                    <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq4">Is airport transfer available?</button></h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body text-muted">Yes! We offer private airport transfers from Zanzibar International Airport (ZNZ) to all our villas. You can add this as an extra service during booking.</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Map -->
<section class="py-0">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253682.46430640914!2d39.09787775!3d-6.1357!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185cd0ba23b63e57%3A0x5c9b6cd40d699b7e!2sZanzibar!5e0!3m2!1sen!2stz!4v1" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</section>
