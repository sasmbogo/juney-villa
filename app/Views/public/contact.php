<section class="page-hero" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920') center/cover; min-height: 40vh; display:flex; align-items:center; padding-top: 80px;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold font-playfair">Contact Us</h1>
        <p class="lead">Get in touch for bookings and inquiries</p>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <h4 class="fw-bold font-playfair mb-4">Get in Touch</h4>
                <div class="mb-4"><h6><i class="bi bi-geo-alt text-gold me-2"></i>Address</h6><p class="text-muted">Zanzibar, Tanzania</p></div>
                <div class="mb-4"><h6><i class="bi bi-telephone text-gold me-2"></i>Phone</h6><p class="text-muted">+255 777 000 000</p></div>
                <div class="mb-4"><h6><i class="bi bi-envelope text-gold me-2"></i>Email</h6><p class="text-muted">info@juneyvillaszanzibar.co.tz</p></div>
                <div class="mb-4"><h6><i class="bi bi-whatsapp text-gold me-2"></i>WhatsApp</h6><p class="text-muted">+255 777 000 000</p></div>
            </div>
            <div class="col-lg-7">
                <?php if ($flash = flash('success')): ?><div class="alert alert-success"><?= e($flash) ?></div><?php endif; ?>
                <?php if ($flash = flash('error')): ?><div class="alert alert-danger"><?= e($flash) ?></div><?php endif; ?>
                <form action="<?= url('/contact') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                        <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email Address" required></div>
                        <div class="col-md-6"><input type="tel" name="phone" class="form-control" placeholder="Phone Number"></div>
                        <div class="col-md-6"><input type="text" name="subject" class="form-control" placeholder="Subject" required></div>
                        <div class="col-12"><textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea></div>
                        <div class="col-12"><button type="submit" class="btn btn-gold px-4 py-2">Send Message</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
