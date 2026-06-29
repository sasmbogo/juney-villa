# Juney Villa Limited - Luxury Villa Rental & Booking Management System

A complete, production-ready, enterprise-grade luxury villa rental website and booking management system built for **Juney Villa Limited** in Zanzibar, Tanzania.

## Features

### Marketing Website
- Premium luxury design with animations and glassmorphism effects
- Hero section with image slider and booking search form
- Villa showcase with detailed pages
- Photo gallery with categories
- Blog with travel guides and news
- Testimonials, FAQ, and Google Maps
- SEO optimized with Schema.org markup
- Multi-language (EN, SW, FR, DE, IT, AR) and multi-currency (USD, TZS, EUR, GBP)
- WhatsApp floating button and newsletter subscription

### Booking System
- Real-time availability checking
- Dynamic pricing (base, weekend, seasonal, holidays)
- Extra services (airport pickup, breakfast, tours, spa)
- Discount codes and coupons
- Tax calculation (VAT 18%, Tourism Levy 1.5%)
- Professional PDF invoices
- Email/SMS/WhatsApp notifications
- Complete booking workflow: Search → Select → Book → Pay → Confirm

### Admin Dashboard
- Beautiful analytics with charts (Chart.js)
- Revenue and occupancy tracking
- Villa management (CRUD)
- Booking management with status workflow
- User and role management (9 roles)
- Housekeeping and maintenance modules
- Blog and gallery management
- Reports (revenue, occupancy, bookings)
- Coupon and discount management
- System settings and company profile
- Activity logs and audit trails
- Dark/Light mode toggle

### Payment Integration
- **Mobile Money:** M-Pesa, Airtel Money, Mixx by Yas, HaloPesa, Ezypesa
- **Banks:** CRDB, NMB, NBC, Absa, Stanbic, Equity, KCB, Exim, DTB, Azania, BOA, I&M, Ecobank
- **International:** Visa, Mastercard, AmEx, UnionPay, PayPal, Stripe, Flutterwave, Pesapal, Selcom, DPO Pay

### Security
- CSRF Protection on all forms
- SQL Injection Prevention (PDO Prepared Statements)
- XSS Protection (Output escaping)
- Password Hashing (bcrypt, cost 12)
- Rate Limiting (IP-based)
- Login attempt tracking with account lockout
- Role-based access control (RBAC)
- Activity and audit logging

## Technology Stack

| Technology | Version |
|-----------|---------|
| PHP | 8.3+ |
| MySQL | 8.0+ |
| Bootstrap | 5.3.3 |
| jQuery | 3.7.1 |
| Chart.js | 4.4.1 |
| SweetAlert2 | 11 |
| AOS | 2.3.4 |
| Flatpickr | (latest) |
| Font Awesome | 6.5.1 |

## Requirements

- PHP 8.3 or higher
- MySQL 8.0 or higher
- Apache with mod_rewrite enabled
- Composer
- PHP Extensions: PDO, mbstring, json, curl, gd, openssl

## Installation

### Option 1: Installation Wizard (Recommended)

1. Extract the zip to your web server root (e.g., `htdocs/juney-villa`)
2. Run `composer install` in the project directory
3. Navigate to `http://localhost/juney-villa/install.php`
4. Follow the wizard steps
5. **Delete `install.php` after installation**

### Option 2: Manual Setup

1. Extract and place files in your web root
2. Run `composer install`
3. Create a MySQL database named `juney_villa`
4. Import `database/schema.sql`
5. Import `database/seeds/seed.sql`
6. Copy `.env.example` to `.env` and update credentials
7. Ensure `storage/` and `public/uploads/` are writable

### XAMPP Setup

```bash
cd C:\xampp\htdocs
# Place project in htdocs/juney-villa
cd juney-villa
composer install
# Access: http://localhost/juney-villa
```

### Laragon Setup

```bash
cd C:\laragon\www
# Place project in www/juney-villa
cd juney-villa
composer install
# Access: http://juney-villa.test (with Laragon auto-vhost)
```

## Default Admin Login

- **Email:** admin@juneyvillaszanzibar.co.tz
- **Password:** Tanzania12$
- **Note:** Password change required on first login

## Project Structure

```
juney-villa/
├── app/
│   ├── Controllers/      # MVC Controllers
│   │   ├── Admin/       # Admin panel controllers
│   │   ├── Api/         # REST API controllers
│   │   ├── Auth/        # Authentication controllers
│   │   └── Guest/       # Guest dashboard controllers
│   ├── Core/            # Framework core classes
│   │   ├── Application.php
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Model.php
│   │   ├── Router.php
│   │   └── Exceptions/
│   ├── Helpers/         # Global helper functions
│   ├── Middleware/      # Request middleware
│   ├── Models/          # Database models
│   └── Views/           # View templates
│       ├── admin/       # Admin dashboard views
│       ├── auth/        # Authentication views
│       ├── errors/      # Error pages
│       ├── guest/       # Guest panel views
│       ├── layouts/     # Layout templates
│       └── public/      # Public website views
├── config/              # Configuration files
├── database/            # SQL schema and seeds
├── public/              # Web root (document root)
│   ├── css/
│   ├── js/
│   ├── images/
│   ├── uploads/
│   └── index.php        # Entry point
├── routes/              # Route definitions
├── storage/             # Cache, logs, sessions
├── .env.example         # Environment template
├── composer.json        # PHP dependencies
└── install.php          # Installation wizard
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/villas` | List all villas |
| GET | `/api/v1/villas/{slug}` | Get villa details |
| GET | `/api/v1/availability/{villaId}` | Get blocked dates |
| POST | `/api/v1/check-availability` | Check date availability |
| POST | `/api/v1/validate-coupon` | Validate coupon code |
| GET | `/api/v1/reviews/{villaId}` | Get villa reviews |
| POST | `/api/v1/payment/callback/*` | Payment gateway callbacks |

## Villas

1. **Villa Ocean Paradise** - 4BR, Private Pool, Ocean View ($850/night)
2. **Villa Sunset** - 3BR, Garden, BBQ Area ($550/night)
3. **Villa Palm** - 2BR, Beach Access ($380/night)
4. **Villa Coral** - 5BR, Infinity Pool ($1,200/night)
5. **Villa Royal Zanzibar** - 6BR, Presidential, Private Beach ($2,500/night)

## User Roles

1. Super Admin (full access)
2. Manager
3. Receptionist
4. Finance
5. Housekeeping
6. Maintenance
7. Marketing
8. Customer Support
9. Guest

## Cron Jobs

Add to your server crontab:
```bash
# Send booking reminders (daily at 8am)
0 8 * * * php /path/to/juney-villa/cron/reminders.php

# Clean expired sessions (daily at midnight)
0 0 * * * php /path/to/juney-villa/cron/cleanup.php
```

## License

Proprietary - Juney Villa Limited. All rights reserved.

## Support

- Email: info@juneyvillaszanzibar.co.tz
- Phone: +255 777 000 000
- Website: https://juneyvillaszanzibar.co.tz
