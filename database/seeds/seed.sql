-- ================================================================
-- Juney Villa Limited - Seed Data
-- ================================================================

USE `juney_villa`;

-- ================================================================
-- ROLES
-- ================================================================

INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Super Admin', 'super-admin', 'Full system access'),
(2, 'Manager', 'manager', 'Property management access'),
(3, 'Receptionist', 'receptionist', 'Front desk operations'),
(4, 'Finance', 'finance', 'Financial management'),
(5, 'Housekeeping', 'housekeeping', 'Housekeeping management'),
(6, 'Maintenance', 'maintenance', 'Maintenance management'),
(7, 'Marketing', 'marketing', 'Marketing and content'),
(8, 'Customer Support', 'customer-support', 'Customer support'),
(9, 'Guest', 'guest', 'Registered guest');

-- ================================================================
-- PERMISSIONS
-- ================================================================

INSERT INTO `permissions` (`name`, `slug`, `module`) VALUES
('View Dashboard', 'view-dashboard', 'dashboard'),
('Manage Villas', 'manage-villas', 'villas'),
('View Villas', 'view-villas', 'villas'),
('Manage Bookings', 'manage-bookings', 'bookings'),
('View Bookings', 'view-bookings', 'bookings'),
('Create Bookings', 'create-bookings', 'bookings'),
('Manage Payments', 'manage-payments', 'payments'),
('View Payments', 'view-payments', 'payments'),
('Manage Users', 'manage-users', 'users'),
('View Users', 'view-users', 'users'),
('Manage Reviews', 'manage-reviews', 'reviews'),
('Manage Housekeeping', 'manage-housekeeping', 'housekeeping'),
('Manage Maintenance', 'manage-maintenance', 'maintenance'),
('Manage Blog', 'manage-blog', 'blog'),
('Manage Settings', 'manage-settings', 'settings'),
('View Reports', 'view-reports', 'reports'),
('Manage Gallery', 'manage-gallery', 'gallery'),
('Manage Coupons', 'manage-coupons', 'coupons'),
('Manage Support', 'manage-support', 'support'),
('Manage Notifications', 'manage-notifications', 'notifications');

-- ================================================================
-- ROLE PERMISSIONS (Super Admin gets all)
-- ================================================================

INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM `permissions`;

-- Manager permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 2, id FROM `permissions` WHERE slug IN ('view-dashboard','manage-villas','view-villas','manage-bookings','view-bookings','create-bookings','view-payments','manage-reviews','manage-housekeeping','manage-maintenance','view-reports','manage-gallery');

-- Receptionist permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 3, id FROM `permissions` WHERE slug IN ('view-dashboard','view-villas','view-bookings','create-bookings','view-payments','view-users');

-- Finance permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 4, id FROM `permissions` WHERE slug IN ('view-dashboard','manage-payments','view-payments','view-bookings','view-reports');

-- ================================================================
-- DEFAULT ADMIN USER
-- ================================================================

INSERT INTO `users` (`role_id`, `first_name`, `last_name`, `email`, `phone`, `password`, `email_verified_at`, `must_change_password`, `is_active`) VALUES
(1, 'Admin', 'Juney Villa', 'admin@juneyvillaszanzibar.co.tz', '+255777000000', '$2y$12$PYJBUnX6Lby9ZitHCqL9l.rwIoCCPfsodE3SW6zK7TKmA2YAGES7u', NOW(), 1, 1);

-- ================================================================
-- COMPANY INFO
-- ================================================================

INSERT INTO `company` (`name`, `tagline`, `email`, `phone`, `whatsapp`, `address`, `city`, `country`, `website`, `description`, `facebook`, `instagram`, `twitter`, `meta_title`, `meta_description`) VALUES
('Juney Villa Limited', 'Luxury Living in Paradise', 'info@juneyvillaszanzibar.co.tz', '+255 777 000 000', '+255777000000', 'Nungwi Road, North Coast', 'Zanzibar', 'Tanzania', 'https://juneyvillaszanzibar.co.tz', 'Experience the ultimate luxury villa rental in Zanzibar, Tanzania. Our five exclusive villas offer world-class amenities, private beaches, and the finest hospitality.', 'https://facebook.com/juneyvillaszanzibar', 'https://instagram.com/juneyvillaszanzibar', 'https://twitter.com/juneyvillas', 'Juney Villa Limited - Luxury Villa Rental in Zanzibar, Tanzania', 'Book your dream luxury villa in Zanzibar. Private pools, ocean views, personal chefs, and exclusive beach access. 5 premium villas available for short and long-term rental.');

-- ================================================================
-- LANGUAGES
-- ================================================================

INSERT INTO `languages` (`code`, `name`, `native_name`, `direction`, `is_active`, `is_default`) VALUES
('en', 'English', 'English', 'ltr', 1, 1),
('sw', 'Swahili', 'Kiswahili', 'ltr', 1, 0),
('fr', 'French', 'Français', 'ltr', 1, 0),
('de', 'German', 'Deutsch', 'ltr', 1, 0),
('it', 'Italian', 'Italiano', 'ltr', 1, 0),
('ar', 'Arabic', 'العربية', 'rtl', 1, 0);

-- ================================================================
-- CURRENCIES
-- ================================================================

INSERT INTO `currencies` (`code`, `name`, `symbol`, `exchange_rate`, `is_active`, `is_default`) VALUES
('USD', 'US Dollar', '$', 1.000000, 1, 1),
('TZS', 'Tanzanian Shilling', 'TSh', 2500.000000, 1, 0),
('EUR', 'Euro', '€', 0.920000, 1, 0),
('GBP', 'British Pound', '£', 0.790000, 1, 0);

-- ================================================================
-- VILLAS
-- ================================================================

INSERT INTO `villas` (`name`, `slug`, `tagline`, `description`, `short_description`, `villa_type`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqm`, `base_price`, `weekend_price`, `weekly_discount`, `monthly_discount`, `cleaning_fee`, `security_deposit`, `check_in_time`, `check_out_time`, `min_nights`, `address`, `latitude`, `longitude`, `rules`, `cancellation_policy`, `status`, `is_featured`, `sort_order`) VALUES
('Villa Ocean Paradise', 'villa-ocean-paradise', 'Your Private Ocean Sanctuary', 'Villa Ocean Paradise is our premier oceanfront property, offering breathtaking panoramic views of the Indian Ocean. This stunning 4-bedroom villa features a private infinity pool that seems to merge with the ocean horizon, spacious living areas with floor-to-ceiling windows, and a dedicated butler service. Each bedroom is elegantly furnished with king-size beds, premium linens, and en-suite bathrooms featuring rain showers and deep soaking tubs. The outdoor terrace is perfect for sunset cocktails, while the private garden provides a serene escape surrounded by tropical flora.', 'Stunning 4-bedroom oceanfront villa with private pool and panoramic ocean views.', 'luxury', 4, 4, 8, 450, 850.00, 1050.00, 10.00, 20.00, 150.00, 500.00, '14:00:00', '11:00:00', 2, 'Nungwi Beach, North Coast, Zanzibar', -5.7269, 39.2980, 'No smoking inside the villa. No parties or events without prior approval. Quiet hours 10 PM - 8 AM. Pets not allowed. Maximum occupancy must be respected.', 'Free cancellation up to 14 days before check-in. 50% refund for cancellations 7-14 days before check-in. No refund for cancellations less than 7 days before check-in.', 'active', 1, 1),

('Villa Sunset', 'villa-sunset', 'Where Every Evening is Golden', 'Villa Sunset lives up to its name with its west-facing orientation capturing the most spectacular sunsets in Zanzibar. This charming 3-bedroom villa combines traditional Zanzibari architecture with modern luxury. The lush tropical garden leads to a spacious BBQ area perfect for al fresco dining. Enjoy morning yoga on the private deck, afternoon swims in the garden pool, and evenings watching the sky transform into shades of gold and crimson.', 'Beautiful 3-bedroom villa with stunning sunset views, tropical garden, and BBQ area.', 'luxury', 3, 3, 6, 320, 550.00, 700.00, 10.00, 20.00, 100.00, 300.00, '14:00:00', '11:00:00', 2, 'Kendwa Beach, North West Coast, Zanzibar', -5.7501, 39.2270, 'No smoking inside the villa. No parties or events without prior approval. Quiet hours 10 PM - 8 AM. Pets not allowed.', 'Free cancellation up to 14 days before check-in. 50% refund for cancellations 7-14 days before check-in. No refund for cancellations less than 7 days before check-in.', 'active', 1, 2),

('Villa Palm', 'villa-palm', 'Beachfront Bliss Simplified', 'Villa Palm offers the perfect intimate retreat for couples or small families seeking direct beach access in a luxurious setting. This beautifully designed 2-bedroom villa opens directly onto pristine white sand, with the turquoise waters of the Indian Ocean just steps away. The minimalist yet elegant interior features handcrafted wooden furniture, premium mattresses, and contemporary artwork by local artists. A private veranda with hammocks and daybed provides the ideal spot for lazy afternoons.', 'Intimate 2-bedroom beachfront villa with direct beach access and minimalist luxury.', 'luxury', 2, 2, 4, 200, 380.00, 480.00, 10.00, 15.00, 75.00, 200.00, '14:00:00', '11:00:00', 2, 'Paje Beach, East Coast, Zanzibar', -6.2700, 39.5350, 'No smoking inside the villa. Quiet hours 10 PM - 8 AM. Pets not allowed. Please respect the natural beach environment.', 'Free cancellation up to 7 days before check-in. 50% refund for cancellations 3-7 days before. No refund within 3 days.', 'active', 1, 3),

('Villa Coral', 'villa-coral', 'Grandeur Meets the Sea', 'Villa Coral is our most spacious family villa, designed for those who demand nothing less than extraordinary. With 5 bedrooms spread across two floors, an infinity pool overlooking the reef, and over 600 square meters of living space, this villa is a private resort in itself. The professional kitchen caters to those who enjoy culinary adventures, while the dedicated cinema room provides entertainment for all ages. The villa includes a private gym, steam room, and direct access to a coral-sheltered lagoon.', 'Grand 5-bedroom villa with infinity pool, cinema room, gym, and reef views.', 'luxury', 5, 5, 10, 620, 1200.00, 1500.00, 10.00, 25.00, 200.00, 750.00, '14:00:00', '11:00:00', 3, 'Matemwe, North East Coast, Zanzibar', -5.8640, 39.3750, 'No smoking inside the villa. No parties without prior approval and additional fee. Quiet hours 10 PM - 8 AM. Pets not allowed. Maximum occupancy must be respected.', 'Free cancellation up to 21 days before check-in. 50% refund for cancellations 14-21 days before. 25% refund 7-14 days before. No refund within 7 days.', 'active', 1, 4),

('Villa Royal Zanzibar', 'villa-royal-zanzibar', 'The Crown Jewel of Zanzibar', 'Villa Royal Zanzibar is our presidential masterpiece - the ultimate expression of luxury living in East Africa. This extraordinary 6-bedroom estate features a private beach, dedicated personal chef, 24-hour butler service, and amenities that rival the finest resorts in the world. The palatial living spaces are adorned with handpicked antiques, Omani chandeliers, and Italian marble. The grounds include tropical gardens, a tennis court, private pier, and a spectacular infinity pool with a swim-up bar. This is not just a villa - it is a destination.', 'Presidential 6-bedroom estate with private beach, personal chef, butler service, and unmatched luxury.', 'presidential', 6, 7, 14, 950, 2500.00, 3200.00, 10.00, 30.00, 350.00, 1500.00, '14:00:00', '11:00:00', 3, 'Kizimkazi, South Coast, Zanzibar', -6.4290, 39.4560, 'No smoking inside the villa. Events must be pre-approved with additional charges. Quiet hours 11 PM - 7 AM. No pets. Staff areas are private.', 'Free cancellation up to 30 days before check-in. 50% refund 14-30 days before. 25% refund 7-14 days before. No refund within 7 days.', 'active', 1, 5);

-- ================================================================
-- AMENITIES
-- ================================================================

INSERT INTO `amenities` (`name`, `slug`, `icon`, `category`, `sort_order`) VALUES
('Private Pool', 'private-pool', 'bi-water', 'outdoor', 1),
('Ocean View', 'ocean-view', 'bi-binoculars', 'view', 2),
('Air Conditioning', 'air-conditioning', 'bi-thermometer-snow', 'comfort', 3),
('Free WiFi', 'free-wifi', 'bi-wifi', 'technology', 4),
('Fully Equipped Kitchen', 'kitchen', 'bi-cup-hot', 'kitchen', 5),
('Private Parking', 'parking', 'bi-car-front', 'facility', 6),
('BBQ/Grill', 'bbq-grill', 'bi-fire', 'outdoor', 7),
('Garden', 'garden', 'bi-tree', 'outdoor', 8),
('Beach Access', 'beach-access', 'bi-umbrella', 'outdoor', 9),
('Gym/Fitness', 'gym', 'bi-heart-pulse', 'wellness', 10),
('Spa/Sauna', 'spa-sauna', 'bi-droplet', 'wellness', 11),
('Home Theater', 'home-theater', 'bi-film', 'entertainment', 12),
('Laundry', 'laundry', 'bi-basket', 'service', 13),
('24h Security', 'security', 'bi-shield-check', 'safety', 14),
('Butler Service', 'butler-service', 'bi-person-badge', 'service', 15),
('Private Chef', 'private-chef', 'bi-egg-fried', 'service', 16),
('Airport Transfer', 'airport-transfer', 'bi-airplane', 'transport', 17),
('Concierge', 'concierge', 'bi-telephone', 'service', 18),
('Infinity Pool', 'infinity-pool', 'bi-water', 'outdoor', 19),
('Tennis Court', 'tennis-court', 'bi-dribbble', 'recreation', 20),
('Private Beach', 'private-beach', 'bi-sun', 'outdoor', 21),
('Rooftop Terrace', 'rooftop-terrace', 'bi-building', 'outdoor', 22),
('Smart TV', 'smart-tv', 'bi-tv', 'entertainment', 23),
('King Size Beds', 'king-size-beds', 'bi-lamp', 'comfort', 24),
('Rain Shower', 'rain-shower', 'bi-droplet-half', 'comfort', 25);

-- Villa Amenities
INSERT INTO `villa_amenities` (`villa_id`, `amenity_id`) VALUES
-- Villa Ocean Paradise
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,8),(1,9),(1,13),(1,14),(1,15),(1,17),(1,18),(1,23),(1,24),(1,25),
-- Villa Sunset
(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,13),(2,14),(2,17),(2,23),(2,24),(2,25),
-- Villa Palm
(3,3),(3,4),(3,5),(3,9),(3,13),(3,14),(3,17),(3,23),(3,24),(3,25),
-- Villa Coral
(4,3),(4,4),(4,5),(4,6),(4,8),(4,10),(4,11),(4,12),(4,13),(4,14),(4,15),(4,17),(4,18),(4,19),(4,23),(4,24),(4,25),
-- Villa Royal Zanzibar
(5,2),(5,3),(5,4),(5,5),(5,6),(5,8),(5,10),(5,11),(5,12),(5,13),(5,14),(5,15),(5,16),(5,17),(5,18),(5,19),(5,20),(5,21),(5,22),(5,23),(5,24),(5,25);

-- ================================================================
-- EXTRAS CATALOG
-- ================================================================

INSERT INTO `extras_catalog` (`name`, `slug`, `description`, `price`, `price_type`, `category`, `icon`, `is_active`) VALUES
('Airport Pickup', 'airport-pickup', 'Private airport transfer from Zanzibar International Airport', 75.00, 'per_booking', 'transport', 'bi-airplane', 1),
('Daily Breakfast', 'daily-breakfast', 'Fresh tropical breakfast served at your villa daily', 25.00, 'per_person', 'dining', 'bi-cup-hot', 1),
('Car Rental', 'car-rental', 'Full day car rental with driver', 80.00, 'per_night', 'transport', 'bi-car-front', 1),
('Spice Tour', 'spice-tour', 'Guided tour of Zanzibar spice farms', 45.00, 'per_person', 'tours', 'bi-tree', 1),
('Sunset Dhow Cruise', 'sunset-cruise', 'Traditional dhow sailing cruise at sunset', 65.00, 'per_person', 'tours', 'bi-water', 1),
('Spa Package', 'spa-package', 'Relaxing full body spa treatment', 120.00, 'per_person', 'wellness', 'bi-droplet', 1),
('Private Chef Dinner', 'private-chef-dinner', 'Exclusive 5-course dinner prepared by private chef', 150.00, 'per_booking', 'dining', 'bi-egg-fried', 1),
('Snorkeling Trip', 'snorkeling-trip', 'Half-day snorkeling trip to coral reefs', 55.00, 'per_person', 'tours', 'bi-water', 1),
('Stone Town Tour', 'stone-town-tour', 'Guided historical tour of Stone Town', 40.00, 'per_person', 'tours', 'bi-building', 1),
('Dolphin Tour', 'dolphin-tour', 'Morning dolphin watching and swimming tour', 60.00, 'per_person', 'tours', 'bi-water', 1);

-- ================================================================
-- PAYMENT METHODS
-- ================================================================

INSERT INTO `payment_methods` (`name`, `slug`, `type`, `provider`, `is_active`, `sort_order`) VALUES
('M-Pesa Tanzania', 'mpesa', 'mobile_money', 'vodacom', 1, 1),
('Airtel Money', 'airtel-money', 'mobile_money', 'airtel', 1, 2),
('Mixx by Yas (Tigo Pesa)', 'mixx-yas', 'mobile_money', 'tigo', 1, 3),
('HaloPesa', 'halopesa', 'mobile_money', 'halotel', 1, 4),
('CRDB Bank', 'crdb-bank', 'bank_transfer', 'crdb', 1, 5),
('NMB Bank', 'nmb-bank', 'bank_transfer', 'nmb', 1, 6),
('NBC Bank', 'nbc-bank', 'bank_transfer', 'nbc', 1, 7),
('Visa/Mastercard', 'visa-mastercard', 'card', 'stripe', 1, 8),
('PayPal', 'paypal', 'online_gateway', 'paypal', 1, 9),
('Stripe', 'stripe', 'online_gateway', 'stripe', 1, 10),
('Flutterwave', 'flutterwave', 'online_gateway', 'flutterwave', 1, 11),
('Pesapal', 'pesapal', 'online_gateway', 'pesapal', 1, 12),
('Selcom', 'selcom', 'online_gateway', 'selcom', 1, 13),
('DPO Pay', 'dpo-pay', 'online_gateway', 'dpo', 1, 14);

-- ================================================================
-- TAXES
-- ================================================================

INSERT INTO `taxes` (`name`, `rate`, `type`, `is_active`, `apply_to`) VALUES
('VAT', 18.00, 'percentage', 1, 'all'),
('Tourism Levy', 1.50, 'percentage', 1, 'all');

-- ================================================================
-- TESTIMONIALS
-- ================================================================

INSERT INTO `testimonials` (`name`, `title`, `content`, `rating`, `country`, `is_active`, `sort_order`) VALUES
('Sarah & James Miller', 'Honeymoon at Villa Ocean Paradise', 'Our honeymoon at Villa Ocean Paradise was absolutely magical. The private pool overlooking the ocean, the impeccable service, and the stunning sunsets made it a trip we will never forget. The staff went above and beyond to make us feel special.', 5, 'United Kingdom', 1, 1),
('Hans Mueller', 'Family Vacation at Villa Coral', 'We stayed at Villa Coral with our three children and it was perfect. The infinity pool, cinema room, and direct beach access kept everyone entertained. The private chef prepared amazing meals every day. Already planning our next visit!', 5, 'Germany', 1, 2),
('Marie Dubois', 'Romantic Getaway at Villa Palm', 'Villa Palm is a hidden gem! The direct beach access, the sound of waves, and the intimate setting made it the perfect romantic escape. The attention to detail in the decor and service is outstanding.', 5, 'France', 1, 3),
('David & Lisa Thompson', 'Group Stay at Villa Royal Zanzibar', 'We celebrated our anniversary with friends at Villa Royal Zanzibar. What an incredible estate! The private beach, personal chef, and butler made us feel like royalty. The best holiday experience of our lives.', 5, 'Australia', 1, 4),
('Akiko Tanaka', 'Wellness Retreat at Villa Sunset', 'I came to Villa Sunset for a personal wellness retreat and left feeling completely rejuvenated. The peaceful garden, the yoga deck, and the proximity to nature were exactly what I needed. Pure bliss!', 5, 'Japan', 1, 5);

-- ================================================================
-- FAQ
-- ================================================================

INSERT INTO `faqs` (`category`, `question`, `answer`, `sort_order`, `is_active`) VALUES
('booking', 'How do I book a villa?', 'You can book directly through our website by selecting your preferred villa, choosing dates, and completing the booking form. You can also contact us via email or WhatsApp for assistance.', 1, 1),
('booking', 'What is the minimum stay requirement?', 'Most of our villas require a minimum stay of 2 nights. Villa Coral and Villa Royal Zanzibar require a minimum of 3 nights. During peak season, minimum stays may be longer.', 2, 1),
('payment', 'What payment methods do you accept?', 'We accept M-Pesa, Airtel Money, bank transfers (CRDB, NMB, NBC), Visa/Mastercard, PayPal, and other online payment gateways. A 50% deposit is required to confirm your booking.', 3, 1),
('payment', 'Is there a security deposit?', 'Yes, a refundable security deposit is required upon check-in. The amount varies by villa. It will be refunded within 7 days after checkout, subject to a property inspection.', 4, 1),
('general', 'Is airport transfer included?', 'Airport transfer is available as an add-on service. We offer private transfers from Zanzibar International Airport (ZNZ) to all our villas.', 5, 1),
('general', 'Do you provide a chef or cook?', 'Villa Royal Zanzibar includes a dedicated private chef. For other villas, a private chef can be arranged as an add-on service.', 6, 1),
('cancellation', 'What is your cancellation policy?', 'Cancellation policies vary by villa. Generally, free cancellation is available up to 14 days before check-in. Please check the specific villa page for detailed cancellation terms.', 7, 1),
('general', 'Are the villas child-friendly?', 'Yes, all our villas are family-friendly. Baby cots, high chairs, and pool safety equipment are available upon request.', 8, 1);

-- ================================================================
-- BLOG CATEGORIES
-- ================================================================

INSERT INTO `blog_categories` (`name`, `slug`, `description`, `is_active`) VALUES
('Travel Guide', 'travel-guide', 'Zanzibar travel tips and guides', 1),
('News', 'news', 'Latest news and updates', 1),
('Offers', 'offers', 'Special offers and promotions', 1),
('Events', 'events', 'Local events and festivals', 1),
('Attractions', 'attractions', 'Zanzibar attractions and activities', 1);

-- ================================================================
-- SAMPLE BLOGS
-- ================================================================

INSERT INTO `blogs` (`category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `status`, `published_at`, `is_featured`) VALUES
(1, 1, 'Top 10 Things to Do in Zanzibar', 'top-10-things-to-do-in-zanzibar', 'Discover the best experiences Zanzibar has to offer, from pristine beaches to historic Stone Town.', '<h2>Explore the Magic of Zanzibar</h2><p>Zanzibar is a tropical paradise that offers something for everyone. From its pristine white-sand beaches to its rich cultural heritage, here are the top 10 must-do experiences...</p><h3>1. Visit Stone Town</h3><p>A UNESCO World Heritage Site, Stone Town is a maze of narrow streets, historic buildings, and vibrant markets...</p><h3>2. Spice Farm Tour</h3><p>Known as the Spice Island, Zanzibar offers fascinating tours through its aromatic spice farms...</p>', 'published', NOW(), 1),
(5, 1, 'Best Beaches in Zanzibar for 2025', 'best-beaches-zanzibar-2025', 'From the famous Nungwi to the serene Paje, explore Zanzibar''s most beautiful coastlines.', '<h2>Zanzibar''s Most Beautiful Beaches</h2><p>Zanzibar is home to some of the world''s most beautiful beaches. Each coast offers a unique experience...</p><h3>Nungwi Beach</h3><p>Located on the northern tip, Nungwi offers crystal-clear waters and spectacular sunsets...</p>', 'published', NOW(), 1);

-- ================================================================
-- COUPONS
-- ================================================================

INSERT INTO `coupons` (`code`, `name`, `type`, `value`, `min_nights`, `start_date`, `end_date`, `usage_limit`, `is_active`) VALUES
('WELCOME10', 'Welcome Discount', 'percentage', 10.00, 2, '2024-01-01', '2025-12-31', 100, 1),
('LONGSTAY20', 'Long Stay Discount', 'percentage', 20.00, 7, '2024-01-01', '2025-12-31', 50, 1),
('EARLYBIRD15', 'Early Bird Special', 'percentage', 15.00, 3, '2024-01-01', '2025-12-31', 75, 1),
('ZANZIBAR50', 'Zanzibar Special', 'fixed', 50.00, 2, '2024-01-01', '2025-12-31', 200, 1);

-- ================================================================
-- SETTINGS
-- ================================================================

INSERT INTO `settings` (`group`, `key`, `value`, `type`) VALUES
('general', 'site_name', 'Juney Villa Limited', 'text'),
('general', 'site_tagline', 'Luxury Villa Rental in Zanzibar', 'text'),
('general', 'timezone', 'Africa/Dar_es_Salaam', 'text'),
('general', 'date_format', 'Y-m-d', 'text'),
('general', 'time_format', 'H:i', 'text'),
('booking', 'auto_confirm', '0', 'boolean'),
('booking', 'require_deposit', '1', 'boolean'),
('booking', 'deposit_percentage', '50', 'number'),
('booking', 'max_guests_without_extra', '2', 'number'),
('booking', 'check_in_time', '14:00', 'text'),
('booking', 'check_out_time', '11:00', 'text'),
('email', 'smtp_host', 'smtp.gmail.com', 'text'),
('email', 'smtp_port', '587', 'number'),
('email', 'smtp_encryption', 'tls', 'text'),
('payment', 'default_currency', 'USD', 'text'),
('payment', 'tax_rate', '18', 'number'),
('payment', 'tourism_levy', '1.5', 'number'),
('seo', 'google_analytics', '', 'text'),
('seo', 'meta_title', 'Juney Villa Limited - Luxury Villa Rental in Zanzibar', 'text'),
('seo', 'meta_description', 'Book luxury villas in Zanzibar, Tanzania. Private pools, ocean views, and world-class service.', 'text');
