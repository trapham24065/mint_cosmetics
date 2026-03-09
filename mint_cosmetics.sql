-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 06, 2026 at 09:14 AM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mint_cosmetics`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Capacity', '2024-11-12 12:06:55', NULL),
(4, 'efefe', '2025-08-24 09:02:12', '2025-08-24 09:02:12'),
(5, 'Trà Phạm', '2025-08-24 09:05:33', '2025-08-24 09:05:33'),
(7, 'Trà Phạm1212', '2025-09-03 22:28:03', '2025-09-03 22:28:03'),
(8, 'Trà Phạm2121212', '2025-09-03 22:28:07', '2025-09-03 22:28:07'),
(9, 'Trà Phạmddwwd121212', '2025-09-03 22:28:11', '2025-09-03 22:28:11'),
(10, 'Trà Phạm212121212', '2025-09-03 22:28:18', '2025-09-03 22:28:18'),
(11, 'Son YSL Slim Velvet Radical Matte Lipstick 1966 Rouge Libre – Màu Đỏ Gạch2121', '2025-09-03 22:28:23', '2025-09-03 22:28:23'),
(12, '2121212', '2025-09-03 22:28:27', '2025-09-03 22:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_category`
--

DROP TABLE IF EXISTS `attribute_category`;
CREATE TABLE IF NOT EXISTS `attribute_category` (
  `attribute_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`attribute_id`,`category_id`),
  KEY `attribute_category_category_id_foreign` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_category`
--

INSERT INTO `attribute_category` (`attribute_id`, `category_id`) VALUES
(1, 1),
(4, 1),
(5, 1),
(4, 7),
(1, 10),
(4, 10),
(5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

DROP TABLE IF EXISTS `attribute_values`;
CREATE TABLE IF NOT EXISTS `attribute_values` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_values_attribute_id_foreign` (`attribute_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `created_at`, `updated_at`) VALUES
(8, 5, 'scscscsc', '2025-08-24 09:08:44', '2025-08-29 09:06:51'),
(11, 4, 'cscacasc', '2025-08-27 01:13:56', '2025-08-27 01:13:56'),
(12, 5, 'dwdwd', '2025-08-29 09:06:51', '2025-08-29 09:06:51'),
(14, 12, 'ddwdw', '2025-10-27 11:28:29', '2025-10-27 11:32:12'),
(17, 12, 'ccscc', '2025-10-27 11:32:12', '2025-10-27 11:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_value_product_variant`
--

DROP TABLE IF EXISTS `attribute_value_product_variant`;
CREATE TABLE IF NOT EXISTS `attribute_value_product_variant` (
  `attribute_value_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`attribute_value_id`,`product_variant_id`),
  KEY `attribute_value_product_variant_product_variant_id_foreign` (`product_variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_value_product_variant`
--

INSERT INTO `attribute_value_product_variant` (`attribute_value_id`, `product_variant_id`) VALUES
(8, 3),
(11, 3),
(8, 6),
(11, 6),
(8, 7),
(11, 7),
(8, 66),
(11, 66),
(11, 67),
(12, 67),
(11, 74),
(11, 96),
(8, 112),
(11, 112),
(11, 113),
(12, 113),
(8, 126),
(11, 126),
(11, 127),
(12, 127);

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `content`, `image`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Test121212', 'test121212', '<p>Tr&agrave; Phạm test blog 1234568344343434<img style=\"width: min(526px, 100%); aspect-ratio: 526 / 512;\" src=\"https://ucarecdn.com/bb15d916-370b-43bf-abed-3bd524c5fbc9/-/preview/\" sizes=\"(min-width: 526px) 526px, 100vw\" srcset=\"https://ucarecdn.com/bb15d916-370b-43bf-abed-3bd524c5fbc9/-/resize/100x/ 100w,https://ucarecdn.com/bb15d916-370b-43bf-abed-3bd524c5fbc9/-/resize/200x/ 200w,https://ucarecdn.com/bb15d916-370b-43bf-abed-3bd524c5fbc9/-/resize/300x/ 300w,https://ucarecdn.com/bb15d916-370b-43bf-abed-3bd524c5fbc9/-/resize/500x/ 500w,https://ucarecdn.com/bb15d916-370b-43bf-abed-3bd524c5fbc9/-/preview/ 526w\"></p>', 'blog_images/gKs69M9aqYQxpirVp5vmpEf8mEOz0B6iEpqmRbhd.jpg', 1, '2025-11-01 14:55:46', '2025-11-01 14:55:47', '2025-11-01 14:55:47');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'son', 'son', 'brands/7uKgeRZQgnjCF2MQfLID2PdRVyJGOlsznhzKvlN5.webp', 1, '2025-08-26 18:13:07', '2025-08-28 06:30:26'),
(2, 'nước hoa', 'nuoc-hoa', 'brands/85JyoSeWaZiViusRMcNVTRxwCHrhJW8yA4vWKSzf.png', 1, '2025-08-28 06:08:44', '2025-08-28 06:08:55'),
(3, 'Trà Phạm', 'tra-pham', 'brands/aaks9mfNE1F3TRuoEMvpchbTMtbw2CTvKwKmDDqM.jpg', 1, '2025-09-05 02:29:01', '2025-09-05 02:29:01'),
(4, 'Trà Phạm1212', 'tra-pham1212', NULL, 1, '2025-09-05 02:29:12', '2025-09-05 02:29:12'),
(5, 'Trà Phạmddwwd121', 'tra-phamddwwd121', NULL, 1, '2025-09-05 02:29:15', '2025-09-05 02:29:15'),
(6, '121212', '121212', NULL, 1, '2025-09-05 02:29:19', '2025-09-05 02:29:19'),
(7, '1212121', '1212121', NULL, 1, '2025-09-05 02:29:24', '2025-09-05 02:29:24'),
(8, '12121212', '12121212', NULL, 1, '2025-09-05 02:29:29', '2025-09-05 02:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('mint-cosmetics-cache-all_settings', 'a:15:{s:9:\"site_name\";s:14:\"Mint Cosmetics\";s:13:\"contact_email\";s:25:\"contact@mintcosmetics.com\";s:13:\"contact_phone\";s:10:\"0123456789\";s:14:\"vietqr_bank_id\";s:6:\"970436\";s:17:\"vietqr_account_no\";s:10:\"1032850005\";s:13:\"vietqr_prefix\";s:2:\"DH\";s:14:\"mail_from_name\";s:14:\"Mint Cosmetics\";s:17:\"mail_from_address\";s:22:\"trapham24065@gmail.com\";s:9:\"site_logo\";s:53:\"settings/tOBorFKcqGUgT7FLhN7Xf7YjIilSqtcRFyKGf0ho.png\";s:11:\"mail_driver\";s:4:\"smtp\";s:9:\"mail_host\";s:14:\"smtp.gmail.com\";s:9:\"mail_port\";s:3:\"587\";s:13:\"mail_username\";s:22:\"trapham24065@gmail.com\";s:13:\"mail_password\";s:19:\"oogg cuuu yugg pjpw\";s:15:\"mail_encryption\";s:3:\"tls\";}', 2087622219),
('mint-cosmetics-cache-sitemap.xml', 's:4121:\"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n  <url>\r\n    <loc>http://mint-cosmetics.local</loc>\r\n    <lastmod>2026-03-03T13:40:23+07:00</lastmod>\r\n    <changefreq>daily</changefreq>\r\n    <priority>1.0</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/about-us</loc>\r\n    <lastmod>2026-03-03T13:40:23+07:00</lastmod>\r\n    <changefreq>monthly</changefreq>\r\n    <priority>0.5</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/contact</loc>\r\n    <lastmod>2026-03-03T13:40:23+07:00</lastmod>\r\n    <changefreq>monthly</changefreq>\r\n    <priority>0.5</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/blog</loc>\r\n    <lastmod>2026-03-03T13:40:23+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.6</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/shop</loc>\r\n    <lastmod>2026-03-03T13:40:23+07:00</lastmod>\r\n    <changefreq>daily</changefreq>\r\n    <priority>0.8</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/son-ysl-slim-velvet-radical-matte-lipstick-1966-rouge-libre-mau-do-gach</loc>\r\n    <lastmod>2026-02-25T23:47:12+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/tra-pham1212121</loc>\r\n    <lastmod>2026-02-25T23:47:38+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/free-shipping1212</loc>\r\n    <lastmod>2026-02-25T23:51:04+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/tra-pham1212</loc>\r\n    <lastmod>2026-02-25T23:48:02+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/test1234</loc>\r\n    <lastmod>2025-09-29T00:28:01+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/tra-pham1212121212</loc>\r\n    <lastmod>2026-02-25T23:49:42+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/san-pham-1</loc>\r\n    <lastmod>2026-02-25T23:46:48+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/tra-phamtest</loc>\r\n    <lastmod>2026-02-25T20:31:18+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/test123</loc>\r\n    <lastmod>2025-10-28T16:33:13+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/cscsc12</loc>\r\n    <lastmod>2026-01-25T16:54:12+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/cscsc</loc>\r\n    <lastmod>2026-02-25T17:47:25+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/scscscsc</loc>\r\n    <lastmod>2026-02-25T17:47:02+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/son-phan</loc>\r\n    <lastmod>2026-01-24T23:41:42+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/tra-pham12344532</loc>\r\n    <lastmod>2026-01-25T10:29:53+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n  <url>\r\n    <loc>http://mint-cosmetics.local/products/san-pham-test-lan-2</loc>\r\n    <lastmod>2026-01-25T15:36:22+07:00</lastmod>\r\n    <changefreq>weekly</changefreq>\r\n    <priority>0.7</priority>\r\n  </url>\r\n</urlset>\";', 1772521823),
('mint-cosmetics-cache-trapham24065@gmail.com|::1', 'i:1;', 1772283876),
('mint-cosmetics-cache-trapham24065@gmail.com|::1:timer', 'i:1772283876;', 1772283876);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_customer_variant_unique` (`customer_id`,`product_variant_id`),
  KEY `carts_product_variant_id_foreign` (`product_variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `active`, `created_at`, `updated_at`) VALUES
(1, 'lips tick', 'lips-tick', 'categories/2D7qJWWaVg0p7FIqKNpXiwiHEpmSCvllFNWJGt8K.jpg', 1, '2025-08-23 05:42:39', '2025-09-03 21:00:25'),
(7, 'Free Shipping12', 'free-shipping12', NULL, 1, '2025-08-23 07:56:11', '2025-08-23 07:56:11'),
(8, 'gdffff', 'gdffff', NULL, 1, '2025-08-23 08:03:36', '2025-08-23 08:03:36'),
(9, 'Trà Phạm12', 'tra-pham12', 'categories/T9hC9A5ZWdn9wY7mO5nzma7GFTUbedwBQJZiC8NY.jpg', 1, '2025-08-24 02:48:54', '2025-09-03 21:13:50'),
(10, 'Free Shipping1212', 'free-shipping1212', 'categories/IoKeUX6W6m4hqwX9GqCjeT39SdubuH0dDqy7WTHX.jpg', 1, '2025-08-27 01:22:37', '2025-09-03 21:00:37'),
(11, 'Trà Phạm1212', 'tra-pham1212', 'categories/rezpMA5r9Sxfxaxrw7nJnzasUKGM9ub4gBT7mTBB.jpg', 1, '2025-09-03 21:59:58', '2025-09-03 22:00:18'),
(12, 'Trà Phạ12121', 'tra-phamddwwduewewe', 'categories/R46uBzd9ubn4sXhogH4vCEtLFB4j3QFJCRWzrwAd.jpg', 1, '2025-09-03 22:04:25', '2025-09-03 22:06:34'),
(13, 'Trà Phạm121212', 'tra-pham121212', NULL, 1, '2025-09-03 22:16:04', '2025-09-03 22:16:04');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_keywords`
--

DROP TABLE IF EXISTS `chatbot_keywords`;
CREATE TABLE IF NOT EXISTS `chatbot_keywords` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `chatbot_reply_id` bigint UNSIGNED NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chatbot_keywords_chatbot_reply_id_foreign` (`chatbot_reply_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot_keywords`
--

INSERT INTO `chatbot_keywords` (`id`, `chatbot_reply_id`, `keyword`) VALUES
(1, 3, 'shipping'),
(2, 3, 'delivery'),
(3, 3, 'ship'),
(4, 3, 'freeship'),
(5, 4, 'return'),
(6, 4, 'exchange'),
(7, 4, 'refund'),
(8, 5, 'hi');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_replies`
--

DROP TABLE IF EXISTS `chatbot_replies`;
CREATE TABLE IF NOT EXISTS `chatbot_replies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot_replies`
--

INSERT INTO `chatbot_replies` (`id`, `topic`, `reply`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Shipping Policy', 'We offer free shipping for all orders over 500,000 VND.', 1, '2025-09-10 23:37:43', '2025-09-10 23:37:43'),
(4, 'Return Policy', 'You can return any item within 7 days of purchase, provided it is in its original condition. Please contact our support hotline to initiate a return.', 1, '2025-09-10 23:37:43', '2025-09-10 23:37:43'),
(5, 'Hi', 'Hi, I am the automatic chat bot of Mint Cosmetics shop.', 1, '2025-10-26 14:37:12', '2025-10-26 14:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_rules`
--

DROP TABLE IF EXISTS `chatbot_rules`;
CREATE TABLE IF NOT EXISTS `chatbot_rules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `chatbot_rules_keyword_unique` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot_rules`
--

INSERT INTO `chatbot_rules` (`id`, `keyword`, `reply`, `is_active`) VALUES
(1, 'Shipping policy', 'We offer free shipping on orders over 500,000 VND.', 1),
(2, 'Current promotions', 'There is currently a 10% discount on all lipsticks. Check it out!', 1),
(3, 'How to return goods?', 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--

DROP TABLE IF EXISTS `chat_conversations`;
CREATE TABLE IF NOT EXISTS `chat_conversations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `private` tinyint(1) NOT NULL DEFAULT '1',
  `direct_message` tinyint(1) NOT NULL DEFAULT '0',
  `data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `private`, `direct_message`, `data`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '[]', '2025-12-03 15:11:07', '2025-12-03 15:11:07'),
(2, 1, 1, '[]', '2025-12-03 15:42:03', '2025-12-03 17:27:19'),
(3, 1, 1, '[]', '2025-12-04 02:34:00', '2026-01-26 15:41:51'),
(4, 1, 1, '[]', '2025-12-04 16:39:05', '2025-12-04 16:39:35'),
(5, 1, 1, '[]', '2025-12-05 02:16:12', '2025-12-05 05:10:32'),
(6, 1, 1, '[]', '2025-12-08 05:56:22', '2025-12-08 07:03:15'),
(7, 1, 1, '[]', '2025-12-08 07:03:56', '2025-12-08 07:08:24'),
(8, 1, 1, '[]', '2025-12-08 07:09:50', '2025-12-08 09:36:38'),
(9, 1, 1, '[]', '2025-12-09 01:41:22', '2025-12-09 01:41:23'),
(10, 1, 1, '[]', '2025-12-18 09:59:59', '2025-12-18 10:00:10'),
(11, 1, 1, '[]', '2026-01-12 02:20:55', '2026-01-12 02:20:56'),
(12, 1, 1, '[]', '2026-01-26 15:37:02', '2026-01-26 15:39:36'),
(13, 1, 1, '[]', '2026-01-26 15:48:00', '2026-01-26 15:57:12'),
(14, 1, 1, '[]', '2026-02-03 03:32:52', '2026-02-03 03:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `participation_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_participation_id_foreign` (`participation_id`),
  KEY `chat_messages_conversation_id_foreign` (`conversation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `body`, `conversation_id`, `participation_id`, `type`, `data`, `created_at`, `updated_at`) VALUES
(1, 'hi', 1, 1, 'text', '[]', '2025-12-03 15:11:07', '2025-12-03 15:11:07'),
(2, 'hi', 2, 3, 'text', '[]', '2025-12-03 15:42:03', '2025-12-03 15:42:03'),
(3, 'hi', 2, 3, 'text', '[]', '2025-12-03 16:02:40', '2025-12-03 16:02:40'),
(4, 'hi', 2, 3, 'text', '[]', '2025-12-03 16:03:04', '2025-12-03 16:03:04'),
(5, 'mik là customer', 2, 3, 'text', '[]', '2025-12-03 16:04:28', '2025-12-03 16:04:28'),
(6, 'đwd', 2, 4, 'text', '[]', '2025-12-03 17:26:37', '2025-12-03 17:26:37'),
(7, 'hi', 2, 3, 'text', '[]', '2025-12-03 17:26:50', '2025-12-03 17:26:50'),
(8, 'dạ chào bạn', 2, 4, 'text', '[]', '2025-12-03 17:27:07', '2025-12-03 17:27:07'),
(9, 'vâng ạ', 2, 3, 'text', '[]', '2025-12-03 17:27:19', '2025-12-03 17:27:19'),
(10, 'hi', 3, 5, 'text', '[]', '2025-12-04 02:34:00', '2025-12-04 02:34:00'),
(11, 'hi', 3, 6, 'text', '[]', '2025-12-04 02:34:35', '2025-12-04 02:34:35'),
(12, 'chào bạn', 3, 6, 'text', '[]', '2025-12-04 02:34:40', '2025-12-04 02:34:40'),
(13, 'dạ mik là khách hàng, mik cần tư vấn', 3, 5, 'text', '[]', '2025-12-04 02:35:01', '2025-12-04 02:35:01'),
(14, 'bạn giúp mình được không', 3, 5, 'text', '[]', '2025-12-04 02:35:11', '2025-12-04 02:35:11'),
(15, 'vâng ạ , không biết bên mik như nào', 3, 6, 'text', '[]', '2025-12-04 02:35:25', '2025-12-04 02:35:25'),
(16, 'hi', 3, 6, 'text', '[]', '2025-12-04 03:42:54', '2025-12-04 03:42:54'),
(17, 'hi', 3, 6, 'text', '[]', '2025-12-04 04:30:39', '2025-12-04 04:30:39'),
(18, 'hiiii', 3, 5, 'text', '[]', '2025-12-04 04:31:14', '2025-12-04 04:31:14'),
(19, 'hi admin', 3, 5, 'text', '[]', '2025-12-04 07:24:26', '2025-12-04 07:24:26'),
(20, 'hi', 3, 6, 'text', '[]', '2025-12-04 07:37:48', '2025-12-04 07:37:48'),
(21, 'dạ bên mik chào bạn', 3, 6, 'text', '[]', '2025-12-04 07:38:16', '2025-12-04 07:38:16'),
(22, 'vâng ạ', 3, 5, 'text', '[]', '2025-12-04 08:13:02', '2025-12-04 08:13:02'),
(23, 'dạ', 3, 6, 'text', '[]', '2025-12-04 08:13:14', '2025-12-04 08:13:14'),
(24, 'dạ', 3, 5, 'text', '[]', '2025-12-04 08:13:20', '2025-12-04 08:13:20'),
(25, 'hi', 3, 6, 'text', '[]', '2025-12-04 08:45:25', '2025-12-04 08:45:25'),
(26, 'hi', 3, 5, 'text', '[]', '2025-12-04 08:45:28', '2025-12-04 08:45:28'),
(27, 'hiii', 3, 6, 'text', '[]', '2025-12-04 08:56:13', '2025-12-04 08:56:13'),
(28, 'hiii', 3, 5, 'text', '[]', '2025-12-04 08:56:19', '2025-12-04 08:56:19'),
(29, 'chào bạn', 3, 6, 'text', '[]', '2025-12-04 08:56:27', '2025-12-04 08:56:27'),
(30, 'chào admin', 3, 5, 'text', '[]', '2025-12-04 08:56:37', '2025-12-04 08:56:37'),
(31, 'ffff', 3, 6, 'text', '[]', '2025-12-04 08:56:43', '2025-12-04 08:56:43'),
(32, '1213', 3, 6, 'text', '[]', '2025-12-04 08:57:11', '2025-12-04 08:57:11'),
(33, '2345', 3, 5, 'text', '[]', '2025-12-04 08:57:16', '2025-12-04 08:57:16'),
(34, 'dạ', 3, 6, 'text', '[]', '2025-12-04 09:17:32', '2025-12-04 09:17:32'),
(35, 'dạ', 3, 5, 'text', '[]', '2025-12-04 09:17:38', '2025-12-04 09:17:38'),
(36, 'dạ', 3, 6, 'text', '[]', '2025-12-04 09:17:49', '2025-12-04 09:17:49'),
(37, 'dạ', 3, 5, 'text', '[]', '2025-12-04 09:17:53', '2025-12-04 09:17:53'),
(38, 'hello', 3, 5, 'text', '[]', '2025-12-04 09:24:55', '2025-12-04 09:24:55'),
(39, 'hi', 3, 6, 'text', '[]', '2025-12-04 09:31:18', '2025-12-04 09:31:18'),
(40, 'ho', 3, 5, 'text', '[]', '2025-12-04 09:31:27', '2025-12-04 09:31:27'),
(41, 'fefefef', 3, 6, 'text', '[]', '2025-12-04 09:31:48', '2025-12-04 09:31:48'),
(42, 'chào', 3, 6, 'text', '[]', '2025-12-04 09:32:10', '2025-12-04 09:32:10'),
(43, 'chào', 3, 5, 'text', '[]', '2025-12-04 09:32:16', '2025-12-04 09:32:16'),
(44, 'xin chào', 3, 6, 'text', '[]', '2025-12-04 09:32:23', '2025-12-04 09:32:23'),
(45, 'dạ chào bạn', 3, 5, 'text', '[]', '2025-12-04 09:33:56', '2025-12-04 09:33:56'),
(46, 'dạ', 3, 6, 'text', '[]', '2025-12-04 09:34:02', '2025-12-04 09:34:02'),
(47, 'chào bạn', 3, 6, 'text', '[]', '2025-12-04 09:38:26', '2025-12-04 09:38:26'),
(48, 'vâng ạ', 3, 5, 'text', '[]', '2025-12-04 09:38:36', '2025-12-04 09:38:36'),
(49, 'dạ', 3, 5, 'text', '[]', '2025-12-04 09:39:11', '2025-12-04 09:39:11'),
(50, 'dạ', 3, 6, 'text', '[]', '2025-12-04 13:11:41', '2025-12-04 13:11:41'),
(51, 'mik là admin', 3, 6, 'text', '[]', '2025-12-04 13:41:57', '2025-12-04 13:41:57'),
(52, 'dạ chào bạn', 3, 5, 'text', '[]', '2025-12-04 13:46:56', '2025-12-04 13:46:56'),
(53, 'hi', 3, 5, 'text', '[]', '2025-12-04 13:47:34', '2025-12-04 13:47:34'),
(54, 'dạ', 3, 6, 'text', '[]', '2025-12-04 13:58:30', '2025-12-04 13:58:30'),
(55, 'chào', 3, 5, 'text', '[]', '2025-12-04 14:07:54', '2025-12-04 14:07:54'),
(56, 'Shipping policy', 3, 5, 'text', '[]', '2025-12-04 14:13:15', '2025-12-04 14:13:15'),
(57, 'Current promotions', 3, 5, 'text', '[]', '2025-12-04 14:13:16', '2025-12-04 14:13:16'),
(58, 'hi', 3, 5, 'text', '[]', '2025-12-04 14:13:20', '2025-12-04 14:13:20'),
(59, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-04 14:13:30', '2025-12-04 14:13:30'),
(60, 'chào bạn', 3, 5, 'text', '[]', '2025-12-04 14:13:35', '2025-12-04 14:13:35'),
(61, 'chào bạn nha', 3, 5, 'text', '[]', '2025-12-04 14:43:19', '2025-12-04 14:43:19'),
(62, 'dạ', 3, 6, 'text', '[]', '2025-12-04 14:43:34', '2025-12-04 14:43:34'),
(63, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-04 15:59:17', '2025-12-04 15:59:17'),
(64, 'Current promotions', 3, 5, 'text', '[]', '2025-12-04 15:59:20', '2025-12-04 15:59:20'),
(65, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-04 16:01:13', '2025-12-04 16:01:13'),
(66, 'hi', 3, 5, 'text', '[]', '2025-12-04 16:08:51', '2025-12-04 16:08:51'),
(67, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-04 16:09:20', '2025-12-04 16:09:20'),
(68, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-04 16:21:44', '2025-12-04 16:21:44'),
(69, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-04 16:21:45', '2025-12-04 16:21:45'),
(70, 'chào', 3, 5, 'text', '[]', '2025-12-04 16:33:04', '2025-12-04 16:33:04'),
(71, 'Shipping policy', 3, 5, 'text', '[]', '2025-12-04 16:33:07', '2025-12-04 16:33:07'),
(72, 'Shipping policy', 3, 5, 'text', '[]', '2025-12-04 16:33:18', '2025-12-04 16:33:18'),
(73, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-04 16:38:35', '2025-12-04 16:38:35'),
(74, 'Current promotions', 4, 7, 'text', '[]', '2025-12-04 16:39:05', '2025-12-04 16:39:05'),
(75, 'hi', 4, 7, 'text', '[]', '2025-12-04 16:39:08', '2025-12-04 16:39:08'),
(76, 'hi', 4, 7, 'text', '[]', '2025-12-04 16:39:15', '2025-12-04 16:39:15'),
(77, 'chào bạn', 4, 8, 'text', '[]', '2025-12-04 16:39:29', '2025-12-04 16:39:29'),
(78, 'Shipping policy', 4, 7, 'text', '[]', '2025-12-04 16:39:35', '2025-12-04 16:39:35'),
(79, 'Shipping policy', 5, 9, 'text', '[]', '2025-12-05 02:16:13', '2025-12-05 02:16:13'),
(80, 'Current promotions', 5, 9, 'text', '[]', '2025-12-05 02:16:14', '2025-12-05 02:16:14'),
(81, 'How to return goods?', 5, 9, 'text', '[]', '2025-12-05 02:16:15', '2025-12-05 02:16:15'),
(82, 'Current promotions', 5, 9, 'text', '[]', '2025-12-05 05:10:32', '2025-12-05 05:10:32'),
(83, 'hi', 3, 5, 'text', '[]', '2025-12-05 05:19:07', '2025-12-05 05:19:07'),
(84, 'dạ', 3, 5, 'text', '[]', '2025-12-05 05:19:18', '2025-12-05 05:19:18'),
(85, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-05 09:17:26', '2025-12-05 09:17:26'),
(86, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-05 09:17:49', '2025-12-05 09:17:49'),
(87, 'Current promotions', 3, 5, 'text', '[]', '2025-12-05 09:17:53', '2025-12-05 09:17:53'),
(88, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-05 10:22:24', '2025-12-05 10:22:24'),
(89, 'Shipping policy', 3, 5, 'text', '[]', '2025-12-05 10:22:30', '2025-12-05 10:22:30'),
(90, 'hi', 3, 5, 'text', '[]', '2025-12-05 10:22:34', '2025-12-05 10:22:34'),
(91, 'hello', 3, 5, 'text', '[]', '2025-12-05 10:22:37', '2025-12-05 10:22:37'),
(92, 'hi', 3, 5, 'text', '[]', '2025-12-05 10:22:42', '2025-12-05 10:22:42'),
(93, 'hello', 3, 5, 'text', '[]', '2025-12-05 10:22:54', '2025-12-05 10:22:54'),
(94, 'hi lo', 3, 5, 'text', '[]', '2025-12-05 10:23:16', '2025-12-05 10:23:16'),
(95, 'Hi', 3, 5, 'text', '[]', '2025-12-05 10:33:05', '2025-12-05 10:33:05'),
(96, 'Shipping policy', 3, 5, 'text', '[]', '2025-12-05 10:47:53', '2025-12-05 10:47:53'),
(97, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-05 11:34:22', '2025-12-05 11:34:22'),
(98, 'hi', 3, 5, 'text', '[]', '2025-12-05 11:52:53', '2025-12-05 11:52:53'),
(99, 'Shipping policy', 3, 5, 'text', '[]', '2025-12-05 11:52:55', '2025-12-05 11:52:55'),
(100, 'Current promotions', 3, 5, 'text', '[]', '2025-12-05 11:52:59', '2025-12-05 11:52:59'),
(101, 'How to return goods?', 3, 5, 'text', '[]', '2025-12-05 11:53:01', '2025-12-05 11:53:01'),
(102, 'Shipping policy', 6, 11, 'text', '[]', '2025-12-08 05:56:22', '2025-12-08 05:56:22'),
(103, 'Shipping policy', 6, 11, 'text', '[]', '2025-12-08 06:51:42', '2025-12-08 06:51:42'),
(104, 'Current promotions', 6, 11, 'text', '[]', '2025-12-08 06:51:45', '2025-12-08 06:51:45'),
(105, 'Current promotions', 6, 11, 'text', '[]', '2025-12-08 06:51:51', '2025-12-08 06:51:51'),
(106, 'Shipping policy', 6, 11, 'text', '[]', '2025-12-08 06:51:58', '2025-12-08 06:51:58'),
(107, 'Current promotions', 6, 11, 'text', '[]', '2025-12-08 06:52:13', '2025-12-08 06:52:13'),
(108, 'How to return goods?', 6, 11, 'text', '[]', '2025-12-08 06:53:08', '2025-12-08 06:53:08'),
(109, 'Current promotions', 6, 11, 'text', '[]', '2025-12-08 07:00:27', '2025-12-08 07:00:27'),
(110, 'Shipping policy', 6, 11, 'text', '[]', '2025-12-08 07:00:28', '2025-12-08 07:00:28'),
(111, 'Shipping policy', 6, 11, 'text', '[]', '2025-12-08 07:03:15', '2025-12-08 07:03:15'),
(112, 'Current promotions', 7, 13, 'text', '[]', '2025-12-08 07:03:56', '2025-12-08 07:03:56'),
(113, 'Shipping policy', 7, 13, 'text', '[]', '2025-12-08 07:03:58', '2025-12-08 07:03:58'),
(114, 'How to return goods?', 7, 13, 'text', '[]', '2025-12-08 07:03:59', '2025-12-08 07:03:59'),
(115, 'xin chào', 7, 13, 'text', '[]', '2025-12-08 07:04:03', '2025-12-08 07:04:03'),
(116, 'Current promotions', 7, 13, 'text', '[]', '2025-12-08 07:04:11', '2025-12-08 07:04:11'),
(117, 'chào', 7, 14, 'text', '[]', '2025-12-08 07:05:04', '2025-12-08 07:05:04'),
(118, 'hi', 7, 14, 'text', '[]', '2025-12-08 07:05:12', '2025-12-08 07:05:12'),
(119, 'Shipping policy', 7, 13, 'text', '[]', '2025-12-08 07:06:02', '2025-12-08 07:06:02'),
(120, 'chào bạn', 7, 13, 'text', '[]', '2025-12-08 07:06:05', '2025-12-08 07:06:05'),
(121, 'dạ', 7, 14, 'text', '[]', '2025-12-08 07:06:20', '2025-12-08 07:06:20'),
(122, 'chào bạn', 7, 13, 'text', '[]', '2025-12-08 07:07:18', '2025-12-08 07:07:18'),
(123, 'dạ mik chào ạ', 7, 14, 'text', '[]', '2025-12-08 07:07:40', '2025-12-08 07:07:40'),
(124, 'vâng bạn có việc j', 7, 14, 'text', '[]', '2025-12-08 07:08:24', '2025-12-08 07:08:24'),
(125, 'Shipping policy', 8, 15, 'text', '[]', '2025-12-08 07:09:50', '2025-12-08 07:09:50'),
(126, 'Shipping policy', 8, 15, 'text', '[]', '2025-12-08 07:16:17', '2025-12-08 07:16:17'),
(127, 'Current promotions', 8, 15, 'text', '[]', '2025-12-08 07:16:34', '2025-12-08 07:16:34'),
(128, 'Current promotions', 8, 15, 'text', '[]', '2025-12-08 07:17:09', '2025-12-08 07:17:09'),
(129, 'Current promotions', 8, 15, 'text', '[]', '2025-12-08 09:36:37', '2025-12-08 09:36:37'),
(130, 'Shipping policy', 8, 15, 'text', '[]', '2025-12-08 09:36:38', '2025-12-08 09:36:38'),
(131, 'Shipping policy', 9, 17, 'text', '[]', '2025-12-09 01:41:23', '2025-12-09 01:41:23'),
(132, 'Shipping policy', 10, 19, 'text', '[]', '2025-12-18 10:00:00', '2025-12-18 10:00:00'),
(133, 'xin chào', 10, 20, 'text', '[]', '2025-12-18 10:00:09', '2025-12-18 10:00:09'),
(134, 'xin chào', 3, 5, 'text', '[]', '2025-12-18 10:00:44', '2025-12-18 10:00:44'),
(135, 'Current promotions', 11, 21, 'text', '[]', '2026-01-12 02:20:56', '2026-01-12 02:20:56'),
(136, 'Shipping policy', 12, 23, 'text', '[]', '2026-01-26 15:37:02', '2026-01-26 15:37:02'),
(137, 'Shipping policy', 12, 23, 'text', '[]', '2026-01-26 15:39:20', '2026-01-26 15:39:20'),
(138, 'Shipping policy', 12, 23, 'text', '[]', '2026-01-26 15:39:36', '2026-01-26 15:39:36'),
(139, 'We offer free shipping on orders over 500,000 VND.', 12, 24, 'text', '[]', '2026-01-26 15:39:36', '2026-01-26 15:39:36'),
(140, 'How to return goods?', 3, 5, 'text', '[]', '2026-01-26 15:39:48', '2026-01-26 15:39:48'),
(141, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 3, 6, 'text', '[]', '2026-01-26 15:39:48', '2026-01-26 15:39:48'),
(142, 'xin chào', 3, 5, 'text', '[]', '2026-01-26 15:39:53', '2026-01-26 15:39:53'),
(143, 'Xin lỗi, hiện tại admin đang bận. Bạn có thể chọn một trong các câu hỏi thường gặp bên dưới để được trả lời ngay nhé! 😊', 3, 6, 'text', '[]', '2026-01-26 15:40:23', '2026-01-26 15:40:23'),
(144, 'shipping policy', 3, 5, 'text', '[]', '2026-01-26 15:40:40', '2026-01-26 15:40:40'),
(145, 'Xin lỗi, hiện tại admin đang bận. Bạn có thể chọn một trong các câu hỏi thường gặp bên dưới để được trả lời ngay nhé! 😊', 3, 6, 'text', '[]', '2026-01-26 15:41:10', '2026-01-26 15:41:10'),
(146, 'Current promotions', 3, 5, 'text', '[]', '2026-01-26 15:41:16', '2026-01-26 15:41:16'),
(147, 'There is currently a 10% discount on all lipsticks. Check it out!', 3, 6, 'text', '[]', '2026-01-26 15:41:16', '2026-01-26 15:41:16'),
(148, 'Current promotions', 3, 5, 'text', '[]', '2026-01-26 15:41:21', '2026-01-26 15:41:21'),
(149, 'Xin lỗi, hiện tại admin đang bận. Bạn có thể chọn một trong các câu hỏi thường gặp bên dưới để được trả lời ngay nhé! 😊', 3, 6, 'text', '[]', '2026-01-26 15:41:51', '2026-01-26 15:41:51'),
(150, 'Shipping policy', 13, 25, 'text', '[]', '2026-01-26 15:48:00', '2026-01-26 15:48:00'),
(151, 'We offer free shipping on orders over 500,000 VND.', 13, 26, 'text', '[]', '2026-01-26 15:48:00', '2026-01-26 15:48:00'),
(152, 'Current promotions', 13, 25, 'text', '[]', '2026-01-26 15:48:02', '2026-01-26 15:48:02'),
(153, 'There is currently a 10% discount on all lipsticks. Check it out!', 13, 26, 'text', '[]', '2026-01-26 15:48:02', '2026-01-26 15:48:02'),
(154, 'hi', 13, 25, 'text', '[]', '2026-01-26 15:48:05', '2026-01-26 15:48:05'),
(155, 'Sorry, the admin is busy right now. You can choose one of the frequently asked questions below to get an immediate answer!😊', 13, 26, 'text', '[]', '2026-01-26 15:48:36', '2026-01-26 15:48:36'),
(156, 'Current promotions', 13, 25, 'text', '[]', '2026-01-26 15:51:20', '2026-01-26 15:51:20'),
(157, 'There is currently a 10% discount on all lipsticks. Check it out!', 13, 26, 'text', '[]', '2026-01-26 15:51:20', '2026-01-26 15:51:20'),
(158, 'Shipping policy', 13, 25, 'text', '[]', '2026-01-26 15:57:12', '2026-01-26 15:57:12'),
(159, 'We offer free shipping on orders over 500,000 VND.', 13, 26, 'text', '[]', '2026-01-26 15:57:12', '2026-01-26 15:57:12'),
(160, 'Shipping policy', 14, 27, 'text', '[]', '2026-02-03 03:32:52', '2026-02-03 03:32:52'),
(161, 'We offer free shipping on orders over 500,000 VND.', 14, 28, 'text', '[]', '2026-02-03 03:32:52', '2026-02-03 03:32:52'),
(162, 'hi', 14, 27, 'text', '[]', '2026-02-03 03:32:56', '2026-02-03 03:32:56'),
(163, 'hello', 14, 28, 'text', '[]', '2026-02-03 03:33:08', '2026-02-03 03:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_notifications`
--

DROP TABLE IF EXISTS `chat_message_notifications`;
CREATE TABLE IF NOT EXISTS `chat_message_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` bigint UNSIGNED NOT NULL,
  `messageable_id` bigint UNSIGNED NOT NULL,
  `messageable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `participation_id` bigint UNSIGNED NOT NULL,
  `is_seen` tinyint(1) NOT NULL DEFAULT '0',
  `is_sender` tinyint(1) NOT NULL DEFAULT '0',
  `flagged` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `participation_message_index` (`participation_id`,`message_id`),
  KEY `chat_message_notifications_message_id_foreign` (`message_id`),
  KEY `chat_message_notifications_conversation_id_foreign` (`conversation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=327 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_message_notifications`
--

INSERT INTO `chat_message_notifications` (`id`, `message_id`, `messageable_id`, `messageable_type`, `conversation_id`, `participation_id`, `is_seen`, `is_sender`, `flagged`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'App\\Models\\User', 1, 2, 0, 0, 0, '2025-12-03 15:11:07', NULL, NULL),
(2, 1, 2, 'App\\Models\\Guest', 1, 1, 1, 1, 0, '2025-12-03 15:11:07', NULL, NULL),
(3, 2, 1, 'App\\Models\\User', 2, 4, 0, 0, 0, '2025-12-03 15:42:03', NULL, NULL),
(4, 2, 3, 'App\\Models\\Guest', 2, 3, 1, 1, 0, '2025-12-03 15:42:03', NULL, NULL),
(5, 3, 1, 'App\\Models\\User', 2, 4, 0, 0, 0, '2025-12-03 16:02:40', NULL, NULL),
(6, 3, 3, 'App\\Models\\Guest', 2, 3, 1, 1, 0, '2025-12-03 16:02:40', NULL, NULL),
(7, 4, 1, 'App\\Models\\User', 2, 4, 0, 0, 0, '2025-12-03 16:03:04', NULL, NULL),
(8, 4, 3, 'App\\Models\\Guest', 2, 3, 1, 1, 0, '2025-12-03 16:03:04', NULL, NULL),
(9, 5, 1, 'App\\Models\\User', 2, 4, 0, 0, 0, '2025-12-03 16:04:28', NULL, NULL),
(10, 5, 3, 'App\\Models\\Guest', 2, 3, 1, 1, 0, '2025-12-03 16:04:28', NULL, NULL),
(11, 6, 1, 'App\\Models\\User', 2, 4, 1, 1, 0, '2025-12-03 17:26:37', NULL, NULL),
(12, 6, 3, 'App\\Models\\Guest', 2, 3, 0, 0, 0, '2025-12-03 17:26:37', NULL, NULL),
(13, 7, 1, 'App\\Models\\User', 2, 4, 0, 0, 0, '2025-12-03 17:26:50', NULL, NULL),
(14, 7, 3, 'App\\Models\\Guest', 2, 3, 1, 1, 0, '2025-12-03 17:26:50', NULL, NULL),
(15, 8, 1, 'App\\Models\\User', 2, 4, 1, 1, 0, '2025-12-03 17:27:07', NULL, NULL),
(16, 8, 3, 'App\\Models\\Guest', 2, 3, 0, 0, 0, '2025-12-03 17:27:07', NULL, NULL),
(17, 9, 1, 'App\\Models\\User', 2, 4, 0, 0, 0, '2025-12-03 17:27:19', NULL, NULL),
(18, 9, 3, 'App\\Models\\Guest', 2, 3, 1, 1, 0, '2025-12-03 17:27:19', NULL, NULL),
(19, 10, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 02:34:00', NULL, NULL),
(20, 10, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 02:34:00', NULL, NULL),
(21, 11, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 02:34:35', NULL, NULL),
(22, 11, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 02:34:35', NULL, NULL),
(23, 12, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 02:34:40', NULL, NULL),
(24, 12, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 02:34:40', NULL, NULL),
(25, 13, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 02:35:01', NULL, NULL),
(26, 13, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 02:35:01', NULL, NULL),
(27, 14, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 02:35:11', NULL, NULL),
(28, 14, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 02:35:11', NULL, NULL),
(29, 15, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 02:35:25', NULL, NULL),
(30, 15, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 02:35:25', NULL, NULL),
(31, 16, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 03:42:54', NULL, NULL),
(32, 16, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 03:42:54', NULL, NULL),
(33, 17, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 04:30:39', NULL, NULL),
(34, 17, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 04:30:39', NULL, NULL),
(35, 18, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 04:31:14', NULL, NULL),
(36, 18, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 04:31:14', NULL, NULL),
(37, 19, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 07:24:26', NULL, NULL),
(38, 19, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 07:24:26', NULL, NULL),
(39, 20, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 07:37:48', NULL, NULL),
(40, 20, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 07:37:48', NULL, NULL),
(41, 21, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 07:38:16', NULL, NULL),
(42, 21, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 07:38:16', NULL, NULL),
(43, 22, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 08:13:02', NULL, NULL),
(44, 22, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 08:13:02', NULL, NULL),
(45, 23, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 08:13:14', NULL, NULL),
(46, 23, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 08:13:14', NULL, NULL),
(47, 24, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 08:13:20', NULL, NULL),
(48, 24, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 08:13:20', NULL, NULL),
(49, 25, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 08:45:25', NULL, NULL),
(50, 25, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 08:45:25', NULL, NULL),
(51, 26, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 08:45:28', NULL, NULL),
(52, 26, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 08:45:28', NULL, NULL),
(53, 27, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 08:56:13', NULL, NULL),
(54, 27, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 08:56:13', NULL, NULL),
(55, 28, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 08:56:19', NULL, NULL),
(56, 28, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 08:56:19', NULL, NULL),
(57, 29, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 08:56:27', NULL, NULL),
(58, 29, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 08:56:27', NULL, NULL),
(59, 30, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 08:56:37', NULL, NULL),
(60, 30, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 08:56:37', NULL, NULL),
(61, 31, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 08:56:43', NULL, NULL),
(62, 31, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 08:56:43', NULL, NULL),
(63, 32, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 08:57:11', NULL, NULL),
(64, 32, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 08:57:11', NULL, NULL),
(65, 33, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 08:57:16', NULL, NULL),
(66, 33, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 08:57:16', NULL, NULL),
(67, 34, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:17:32', NULL, NULL),
(68, 34, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:17:32', NULL, NULL),
(69, 35, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:17:38', NULL, NULL),
(70, 35, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:17:38', NULL, NULL),
(71, 36, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:17:49', NULL, NULL),
(72, 36, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:17:49', NULL, NULL),
(73, 37, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:17:53', NULL, NULL),
(74, 37, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:17:53', NULL, NULL),
(75, 38, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:24:55', NULL, NULL),
(76, 38, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:24:55', NULL, NULL),
(77, 39, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:31:18', NULL, NULL),
(78, 39, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:31:18', NULL, NULL),
(79, 40, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:31:27', NULL, NULL),
(80, 40, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:31:27', NULL, NULL),
(81, 41, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:31:48', NULL, NULL),
(82, 41, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:31:48', NULL, NULL),
(83, 42, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:32:10', NULL, NULL),
(84, 42, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:32:10', NULL, NULL),
(85, 43, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:32:16', NULL, NULL),
(86, 43, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:32:16', NULL, NULL),
(87, 44, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:32:23', NULL, NULL),
(88, 44, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:32:23', NULL, NULL),
(89, 45, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:33:56', NULL, NULL),
(90, 45, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:33:56', NULL, NULL),
(91, 46, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:34:02', NULL, NULL),
(92, 46, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:34:02', NULL, NULL),
(93, 47, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 09:38:26', NULL, NULL),
(94, 47, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 09:38:26', NULL, NULL),
(95, 48, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:38:36', NULL, NULL),
(96, 48, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:38:36', NULL, NULL),
(97, 49, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 09:39:11', NULL, NULL),
(98, 49, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 09:39:11', NULL, NULL),
(99, 50, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 13:11:41', NULL, NULL),
(100, 50, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 13:11:41', NULL, NULL),
(101, 51, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 13:41:57', NULL, NULL),
(102, 51, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 13:41:57', NULL, NULL),
(103, 52, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 13:46:56', NULL, NULL),
(104, 52, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 13:46:56', NULL, NULL),
(105, 53, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 13:47:34', NULL, NULL),
(106, 53, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 13:47:34', NULL, NULL),
(107, 54, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 13:58:30', NULL, NULL),
(108, 54, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 13:58:30', NULL, NULL),
(109, 55, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 14:07:54', NULL, NULL),
(110, 55, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 14:07:54', NULL, NULL),
(111, 56, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 14:13:15', NULL, NULL),
(112, 56, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 14:13:15', NULL, NULL),
(113, 57, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 14:13:16', NULL, NULL),
(114, 57, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 14:13:16', NULL, NULL),
(115, 58, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 14:13:20', NULL, NULL),
(116, 58, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 14:13:20', NULL, NULL),
(117, 59, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 14:13:30', NULL, NULL),
(118, 59, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 14:13:30', NULL, NULL),
(119, 60, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 14:13:35', NULL, NULL),
(120, 60, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 14:13:35', NULL, NULL),
(121, 61, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 14:43:19', NULL, NULL),
(122, 61, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 14:43:19', NULL, NULL),
(123, 62, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2025-12-04 14:43:34', NULL, NULL),
(124, 62, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2025-12-04 14:43:34', NULL, NULL),
(125, 63, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 15:59:17', NULL, NULL),
(126, 63, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 15:59:17', NULL, NULL),
(127, 64, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 15:59:20', NULL, NULL),
(128, 64, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 15:59:20', NULL, NULL),
(129, 65, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:01:13', NULL, NULL),
(130, 65, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:01:13', NULL, NULL),
(131, 66, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:08:51', NULL, NULL),
(132, 66, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:08:51', NULL, NULL),
(133, 67, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:09:20', NULL, NULL),
(134, 67, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:09:20', NULL, NULL),
(135, 68, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:21:44', NULL, NULL),
(136, 68, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:21:44', NULL, NULL),
(137, 69, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:21:45', NULL, NULL),
(138, 69, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:21:45', NULL, NULL),
(139, 70, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:33:04', NULL, NULL),
(140, 70, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:33:04', NULL, NULL),
(141, 71, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:33:07', NULL, NULL),
(142, 71, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:33:07', NULL, NULL),
(143, 72, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:33:18', NULL, NULL),
(144, 72, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:33:18', NULL, NULL),
(145, 73, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-04 16:38:35', NULL, NULL),
(146, 73, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-04 16:38:35', NULL, NULL),
(147, 74, 1, 'App\\Models\\User', 4, 8, 0, 0, 0, '2025-12-04 16:39:05', NULL, NULL),
(148, 74, 13, 'App\\Models\\Guest', 4, 7, 1, 1, 0, '2025-12-04 16:39:05', NULL, NULL),
(149, 75, 1, 'App\\Models\\User', 4, 8, 0, 0, 0, '2025-12-04 16:39:08', NULL, NULL),
(150, 75, 13, 'App\\Models\\Guest', 4, 7, 1, 1, 0, '2025-12-04 16:39:08', NULL, NULL),
(151, 76, 1, 'App\\Models\\User', 4, 8, 0, 0, 0, '2025-12-04 16:39:15', NULL, NULL),
(152, 76, 13, 'App\\Models\\Guest', 4, 7, 1, 1, 0, '2025-12-04 16:39:15', NULL, NULL),
(153, 77, 1, 'App\\Models\\User', 4, 8, 1, 1, 0, '2025-12-04 16:39:29', NULL, NULL),
(154, 77, 13, 'App\\Models\\Guest', 4, 7, 0, 0, 0, '2025-12-04 16:39:29', NULL, NULL),
(155, 78, 1, 'App\\Models\\User', 4, 8, 0, 0, 0, '2025-12-04 16:39:35', NULL, NULL),
(156, 78, 13, 'App\\Models\\Guest', 4, 7, 1, 1, 0, '2025-12-04 16:39:35', NULL, NULL),
(157, 79, 1, 'App\\Models\\User', 5, 10, 0, 0, 0, '2025-12-05 02:16:13', NULL, NULL),
(158, 79, 14, 'App\\Models\\Guest', 5, 9, 1, 1, 0, '2025-12-05 02:16:13', NULL, NULL),
(159, 80, 1, 'App\\Models\\User', 5, 10, 0, 0, 0, '2025-12-05 02:16:14', NULL, NULL),
(160, 80, 14, 'App\\Models\\Guest', 5, 9, 1, 1, 0, '2025-12-05 02:16:14', NULL, NULL),
(161, 81, 1, 'App\\Models\\User', 5, 10, 0, 0, 0, '2025-12-05 02:16:15', NULL, NULL),
(162, 81, 14, 'App\\Models\\Guest', 5, 9, 1, 1, 0, '2025-12-05 02:16:15', NULL, NULL),
(163, 82, 1, 'App\\Models\\User', 5, 10, 0, 0, 0, '2025-12-05 05:10:32', NULL, NULL),
(164, 82, 14, 'App\\Models\\Guest', 5, 9, 1, 1, 0, '2025-12-05 05:10:32', NULL, NULL),
(165, 83, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 05:19:07', NULL, NULL),
(166, 83, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 05:19:07', NULL, NULL),
(167, 84, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 05:19:18', NULL, NULL),
(168, 84, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 05:19:18', NULL, NULL),
(169, 85, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 09:17:26', NULL, NULL),
(170, 85, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 09:17:26', NULL, NULL),
(171, 86, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 09:17:49', NULL, NULL),
(172, 86, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 09:17:49', NULL, NULL),
(173, 87, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 09:17:53', NULL, NULL),
(174, 87, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 09:17:53', NULL, NULL),
(175, 88, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:22:24', NULL, NULL),
(176, 88, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:22:24', NULL, NULL),
(177, 89, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:22:30', NULL, NULL),
(178, 89, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:22:30', NULL, NULL),
(179, 90, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:22:34', NULL, NULL),
(180, 90, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:22:34', NULL, NULL),
(181, 91, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:22:37', NULL, NULL),
(182, 91, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:22:37', NULL, NULL),
(183, 92, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:22:42', NULL, NULL),
(184, 92, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:22:42', NULL, NULL),
(185, 93, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:22:54', NULL, NULL),
(186, 93, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:22:54', NULL, NULL),
(187, 94, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:23:16', NULL, NULL),
(188, 94, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:23:16', NULL, NULL),
(189, 95, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:33:05', NULL, NULL),
(190, 95, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:33:05', NULL, NULL),
(191, 96, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 10:47:53', NULL, NULL),
(192, 96, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 10:47:53', NULL, NULL),
(193, 97, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 11:34:22', NULL, NULL),
(194, 97, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 11:34:22', NULL, NULL),
(195, 98, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 11:52:53', NULL, NULL),
(196, 98, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 11:52:53', NULL, NULL),
(197, 99, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 11:52:55', NULL, NULL),
(198, 99, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 11:52:55', NULL, NULL),
(199, 100, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 11:52:59', NULL, NULL),
(200, 100, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 11:52:59', NULL, NULL),
(201, 101, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-05 11:53:01', NULL, NULL),
(202, 101, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-05 11:53:01', NULL, NULL),
(203, 102, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 05:56:22', NULL, NULL),
(204, 102, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 05:56:22', NULL, NULL),
(205, 103, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 06:51:42', NULL, NULL),
(206, 103, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 06:51:42', NULL, NULL),
(207, 104, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 06:51:45', NULL, NULL),
(208, 104, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 06:51:45', NULL, NULL),
(209, 105, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 06:51:51', NULL, NULL),
(210, 105, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 06:51:51', NULL, NULL),
(211, 106, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 06:51:58', NULL, NULL),
(212, 106, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 06:51:58', NULL, NULL),
(213, 107, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 06:52:13', NULL, NULL),
(214, 107, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 06:52:13', NULL, NULL),
(215, 108, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 06:53:08', NULL, NULL),
(216, 108, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 06:53:08', NULL, NULL),
(217, 109, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 07:00:27', NULL, NULL),
(218, 109, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 07:00:27', NULL, NULL),
(219, 110, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 07:00:28', NULL, NULL),
(220, 110, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 07:00:28', NULL, NULL),
(221, 111, 1, 'App\\Models\\User', 6, 12, 0, 0, 0, '2025-12-08 07:03:15', NULL, NULL),
(222, 111, 17, 'App\\Models\\Guest', 6, 11, 1, 1, 0, '2025-12-08 07:03:15', NULL, NULL),
(223, 112, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:03:56', NULL, NULL),
(224, 112, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:03:56', NULL, NULL),
(225, 113, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:03:58', NULL, NULL),
(226, 113, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:03:58', NULL, NULL),
(227, 114, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:03:59', NULL, NULL),
(228, 114, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:03:59', NULL, NULL),
(229, 115, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:04:03', NULL, NULL),
(230, 115, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:04:03', NULL, NULL),
(231, 116, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:04:11', NULL, NULL),
(232, 116, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:04:11', NULL, NULL),
(233, 117, 1, 'App\\Models\\User', 7, 14, 1, 1, 0, '2025-12-08 07:05:04', NULL, NULL),
(234, 117, 18, 'App\\Models\\Guest', 7, 13, 0, 0, 0, '2025-12-08 07:05:04', NULL, NULL),
(235, 118, 1, 'App\\Models\\User', 7, 14, 1, 1, 0, '2025-12-08 07:05:12', NULL, NULL),
(236, 118, 18, 'App\\Models\\Guest', 7, 13, 0, 0, 0, '2025-12-08 07:05:12', NULL, NULL),
(237, 119, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:06:02', NULL, NULL),
(238, 119, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:06:02', NULL, NULL),
(239, 120, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:06:05', NULL, NULL),
(240, 120, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:06:05', NULL, NULL),
(241, 121, 1, 'App\\Models\\User', 7, 14, 1, 1, 0, '2025-12-08 07:06:20', NULL, NULL),
(242, 121, 18, 'App\\Models\\Guest', 7, 13, 0, 0, 0, '2025-12-08 07:06:20', NULL, NULL),
(243, 122, 1, 'App\\Models\\User', 7, 14, 0, 0, 0, '2025-12-08 07:07:18', NULL, NULL),
(244, 122, 18, 'App\\Models\\Guest', 7, 13, 1, 1, 0, '2025-12-08 07:07:18', NULL, NULL),
(245, 123, 1, 'App\\Models\\User', 7, 14, 1, 1, 0, '2025-12-08 07:07:40', NULL, NULL),
(246, 123, 18, 'App\\Models\\Guest', 7, 13, 0, 0, 0, '2025-12-08 07:07:40', NULL, NULL),
(247, 124, 1, 'App\\Models\\User', 7, 14, 1, 1, 0, '2025-12-08 07:08:24', NULL, NULL),
(248, 124, 18, 'App\\Models\\Guest', 7, 13, 0, 0, 0, '2025-12-08 07:08:24', NULL, NULL),
(249, 125, 1, 'App\\Models\\User', 8, 16, 0, 0, 0, '2025-12-08 07:09:50', NULL, NULL),
(250, 125, 19, 'App\\Models\\Guest', 8, 15, 1, 1, 0, '2025-12-08 07:09:50', NULL, NULL),
(251, 126, 1, 'App\\Models\\User', 8, 16, 0, 0, 0, '2025-12-08 07:16:17', NULL, NULL),
(252, 126, 19, 'App\\Models\\Guest', 8, 15, 1, 1, 0, '2025-12-08 07:16:17', NULL, NULL),
(253, 127, 1, 'App\\Models\\User', 8, 16, 0, 0, 0, '2025-12-08 07:16:34', NULL, NULL),
(254, 127, 19, 'App\\Models\\Guest', 8, 15, 1, 1, 0, '2025-12-08 07:16:34', NULL, NULL),
(255, 128, 1, 'App\\Models\\User', 8, 16, 0, 0, 0, '2025-12-08 07:17:09', NULL, NULL),
(256, 128, 19, 'App\\Models\\Guest', 8, 15, 1, 1, 0, '2025-12-08 07:17:09', NULL, NULL),
(257, 129, 1, 'App\\Models\\User', 8, 16, 0, 0, 0, '2025-12-08 09:36:37', NULL, NULL),
(258, 129, 19, 'App\\Models\\Guest', 8, 15, 1, 1, 0, '2025-12-08 09:36:37', NULL, NULL),
(259, 130, 1, 'App\\Models\\User', 8, 16, 0, 0, 0, '2025-12-08 09:36:38', NULL, NULL),
(260, 130, 19, 'App\\Models\\Guest', 8, 15, 1, 1, 0, '2025-12-08 09:36:38', NULL, NULL),
(261, 131, 1, 'App\\Models\\User', 9, 18, 0, 0, 0, '2025-12-09 01:41:23', NULL, NULL),
(262, 131, 22, 'App\\Models\\Guest', 9, 17, 1, 1, 0, '2025-12-09 01:41:23', NULL, NULL),
(263, 132, 1, 'App\\Models\\User', 10, 20, 0, 0, 0, '2025-12-18 10:00:00', NULL, NULL),
(264, 132, 26, 'App\\Models\\Guest', 10, 19, 1, 1, 0, '2025-12-18 10:00:00', NULL, NULL),
(265, 133, 1, 'App\\Models\\User', 10, 20, 1, 1, 0, '2025-12-18 10:00:09', NULL, NULL),
(266, 133, 26, 'App\\Models\\Guest', 10, 19, 0, 0, 0, '2025-12-18 10:00:09', NULL, NULL),
(267, 134, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2025-12-18 10:00:44', NULL, NULL),
(268, 134, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2025-12-18 10:00:44', NULL, NULL),
(269, 135, 1, 'App\\Models\\User', 11, 22, 0, 0, 0, '2026-01-12 02:20:56', NULL, NULL),
(270, 135, 31, 'App\\Models\\Guest', 11, 21, 1, 1, 0, '2026-01-12 02:20:56', NULL, NULL),
(271, 136, 1, 'App\\Models\\User', 12, 24, 0, 0, 0, '2026-01-26 15:37:02', NULL, NULL),
(272, 136, 61, 'App\\Models\\Guest', 12, 23, 1, 1, 0, '2026-01-26 15:37:02', NULL, NULL),
(273, 137, 1, 'App\\Models\\User', 12, 24, 0, 0, 0, '2026-01-26 15:39:20', NULL, NULL),
(274, 137, 61, 'App\\Models\\Guest', 12, 23, 1, 1, 0, '2026-01-26 15:39:20', NULL, NULL),
(275, 138, 1, 'App\\Models\\User', 12, 24, 0, 0, 0, '2026-01-26 15:39:36', NULL, NULL),
(276, 138, 61, 'App\\Models\\Guest', 12, 23, 1, 1, 0, '2026-01-26 15:39:36', NULL, NULL),
(277, 139, 1, 'App\\Models\\User', 12, 24, 1, 1, 0, '2026-01-26 15:39:36', NULL, NULL),
(278, 139, 61, 'App\\Models\\Guest', 12, 23, 0, 0, 0, '2026-01-26 15:39:36', NULL, NULL),
(279, 140, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2026-01-26 15:39:48', NULL, NULL),
(280, 140, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2026-01-26 15:39:48', NULL, NULL),
(281, 141, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2026-01-26 15:39:48', NULL, NULL),
(282, 141, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2026-01-26 15:39:48', NULL, NULL),
(283, 142, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2026-01-26 15:39:53', NULL, NULL),
(284, 142, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2026-01-26 15:39:53', NULL, NULL),
(285, 143, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2026-01-26 15:40:23', NULL, NULL),
(286, 143, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2026-01-26 15:40:23', NULL, NULL),
(287, 144, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2026-01-26 15:40:40', NULL, NULL),
(288, 144, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2026-01-26 15:40:40', NULL, NULL),
(289, 145, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2026-01-26 15:41:10', NULL, NULL),
(290, 145, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2026-01-26 15:41:10', NULL, NULL),
(291, 146, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2026-01-26 15:41:16', NULL, NULL),
(292, 146, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2026-01-26 15:41:16', NULL, NULL),
(293, 147, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2026-01-26 15:41:16', NULL, NULL),
(294, 147, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2026-01-26 15:41:16', NULL, NULL),
(295, 148, 1, 'App\\Models\\Customer', 3, 5, 1, 1, 0, '2026-01-26 15:41:21', NULL, NULL),
(296, 148, 1, 'App\\Models\\User', 3, 6, 0, 0, 0, '2026-01-26 15:41:21', NULL, NULL),
(297, 149, 1, 'App\\Models\\Customer', 3, 5, 0, 0, 0, '2026-01-26 15:41:51', NULL, NULL),
(298, 149, 1, 'App\\Models\\User', 3, 6, 1, 1, 0, '2026-01-26 15:41:51', NULL, NULL),
(299, 150, 1, 'App\\Models\\User', 13, 26, 0, 0, 0, '2026-01-26 15:48:00', NULL, NULL),
(300, 150, 62, 'App\\Models\\Guest', 13, 25, 1, 1, 0, '2026-01-26 15:48:00', NULL, NULL),
(301, 151, 1, 'App\\Models\\User', 13, 26, 1, 1, 0, '2026-01-26 15:48:00', NULL, NULL),
(302, 151, 62, 'App\\Models\\Guest', 13, 25, 0, 0, 0, '2026-01-26 15:48:00', NULL, NULL),
(303, 152, 1, 'App\\Models\\User', 13, 26, 0, 0, 0, '2026-01-26 15:48:02', NULL, NULL),
(304, 152, 62, 'App\\Models\\Guest', 13, 25, 1, 1, 0, '2026-01-26 15:48:02', NULL, NULL),
(305, 153, 1, 'App\\Models\\User', 13, 26, 1, 1, 0, '2026-01-26 15:48:02', NULL, NULL),
(306, 153, 62, 'App\\Models\\Guest', 13, 25, 0, 0, 0, '2026-01-26 15:48:02', NULL, NULL),
(307, 154, 1, 'App\\Models\\User', 13, 26, 0, 0, 0, '2026-01-26 15:48:05', NULL, NULL),
(308, 154, 62, 'App\\Models\\Guest', 13, 25, 1, 1, 0, '2026-01-26 15:48:05', NULL, NULL),
(309, 155, 1, 'App\\Models\\User', 13, 26, 1, 1, 0, '2026-01-26 15:48:36', NULL, NULL),
(310, 155, 62, 'App\\Models\\Guest', 13, 25, 0, 0, 0, '2026-01-26 15:48:36', NULL, NULL),
(311, 156, 1, 'App\\Models\\User', 13, 26, 0, 0, 0, '2026-01-26 15:51:20', NULL, NULL),
(312, 156, 62, 'App\\Models\\Guest', 13, 25, 1, 1, 0, '2026-01-26 15:51:20', NULL, NULL),
(313, 157, 1, 'App\\Models\\User', 13, 26, 1, 1, 0, '2026-01-26 15:51:20', NULL, NULL),
(314, 157, 62, 'App\\Models\\Guest', 13, 25, 0, 0, 0, '2026-01-26 15:51:20', NULL, NULL),
(315, 158, 1, 'App\\Models\\User', 13, 26, 0, 0, 0, '2026-01-26 15:57:12', NULL, NULL),
(316, 158, 62, 'App\\Models\\Guest', 13, 25, 1, 1, 0, '2026-01-26 15:57:12', NULL, NULL),
(317, 159, 1, 'App\\Models\\User', 13, 26, 1, 1, 0, '2026-01-26 15:57:12', NULL, NULL),
(318, 159, 62, 'App\\Models\\Guest', 13, 25, 0, 0, 0, '2026-01-26 15:57:12', NULL, NULL),
(319, 160, 1, 'App\\Models\\User', 14, 28, 0, 0, 0, '2026-02-03 03:32:52', NULL, NULL),
(320, 160, 65, 'App\\Models\\Guest', 14, 27, 1, 1, 0, '2026-02-03 03:32:52', NULL, NULL),
(321, 161, 1, 'App\\Models\\User', 14, 28, 1, 1, 0, '2026-02-03 03:32:52', NULL, NULL),
(322, 161, 65, 'App\\Models\\Guest', 14, 27, 0, 0, 0, '2026-02-03 03:32:52', NULL, NULL),
(323, 162, 1, 'App\\Models\\User', 14, 28, 0, 0, 0, '2026-02-03 03:32:56', NULL, NULL),
(324, 162, 65, 'App\\Models\\Guest', 14, 27, 1, 1, 0, '2026-02-03 03:32:56', NULL, NULL),
(325, 163, 1, 'App\\Models\\User', 14, 28, 1, 1, 0, '2026-02-03 03:33:08', NULL, NULL),
(326, 163, 65, 'App\\Models\\Guest', 14, 27, 0, 0, 0, '2026-02-03 03:33:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_participation`
--

DROP TABLE IF EXISTS `chat_participation`;
CREATE TABLE IF NOT EXISTS `chat_participation` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `messageable_id` bigint UNSIGNED NOT NULL,
  `messageable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participation_index` (`conversation_id`,`messageable_id`,`messageable_type`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_participation`
--

INSERT INTO `chat_participation` (`id`, `conversation_id`, `messageable_id`, `messageable_type`, `settings`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'App\\Models\\Guest', NULL, '2025-12-03 15:11:07', '2025-12-03 15:11:07'),
(2, 1, 1, 'App\\Models\\User', NULL, '2025-12-03 15:11:07', '2025-12-03 15:11:07'),
(3, 2, 3, 'App\\Models\\Guest', NULL, '2025-12-03 15:42:03', '2025-12-03 15:42:03'),
(4, 2, 1, 'App\\Models\\User', NULL, '2025-12-03 15:42:03', '2025-12-03 15:42:03'),
(5, 3, 1, 'App\\Models\\Customer', NULL, '2025-12-04 02:34:00', '2025-12-04 02:34:00'),
(6, 3, 1, 'App\\Models\\User', NULL, '2025-12-04 02:34:00', '2025-12-04 02:34:00'),
(7, 4, 13, 'App\\Models\\Guest', NULL, '2025-12-04 16:39:05', '2025-12-04 16:39:05'),
(8, 4, 1, 'App\\Models\\User', NULL, '2025-12-04 16:39:05', '2025-12-04 16:39:05'),
(9, 5, 14, 'App\\Models\\Guest', NULL, '2025-12-05 02:16:12', '2025-12-05 02:16:12'),
(10, 5, 1, 'App\\Models\\User', NULL, '2025-12-05 02:16:12', '2025-12-05 02:16:12'),
(11, 6, 17, 'App\\Models\\Guest', NULL, '2025-12-08 05:56:22', '2025-12-08 05:56:22'),
(12, 6, 1, 'App\\Models\\User', NULL, '2025-12-08 05:56:22', '2025-12-08 05:56:22'),
(13, 7, 18, 'App\\Models\\Guest', NULL, '2025-12-08 07:03:56', '2025-12-08 07:03:56'),
(14, 7, 1, 'App\\Models\\User', NULL, '2025-12-08 07:03:56', '2025-12-08 07:03:56'),
(15, 8, 19, 'App\\Models\\Guest', NULL, '2025-12-08 07:09:50', '2025-12-08 07:09:50'),
(16, 8, 1, 'App\\Models\\User', NULL, '2025-12-08 07:09:50', '2025-12-08 07:09:50'),
(17, 9, 22, 'App\\Models\\Guest', NULL, '2025-12-09 01:41:22', '2025-12-09 01:41:22'),
(18, 9, 1, 'App\\Models\\User', NULL, '2025-12-09 01:41:22', '2025-12-09 01:41:22'),
(19, 10, 26, 'App\\Models\\Guest', NULL, '2025-12-18 09:59:59', '2025-12-18 09:59:59'),
(20, 10, 1, 'App\\Models\\User', NULL, '2025-12-18 09:59:59', '2025-12-18 09:59:59'),
(21, 11, 31, 'App\\Models\\Guest', NULL, '2026-01-12 02:20:55', '2026-01-12 02:20:55'),
(22, 11, 1, 'App\\Models\\User', NULL, '2026-01-12 02:20:55', '2026-01-12 02:20:55'),
(23, 12, 61, 'App\\Models\\Guest', NULL, '2026-01-26 15:37:02', '2026-01-26 15:37:02'),
(24, 12, 1, 'App\\Models\\User', NULL, '2026-01-26 15:37:02', '2026-01-26 15:37:02'),
(25, 13, 62, 'App\\Models\\Guest', NULL, '2026-01-26 15:48:00', '2026-01-26 15:48:00'),
(26, 13, 1, 'App\\Models\\User', NULL, '2026-01-26 15:48:00', '2026-01-26 15:48:00'),
(27, 14, 65, 'App\\Models\\Guest', NULL, '2026-02-03 03:32:52', '2026-02-03 03:32:52'),
(28, 14, 1, 'App\\Models\\User', NULL, '2026-02-03 03:32:52', '2026-02-03 03:32:52');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversations_session_id_unique` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, 'PLoUP6WKuomdMQKbnV4bD1felS9hsWmCkwmXhdfl', '2025-09-10 09:54:14', '2025-09-10 09:54:14'),
(2, '9gvCKZwnlmnUfG7WILJEyk0iydRYc2OZyzyUvOb3', '2025-09-10 21:04:01', '2025-09-10 21:04:01'),
(3, 'Gc8bqWacFHybmYgN9zUv3nu9OIJwWQlqqWkSg650', '2025-09-11 08:00:27', '2025-09-11 08:00:27'),
(4, 'aRcErChEdmQLsUb5eZx1tQaSBOxcdlrDffdvHlPM', '2025-09-11 08:53:37', '2025-09-11 08:53:37'),
(5, 'CA4DlNum3gPLjqZUropJlrdA5WUkKd23i5Y0KlDk', '2025-09-12 07:01:41', '2025-09-12 07:01:41'),
(6, '3nsqPi9gLVN3717s9fDGx8fwolTwe3064Q6bXEqU', '2025-09-28 12:52:26', '2025-09-28 12:52:26'),
(7, 'GaldF4cdtj47SQmgyMrXsLjLPrhySON8fGfyTWsP', '2025-10-26 13:01:46', '2025-10-26 13:01:46'),
(8, '3LTDZ1DPjV7acXNO3fmlBydmGSqwsKfjSZJKR7tc', '2025-10-26 14:36:11', '2025-10-26 14:36:11'),
(9, 'gHbjjzTSZclS0skOEoZgOlrKJiqGuu1k4RUY5tnj', '2025-10-28 06:17:36', '2025-10-28 06:17:36'),
(10, 'XwA3gWU1DrZ1keOzAoinqhHhgya4bZ5cypK84kZB', '2025-11-19 09:44:07', '2025-11-19 09:44:07'),
(11, '6Sj0PI51nI4FJgRvrCXl85F0bx07X1ScIdEG876m', '2025-11-24 03:56:54', '2025-11-24 03:56:54'),
(12, 'FU1TGGphJhuoR40RnGkejKATCOOMOfjTkrYBatTx', '2025-11-27 12:44:24', '2025-11-27 12:44:24'),
(13, 'WpTl55d1duUjc9DxNHUxtuaMurGSWLKw2SHJgfRM', '2025-11-28 07:57:20', '2025-11-28 07:57:20'),
(14, 'NiQwqr7gwkTpe4APMrjxhtA7nka1OBzIeardbsWl', '2025-12-04 02:49:44', '2025-12-04 02:49:44'),
(15, 'occ1Gbk0y0sVxAUU1J9vLq3WwW5HELROVco4Ixa0', '2025-12-04 12:18:14', '2025-12-04 12:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `min_purchase_amount` decimal(15,2) DEFAULT NULL,
  `max_uses` int UNSIGNED DEFAULT NULL,
  `times_used` int UNSIGNED NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_purchase_amount`, `max_uses`, `times_used`, `starts_at`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'SKINCARE20', 'percentage', 20.00, 500000.00, 100, 0, '2025-09-02 17:00:00', '2025-09-29 17:00:00', 1, '2025-09-03 00:56:17', '2025-09-03 00:56:17'),
(4, 'MAKEUP50K', 'fixed_amount', 50000.00, 200000.00, NULL, 0, '2025-09-02 17:00:00', '2025-09-29 17:00:00', 1, '2025-09-03 00:57:28', '2025-09-03 00:57:28'),
(7, 'BEAUTY@#$%', 'fixed_amount', 30.00, 10000.00, 30, 0, '2025-09-02 17:00:00', '2025-09-29 17:00:00', 1, '2025-09-03 01:07:49', '2025-09-03 01:07:49'),
(8, 'THISISAVERYLONGCOUPONCODEFORCOSMETICS123456789', 'percentage', 70.00, 1000.00, 100, 0, '2025-09-02 17:00:00', '2025-09-29 17:00:00', 1, '2025-09-03 01:11:05', '2025-09-03 01:11:05'),
(9, 'THISISAVERYLONGCOUPONCODEFORCOSMETICS1234567892739nsdb', 'percentage', 12.00, 1000.00, 70, 0, '2025-09-02 17:00:00', '2025-09-29 17:00:00', 1, '2025-09-03 01:12:21', '2025-09-09 04:57:06'),
(11, 'FREESHIP12', 'fixed_amount', 12121212.00, NULL, NULL, 0, '2025-09-03 17:00:00', '2025-09-12 17:00:00', 1, '2025-09-03 22:29:35', '2025-09-03 22:29:35'),
(12, 'FREESHIP121212', 'fixed_amount', 12000.00, NULL, 21, 1, '2025-09-03 17:00:00', '2025-09-12 17:00:00', 1, '2025-09-03 22:29:52', '2025-09-09 04:40:02'),
(13, 'FREESHIP121212121', 'fixed_amount', 1212121.00, NULL, 12, 0, '2025-09-03 17:00:00', '2025-09-08 17:00:00', 1, '2025-09-03 22:30:07', '2025-09-03 22:30:07'),
(16, 'test121212', 'fixed_amount', 1200000.00, 200000.00, 100, 1, '2025-09-27 17:00:00', '2025-09-29 17:00:00', 1, '2025-09-09 05:15:45', '2025-09-28 12:58:29');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `address`, `city`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `last_login_at`, `last_login_ip`) VALUES
(1, 'Phạm', 'Trà', 'trapham24065@gmail.com', '0868720028', '$2y$12$GW/GyLJENx4CkKgTnFDVDeEsQ8HuTKYKGR9h3f9V/I6Skg2xtNsgi', 'mỹ thịnh-mỹ lộc-nam định', 'Nam Định', 1, 'g4ay21cCfvHx9BtmFEVgoYVvVofT6w5tbRzqlTv0iuC6XwpC3t58Jv2e0XGo', '2025-11-28 09:56:51', '2026-02-24 16:04:17', NULL, '2026-02-24 16:04:17', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
CREATE TABLE IF NOT EXISTS `guests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Guest',
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guests_session_id_unique` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `session_id`, `name`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'FU1TGGphJhuoR40RnGkejKATCOOMOfjTkrYBatTx', 'Khách Vãng Lai', '::1', '2025-11-27 13:54:09', '2025-11-27 13:54:09'),
(2, 'Mg2GWQREyv2qgvw8ePkmU8ZdK63RzfC5Uky2S0yT', 'Khách Vãng Lai', '::1', '2025-12-03 14:05:35', '2025-12-03 14:05:35'),
(3, 'gC9WxxGeLcubbdrBspjj6VqdZ12oIF0qWbgPpYdR', 'Khách Vãng Lai', '::1', '2025-12-03 15:41:58', '2025-12-03 15:41:58'),
(4, '7gxHOlRvCxud66vd9eq2v8yzEkq4SF2KIpuCWF9n', 'Khách Vãng Lai', '::1', '2025-12-03 19:29:33', '2025-12-03 19:29:33'),
(5, 'AIpDNhx3lG2gYy4hslrCxliT3o2dlYL0cPeKonK1', 'Khách Vãng Lai', '::1', '2025-12-04 02:12:41', '2025-12-04 02:12:41'),
(6, 'BsKON0tiJJeZ81FjvzpMw1jcHESA5GUSn0LgKJoS', 'Khách Vãng Lai', '127.0.0.1', '2025-12-04 02:12:42', '2025-12-04 02:12:42'),
(7, 'MxlwTePhSjTVmAMQf0ax15g48wgqPDwG3wjBHFoZ', 'Khách Vãng Lai', '::1', '2025-12-04 02:12:42', '2025-12-04 02:12:42'),
(8, 'nj6jm5KX88ReMQ45xOviJ7PSjzEWiFHN8cMmHopT', 'Khách Vãng Lai', '::1', '2025-12-04 02:12:42', '2025-12-04 02:12:42'),
(9, 'YdBFHdWT8ZviBwWR5Vb6zhJScG4OwGLW0idNKSn3', 'Khách Vãng Lai', '::1', '2025-12-04 02:12:42', '2025-12-04 02:12:42'),
(10, 'SO4JZldLOInwZlBCWaxdfCxZwzdaseSnBDZSRAG2', 'Khách Vãng Lai', '::1', '2025-12-04 02:12:42', '2025-12-04 02:12:42'),
(11, 'mIi1F4gSPgU1sUcALUGPHMYbdKGy3ZPmMBIghAzg', 'Khách Vãng Lai', '::1', '2025-12-04 07:10:11', '2025-12-04 07:10:11'),
(12, '3rVGgHWiYAJ5T3UittwJGHPxzWs2aDr6SZXAYjxo', 'Khách Vãng Lai', '::1', '2025-12-04 13:11:22', '2025-12-04 13:11:22'),
(13, '2yNuvuYBTesOBSiPBi1WeEINx76bPppLb0fturw2', 'Khách Vãng Lai', '::1', '2025-12-04 16:39:03', '2025-12-04 16:39:03'),
(14, '7ooCIxrXizwTbxHcn3Io0ijZTYM0CHyHxQuirW9G', 'Khách Vãng Lai', '::1', '2025-12-05 01:52:23', '2025-12-05 01:52:23'),
(15, 'XuBLSLzd6I7YpLHb3sHVWOPgsUfOBx3RErKwpRfQ', 'Khách Vãng Lai', '::1', '2025-12-05 05:12:31', '2025-12-05 05:12:31'),
(16, 'ckBquo671mOntvJ5n72ARpnJHYD6kBeZRgEioYWE', 'Visitors', '::1', '2025-12-07 11:02:41', '2025-12-07 11:02:41'),
(17, '3lkgxmy4hxxXNkGe27GIUuQTdiwQxOClkToWr87X', 'Visitors', '::1', '2025-12-08 05:55:52', '2025-12-08 05:55:52'),
(18, 'bVd0ltD0RAWUA8ryQRKL10ZZTWwWdiILwbj7De1s', 'Visitors', '::1', '2025-12-08 07:03:44', '2025-12-08 07:03:44'),
(19, 'OaEeUV5Boz1dwoo3NS8u1b3HaRPosNos21ErmirS', 'Visitors', '::1', '2025-12-08 07:08:59', '2025-12-08 07:08:59'),
(20, 'n2I2krra84CpYsURNweTvjhl8Rdl4lXO4kA2pypY', 'Visitors', '::1', '2025-12-08 13:04:43', '2025-12-08 13:04:43'),
(21, 'lpBTWbWCMetLLl6EhoUgf5kpUxlNPRaog8JiIbHP', 'Visitors', '::1', '2025-12-08 13:04:43', '2025-12-08 13:04:43'),
(22, 'GagGEOgV0894rbJXqR8Lf42TpkgH5N62vPwupF3c', 'Visitors', '::1', '2025-12-09 01:41:20', '2025-12-09 01:41:20'),
(23, 'YYbzezSVE9EbZP84v9DimDiECfhvITCtMBzS0VR4', 'Visitors', '::1', '2025-12-09 01:58:41', '2025-12-09 01:58:41'),
(24, 'QYj1YsLt4m2L3ku0o25AkXsq3cxYy9h5NRVnQthu', 'Visitors', '::1', '2025-12-10 01:56:03', '2025-12-10 01:56:03'),
(25, 'b1E7UfHhkRs8mTSCzrGv828ALEzewSH5eZI5NHK0', 'Visitors', '::1', '2025-12-18 09:58:43', '2025-12-18 09:58:43'),
(26, 'tO83hZ5pl2y7tyL8C6GB8tZ0yMMwd1VYiy8Qf3p7', 'Visitors', '::1', '2025-12-18 09:59:01', '2025-12-18 09:59:01'),
(27, 'ySA0pplILczqT9BVLlU03TgW5BOOCwrsIdlTqLr8', 'Visitors', '127.0.0.1', '2025-12-29 08:56:12', '2025-12-29 08:56:12'),
(28, 'oTOBaoSMFPPi7gbcDq1X9UFhuyBMcyiYU0DHXOBQ', 'Visitors', '127.0.0.1', '2025-12-29 08:57:31', '2025-12-29 08:57:31'),
(29, 'koVh53Y1fr1a0tAj47LKcURGkRkfLvN4Tk87EIgJ', 'Visitors', '127.0.0.1', '2025-12-29 08:59:03', '2025-12-29 08:59:03'),
(30, 'vGfGWRt9gVd9VYgMSZ0c9eQUYsydCco5UB3a9Tz6', 'Visitors', '127.0.0.1', '2025-12-29 09:01:30', '2025-12-29 09:01:30'),
(31, 'vJ6wo0JbcJYU1Ea0e4RwRr4g50dJzsy83slRX6dT', 'Visitors', '::1', '2026-01-12 02:17:23', '2026-01-12 02:17:23'),
(32, 'gETnLJWHoAQa7iXaMR0JCID72UcLX6EtMB72Pmsr', 'Visitors', '::1', '2026-01-12 02:21:17', '2026-01-12 02:21:17'),
(33, 'GdJNOZK8ma3dJE6D9KFNoZ0U2wOesm4rMKXefRJv', 'Visitors', '::1', '2026-01-12 03:37:08', '2026-01-12 03:37:08'),
(34, 'wcGMldSfDlgxTL8zXq09BIUntKpFSBujHgrFADXe', 'Visitors', '::1', '2026-01-12 03:38:08', '2026-01-12 03:38:08'),
(35, 'ZAaio0tagM8Wv0TwMMJnzJXAtELtqckaupDiXjb2', 'Visitors', '::1', '2026-01-12 03:44:59', '2026-01-12 03:44:59'),
(36, 't9skMkbF8inPR69qqH7Y0a3M9msYhbMBkJhczWBd', 'Visitors', '::1', '2026-01-19 12:51:18', '2026-01-19 12:51:18'),
(37, 'qv9UUpgZ9ZYeeXx3YsEpYaDDnKuv7nx02HnqD4wr', 'Visitors', '::1', '2026-01-19 13:18:25', '2026-01-19 13:18:25'),
(38, '18WQTIrK817FulXCNA1PfH63BWoLt8Dqm4REXGtR', 'Visitors', '::1', '2026-01-20 13:25:38', '2026-01-20 13:25:38'),
(39, '5OOo69r51VYU0EXXB2R2V2NOnzwNI3lKeSaAz6UJ', 'Visitors', '::1', '2026-01-20 13:26:25', '2026-01-20 13:26:25'),
(40, 'UYlbSAK2YMXJRpJLyihx0r5WbjAudzJwPA8BdvDY', 'Visitors', '::1', '2026-01-20 13:27:12', '2026-01-20 13:27:12'),
(41, 'EmsdV0thVphGL9bcKJpC2cZBL8MvoDXl5puaaTY4', 'Visitors', '::1', '2026-01-23 11:27:10', '2026-01-23 11:27:10'),
(42, '30Ah2efZJrGbpxVQqbzS767Am08JpIG1Q3Z58JOa', 'Visitors', '::1', '2026-01-23 11:35:43', '2026-01-23 11:35:43'),
(43, 'Opc5xREnC11eTjThrbuMR4Gt4dJeX4rpUZoisJFV', 'Visitors', '::1', '2026-01-23 12:00:55', '2026-01-23 12:00:55'),
(44, 'Z7SYCgspsuUE9sZVkP94AmnK7g7OZ3oh6FRkr462', 'Visitors', '::1', '2026-01-23 12:45:55', '2026-01-23 12:45:55'),
(45, 'M0EZA50qXHxvslB9Sy28pQupURsxhXeU7MoQwtlY', 'Visitors', '::1', '2026-01-23 14:12:58', '2026-01-23 14:12:58'),
(46, 'pRFnc32iiMGpd9qiDKWcnfDDD6i1c65BM6jL7CqR', 'Visitors', '::1', '2026-01-23 14:17:01', '2026-01-23 14:17:01'),
(47, 'eDxbNoezVXPztZ7AJBiKLOm7JY0TucozvqDN2muV', 'Visitors', '::1', '2026-01-23 14:51:41', '2026-01-23 14:51:41'),
(48, 'ywHdZS1rBOquCrgogTaXlVpcnYgJaxyAZiTedUdM', 'Visitors', '::1', '2026-01-23 14:51:50', '2026-01-23 14:51:50'),
(49, 'PFXPBymepJADHi0gNaNxVlF58i2616RsKTkKrrHN', 'Visitors', '::1', '2026-01-24 01:44:53', '2026-01-24 01:44:53'),
(50, 'qxUZkdl6ut81RwwMzTWekvOSf8spYfxGmTSWPB1u', 'Visitors', '::1', '2026-01-24 01:45:48', '2026-01-24 01:45:48'),
(51, 'Xtg4IYVLHCq0OQCYMA59GrGn0HdrCivNDmHG6wwN', 'Visitors', '::1', '2026-01-24 10:38:00', '2026-01-24 10:38:00'),
(52, '6TSFdNIfg94HCM9NPvlAlDiertYGJWTimoTg7YF0', 'Visitors', '::1', '2026-01-24 10:46:17', '2026-01-24 10:46:17'),
(53, 'cR93jtMfYI027T5P25c1CKqvRlnXWTph8sO1eEw5', 'Visitors', '::1', '2026-01-25 03:29:02', '2026-01-25 03:29:02'),
(54, 'qrTqsOuylsnuFLadzauIFMDXx7htZhXAKKIgd2Qv', 'Visitors', '::1', '2026-01-25 03:49:55', '2026-01-25 03:49:55'),
(55, 'AIibOYZtwBpYp6gibWHyLd8IAUUL9qUB0Ep5Y5f6', 'Visitors', '::1', '2026-01-25 08:18:26', '2026-01-25 08:18:26'),
(56, 'uDap7jwqLZBdKyUTcaVqPFX4Iow08EjO3alj6hLO', 'Visitors', '::1', '2026-01-25 08:18:28', '2026-01-25 08:18:28'),
(57, 'haZPzFDqhueLcWXtcHvludXNrUp9GQHDi959C9Mi', 'Visitors', '::1', '2026-01-25 08:34:06', '2026-01-25 08:34:06'),
(58, 'C9xa981j8O25CGIzMvY7VmNJM5ymBglz4zv0OCyd', 'Visitors', '::1', '2026-01-25 13:12:43', '2026-01-25 13:12:43'),
(59, 'Dpk5SpJWX94fBgycCdXl8EIP71OpGogaXiyaWKmz', 'Visitors', '::1', '2026-01-25 13:31:23', '2026-01-25 13:31:23'),
(60, 'RTUNKLTTZ9AQASURauSLZk2yizJxjQIuCTZDxtuQ', 'Visitors', '::1', '2026-01-26 14:56:20', '2026-01-26 14:56:20'),
(61, 'cMHzL2IV9nMLYKBEkk11lpxkI5C8pzN21RMBrj4m', 'Visitors', '::1', '2026-01-26 14:56:37', '2026-01-26 14:56:37'),
(62, '3MVi8cPHNTdkUTLVP2zbf0PsdJto5vNsVNdiczkM', 'Visitors', '::1', '2026-01-26 15:47:58', '2026-01-26 15:47:58'),
(63, 'BqGxERspuVWYChVosmV7ZP5MgjR3mVQtLYwdPdgk', 'Visitors', '::1', '2026-01-26 16:07:40', '2026-01-26 16:07:40'),
(64, 'U25GhnBE81zAbWf6GIbS6pSsoVe6ZKMDsXTNpx2s', 'Visitors', '::1', '2026-02-03 03:05:38', '2026-02-03 03:05:38'),
(65, 'V5kUNKSyVjK6Jozd8uXx4BZV3L9tb76AaPeFqGtG', 'Visitors', '::1', '2026-02-03 03:15:41', '2026-02-03 03:15:41'),
(66, 'p5hAl9ULvt6tSmCnLUzk2o3mM67oIAIyHBdeTScU', 'Visitors', '::1', '2026-02-03 17:22:26', '2026-02-03 17:22:26'),
(67, 'jjZhGI0gDE3Czo5UOFBj2IkodH2HCNfsesoYAdz8', 'Visitors', '::1', '2026-02-04 13:10:22', '2026-02-04 13:10:22'),
(68, 'ewYZenakpHZeydp48jhz1w1jViKQn4EnxSOmYEOD', 'Visitors', '::1', '2026-02-10 03:44:14', '2026-02-10 03:44:14'),
(69, 'usBZpSwZDTzuq07A4EuH7TXe77chqjy0NBB8mgk6', 'Visitors', '::1', '2026-02-10 10:53:51', '2026-02-10 10:53:51'),
(70, 'Lfnp1sCYglu2sX8H88IqPCjpVzgtFxaNYN9A2pBh', 'Visitors', '127.0.0.1', '2026-02-19 10:07:11', '2026-02-19 10:07:11'),
(71, 'plLB0WTVB1B8bPgFPXtqYNYiaPgV3dNc1hUAzTRh', 'Visitors', '::1', '2026-02-22 11:39:19', '2026-02-22 11:39:19'),
(72, 'u8N7lSapmSikCY4b3fMFKu4IISKOZ9GnhKEpVyfD', 'Visitors', '::1', '2026-02-24 10:24:17', '2026-02-24 10:24:17'),
(73, 'iTaxC2YlJduZNEdTMAHX6Rnmg7kO2e3iGpGTdmRi', 'Visitors', '::1', '2026-02-24 11:10:28', '2026-02-24 11:10:28'),
(74, 'yIdCikG1gmLyQJ4gnacM6YGgsLIJsbSKp1lCsv5O', 'Visitors', '::1', '2026-02-25 05:08:42', '2026-02-25 05:08:42'),
(75, 'fEjNWpf3V0H8HBYmqMu5RyXaQVsSLa50w8C1Nwiz', 'Visitors', '::1', '2026-02-27 10:27:19', '2026-02-27 10:27:19'),
(76, 'ZZXBT0TVE7zrOP0untwZrGMJL24C5fY5ZlSMhSyT', 'Visitors', '::1', '2026-02-27 11:22:50', '2026-02-27 11:22:50'),
(77, 'srSvXqn6b5ejDewv6FOlVzdZNiZGtMritBcUZ4KE', 'Visitors', '::1', '2026-02-28 07:03:57', '2026-02-28 07:03:57'),
(78, 'I7YcxpXgepdCrCJkbL9Raq4QqkR2btevKCBOmiLi', 'Visitors', '::1', '2026-03-03 06:40:12', '2026-03-03 06:40:12'),
(79, 'NQ3RtSGNFlBtgc8jleZJfPpNmoi9wCsB8i08bFTL', 'Visitors', '::1', '2026-03-04 14:13:28', '2026-03-04 14:13:28');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
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

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_conversation_id_foreign` (`conversation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=680 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `content`, `sender`, `created_at`, `updated_at`) VALUES
(1, 1, 'xin chào', 'user', '2025-09-10 09:54:14', '2025-09-10 09:54:14'),
(2, 1, 'Thank you for your message! We will get back to you shortly.', 'bot', '2025-09-10 09:54:14', '2025-09-10 09:54:14'),
(3, 1, 'tôi muốn hỏi sản phẩm xịn', 'user', '2025-09-10 09:54:31', '2025-09-10 09:54:31'),
(4, 1, 'Thank you for your message! We will get back to you shortly.', 'bot', '2025-09-10 09:54:31', '2025-09-10 09:54:31'),
(5, 1, 'Shipping policy', 'user', '2025-09-10 11:04:19', '2025-09-10 11:04:19'),
(6, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:04:19', '2025-09-10 11:04:19'),
(7, 1, 'hi', 'user', '2025-09-10 11:04:24', '2025-09-10 11:04:24'),
(8, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:04:24', '2025-09-10 11:04:24'),
(9, 1, 'promotions', 'user', '2025-09-10 11:04:38', '2025-09-10 11:04:38'),
(10, 1, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-09-10 11:04:38', '2025-09-10 11:04:38'),
(11, 1, 'ship', 'user', '2025-09-10 11:04:45', '2025-09-10 11:04:45'),
(12, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:04:45', '2025-09-10 11:04:45'),
(13, 1, 'ship', 'user', '2025-09-10 11:14:41', '2025-09-10 11:14:41'),
(14, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:14:41', '2025-09-10 11:14:41'),
(15, 1, 'ship', 'user', '2025-09-10 11:23:20', '2025-09-10 11:23:20'),
(16, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:23:20', '2025-09-10 11:23:20'),
(17, 1, 'Shipping policy', 'user', '2025-09-10 11:23:50', '2025-09-10 11:23:50'),
(18, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:23:50', '2025-09-10 11:23:50'),
(19, 1, 'Shipping policy', 'user', '2025-09-10 11:29:46', '2025-09-10 11:29:46'),
(20, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:29:46', '2025-09-10 11:29:46'),
(21, 1, 'hi', 'user', '2025-09-10 11:29:48', '2025-09-10 11:29:48'),
(22, 1, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 11:29:48', '2025-09-10 11:29:48'),
(23, 1, 'How to return goods?', 'user', '2025-09-10 11:31:51', '2025-09-10 11:31:51'),
(24, 1, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-10 11:31:51', '2025-09-10 11:31:51'),
(25, 2, 'Shipping policy', 'user', '2025-09-10 21:04:01', '2025-09-10 21:04:01'),
(26, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 21:04:01', '2025-09-10 21:04:01'),
(27, 2, 'Hi', 'user', '2025-09-10 21:13:11', '2025-09-10 21:13:11'),
(28, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 21:13:11', '2025-09-10 21:13:11'),
(29, 2, 'Hello', 'user', '2025-09-10 21:41:59', '2025-09-10 21:41:59'),
(30, 2, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-10 21:41:59', '2025-09-10 21:41:59'),
(31, 2, 'hi', 'user', '2025-09-10 21:42:03', '2025-09-10 21:42:03'),
(32, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 21:42:03', '2025-09-10 21:42:03'),
(33, 2, 'Shipping policy', 'user', '2025-09-10 21:42:04', '2025-09-10 21:42:04'),
(34, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 21:42:04', '2025-09-10 21:42:04'),
(35, 2, 'Hêlo', 'user', '2025-09-10 21:42:07', '2025-09-10 21:42:07'),
(36, 2, 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể thử các gợi ý bên dưới hoặc liên hệ hotline để được hỗ trợ nhé.', 'bot', '2025-09-10 21:42:07', '2025-09-10 21:42:07'),
(37, 2, 'Hello', 'user', '2025-09-10 21:42:13', '2025-09-10 21:42:13'),
(38, 2, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-10 21:42:13', '2025-09-10 21:42:13'),
(39, 2, 'How to return goods?', 'user', '2025-09-10 21:42:34', '2025-09-10 21:42:34'),
(40, 2, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-10 21:42:34', '2025-09-10 21:42:34'),
(41, 2, 'helo', 'user', '2025-09-10 21:42:37', '2025-09-10 21:42:37'),
(42, 2, 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể thử các gợi ý bên dưới hoặc liên hệ hotline để được hỗ trợ nhé.', 'bot', '2025-09-10 21:42:37', '2025-09-10 21:42:37'),
(43, 2, 'hello bạn', 'user', '2025-09-10 21:42:44', '2025-09-10 21:42:44'),
(44, 2, 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể thử các gợi ý bên dưới hoặc liên hệ hotline để được hỗ trợ nhé.', 'bot', '2025-09-10 21:42:44', '2025-09-10 21:42:44'),
(45, 2, 'hello', 'user', '2025-09-10 21:42:48', '2025-09-10 21:42:48'),
(46, 2, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-10 21:42:48', '2025-09-10 21:42:48'),
(47, 2, 'Hello', 'user', '2025-09-10 22:14:45', '2025-09-10 22:14:45'),
(48, 2, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-10 22:14:45', '2025-09-10 22:14:45'),
(49, 2, 'Hi', 'user', '2025-09-10 22:14:49', '2025-09-10 22:14:49'),
(50, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 22:14:49', '2025-09-10 22:14:49'),
(51, 2, 'Shipping policy', 'user', '2025-09-10 22:14:55', '2025-09-10 22:14:55'),
(52, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 22:14:55', '2025-09-10 22:14:55'),
(53, 2, 'How to return goods?', 'user', '2025-09-10 22:14:56', '2025-09-10 22:14:56'),
(54, 2, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-10 22:14:56', '2025-09-10 22:14:56'),
(55, 2, 'How to return goods?', 'user', '2025-09-10 22:15:03', '2025-09-10 22:15:03'),
(56, 2, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-10 22:15:03', '2025-09-10 22:15:03'),
(57, 2, 'Hello , where do  shop form', 'user', '2025-09-10 22:15:23', '2025-09-10 22:15:23'),
(58, 2, 'Sorry, I don\'t understand your question. You can try the suggestions below or contact the hotline for support..', 'bot', '2025-09-10 22:15:23', '2025-09-10 22:15:23'),
(59, 2, 'Hello', 'user', '2025-09-10 22:37:15', '2025-09-10 22:37:15'),
(60, 2, 'Ah, Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-10 22:37:15', '2025-09-10 22:37:15'),
(61, 2, 'Shipping policy', 'user', '2025-09-10 22:44:54', '2025-09-10 22:44:54'),
(62, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 22:44:54', '2025-09-10 22:44:54'),
(63, 2, 'Shipping policy', 'user', '2025-09-10 23:05:38', '2025-09-10 23:05:38'),
(64, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 23:05:38', '2025-09-10 23:05:38'),
(65, 2, 'Current promotions', 'user', '2025-09-10 23:05:39', '2025-09-10 23:05:39'),
(66, 2, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-09-10 23:05:39', '2025-09-10 23:05:39'),
(67, 2, 'How to return goods?', 'user', '2025-09-10 23:05:40', '2025-09-10 23:05:40'),
(68, 2, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-10 23:05:40', '2025-09-10 23:05:40'),
(69, 2, 'Hello', 'user', '2025-09-10 23:05:42', '2025-09-10 23:05:42'),
(70, 2, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-10 23:05:42', '2025-09-10 23:05:42'),
(71, 2, 'Hello', 'user', '2025-09-10 23:39:35', '2025-09-10 23:39:35'),
(72, 2, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-10 23:39:35', '2025-09-10 23:39:35'),
(73, 2, 'Shipping policy', 'user', '2025-09-10 23:39:38', '2025-09-10 23:39:38'),
(74, 2, 'We offer free shipping for all orders over 500,000 VND.', 'bot', '2025-09-10 23:39:38', '2025-09-10 23:39:38'),
(75, 2, 'How to return goods?', 'user', '2025-09-10 23:39:43', '2025-09-10 23:39:43'),
(76, 2, 'You can return any item within 7 days of purchase, provided it is in its original condition. Please contact our support hotline to initiate a return.', 'bot', '2025-09-10 23:39:43', '2025-09-10 23:39:43'),
(77, 2, 'Current promotions', 'user', '2025-09-10 23:39:46', '2025-09-10 23:39:46'),
(78, 2, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-10 23:39:46', '2025-09-10 23:39:46'),
(79, 2, 'i want return', 'user', '2025-09-10 23:40:16', '2025-09-10 23:40:16'),
(80, 2, 'You can return any item within 7 days of purchase, provided it is in its original condition. Please contact our support hotline to initiate a return.', 'bot', '2025-09-10 23:40:16', '2025-09-10 23:40:16'),
(81, 2, 'hello', 'user', '2025-09-10 23:40:21', '2025-09-10 23:40:21'),
(82, 2, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-10 23:40:21', '2025-09-10 23:40:21'),
(83, 2, 'Shipping policy', 'user', '2025-09-10 23:44:59', '2025-09-10 23:44:59'),
(84, 2, 'We offer free shipping for all orders over 500,000 VND.', 'bot', '2025-09-10 23:44:59', '2025-09-10 23:44:59'),
(85, 2, 'Current promotions', 'user', '2025-09-10 23:45:01', '2025-09-10 23:45:01'),
(86, 2, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-10 23:45:01', '2025-09-10 23:45:01'),
(87, 2, 'How to return goods?', 'user', '2025-09-10 23:45:04', '2025-09-10 23:45:04'),
(88, 2, 'You can return any item within 7 days of purchase, provided it is in its original condition. Please contact our support hotline to initiate a return.', 'bot', '2025-09-10 23:45:04', '2025-09-10 23:45:04'),
(89, 2, 'Hello', 'user', '2025-09-10 23:45:08', '2025-09-10 23:45:08'),
(90, 2, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-10 23:45:08', '2025-09-10 23:45:08'),
(91, 2, 'Shipping policy', 'user', '2025-09-10 23:49:20', '2025-09-10 23:49:20'),
(92, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-10 23:49:20', '2025-09-10 23:49:20'),
(93, 2, 'Current promotions', 'user', '2025-09-10 23:49:21', '2025-09-10 23:49:21'),
(94, 2, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-09-10 23:49:21', '2025-09-10 23:49:21'),
(95, 2, 'How to return goods?', 'user', '2025-09-10 23:49:24', '2025-09-10 23:49:24'),
(96, 2, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-10 23:49:24', '2025-09-10 23:49:24'),
(97, 2, 'Hello', 'user', '2025-09-10 23:49:26', '2025-09-10 23:49:26'),
(98, 2, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-10 23:49:26', '2025-09-10 23:49:26'),
(99, 2, 'Shipping policy', 'user', '2025-09-11 00:23:24', '2025-09-11 00:23:24'),
(100, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-11 00:23:24', '2025-09-11 00:23:24'),
(101, 2, 'Thùy Linh đần', 'user', '2025-09-11 00:23:51', '2025-09-11 00:23:51'),
(102, 2, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-11 00:23:51', '2025-09-11 00:23:51'),
(103, 2, 'Shipping policy', 'user', '2025-09-11 00:24:29', '2025-09-11 00:24:29'),
(104, 2, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-11 00:24:29', '2025-09-11 00:24:29'),
(105, 2, 'Current promotions', 'user', '2025-09-11 00:24:35', '2025-09-11 00:24:35'),
(106, 2, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-09-11 00:24:35', '2025-09-11 00:24:35'),
(107, 2, 'How to return goods?', 'user', '2025-09-11 00:24:40', '2025-09-11 00:24:40'),
(108, 2, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-11 00:24:40', '2025-09-11 00:24:40'),
(109, 2, 'Hello', 'user', '2025-09-11 00:24:43', '2025-09-11 00:24:43'),
(110, 2, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-11 00:24:43', '2025-09-11 00:24:43'),
(111, 2, 'i want to return', 'user', '2025-09-11 00:24:51', '2025-09-11 00:24:51'),
(112, 2, 'You can return any item within 7 days of purchase, provided it is in its original condition. Please contact our support hotline to initiate a return.', 'bot', '2025-09-11 00:24:51', '2025-09-11 00:24:51'),
(113, 3, 'Hello', 'user', '2025-09-11 08:00:27', '2025-09-11 08:00:27'),
(114, 3, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-11 08:00:27', '2025-09-11 08:00:27'),
(115, 4, 'Shipping policy', 'user', '2025-09-11 08:53:37', '2025-09-11 08:53:37'),
(116, 4, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-11 08:53:37', '2025-09-11 08:53:37'),
(117, 4, 'Current promotions', 'user', '2025-09-11 08:53:39', '2025-09-11 08:53:39'),
(118, 4, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-09-11 08:53:39', '2025-09-11 08:53:39'),
(119, 4, 'How to return goods?', 'user', '2025-09-11 08:53:41', '2025-09-11 08:53:41'),
(120, 4, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-11 08:53:41', '2025-09-11 08:53:41'),
(121, 4, 'Hello', 'user', '2025-09-11 08:53:42', '2025-09-11 08:53:42'),
(122, 4, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-11 08:53:42', '2025-09-11 08:53:42'),
(123, 5, 'Helo', 'user', '2025-09-12 07:01:41', '2025-09-12 07:01:41'),
(124, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 07:01:41', '2025-09-12 07:01:41'),
(125, 5, 'Hello', 'user', '2025-09-12 07:01:45', '2025-09-12 07:01:45'),
(126, 5, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-12 07:01:45', '2025-09-12 07:01:45'),
(127, 5, 'Hello', 'user', '2025-09-12 09:23:55', '2025-09-12 09:23:55'),
(128, 5, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-12 09:23:55', '2025-09-12 09:23:55'),
(129, 5, 'Shipping policy', 'user', '2025-09-12 09:23:59', '2025-09-12 09:23:59'),
(130, 5, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-12 09:23:59', '2025-09-12 09:23:59'),
(131, 5, 'shipping refund', 'user', '2025-09-12 09:24:48', '2025-09-12 09:24:48'),
(132, 5, 'We offer free shipping for all orders over 500,000 VND.', 'bot', '2025-09-12 09:24:48', '2025-09-12 09:24:48'),
(133, 5, 'shipping exchange', 'user', '2025-09-12 09:25:42', '2025-09-12 09:25:42'),
(134, 5, 'We offer free shipping for all orders over 500,000 VND.', 'bot', '2025-09-12 09:25:42', '2025-09-12 09:25:42'),
(135, 5, 'exchange shipping', 'user', '2025-09-12 09:26:00', '2025-09-12 09:26:00'),
(136, 5, 'We offer free shipping for all orders over 500,000 VND.', 'bot', '2025-09-12 09:26:00', '2025-09-12 09:26:00'),
(137, 5, 'Hello', 'user', '2025-09-12 09:26:09', '2025-09-12 09:26:09'),
(138, 5, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-12 09:26:09', '2025-09-12 09:26:09'),
(139, 5, 'hêllo', 'user', '2025-09-12 09:26:18', '2025-09-12 09:26:18'),
(140, 5, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-12 09:26:18', '2025-09-12 09:26:18'),
(141, 5, 'kk', 'user', '2025-09-12 09:26:26', '2025-09-12 09:26:26'),
(142, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:26', '2025-09-12 09:26:26'),
(143, 5, '1', 'user', '2025-09-12 09:26:42', '2025-09-12 09:26:42'),
(144, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:42', '2025-09-12 09:26:42'),
(145, 5, '1', 'user', '2025-09-12 09:26:42', '2025-09-12 09:26:42'),
(146, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:42', '2025-09-12 09:26:42'),
(147, 5, '1', 'user', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(148, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(149, 5, '1', 'user', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(150, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(151, 5, '1', 'user', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(152, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(153, 5, '1', 'user', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(154, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(155, 5, '1', 'user', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(156, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(157, 5, '1', 'user', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(158, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(159, 5, '1', 'user', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(160, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:43', '2025-09-12 09:26:43'),
(161, 5, '1', 'user', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(162, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(163, 5, '1', 'user', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(164, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(165, 5, '1', 'user', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(166, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(167, 5, '1', 'user', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(168, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:44', '2025-09-12 09:26:44'),
(169, 5, '1', 'user', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(170, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(171, 5, '1', 'user', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(172, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(173, 5, '1', 'user', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(174, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(175, 5, '1', 'user', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(176, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:45', '2025-09-12 09:26:45'),
(177, 5, '1', 'user', '2025-09-12 09:26:46', '2025-09-12 09:26:46'),
(178, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:46', '2025-09-12 09:26:46'),
(179, 5, '1', 'user', '2025-09-12 09:26:46', '2025-09-12 09:26:46'),
(180, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:46', '2025-09-12 09:26:46'),
(181, 5, '1', 'user', '2025-09-12 09:26:46', '2025-09-12 09:26:46'),
(182, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:46', '2025-09-12 09:26:46'),
(183, 5, '1', 'user', '2025-09-12 09:26:46', '2025-09-12 09:26:46'),
(184, 5, '1', 'user', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(185, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(186, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(187, 5, '1', 'user', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(188, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(189, 5, '1', 'user', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(190, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(191, 5, '1', 'user', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(192, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(193, 5, '1', 'user', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(194, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:47', '2025-09-12 09:26:47'),
(195, 5, '1', 'user', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(196, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(197, 5, '1', 'user', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(198, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(199, 5, '1', 'user', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(200, 5, '1', 'user', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(201, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(202, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(203, 5, '1', 'user', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(204, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(205, 5, '1', 'user', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(206, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(207, 5, '1', 'user', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(208, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:48', '2025-09-12 09:26:48'),
(209, 5, '1', 'user', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(210, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(211, 5, '1', 'user', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(212, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(213, 5, '1', 'user', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(214, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(215, 5, '1', 'user', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(216, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(217, 5, '1', 'user', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(218, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:49', '2025-09-12 09:26:49'),
(219, 5, '1', 'user', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(220, 5, '1', 'user', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(221, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(222, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(223, 5, '1', 'user', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(224, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(225, 5, '1', 'user', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(226, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(227, 5, '1', 'user', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(228, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:50', '2025-09-12 09:26:50'),
(229, 5, '1', 'user', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(230, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(231, 5, '1', 'user', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(232, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(233, 5, '1', 'user', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(234, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(235, 5, '123', 'user', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(236, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(237, 5, '21', 'user', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(238, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(239, 5, '321', 'user', '2025-09-12 09:26:51', '2025-09-12 09:26:51'),
(240, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(241, 5, '321', 'user', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(242, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(243, 5, '312', 'user', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(244, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(245, 5, '312', 'user', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(246, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(247, 5, '312', 'user', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(248, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:52', '2025-09-12 09:26:52'),
(249, 5, '312', 'user', '2025-09-12 09:26:53', '2025-09-12 09:26:53'),
(250, 5, '321', 'user', '2025-09-12 09:26:53', '2025-09-12 09:26:53'),
(251, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:53', '2025-09-12 09:26:53'),
(252, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:53', '2025-09-12 09:26:53'),
(253, 5, '3', 'user', '2025-09-12 09:26:53', '2025-09-12 09:26:53'),
(254, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:53', '2025-09-12 09:26:53'),
(255, 5, '213', 'user', '2025-09-12 09:26:53', '2025-09-12 09:26:53'),
(256, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(257, 5, '213', 'user', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(258, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(259, 5, '213', 'user', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(260, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(261, 5, '21', 'user', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(262, 5, '312', 'user', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(263, 5, '312', 'user', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(264, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(265, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(266, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(267, 5, '3', 'user', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(268, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:54', '2025-09-12 09:26:54'),
(269, 5, '123', 'user', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(270, 5, '123', 'user', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(271, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(272, 5, '12', 'user', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(273, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(274, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(275, 5, '312', 'user', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(276, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(277, 5, '312', 'user', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(278, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(279, 5, '312', 'user', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(280, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:55', '2025-09-12 09:26:55'),
(281, 5, '3', 'user', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(282, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(283, 5, '213', 'user', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(284, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(285, 5, '213', 'user', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(286, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(287, 5, '21', 'user', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(288, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(289, 5, '312', 'user', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(290, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(291, 5, '312', 'user', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(292, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:56', '2025-09-12 09:26:56'),
(293, 5, '321', 'user', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(294, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(295, 5, '12', 'user', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(296, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(297, 5, '1', 'user', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(298, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(299, 5, '1', 'user', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(300, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(301, 5, '1', 'user', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(302, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:57', '2025-09-12 09:26:57'),
(303, 5, '1', 'user', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(304, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(305, 5, '1', 'user', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(306, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(307, 5, '1', 'user', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(308, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(309, 5, '1', 'user', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(310, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(311, 5, '1', 'user', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(312, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(313, 5, '1', 'user', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(314, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(315, 5, '1', 'user', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(316, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:58', '2025-09-12 09:26:58'),
(317, 5, '1', 'user', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(318, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(319, 5, '1', 'user', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(320, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(321, 5, '1', 'user', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(322, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(323, 5, '1', 'user', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(324, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(325, 5, '1', 'user', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(326, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:26:59', '2025-09-12 09:26:59'),
(327, 5, '1', 'user', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(328, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(329, 5, '1', 'user', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(330, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(331, 5, '1', 'user', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(332, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(333, 5, '1', 'user', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(334, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(335, 5, '1', 'user', '2025-09-12 09:27:00', '2025-09-12 09:27:00'),
(336, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(337, 5, '1', 'user', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(338, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(339, 5, '1', 'user', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(340, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(341, 5, '1', 'user', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(342, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(343, 5, '1', 'user', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(344, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(345, 5, '1', 'user', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(346, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:01', '2025-09-12 09:27:01'),
(347, 5, '11', 'user', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(348, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(349, 5, '1', 'user', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(350, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(351, 5, '1', 'user', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(352, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(353, 5, '1', 'user', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(354, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:02', '2025-09-12 09:27:02'),
(355, 5, '1', 'user', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(356, 5, '1', 'user', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(357, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(358, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(359, 5, '1', 'user', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(360, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(361, 5, '1', 'user', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(362, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:03', '2025-09-12 09:27:03'),
(363, 5, '1', 'user', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(364, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(365, 5, '1', 'user', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(366, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(367, 5, '1', 'user', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(368, 5, '1', 'user', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(369, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(370, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(371, 5, '1', 'user', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(372, 5, '1', 'user', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(373, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(374, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:04', '2025-09-12 09:27:04'),
(375, 5, '1', 'user', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(376, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(377, 5, '1', 'user', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(378, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(379, 5, '1', 'user', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(380, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(381, 5, '1', 'user', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(382, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(383, 5, '1', 'user', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(384, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:05', '2025-09-12 09:27:05'),
(385, 5, '1', 'user', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(386, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(387, 5, '1', 'user', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(388, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(389, 5, '1', 'user', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(390, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(391, 5, '1', 'user', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(392, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(393, 5, '1', 'user', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(394, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:06', '2025-09-12 09:27:06'),
(395, 5, '1', 'user', '2025-09-12 09:27:07', '2025-09-12 09:27:07'),
(396, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:07', '2025-09-12 09:27:07'),
(397, 5, '1', 'user', '2025-09-12 09:27:07', '2025-09-12 09:27:07'),
(398, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:07', '2025-09-12 09:27:07'),
(399, 5, '1', 'user', '2025-09-12 09:27:07', '2025-09-12 09:27:07'),
(400, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:27:07', '2025-09-12 09:27:07'),
(401, 5, 'ư', 'user', '2025-09-12 09:28:28', '2025-09-12 09:28:28'),
(402, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:28:28', '2025-09-12 09:28:28'),
(403, 5, 's', 'user', '2025-09-12 09:31:10', '2025-09-12 09:31:10'),
(404, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:10', '2025-09-12 09:31:10'),
(405, 5, 's', 'user', '2025-09-12 09:31:10', '2025-09-12 09:31:10'),
(406, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:10', '2025-09-12 09:31:10'),
(407, 5, 's', 'user', '2025-09-12 09:31:10', '2025-09-12 09:31:10'),
(408, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:10', '2025-09-12 09:31:10'),
(409, 5, 's', 'user', '2025-09-12 09:31:11', '2025-09-12 09:31:11'),
(410, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:11', '2025-09-12 09:31:11'),
(411, 5, 's', 'user', '2025-09-12 09:31:11', '2025-09-12 09:31:11'),
(412, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:11', '2025-09-12 09:31:11'),
(413, 5, 's', 'user', '2025-09-12 09:31:12', '2025-09-12 09:31:12'),
(414, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:12', '2025-09-12 09:31:12'),
(415, 5, ';', 'user', '2025-09-12 09:31:13', '2025-09-12 09:31:13'),
(416, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:13', '2025-09-12 09:31:13'),
(417, 5, ';', 'user', '2025-09-12 09:31:13', '2025-09-12 09:31:13'),
(418, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:13', '2025-09-12 09:31:13'),
(419, 5, ';', 'user', '2025-09-12 09:31:13', '2025-09-12 09:31:13'),
(420, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(421, 5, ';', 'user', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(422, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(423, 5, ';', 'user', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(424, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(425, 5, ';', 'user', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(426, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(427, 5, ';', 'user', '2025-09-12 09:31:14', '2025-09-12 09:31:14');
INSERT INTO `messages` (`id`, `conversation_id`, `content`, `sender`, `created_at`, `updated_at`) VALUES
(428, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:14', '2025-09-12 09:31:14'),
(429, 5, ';', 'user', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(430, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(431, 5, ';', 'user', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(432, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(433, 5, ';', 'user', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(434, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(435, 5, ';', 'user', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(436, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:15', '2025-09-12 09:31:15'),
(437, 5, ';', 'user', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(438, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(439, 5, ';', 'user', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(440, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(441, 5, ';', 'user', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(442, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(443, 5, ';', 'user', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(444, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(445, 5, ';', 'user', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(446, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:16', '2025-09-12 09:31:16'),
(447, 5, ';', 'user', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(448, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(449, 5, ';', 'user', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(450, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(451, 5, ';', 'user', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(452, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(453, 5, ';', 'user', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(454, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(455, 5, ';', 'user', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(456, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:17', '2025-09-12 09:31:17'),
(457, 5, ';', 'user', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(458, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(459, 5, ';', 'user', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(460, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(461, 5, ';', 'user', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(462, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(463, 5, ';', 'user', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(464, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(465, 5, ';', 'user', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(466, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:18', '2025-09-12 09:31:18'),
(467, 5, ';', 'user', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(468, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(469, 5, ';', 'user', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(470, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(471, 5, ';', 'user', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(472, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(473, 5, ';', 'user', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(474, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(475, 5, ';', 'user', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(476, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:19', '2025-09-12 09:31:19'),
(477, 5, ';', 'user', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(478, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(479, 5, ';', 'user', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(480, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(481, 5, ';', 'user', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(482, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(483, 5, ';', 'user', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(484, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(485, 5, ';', 'user', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(486, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:20', '2025-09-12 09:31:20'),
(487, 5, ';', 'user', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(488, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(489, 5, ';', 'user', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(490, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(491, 5, ';', 'user', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(492, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(493, 5, ';', 'user', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(494, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(495, 5, ';', 'user', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(496, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:21', '2025-09-12 09:31:21'),
(497, 5, ';', 'user', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(498, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(499, 5, ';', 'user', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(500, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(501, 5, ';', 'user', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(502, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(503, 5, ';', 'user', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(504, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:22', '2025-09-12 09:31:22'),
(505, 5, ';', 'user', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(506, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(507, 5, ';', 'user', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(508, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(509, 5, ';', 'user', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(510, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(511, 5, ';', 'user', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(512, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(513, 5, ';', 'user', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(514, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:23', '2025-09-12 09:31:23'),
(515, 5, ';', 'user', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(516, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(517, 5, ';', 'user', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(518, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(519, 5, ';', 'user', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(520, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(521, 5, ';', 'user', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(522, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(523, 5, ';', 'user', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(524, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:24', '2025-09-12 09:31:24'),
(525, 5, ';', 'user', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(526, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(527, 5, ';', 'user', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(528, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(529, 5, ';', 'user', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(530, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(531, 5, ';', 'user', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(532, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:25', '2025-09-12 09:31:25'),
(533, 5, ';', 'user', '2025-09-12 09:31:26', '2025-09-12 09:31:26'),
(534, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:26', '2025-09-12 09:31:26'),
(535, 5, ';', 'user', '2025-09-12 09:31:26', '2025-09-12 09:31:26'),
(536, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:26', '2025-09-12 09:31:26'),
(537, 5, ';', 'user', '2025-09-12 09:31:26', '2025-09-12 09:31:26'),
(538, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:26', '2025-09-12 09:31:26'),
(539, 5, ';', 'user', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(540, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(541, 5, ';', 'user', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(542, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(543, 5, ';', 'user', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(544, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(545, 5, ';', 'user', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(546, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:27', '2025-09-12 09:31:27'),
(547, 5, ';', 'user', '2025-09-12 09:31:28', '2025-09-12 09:31:28'),
(548, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:28', '2025-09-12 09:31:28'),
(549, 5, ';', 'user', '2025-09-12 09:31:28', '2025-09-12 09:31:28'),
(550, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:28', '2025-09-12 09:31:28'),
(551, 5, ';', 'user', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(552, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(553, 5, ';', 'user', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(554, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(555, 5, ';', 'user', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(556, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(557, 5, ';', 'user', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(558, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:29', '2025-09-12 09:31:29'),
(559, 5, ';', 'user', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(560, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(561, 5, ';', 'user', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(562, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(563, 5, ';', 'user', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(564, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(565, 5, ';', 'user', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(566, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(567, 5, ';', 'user', '2025-09-12 09:31:30', '2025-09-12 09:31:30'),
(568, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:31', '2025-09-12 09:31:31'),
(569, 5, ';', 'user', '2025-09-12 09:31:31', '2025-09-12 09:31:31'),
(570, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:31', '2025-09-12 09:31:31'),
(571, 5, ';', 'user', '2025-09-12 09:31:31', '2025-09-12 09:31:31'),
(572, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:31', '2025-09-12 09:31:31'),
(573, 5, ';', 'user', '2025-09-12 09:31:31', '2025-09-12 09:31:31'),
(574, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:31', '2025-09-12 09:31:31'),
(575, 5, ';', 'user', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(576, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(577, 5, ';', 'user', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(578, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(579, 5, ';', 'user', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(580, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(581, 5, ';', 'user', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(582, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(583, 5, ';', 'user', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(584, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(585, 5, ';', 'user', '2025-09-12 09:31:32', '2025-09-12 09:31:32'),
(586, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:33', '2025-09-12 09:31:33'),
(587, 5, ';', 'user', '2025-09-12 09:31:33', '2025-09-12 09:31:33'),
(588, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:33', '2025-09-12 09:31:33'),
(589, 5, ';', 'user', '2025-09-12 09:31:33', '2025-09-12 09:31:33'),
(590, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:33', '2025-09-12 09:31:33'),
(591, 5, ';', 'user', '2025-09-12 09:31:33', '2025-09-12 09:31:33'),
(592, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:33', '2025-09-12 09:31:33'),
(593, 5, ';', 'user', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(594, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(595, 5, ';', 'user', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(596, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(597, 5, ';', 'user', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(598, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(599, 5, ';', 'user', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(600, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(601, 5, ';', 'user', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(602, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:34', '2025-09-12 09:31:34'),
(603, 5, ';', 'user', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(604, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(605, 5, ';', 'user', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(606, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(607, 5, ';', 'user', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(608, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(609, 5, ';', 'user', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(610, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:35', '2025-09-12 09:31:35'),
(611, 5, ';', 'user', '2025-09-12 09:31:37', '2025-09-12 09:31:37'),
(612, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:31:37', '2025-09-12 09:31:37'),
(613, 5, '<script>alert(\"failed\")</script>', 'user', '2025-09-12 09:32:12', '2025-09-12 09:32:12'),
(614, 5, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-09-12 09:32:12', '2025-09-12 09:32:12'),
(615, 6, 'Shipping policy', 'user', '2025-09-28 12:52:26', '2025-09-28 12:52:26'),
(616, 6, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-09-28 12:52:26', '2025-09-28 12:52:26'),
(617, 6, 'Current promotions', 'user', '2025-09-28 12:52:28', '2025-09-28 12:52:28'),
(618, 6, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-09-28 12:52:28', '2025-09-28 12:52:28'),
(619, 6, 'How to return goods?', 'user', '2025-09-28 12:52:31', '2025-09-28 12:52:31'),
(620, 6, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-09-28 12:52:31', '2025-09-28 12:52:31'),
(621, 6, 'Hello', 'user', '2025-09-28 12:52:32', '2025-09-28 12:52:32'),
(622, 6, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-09-28 12:52:32', '2025-09-28 12:52:32'),
(623, 7, 'Shipping policy', 'user', '2025-10-26 13:01:46', '2025-10-26 13:01:46'),
(624, 7, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-10-26 13:01:46', '2025-10-26 13:01:46'),
(625, 7, 'Shipping policy', 'user', '2025-10-26 13:01:51', '2025-10-26 13:01:51'),
(626, 7, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-10-26 13:01:51', '2025-10-26 13:01:51'),
(627, 7, 'Hi', 'user', '2025-10-26 13:01:54', '2025-10-26 13:01:54'),
(628, 7, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-10-26 13:01:54', '2025-10-26 13:01:54'),
(629, 7, 'Hello', 'user', '2025-10-26 14:14:10', '2025-10-26 14:14:10'),
(630, 7, 'Hello, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-10-26 14:14:10', '2025-10-26 14:14:10'),
(631, 7, 'Shipping policy', 'user', '2025-10-26 14:14:12', '2025-10-26 14:14:12'),
(632, 7, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-10-26 14:14:12', '2025-10-26 14:14:12'),
(633, 7, 'How to return goods?', 'user', '2025-10-26 14:14:13', '2025-10-26 14:14:13'),
(634, 7, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-10-26 14:14:13', '2025-10-26 14:14:13'),
(635, 8, 'Current promotions', 'user', '2025-10-26 14:36:11', '2025-10-26 14:36:11'),
(636, 8, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-10-26 14:36:11', '2025-10-26 14:36:11'),
(637, 9, 'Shipping policy', 'user', '2025-10-28 06:17:36', '2025-10-28 06:17:36'),
(638, 9, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-10-28 06:17:36', '2025-10-28 06:17:36'),
(639, 9, 'Current promotions', 'user', '2025-10-28 06:17:38', '2025-10-28 06:17:38'),
(640, 9, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-10-28 06:17:38', '2025-10-28 06:17:38'),
(641, 9, 'How to return goods?', 'user', '2025-10-28 06:17:40', '2025-10-28 06:17:40'),
(642, 9, 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.', 'bot', '2025-10-28 06:17:40', '2025-10-28 06:17:40'),
(643, 10, 'Shipping policy', 'user', '2025-11-19 09:44:07', '2025-11-19 09:44:07'),
(644, 10, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-11-19 09:44:07', '2025-11-19 09:44:07'),
(645, 10, 'hi one', 'user', '2025-11-19 09:44:13', '2025-11-19 09:44:13'),
(646, 10, 'Hi, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-11-19 09:44:13', '2025-11-19 09:44:13'),
(647, 11, 'Current promotions', 'user', '2025-11-24 03:56:54', '2025-11-24 03:56:54'),
(648, 11, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-11-24 03:56:54', '2025-11-24 03:56:54'),
(649, 11, 'hi', 'user', '2025-11-24 03:56:56', '2025-11-24 03:56:56'),
(650, 11, 'Hi, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-11-24 03:56:56', '2025-11-24 03:56:56'),
(651, 12, 'Shipping policy', 'user', '2025-11-27 12:44:24', '2025-11-27 12:44:24'),
(652, 12, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-11-27 12:44:24', '2025-11-27 12:44:24'),
(653, 13, 'Current promotions', 'user', '2025-11-28 07:57:20', '2025-11-28 07:57:20'),
(654, 13, 'There is currently a 10% discount on all lipsticks. Check it out!', 'bot', '2025-11-28 07:57:20', '2025-11-28 07:57:20'),
(655, 14, 'Shipping policy', 'user', '2025-12-04 02:49:44', '2025-12-04 02:49:44'),
(656, 14, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-12-04 02:49:44', '2025-12-04 02:49:44'),
(657, 15, 'Shipping policy', 'user', '2025-12-04 12:18:14', '2025-12-04 12:18:14'),
(658, 15, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-12-04 12:18:14', '2025-12-04 12:18:14'),
(659, 15, 'hi', 'user', '2025-12-04 13:01:58', '2025-12-04 13:01:58'),
(660, 15, 'Hi, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-12-04 13:01:58', '2025-12-04 13:01:58'),
(661, 15, 'chào', 'user', '2025-12-04 13:02:04', '2025-12-04 13:02:04'),
(662, 15, 'I\'m sorry, I don\'t understand the question. You can try one of the suggestions or contact our support team.', 'bot', '2025-12-04 13:02:04', '2025-12-04 13:02:04'),
(663, 15, 'scsc', 'user', '2025-12-04 13:07:16', '2025-12-04 13:07:16'),
(664, 15, 'csc', 'user', '2025-12-04 13:08:21', '2025-12-04 13:08:21'),
(665, 15, 'ssds', 'user', '2025-12-04 13:08:39', '2025-12-04 13:08:39'),
(666, 15, 'csc', 'user', '2025-12-04 13:09:18', '2025-12-04 13:09:18'),
(667, 15, 'cscsc', 'user', '2025-12-04 13:09:30', '2025-12-04 13:09:30'),
(668, 15, 'hi bạn', 'user', '2025-12-04 13:11:22', '2025-12-04 13:11:22'),
(669, 15, 'Hi, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-12-04 13:11:22', '2025-12-04 13:11:22'),
(670, 15, 'chào bạn', 'user', '2025-12-04 13:11:28', '2025-12-04 13:11:28'),
(671, 15, 'vui vì gặp bạn', 'user', '2025-12-04 13:11:51', '2025-12-04 13:11:51'),
(672, 15, 'chào admin', 'user', '2025-12-04 13:40:21', '2025-12-04 13:40:21'),
(673, 15, 'cscsc', 'user', '2025-12-04 13:41:13', '2025-12-04 13:41:13'),
(674, 15, 'jhii', 'user', '2025-12-04 13:47:58', '2025-12-04 13:47:58'),
(675, 15, 'Shipping policy', 'user', '2025-12-04 13:48:25', '2025-12-04 13:48:25'),
(676, 15, 'We offer free shipping on orders over 500,000 VND.', 'bot', '2025-12-04 13:48:25', '2025-12-04 13:48:25'),
(677, 15, 'hi', 'user', '2025-12-04 13:58:18', '2025-12-04 13:58:18'),
(678, 15, 'Hi, I am the automatic chat bot of Mint Cosmetics shop.', 'bot', '2025-12-04 13:58:18', '2025-12-04 13:58:18'),
(679, 15, 'dạ', 'user', '2025-12-04 13:58:37', '2025-12-04 13:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_21_082803_add_extra_fields_to_users_table', 2),
(5, '2025_08_22_071241_create_categories_table', 3),
(6, '2025_08_23_114502_create_attributes_table', 4),
(7, '2025_08_23_114720_create_attribute_values_table', 4),
(8, '2025_08_23_114745_create_attribute_category_table', 4),
(9, '2025_08_23_140728_create_brands_table', 5),
(10, '2025_08_23_140758_create_products_table', 5),
(11, '2025_08_23_140837_create_product_variants_table', 5),
(12, '2025_08_23_140857_create_attribute_value_product_variant_table', 5),
(13, '2025_08_25_172203_create_coupons_table', 6),
(14, '2025_08_26_173628_add_soft_deletes_to_products_table', 7),
(15, '2025_08_27_150212_create_orders_table', 8),
(16, '2025_08_27_150344_create_order_items_table', 8),
(17, '2025_09_04_034136_add_image_to_categories_table', 9),
(18, '2025_09_07_140721_add_review_fields_to_order_items_table', 10),
(20, '2025_09_07_145810_create_reviews_table', 11),
(21, '2025_09_08_144726_add_media_to_reviews_table', 12),
(22, '2025_09_09_112529_add_coupon_details_to_orders_table', 13),
(23, '2025_09_09_143904_create_settings_table', 14),
(24, '2025_09_09_150329_add_timestamps_to_settings_table', 15),
(25, '2025_09_10_162505_create_conversations_table', 16),
(26, '2025_09_10_162634_create_messages_table', 16),
(27, '2025_09_10_170303_create_chatbot_rules_table', 17),
(28, '2025_09_11_055840_create_chatbot_replies_table', 18),
(29, '2025_09_11_055846_create_chatbot_keywords_table', 18),
(30, '2025_10_28_133723_create_blog_posts_table', 19),
(31, '2025_11_27_172856_create_chat_tables', 20),
(32, '2025_11_27_194413_create_guests_table', 21),
(33, '2025_11_28_150112_create_customers_table', 22),
(34, '2025_11_28_170031_add_login_tracking_to_customers_table', 23),
(35, '2025_11_29_171618_add_customer_id_to_orders_table', 24),
(36, '2025_12_09_095443_create_suppliers_table', 24),
(37, '2025_12_09_113709_create_purchase_orders_table', 25),
(38, '2026_01_12_100816_add_avatar_to_users_table', 26),
(39, '2026_01_23_201229_create_notifications_table', 27),
(40, '2026_01_23_211531_create_carts_table', 28),
(41, '2026_01_23_213857_add_unique_constraint_to_carts_table', 29),
(42, '2026_02_03_104803_create_visits_table', 30);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('06457653-aa4a-4169-838a-40b60e04c837', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":48,\"customer_name\":\"Tr\\u00e0 Ph\\u1ea1m\",\"total_price\":1999,\"message\":\"New order #48 has been placed.\",\"link\":\"http:\\/\\/mint-cosmetics.local\\/admin\\/orders\\/48\"}', '2026-02-24 10:36:26', '2026-02-19 10:10:55', '2026-02-24 10:36:26'),
('2f9d5152-803f-41e5-94db-2163104323fd', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":46,\"customer_name\":\"admin ablue\",\"total_price\":22054,\"message\":\"New order #46 has been placed.\",\"link\":\"http:\\/\\/mint-cosmetics.local\\/admin\\/orders\\/46\"}', '2026-02-24 10:36:26', '2026-01-25 13:49:24', '2026-02-24 10:36:26'),
('31213307-6045-4416-be53-d15748eee5b0', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":44,\"customer_name\":\"admin ablue\",\"total_price\":3221508,\"message\":\"New order #44 has been placed.\",\"link\":\"http:\\/\\/mint-cosmetics.local\\/admin\\/orders\\/44\"}', '2026-02-24 10:36:26', '2026-01-24 02:00:16', '2026-02-24 10:36:26'),
('727a031d-74dd-4499-b9ca-b0564a5c7e24', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":45,\"customer_name\":\"admin ablue\",\"total_price\":10755,\"message\":\"New order #45 has been placed.\",\"link\":\"http:\\/\\/mint-cosmetics.local\\/admin\\/orders\\/45\"}', '2026-02-24 10:36:26', '2026-01-24 02:42:54', '2026-02-24 10:36:26'),
('7ab5ac01-b94f-4e36-b5c3-be5b1d432b0c', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":43,\"customer_name\":\"admin ablue\",\"total_price\":2133332,\"message\":\"New order #43 has been placed.\",\"link\":\"http:\\/\\/mint-cosmetics.local\\/admin\\/orders\\/43\"}', '2026-01-23 14:05:44', '2026-01-23 14:03:28', '2026-01-23 14:05:44'),
('88ff8fdc-f91f-4b1d-9305-8d831b1245a7', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":47,\"customer_name\":\"admin ablue\",\"total_price\":10755,\"message\":\"New order #47 has been placed.\",\"link\":\"http:\\/\\/mint-cosmetics.local\\/admin\\/orders\\/47\"}', '2026-02-24 10:36:26', '2026-01-25 14:05:35', '2026-02-24 10:36:26'),
('b36271d5-3030-4691-be7c-565e618a58a8', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":49,\"customer_name\":\"Tr\\u00e0 Ph\\u1ea1m\",\"total_price\":100000,\"message\":\"New order #49 has been placed.\",\"link\":\"http:\\/\\/mint-cosmetics.local\\/admin\\/orders\\/49\"}', '2026-02-25 10:33:34', '2026-02-24 11:11:20', '2026-02-25 10:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `total_price` decimal(15,2) NOT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `total_price`, `coupon_code`, `discount_amount`, `status`, `payment_method`, `transaction_id`, `notes`, `first_name`, `last_name`, `address`, `phone`, `email`, `created_at`, `updated_at`, `customer_id`) VALUES
(42, 34.00, NULL, 0.00, 'completed', NULL, NULL, NULL, 'Trà', 'Phạm', 'mỹ thịnh-mỹ lộc-nam định', '0868720028', 'trapham24065@gmail.com', '2025-12-01 12:43:31', '2025-12-01 14:13:39', 1),
(43, 2133332.00, NULL, 0.00, 'processing', NULL, NULL, NULL, 'admin', 'ablue', 'Hà Nội', '0868720023', 'devblueteam2022@gmail.com', '2026-01-23 14:03:27', '2026-01-23 14:03:45', 1),
(44, 3221508.00, NULL, 0.00, 'pending', NULL, NULL, NULL, 'admin', 'ablue', 'Hà Nội', '0868720023', 'trapham@gmail.com', '2026-01-24 02:00:16', '2026-01-24 02:00:16', 1),
(45, 10755.00, NULL, 0.00, 'pending', NULL, NULL, NULL, 'admin', 'ablue', 'Hà Nội', '0868720023', 'tr@gmail.com', '2026-01-24 02:42:54', '2026-01-24 02:42:54', 1),
(46, 22054.00, NULL, 0.00, 'processing', NULL, NULL, NULL, 'admin', 'ablue', 'Hà Nội', '0868720023', 'trapham@gmail.com', '2026-01-25 13:49:23', '2026-01-25 13:49:39', 1),
(47, 10755.00, NULL, 0.00, 'pending', 'vnpay', NULL, NULL, 'admin', 'ablue', 'Hà Nội', '0868720023', 'tra@gmail.com', '2026-01-25 14:05:35', '2026-01-25 14:05:35', 1),
(48, 1999.00, NULL, 0.00, 'pending', 'vnpay', NULL, NULL, 'Trà', 'Phạm', 'Hà Nội', '0868720028', 'trapham24065@gmail.com', '2026-02-19 10:10:53', '2026-02-19 10:10:53', 1),
(49, 100000.00, NULL, 0.00, 'processing', 'vnpay', NULL, NULL, 'Trà', 'Phạm', 'Hà Nội', '0868720028', 'canhet1212@gmail.com', '2026-02-24 11:11:18', '2026-02-24 11:11:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `review_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_items_review_token_unique` (`review_token`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_product_variant_id_foreign` (`product_variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_variant_id`, `product_name`, `quantity`, `price`, `review_token`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(52, 42, 15, 112, 'Trà Phạmtest (scscscsc / cscacasc)', 1, 34.00, 'pQBd1mjQce4yoe8ixDDbq0GfWGtRJY7y', NULL, '2025-12-01 12:43:31', '2025-12-01 14:13:34'),
(53, 43, 27, 122, 'cscsc ()', 2, 1066666.00, NULL, NULL, '2026-01-23 14:03:27', '2026-01-23 14:03:27'),
(54, 44, 26, 121, 'cscsc123 ()', 2, 10755.00, NULL, NULL, '2026-01-24 02:00:16', '2026-01-24 02:00:16'),
(55, 44, 27, 122, 'cscsc ()', 3, 1066666.00, NULL, NULL, '2026-01-24 02:00:16', '2026-01-24 02:00:16'),
(56, 45, 26, 121, 'cscsc123 ()', 1, 10755.00, NULL, NULL, '2026-01-24 02:42:54', '2026-01-24 02:42:54'),
(57, 46, 15, 112, 'Trà Phạmtest (scscscsc / cscacasc)', 16, 34.00, NULL, NULL, '2026-01-25 13:49:23', '2026-01-25 13:49:23'),
(58, 46, 26, 121, 'cscsc123 ()', 2, 10755.00, NULL, NULL, '2026-01-25 13:49:23', '2026-01-25 13:49:23'),
(59, 47, 26, 121, 'cscsc123 ()', 1, 10755.00, NULL, NULL, '2026-01-25 14:05:35', '2026-01-25 14:05:35'),
(60, 48, 10, 63, 'Trà Phạm1212 ()', 1, 1999.00, NULL, NULL, '2026-02-19 10:10:53', '2026-02-19 10:10:53'),
(61, 49, 16, 114, 'test123 ()', 1, 100000.00, NULL, NULL, '2026-02-24 11:11:18', '2026-02-24 11:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('canhet1212@gmail.com', '$2y$12$qCMgpTA3Qfi.d1if4.Y04enn1WsT7w9AAlmvuVoTOhEHY5ifhvD1m', '2025-08-21 07:49:53'),
('maitran.050704@gmail.com', '$2y$12$0rY6pgKaPKds61bxvprbKubO7W0SoKRkZPo6oymxiXgzNOm8qvvIm', '2025-08-22 04:08:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_in_stock` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list_image` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `slug`, `quantity_in_stock`, `description`, `image`, `list_image`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Trà Phạm', 'tra-pham', 0, 'dâdwadawd', 'products/OLJyfojP69HybNWDt0u8UJRSt9vNXe3tTJ7f9fug.jpg', '[\"products/gallery/C6vQVpN53jSRgXSwwAcjUIaJBTrxNRKhaFLwX6WZ.jpg\", \"products/gallery/vcBNZHcdecCpGF3cItJa7Ws9hKmvnyxgbaeQJmPW.jpg\", \"products/gallery/4dZltt5uKUoBpcd2MSlrHa4YBdcDGDRfuoQp14WY.jpg\"]', 1, '2025-08-26 11:30:00', '2025-08-27 08:18:18', '2025-08-27 08:18:18'),
(2, 1, 1, 'Trà Phạm12', 'tra-pham12', 0, 'ưeqwewqewqe', NULL, NULL, 1, '2025-08-27 02:41:58', '2025-08-27 08:14:17', '2025-08-27 08:14:17'),
(3, 1, NULL, 'Free Shipping12ssd', 'free-shipping12ssd', 0, 'dsvđss', 'products/GdXW5DEy05T66QxSLQWchdUi8JtODJ5xb58j1WiM.jpg', '[\"products/gallery/mrToLA4eY8zX7jdPrQF1EXQ2x7VaXaG8WHGZdruK.jpg\", \"products/gallery/1tb8YJUWSwC2tb36nqY8sAtdzQNRp2k1c6orG64H.jpg\", \"products/gallery/1b3xYo7DNwdJzFfOuTOeHBC6mQCqEXbcP2C7jaOg.jpg\", \"products/gallery/LTzSkqBH0G6xZNfVes2Om4Ma59Ka5J3zRg70zc2l.jpg\"]', 1, '2025-08-27 02:43:10', '2025-08-27 08:18:16', '2025-08-27 08:18:16'),
(4, 1, 1, 'wwewqewq', 'wwewqewq', 0, 'ewqewqeqwewq', 'products/7oWJ5nUslZ1ZuPlh9jsrV1nRH607q8hZq7noiWkU.jpg', NULL, 1, '2025-08-27 03:13:04', '2025-08-27 08:18:15', '2025-08-27 08:18:15'),
(5, 1, 1, 'dfg', 'dfghjk', 0, 'wdfghjkl;', 'products/QuLynLJzEitjf5rqdFnsuKaSX1JgxmhyivC7GMGT.jpg', NULL, 1, '2025-08-27 03:31:11', '2025-08-27 08:18:13', '2025-08-27 08:18:13'),
(6, 1, 1, 'Son YSL Slim Velvet Radical Matte Lipstick 1966 Rouge Libre – Màu Đỏ Gạch', 'son-ysl-slim-velvet-radical-matte-lipstick-1966-rouge-libre-mau-do-gach', 0, '<p>Son YSL Slim Velvet Radical Matte Lipstick 1966 Rouge Libre- M&agrave;u Đỏ Gạch&ndash; một trong những thỏi son mang m&agrave;u sắc si&ecirc;u đỉnh nh&agrave; YSL với t&ocirc;ng m&agrave;u đỏ gạch c&oacute; thể mix and match với đa dạng phong c&aacute;ch. Cũng ch&iacute;nh v&igrave; l&yacute; do đ&oacute; m&agrave; đ&acirc;y l&agrave; m&agrave;u son &ldquo;si&ecirc;u b&atilde;o&rdquo; nh&agrave; YSL v&agrave; lu&ocirc;n được chị em săn đ&oacute;n.</p>', 'products/fxZrKfosA3PHm1o9YetBbBduJ1ZW3hfeFt37r1jf.png', '[\"products/gallery/5n5xBUpcq4GxflEEte7wlgqvnMVZOr8TpaGZABoI.jpg\", \"products/gallery/0ksuvnIUyR6Tdn5UBg8rIwkNyBTiGFA2QltfFIBX.jpg\", \"products/gallery/VRyKrDFaJFAT2L6kgdpr7Cd3go10MKZAXHZQFQls.jpg\", \"products/gallery/9aSWMTG8c9JHDMBrqJcxbjV5aOAwiyO9klp6ngwB.jpg\", \"products/gallery/RUauZboij75Zi0Empcac0fweDk25vJBUFyHE3x7Q.webp\"]', 1, '2025-08-27 08:20:49', '2026-02-25 16:47:12', NULL),
(8, 1, NULL, 'Trà Phạm1212121', 'tra-pham1212121', 0, '<p>dsdasdassa</p>', 'products/LRpHqylyw11maDUIRxiTqgt6LoSvZVos46Puw2CV.jpg', '[\"products/gallery/JgM6F51RjgR9avKkdfzcsLPuA0j1KQjXzB1xtL1F.jpg\", \"products/gallery/gWgCc577qPgpeRcb6yGdwgzbIZ0v77vmqOCSJT6u.jpg\", \"products/gallery/D42vphZmOUhKpJttdEdz8tqzzGVQhwbjKVSfCqaT.jpg\", \"products/gallery/D89dLtJXLF1jHls5oAaBqmWT3B7qaDlUw11gPdhC.jpg\"]', 1, '2025-08-27 09:03:10', '2026-02-25 16:47:38', NULL),
(9, 1, 1, 'Free Shipping1212', 'free-shipping1212', 0, '<p>qqwqwqwqwqwqw</p>', 'products/kQGhdLmz94nyyr19yfguWaUbgmHHHYfGkarYeWun.jpg', '[\"products/gallery/3jQucaPVn7GTXQWthYp96X0Yplq6KatGdvWOW5F6.png\", \"products/gallery/FJqQGGhDgdyF5yjUyaAB2bi1XsdjAwXzlyB1vcud.jpg\", \"products/gallery/KBJl1rHDiNkCyQA9cx6h7c61CaYQBRhkxp1BNPkW.jpg\"]', 1, '2025-08-30 02:47:40', '2026-02-25 16:51:04', NULL),
(10, 1, NULL, 'Trà Phạm1212', 'tra-pham1212', 0, '<p>cscscscscs</p>', 'products/UVw4y1BbJaTvchDiXaPOAhLG39U0uQRKHjgVWmHJ.jpg', '[\"products/gallery/YpHgw2SHaco8RIQekc6F9KklSLZTBBFbiM0Ht1q5.jpg\", \"products/gallery/xt1zwgO8AQvv1WiOpzu2PgfseZVdZbtDiFFbfWRW.webp\", \"products/gallery/AkEFmo9ke863hn6nBz5zwS4skqNLKvoldi1c8nzZ.webp\"]', 1, '2025-08-30 02:52:50', '2026-02-25 16:48:02', NULL),
(11, 1, 1, 'test123456', 'test1234', 0, NULL, 'products/qZgRpiHPr32k7CXpw2UI7ikgIiz3cvidn3bbIvk1.jpg', '[\"products/gallery/dQ7pd4bc0dj1WHS20tNNAafMPG6HpA6dMLw5UuUU.jpg\", \"products/gallery/upNUjqrQwuvJ3WKEVYK7EwnXyJPazdVPRwBlfOSv.jpg\", \"products/gallery/BLmAopInstIdimog2myxdSSTGMTAM5UNvWiH51dH.jpg\", \"products/gallery/6osDAaDmpmgfROnG40dnZfVJmvW2hMlYQj5OuV5p.webp\"]', 1, '2025-09-04 03:04:23', '2025-09-28 17:28:01', NULL),
(12, 1, NULL, 'Trà Phạm1212121212', 'tra-pham1212121212', 0, NULL, 'products/uIqinDrVbmNyxYlP4wM9ia6V1blA57FfjcRrgV12.jpg', NULL, 1, '2025-09-09 02:21:12', '2026-02-25 16:49:42', NULL),
(13, 7, 1, 'Sản Phẩm 1121212', 'san-pham-1', 0, '<p>Sản phẩm si&ecirc;u xịn s&ograve;</p>', 'products/Z5fF8JGgUNrUgIoPm46JXTmUaPKOQD12YIS82yEO.jpg', NULL, 1, '2025-09-09 09:43:08', '2026-02-25 16:46:48', NULL),
(15, 1, 3, 'Trà Phạmtest', 'tra-phamtest', 0, '<p>fbfbfbfb</p>', 'products/jItxAlmcYKUs5IpztPNu438V1tqs4uHEuuEZm7HG.jpg', NULL, 1, '2025-09-11 19:14:43', '2026-02-25 13:31:18', NULL),
(16, 1, NULL, 'test123', 'test123', 0, '<p>Traddpk reaoaddggg</p>', 'products/bgs6yTBH4VgGESLZqvzdZBJzwDIuphanyfIXaLbm.jpg', NULL, 1, '2025-09-12 09:45:22', '2025-10-28 09:33:13', NULL),
(17, 9, 2, 'sản phẩm 2', 'san-pham-2', 0, 'Trà Phạm 123456', 'products/nwtqxXNrlQnqcOaKMimWml9NKhtx1dv5xHLIPBj1.jpg', '[\"products/gallery/nhkq0ADWry9Cpgd8Gc4gSxQHHhZVqnmEqlTZ3UGo.jpg\", \"products/gallery/ZuikGfcDKtA85XM9jzMzZRNdFdFfT0emhNhlVaZO.jpg\", \"products/gallery/Vf5LfAi8DNrVUKJwBm9XmmXqZMVcv1SjSwO6WmH5.jpg\"]', 1, '2025-09-28 16:41:22', '2025-10-26 16:32:41', '2025-10-26 16:32:41'),
(18, 7, 2, 'Test12341234', 'test12341234', 0, '<p>Tr&agrave; phạm 123456<span style=\"font-family: impact, sans-serif;\">grgrgggggggg</span></p>\r\n<p><span style=\"font-family: impact, sans-serif;\"><img style=\"width: min(224px, 100%); aspect-ratio: 224 / 225; display: block; margin-left: auto; margin-right: auto;\" src=\"https://ucarecdn.com/b97d0442-a2a2-4f1f-854c-a867dc6661ac/-/preview/\" sizes=\"(min-width: 224px) 224px, 100vw\" srcset=\"https://ucarecdn.com/b97d0442-a2a2-4f1f-854c-a867dc6661ac/-/resize/100x/ 100w,https://ucarecdn.com/b97d0442-a2a2-4f1f-854c-a867dc6661ac/-/resize/200x/ 200w,https://ucarecdn.com/b97d0442-a2a2-4f1f-854c-a867dc6661ac/-/preview/ 224w\"></span></p>', 'products/gdMsMlOj0IyNOF1S57LqaeuInAuSynS9X0hGjIPf.jpg', '[\"products/gallery/dZJ2EjUncKtI2kH0Gk9fjRx5ItRW7Y5JXr1O8Zm9.jpg\"]', 1, '2025-10-26 16:01:56', '2025-10-26 16:32:21', '2025-10-26 16:32:21'),
(21, 9, 2, 'Trà Phạm121', 'tra-pham121', 0, '<p>grgrg1212</p>', 'products/YRjONvgXy9jR1iDTh4t5mAklKxXtRYbgBUb7c6al.jpg', NULL, 1, '2025-10-26 16:37:07', '2025-10-26 16:37:19', '2025-10-26 16:37:19'),
(22, 7, 4, 'Trà Phạm12121212', 'tra-pham12121212', 0, '<p>12121212</p>', 'products/LcbbGVJkyxA8rCm86PImzwYgc6O11rrARr8aWQDs.jpg', NULL, 1, '2025-10-26 16:40:10', '2025-10-26 16:43:35', '2025-10-26 16:43:35'),
(24, 10, 8, 'Trà Phạm11111', 'tra-pham11111', 0, '<p>zxcvbnm1111</p>', 'products/aomObpfnh3V774qkhUcvx3p7ms2CkLWEJv196YTR.jpg', NULL, 1, '2025-10-26 16:57:49', '2025-10-26 16:57:53', '2025-10-26 16:57:53'),
(25, 13, 7, 'Trà Phạm12312496', 'tra-pham12312496', 0, '<p>22e2e2e2e</p>', 'products/NuNRGFDQA4Hpfo8CGsp2aBRBac2I7Fnqiv8ZgRXD.jpg', NULL, 1, '2025-10-26 17:02:30', '2025-10-26 17:11:15', '2025-10-26 17:11:15'),
(26, 7, 3, 'cscsc123', 'cscsc12', 0, '<p>12122124444</p>\r\n<p><img src=\"../../../storage/products/descriptions/1765247620_images.jpg\" alt=\"\" width=\"300\" height=\"120\"></p>', 'products/gKHOLSzn8qqfVnyLYivXJVigGZ0zUXeKcCknJMZy.jpg', NULL, 1, '2025-10-28 09:32:25', '2026-01-25 09:54:12', NULL),
(27, 1, 2, 'cscsc', 'cscsc', 0, '<p>cscscsscscsc</p>', NULL, NULL, 1, '2026-01-19 15:12:12', '2026-02-25 10:47:25', NULL),
(28, 1, 1, 'scscscsc', 'scscscsc', 0, '<p>scsc</p>', NULL, NULL, 1, '2026-01-19 15:51:52', '2026-02-25 10:47:02', NULL),
(30, 1, 1, 'Son Phấn', 'son-phan', 0, '<p>Sản phẩm son chiết suất 100% từ thi&ecirc;n nhi&ecirc;n</p>', NULL, NULL, 1, '2026-01-24 16:41:42', '2026-01-24 16:41:42', NULL),
(31, 1, 1, 'Trà Phạm12344532', 'tra-pham12344532', 0, NULL, NULL, NULL, 1, '2026-01-25 03:29:53', '2026-01-25 03:29:53', NULL),
(32, 1, 1, 'Sản phẩm test lần 2', 'san-pham-test-lan-2', 0, '<p class=\"Body\">From a purely practical standpoint, hair insulates us and helps us regulate our body temperature. From a cultural standpoint, it can do everything from signify our identities, beliefs and affiliations, to help us attract a mate.</p>\r\n<p class=\"Body\">Most importantly,&nbsp;<strong>how we&nbsp;<em>feel&nbsp;</em>about our hair</strong> can influence our entire self-image. Those of us who have ever experienced a bad hair day or at-home color disaster can likely relate to not wanting to leave the house without our emotional support beanie.</p>', NULL, NULL, 1, '2026-01-25 08:36:22', '2026-01-25 08:36:22', NULL),
(43, 1, 5, 'Trà Phạm121212', 'tra-pham121212-1', 0, '<p>hyhyhyh12</p>', NULL, NULL, 1, '2026-02-22 13:28:47', '2026-02-25 16:49:07', '2026-02-25 16:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `discount_price` decimal(15,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `price`, `discount_price`, `stock`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 12222220.00, 122222.00, 56, NULL, '2025-08-26 11:30:00', '2025-08-27 07:39:22'),
(2, 2, NULL, 12000000.00, 12.00, 50, NULL, '2025-08-27 02:41:58', '2025-08-27 02:41:58'),
(3, 3, NULL, 2000000.00, NULL, 50, NULL, '2025-08-27 02:43:10', '2025-08-27 07:37:49'),
(4, 4, NULL, 12000000.00, NULL, 50, NULL, '2025-08-27 03:13:04', '2025-08-27 03:13:04'),
(5, 5, NULL, 1200000.00, 1056000.00, 50, NULL, '2025-08-27 03:31:11', '2025-08-27 07:44:24'),
(6, 5, NULL, 12000000.00, NULL, 20, NULL, '2025-08-27 07:38:44', '2025-08-27 07:44:24'),
(7, 1, NULL, 12222220.00, NULL, 30, NULL, '2025-08-27 07:39:22', '2025-08-27 07:39:22'),
(53, 8, 'TRA-PHAM123456', 12000000.00, 11000000.00, 2130, NULL, '2025-08-30 02:44:52', '2026-02-25 16:47:38'),
(63, 10, 'sccs', 2000.00, 1999.00, 42, NULL, '2025-08-31 21:12:35', '2026-02-25 16:48:02'),
(64, 9, 'tainghe112121', 2000.00, 1999.00, 12, NULL, '2025-08-31 21:12:57', '2026-02-25 16:51:04'),
(66, 6, '12121adb', 1222.00, 1221.00, 45, NULL, '2025-09-01 02:05:50', '2026-02-25 16:47:12'),
(67, 6, '22222adb', 1224.00, 1221.00, 30, NULL, '2025-09-01 02:05:50', '2026-02-25 16:47:12'),
(74, 12, '121212abc', 1212121.00, 1212121.00, 12, NULL, '2025-09-09 09:54:17', '2026-02-25 16:49:42'),
(96, 13, 'ewdwdwd12121', 220000.00, NULL, 15, NULL, '2025-09-11 19:13:53', '2026-02-25 16:46:48'),
(109, 11, NULL, 1200000.00, 1200000.00, 50, NULL, '2025-09-11 19:21:42', '2025-09-11 19:21:42'),
(112, 15, 'dêdđ', 14000.00, 34.00, 30, NULL, '2025-09-11 19:34:36', '2026-02-25 13:31:18'),
(113, 15, 'tra222222', 12000.00, NULL, 20, NULL, '2025-09-11 19:34:36', '2026-02-25 13:31:18'),
(114, 16, NULL, 100000.00, NULL, 19, NULL, '2025-09-12 09:45:22', '2026-02-24 11:11:18'),
(115, 17, NULL, 1500000.00, 1350000.00, 30, NULL, '2025-09-28 16:41:22', '2025-09-28 16:41:22'),
(116, 18, NULL, 120000.00, 105600.00, 100, NULL, '2025-10-26 16:01:56', '2025-10-26 16:01:56'),
(117, 21, NULL, 1212.00, 1067.00, 11, NULL, '2025-10-26 16:37:07', '2025-10-26 16:37:07'),
(118, 22, NULL, 12121212.00, 10666667.00, 12121, NULL, '2025-10-26 16:40:10', '2025-10-26 16:40:10'),
(119, 24, NULL, 1212121.00, 1200000.00, 11, NULL, '2025-10-26 16:57:49', '2025-10-26 16:57:49'),
(120, 25, NULL, 1212121.00, 1200000.00, 12, NULL, '2025-10-26 17:02:30', '2025-10-26 17:02:30'),
(121, 26, '233323', 12222.00, 10755.00, 11, NULL, '2025-10-28 09:32:25', '2026-01-25 14:05:35'),
(122, 27, 'fefefe', 1212121.00, 1066666.00, 50, NULL, '2026-01-19 15:12:12', '2026-02-25 10:47:25'),
(123, 28, 'TRA-PHAM121212', 1212121.00, 106667.00, 0, NULL, '2026-01-19 15:51:52', '2026-02-25 10:47:02'),
(124, 30, 'SON-UXOBUJ', 12000.00, 10560.00, 200, NULL, '2026-01-24 16:41:42', '2026-02-04 13:18:55'),
(125, 31, 'TRA-PHAM12344532', 1222222.00, NULL, 0, NULL, '2026-01-25 03:29:53', '2026-01-25 03:29:53'),
(126, 32, 'SAN-YOUEKU', 1222222.00, 1222222.00, 0, NULL, '2026-01-25 08:36:22', '2026-01-25 08:36:22'),
(127, 32, 'SAN-MZUD8Q', 122121212.00, 1222222.00, 0, NULL, '2026-01-25 08:36:22', '2026-01-25 08:36:22'),
(128, 43, 'TRA-PHAM', 12121212.00, 11878788.00, 0, NULL, '2026-02-22 13:28:47', '2026-02-22 13:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE IF NOT EXISTS `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text COLLATE utf8mb4_unicode_ci,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_code_unique` (`code`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `code`, `supplier_id`, `total_amount`, `status`, `note`, `received_at`, `created_at`, `updated_at`) VALUES
(1, 'PO-20251210-DCHA', 1, 20000000.00, 'completed', NULL, '2025-12-10 02:17:22', '2025-12-10 02:11:47', '2025-12-10 02:17:22'),
(2, 'PO-20251210-M6NI', 1, 400000000.00, 'completed', NULL, '2025-12-10 02:18:14', '2025-12-10 02:18:10', '2025-12-10 02:18:14'),
(3, 'PO-20260204-FP7N', 1, 4000000.00, 'completed', NULL, '2026-02-04 13:18:55', '2026-02-04 13:18:47', '2026-02-04 13:18:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE IF NOT EXISTS `purchase_order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `import_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_product_variant_id_foreign` (`product_variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `product_variant_id`, `quantity`, `import_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 53, 100, 200000.00, 20000000.00, '2025-12-10 02:11:47', '2025-12-10 02:11:47'),
(2, 2, 53, 2000, 200000.00, 400000000.00, '2025-12-10 02:18:10', '2025-12-10 02:18:10'),
(3, 3, 124, 200, 20000.00, 4000000.00, '2026-02-04 13:18:47', '2026-02-04 13:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `reviewer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `media` json DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_order_item_id_unique` (`order_item_id`),
  KEY `reviews_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('I7YcxpXgepdCrCJkbL9Raq4QqkR2btevKCBOmiLi', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVHdQM290RzVnN0JTVVVUSnVXQXAwTjc4NGxuSFdxU1pTcE9DSGVzZiI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9taW50LWNvc21ldGljcy5sb2NhbC9zaXRlbWFwLnhtbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1772520303),
('JKVKthq1jgH00gJXAy0A6USKiLtJZ0td1f1rtIKN', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU3NLS1k0cGs5OGNkazV6bW1EdGV4aWlzZGlzRnlXMmNxalhwWUY0SCI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9taW50LWNvc21ldGljcy5sb2NhbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1772630461),
('NQ3RtSGNFlBtgc8jleZJfPpNmoi9wCsB8i08bFTL', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZTIwanJEWlkzYUtUWk1mNHRyWkZCWUVCa2dNQ2hScEpJMVpmTVdxTSI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9taW50LWNvc21ldGljcy5sb2NhbC93aXNobGlzdC9pZHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1772633626),
('w0ovhch26F4XX9ufJYetZlWVcdzUSqWNA3HTLYGJ', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiY29tWXVmMnZQMHRiSjZWZDZJTzJseWhBQllLOWJtcmNCeDJCQmIyRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772630449);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `options` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `options`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Mint Cosmetics', 'text', 'general', 'Website name', 'Website display name.', NULL, 1, 1, NULL, NULL),
(2, 'contact_email', 'contact@mintcosmetics.com', 'email', 'general', 'Contact email', NULL, NULL, 1, 2, NULL, NULL),
(3, 'contact_phone', '0123456789', 'text', 'general', 'Phone number', NULL, NULL, 1, 3, NULL, NULL),
(4, 'vietqr_bank_id', '970436', 'select', 'payment', 'VietQR Bank', NULL, '\"{\\\"970436\\\":\\\"Vietcombank\\\",\\\"970422\\\":\\\"MB Bank\\\",\\\"970423\\\":\\\"TPBank\\\"}\"', 1, 1, NULL, NULL),
(5, 'vietqr_account_no', '1032850005', 'text', 'payment', 'Account number VietQR', NULL, NULL, 1, 2, NULL, NULL),
(6, 'vietqr_prefix', 'DH', 'text', 'payment', 'Order prefix', NULL, NULL, 1, 3, NULL, NULL),
(7, 'mail_from_name', 'Mint Cosmetics', 'text', 'email', 'Email sender name', NULL, NULL, 1, 1, NULL, NULL),
(8, 'mail_from_address', 'trapham24065@gmail.com', 'email', 'email', 'Email address', NULL, NULL, 1, 2, NULL, NULL),
(9, 'site_logo', 'settings/tOBorFKcqGUgT7FLhN7Xf7YjIilSqtcRFyKGf0ho.png', 'image', 'general', 'Website Logo', 'Upload logo for your website (PNG, JPG, SVG).', NULL, 1, 0, NULL, NULL),
(10, 'mail_driver', 'smtp', 'select', 'email', 'Mail driver', NULL, '\"{\\\"smtp\\\":\\\"SMTP\\\",\\\"sendmail\\\":\\\"Sendmail\\\",\\\"mailgun\\\":\\\"Mailgun\\\",\\\"ses\\\":\\\"SES\\\",\\\"postmark\\\":\\\"Postmark\\\",\\\"resend\\\":\\\"Resend\\\",\\\"log\\\":\\\"Log\\\",\\\"array\\\":\\\"Array\\\"}\"', 1, 3, NULL, NULL),
(11, 'mail_host', 'smtp.gmail.com', 'text', 'email', 'SMTP host', NULL, NULL, 1, 4, NULL, NULL),
(12, 'mail_port', '587', 'text', 'email', 'SMTP port', NULL, NULL, 1, 5, NULL, NULL),
(13, 'mail_username', 'trapham24065@gmail.com', 'text', 'email', 'SMTP username', NULL, NULL, 1, 6, NULL, NULL),
(14, 'mail_password', 'eyJpdiI6Ijl3R2RkbzdJNk9USWxpbWR0cDBaZVE9PSIsInZhbHVlIjoiMnMxRldkcm8vbEQvQXFneFBGeEZIbDdsMWhmSEpDbkx1OU5nbURQVzBOYz0iLCJtYWMiOiI3OTI2ZjYyMzdhNzYzMGI0ZGI4Nzc0NDkwNDQyYjQ2MWE3NjE2M2QyMzE5YTBlNGU3MTg4MDU1YTY3NjgwMWM1IiwidGFnIjoiIn0=', 'password', 'email', 'SMTP password', NULL, NULL, 1, 7, NULL, NULL),
(15, 'mail_encryption', 'tls', 'select', 'email', 'Encryption', NULL, '\"{\\\"tls\\\":\\\"TLS\\\",\\\"ssl\\\":\\\"SSL\\\",\\\"\\\":\\\"None\\\"}\"', 1, 8, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `phone`, `address`, `contact_person`, `note`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tổng Kho Miền Bắc', 'trapham24065@gmail.com', '0912345678', 'Lô C5, Khu Công Nghiệp Thăng Long, Huyện Đông Anh', 'Phạm Trà', 'Phạm Trà', 1, '2025-12-09 03:42:26', '2025-12-09 03:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `status`, `remember_token`, `last_login_at`, `last_login_ip`, `created_at`, `updated_at`) VALUES
(1, 'Admin User123', 'truongthuylinh10102004@gmail.com', 'avatars/jXS13VVHvL55CvuyGjMKLLrnwldtKQ1PGrKMe6h6.jpg', NULL, '$2y$12$LsarAjTpdAlh/qZuFUb8cOvzCX7w/pS1lNEP38z9uqCh6AJUw6on6', 1, 'o6IOeiXT90NycXYaU1Emdfh284gLIKez9U79yz0BBK0ZzgdUD6pdqcTm6joG', '2026-02-28 13:11:27', '::1', '2025-08-20 22:32:00', '2026-02-28 13:11:27');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

DROP TABLE IF EXISTS `visits`;
CREATE TABLE IF NOT EXISTS `visits` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `visited_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `ip_address`, `user_agent`, `visited_at`, `created_at`, `updated_at`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 05:34:56', '2026-02-03 05:34:56', '2026-02-03 05:34:56'),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 05:34:56', '2026-02-03 05:34:56', '2026-02-03 05:34:56'),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:23:47', '2026-02-03 07:23:47', '2026-02-03 07:23:47'),
(4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:23:48', '2026-02-03 07:23:48', '2026-02-03 07:23:48'),
(5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:26:46', '2026-02-03 07:26:46', '2026-02-03 07:26:46'),
(6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:27:37', '2026-02-03 07:27:37', '2026-02-03 07:27:37'),
(7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:30:38', '2026-02-03 07:30:38', '2026-02-03 07:30:38'),
(8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:31:44', '2026-02-03 07:31:44', '2026-02-03 07:31:44'),
(9, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:31:51', '2026-02-03 07:31:51', '2026-02-03 07:31:51'),
(10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:32:00', '2026-02-03 07:32:00', '2026-02-03 07:32:00'),
(11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:32:32', '2026-02-03 07:32:32', '2026-02-03 07:32:32'),
(12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:35:45', '2026-02-03 07:35:45', '2026-02-03 07:35:45'),
(13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:35:55', '2026-02-03 07:35:55', '2026-02-03 07:35:55'),
(14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:36:03', '2026-02-03 07:36:03', '2026-02-03 07:36:03'),
(15, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:36:43', '2026-02-03 07:36:43', '2026-02-03 07:36:43'),
(16, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:37:40', '2026-02-03 07:37:40', '2026-02-03 07:37:40'),
(17, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:37:49', '2026-02-03 07:37:49', '2026-02-03 07:37:49'),
(18, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:37:58', '2026-02-03 07:37:58', '2026-02-03 07:37:58'),
(19, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:38:09', '2026-02-03 07:38:09', '2026-02-03 07:38:09'),
(20, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:38:37', '2026-02-03 07:38:37', '2026-02-03 07:38:37'),
(21, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:38:42', '2026-02-03 07:38:42', '2026-02-03 07:38:42'),
(22, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:39:21', '2026-02-03 07:39:21', '2026-02-03 07:39:21'),
(23, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:48:15', '2026-02-03 07:48:15', '2026-02-03 07:48:15'),
(24, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:48:30', '2026-02-03 07:48:30', '2026-02-03 07:48:30'),
(25, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:03', '2026-02-03 07:49:03', '2026-02-03 07:49:03'),
(26, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:03', '2026-02-03 07:49:03', '2026-02-03 07:49:03'),
(27, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:05', '2026-02-03 07:49:05', '2026-02-03 07:49:05'),
(28, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:06', '2026-02-03 07:49:06', '2026-02-03 07:49:06'),
(29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:07', '2026-02-03 07:49:07', '2026-02-03 07:49:07'),
(30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:08', '2026-02-03 07:49:08', '2026-02-03 07:49:08'),
(31, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:10', '2026-02-03 07:49:10', '2026-02-03 07:49:10'),
(32, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:10', '2026-02-03 07:49:10', '2026-02-03 07:49:10'),
(33, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:12', '2026-02-03 07:49:12', '2026-02-03 07:49:12'),
(34, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:21', '2026-02-03 07:49:21', '2026-02-03 07:49:21'),
(35, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:21', '2026-02-03 07:49:21', '2026-02-03 07:49:21'),
(36, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:25', '2026-02-03 07:49:25', '2026-02-03 07:49:25'),
(37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:25', '2026-02-03 07:49:25', '2026-02-03 07:49:25'),
(38, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:27', '2026-02-03 07:49:27', '2026-02-03 07:49:27'),
(39, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:27', '2026-02-03 07:49:27', '2026-02-03 07:49:27'),
(40, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:30', '2026-02-03 07:49:30', '2026-02-03 07:49:30'),
(41, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:30', '2026-02-03 07:49:30', '2026-02-03 07:49:30'),
(42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:31', '2026-02-03 07:49:31', '2026-02-03 07:49:31'),
(43, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:37', '2026-02-03 07:49:37', '2026-02-03 07:49:37'),
(44, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:37', '2026-02-03 07:49:37', '2026-02-03 07:49:37'),
(45, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:39', '2026-02-03 07:49:39', '2026-02-03 07:49:39'),
(46, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:42', '2026-02-03 07:49:42', '2026-02-03 07:49:42'),
(47, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 07:49:44', '2026-02-03 07:49:44', '2026-02-03 07:49:44'),
(48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:15:50', '2026-02-03 08:15:50', '2026-02-03 08:15:50'),
(49, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:15:51', '2026-02-03 08:15:51', '2026-02-03 08:15:51'),
(50, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:12', '2026-02-03 08:16:12', '2026-02-03 08:16:12'),
(51, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:12', '2026-02-03 08:16:12', '2026-02-03 08:16:12'),
(52, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:14', '2026-02-03 08:16:14', '2026-02-03 08:16:14'),
(53, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:14', '2026-02-03 08:16:14', '2026-02-03 08:16:14'),
(54, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:18', '2026-02-03 08:16:18', '2026-02-03 08:16:18'),
(55, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:18', '2026-02-03 08:16:18', '2026-02-03 08:16:18'),
(56, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:19', '2026-02-03 08:16:19', '2026-02-03 08:16:19'),
(57, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:19', '2026-02-03 08:16:19', '2026-02-03 08:16:19'),
(58, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:20', '2026-02-03 08:16:20', '2026-02-03 08:16:20'),
(59, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:20', '2026-02-03 08:16:20', '2026-02-03 08:16:20'),
(60, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:16:27', '2026-02-03 08:16:27', '2026-02-03 08:16:27'),
(61, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:17:05', '2026-02-03 08:17:05', '2026-02-03 08:17:05'),
(62, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:17:05', '2026-02-03 08:17:05', '2026-02-03 08:17:05'),
(63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:20:10', '2026-02-03 08:20:10', '2026-02-03 08:20:10'),
(64, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:20:11', '2026-02-03 08:20:11', '2026-02-03 08:20:11'),
(65, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:20:58', '2026-02-03 08:20:58', '2026-02-03 08:20:58'),
(66, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:20:59', '2026-02-03 08:20:59', '2026-02-03 08:20:59'),
(67, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:21:17', '2026-02-03 08:21:17', '2026-02-03 08:21:17'),
(68, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:21:17', '2026-02-03 08:21:17', '2026-02-03 08:21:17'),
(69, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:21:39', '2026-02-03 08:21:39', '2026-02-03 08:21:39'),
(70, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:21:40', '2026-02-03 08:21:40', '2026-02-03 08:21:40'),
(71, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:21:44', '2026-02-03 08:21:44', '2026-02-03 08:21:44'),
(72, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 08:21:44', '2026-02-03 08:21:44', '2026-02-03 08:21:44'),
(73, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 17:22:22', '2026-02-03 17:22:22', '2026-02-03 17:22:22'),
(74, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-10 03:44:07', '2026-02-10 03:44:07', '2026-02-10 03:44:07'),
(75, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 10:07:01', '2026-02-19 10:07:01', '2026-02-19 10:07:01'),
(76, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 10:07:08', '2026-02-19 10:07:08', '2026-02-19 10:07:08'),
(77, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 11:39:11', '2026-02-22 11:39:11', '2026-02-22 11:39:11'),
(78, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 10:24:09', '2026-02-24 10:24:09', '2026-02-24 10:24:09'),
(79, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 10:24:09', '2026-02-24 10:24:09', '2026-02-24 10:24:09'),
(80, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 05:08:34', '2026-02-25 05:08:34', '2026-02-25 05:08:34'),
(81, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 07:26:27', '2026-02-26 07:26:27', '2026-02-26 07:26:27'),
(82, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 07:26:27', '2026-02-26 07:26:27', '2026-02-26 07:26:27'),
(83, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:26:35', '2026-02-27 10:26:35', '2026-02-27 10:26:35'),
(84, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-28 07:02:52', '2026-02-28 07:02:52', '2026-02-28 07:02:52'),
(85, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:40:06', '2026-03-03 06:40:06', '2026-03-03 06:40:06'),
(86, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 13:20:58', '2026-03-04 13:20:58', '2026-03-04 13:20:58');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_category`
--
ALTER TABLE `attribute_category`
  ADD CONSTRAINT `attribute_category_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attribute_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attribute_value_product_variant`
--
ALTER TABLE `attribute_value_product_variant`
  ADD CONSTRAINT `attribute_value_product_variant_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attribute_value_product_variant_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chatbot_keywords`
--
ALTER TABLE `chatbot_keywords`
  ADD CONSTRAINT `chatbot_keywords_chatbot_reply_id_foreign` FOREIGN KEY (`chatbot_reply_id`) REFERENCES `chatbot_replies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_participation_id_foreign` FOREIGN KEY (`participation_id`) REFERENCES `chat_participation` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_message_notifications`
--
ALTER TABLE `chat_message_notifications`
  ADD CONSTRAINT `chat_message_notifications_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_message_notifications_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `chat_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_message_notifications_participation_id_foreign` FOREIGN KEY (`participation_id`) REFERENCES `chat_participation` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_participation`
--
ALTER TABLE `chat_participation`
  ADD CONSTRAINT `chat_participation_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
