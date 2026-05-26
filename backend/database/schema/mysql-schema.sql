/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `affiliate_product_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliate_product_post` (
  `affiliate_product_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`affiliate_product_id`,`post_id`),
  KEY `affiliate_product_post_post_id_foreign` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `affiliate_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliate_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amazon_asin` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Amazon Standard Identification Number',
  `product_name_snapshot` text COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Name of product at time of adding',
  `price` decimal(10,2) DEFAULT NULL,
  `amazon_url` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Direct affiliate link (optional if generated elsewhere)',
  `your_notes` text COLLATE utf8mb3_unicode_ci COMMENT 'Why you recommend it, keywords, etc.',
  `main_image_url_snapshot` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Snapshot of main image URL (from Amazon)',
  `image_urls` json DEFAULT NULL,
  `try_on_image_url` text COLLATE utf8mb3_unicode_ci,
  `item_type` text COLLATE utf8mb3_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `local_image_path` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `try_on_type` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `try_on_anchor` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `try_on_scale_default` decimal(5,3) NOT NULL DEFAULT '0.100',
  `try_on_offset_y` int NOT NULL DEFAULT '0',
  `seo_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `schema_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Product',
  PRIMARY KEY (`id`),
  UNIQUE KEY `affiliate_products_amazon_asin_unique` (`amazon_asin`),
  KEY `affiliate_products_category_id_foreign` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `old_values` text COLLATE utf8mb3_unicode_ci,
  `new_values` text COLLATE utf8mb3_unicode_ci,
  `ip_address` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `image` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `seo_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `og_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_image` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `schema_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'CollectionPage',
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `category_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category_post` (
  `category_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`post_id`),
  KEY `category_post_post_id_foreign` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contact_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'new',
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `content_placements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_placements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_key` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `section_key` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `block_type` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'product_rail',
  `title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `settings` json DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_placements_page_key_section_key_unique` (`page_key`,`section_key`),
  KEY `content_placements_page_key_is_enabled_sort_order_index` (`page_key`,`is_enabled`,`sort_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cookie_consent_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cookie_consent_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cookie_consent_id` bigint unsigned NOT NULL,
  `action` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `previous_state` json DEFAULT NULL,
  `new_state` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cookie_consent_logs_cookie_consent_id_foreign` (`cookie_consent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cookie_consents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cookie_consents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `visitor_id` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(128) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `essential` tinyint(1) NOT NULL DEFAULT '1',
  `analytics` tinyint(1) NOT NULL DEFAULT '0',
  `marketing` tinyint(1) NOT NULL DEFAULT '0',
  `preferences` tinyint(1) NOT NULL DEFAULT '0',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cookie_consents_user_id_foreign` (`user_id`),
  KEY `cookie_consents_visitor_id_index` (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `current_gold_prices`;
/*!50001 DROP VIEW IF EXISTS `current_gold_prices`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `current_gold_prices` AS SELECT 
 1 AS `id`,
 1 AS `date_recorded`,
 1 AS `timestamp_recorded`,
 1 AS `currency_code`,
 1 AS `price_per_ounce`,
 1 AS `price_per_gram_24k`,
 1 AS `price_per_gram_22k`,
 1 AS `price_per_gram_18k`,
 1 AS `price_per_gram_14k`,
 1 AS `price_per_gram_10k`,
 1 AS `market_open`,
 1 AS `market_high`,
 1 AS `market_low`,
 1 AS `market_close`,
 1 AS `volume`,
 1 AS `source`,
 1 AS `is_active`,
 1 AS `created_at`,
 1 AS `updated_at`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `current_material_prices`;
/*!50001 DROP VIEW IF EXISTS `current_material_prices`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `current_material_prices` AS SELECT 
 1 AS `id`,
 1 AS `material_type`,
 1 AS `material_name`,
 1 AS `material_category`,
 1 AS `unit_type`,
 1 AS `price_per_unit`,
 1 AS `currency_code`,
 1 AS `purity_grade`,
 1 AS `quality_grade`,
 1 AS `supplier`,
 1 AS `minimum_order_quantity`,
 1 AS `date_updated`,
 1 AS `price_valid_until`,
 1 AS `market_region`,
 1 AS `is_active`,
 1 AS `created_at`,
 1 AS `updated_at`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `current_zakat_rates`;
/*!50001 DROP VIEW IF EXISTS `current_zakat_rates`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `current_zakat_rates` AS SELECT 
 1 AS `id`,
 1 AS `calculation_year`,
 1 AS `nisab_gold_grams`,
 1 AS `nisab_silver_grams`,
 1 AS `zakat_percentage`,
 1 AS `gold_price_per_gram`,
 1 AS `silver_price_per_gram`,
 1 AS `currency_code`,
 1 AS `lunar_year_days`,
 1 AS `holding_period_days`,
 1 AS `is_active`,
 1 AS `date_effective`,
 1 AS `created_at`,
 1 AS `updated_at`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `custom_jewelry_designs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_jewelry_designs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `jewelry_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `materials` json NOT NULL,
  `customizations` json NOT NULL,
  `estimated_price` decimal(10,2) DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `preview_image` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_jewelry_designs_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `daily_metal_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_metal_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `price_date` date NOT NULL,
  `base_currency` varchar(3) COLLATE utf8mb3_unicode_ci NOT NULL,
  `metal_symbol` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL,
  `price_per_unit` decimal(20,8) NOT NULL,
  `unit` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL,
  `source` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_metal_prices_unique` (`price_date`,`base_currency`,`metal_symbol`),
  KEY `idx_daily_metal_prices_symbol_currency_date` (`metal_symbol`,`base_currency`,`price_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `diamond_pricings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diamond_pricings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `carat_range_min` decimal(4,2) NOT NULL,
  `carat_range_max` decimal(4,2) NOT NULL,
  `cut_grade` enum('Excellent','Very Good','Good','Fair','Poor') COLLATE utf8mb3_unicode_ci NOT NULL,
  `color_grade` enum('D','E','F','G','H','I','J','K','L','M','N') COLLATE utf8mb3_unicode_ci NOT NULL,
  `clarity_grade` enum('FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','I1','I2','I3') COLLATE utf8mb3_unicode_ci NOT NULL,
  `base_price_per_carat` decimal(12,2) NOT NULL,
  `price_multiplier` decimal(5,2) NOT NULL DEFAULT '1.00',
  `shape` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Round',
  `fluorescence` enum('None','Faint','Medium','Strong') COLLATE utf8mb3_unicode_ci NOT NULL,
  `certification` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'GIA',
  `date_updated` date NOT NULL,
  `market_region` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Global',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diamond_carat_range_index` (`carat_range_min`,`carat_range_max`),
  KEY `diamond_grades_index` (`cut_grade`,`color_grade`,`clarity_grade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `location_name` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `location_address` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `event_url` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `featured_image` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `featured_image_url_snapshot` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image_urls` json DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'upcoming',
  `seo_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `og_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_image` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `schema_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Event',
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_datetime_status_index` (`start_datetime`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gemstone_pricings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gemstone_pricings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `price_per_carat` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gemstone_pricing_name_unique` (`name`),
  UNIQUE KEY `gemstone_pricing_slug_unique` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gold_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gold_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date_recorded` date NOT NULL,
  `timestamp_recorded` datetime DEFAULT NULL,
  `currency_code` varchar(3) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'USD',
  `price_per_ounce` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `price_per_gram_24k` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `price_per_gram_22k` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `price_per_gram_18k` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `price_per_gram_14k` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `price_per_gram_10k` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `market_open` decimal(18,6) DEFAULT NULL,
  `market_high` decimal(18,6) DEFAULT NULL,
  `market_low` decimal(18,6) DEFAULT NULL,
  `market_close` decimal(18,6) DEFAULT NULL,
  `volume` bigint unsigned DEFAULT NULL,
  `source` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT 'API',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gold_prices_date_currency_unique` (`date_recorded`,`currency_code`),
  KEY `gold_prices_date_currency_index` (`date_recorded`,`currency_code`),
  KEY `gold_prices_timestamp_index` (`timestamp_recorded`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jewelry_horoscopes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jewelry_horoscopes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `zodiac_sign` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `recommended_jewelry` json NOT NULL,
  `lucky_color` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `lucky_gemstone` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jewelry_horoscopes_zodiac_sign_date_unique` (`zodiac_sign`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jewelry_matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jewelry_matches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `gemstone_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `jewelry_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `difficulty_level` int NOT NULL,
  `score` int NOT NULL,
  `time_taken` int NOT NULL,
  `answer_details` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jewelry_matches_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jewelry_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jewelry_materials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `material_type` enum('Metal','Gemstone','Finding','Labor') COLLATE utf8mb3_unicode_ci NOT NULL,
  `material_name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `material_category` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `unit_type` enum('gram','carat','piece','hour') COLLATE utf8mb3_unicode_ci NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `markup_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `currency_code` varchar(3) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'USD',
  `purity_grade` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `quality_grade` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `supplier` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `minimum_order_quantity` decimal(8,3) NOT NULL DEFAULT '0.000',
  `date_updated` date NOT NULL,
  `price_valid_until` date DEFAULT NULL,
  `market_region` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Global',
  `is_auto_synced` tinyint(1) NOT NULL DEFAULT '0',
  `sync_source` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `manual_override` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_jewelry_materials_auto_synced_type` (`is_auto_synced`,`material_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jewelry_style_quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jewelry_style_quizzes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `answers` json NOT NULL,
  `personality_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `recommendations` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jewelry_style_quizzes_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb3_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `market_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `market_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `metal_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'USD',
  `rate_date` date NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `market_rates_metal_type_rate_date_unique` (`metal_type`,`rate_date`),
  KEY `market_rates_rate_date_index` (`rate_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL DEFAULT '1',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_email` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `billing_address` json DEFAULT NULL,
  `shipping_address` json DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'usd',
  `payment_method` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled','returned') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_index` (`user_id`),
  KEY `orders_customer_email_index` (`customer_email`),
  KEY `orders_total_index` (`total`),
  KEY `orders_payment_status_index` (`payment_status`),
  KEY `orders_order_status_index` (`order_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pendingblogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendingblogs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `local_image_path` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `product_link` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status_completed` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb3_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb3_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `post_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_tag` (
  `post_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `post_tag_tag_id_foreign` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb3_unicode_ci,
  `featured_image` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `featured_image_url_snapshot` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image_urls` json DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `seo_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_keywords` json DEFAULT NULL,
  `canonical_url` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `noindex` tinyint(1) NOT NULL DEFAULT '0',
  `nofollow` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `og_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_image` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `twitter_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `twitter_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `schema_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Article',
  `focus_keyword` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `reading_time` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_user_id_index` (`user_id`),
  KEY `posts_created_at_index` (`created_at`),
  KEY `posts_status_published_at_index` (`status`,`published_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_analytics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb3_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb3_unicode_ci,
  `group` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seo_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_image` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `schema_type` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT 'CollectionPage',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trending_looks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trending_looks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `image_url` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `jewelry_items` json NOT NULL,
  `likes` int NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  `shares` int NOT NULL DEFAULT '0',
  `status` enum('draft','published','trending') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'draft',
  `category` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trending_looks_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `youtube_video_id` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `duration_seconds` int unsigned DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'published',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seo_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_title` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `og_description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `schema_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'VideoObject',
  PRIMARY KEY (`id`),
  UNIQUE KEY `videos_youtube_video_id_unique` (`youtube_video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `visitor_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitor_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `visitor_id` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `event_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitor_events_user_id_foreign` (`user_id`),
  KEY `visitor_events_visitor_id_index` (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `visitor_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitor_preferences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `visitor_id` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `theme` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'system',
  `language` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'en',
  `layout` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'standard',
  `favorite_categories` json DEFAULT NULL,
  `favorite_tags` json DEFAULT NULL,
  `recently_viewed_products` json DEFAULT NULL,
  `recently_viewed_posts` json DEFAULT NULL,
  `saved_preferences` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitor_preferences_user_id_foreign` (`user_id`),
  KEY `visitor_preferences_visitor_id_index` (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `zakat_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zakat_rates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `calculation_year` year NOT NULL,
  `nisab_gold_grams` decimal(8,3) NOT NULL DEFAULT '87.480',
  `nisab_silver_grams` decimal(8,3) NOT NULL DEFAULT '612.360',
  `zakat_percentage` decimal(5,4) NOT NULL DEFAULT '2.5000',
  `gold_price_per_gram` decimal(10,2) NOT NULL,
  `silver_price_per_gram` decimal(10,2) NOT NULL,
  `currency_code` varchar(3) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'USD',
  `lunar_year_days` smallint NOT NULL DEFAULT '354',
  `holding_period_days` smallint NOT NULL DEFAULT '354',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `date_effective` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zakat_rates_calculation_year_currency_unique` (`calculation_year`,`currency_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP VIEW IF EXISTS `current_gold_prices`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `current_gold_prices` AS select `gp`.`id` AS `id`,`gp`.`date_recorded` AS `date_recorded`,`gp`.`timestamp_recorded` AS `timestamp_recorded`,`gp`.`currency_code` AS `currency_code`,`gp`.`price_per_ounce` AS `price_per_ounce`,`gp`.`price_per_gram_24k` AS `price_per_gram_24k`,`gp`.`price_per_gram_22k` AS `price_per_gram_22k`,`gp`.`price_per_gram_18k` AS `price_per_gram_18k`,`gp`.`price_per_gram_14k` AS `price_per_gram_14k`,`gp`.`price_per_gram_10k` AS `price_per_gram_10k`,`gp`.`market_open` AS `market_open`,`gp`.`market_high` AS `market_high`,`gp`.`market_low` AS `market_low`,`gp`.`market_close` AS `market_close`,`gp`.`volume` AS `volume`,`gp`.`source` AS `source`,`gp`.`is_active` AS `is_active`,`gp`.`created_at` AS `created_at`,`gp`.`updated_at` AS `updated_at` from (`gold_prices` `gp` join (select `gold_prices`.`currency_code` AS `currency_code`,max(concat(`gold_prices`.`date_recorded`,' ',coalesce(`gold_prices`.`timestamp_recorded`,'00:00:00'))) AS `max_datetime` from `gold_prices` where (`gold_prices`.`is_active` = 1) group by `gold_prices`.`currency_code`) `latest` on(((`gp`.`currency_code` = `latest`.`currency_code`) and (concat(`gp`.`date_recorded`,' ',coalesce(`gp`.`timestamp_recorded`,'00:00:00')) = `latest`.`max_datetime`)))) where (`gp`.`is_active` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `current_material_prices`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `current_material_prices` AS select `jewelry_materials`.`id` AS `id`,`jewelry_materials`.`material_type` AS `material_type`,`jewelry_materials`.`material_name` AS `material_name`,`jewelry_materials`.`material_category` AS `material_category`,`jewelry_materials`.`unit_type` AS `unit_type`,`jewelry_materials`.`price_per_unit` AS `price_per_unit`,`jewelry_materials`.`currency_code` AS `currency_code`,`jewelry_materials`.`purity_grade` AS `purity_grade`,`jewelry_materials`.`quality_grade` AS `quality_grade`,`jewelry_materials`.`supplier` AS `supplier`,`jewelry_materials`.`minimum_order_quantity` AS `minimum_order_quantity`,`jewelry_materials`.`date_updated` AS `date_updated`,`jewelry_materials`.`price_valid_until` AS `price_valid_until`,`jewelry_materials`.`market_region` AS `market_region`,`jewelry_materials`.`is_active` AS `is_active`,`jewelry_materials`.`created_at` AS `created_at`,`jewelry_materials`.`updated_at` AS `updated_at` from `jewelry_materials` where ((`jewelry_materials`.`is_active` = 1) and ((`jewelry_materials`.`price_valid_until` is null) or (`jewelry_materials`.`price_valid_until` > now()))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `current_zakat_rates`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `current_zakat_rates` AS select `zakat_rates`.`id` AS `id`,`zakat_rates`.`calculation_year` AS `calculation_year`,`zakat_rates`.`nisab_gold_grams` AS `nisab_gold_grams`,`zakat_rates`.`nisab_silver_grams` AS `nisab_silver_grams`,`zakat_rates`.`zakat_percentage` AS `zakat_percentage`,`zakat_rates`.`gold_price_per_gram` AS `gold_price_per_gram`,`zakat_rates`.`silver_price_per_gram` AS `silver_price_per_gram`,`zakat_rates`.`currency_code` AS `currency_code`,`zakat_rates`.`lunar_year_days` AS `lunar_year_days`,`zakat_rates`.`holding_period_days` AS `holding_period_days`,`zakat_rates`.`is_active` AS `is_active`,`zakat_rates`.`date_effective` AS `date_effective`,`zakat_rates`.`created_at` AS `created_at`,`zakat_rates`.`updated_at` AS `updated_at` from `zakat_rates` where ((`zakat_rates`.`is_active` = 1) and (`zakat_rates`.`calculation_year` = year(now()))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_05_13_075735_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_05_13_183012_create_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_05_13_192345_create_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_05_13_192459_create_tags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_05_13_192551_create_category_post_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_05_13_192626_create_post_tag_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_05_13_200815_modify_meta_keywords_to_json_in_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_05_15_055133_create_affiliate_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_05_15_075511_add_is_featured_to_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_05_22_104433_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_06_04_070619_create_videos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_07_12_150223_create_daily_metal_prices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_07_14_193635_create_gemstone_pricings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_07_17_152018_create_contact_submissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_04_27_000000_create_gold_prices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_04_27_000002_create_diamond_pricing_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_04_27_000004_create_affiliate_product_post_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_04_27_000005_create_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_04_27_000006_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_04_27_000007_create_audit_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2026_04_28_000000_create_pendingblogs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_04_28_000001_create_zakat_rates_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2026_04_28_000002_create_jewelry_materials_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2026_04_28_000003_create_database_views',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_04_28_000004_add_missing_columns_to_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_04_28_000005_add_image_to_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2026_04_28_000006_add_missing_columns_to_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2026_04_28_000007_remove_post_id_from_videos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2026_04_28_000008_add_missing_columns_to_affiliate_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2026_04_28_000009_fix_gold_prices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2026_04_28_000010_fix_diamond_pricings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2026_04_28_000011_rename_gemstone_pricing_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2026_04_28_000012_add_columns_to_daily_metal_prices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2026_05_02_082213_add_seo_fields_to_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2026_05_02_082310_add_seo_fields_to_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2026_05_02_082334_add_seo_fields_to_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_05_02_082352_add_seo_fields_to_tags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_05_02_082740_add_seo_fields_to_affiliate_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2026_05_02_082800_add_seo_fields_to_videos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2026_05_04_194131_add_indexes_to_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_05_05_000001_optimize_search_and_integrity',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2026_05_05_190327_add_price_column_to_affiliate_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2026_05_07_114512_add_seo_fields_to_tags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2026_05_09_133702_create_product_analytics_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2026_05_09_195558_create_market_rates_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2026_05_10_043602_create_jewelry_style_quizzes_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2026_05_10_043806_create_custom_jewelry_designs_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2026_05_10_043921_create_jewelry_horoscopes_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_05_10_051621_create_jewelry_matches_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_05_10_051738_create_trending_looks_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2026_05_10_120000_create_content_placements_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2026_05_15_000001_create_cookie_consents_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2026_05_15_000002_create_cookie_consent_logs_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2026_05_15_000003_create_visitor_preferences_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2026_05_15_000004_create_visitor_events_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2026_05_19_000001_create_orders_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2026_05_19_000002_create_order_items_table',3);
