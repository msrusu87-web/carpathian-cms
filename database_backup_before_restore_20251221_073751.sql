-- MySQL dump 10.13  Distrib 8.4.7, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: carphatian_cms
-- ------------------------------------------------------
-- Server version	8.4.7-0ubuntu0.25.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `changes` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `severity` enum('info','warning','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_settings`
--

DROP TABLE IF EXISTS `admin_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_settings`
--

LOCK TABLES `admin_settings` WRITE;
/*!40000 ALTER TABLE `admin_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_generations`
--

DROP TABLE IF EXISTS `ai_generations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_generations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prompt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` longtext COLLATE utf8mb4_unicode_ci,
  `parameters` json DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'groq',
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `tokens_used` int unsigned DEFAULT NULL,
  `generation_time` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_generations_user_id_foreign` (`user_id`),
  KEY `ai_generations_type_user_id_index` (`type`,`user_id`),
  KEY `ai_generations_status_index` (`status`),
  CONSTRAINT `ai_generations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_generations`
--

LOCK TABLES `ai_generations` WRITE;
/*!40000 ALTER TABLE `ai_generations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_generations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_settings`
--

DROP TABLE IF EXISTS `ai_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` text COLLATE utf8mb4_unicode_ci,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ai_settings_provider_unique` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_settings`
--

LOCK TABLES `ai_settings` WRITE;
/*!40000 ALTER TABLE `ai_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics_events`
--

DROP TABLE IF EXISTS `analytics_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analytics_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_data` json DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `referer` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `analytics_events_user_id_foreign` (`user_id`),
  KEY `analytics_events_event_name_created_at_index` (`event_name`,`created_at`),
  CONSTRAINT `analytics_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_events`
--

LOCK TABLES `analytics_events` WRITE;
/*!40000 ALTER TABLE `analytics_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_keys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` json DEFAULT NULL,
  `rate_limit` int NOT NULL DEFAULT '1000',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_keys_key_unique` (`key`),
  KEY `api_keys_user_id_foreign` (`user_id`),
  CONSTRAINT `api_keys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_keys`
--

LOCK TABLES `api_keys` WRITE;
/*!40000 ALTER TABLE `api_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_logs`
--

DROP TABLE IF EXISTS `api_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `api_key_id` bigint unsigned DEFAULT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response_time` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `api_logs_api_key_id_foreign` (`api_key_id`),
  KEY `api_logs_endpoint_created_at_index` (`endpoint`,`created_at`),
  CONSTRAINT `api_logs_api_key_id_foreign` FOREIGN KEY (`api_key_id`) REFERENCES `api_keys` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_logs`
--

LOCK TABLES `api_logs` WRITE;
/*!40000 ALTER TABLE `api_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_schedules`
--

DROP TABLE IF EXISTS `backup_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backup_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` enum('hourly','daily','weekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `includes` json NOT NULL,
  `storage_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `retention_days` int NOT NULL DEFAULT '30',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_run_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_schedules`
--

LOCK TABLES `backup_schedules` WRITE;
/*!40000 ALTER TABLE `backup_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backups`
--

DROP TABLE IF EXISTS `backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint unsigned NOT NULL,
  `status` enum('creating','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'creating',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `backups_schedule_id_foreign` (`schedule_id`),
  CONSTRAINT `backups_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `backup_schedules` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('active_redirects','O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1766306089),('active_template','N;',1766306221),('default_template','N;',1766306221),('page_cache_ro_488819a0d67921ed655250d094711bd9','a:2:{s:7:\"content\";s:16392:\"<!DOCTYPE html>\n<html lang=\"ro\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    \n    <!-- Primary Meta Tags -->\n    <title>Contactează-ne - Carphatian CMS | Carphatian CMS</title>\n    <meta name=\"title\" content=\"Contactează-ne - Carphatian CMS\">\n    <meta name=\"description\" content=\"Contactează echipa Carphatian CMS pentru dezvoltare web profesională, consultanță IT și soluții software personalizate. Răspundem în 24 de ore.\">\n    <meta name=\"keywords\" content=\"contact carphatian cms, web development quote, software development consultation, hire developers, custom software request, get project estimate, web development services, it consulting, digital agency contact, free quote, project inquiry\">\n    <meta name=\"author\" content=\"Carphatian CMS\">\n    <meta name=\"robots\" content=\"index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1\">\n    <link rel=\"canonical\" href=\"https://carphatian.ro/contact\">\n    \n    <!-- Open Graph / Facebook -->\n    <meta property=\"og:type\" content=\"website\">\n    <meta property=\"og:url\" content=\"https://carphatian.ro/contact\">\n    <meta property=\"og:title\" content=\"Contactează-ne - Carphatian CMS\">\n    <meta property=\"og:description\" content=\"Contactează echipa Carphatian CMS pentru dezvoltare web profesională, consultanță IT și soluții software personalizate. Răspundem în 24 de ore.\">\n    <meta property=\"og:image\" content=\"https://carphatian.ro/images/carpathian-og-image.jpg\">\n    <meta property=\"og:locale\" content=\"ro\">\n    <meta property=\"og:site_name\" content=\"Carphatian CMS\">\n    \n    <!-- Twitter Card -->\n    <meta name=\"twitter:card\" content=\"summary_large_image\">\n    <meta name=\"twitter:url\" content=\"https://carphatian.ro/contact\">\n    <meta name=\"twitter:title\" content=\"Contactează-ne - Carphatian CMS\">\n    <meta name=\"twitter:description\" content=\"Contactează echipa Carphatian CMS pentru dezvoltare web profesională, consultanță IT și soluții software personalizate. Răspundem în 24 de ore.\">\n    <meta name=\"twitter:image\" content=\"https://carphatian.ro/images/carpathian-og-image.jpg\">\n    \n    <!-- Additional SEO -->\n    <meta name=\"geo.region\" content=\"RO\">\n    <meta name=\"geo.placename\" content=\"Romania\">\n    <meta name=\"language\" content=\"ro\">\n    <meta name=\"rating\" content=\"general\">\n    <script src=\"https://cdn.tailwindcss.com\"></script>\n    <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>\n</head>\n<body class=\"bg-gray-50\">\n    <nav class=\"bg-white shadow-sm sticky top-0 z-50\">\n    <div class=\"container mx-auto px-4\">\n        <div class=\"flex items-center justify-between h-24\">\n            <!-- Logo -->\n            <div class=\"flex-shrink-0\">\n                <a href=\"/\" class=\"flex items-center\">\n                    <img src=\"/images/carphatian-logo-transparent.png\" alt=\"Carphatian CMS\" style=\"width: 280px; height: auto;\">\n                </a>\n            </div>\n\n            <!-- Desktop Navigation -->\n            <div class=\"hidden md:flex items-center flex-wrap gap-3 lg:gap-4 xl:gap-6\">\n                <!-- Home Link -->\n                <a href=\"/\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Acasă\n                </a>\n                \n                <!-- Plugin Dropdown Menus -->\n                                \n                <!-- Portfolio Link -->\n                <a href=\"/portfolios\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Portofoliu\n                </a>\n                \n                <!-- Default Links -->\n                <a href=\"/blog\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Blog\n                </a>\n                <a href=\"/contact\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap font-semibold text-purple-600\">\n                    Contact\n                </a>\n\n                <!-- Language Switcher -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>RO</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n\n                <!-- Cart Icon with Badge -->\n                <div class=\"relative\" x-data=\"{ cartCount: 0 }\" x-init=\"fetch(\'/cart/count\').then(r => r.json()).then(d => cartCount = d.count).catch(() => cartCount = 0)\">\n                    <a href=\"/cart\" class=\"text-gray-700 hover:text-purple-600\">\n                        <svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\"/>\n                        </svg>\n                        <span x-show=\"cartCount > 0\" \n                              x-text=\"cartCount\" \n                              class=\"absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold\"\n                              style=\"display: none;\">\n                        </span>\n                    </a>\n                </div>\n\n                <!-- Admin Link -->\n                <a href=\"/admin\" class=\"bg-purple-600 text-white px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-purple-700 transition text-sm lg:text-base font-semibold whitespace-nowrap\">\n                    Admin\n                </a>\n            </div>\n\n            <!-- Mobile menu button -->\n            <div class=\"md:hidden flex items-center space-x-3\">\n                <!-- Language Switcher Mobile -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>RO</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n                \n                <button id=\"mobile-menu-button\" class=\"text-gray-700 hover:text-purple-600 focus:outline-none\">\n                    <svg class=\"h-6 w-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M4 6h16M4 12h16M4 18h16\"></path>\n                    </svg>\n                </button>\n            </div>\n        </div>\n\n        <!-- Mobile menu -->\n        <div id=\"mobile-menu\" class=\"hidden md:hidden pb-4\">\n            <a href=\"/\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Acasă\n            </a>\n            \n                        \n            <a href=\"/portfolios\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 \">\n                Portofoliu\n            </a>\n            <a href=\"/blog\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Blog\n            </a>\n            <a href=\"/contact\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Contact\n            </a>\n            <a href=\"/cart\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Coș\n            </a>\n            <a href=\"/admin\" class=\"block px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50\">\n                Admin\n            </a>\n        </div>\n    </div>\n</nav>\n\n<script>\n    document.addEventListener(\'DOMContentLoaded\', function() {\n        const mobileMenuButton = document.getElementById(\'mobile-menu-button\');\n        const mobileMenu = document.getElementById(\'mobile-menu\');\n        \n        if (mobileMenuButton && mobileMenu) {\n            mobileMenuButton.addEventListener(\'click\', function() {\n                mobileMenu.classList.toggle(\'hidden\');\n            });\n        }\n    });\n</script>\n\n    <section class=\"py-12 md:py-16\">\n        <div class=\"max-w-7xl mx-auto px-4\">\n            <div class=\"grid md:grid-cols-2 gap-8 md:gap-12\">\n                <!-- Contact Form -->\n                <div class=\"bg-white rounded-lg shadow-lg p-6 md:p-8\">\n                    <h2 class=\"text-2xl md:text-3xl font-bold mb-6\">Trimite Mesaj</h2>\n                    \n                    \n                    \n                    <form action=\"https://carphatian.ro/contact\" method=\"POST\">\n                        <input type=\"hidden\" name=\"_token\" value=\"KDsK6by7eIhWNVictxKs8HhP2G0uFmRl6WHdIDC1\" autocomplete=\"off\">                        <div class=\"mb-4\">\n                            <label class=\"block text-gray-700 font-semibold mb-2\">Nume *</label>\n                            <input type=\"text\" name=\"name\" value=\"\" required\n                                   class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 \">\n                                                    </div>\n\n                        <div class=\"mb-4\">\n                            <label class=\"block text-gray-700 font-semibold mb-2\">Email *</label>\n                            <input type=\"email\" name=\"email\" value=\"\" required\n                                   class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 \">\n                                                    </div>\n\n                        <div class=\"mb-4\">\n                            <label class=\"block text-gray-700 font-semibold mb-2\">Subiect *</label>\n                            <input type=\"text\" name=\"subject\" value=\"\" required\n                                   class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 \">\n                                                    </div>\n\n                        <div class=\"mb-6\">\n                            <label class=\"block text-gray-700 font-semibold mb-2\">Mesaj *</label>\n                            <textarea name=\"message\" rows=\"6\" required\n                                      class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 \"></textarea>\n                                                    </div>\n\n                        <button type=\"submit\" class=\"w-full bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition\">\n                            Trimite\n                        </button>\n                    </form>\n                </div>\n\n                <!-- Contact Information -->\n                <div>\n                    <div class=\"bg-white rounded-lg shadow-lg p-6 md:p-8 mb-6 md:mb-8\">\n                        <h2 class=\"text-2xl md:text-3xl font-bold mb-6\">Informații de Contact</h2>\n                        \n                        <!-- Company Name -->\n                        <div class=\"bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6 border-l-4 border-blue-600\">\n                            <h3 class=\"font-bold text-gray-900 text-lg\">Aziz Ride Sharing S.R.L.</h3>\n                            <p class=\"text-gray-600 text-sm mt-1\">Societate comercială înregistrată în România</p>\n                        </div>\n                        \n                                            </div>\n\n                                    </div>\n            </div>\n        </div>\n    </section>\n\n        </body>\n</html>\n\";s:12:\"content_type\";s:24:\"text/html; charset=utf-8\";}',1766306185),('page_cache_ro_851dcb76b1d52f5247e9bbd66b2a3a2a','a:2:{s:7:\"content\";s:15593:\"<!DOCTYPE html>\n<html lang=\"de\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    \n    <!-- Primary Meta Tags -->\n    <title>Carphatian CMS - Professional Web Development Software on Demand</title>\n    <meta name=\"title\" content=\"Carphatian CMS - Professional Web Development Software on Demand\">\n    <meta name=\"description\" content=\"Custom web development solutions on demand. Build scalable websites, eCommerce platforms, and enterprise applications with Carphatian CMS. Expert developers, competitive pricing, fast delivery.\">\n    <meta name=\"keywords\" content=\"carphatian cms, web development on demand, custom software development, website builder, cms platform, ecommerce development, laravel cms, professional web design, scalable applications, enterprise software, full stack development, php development, modern web applications, business automation, digital transformation\">\n    <meta name=\"author\" content=\"Carphatian CMS\">\n    <meta name=\"robots\" content=\"index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1\">\n    <link rel=\"canonical\" href=\"https://carphatian.ro\">\n    \n    <!-- Open Graph / Facebook -->\n    <meta property=\"og:type\" content=\"website\">\n    <meta property=\"og:url\" content=\"https://carphatian.ro\">\n    <meta property=\"og:title\" content=\"Carphatian CMS - Professional Web Development Software on Demand\">\n    <meta property=\"og:description\" content=\"Custom web development solutions on demand. Build scalable websites, eCommerce platforms, and enterprise applications with Carphatian CMS. Expert developers, competitive pricing, fast delivery.\">\n    <meta property=\"og:image\" content=\"https://carphatian.ro/images/carpathian-og-image.jpg\">\n    <meta property=\"og:locale\" content=\"de\">\n    <meta property=\"og:site_name\" content=\"Carphatian CMS\">\n    \n    <!-- Twitter Card -->\n    <meta name=\"twitter:card\" content=\"summary_large_image\">\n    <meta name=\"twitter:url\" content=\"https://carphatian.ro\">\n    <meta name=\"twitter:title\" content=\"Carphatian CMS - Professional Web Development Software on Demand\">\n    <meta name=\"twitter:description\" content=\"Custom web development solutions on demand. Build scalable websites, eCommerce platforms, and enterprise applications with Carphatian CMS. Expert developers, competitive pricing, fast delivery.\">\n    <meta name=\"twitter:image\" content=\"https://carphatian.ro/images/carpathian-og-image.jpg\">\n    \n    <!-- Additional SEO -->\n    <meta name=\"geo.region\" content=\"RO\">\n    <meta name=\"geo.placename\" content=\"Romania\">\n    <meta name=\"language\" content=\"de\">\n    <meta name=\"rating\" content=\"general\">\n    \n    <!-- Structured Data (JSON-LD) for Google -->\n    <script type=\"application/ld+json\">\n    {\n        \"@context\": \"https://schema.org\",\n        \"@type\": \"WebSite\",\n        \"name\": \"Carphatian CMS\",\n        \"url\": \"https://carphatian.ro\",\n        \"description\": \"Custom web development solutions on demand. Build scalable websites, eCommerce platforms, and enterprise applications with Carphatian CMS. Expert developers, competitive pricing, fast delivery.\",\n        \"inLanguage\": [\"ro\", \"en\", \"es\", \"it\", \"de\", \"fr\"]\n    }\n    </script>\n    \n    <script type=\"application/ld+json\">\n    {\n        \"@context\": \"https://schema.org\",\n        \"@type\": \"Organization\",\n        \"name\": \"Carphatian CMS\",\n        \"url\": \"https://carphatian.ro\",\n        \"logo\": \"https://carphatian.ro/images/carphatian-logo-transparent.png\",\n        \"description\": \"Custom web development solutions on demand. Build scalable websites, eCommerce platforms, and enterprise applications with Carphatian CMS. Expert developers, competitive pricing, fast delivery.\",\n        \"address\": {\n            \"@type\": \"PostalAddress\",\n            \"addressCountry\": \"RO\"\n        },\n        \"contactPoint\": {\n            \"@type\": \"ContactPoint\",\n            \"contactType\": \"Customer Service\",\n            \"availableLanguage\": [\"Romanian\", \"English\", \"Spanish\", \"Italian\", \"German\", \"French\"]\n        }\n    }\n    </script>\n    \n    <script type=\"application/ld+json\">\n    {\n        \"@context\": \"https://schema.org\",\n        \"@type\": \"SoftwareApplication\",\n        \"name\": \"Carphatian CMS\",\n        \"applicationCategory\": \"WebApplication\",\n        \"offers\": {\n            \"@type\": \"Offer\",\n            \"price\": \"0\",\n            \"priceCurrency\": \"EUR\"\n        },\n        \"operatingSystem\": \"Web-based\"\n    }\n    </script>\n    \n    <script src=\"https://cdn.tailwindcss.com\"></script>\n    <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>\n    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\n    \n    <!-- AOS Animation Library -->\n    <link href=\"https://unpkg.com/aos@2.3.1/dist/aos.css\" rel=\"stylesheet\">\n    <script src=\"https://unpkg.com/aos@2.3.1/dist/aos.js\"></script>\n    \n        \n    </head>\n<body class=\"bg-gray-50\">\n    <nav class=\"bg-white shadow-sm sticky top-0 z-50\">\n    <div class=\"container mx-auto px-4\">\n        <div class=\"flex items-center justify-between h-24\">\n            <!-- Logo -->\n            <div class=\"flex-shrink-0\">\n                <a href=\"/\" class=\"flex items-center\">\n                    <img src=\"/images/carphatian-logo-transparent.png\" alt=\"Carphatian CMS\" style=\"width: 280px; height: auto;\">\n                </a>\n            </div>\n\n            <!-- Desktop Navigation -->\n            <div class=\"hidden md:flex items-center flex-wrap gap-3 lg:gap-4 xl:gap-6\">\n                <!-- Home Link -->\n                <a href=\"/\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap font-semibold text-purple-600\">\n                    Home\n                </a>\n                \n                <!-- Plugin Dropdown Menus -->\n                                \n                <!-- Portfolio Link -->\n                <a href=\"/portfolios\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Portfolio\n                </a>\n                \n                <!-- Default Links -->\n                <a href=\"/blog\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Blog\n                </a>\n                <a href=\"/contact\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Contatti\n                </a>\n\n                <!-- Language Switcher -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>DE</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n\n                <!-- Cart Icon with Badge -->\n                <div class=\"relative\" x-data=\"{ cartCount: 0 }\" x-init=\"fetch(\'/cart/count\').then(r => r.json()).then(d => cartCount = d.count).catch(() => cartCount = 0)\">\n                    <a href=\"/cart\" class=\"text-gray-700 hover:text-purple-600\">\n                        <svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\"/>\n                        </svg>\n                        <span x-show=\"cartCount > 0\" \n                              x-text=\"cartCount\" \n                              class=\"absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold\"\n                              style=\"display: none;\">\n                        </span>\n                    </a>\n                </div>\n\n                <!-- Admin Link -->\n                <a href=\"/admin\" class=\"bg-purple-600 text-white px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-purple-700 transition text-sm lg:text-base font-semibold whitespace-nowrap\">\n                    Admin\n                </a>\n            </div>\n\n            <!-- Mobile menu button -->\n            <div class=\"md:hidden flex items-center space-x-3\">\n                <!-- Language Switcher Mobile -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>DE</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n                \n                <button id=\"mobile-menu-button\" class=\"text-gray-700 hover:text-purple-600 focus:outline-none\">\n                    <svg class=\"h-6 w-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M4 6h16M4 12h16M4 18h16\"></path>\n                    </svg>\n                </button>\n            </div>\n        </div>\n\n        <!-- Mobile menu -->\n        <div id=\"mobile-menu\" class=\"hidden md:hidden pb-4\">\n            <a href=\"/\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Home\n            </a>\n            \n                        \n            <a href=\"/portfolios\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 \">\n                Portfolio\n            </a>\n            <a href=\"/blog\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Blog\n            </a>\n            <a href=\"/contact\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Contatti\n            </a>\n            <a href=\"/cart\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Carrello\n            </a>\n            <a href=\"/admin\" class=\"block px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50\">\n                Admin\n            </a>\n        </div>\n    </div>\n</nav>\n\n<script>\n    document.addEventListener(\'DOMContentLoaded\', function() {\n        const mobileMenuButton = document.getElementById(\'mobile-menu-button\');\n        const mobileMenu = document.getElementById(\'mobile-menu\');\n        \n        if (mobileMenuButton && mobileMenu) {\n            mobileMenuButton.addEventListener(\'click\', function() {\n                mobileMenu.classList.toggle(\'hidden\');\n            });\n        }\n    });\n</script>\n\n    <!-- Widgets Area -->\n    \n    <!-- Footer -->\n        \n    <!-- Initialize AOS -->\n    <script>\n        AOS.init({\n            duration: 800,\n            easing: \'ease-in-out\',\n            once: true,\n            offset: 100\n        });\n    </script>\n</body>\n</html>\n\";s:12:\"content_type\";s:24:\"text/html; charset=utf-8\";}',1766306089),('page_cache_ro_86534a11525c548b98daced7f356a0fe','a:2:{s:7:\"content\";s:38648:\"<!DOCTYPE html>\n<html lang=\"ro\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    <title>Portofoliul Nostru | Carphatian CMS</title>\n    <script src=\"https://cdn.tailwindcss.com\"></script>\n    <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>\n</head>\n<body class=\"bg-gray-50\">\n    <nav class=\"bg-white shadow-sm sticky top-0 z-50\">\n    <div class=\"container mx-auto px-4\">\n        <div class=\"flex items-center justify-between h-24\">\n            <!-- Logo -->\n            <div class=\"flex-shrink-0\">\n                <a href=\"/\" class=\"flex items-center\">\n                    <img src=\"/images/carphatian-logo-transparent.png\" alt=\"Carphatian CMS\" style=\"width: 280px; height: auto;\">\n                </a>\n            </div>\n\n            <!-- Desktop Navigation -->\n            <div class=\"hidden md:flex items-center flex-wrap gap-3 lg:gap-4 xl:gap-6\">\n                <!-- Home Link -->\n                <a href=\"/\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Acasă\n                </a>\n                \n                <!-- Plugin Dropdown Menus -->\n                                \n                <!-- Portfolio Link -->\n                <a href=\"/portfolios\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap font-semibold text-purple-600\">\n                    Portofoliu\n                </a>\n                \n                <!-- Default Links -->\n                <a href=\"/blog\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Blog\n                </a>\n                <a href=\"/contact\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Contact\n                </a>\n\n                <!-- Language Switcher -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>RO</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n\n                <!-- Cart Icon with Badge -->\n                <div class=\"relative\" x-data=\"{ cartCount: 0 }\" x-init=\"fetch(\'/cart/count\').then(r => r.json()).then(d => cartCount = d.count).catch(() => cartCount = 0)\">\n                    <a href=\"/cart\" class=\"text-gray-700 hover:text-purple-600\">\n                        <svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\"/>\n                        </svg>\n                        <span x-show=\"cartCount > 0\" \n                              x-text=\"cartCount\" \n                              class=\"absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold\"\n                              style=\"display: none;\">\n                        </span>\n                    </a>\n                </div>\n\n                <!-- Admin Link -->\n                <a href=\"/admin\" class=\"bg-purple-600 text-white px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-purple-700 transition text-sm lg:text-base font-semibold whitespace-nowrap\">\n                    Admin\n                </a>\n            </div>\n\n            <!-- Mobile menu button -->\n            <div class=\"md:hidden flex items-center space-x-3\">\n                <!-- Language Switcher Mobile -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>RO</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n                \n                <button id=\"mobile-menu-button\" class=\"text-gray-700 hover:text-purple-600 focus:outline-none\">\n                    <svg class=\"h-6 w-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M4 6h16M4 12h16M4 18h16\"></path>\n                    </svg>\n                </button>\n            </div>\n        </div>\n\n        <!-- Mobile menu -->\n        <div id=\"mobile-menu\" class=\"hidden md:hidden pb-4\">\n            <a href=\"/\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Acasă\n            </a>\n            \n                        \n            <a href=\"/portfolios\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 font-semibold text-purple-600 bg-purple-50\">\n                Portofoliu\n            </a>\n            <a href=\"/blog\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Blog\n            </a>\n            <a href=\"/contact\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Contact\n            </a>\n            <a href=\"/cart\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Coș\n            </a>\n            <a href=\"/admin\" class=\"block px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50\">\n                Admin\n            </a>\n        </div>\n    </div>\n</nav>\n\n<script>\n    document.addEventListener(\'DOMContentLoaded\', function() {\n        const mobileMenuButton = document.getElementById(\'mobile-menu-button\');\n        const mobileMenu = document.getElementById(\'mobile-menu\');\n        \n        if (mobileMenuButton && mobileMenu) {\n            mobileMenuButton.addEventListener(\'click\', function() {\n                mobileMenu.classList.toggle(\'hidden\');\n            });\n        }\n    });\n</script>\n<div class=\"min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-blue-50 py-20\">\n    <div class=\"container mx-auto px-4\">\n        <!-- Header Section with Animation -->\n        <div class=\"text-center mb-20 animate-fade-in\">\n            <span class=\"inline-block px-6 py-2 mb-6 text-sm font-semibold text-purple-700 bg-purple-100 rounded-full shadow-sm hover:shadow-md transition-shadow duration-300\">\n                Portofoliu\n            </span>\n            <h1 class=\"text-6xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 mb-6 leading-tight\">\n                Portofoliul Nostru\n            </h1>\n            <p class=\"text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed font-light\">\n                Descoperă colecția noastră de soluții web inovatoare, de la instrumente bazate pe AI la platforme blockchain. Fiecare proiect reprezintă angajamentul nostru față de tehnologie de ultimă oră și experiență excepțională pentru utilizatori.\n            </p>\n        </div>\n\n        <!-- Portfolio Grid with Modern Cards -->\n        <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto mb-16\">\n            \n            <!-- Project 0: zanziBAR Cernavodă - LATEST -->\n            <div class=\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\">\n                <div class=\"absolute inset-0 bg-gradient-to-br from-emerald-900/10 to-amber-700/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\"></div>\n                \n                <div class=\"relative h-72 overflow-hidden bg-gradient-to-br from-emerald-900 via-amber-700 to-yellow-600\">\n                    <img src=\"/images/portfolio/zanzibarcaffe-footer.png\" \n                         alt=\"zanziBAR Cernavodă\" \n                         class=\"w-full h-full object-contain bg-gray-900 p-4 group-hover:scale-110 transition-transform duration-700\"\n                         loading=\"lazy\"\n                         onerror=\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\">\n                    <div class=\"absolute inset-0 bg-gradient-to-br from-emerald-900/90 to-amber-700/90 flex items-center justify-center\" style=\"display:none;\">\n                        <div class=\"text-center text-white p-6\">\n                            <svg class=\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 7V3m8 4V3M5 21h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z\"/>\n                            </svg>\n                            <p class=\"text-base font-bold tracking-wide\">Dezvoltare Web</p>\n                        </div>\n                    </div>\n                    <div class=\"absolute top-4 right-4 z-20\">\n                        <span class=\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-emerald-900 shadow-lg backdrop-blur-sm\">\n                            <svg class=\"w-3.5 h-3.5\" fill=\"currentColor\" viewBox=\"0 0 20 20\">\n                                <path d=\"M10 12a2 2 0 100-4 2 2 0 000 4z\"/>\n                                <path fill-rule=\"evenodd\" d=\"M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z\" clip-rule=\"evenodd\"/>\n                            </svg>\n                            Dezvoltare Web\n                        </span>\n                    </div>\n                </div>\n                \n                <div class=\"p-8 relative z-20\">\n                    <h3 class=\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-emerald-900 group-hover:to-amber-700 transition-all duration-300\">\n                        zanziBAR Cernavodă — Website Redesign & Development\n                    </h3>\n                    <p class=\"text-gray-600 mb-6 leading-relaxed line-clamp-3\">\n                        Client: zanziBAR Lounge & Coffee (Cernavodă). Platformă: Laravel/Blade (Carpathian CMS). Decembrie 2025. Meniu digital restructurat, PDF printabil, design coffee-themed, SEO local și responsivitate completă.\n                    </p>\n                    \n                    <a href=\"https://zanzibarcaffe.ro\" target=\"_blank\" rel=\"noopener noreferrer\"\n                       class=\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-900 to-amber-700 text-white font-semibold rounded-xl hover:from-emerald-800 hover:to-amber-600 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\">\n                        Vizitează Site-ul\n                        <svg class=\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\"/>\n                        </svg>\n                    </a>\n                </div>\n            </div>\n            \n            <!-- Project 1: Carpathian AI SaaS Marketplace -->\n            <div class=\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\">\n                <!-- Gradient Overlay -->\n                <div class=\"absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\"></div>\n                \n                <!-- Image Container with Gradient Background -->\n                <div class=\"relative h-72 overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600\">\n                    <img src=\"/images/portfolio/saas-marketplace.jpg\" \n                         alt=\"Carpathian AI SaaS Marketplace\" \n                         class=\"w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700\"\n                         loading=\"lazy\"\n                         onerror=\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\">\n                    <div class=\"absolute inset-0 bg-gradient-to-br from-indigo-600/90 to-purple-600/90 flex items-center justify-center\" style=\"display:none;\">\n                        <div class=\"text-center text-white p-6\">\n                            <svg class=\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z\"/>\n                            </svg>\n                            <p class=\"text-base font-bold tracking-wide\">Platformă AI</p>\n                        </div>\n                    </div>\n                    <!-- Floating Badge -->\n                    <div class=\"absolute top-4 right-4 z-20\">\n                        <span class=\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-indigo-700 shadow-lg backdrop-blur-sm\">\n                            <svg class=\"w-3.5 h-3.5\" fill=\"currentColor\" viewBox=\"0 0 20 20\">\n                                <path fill-rule=\"evenodd\" d=\"M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z\" clip-rule=\"evenodd\"/>\n                            </svg>\n                            Platformă AI\n                        </span>\n                    </div>\n                </div>\n                \n                <!-- Content -->\n                <div class=\"p-8 relative z-20\">\n                    <h3 class=\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-600 transition-all duration-300\">\n                        Carpathian AI SaaS Marketplace\n                    </h3>\n                    <p class=\"text-gray-600 mb-6 leading-relaxed line-clamp-3\">\n                        AI-powered freelance platform connecting talented professionals with clients through intelligent matching and seamless collaboration.\n                    </p>\n                    \n                    <a href=\"https://chat.carphatian.ro\" target=\"_blank\" rel=\"noopener noreferrer\" \n                       class=\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\">\n                        Vizitează Site-ul\n                        <svg class=\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\"/>\n                        </svg>\n                    </a>\n                </div>\n            </div>\n\n            <!-- Project 2: Demo Tools Portfolio -->\n            <div class=\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\">\n                <div class=\"absolute inset-0 bg-gradient-to-br from-purple-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\"></div>\n                \n                <div class=\"relative h-72 overflow-hidden bg-gradient-to-br from-purple-600 via-blue-600 to-cyan-600\">\n                    <img src=\"/images/portfolio/demo-tools.jpg\" \n                         alt=\"Demo Tools Portfolio\" \n                         class=\"w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700\"\n                         loading=\"lazy\"\n                         onerror=\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\">\n                    <div class=\"absolute inset-0 bg-gradient-to-br from-purple-600/90 to-blue-600/90 flex items-center justify-center\" style=\"display:none;\">\n                        <div class=\"text-center text-white p-6\">\n                            <svg class=\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z\"/>\n                            </svg>\n                            <p class=\"text-base font-bold tracking-wide\">Instrumente Web</p>\n                        </div>\n                    </div>\n                    <div class=\"absolute top-4 right-4 z-20\">\n                        <span class=\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-purple-700 shadow-lg backdrop-blur-sm\">\n                            <svg class=\"w-3.5 h-3.5\" fill=\"currentColor\" viewBox=\"0 0 20 20\">\n                                <path d=\"M10 12a2 2 0 100-4 2 2 0 000 4z\"/>\n                                <path fill-rule=\"evenodd\" d=\"M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z\" clip-rule=\"evenodd\"/>\n                            </svg>\n                            Instrumente Web\n                        </span>\n                    </div>\n                </div>\n                \n                <div class=\"p-8 relative z-20\">\n                    <h3 class=\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-blue-600 transition-all duration-300\">\n                        Demo Tools Portfolio\n                    </h3>\n                    <p class=\"text-gray-600 mb-6 leading-relaxed line-clamp-3\">\n                        Professional web tools showcasing various utilities and demonstrations for modern web development.\n                    </p>\n                    \n                    <a href=\"https://social.carphatian.ro\" target=\"_blank\" rel=\"noopener noreferrer\" \n                       class=\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\">\n                        Vizitează Site-ul\n                        <svg class=\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\"/>\n                        </svg>\n                    </a>\n                </div>\n            </div>\n\n            <!-- Project 4: ATMN - Antimony Coin -->\n            <div class=\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\">\n                <div class=\"absolute inset-0 bg-gradient-to-br from-green-500/10 to-teal-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\"></div>\n                \n                <div class=\"relative h-72 overflow-hidden bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600\">\n                    <img src=\"/images/portfolio/antimony-coin.jpg\" \n                         alt=\"ATMN Antimony Coin\" \n                         class=\"w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700\"\n                         loading=\"lazy\"\n                         onerror=\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\">\n                    <div class=\"absolute inset-0 bg-gradient-to-br from-green-600/90 to-teal-600/90 flex items-center justify-center\" style=\"display:none;\">\n                        <div class=\"text-center text-white p-6\">\n                            <svg class=\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\"/>\n                            </svg>\n                            <p class=\"text-base font-bold tracking-wide\">Blockchain</p>\n                        </div>\n                    </div>\n                    <div class=\"absolute top-4 right-4 z-20\">\n                        <span class=\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-green-700 shadow-lg backdrop-blur-sm\">\n                            <svg class=\"w-3.5 h-3.5\" fill=\"currentColor\" viewBox=\"0 0 20 20\">\n                                <path fill-rule=\"evenodd\" d=\"M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z\" clip-rule=\"evenodd\"/>\n                                <path d=\"M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z\"/>\n                            </svg>\n                            Blockchain\n                        </span>\n                    </div>\n                </div>\n                \n                <div class=\"p-8 relative z-20\">\n                    <h3 class=\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-green-600 group-hover:to-teal-600 transition-all duration-300\">\n                        ATMN - Antimony Coin\n                    </h3>\n                    <p class=\"text-gray-600 mb-6 leading-relaxed line-clamp-3\">\n                        Blockchain explorer and cryptocurrency platform for Antimony Coin with real-time transaction tracking and analytics.\n                    </p>\n                    \n                    <a href=\"https://explorer.carphatian.ro\" target=\"_blank\" rel=\"noopener noreferrer\" \n                       class=\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-teal-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\">\n                        Vizitează Site-ul\n                        <svg class=\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\"/>\n                        </svg>\n                    </a>\n                </div>\n            </div>\n\n            <!-- Project 5: Language Detection -->\n            <div class=\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\">\n                <div class=\"absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\"></div>\n                \n                <div class=\"relative h-72 overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600\">\n                    <img src=\"/images/portfolio/language-detection.jpg\" \n                         alt=\"Language Detection\" \n                         class=\"w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700\"\n                         loading=\"lazy\"\n                         onerror=\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\">\n                    <div class=\"absolute inset-0 bg-gradient-to-br from-blue-600/90 to-indigo-600/90 flex items-center justify-center\" style=\"display:none;\">\n                        <div class=\"text-center text-white p-6\">\n                            <svg class=\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.613 3 18.129\"/>\n                            </svg>\n                            <p class=\"text-base font-bold tracking-wide\">OpenAI</p>\n                        </div>\n                    </div>\n                    <div class=\"absolute top-4 right-4 z-20\">\n                        <span class=\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-blue-700 shadow-lg backdrop-blur-sm\">\n                            <svg class=\"w-3.5 h-3.5\" fill=\"currentColor\" viewBox=\"0 0 20 20\">\n                                <path fill-rule=\"evenodd\" d=\"M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z\" clip-rule=\"evenodd\"/>\n                            </svg>\n                            OpenAI\n                        </span>\n                    </div>\n                </div>\n                \n                <div class=\"p-8 relative z-20\">\n                    <h3 class=\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-indigo-600 transition-all duration-300\">\n                        Language Detection\n                    </h3>\n                    <p class=\"text-gray-600 mb-6 leading-relaxed line-clamp-3\">\n                        OpenAI-powered language detection tool that accurately identifies and analyzes text in multiple languages.\n                    </p>\n                    \n                    <a href=\"https://antimony.carphatian.ro\" target=\"_blank\" rel=\"noopener noreferrer\" \n                       class=\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\">\n                        Vizitează Site-ul\n                        <svg class=\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\"/>\n                        </svg>\n                    </a>\n                </div>\n            </div>\n\n            <!-- Project 6: Carpathian CMS -->\n            <div class=\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\">\n                <div class=\"absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\"></div>\n                \n                <div class=\"relative h-72 overflow-hidden bg-gradient-to-br from-purple-600 via-fuchsia-600 to-pink-600\">\n                    <img src=\"/images/portfolio/carpathian-cms.jpg\" \n                         alt=\"Carpathian CMS\" \n                         class=\"w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700\"\n                         loading=\"lazy\"\n                         onerror=\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\">\n                    <div class=\"absolute inset-0 bg-gradient-to-br from-purple-600/90 to-pink-600/90 flex items-center justify-center\" style=\"display:none;\">\n                        <div class=\"text-center text-white p-6\">\n                            <svg class=\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\"/>\n                            </svg>\n                            <p class=\"text-base font-bold tracking-wide\">Bazat pe AI</p>\n                        </div>\n                    </div>\n                    <div class=\"absolute top-4 right-4 z-20\">\n                        <span class=\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-purple-700 shadow-lg backdrop-blur-sm\">\n                            <svg class=\"w-3.5 h-3.5\" fill=\"currentColor\" viewBox=\"0 0 20 20\">\n                                <path fill-rule=\"evenodd\" d=\"M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z\" clip-rule=\"evenodd\"/>\n                            </svg>\n                            Bazat pe AI\n                        </span>\n                    </div>\n                </div>\n                \n                <div class=\"p-8 relative z-20\">\n                    <h3 class=\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-pink-600 transition-all duration-300\">\n                        Carpathian CMS\n                    </h3>\n                    <p class=\"text-gray-600 mb-6 leading-relaxed line-clamp-3\">\n                        Modern content management system powered by AI, featuring advanced page building and seamless content creation.\n                    </p>\n                    \n                    <a href=\"https://cms.carphatian.ro\" target=\"_blank\" rel=\"noopener noreferrer\" \n                       class=\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\">\n                        Vizitează Site-ul\n                        <svg class=\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\"/>\n                        </svg>\n                    </a>\n                </div>\n            </div>\n\n        </div>\n\n            \n    </div>\n</div>\n\n<style>\n@keyframes fade-in {\n    from {\n        opacity: 0;\n        transform: translateY(20px);\n    }\n    to {\n        opacity: 1;\n        transform: translateY(0);\n    }\n}\n\n.animate-fade-in {\n    animation: fade-in 1s ease-out;\n}\n\n.line-clamp-3 {\n    display: -webkit-box;\n    -webkit-line-clamp: 3;\n    -webkit-box-orient: vertical;\n    overflow: hidden;\n}\n</style>\n\n<!-- Footer -->\n<footer class=\"bg-gray-800 text-white py-12\">\n    <div class=\"max-w-7xl mx-auto px-4\">\n        <div class=\"grid md:grid-cols-4 gap-8\">\n            <div>\n                <h3 class=\"text-lg font-bold mb-4\">Laravel</h3>\n                <p class=\"text-gray-400\">Soluții profesionale pentru businessul tău</p>\n            </div>\n            \n            <div>\n                <h4 class=\"font-semibold mb-4\">Pagini</h4>\n                <ul class=\"space-y-2\">\n                    <li><a href=\"/\" class=\"text-gray-400 hover:text-white\">Acasă</a></li>\n                    <li><a href=\"/portfolios\" class=\"text-gray-400 hover:text-white\">Portofoliu</a></li>\n                    <li><a href=\"/blog\" class=\"text-gray-400 hover:text-white\">Blog</a></li>\n                    <li><a href=\"/contact\" class=\"text-gray-400 hover:text-white\">Contact</a></li>\n                </ul>\n            </div>\n            \n            <div>\n                <h4 class=\"font-semibold mb-4\">Servicii</h4>\n                <ul class=\"space-y-2\">\n                    <li><a href=\"/shop\" class=\"text-gray-400 hover:text-white\">Magazin</a></li>\n                    <li><a href=\"/products\" class=\"text-gray-400 hover:text-white\">Produse</a></li>\n                </ul>\n            </div>\n            \n            <div>\n                <h4 class=\"font-semibold mb-4\">Admin</h4>\n                <ul class=\"space-y-2\">\n                    <li><a href=\"/admin\" class=\"text-gray-400 hover:text-white\">Panou Control</a></li>\n                </ul>\n            </div>\n        </div>\n        \n        <div class=\"border-t border-gray-700 mt-8 pt-8 text-center text-gray-400\">\n            <p>By Carphatian</p>\n        </div>\n    </div>\n</footer>\n</body>\n</html>\n\";s:12:\"content_type\";s:24:\"text/html; charset=utf-8\";}',1766306174),('page_cache_ro_eac44b29f3f3e4bf7c375c1520a90619','a:2:{s:7:\"content\";s:22773:\"<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    \n    <!-- Primary Meta Tags -->\n    <title>Blog - CMS Articles &amp; Tutorials | Carphatian CMS</title>\n    <meta name=\"title\" content=\"Blog - CMS Articles &amp; Tutorials\">\n    <meta name=\"description\" content=\"Discover the latest articles, tutorials and guides about web development, CMS, AI and technology\">\n    <meta name=\"keywords\" content=\"web development blog, programming tutorials, laravel tutorials, php development, javascript tips, react guides, vue.js tutorials, software engineering, coding best practices, web design trends, tech blog, developer resources, cms tutorials, fullstack development\">\n    <meta name=\"author\" content=\"Carphatian CMS\">\n    <meta name=\"robots\" content=\"index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1\">\n    <link rel=\"canonical\" href=\"https://carphatian.ro/blog\">\n    \n    <!-- Open Graph / Facebook -->\n    <meta property=\"og:type\" content=\"website\">\n    <meta property=\"og:url\" content=\"https://carphatian.ro/blog\">\n    <meta property=\"og:title\" content=\"Blog - CMS Articles &amp; Tutorials\">\n    <meta property=\"og:description\" content=\"Discover the latest articles, tutorials and guides about web development, CMS, AI and technology\">\n    <meta property=\"og:image\" content=\"https://carphatian.ro/images/carpathian-og-image.jpg\">\n    <meta property=\"og:locale\" content=\"en\">\n    <meta property=\"og:site_name\" content=\"Carphatian CMS\">\n    \n    <!-- Twitter Card -->\n    <meta name=\"twitter:card\" content=\"summary_large_image\">\n    <meta name=\"twitter:url\" content=\"https://carphatian.ro/blog\">\n    <meta name=\"twitter:title\" content=\"Blog - CMS Articles &amp; Tutorials\">\n    <meta name=\"twitter:description\" content=\"Discover the latest articles, tutorials and guides about web development, CMS, AI and technology\">\n    <meta name=\"twitter:image\" content=\"https://carphatian.ro/images/carpathian-og-image.jpg\">\n    \n    <!-- Additional SEO -->\n    <meta name=\"geo.region\" content=\"RO\">\n    <meta name=\"geo.placename\" content=\"Romania\">\n    <meta name=\"language\" content=\"en\">\n    <meta name=\"rating\" content=\"general\">\n    <script src=\"https://cdn.tailwindcss.com\"></script>\n    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\n    <link href=\"https://unpkg.com/aos@2.3.1/dist/aos.css\" rel=\"stylesheet\">\n    <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>\n    <style>\n        @keyframes float {\n            0%, 100% { transform: translateY(0px); }\n            50% { transform: translateY(-20px); }\n        }\n        .float-animation {\n            animation: float 6s ease-in-out infinite;\n        }\n        @keyframes pulse-glow {\n            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }\n            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }\n        }\n        .pulse-glow {\n            animation: pulse-glow 2s ease-in-out infinite;\n        }\n    </style>\n</head>\n<body class=\"bg-gray-50\">\n    <nav class=\"bg-white shadow-sm sticky top-0 z-50\">\n    <div class=\"container mx-auto px-4\">\n        <div class=\"flex items-center justify-between h-24\">\n            <!-- Logo -->\n            <div class=\"flex-shrink-0\">\n                <a href=\"/\" class=\"flex items-center\">\n                    <img src=\"/images/carphatian-logo-transparent.png\" alt=\"Carphatian CMS\" style=\"width: 280px; height: auto;\">\n                </a>\n            </div>\n\n            <!-- Desktop Navigation -->\n            <div class=\"hidden md:flex items-center flex-wrap gap-3 lg:gap-4 xl:gap-6\">\n                <!-- Home Link -->\n                <a href=\"/\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Home\n                </a>\n                \n                <!-- Plugin Dropdown Menus -->\n                                \n                <!-- Portfolio Link -->\n                <a href=\"/portfolios\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Portfolio\n                </a>\n                \n                <!-- Default Links -->\n                <a href=\"/blog\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap font-semibold text-purple-600\">\n                    Blog\n                </a>\n                <a href=\"/contact\" class=\"text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap \">\n                    Contact\n                </a>\n\n                <!-- Language Switcher -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>EN</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n\n                <!-- Cart Icon with Badge -->\n                <div class=\"relative\" x-data=\"{ cartCount: 0 }\" x-init=\"fetch(\'/cart/count\').then(r => r.json()).then(d => cartCount = d.count).catch(() => cartCount = 0)\">\n                    <a href=\"/cart\" class=\"text-gray-700 hover:text-purple-600\">\n                        <svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\"/>\n                        </svg>\n                        <span x-show=\"cartCount > 0\" \n                              x-text=\"cartCount\" \n                              class=\"absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold\"\n                              style=\"display: none;\">\n                        </span>\n                    </a>\n                </div>\n\n                <!-- Admin Link -->\n                <a href=\"/admin\" class=\"bg-purple-600 text-white px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-purple-700 transition text-sm lg:text-base font-semibold whitespace-nowrap\">\n                    Admin\n                </a>\n            </div>\n\n            <!-- Mobile menu button -->\n            <div class=\"md:hidden flex items-center space-x-3\">\n                <!-- Language Switcher Mobile -->\n                <div class=\"relative inline-block text-left\" x-data=\"{ open: false }\">\n    <button @click=\"open = !open\" type=\"button\" class=\"inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500\">\n        <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129\" />\n        </svg>\n        <span>EN</span>\n        <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\" />\n        </svg>\n    </button>\n\n    <div x-show=\"open\" @click.away=\"open = false\" class=\"absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none\" x-transition>\n        <div class=\"py-1\">\n            <a href=\"https://carphatian.ro/lang/en\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 font-semibold\">\n                <span class=\"text-lg\">🇬🇧</span>\n                <span>English</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/ro\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇷🇴</span>\n                <span>Română</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/de\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇩🇪</span>\n                <span>Deutsch</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/es\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇪🇸</span>\n                <span>Español</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/fr\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇫🇷</span>\n                <span>Français</span>\n            </a>\n            <a href=\"https://carphatian.ro/lang/it\" class=\"flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 \">\n                <span class=\"text-lg\">🇮🇹</span>\n                <span>Italiano</span>\n            </a>\n        </div>\n    </div>\n</div>\n                \n                <button id=\"mobile-menu-button\" class=\"text-gray-700 hover:text-purple-600 focus:outline-none\">\n                    <svg class=\"h-6 w-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M4 6h16M4 12h16M4 18h16\"></path>\n                    </svg>\n                </button>\n            </div>\n        </div>\n\n        <!-- Mobile menu -->\n        <div id=\"mobile-menu\" class=\"hidden md:hidden pb-4\">\n            <a href=\"/\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Home\n            </a>\n            \n                        \n            <a href=\"/portfolios\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 \">\n                Portfolio\n            </a>\n            <a href=\"/blog\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Blog\n            </a>\n            <a href=\"/contact\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Contact\n            </a>\n            <a href=\"/cart\" class=\"block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600\">\n                Cart\n            </a>\n            <a href=\"/admin\" class=\"block px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50\">\n                Admin\n            </a>\n        </div>\n    </div>\n</nav>\n\n<script>\n    document.addEventListener(\'DOMContentLoaded\', function() {\n        const mobileMenuButton = document.getElementById(\'mobile-menu-button\');\n        const mobileMenu = document.getElementById(\'mobile-menu\');\n        \n        if (mobileMenuButton && mobileMenu) {\n            mobileMenuButton.addEventListener(\'click\', function() {\n                mobileMenu.classList.toggle(\'hidden\');\n            });\n        }\n    });\n</script>\n\n    <!-- Hero Section with Animated Background -->\n    <div class=\"relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white py-24 overflow-hidden\">\n        <!-- Animated Blobs -->\n        <div class=\"absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse\"></div>\n        <div class=\"absolute top-0 right-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse\" style=\"animation-delay: 2s;\"></div>\n        <div class=\"absolute -bottom-32 left-1/2 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse\" style=\"animation-delay: 4s;\"></div>\n        \n        <div class=\"container mx-auto px-4 text-center relative z-10\">\n            <div class=\"inline-block mb-6\">\n                <div class=\"bg-white/20 backdrop-blur-sm rounded-full px-6 py-2 text-sm font-semibold\">\n                    <i class=\"fas fa-fire text-orange-300 mr-2\"></i>\n                    0 articles available\n                </div>\n            </div>\n            <h1 class=\"text-4xl md:text-6xl font-extrabold mb-6 leading-tight\" data-aos=\"fade-up\">\n                📚 Knowledge Library<br>\n                <span class=\"bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent\">CMS Carphatian</span>\n            </h1>\n            <p class=\"text-xl md:text-2xl opacity-95 max-w-3xl mx-auto mb-8\" data-aos=\"fade-up\" data-aos-delay=\"100\">\n                Latest articles, news, and updates from the world of technology\n            </p>\n            \n            <!-- Search Bar -->\n            <div class=\"max-w-2xl mx-auto\" data-aos=\"fade-up\" data-aos-delay=\"200\">\n                <div class=\"relative\">\n                    <input type=\"text\" \n                           placeholder=\"Search articles...\" \n                           class=\"w-full px-6 py-4 rounded-full text-gray-800 text-lg shadow-2xl focus:outline-none focus:ring-4 focus:ring-white/50 pl-14\">\n                    <i class=\"fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl\"></i>\n                    <button class=\"absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-2 rounded-full font-semibold hover:shadow-lg transition\">\n                        Search\n                    </button>\n                </div>\n            </div>\n        </div>\n    </div>\n\n    <!-- Main Content Area -->\n    <div class=\"container mx-auto px-4 py-12\">\n        <div class=\"flex flex-col lg:flex-row gap-8\">\n            <!-- Sidebar -->\n            <aside class=\"lg:w-1/4 space-y-6\" data-aos=\"fade-right\">\n                <!-- Categories Widget -->\n                <div class=\"bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100\">\n                    <div class=\"bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-4\">\n                        <h3 class=\"text-xl font-bold flex items-center\">\n                            <i class=\"fas fa-folder-open mr-3\"></i>\n                            Categories\n                        </h3>\n                    </div>\n                    <div class=\"p-4\">\n                                                <ul class=\"space-y-2\">\n                            <li>\n                                <a href=\"https://carphatian.ro/blog\" \n                                   class=\"flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition group bg-gradient-to-r from-blue-50 to-purple-50 text-blue-600\">\n                                    <span class=\"flex items-center font-medium\">\n                                        <i class=\"fas fa-th mr-3 text-blue-500\"></i>\n                                        All Articles\n                                    </span>\n                                    <span class=\"bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-bold\">\n                                        0\n                                    </span>\n                                </a>\n                            </li>\n                                                    </ul>\n                    </div>\n                </div>\n\n                <!-- Popular Tags -->\n                <div class=\"bg-gradient-to-br from-orange-50 to-pink-50 rounded-2xl shadow-lg p-6 border border-orange-100\">\n                    <h3 class=\"text-xl font-bold text-gray-800 mb-4 flex items-center\">\n                        <i class=\"fas fa-tags mr-3 text-orange-500\"></i>\n                        Popular Topics\n                    </h3>\n                    <div class=\"flex flex-wrap gap-2\">\n                        <span class=\"bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer\">\n                            #Design\n                        </span>\n                        <span class=\"bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer\">\n                            #Performanță\n                        </span>\n                        <span class=\"bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer\">\n                            #AI\n                        </span>\n                        <span class=\"bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer\">\n                            #Securitate\n                        </span>\n                        <span class=\"bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer\">\n                            #SEO\n                        </span>\n                        <span class=\"bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer\">\n                            #Tutorial\n                        </span>\n                    </div>\n                </div>\n\n                <!-- Newsletter Widget -->\n                <div class=\"bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-2xl p-6 text-white relative overflow-hidden\">\n                    <div class=\"absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16\"></div>\n                    <div class=\"relative z-10\">\n                        <div class=\"text-4xl mb-4 float-animation\">📬</div>\n                        <h3 class=\"text-xl font-bold mb-2\">Newsletter</h3>\n                        <p class=\"text-sm opacity-90 mb-4\">Get the latest articles</p>\n                        <input type=\"email\" placeholder=\"Your Email\" class=\"w-full px-4 py-2 rounded-lg text-gray-800 mb-3 focus:outline-none focus:ring-2 focus:ring-white\">\n                        <button class=\"w-full bg-white text-blue-600 font-bold py-2 rounded-lg hover:bg-opacity-90 transition\">\n                            Subscribe\n                        </button>\n                    </div>\n                </div>\n            </aside>\n\n            <!-- Main Content -->\n            <main class=\"lg:w-3/4\">\n                                    <!-- Empty State -->\n                    <div class=\"text-center py-20 bg-white rounded-2xl shadow-lg\" data-aos=\"fade-up\">\n                        <div class=\"inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mb-6 float-animation\">\n                            <i class=\"fas fa-newspaper text-5xl text-blue-500\"></i>\n                        </div>\n                        <h2 class=\"text-4xl font-bold text-gray-800 mb-3\">No articles yet</h2>\n                        <p class=\"text-gray-600 text-lg\">Check back soon for new content</p>\n                    </div>\n                            </main>\n        </div>\n    </div>\n\n    <!-- Call to Action Banner -->\n    <div class=\"bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 py-16 mt-20\" data-aos=\"fade-up\">\n        <div class=\"container mx-auto px-4 text-center text-white\">\n            <h2 class=\"text-3xl md:text-4xl font-bold mb-4\">🚀 Ready to Start?</h2>\n            <p class=\"text-xl mb-8 opacity-90\">Discover our platform and start creating today</p>\n            <div class=\"flex flex-wrap justify-center gap-4\">\n                <a href=\"https://carphatian.ro/shop\" class=\"bg-white text-blue-600 font-bold px-8 py-4 rounded-xl hover:bg-opacity-90 transition shadow-2xl\">\n                    <i class=\"fas fa-shopping-cart mr-2\"></i>\n                    Explore Products\n                </a>\n                <a href=\"#\" class=\"bg-transparent border-2 border-white text-white font-bold px-8 py-4 rounded-xl hover:bg-white hover:text-blue-600 transition\">\n                    <i class=\"fas fa-play-circle mr-2\"></i>\n                    Watch Demo\n                </a>\n            </div>\n        </div>\n    </div>\n\n    <!-- Footer -->\n        \n    <script src=\"https://unpkg.com/aos@2.3.1/dist/aos.js\"></script>\n    <script>\n        AOS.init({\n            duration: 800,\n            easing: \'ease-in-out\',\n            once: true,\n            offset: 100\n        });\n    </script>\n</body>\n</html>\n\";s:12:\"content_type\";s:24:\"text/html; charset=utf-8\";}',1766306134),('page_cache_ro_f7e46ce1072cc8f4f58ad79cda2f53ca','a:2:{s:7:\"content\";s:11:\"{\"count\":0}\";s:12:\"content_type\";s:16:\"application/json\";}',1766306089);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_rules`
--

DROP TABLE IF EXISTS `cache_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `path_pattern` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ttl` int NOT NULL DEFAULT '3600',
  `conditions` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_rules`
--

LOCK TABLES `cache_rules` WRITE;
/*!40000 ALTER TABLE `cache_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint unsigned DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_slug_index` (`slug`),
  KEY `categories_parent_id_index` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compliance_privacy_features`
--

DROP TABLE IF EXISTS `compliance_privacy_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compliance_privacy_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compliance_privacy_features`
--

LOCK TABLES `compliance_privacy_features` WRITE;
/*!40000 ALTER TABLE `compliance_privacy_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `compliance_privacy_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','read','replied','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_settings`
--

DROP TABLE IF EXISTS `contact_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Web Agency',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_embed` text COLLATE utf8mb4_unicode_ci,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_hours` text COLLATE utf8mb4_unicode_ci,
  `receive_emails` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_settings`
--

LOCK TABLES `contact_settings` WRITE;
/*!40000 ALTER TABLE `contact_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cookie_consents`
--

DROP TABLE IF EXISTS `cookie_consents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cookie_consents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consents` json NOT NULL,
  `consented_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cookie_consents_user_id_foreign` (`user_id`),
  CONSTRAINT `cookie_consents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookie_consents`
--

LOCK TABLES `cookie_consents` WRITE;
/*!40000 ALTER TABLE `cookie_consents` DISABLE KEYS */;
/*!40000 ALTER TABLE `cookie_consents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_usages`
--

DROP TABLE IF EXISTS `coupon_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_usages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_usages_user_id_foreign` (`user_id`),
  KEY `coupon_usages_order_id_foreign` (`order_id`),
  KEY `coupon_usages_coupon_id_user_id_index` (`coupon_id`,`user_id`),
  CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_usages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_usages`
--

LOCK TABLES `coupon_usages` WRITE;
/*!40000 ALTER TABLE `coupon_usages` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('percentage','fixed_amount','free_shipping') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_purchase` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_limit_per_user` int DEFAULT NULL,
  `times_used` int NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `applicable_products` json DEFAULT NULL,
  `applicable_categories` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_code_is_active_index` (`code`,`is_active`),
  KEY `coupons_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_field_values`
--

DROP TABLE IF EXISTS `custom_field_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_field_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `field_id` bigint unsigned NOT NULL,
  `fieldable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fieldable_id` bigint unsigned NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_field_values_field_id_foreign` (`field_id`),
  KEY `custom_field_values_fieldable_type_fieldable_id_index` (`fieldable_type`,`fieldable_id`),
  CONSTRAINT `custom_field_values_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_field_values`
--

LOCK TABLES `custom_field_values` WRITE;
/*!40000 ALTER TABLE `custom_field_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_field_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_fields`
--

DROP TABLE IF EXISTS `custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_type_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('text','textarea','wysiwyg','number','email','url','date','select','checkbox','radio','file','image','gallery','repeater') COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_fields_key_unique` (`key`),
  KEY `custom_fields_post_type_id_foreign` (`post_type_id`),
  CONSTRAINT `custom_fields_post_type_id_foreign` FOREIGN KEY (`post_type_id`) REFERENCES `custom_post_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_fields`
--

LOCK TABLES `custom_fields` WRITE;
/*!40000 ALTER TABLE `custom_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_post_types`
--

DROP TABLE IF EXISTS `custom_post_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_post_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `supports` json NOT NULL,
  `taxonomies` json DEFAULT NULL,
  `is_hierarchical` tinyint(1) NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `menu_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_position` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_post_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_post_types`
--

LOCK TABLES `custom_post_types` WRITE;
/*!40000 ALTER TABLE `custom_post_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_post_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_post_types_system`
--

DROP TABLE IF EXISTS `custom_post_types_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_post_types_system` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_post_types_system`
--

LOCK TABLES `custom_post_types_system` WRITE;
/*!40000 ALTER TABLE `custom_post_types_system` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_post_types_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_taxonomies`
--

DROP TABLE IF EXISTS `custom_taxonomies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_taxonomies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_type_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_hierarchical` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_taxonomies_slug_unique` (`slug`),
  KEY `custom_taxonomies_post_type_id_foreign` (`post_type_id`),
  CONSTRAINT `custom_taxonomies_post_type_id_foreign` FOREIGN KEY (`post_type_id`) REFERENCES `custom_post_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_taxonomies`
--

LOCK TABLES `custom_taxonomies` WRITE;
/*!40000 ALTER TABLE `custom_taxonomies` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_taxonomies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_deletion_requests`
--

DROP TABLE IF EXISTS `data_deletion_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_deletion_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','processing','completed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `scheduled_for` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data_deletion_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `data_deletion_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_deletion_requests`
--

LOCK TABLES `data_deletion_requests` WRITE;
/*!40000 ALTER TABLE `data_deletion_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_deletion_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_export_requests`
--

DROP TABLE IF EXISTS `data_export_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_export_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('pending','processing','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data_export_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `data_export_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_export_requests`
--

LOCK TABLES `data_export_requests` WRITE;
/*!40000 ALTER TABLE `data_export_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_export_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `database_query_logs`
--

DROP TABLE IF EXISTS `database_query_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `database_query_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `query` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `execution_time` decimal(10,4) NOT NULL,
  `bindings` json DEFAULT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mysql',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `database_query_logs_execution_time_index` (`execution_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `database_query_logs`
--

LOCK TABLES `database_query_logs` WRITE;
/*!40000 ALTER TABLE `database_query_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `database_query_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_html` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_text` text COLLATE utf8mb4_unicode_ci,
  `variables` json DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_templates_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feature_flags`
--

DROP TABLE IF EXISTS `feature_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feature_flags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `rollout_percentage` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feature_flags_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feature_flags`
--

LOCK TABLES `feature_flags` WRITE;
/*!40000 ALTER TABLE `feature_flags` DISABLE KEYS */;
/*!40000 ALTER TABLE `feature_flags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_submissions`
--

DROP TABLE IF EXISTS `form_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `data` json NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `is_spam` tinyint(1) NOT NULL DEFAULT '0',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_submissions_form_id_foreign` (`form_id`),
  KEY `form_submissions_user_id_foreign` (`user_id`),
  CONSTRAINT `form_submissions_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `form_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_submissions`
--

LOCK TABLES `form_submissions` WRITE;
/*!40000 ALTER TABLE `form_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `fields` json NOT NULL,
  `settings` json DEFAULT NULL,
  `success_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_submissions` tinyint(1) NOT NULL DEFAULT '1',
  `send_email` tinyint(1) NOT NULL DEFAULT '0',
  `email_recipients` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `forms_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms`
--

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freelancer_earnings`
--

DROP TABLE IF EXISTS `freelancer_earnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_earnings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','available','withdrawn') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `available_at` timestamp NULL DEFAULT NULL,
  `withdrawn_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `freelancer_earnings_order_id_foreign` (`order_id`),
  KEY `freelancer_earnings_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `freelancer_earnings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `freelancer_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `freelancer_earnings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freelancer_earnings`
--

LOCK TABLES `freelancer_earnings` WRITE;
/*!40000 ALTER TABLE `freelancer_earnings` DISABLE KEYS */;
/*!40000 ALTER TABLE `freelancer_earnings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freelancer_messages`
--

DROP TABLE IF EXISTS `freelancer_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned DEFAULT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `freelancer_messages_receiver_id_foreign` (`receiver_id`),
  KEY `freelancer_messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
  KEY `freelancer_messages_order_id_is_read_index` (`order_id`,`is_read`),
  CONSTRAINT `freelancer_messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `freelancer_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `freelancer_messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `freelancer_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freelancer_messages`
--

LOCK TABLES `freelancer_messages` WRITE;
/*!40000 ALTER TABLE `freelancer_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `freelancer_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freelancer_orders`
--

DROP TABLE IF EXISTS `freelancer_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gig_id` bigint unsigned NOT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `package_type` enum('basic','standard','premium') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extras` json DEFAULT NULL,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `seller_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','in_progress','in_review','completed','cancelled','disputed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `due_date` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `freelancer_orders_order_number_unique` (`order_number`),
  KEY `freelancer_orders_gig_id_foreign` (`gig_id`),
  KEY `freelancer_orders_buyer_id_status_index` (`buyer_id`,`status`),
  KEY `freelancer_orders_seller_id_status_index` (`seller_id`,`status`),
  KEY `freelancer_orders_status_index` (`status`),
  CONSTRAINT `freelancer_orders_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `freelancer_orders_gig_id_foreign` FOREIGN KEY (`gig_id`) REFERENCES `gigs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `freelancer_orders_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freelancer_orders`
--

LOCK TABLES `freelancer_orders` WRITE;
/*!40000 ALTER TABLE `freelancer_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `freelancer_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freelancer_profiles`
--

DROP TABLE IF EXISTS `freelancer_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` json DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `certifications` json DEFAULT NULL,
  `availability` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `response_time` int DEFAULT NULL,
  `completed_orders` int NOT NULL DEFAULT '0',
  `total_earned` decimal(12,2) NOT NULL DEFAULT '0.00',
  `average_rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_pro` tinyint(1) NOT NULL DEFAULT '0',
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `freelancer_profiles_user_id_unique` (`user_id`),
  CONSTRAINT `freelancer_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freelancer_profiles`
--

LOCK TABLES `freelancer_profiles` WRITE;
/*!40000 ALTER TABLE `freelancer_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `freelancer_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gig_extras`
--

DROP TABLE IF EXISTS `gig_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gig_extras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gig_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `additional_days` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gig_extras_gig_id_foreign` (`gig_id`),
  CONSTRAINT `gig_extras_gig_id_foreign` FOREIGN KEY (`gig_id`) REFERENCES `gigs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gig_extras`
--

LOCK TABLES `gig_extras` WRITE;
/*!40000 ALTER TABLE `gig_extras` DISABLE KEYS */;
/*!40000 ALTER TABLE `gig_extras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gig_packages`
--

DROP TABLE IF EXISTS `gig_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gig_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gig_id` bigint unsigned NOT NULL,
  `type` enum('basic','standard','premium') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `delivery_days` int NOT NULL,
  `revisions` int NOT NULL,
  `features` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gig_packages_gig_id_type_unique` (`gig_id`,`type`),
  CONSTRAINT `gig_packages_gig_id_foreign` FOREIGN KEY (`gig_id`) REFERENCES `gigs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gig_packages`
--

LOCK TABLES `gig_packages` WRITE;
/*!40000 ALTER TABLE `gig_packages` DISABLE KEYS */;
/*!40000 ALTER TABLE `gig_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gig_reviews`
--

DROP TABLE IF EXISTS `gig_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gig_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gig_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` int unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `ratings_breakdown` json DEFAULT NULL,
  `seller_response` text COLLATE utf8mb4_unicode_ci,
  `responded_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gig_reviews_order_id_user_id_unique` (`order_id`,`user_id`),
  KEY `gig_reviews_user_id_foreign` (`user_id`),
  KEY `gig_reviews_gig_id_rating_index` (`gig_id`,`rating`),
  CONSTRAINT `gig_reviews_gig_id_foreign` FOREIGN KEY (`gig_id`) REFERENCES `gigs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gig_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `freelancer_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gig_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gig_reviews`
--

LOCK TABLES `gig_reviews` WRITE;
/*!40000 ALTER TABLE `gig_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `gig_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gigs`
--

DROP TABLE IF EXISTS `gigs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gigs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `images` json DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `delivery_days` int NOT NULL DEFAULT '7',
  `revisions` int NOT NULL DEFAULT '1',
  `status` enum('draft','active','paused','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `views` int NOT NULL DEFAULT '0',
  `orders_in_queue` int NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_reviews` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gigs_slug_unique` (`slug`),
  KEY `gigs_user_id_foreign` (`user_id`),
  KEY `gigs_status_user_id_index` (`status`,`user_id`),
  KEY `gigs_category_id_index` (`category_id`),
  CONSTRAINT `gigs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gigs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gigs`
--

LOCK TABLES `gigs` WRITE;
/*!40000 ALTER TABLE `gigs` DISABLE KEYS */;
/*!40000 ALTER TABLE `gigs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_settings`
--

DROP TABLE IF EXISTS `global_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `global_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'My CMS Website',
  `site_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_description` text COLLATE utf8mb4_unicode_ci,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `date_format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y-m-d',
  `time_format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H:i:s',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `maintenance_message` text COLLATE utf8mb4_unicode_ci,
  `social_links` json DEFAULT NULL,
  `custom_scripts` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_settings`
--

LOCK TABLES `global_settings` WRITE;
/*!40000 ALTER TABLE `global_settings` DISABLE KEYS */;
INSERT INTO `global_settings` VALUES (1,'Web Agency CMS','cms.carphatian.ro',NULL,NULL,'admin@example.com','Professional web development and digital solutions','UTC','Y-m-d','H:i:s',0,NULL,NULL,NULL,'2025-12-21 07:31:14','2025-12-21 07:31:14');
/*!40000 ALTER TABLE `global_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_jobs`
--

DROP TABLE IF EXISTS `import_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_rows` int NOT NULL DEFAULT '0',
  `processed_rows` int NOT NULL DEFAULT '0',
  `failed_rows` int NOT NULL DEFAULT '0',
  `errors` json DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `import_jobs_user_id_foreign` (`user_id`),
  CONSTRAINT `import_jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_jobs`
--

LOCK TABLES `import_jobs` WRITE;
/*!40000 ALTER TABLE `import_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installed_packages`
--

DROP TABLE IF EXISTS `installed_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `installed_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('plugin','template','theme','module') COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `install_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `settings` json DEFAULT NULL,
  `requirements` json DEFAULT NULL,
  `update_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_checked` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `installed_packages_slug_unique` (`slug`),
  KEY `installed_packages_type_is_active_index` (`type`,`is_active`),
  KEY `installed_packages_type_index` (`type`),
  KEY `installed_packages_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installed_packages`
--

LOCK TABLES `installed_packages` WRITE;
/*!40000 ALTER TABLE `installed_packages` DISABLE KEYS */;
/*!40000 ALTER TABLE `installed_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_logs`
--

DROP TABLE IF EXISTS `inventory_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` enum('purchase','sale','return','adjustment','damage','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_before` int NOT NULL,
  `quantity_change` int NOT NULL,
  `quantity_after` int NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_logs_user_id_foreign` (`user_id`),
  KEY `inventory_logs_product_id_created_at_index` (`product_id`,`created_at`),
  KEY `inventory_logs_variant_id_created_at_index` (`variant_id`,`created_at`),
  CONSTRAINT `inventory_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_logs_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_logs`
--

LOCK TABLES `inventory_logs` WRITE;
/*!40000 ALTER TABLE `inventory_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` enum('ltr','rtl') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_schedules`
--

DROP TABLE IF EXISTS `maintenance_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_at` timestamp NOT NULL,
  `end_at` timestamp NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci,
  `allowed_ips` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_schedules`
--

LOCK TABLES `maintenance_schedules` WRITE;
/*!40000 ALTER TABLE `maintenance_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint unsigned NOT NULL,
  `width` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `alt_text` text COLLATE utf8mb4_unicode_ci,
  `caption` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `user_id` bigint unsigned NOT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_type_mime_type_index` (`type`,`mime_type`),
  KEY `media_user_id_index` (`user_id`),
  KEY `media_mime_type_index` (`mime_type`),
  KEY `media_type_index` (`type`),
  CONSTRAINT `media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_cdn_caches`
--

DROP TABLE IF EXISTS `media_cdn_caches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_cdn_caches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `media_id` bigint unsigned NOT NULL,
  `cdn_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cdn_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cached_at` timestamp NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_cdn_caches_media_id_foreign` (`media_id`),
  CONSTRAINT `media_cdn_caches_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_cdn_caches`
--

LOCK TABLES `media_cdn_caches` WRITE;
/*!40000 ALTER TABLE `media_cdn_caches` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_cdn_caches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_conversions`
--

DROP TABLE IF EXISTS `media_conversions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_conversions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `media_id` bigint unsigned NOT NULL,
  `conversion_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint unsigned NOT NULL,
  `custom_properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_conversions_media_id_foreign` (`media_id`),
  CONSTRAINT `media_conversions_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_conversions`
--

LOCK TABLES `media_conversions` WRITE;
/*!40000 ALTER TABLE `media_conversions` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_conversions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_folders`
--

DROP TABLE IF EXISTS `media_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_folders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_folders_parent_id_foreign` (`parent_id`),
  CONSTRAINT `media_folders_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `media_folders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_folders`
--

LOCK TABLES `media_folders` WRITE;
/*!40000 ALTER TABLE `media_folders` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `reference_id` bigint unsigned DEFAULT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `css_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  KEY `menu_items_parent_id_foreign` (`parent_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_styles`
--

DROP TABLE IF EXISTS `menu_styles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_styles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `html_template` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `css_template` text COLLATE utf8mb4_unicode_ci,
  `config` json DEFAULT NULL,
  `preview_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_styles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_styles`
--

LOCK TABLES `menu_styles` WRITE;
/*!40000 ALTER TABLE `menu_styles` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_styles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_03_102526_create_ai_generations_table',1),(5,'2025_12_03_102527_b_create_templates_table',1),(6,'2025_12_03_102527_create_tags_table',1),(7,'2025_12_03_102528_create_categories_table',1),(8,'2025_12_03_102528_create_posts_table',1),(9,'2025_12_03_102529_create_media_table',1),(10,'2025_12_03_102529_create_pages_table',1),(11,'2025_12_03_102535_create_plugins_table',1),(12,'2025_12_03_102536_create_settings_table',1),(13,'2025_12_03_102536_create_template_blocks_table',1),(14,'2025_12_03_105000_create_template_zones_system',1),(15,'2025_12_03_110035_create_ecommerce_tables',1),(16,'2025_12_03_120008_create_contact_settings_table',1),(17,'2025_12_03_121906_create_menus_table',1),(18,'2025_12_03_121907_create_menu_items_table',1),(19,'2025_12_03_124605_create_ai_settings_table',1),(20,'2025_12_03_125000_create_global_settings_table',1),(21,'2025_12_03_125100_create_shop_settings_table',1),(22,'2025_12_03_125200_create_seo_settings_table',1),(23,'2025_12_03_141920_create_widgets_table',1),(24,'2025_12_05_022806_create_installed_packages_table',1),(25,'2025_12_05_022806_create_system_settings_table',1),(26,'2025_12_05_022806_create_update_history_table',1),(27,'2025_12_05_032249_create_contact_messages_table',1),(28,'2025_12_05_035427_convert_content_to_json',1),(29,'2025_12_06_040541_create_permission_tables',1),(30,'2025_12_06_040541_create_personal_access_tokens_table',1),(31,'2025_12_06_040543_add_two_factor_columns_to_users_table',1),(32,'2025_12_06_041545_create_page_builder_blocks_table',1),(33,'2025_12_06_042504_add_performance_indexes_to_tables',1),(34,'2025_12_06_085129_create_redirects_table',1),(35,'2025_12_06_100356_create_freelancer_marketplace_tables',1),(36,'2025_12_06_100619_enhance_products_for_ecommerce',1),(37,'2025_12_06_101159_create_custom_post_types_system',1),(38,'2025_12_06_101159_create_media_library_enhancements',1),(39,'2025_12_06_101159_create_multilanguage_system',1),(40,'2025_12_06_101200_create_compliance_privacy_features',1),(41,'2025_12_06_101200_create_custom_post_types_system',1),(42,'2025_12_06_101200_create_pwa_features',1),(43,'2025_12_06_101203_create_compliance_privacy_features',1),(44,'2025_12_06_101300_create_seo_analytics_tables',1),(45,'2025_12_06_101400_create_notification_system',1),(46,'2025_12_06_101401_create_workflow_automation',1),(47,'2025_12_06_101402_create_form_builder_system',1),(48,'2025_12_06_101403_create_backup_system',1),(49,'2025_12_06_101500_create_api_system',1),(50,'2025_12_06_101601_create_advanced_search',1),(51,'2025_12_06_101700_create_final_enterprise_features',1),(52,'2025_12_06_101800_create_multitenancy_system',1),(53,'2025_12_06_101900_create_system_monitoring',1),(54,'2025_12_06_102000_create_advanced_admin_features',1),(55,'2025_12_07_040848_create_missing_tables',1),(56,'2025_12_07_041428_create_page_builder_templates_table',1),(57,'2025_12_07_044850_add_slug_to_menus_table',1),(58,'2025_12_07_045111_create_redirects_table',1),(59,'2025_12_08_063522_add_image_to_product_categories_table',1),(60,'2025_12_18_115337_create_portfolios_table',1),(61,'2025_12_18_144747_convert_widgets_title_content_to_json',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multilanguage_system`
--

DROP TABLE IF EXISTS `multilanguage_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `multilanguage_system` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multilanguage_system`
--

LOCK TABLES `multilanguage_system` WRITE;
/*!40000 ALTER TABLE `multilanguage_system` DISABLE KEYS */;
/*!40000 ALTER TABLE `multilanguage_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_templates`
--

DROP TABLE IF EXISTS `notification_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('email','sms','push','database') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `variables` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notification_templates_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_templates`
--

LOCK TABLES `notification_templates` WRITE;
/*!40000 ALTER TABLE `notification_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_deliveries`
--

DROP TABLE IF EXISTS `order_deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `files` json DEFAULT NULL,
  `status` enum('pending','accepted','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_deliveries_order_id_foreign` (`order_id`),
  CONSTRAINT `order_deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `freelancer_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_deliveries`
--

LOCK TABLES `order_deliveries` WRITE;
/*!40000 ALTER TABLE `order_deliveries` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_revisions`
--

DROP TABLE IF EXISTS `order_revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `files` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_revisions_order_id_foreign` (`order_id`),
  CONSTRAINT `order_revisions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `freelancer_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_revisions`
--

LOCK TABLES `order_revisions` WRITE;
/*!40000 ALTER TABLE `order_revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_details` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_builder_blocks`
--

DROP TABLE IF EXISTS `page_builder_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_builder_blocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `blockable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blockable_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` json NOT NULL,
  `styles` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_builder_blocks_blockable_type_blockable_id_index` (`blockable_type`,`blockable_id`),
  KEY `page_builder_blocks_order_index` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_builder_blocks`
--

LOCK TABLES `page_builder_blocks` WRITE;
/*!40000 ALTER TABLE `page_builder_blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_builder_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_builder_templates`
--

DROP TABLE IF EXISTS `page_builder_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_builder_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blocks` json NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_builder_templates_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_builder_templates`
--

LOCK TABLES `page_builder_templates` WRITE;
/*!40000 ALTER TABLE `page_builder_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_builder_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_views`
--

DROP TABLE IF EXISTS `page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_on_page` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_views_user_id_foreign` (`user_id`),
  KEY `page_views_url_created_at_index` (`url`,`created_at`),
  CONSTRAINT `page_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_views`
--

LOCK TABLES `page_views` WRITE;
/*!40000 ALTER TABLE `page_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `user_id` bigint unsigned NOT NULL,
  `template_id` bigint unsigned DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `is_homepage` tinyint(1) NOT NULL DEFAULT '0',
  `show_in_menu` tinyint(1) NOT NULL DEFAULT '1',
  `menu_locations` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_user_id_foreign` (`user_id`),
  KEY `pages_template_id_foreign` (`template_id`),
  KEY `pages_slug_status_index` (`slug`,`status`),
  KEY `pages_status_index` (`status`),
  KEY `pages_published_at_index` (`published_at`),
  KEY `pages_is_homepage_index` (`is_homepage`),
  KEY `pages_show_in_menu_index` (`show_in_menu`),
  KEY `pages_status_order_index` (`status`,`order`),
  CONSTRAINT `pages_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_gateways`
--

DROP TABLE IF EXISTS `payment_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_gateways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `credentials` json NOT NULL,
  `settings` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_test_mode` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_gateways_provider_unique` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_gateways`
--

LOCK TABLES `payment_gateways` WRITE;
/*!40000 ALTER TABLE `payment_gateways` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_transactions`
--

DROP TABLE IF EXISTS `payment_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `gateway_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `type` enum('payment','refund','partial_refund','chargeback') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','completed','failed','cancelled','refunded') COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_transactions_transaction_id_unique` (`transaction_id`),
  KEY `payment_transactions_gateway_id_foreign` (`gateway_id`),
  KEY `payment_transactions_user_id_foreign` (`user_id`),
  KEY `payment_transactions_order_id_status_index` (`order_id`,`status`),
  KEY `payment_transactions_gateway_transaction_id_index` (`gateway_transaction_id`),
  CONSTRAINT `payment_transactions_gateway_id_foreign` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payment_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_transactions`
--

LOCK TABLES `payment_transactions` WRITE;
/*!40000 ALTER TABLE `payment_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `performance_metrics`
--

DROP TABLE IF EXISTS `performance_metrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `performance_metrics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `metric_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metric_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `measured_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `performance_metrics_metric_type_measured_at_index` (`metric_type`,`measured_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `performance_metrics`
--

LOCK TABLES `performance_metrics` WRITE;
/*!40000 ALTER TABLE `performance_metrics` DISABLE KEYS */;
/*!40000 ALTER TABLE `performance_metrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugins`
--

DROP TABLE IF EXISTS `plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plugins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0.0',
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` longtext COLLATE utf8mb4_unicode_ci,
  `config` json DEFAULT NULL,
  `hooks` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `ai_generated` tinyint(1) NOT NULL DEFAULT '0',
  `ai_generation_id` bigint unsigned DEFAULT NULL,
  `dependencies` json DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plugins_slug_unique` (`slug`),
  KEY `plugins_ai_generation_id_foreign` (`ai_generation_id`),
  KEY `plugins_slug_index` (`slug`),
  KEY `plugins_is_active_index` (`is_active`),
  CONSTRAINT `plugins_ai_generation_id_foreign` FOREIGN KEY (`ai_generation_id`) REFERENCES `ai_generations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugins`
--

LOCK TABLES `plugins` WRITE;
/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolios`
--

DROP TABLE IF EXISTS `portfolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web_development',
  `short_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `technologies` json DEFAULT NULL,
  `services` json DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `gradient_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'purple-600',
  `gradient_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pink-600',
  `badge_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'purple-700',
  `order` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portfolios_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolios`
--

LOCK TABLES `portfolios` WRITE;
/*!40000 ALTER TABLE `portfolios` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tag`
--

DROP TABLE IF EXISTS `post_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_tag` (
  `post_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `post_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tag`
--

LOCK TABLES `post_tag` WRITE;
/*!40000 ALTER TABLE `post_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `template_id` bigint unsigned DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `views` int unsigned NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `allow_comments` tinyint(1) NOT NULL DEFAULT '1',
  `custom_fields` json DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_user_id_foreign` (`user_id`),
  KEY `posts_template_id_foreign` (`template_id`),
  KEY `posts_slug_status_index` (`slug`,`status`),
  KEY `posts_category_id_index` (`category_id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_published_at_index` (`published_at`),
  KEY `posts_featured_index` (`featured`),
  KEY `posts_status_published_at_index` (`status`,`published_at`),
  KEY `posts_category_status_index` (`category_id`,`status`),
  CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privacy_policy_acceptances`
--

DROP TABLE IF EXISTS `privacy_policy_acceptances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `privacy_policy_acceptances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `policy_version` int NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accepted_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `privacy_policy_acceptances_user_id_foreign` (`user_id`),
  CONSTRAINT `privacy_policy_acceptances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privacy_policy_acceptances`
--

LOCK TABLES `privacy_policy_acceptances` WRITE;
/*!40000 ALTER TABLE `privacy_policy_acceptances` DISABLE KEYS */;
/*!40000 ALTER TABLE `privacy_policy_acceptances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_slug_unique` (`slug`),
  KEY `product_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `product_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `rating` int unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `images` json DEFAULT NULL,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `helpful_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_order_id_foreign` (`order_id`),
  KEY `product_reviews_product_id_is_approved_index` (`product_id`,`is_approved`),
  KEY `product_reviews_user_id_product_id_index` (`user_id`,`product_id`),
  CONSTRAINT `product_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributes` json NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `low_stock_threshold` int NOT NULL DEFAULT '5',
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` json DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_is_active_index` (`product_id`,`is_active`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `images` json DEFAULT NULL,
  `attributes` json DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_is_featured_index` (`is_featured`),
  KEY `products_stock_index` (`stock`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pwa_features`
--

DROP TABLE IF EXISTS `pwa_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pwa_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pwa_features`
--

LOCK TABLES `pwa_features` WRITE;
/*!40000 ALTER TABLE `pwa_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `pwa_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `redirects`
--

DROP TABLE IF EXISTS `redirects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `redirects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL DEFAULT '301',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `hits` int NOT NULL DEFAULT '0',
  `last_hit_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `redirects_from_url_unique` (`from_url`),
  KEY `redirects_from_url_index` (`from_url`),
  KEY `redirects_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `redirects`
--

LOCK TABLES `redirects` WRITE;
/*!40000 ALTER TABLE `redirects` DISABLE KEYS */;
/*!40000 ALTER TABLE `redirects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheduled_contents`
--

DROP TABLE IF EXISTS `scheduled_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scheduled_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contentable_id` bigint unsigned NOT NULL,
  `action` enum('publish','unpublish','delete') COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_for` timestamp NOT NULL,
  `status` enum('pending','executed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `executed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scheduled_contents_contentable_type_contentable_id_index` (`contentable_type`,`contentable_id`),
  KEY `scheduled_contents_scheduled_for_status_index` (`scheduled_for`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scheduled_contents`
--

LOCK TABLES `scheduled_contents` WRITE;
/*!40000 ALTER TABLE `scheduled_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `scheduled_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_indexes`
--

DROP TABLE IF EXISTS `search_indexes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_indexes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `indexable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indexable_id` bigint unsigned NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `search_indexes_indexable_type_indexable_id_index` (`indexable_type`,`indexable_id`),
  FULLTEXT KEY `search_indexes_title_content_fulltext` (`title`,`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_indexes`
--

LOCK TABLES `search_indexes` WRITE;
/*!40000 ALTER TABLE `search_indexes` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_indexes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_queries`
--

DROP TABLE IF EXISTS `search_queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_queries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `results_count` int NOT NULL DEFAULT '0',
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `search_queries_user_id_foreign` (`user_id`),
  KEY `search_queries_query_index` (`query`),
  CONSTRAINT `search_queries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_queries`
--

LOCK TABLES `search_queries` WRITE;
/*!40000 ALTER TABLE `search_queries` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_queries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_redirects`
--

DROP TABLE IF EXISTS `search_redirects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_redirects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hits` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_redirects`
--

LOCK TABLES `search_redirects` WRITE;
/*!40000 ALTER TABLE `search_redirects` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_redirects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_synonyms`
--

DROP TABLE IF EXISTS `search_synonyms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_synonyms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `synonyms` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_synonyms`
--

LOCK TABLES `search_synonyms` WRITE;
/*!40000 ALTER TABLE `search_synonyms` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_synonyms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sent_notifications`
--

DROP TABLE IF EXISTS `sent_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sent_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` enum('email','sms','push','database') COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `sent_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sent_notifications_template_id_foreign` (`template_id`),
  KEY `sent_notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `sent_notifications_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `notification_templates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sent_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sent_notifications`
--

LOCK TABLES `sent_notifications` WRITE;
/*!40000 ALTER TABLE `sent_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `sent_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_meta_templates`
--

DROP TABLE IF EXISTS `seo_meta_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seo_meta_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_template` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `schema_markup` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_meta_templates`
--

LOCK TABLES `seo_meta_templates` WRITE;
/*!40000 ALTER TABLE `seo_meta_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `seo_meta_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_settings`
--

DROP TABLE IF EXISTS `seo_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seo_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meta_title` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_txt` text COLLATE utf8mb4_unicode_ci,
  `sitemap_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sitemap_frequencies` json DEFAULT NULL,
  `google_analytics` text COLLATE utf8mb4_unicode_ci,
  `google_tag_manager` text COLLATE utf8mb4_unicode_ci,
  `facebook_pixel` text COLLATE utf8mb4_unicode_ci,
  `custom_head_scripts` text COLLATE utf8mb4_unicode_ci,
  `custom_body_scripts` text COLLATE utf8mb4_unicode_ci,
  `schema_markup` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_settings`
--

LOCK TABLES `seo_settings` WRITE;
/*!40000 ALTER TABLE `seo_settings` DISABLE KEYS */;
INSERT INTO `seo_settings` VALUES (1,'Web Agency - Professional Web Development','Professional web development and digital solutions for modern businesses',NULL,NULL,'User-agent: *\nAllow: /\nSitemap: http://cms.carphatian.ro/sitemap.xml',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-21 07:31:14','2025-12-21 07:31:14');
/*!40000 ALTER TABLE `seo_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('gb6cnURDzkqWnPHWYzcdbQgC7QVyNKcLZZJZGUXs',NULL,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IktEc0s2Ynk3ZUloV05WaWN0eEtzOEhoUDJHMHVGbVJsNldIZElEQzEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766302621),('kSfhwdOM3nsGvl4LkHTlUGoKzzcLSMsupLCOHSRq',NULL,'135.125.174.208','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkoxMHF6SmR6RnhJYXgyOFpJR3FiNHB3RHkxRmZ2VWFUbFF5VnRTaWYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766302492);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_key_group_index` (`key`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_methods`
--

DROP TABLE IF EXISTS `shipping_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_order_amount` decimal(10,2) DEFAULT NULL,
  `estimated_days_min` int DEFAULT NULL,
  `estimated_days_max` int DEFAULT NULL,
  `available_countries` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_methods`
--

LOCK TABLES `shipping_methods` WRITE;
/*!40000 ALTER TABLE `shipping_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipping_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_settings`
--

DROP TABLE IF EXISTS `shop_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$',
  `currency_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before',
  `tax_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `shipping_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `payment_gateways` json DEFAULT NULL,
  `terms_and_conditions` text COLLATE utf8mb4_unicode_ci,
  `privacy_policy` text COLLATE utf8mb4_unicode_ci,
  `return_policy` text COLLATE utf8mb4_unicode_ci,
  `order_prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ORD-',
  `inventory_management` tinyint(1) NOT NULL DEFAULT '1',
  `low_stock_alert` tinyint(1) NOT NULL DEFAULT '1',
  `low_stock_threshold` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_settings`
--

LOCK TABLES `shop_settings` WRITE;
/*!40000 ALTER TABLE `shop_settings` DISABLE KEYS */;
INSERT INTO `shop_settings` VALUES (1,'USD','$','before',0,0.00,0,'{\"stripe\": {\"enabled\": false, \"public_key\": \"\", \"secret_key\": \"\"}, \"paypal_api\": {\"secret\": \"\", \"enabled\": false, \"client_id\": \"\"}, \"bank_transfer\": {\"iban\": \"\", \"enabled\": false, \"bank_name\": \"\", \"bic_swift\": \"\", \"account_name\": \"\", \"instructions\": \"\"}, \"paypal_classic\": {\"email\": \"\", \"enabled\": false}}',NULL,NULL,NULL,'ORD-',1,1,5,'2025-12-21 07:31:14','2025-12-21 07:31:14');
/*!40000 ALTER TABLE `shop_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_health_checks`
--

DROP TABLE IF EXISTS `system_health_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_health_checks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `check_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('healthy','warning','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'healthy',
  `metrics` json DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `checked_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_health_checks`
--

LOCK TABLES `system_health_checks` WRITE;
/*!40000 ALTER TABLE `system_health_checks` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_health_checks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `system_logs_level_created_at_index` (`level`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES (1,'auto_updates','disabled','string','Automatic update mode: disabled, minor, all','2025-12-21 07:31:14','2025-12-21 07:31:14'),(2,'update_server','https://updates.carphatian.ro','url','Update server URL','2025-12-21 07:31:14','2025-12-21 07:31:14'),(3,'backup_retention_days','30','integer','Number of days to keep backups','2025-12-21 07:31:14','2025-12-21 07:31:14');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`),
  KEY `tags_slug_index` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_blocks`
--

DROP TABLE IF EXISTS `template_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_blocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `html` longtext COLLATE utf8mb4_unicode_ci,
  `css` longtext COLLATE utf8mb4_unicode_ci,
  `js` longtext COLLATE utf8mb4_unicode_ci,
  `config` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `ai_generated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_blocks_template_id_type_index` (`template_id`,`type`),
  CONSTRAINT `template_blocks_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_blocks`
--

LOCK TABLES `template_blocks` WRITE;
/*!40000 ALTER TABLE `template_blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `template_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_zones`
--

DROP TABLE IF EXISTS `template_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `settings` json DEFAULT NULL,
  `styles` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_zones_template_id_name_index` (`template_id`,`name`),
  CONSTRAINT `template_zones_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_zones`
--

LOCK TABLES `template_zones` WRITE;
/*!40000 ALTER TABLE `template_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `template_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0.0',
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `css` longtext COLLATE utf8mb4_unicode_ci,
  `js` longtext COLLATE utf8mb4_unicode_ci,
  `config` json DEFAULT NULL,
  `layouts` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `ai_generated` tinyint(1) NOT NULL DEFAULT '0',
  `ai_generation_id` bigint unsigned DEFAULT NULL,
  `color_scheme` json DEFAULT NULL,
  `typography` json DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `templates_slug_unique` (`slug`),
  KEY `templates_ai_generation_id_foreign` (`ai_generation_id`),
  KEY `templates_slug_index` (`slug`),
  KEY `templates_is_active_is_default_index` (`is_active`,`is_default`),
  CONSTRAINT `templates_ai_generation_id_foreign` FOREIGN KEY (`ai_generation_id`) REFERENCES `ai_generations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant_users`
--

DROP TABLE IF EXISTS `tenant_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenant_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_users_tenant_id_user_id_unique` (`tenant_id`,`user_id`),
  KEY `tenant_users_user_id_foreign` (`user_id`),
  CONSTRAINT `tenant_users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tenant_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant_users`
--

LOCK TABLES `tenant_users` WRITE;
/*!40000 ALTER TABLE `tenant_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `tenant_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `database` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `settings` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_domain_unique` (`domain`),
  UNIQUE KEY `tenants_database_unique` (`database`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `update_history`
--

DROP TABLE IF EXISTS `update_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `update_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `package_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plugin',
  `from_version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changelog` text COLLATE utf8mb4_unicode_ci,
  `status` enum('success','failed','rolled_back') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `updated_by` bigint unsigned DEFAULT NULL,
  `backup_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `update_history_updated_by_foreign` (`updated_by`),
  KEY `update_history_package_slug_updated_at_index` (`package_slug`,`updated_at`),
  CONSTRAINT `update_history_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `update_history`
--

LOCK TABLES `update_history` WRITE;
/*!40000 ALTER TABLE `update_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `update_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` json DEFAULT NULL,
  `last_activity_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_sessions_session_id_unique` (`session_id`),
  KEY `user_sessions_user_id_foreign` (`user_id`),
  CONSTRAINT `user_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_sessions`
--

LOCK TABLES `user_sessions` WRITE;
/*!40000 ALTER TABLE `user_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_tenant_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_created_at_index` (`created_at`),
  KEY `users_current_tenant_id_foreign` (`current_tenant_id`),
  CONSTRAINT `users_current_tenant_id_foreign` FOREIGN KEY (`current_tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webhook_deliveries`
--

DROP TABLE IF EXISTS `webhook_deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webhook_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `webhook_id` bigint unsigned NOT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json NOT NULL,
  `status_code` int DEFAULT NULL,
  `response` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','delivered','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `attempts` int NOT NULL DEFAULT '0',
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `webhook_deliveries_webhook_id_foreign` (`webhook_id`),
  CONSTRAINT `webhook_deliveries_webhook_id_foreign` FOREIGN KEY (`webhook_id`) REFERENCES `webhooks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webhook_deliveries`
--

LOCK TABLES `webhook_deliveries` WRITE;
/*!40000 ALTER TABLE `webhook_deliveries` DISABLE KEYS */;
/*!40000 ALTER TABLE `webhook_deliveries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webhooks`
--

DROP TABLE IF EXISTS `webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webhooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `events` json NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `failed_attempts` int NOT NULL DEFAULT '0',
  `last_triggered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `webhooks_user_id_foreign` (`user_id`),
  CONSTRAINT `webhooks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webhooks`
--

LOCK TABLES `webhooks` WRITE;
/*!40000 ALTER TABLE `webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `webhooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websocket_connections`
--

DROP TABLE IF EXISTS `websocket_connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `websocket_connections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `socket_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channels` json DEFAULT NULL,
  `connected_at` timestamp NOT NULL,
  `last_activity_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `websocket_connections_socket_id_unique` (`socket_id`),
  KEY `websocket_connections_user_id_foreign` (`user_id`),
  CONSTRAINT `websocket_connections_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websocket_connections`
--

LOCK TABLES `websocket_connections` WRITE;
/*!40000 ALTER TABLE `websocket_connections` DISABLE KEYS */;
/*!40000 ALTER TABLE `websocket_connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` json DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_variant_id_unique` (`user_id`,`product_id`,`variant_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  KEY `wishlists_variant_id_foreign` (`variant_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdrawal_requests`
--

DROP TABLE IF EXISTS `withdrawal_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdrawal_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_details` json DEFAULT NULL,
  `status` enum('pending','processing','completed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdrawal_requests_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `withdrawal_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdrawal_requests`
--

LOCK TABLES `withdrawal_requests` WRITE;
/*!40000 ALTER TABLE `withdrawal_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdrawal_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflow_executions`
--

DROP TABLE IF EXISTS `workflow_executions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow_executions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workflow_id` bigint unsigned NOT NULL,
  `status` enum('running','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running',
  `context` json DEFAULT NULL,
  `results` json DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_executions_workflow_id_foreign` (`workflow_id`),
  CONSTRAINT `workflow_executions_workflow_id_foreign` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflow_executions`
--

LOCK TABLES `workflow_executions` WRITE;
/*!40000 ALTER TABLE `workflow_executions` DISABLE KEYS */;
/*!40000 ALTER TABLE `workflow_executions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflows`
--

DROP TABLE IF EXISTS `workflows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `trigger_type` enum('schedule','event','webhook') COLLATE utf8mb4_unicode_ci NOT NULL,
  `trigger_config` json NOT NULL,
  `actions` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `execution_count` int NOT NULL DEFAULT '0',
  `last_execution_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `workflows_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflows`
--

LOCK TABLES `workflows` WRITE;
/*!40000 ALTER TABLE `workflows` DISABLE KEYS */;
/*!40000 ALTER TABLE `workflows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'carphatian_cms'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-21  7:37:52
