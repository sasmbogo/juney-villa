/**
 * Juney Villa Limited - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Remove preloader
    setTimeout(() => {
        const preloader = document.getElementById('preloader');
        if (preloader) preloader.classList.add('hidden');
    }, 500);

    // Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 800, once: true, offset: 50 });
    }

    // Navbar scroll effect
    const navbar = document.getElementById('mainNav');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });
    }

    // Hero Slider
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length > 1) {
        let current = 0;
        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 5000);
    }

    // Flatpickr Date Pickers
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.flatpickr-date', {
            minDate: 'today',
            dateFormat: 'Y-m-d',
            disableMobile: true
        });
    }

    // Back to Top Button
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            backToTop.classList.toggle('show', window.scrollY > 500);
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Newsletter Form
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/newsletter/subscribe', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Subscribed!', text: data.message, confirmButtonColor: '#C8A45C' });
                    this.reset();
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#C8A45C' });
                }
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#C8A45C' });
            });
        });
    }

    // Availability Check (AJAX)
    window.checkAvailability = function(villaId, checkIn, checkOut) {
        return fetch('/booking/check-availability', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: `villa_id=${villaId}&check_in=${checkIn}&check_out=${checkOut}&_token=${document.querySelector('[name=_token]')?.value || ''}`
        }).then(res => res.json());
    };

    // Coupon Validation
    window.validateCoupon = function(code) {
        return fetch('/api/v1/validate-coupon', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ code: code })
        }).then(res => res.json());
    };

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Lazy loading images
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        lazyImages.forEach(img => observer.observe(img));
    }

    // Currency converter
    window.convertCurrency = function(amount, fromCurrency, toCurrency) {
        const rates = { USD: 1, TZS: 2500, EUR: 0.92, GBP: 0.79 };
        const inUSD = amount / (rates[fromCurrency] || 1);
        return inUSD * (rates[toCurrency] || 1);
    };
});
