-- ================================================================
-- Juney Villa Limited - Complete Database Schema
-- Luxury Villa Rental & Booking Management System
-- MySQL 8.0+
-- ================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';

CREATE DATABASE IF NOT EXISTS `juney_villa` 
    DEFAULT CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `juney_villa`;

-- ================================================================
-- ROLES & PERMISSIONS
-- ================================================================

CREATE TABLE `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `permissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `module` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `role_permissions` (
    `role_id` INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================================================================
-- USERS
-- ================================================================

CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT UNSIGNED NOT NULL DEFAULT 9,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NULL,
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) NULL,
    `date_of_birth` DATE NULL,
    `gender` ENUM('male','female','other') NULL,
    `nationality` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `city` VARCHAR(100) NULL,
    `country` VARCHAR(100) NULL,
    `id_type` VARCHAR(50) NULL,
    `id_number` VARCHAR(100) NULL,
    `email_verified_at` DATETIME NULL,
    `email_verification_token` VARCHAR(255) NULL,
    `two_factor_enabled` TINYINT(1) DEFAULT 0,
    `two_factor_secret` VARCHAR(255) NULL,
    `google_id` VARCHAR(255) NULL,
    `facebook_id` VARCHAR(255) NULL,
    `remember_token` VARCHAR(255) NULL,
    `password_reset_token` VARCHAR(255) NULL,
    `password_reset_expires` DATETIME NULL,
    `must_change_password` TINYINT(1) DEFAULT 0,
    `last_login_at` DATETIME NULL,
    `last_login_ip` VARCHAR(45) NULL,
    `login_attempts` INT DEFAULT 0,
    `locked_until` DATETIME NULL,
    `preferred_language` VARCHAR(5) DEFAULT 'en',
    `preferred_currency` VARCHAR(3) DEFAULT 'USD',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`),
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_role` (`role_id`),
    INDEX `idx_users_active` (`is_active`)
) ENGINE=InnoDB;

-- ================================================================
-- COMPANY SETTINGS
-- ================================================================

CREATE TABLE `company` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `tagline` VARCHAR(255) NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `whatsapp` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `city` VARCHAR(100) NULL,
    `country` VARCHAR(100) NULL,
    `postal_code` VARCHAR(20) NULL,
    `logo` VARCHAR(255) NULL,
    `favicon` VARCHAR(255) NULL,
    `website` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `tax_id` VARCHAR(50) NULL,
    `registration_number` VARCHAR(100) NULL,
    `bank_name` VARCHAR(100) NULL,
    `bank_account` VARCHAR(50) NULL,
    `bank_branch` VARCHAR(100) NULL,
    `swift_code` VARCHAR(20) NULL,
    `facebook` VARCHAR(255) NULL,
    `instagram` VARCHAR(255) NULL,
    `twitter` VARCHAR(255) NULL,
    `youtube` VARCHAR(255) NULL,
    `tiktok` VARCHAR(255) NULL,
    `linkedin` VARCHAR(255) NULL,
    `tripadvisor` VARCHAR(255) NULL,
    `google_maps_embed` TEXT NULL,
    `google_analytics` VARCHAR(50) NULL,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` TEXT NULL,
    `meta_keywords` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- SYSTEM SETTINGS
-- ================================================================

CREATE TABLE `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `group` VARCHAR(50) NOT NULL,
    `key` VARCHAR(100) NOT NULL,
    `value` TEXT NULL,
    `type` ENUM('text','number','boolean','json','file') DEFAULT 'text',
    `description` VARCHAR(255) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_setting` (`group`, `key`)
) ENGINE=InnoDB;

-- ================================================================
-- LANGUAGES & CURRENCIES
-- ================================================================

CREATE TABLE `languages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(5) NOT NULL UNIQUE,
    `name` VARCHAR(50) NOT NULL,
    `native_name` VARCHAR(50) NOT NULL,
    `direction` ENUM('ltr','rtl') DEFAULT 'ltr',
    `is_active` TINYINT(1) DEFAULT 1,
    `is_default` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `currencies` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(3) NOT NULL UNIQUE,
    `name` VARCHAR(50) NOT NULL,
    `symbol` VARCHAR(10) NOT NULL,
    `exchange_rate` DECIMAL(12,6) NOT NULL DEFAULT 1.000000,
    `is_active` TINYINT(1) DEFAULT 1,
    `is_default` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- VILLAS
-- ================================================================

CREATE TABLE `villas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `tagline` VARCHAR(255) NULL,
    `description` TEXT NOT NULL,
    `short_description` TEXT NULL,
    `villa_type` VARCHAR(50) DEFAULT 'luxury',
    `bedrooms` INT NOT NULL DEFAULT 1,
    `bathrooms` INT NOT NULL DEFAULT 1,
    `max_guests` INT NOT NULL DEFAULT 2,
    `size_sqm` INT NULL,
    `floor_plan` VARCHAR(255) NULL,
    `virtual_tour_url` VARCHAR(500) NULL,
    `video_url` VARCHAR(500) NULL,
    `featured_image` VARCHAR(255) NULL,
    `base_price` DECIMAL(12,2) NOT NULL,
    `weekend_price` DECIMAL(12,2) NULL,
    `weekly_discount` DECIMAL(5,2) DEFAULT 0,
    `monthly_discount` DECIMAL(5,2) DEFAULT 0,
    `cleaning_fee` DECIMAL(10,2) DEFAULT 0,
    `security_deposit` DECIMAL(10,2) DEFAULT 0,
    `extra_guest_fee` DECIMAL(10,2) DEFAULT 0,
    `check_in_time` TIME DEFAULT '14:00:00',
    `check_out_time` TIME DEFAULT '11:00:00',
    `min_nights` INT DEFAULT 1,
    `max_nights` INT DEFAULT 365,
    `address` TEXT NULL,
    `latitude` DECIMAL(10,8) NULL,
    `longitude` DECIMAL(11,8) NULL,
    `google_map_embed` TEXT NULL,
    `rules` TEXT NULL,
    `cancellation_policy` TEXT NULL,
    `nearby_attractions` TEXT NULL,
    `status` ENUM('active','inactive','maintenance','coming_soon') DEFAULT 'active',
    `is_featured` TINYINT(1) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` TEXT NULL,
    `meta_keywords` TEXT NULL,
    `view_count` INT UNSIGNED DEFAULT 0,
    `booking_count` INT UNSIGNED DEFAULT 0,
    `average_rating` DECIMAL(3,2) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_villas_status` (`status`),
    INDEX `idx_villas_featured` (`is_featured`),
    INDEX `idx_villas_slug` (`slug`)
) ENGINE=InnoDB;

CREATE TABLE `villa_images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `thumbnail_path` VARCHAR(255) NULL,
    `title` VARCHAR(255) NULL,
    `alt_text` VARCHAR(255) NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_360` TINYINT(1) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE,
    INDEX `idx_villa_images_villa` (`villa_id`)
) ENGINE=InnoDB;

CREATE TABLE `villa_videos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NULL,
    `video_url` VARCHAR(500) NOT NULL,
    `video_type` ENUM('youtube','vimeo','local','drone') DEFAULT 'youtube',
    `thumbnail` VARCHAR(255) NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `amenities` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `icon` VARCHAR(50) NULL,
    `category` VARCHAR(50) DEFAULT 'general',
    `description` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `villa_amenities` (
    `villa_id` INT UNSIGNED NOT NULL,
    `amenity_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`villa_id`, `amenity_id`),
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================================================================
-- VILLA PRICING & AVAILABILITY
-- ================================================================

CREATE TABLE `villa_pricing` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `price_type` ENUM('seasonal','holiday','special','weekend') NOT NULL,
    `price` DECIMAL(12,2) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `min_nights` INT DEFAULT 1,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE,
    INDEX `idx_pricing_dates` (`start_date`, `end_date`),
    INDEX `idx_pricing_villa` (`villa_id`)
) ENGINE=InnoDB;

CREATE TABLE `villa_availability` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `status` ENUM('available','booked','blocked','maintenance') DEFAULT 'available',
    `price_override` DECIMAL(12,2) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_villa_date` (`villa_id`, `date`),
    INDEX `idx_availability_date` (`date`)
) ENGINE=InnoDB;

-- ================================================================
-- BOOKINGS
-- ================================================================

CREATE TABLE `bookings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_number` VARCHAR(20) NOT NULL UNIQUE,
    `villa_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `guest_name` VARCHAR(200) NOT NULL,
    `guest_email` VARCHAR(255) NOT NULL,
    `guest_phone` VARCHAR(20) NULL,
    `guest_country` VARCHAR(100) NULL,
    `guest_id_type` VARCHAR(50) NULL,
    `guest_id_number` VARCHAR(100) NULL,
    `adults` INT NOT NULL DEFAULT 1,
    `children` INT DEFAULT 0,
    `infants` INT DEFAULT 0,
    `check_in` DATE NOT NULL,
    `check_out` DATE NOT NULL,
    `nights` INT NOT NULL,
    `base_price` DECIMAL(12,2) NOT NULL,
    `extras_total` DECIMAL(12,2) DEFAULT 0,
    `cleaning_fee` DECIMAL(10,2) DEFAULT 0,
    `discount_amount` DECIMAL(10,2) DEFAULT 0,
    `discount_code` VARCHAR(50) NULL,
    `tax_amount` DECIMAL(10,2) DEFAULT 0,
    `total_amount` DECIMAL(12,2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'USD',
    `exchange_rate` DECIMAL(12,6) DEFAULT 1.000000,
    `status` ENUM('pending','confirmed','checked_in','checked_out','completed','cancelled','refunded','no_show') DEFAULT 'pending',
    `payment_status` ENUM('pending','partial','paid','refunded','failed') DEFAULT 'pending',
    `special_requests` TEXT NULL,
    `internal_notes` TEXT NULL,
    `cancellation_reason` TEXT NULL,
    `cancelled_at` DATETIME NULL,
    `checked_in_at` DATETIME NULL,
    `checked_out_at` DATETIME NULL,
    `confirmed_at` DATETIME NULL,
    `confirmed_by` INT UNSIGNED NULL,
    `source` ENUM('website','admin','phone','email','api') DEFAULT 'website',
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_bookings_number` (`booking_number`),
    INDEX `idx_bookings_villa` (`villa_id`),
    INDEX `idx_bookings_user` (`user_id`),
    INDEX `idx_bookings_status` (`status`),
    INDEX `idx_bookings_dates` (`check_in`, `check_out`),
    INDEX `idx_bookings_created` (`created_at`)
) ENGINE=InnoDB;

CREATE TABLE `booking_extras` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `quantity` INT DEFAULT 1,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `total_price` DECIMAL(10,2) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `extras_catalog` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `price_type` ENUM('per_booking','per_night','per_person','per_item') DEFAULT 'per_booking',
    `category` VARCHAR(50) DEFAULT 'general',
    `icon` VARCHAR(50) NULL,
    `image` VARCHAR(255) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- PAYMENTS
-- ================================================================

CREATE TABLE `payment_methods` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `type` ENUM('mobile_money','bank_transfer','card','online_gateway') NOT NULL,
    `provider` VARCHAR(50) NULL,
    `icon` VARCHAR(255) NULL,
    `instructions` TEXT NULL,
    `config` JSON NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT UNSIGNED NOT NULL,
    `payment_method_id` INT UNSIGNED NULL,
    `transaction_id` VARCHAR(255) NULL,
    `reference_number` VARCHAR(100) NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'USD',
    `exchange_rate` DECIMAL(12,6) DEFAULT 1.000000,
    `fee` DECIMAL(10,2) DEFAULT 0,
    `net_amount` DECIMAL(12,2) NOT NULL,
    `status` ENUM('pending','processing','completed','failed','refunded','cancelled') DEFAULT 'pending',
    `gateway_response` JSON NULL,
    `paid_at` DATETIME NULL,
    `refunded_at` DATETIME NULL,
    `refund_reason` TEXT NULL,
    `notes` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`),
    FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods`(`id`),
    INDEX `idx_payments_booking` (`booking_id`),
    INDEX `idx_payments_status` (`status`),
    INDEX `idx_payments_transaction` (`transaction_id`)
) ENGINE=InnoDB;

-- ================================================================
-- COUPONS & DISCOUNTS
-- ================================================================

CREATE TABLE `coupons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `type` ENUM('percentage','fixed') NOT NULL,
    `value` DECIMAL(10,2) NOT NULL,
    `min_nights` INT DEFAULT 1,
    `min_amount` DECIMAL(10,2) DEFAULT 0,
    `max_discount` DECIMAL(10,2) NULL,
    `usage_limit` INT NULL,
    `usage_count` INT DEFAULT 0,
    `per_user_limit` INT DEFAULT 1,
    `applicable_villas` JSON NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_coupons_code` (`code`)
) ENGINE=InnoDB;

-- ================================================================
-- REVIEWS
-- ================================================================

CREATE TABLE `reviews` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NOT NULL,
    `booking_id` INT UNSIGNED NULL,
    `user_id` INT UNSIGNED NULL,
    `guest_name` VARCHAR(200) NOT NULL,
    `guest_email` VARCHAR(255) NULL,
    `rating` TINYINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NULL,
    `comment` TEXT NOT NULL,
    `cleanliness_rating` TINYINT NULL,
    `location_rating` TINYINT NULL,
    `value_rating` TINYINT NULL,
    `service_rating` TINYINT NULL,
    `photos` JSON NULL,
    `admin_reply` TEXT NULL,
    `admin_reply_at` DATETIME NULL,
    `is_verified` TINYINT(1) DEFAULT 0,
    `is_approved` TINYINT(1) DEFAULT 0,
    `is_featured` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_reviews_villa` (`villa_id`),
    INDEX `idx_reviews_approved` (`is_approved`)
) ENGINE=InnoDB;

-- ================================================================
-- HOUSEKEEPING
-- ================================================================

CREATE TABLE `housekeeping` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NOT NULL,
    `assigned_to` INT UNSIGNED NULL,
    `booking_id` INT UNSIGNED NULL,
    `task_type` ENUM('checkout_clean','deep_clean','inspection','turnover','maintenance') NOT NULL,
    `scheduled_date` DATE NOT NULL,
    `scheduled_time` TIME NULL,
    `status` ENUM('pending','in_progress','completed','cancelled') DEFAULT 'pending',
    `priority` ENUM('low','normal','high','urgent') DEFAULT 'normal',
    `notes` TEXT NULL,
    `checklist` JSON NULL,
    `completed_at` DATETIME NULL,
    `inspected_by` INT UNSIGNED NULL,
    `inspection_notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`),
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
    INDEX `idx_housekeeping_villa` (`villa_id`),
    INDEX `idx_housekeeping_date` (`scheduled_date`),
    INDEX `idx_housekeeping_status` (`status`)
) ENGINE=InnoDB;

-- ================================================================
-- MAINTENANCE
-- ================================================================

CREATE TABLE `maintenance` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NOT NULL,
    `reported_by` INT UNSIGNED NULL,
    `assigned_to` INT UNSIGNED NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `category` ENUM('plumbing','electrical','hvac','structural','appliance','furniture','pool','garden','other') NOT NULL,
    `priority` ENUM('low','normal','high','critical') DEFAULT 'normal',
    `status` ENUM('reported','assigned','in_progress','completed','cancelled') DEFAULT 'reported',
    `cost` DECIMAL(10,2) NULL,
    `photos` JSON NULL,
    `resolution_notes` TEXT NULL,
    `started_at` DATETIME NULL,
    `completed_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`),
    FOREIGN KEY (`reported_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_maintenance_villa` (`villa_id`),
    INDEX `idx_maintenance_status` (`status`)
) ENGINE=InnoDB;

-- ================================================================
-- NOTIFICATIONS
-- ================================================================

CREATE TABLE `notifications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `data` JSON NULL,
    `read_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_notifications_user` (`user_id`),
    INDEX `idx_notifications_read` (`read_at`)
) ENGINE=InnoDB;

CREATE TABLE `email_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `to_email` VARCHAR(255) NOT NULL,
    `to_name` VARCHAR(200) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    `template` VARCHAR(100) NULL,
    `status` ENUM('sent','failed','queued') DEFAULT 'queued',
    `error_message` TEXT NULL,
    `sent_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_email_logs_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `sms_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `phone` VARCHAR(20) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('sent','failed','queued') DEFAULT 'queued',
    `provider` VARCHAR(50) NULL,
    `reference_id` VARCHAR(100) NULL,
    `error_message` TEXT NULL,
    `sent_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- BLOG & CONTENT
-- ================================================================

CREATE TABLE `blog_categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `image` VARCHAR(255) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `blogs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED NULL,
    `author_id` INT UNSIGNED NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `excerpt` TEXT NULL,
    `content` LONGTEXT NOT NULL,
    `featured_image` VARCHAR(255) NULL,
    `tags` JSON NULL,
    `status` ENUM('draft','published','archived') DEFAULT 'draft',
    `published_at` DATETIME NULL,
    `view_count` INT UNSIGNED DEFAULT 0,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` TEXT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_blogs_slug` (`slug`),
    INDEX `idx_blogs_status` (`status`),
    INDEX `idx_blogs_published` (`published_at`)
) ENGINE=InnoDB;

-- ================================================================
-- GALLERY
-- ================================================================

CREATE TABLE `gallery` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `thumbnail_path` VARCHAR(255) NULL,
    `type` ENUM('photo','video','drone','360') DEFAULT 'photo',
    `category` VARCHAR(50) DEFAULT 'general',
    `villa_id` INT UNSIGNED NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE SET NULL,
    INDEX `idx_gallery_type` (`type`),
    INDEX `idx_gallery_category` (`category`)
) ENGINE=InnoDB;

-- ================================================================
-- FAQ
-- ================================================================

CREATE TABLE `faqs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(50) DEFAULT 'general',
    `question` TEXT NOT NULL,
    `answer` TEXT NOT NULL,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- CONTACT & SUPPORT
-- ================================================================

CREATE TABLE `contact_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('new','read','replied','archived') DEFAULT 'new',
    `replied_at` DATETIME NULL,
    `replied_by` INT UNSIGNED NULL,
    `reply_message` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `support_tickets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ticket_number` VARCHAR(20) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED NULL,
    `booking_id` INT UNSIGNED NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `category` VARCHAR(50) DEFAULT 'general',
    `priority` ENUM('low','normal','high','urgent') DEFAULT 'normal',
    `status` ENUM('open','in_progress','waiting','resolved','closed') DEFAULT 'open',
    `assigned_to` INT UNSIGNED NULL,
    `closed_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
    INDEX `idx_tickets_number` (`ticket_number`),
    INDEX `idx_tickets_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `support_ticket_replies` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `message` TEXT NOT NULL,
    `attachments` JSON NULL,
    `is_staff_reply` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ================================================================
-- NEWSLETTER
-- ================================================================

CREATE TABLE `newsletters` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(200) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `subscribed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` DATETIME NULL,
    `token` VARCHAR(255) NULL
) ENGINE=InnoDB;

-- ================================================================
-- TAXES & EXPENSES
-- ================================================================

CREATE TABLE `taxes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `rate` DECIMAL(5,2) NOT NULL,
    `type` ENUM('percentage','fixed') DEFAULT 'percentage',
    `is_active` TINYINT(1) DEFAULT 1,
    `apply_to` ENUM('all','villa','extras') DEFAULT 'all',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `expenses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT UNSIGNED NULL,
    `category` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'USD',
    `expense_date` DATE NOT NULL,
    `receipt` VARCHAR(255) NULL,
    `vendor` VARCHAR(200) NULL,
    `approved_by` INT UNSIGNED NULL,
    `status` ENUM('pending','approved','rejected') DEFAULT 'pending',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE SET NULL,
    INDEX `idx_expenses_date` (`expense_date`),
    INDEX `idx_expenses_category` (`category`)
) ENGINE=InnoDB;

-- ================================================================
-- ACTIVITY & AUDIT LOGS
-- ================================================================

CREATE TABLE `activity_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `module` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    `subject_type` VARCHAR(100) NULL,
    `subject_id` INT UNSIGNED NULL,
    `properties` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_activity_user` (`user_id`),
    INDEX `idx_activity_action` (`action`),
    INDEX `idx_activity_created` (`created_at`)
) ENGINE=InnoDB;

CREATE TABLE `audit_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `event` VARCHAR(50) NOT NULL,
    `table_name` VARCHAR(100) NOT NULL,
    `record_id` INT UNSIGNED NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_audit_table` (`table_name`),
    INDEX `idx_audit_event` (`event`)
) ENGINE=InnoDB;

CREATE TABLE `login_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `email` VARCHAR(255) NOT NULL,
    `status` ENUM('success','failed','locked') NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `location` VARCHAR(200) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_login_email` (`email`),
    INDEX `idx_login_created` (`created_at`)
) ENGINE=InnoDB;

-- ================================================================
-- WISHLISTS
-- ================================================================

CREATE TABLE `wishlists` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `villa_id` INT UNSIGNED NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`villa_id`) REFERENCES `villas`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_wishlist` (`user_id`, `villa_id`)
) ENGINE=InnoDB;

-- ================================================================
-- PAGES (CMS)
-- ================================================================

CREATE TABLE `pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT NULL,
    `featured_image` VARCHAR(255) NULL,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` TEXT NULL,
    `status` ENUM('draft','published') DEFAULT 'draft',
    `template` VARCHAR(50) DEFAULT 'default',
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- TESTIMONIALS
-- ================================================================

CREATE TABLE `testimonials` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `title` VARCHAR(200) NULL,
    `content` TEXT NOT NULL,
    `rating` TINYINT UNSIGNED DEFAULT 5,
    `photo` VARCHAR(255) NULL,
    `country` VARCHAR(100) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================================
-- TRANSLATIONS
-- ================================================================

CREATE TABLE `translations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `locale` VARCHAR(5) NOT NULL,
    `group` VARCHAR(50) NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_translation` (`locale`, `group`, `key`),
    INDEX `idx_translations_locale` (`locale`)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
