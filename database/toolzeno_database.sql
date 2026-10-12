-- ToolZeno Production Database Dump
-- Target Database: shebafin_tool
-- Generated: 2026-10-12 10:49:51
-- Compatible with: MySQL 8.0+ / MariaDB 10.6+

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Table: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tools
DROP TABLE IF EXISTS `tools`;
CREATE TABLE `tools` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `meta_title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_en` text COLLATE utf8mb4_unicode_ci,
  `meta_description_bn` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_handler` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tools.show',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` bigint unsigned NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tools_slug_unique` (`slug`),
  KEY `tools_category_id_foreign` (`category_id`),
  CONSTRAINT `tools_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: content_pages
DROP TABLE IF EXISTS `content_pages`;
CREATE TABLE `content_pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_en` text COLLATE utf8mb4_unicode_ci,
  `meta_description_bn` text COLLATE utf8mb4_unicode_ci,
  `body_en` longtext COLLATE utf8mb4_unicode_ci,
  `body_bn` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: ad_placements
DROP TABLE IF EXISTS `ad_placements`;
CREATE TABLE `ad_placements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slot_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ad_placements_slot_key_unique` (`slot_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: site_settings
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: audit_logs
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: media_assets
DROP TABLE IF EXISTS `media_assets`;
CREATE TABLE `media_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`) VALUES (1, 'Sojol', 'sojolstudent66@gmail.com', NULL, '$2y$12$980fW/GsksMbGkUMxq5lDOaZG61rhhCmLktj/MlXighVfZh2rPs7G', 'gUy6MUsdlgnWfNKHsoXyJrw8rkE0h5gZulxZcA5BbKCOvdDPF46oCV54xjMQ', '2026-10-12 08:43:46', '2026-10-12 08:43:46', 1);

-- Dumping data for table `categories`
INSERT INTO `categories` (`id`, `slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `icon`, `color`, `sort_order`, `is_active`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `created_at`, `updated_at`) VALUES (1, 'text', 'Text & Writing', 'টেক্সট এবং রাইটিং', 'Tools for counting, formatting, and converting text instantly.', 'টেক্সট গণনা, ফরম্যাটিং এবং কনভার্ট করার কার্যকরী টুলস।', 'document-text', 'indigo', 1, 1, NULL, NULL, NULL, NULL, '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `categories` (`id`, `slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `icon`, `color`, `sort_order`, `is_active`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `created_at`, `updated_at`) VALUES (2, 'calculators', 'Calculators', 'ক্যালকুলেটর', 'Accurate calculators for percentages, discounts, and finances.', 'শতকরা, ছাড় এবং আর্থিক হিসেবের নির্ভুল ক্যালকুলেটর।', 'calculator', 'emerald', 2, 1, NULL, NULL, NULL, NULL, '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `categories` (`id`, `slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `icon`, `color`, `sort_order`, `is_active`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `created_at`, `updated_at`) VALUES (3, 'developer', 'Developer Tools', 'ডেভেলপার টুলস', 'Formatters, validators, and essential utilities for programmers.', 'প্রোগ্রামারদের জন্য ফরম্যাটার, ভ্যালিডেটর এবং প্রয়োজনীয় ইউটিলিটি।', 'code-bracket', 'amber', 3, 1, NULL, NULL, NULL, NULL, '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `categories` (`id`, `slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `icon`, `color`, `sort_order`, `is_active`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `created_at`, `updated_at`) VALUES (4, 'productivity', 'Productivity', 'উৎপাদনশীলতা', 'Smart utilities to streamline daily digital workflow and tasks.', 'দৈনন্দিন ডিজিটাল কাজের গতি বাড়ানোর স্মার্ট ইউটিলিটি।', 'qr-code', 'sky', 4, 1, NULL, NULL, NULL, NULL, '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `categories` (`id`, `slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `icon`, `color`, `sort_order`, `is_active`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `created_at`, `updated_at`) VALUES (5, 'image', 'Image Tools', 'ইমেজ টুলস', 'Compress, resize, and convert images 100% in your browser.', 'ব্রাউজারেই নিরাপদে ছবি কম্প্রেস, রিসাইজ এবং কনভার্ট করুন।', 'photo', 'violet', 5, 1, NULL, NULL, NULL, NULL, '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `categories` (`id`, `slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `icon`, `color`, `sort_order`, `is_active`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `created_at`, `updated_at`) VALUES (6, 'pdf', 'PDF Utilities', 'পিডিএফ ইউটিলিটি', 'Convert, merge, and organize PDF documents with privacy.', 'ব্যক্তিগত গোপনীয়তা বজায় রেখে পিডিএফ ডকুমেন্ট কনভার্ট করুন।', 'document-duplicate', 'rose', 6, 1, NULL, NULL, NULL, NULL, '2026-10-12 07:50:33', '2026-10-12 07:50:33');

-- Dumping data for table `tools`
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (1, 'word-counter', 'text', 'Word & Character Counter', 'ওয়ার্ড ও ক্যারেক্টার কাউন্টার', 'Accurate real-time word counter with full Bangla Unicode support, reading time, and speaking time estimates.', 'বাংলা ইউনিকোড সাপোর্টেড রিয়েল-টাইম ওয়ার্ড কাউন্টার, ক্যারেক্টার গণনা ও রিডিং টাইম নির্দেশক।', 'Word & Character Counter — Free Online Text Analysis Tool', 'ওয়ার্ড ও ক্যারেক্টার কাউন্টার — ফ্রি অনলাইন টেক্সট বিশ্লেষণ টুল', 'Count words, characters, sentences, and paragraphs in English and Bangla instantly with zero server uploads.', 'ইংরেজি ও বাংলায় নির্ভুলভাবে শব্দ, অক্ষর এবং প্যারাগ্রাফ গণনা করুন সম্পূর্ণ বিনামূল্যে ও নিরাপদে।', 'text-quote', 'tools.word-counter', 'tools.show', 'active', 1, 1, 1, '[\"word count\",\"character count\",\"reading time\",\"bangla words\",\"text statistics\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (2, 'case-converter', 'text', 'Text Case Converter', 'টেক্সট কেস কনভার্টার', 'Easily transform text between UPPERCASE, lowercase, Title Case, camelCase, snake_case, and kebab-case.', 'যেকোনো লেখাকে আপারকেস, লোয়ারকেস, টাইটেল কেস, ক্যামেলকেস এবং স্নেক-কেসে রূপান্তর করুন।', 'Text Case Converter — Convert UPPERCASE, lowercase & Title Case', 'টেক্সট কেস কনভার্টার — আপারকেস, লোয়ারকেস ও টাইটেল কেস রূপান্তর', 'Free online text case conversion utility supporting 7 cases while preserving Indic and Bengali characters.', 'লেখা এক ক্লিকেই যেকোনো কেসে রূপান্তর করুন। সম্পূর্ণ ব্রাউজারেই কার্যকর এবং দ্রুত।', 'arrows-up-down', 'tools.case-converter', 'tools.show', 'active', 0, 1, 2, '[\"uppercase\",\"lowercase\",\"title case\",\"sentence case\",\"camelCase\",\"snake_case\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (3, 'percentage-calculator', 'calculators', 'Percentage & Discount Calculator', 'শতকরা ও ডিসকাউন্ট ক্যালকুলেটর', 'Calculate percentage of a number, percentage increase or decrease, and shop discounts in seconds.', 'যেকোনো সংখ্যার শতকরা মান, বৃদ্ধি বা হ্রাস এবং শপিং ডিসকাউন্ট সহজে ও দ্রুত হিসাব করুন।', 'Percentage & Discount Calculator — Fast Online Calculations', 'শতকরা ও ডিসকাউন্ট ক্যালকুলেটর — সহজ অনলাইন হিসাব', 'Calculate percentages, retail discounts, sales tax, and profit margins with instant interactive results.', 'দোকানের ডিসকাউন্ট, ট্যাক্স এবং শতকরা হিসাব নির্ভুলভাবে বের করুন কয়েক সেকেন্ডে।', 'calculator', 'tools.percentage-calculator', 'tools.show', 'active', 1, 1, 3, '[\"percentage\",\"discount\",\"percentage increase\",\"percent decrease\",\"price calculator\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (4, 'json-formatter', 'developer', 'JSON Formatter & Validator', 'জেসন ফরম্যাটার ও ভ্যালিডেটর', 'Format, validate, prettify, and minify your JSON data with clear syntax error detection and tree navigation.', 'জেসন ডেটা সুন্দরভাবে সাজান, যাচাই করুন ও মিনিফাই করুন। সিনট্যাক্স এরর চিহ্নিত করুন নিমেষেই।', 'JSON Formatter & Validator — Prettify, Minify & Inspect JSON', 'জেসন ফরম্যাটার ও ভ্যালিডেটর — জেসন ফরম্যাট ও যাচাই করুন', 'Clean, format, and debug JSON payloads directly in your browser. Complete client-side security.', 'ব্রাউজারেই সহজে এবং নিরাপদে জেসন কোড ফরম্যাট ও যাচাই করুন। কোন ডেটা সার্ভারে যায় না।', 'code-bracket', 'tools.json-formatter', 'tools.show', 'active', 1, 1, 4, '[\"json format\",\"json validator\",\"json prettify\",\"json minify\",\"beautify json\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (5, 'qr-generator', 'productivity', 'QR Code Generator', 'কিউআর কোড জেনারেটর', 'Create high-resolution QR codes for websites, WiFi networks, phone numbers, and plain text. Download as PNG or SVG.', 'ওয়েবসাইট, ওয়াইফাই এবং টেক্সটের জন্য হাই-রেজোলিউশন কিউআর কোড তৈরি ও ডাউনলোড করুন।', 'QR Code Generator — Free Custom QR Codes in PNG & SVG', 'কিউআর কোড জেনারেটর — ফ্রি পিএনজি ও এসভিজি কিউআর কোড তৈরি', 'Instant client-side QR code generator with custom error correction, colors, and SVG/PNG export.', 'বিনা মূল্যে যেকোনো লিংক বা টেক্সটের জন্য ঝকঝকে কিউআর কোড বানান এবং সেভ করুন।', 'qr-code', 'tools.qr-generator', 'tools.show', 'active', 1, 1, 5, '[\"qr code\",\"free qr generator\",\"create qr code\",\"download qr\",\"url qr code\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (6, 'image-compressor', 'image', 'Image Compressor', 'ইমেজ কম্প্রেসার', 'Compress JPG, PNG, and WebP images directly in your browser. Reduce file size up to 80% without losing visual clarity.', 'গুণগত মান ঠিক রেখে জেপিজি ও পিএনজি ছবির সাইজ শতকরা ৮০ ভাগ পর্যন্ত ছোট করুন ব্রাউজারেই।', 'Image Compressor — Reduce Photo Size Online Free', 'ইমেজ কম্প্রেসার — অনলাইনে ছবির সাইজ কমান সহজে', 'Fast, private client-side image compression. No file uploads, no wait times, and zero server storage.', 'ছবির সাইজ কমিয়ে নিন সহজেই। ব্রাউজারে প্রসেস হওয়ায় আপনার ছবি থাকবে শতভাগ সুরক্ষিত।', 'arrow-down-tray', 'tools.image-compressor', 'tools.show', 'active', 1, 1, 6, '[\"image compressor\",\"compress jpg\",\"reduce png size\",\"webp compression\",\"photo size reducer\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (7, 'image-resizer', 'image', 'Image Resizer', 'ইমেজ রিসাইজার', 'Change image dimensions by exact pixels or percentage with aspect ratio lock and format conversion.', 'ছবির দৈর্ঘ্য ও প্রস্থ ইচ্ছামতো পিক্সেল বা পার্সেন্টেজে পরিবর্তন করুন অ্যাসপেক্ট রেশিও অক্ষুণ্ণ রেখে।', 'Image Resizer — Change Image Dimensions Online', 'ইমেজ রিসাইজার — ছবির মাপ পরিবর্তন করুন সহজে', 'Resize photos in pixels or percentages with aspect ratio locking and export to WebP, JPEG, or PNG.', 'ছবি রিসাইজ করুন কয়েক সেকেন্ডে। পাসপোর্ট ছবি, সোশ্যাল মিডিয়া পোস্ট বা ফর্ম পূরণের জন্য উপযুক্ত।', 'arrows-pointing-out', 'tools.image-resizer', 'tools.show', 'active', 0, 0, 7, '[\"resize image\",\"change dimensions\",\"photo resizer\",\"aspect ratio resize\",\"convert image format\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `tools` (`id`, `slug`, `category_slug`, `name_en`, `name_bn`, `description_en`, `description_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `icon`, `view_handler`, `route_name`, `status`, `is_featured`, `is_popular`, `sort_order`, `tags`, `supported_locales`, `created_at`, `updated_at`) VALUES (8, 'image-to-pdf', 'pdf', 'Image to PDF Converter', 'ইমেজ টু পিডিএফ কনভার্টার', 'Combine multiple JPG, PNG, and WebP images into a single professional PDF document with custom margins and page sizes.', 'একাধিক ছবিকে একসাথে যুক্ত করে একটি পরিচ্ছন্ন পিডিএফ ফাইলে রূপান্তর ও ডাউনলোড করুন।', 'Image to PDF Converter — Convert Photos to PDF Document', 'ইমেজ টু পিডিএফ কনভার্টার — ছবি থেকে পিডিএফ তৈরি করুন', 'Combine multiple images into a standard A4 or Letter PDF document without uploading files to any server.', 'ছবি থেকে ডকুমেন্ট বা অ্যাসাইনমেন্টের জন্য পিডিএফ ফাইল তৈরি করুন বিনামূল্যে এবং সর্বোচ্চ নিরাপত্তায়।', 'document-duplicate', 'tools.image-to-pdf', 'tools.show', 'active', 1, 1, 8, '[\"image to pdf\",\"jpg to pdf\",\"png to pdf\",\"convert photo to pdf\",\"free pdf maker\"]', '[\"en\",\"bn\"]', '2026-10-12 07:50:33', '2026-10-12 07:50:33');

-- Dumping data for table `content_pages`
INSERT INTO `content_pages` (`id`, `slug`, `title_en`, `title_bn`, `body_en`, `body_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `status`, `published_at`, `created_at`, `updated_at`) VALUES (1, 'about', 'About ToolZeno', 'টুলজেনো সম্পর্কে', '## Our Mission

ToolZeno is built to deliver free, high-speed, and secure online utilities for users worldwide. We believe everyday tasks like counting words, converting text cases, calculating percentages, and processing images should be seamless, private, and accessible on any device.

## Client-Side First Philosophy

Unlike traditional web utilities that upload your sensitive documents, photos, or text to external cloud servers, ToolZeno executes operations directly inside your web browser whenever possible. Your confidential text, private photos, and financial calculations remain 100% on your device.', '## আমাদের লক্ষ্য

টুলজেনো বিশ্বের প্রতিটি মানুষের জন্য দ্রুত, সহজ ও সম্পূর্ণ সুরক্ষিত অনলাইন ইউটিলিটি টুলস প্রদান করার লক্ষ্যে তৈরি। শব্দ গণনা, টেক্সট কেস পরিবর্তন, ডিসকাউন্ট হিসাব কিংবা ছবি কনভার্ট করার মতো দৈনন্দিন কাজগুলো কোনো ঝামেলা ছাড়াই করার সুবিধা দিতে আমরা প্রতিশ্রুতিবদ্ধ।

## ব্রাউজার-ভিত্তিক গোপনীয়তা

আমাদের টুলগুলো সরাসরি আপনার ব্রাউজারের ভেতর কার্যকর হয়। আপনার কোনো ছবি, ডকুমেন্ট বা লেখা আমাদের সার্ভারে আপলোড হয় না, ফলে আপনার ব্যক্তিগত তথ্যের নিরাপত্তা থাকে সম্পূর্ণ অক্ষুণ্ণ।', 'About Us — ToolZeno Free Online Utilities', 'আমাদের সম্পর্কে — টুলজেনো অনলাইন ইউটিলিটি', 'Learn about ToolZeno, our mission to build fast, client-side, privacy-respecting online utilities.', 'টুলজেনোের লক্ষ্য, উদ্দেশ্য ও নিরাপদ ক্লায়েন্ট-সাইড টুলস সম্পর্কে বিস্তারিত জানুন।', 'published', '2026-10-12 07:50:33', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `content_pages` (`id`, `slug`, `title_en`, `title_bn`, `body_en`, `body_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `status`, `published_at`, `created_at`, `updated_at`) VALUES (2, 'contact', 'Contact Us', 'যোগাযোগ করুন', 'Have questions, suggestions, or tool requests? We\'d love to hear from you!

Email our support team directly at **support@example.com** or use the contact form on this page.', 'কোনো প্রশ্ন, মতামত বা নতুন টুলের অনুরোধ জানাতে আমাদের সাথে সরাসরি যোগাযোগ করুন।

আমাদের ইমেইল করতে পারেন: **support@example.com** অথবা নিচের ফরমটি পূরণ করুন।', 'Contact Us — ToolZeno Support & Feedback', 'যোগাযোগ — টুলজেনো সাপোর্ট ও ফিডব্যাক', 'Get in touch with the ToolZeno engineering and support team.', 'টুলজেনো টিমের সাথে যোগাযোগ করুন এবং আপনার মূল্যবান পরামর্শ দিন।', 'published', '2026-10-12 07:50:33', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `content_pages` (`id`, `slug`, `title_en`, `title_bn`, `body_en`, `body_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `status`, `published_at`, `created_at`, `updated_at`) VALUES (3, 'privacy-policy', 'Privacy Policy', 'প্রাইভেসি পলিসি (গোপনীয়তা নীতি)', '## 1. Information We Do Not Collect

ToolZeno is designed with a strict **privacy-first architecture**:

- **Zero File Retention**: Images, documents, and PDFs processed through our Image Compressor, Image Resizer, or Image to PDF tools are processed locally inside your web browser via HTML5 Canvas and WebAssembly. They are **never** uploaded to our servers or stored on disk.
- **Zero Text Storage**: Text inputs provided to the Word Counter, Case Converter, or JSON Validator remain on your device and are never recorded or logged.
- **No Invasive Tracking**: We do not sell, rent, or trade personal information to third parties.

## 2. Server Logs and Technical Data

Like most websites, our web server may log standard HTTP request metadata (such as IP address, user-agent, request timestamp, and requested URL) solely for diagnostic monitoring, security protection, and DDoS prevention.

## 3. Cookies and Local Storage

We use essential cookies strictly to maintain session integrity, CSRF security, and remember your language preference (`/en` or `/bn`). We do not use persistent cross-site tracking cookies.', '## ১. তথ্যের গোপনীয়তা রক্ষা

টুলজেনো আপনার গোপনীয়তাকে সর্বোচ্চ অগ্রাধিকার দেয়:

- **ফাইল আপলোড হয় না**: ইমেজ কম্প্রেসার, রিসাইজার এবং পিডিএফ কনভার্টারের সকল কাজ আপনার ডিভাইসের ব্রাউজারে সম্পন্ন হয়। কোনো ফাইল আমাদের সার্ভারে পাঠানো বা সংরক্ষণ করা হয় না।
- **টেক্সট সেভ হয় না**: ওয়ার্ড কাউন্টার বা জেসন ভ্যালিডেটরে লেখা কোনো টেক্সট আমরা সংরক্ষণ বা নজরদারি করি না।
- **তথ্য বিক্রি নয়**: আমরা কোনো ব্যবহারকারীর ব্যক্তিগত তথ্য কারো কাছে বিক্রি বা শেয়ার করি না।

## ২. কুকিজ এবং লোকাল স্টোরেজ

আমরা শুধুমাত্র সাইটের প্রাথমিক কার্যকারিতা এবং ভাষা পছন্দ (বাংলা/ইংরেজি) মনে রাখার জন্য প্রয়োজনীয় কুকিজ ব্যবহার করি।', 'Privacy Policy — ToolZeno Data Protection & Transparency', 'গোপনীয়তা নীতি — টুলজেনো প্রাইভেসি পলিসি', 'Read our transparent privacy policy explaining client-side processing and zero file storage.', 'আমাদের প্রাইভেসি পলিসি পড়ুন এবং জানুন কীভাবে আপনার ফাইল ও তথ্যের পূর্ণ নিরাপত্তা বজায় রাখা হয়।', 'published', '2026-10-12 07:50:33', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `content_pages` (`id`, `slug`, `title_en`, `title_bn`, `body_en`, `body_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `status`, `published_at`, `created_at`, `updated_at`) VALUES (4, 'terms-of-service', 'Terms of Service', 'ব্যবহারের শর্তাবলী', '## 1. Acceptance of Terms

By accessing and using ToolZeno, you agree to comply with and be bound by these Terms of Service.

## 2. Permitted Use

You may use our online tools for personal, educational, or commercial purposes free of charge. You agree not to abuse, disrupt, or attempt unauthorized access to our infrastructure through automated scraping, denial-of-service attacks, or malicious scripts.

## 3. Intellectual Property

All tools, branding, and user interface elements are the property of ToolZeno. Content and files processed by you remain exclusively your own intellectual property.', '## ১. শর্তাবলী গ্রহণ

টুলজেনো ওয়েবসাইট ব্যবহার করার মাধ্যমে আপনি আমাদের ব্যবহারের সকল শর্তাবলী মেনে নিতে সম্মতি প্রদান করছেন।

## ২. ব্যবহারের অনুমতি

আপনি ব্যক্তিগত, প্রাতিষ্ঠানিক বা বাণিজ্যিক কাজে আমাদের সকল ফ্রি টুল ব্যবহার করতে পারেন। তবে কোনো সাইবার আক্রমণ বা ক্ষতিকর কার্যকলাপে এটি ব্যবহার করা সম্পূর্ণ নিষিদ্ধ।

## ৩. বুদ্ধিবৃত্তিক অধিকার

আপনার প্রসেস করা ফাইল বা লেখার পূর্ণ মালিকানা কেবল আপনার নিজেরই থাকবে।', 'Terms of Service — ToolZeno Usage Agreement', 'ব্যবহারের শর্তাবলী — টুলজেনো টার্মস অফ সার্ভিস', 'Terms and conditions for utilizing ToolZeno free online web tools.', 'টুলজেনোের ওয়েবসাইট ও সেবা ব্যবহারের নিয়মাবলী ও শর্তসমূহ।', 'published', '2026-10-12 07:50:33', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `content_pages` (`id`, `slug`, `title_en`, `title_bn`, `body_en`, `body_bn`, `meta_title_en`, `meta_title_bn`, `meta_description_en`, `meta_description_bn`, `status`, `published_at`, `created_at`, `updated_at`) VALUES (5, 'disclaimer', 'Disclaimer', 'দাবিত্যাগ (Disclaimer)', '## General Information Disclaimer

The tools, calculations, and information provided on ToolZeno are for general informational and productivity purposes only. While we rigorously test algorithms for accuracy, calculations (such as percentages or financial discounts) should not be considered formal financial, legal, or professional advice.

## Limitation of Liability

ToolZeno is provided on an \'as is\' and \'as available\' basis without warranties of any kind. Users are advised to review converted files and calculation results before relying on them for critical operations.', '## সাধারণ দাবিত্যাগ

টুলজেনোের সকল হিসাব ও ফলাফল শুধুমাত্র সাধারণ ব্যবহার এবং সুবিধার জন্য প্রদান করা হয়। কোনো গুরুত্বপূর্ণ আর্থিক বা ব্যবসায়িক সিদ্ধান্তে চূড়ান্ত পদক্ষেপ নেওয়ার পূর্বে ফলাফল পুনরায় যাচাই করে নেওয়ার অনুরোধ করা হচ্ছে।

## দায়বদ্ধতার সীমাবদ্ধতা

আমাদের প্ল্যাটফর্ম কোনো প্রকার গ্যারান্টি ছাড়া সম্পূর্ণ উন্মুক্ত সেবা হিসেবে প্রদান করা হয়।', 'Disclaimer — ToolZeno Accuracy & Liability Notice', 'দাবিত্যাগ — টুলজেনো নির্ভুলতা ও দায়বদ্ধতা বিজ্ঞপ্তি', 'Read our general information and calculation accuracy disclaimer.', 'টুলজেনোের সেবার নির্ভুলতা ও তথ্য সংক্রান্ত সাধারণ দাবিত্যাগ বিজ্ঞপ্তি।', 'published', '2026-10-12 07:50:33', '2026-10-12 07:50:33', '2026-10-12 07:50:33');

-- Dumping data for table `ad_placements`
INSERT INTO `ad_placements` (`id`, `identifier`, `name`, `page_location`, `provider`, `slot_id`, `format`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES (1, 'header-leaderboard', 'Header Leaderboard (728x90)', 'all', 'adsense', '', 'horizontal', 0, 'Discreet leaderboard displayed above main content when enabled.', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `ad_placements` (`id`, `identifier`, `name`, `page_location`, `provider`, `slot_id`, `format`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES (2, 'tool-top', 'Tool Page Top Banner', 'tool-view', 'adsense', '', 'responsive', 0, 'Non-intrusive placement placed above tool workspace.', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `ad_placements` (`id`, `identifier`, `name`, `page_location`, `provider`, `slot_id`, `format`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES (3, 'tool-bottom', 'Tool Page Bottom Banner', 'tool-view', 'adsense', '', 'responsive', 0, 'Placement below related tools and tool instructions.', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `ad_placements` (`id`, `identifier`, `name`, `page_location`, `provider`, `slot_id`, `format`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES (4, 'footer-banner', 'Global Footer Banner', 'all', 'adsense', '', 'responsive', 0, 'Displayed just above the site footer.', '2026-10-12 07:50:33', '2026-10-12 07:50:33');

-- Dumping data for table `site_settings`
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (1, 'site_name', 'ToolZeno', 'general', 'string', 'Platform brand name', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (2, 'contact_email', 'support@example.com', 'general', 'string', 'Inquiry and feedback contact email', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (3, 'meta_description_default_en', 'Free, lightning-fast online tools for Bangladesh and worldwide. Text utilities, calculators, developer formats, and private file conversion.', 'seo', 'string', 'Default meta description in English', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (4, 'meta_description_default_bn', 'বাংলাদেশ ও বিশ্বের সবার জন্য ফ্রি ও সুরক্ষিত অনলাইন টুলস। ওয়ার্ড কাউন্টার, ক্যালকুলেটর, কিউআর জেনারেটর এবং ব্রাউজারে নিরাপদ ছবি রূপান্তর।', 'seo', 'string', 'Default meta description in Bangla', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (5, 'sitemap_enabled', '1', 'seo', 'boolean', 'Enable public XML sitemap generation', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (6, 'analytics_enabled', '0', 'analytics', 'boolean', 'Google Analytics 4 integration toggle (disabled by default)', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (7, 'analytics_measurement_id', '', 'analytics', 'string', 'Google Analytics 4 Measurement ID (e.g. G-XXXXXXXXXX)', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (8, 'adsense_enabled', '0', 'ads', 'boolean', 'Global advertising integration toggle (disabled by default)', '2026-10-12 07:50:33', '2026-10-12 07:50:33');
INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `description`, `created_at`, `updated_at`) VALUES (9, 'adsense_publisher_id', '', 'ads', 'string', 'AdSense Publisher ID (e.g. ca-pub-XXXXXXXXXXXXXXXX)', '2026-10-12 07:50:33', '2026-10-12 07:50:33');

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2026_10_12_140000_add_is_admin_to_users_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2026_10_12_140001_create_categories_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2026_10_12_140002_create_tools_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2026_10_12_140003_create_content_pages_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2026_10_12_140004_create_ad_placements_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2026_10_12_140005_create_site_settings_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10, '2026_10_12_140006_create_audit_logs_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12, '2026_10_12_140007_create_media_assets_table', 3);

SET FOREIGN_KEY_CHECKS = 1;
