-- MySQL dump 10.13  Distrib 8.4.7, for Linux (x86_64)
--
-- Host: localhost    Database: carphatian_cms
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `changes` json DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `severity` enum('info','warning','error') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_generations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prompt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `parameters` json DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'groq',
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tokens_used` int unsigned DEFAULT NULL,
  `generation_time` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_generations_user_id_foreign` (`user_id`),
  KEY `ai_generations_type_user_id_index` (`type`,`user_id`),
  KEY `ai_generations_status_index` (`status`),
  CONSTRAINT `ai_generations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_generations`
--

LOCK TABLES `ai_generations` WRITE;
/*!40000 ALTER TABLE `ai_generations` DISABLE KEYS */;
INSERT INTO `ai_generations` (`id`, `type`, `prompt`, `response`, `parameters`, `model`, `user_id`, `status`, `error_message`, `tokens_used`, `generation_time`, `created_at`, `updated_at`) VALUES (1,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>Upon analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Query the database to retrieve the list of 6 products with invalid category references.</li>\n  <li>Verify the product IDs and corresponding category references to confirm the issue.</li>\n</ul>\n\n<h2>Step 2: Update or Remove Invalid References</h2>\n<ul>\n  <li>For each affected product, update the category reference to a valid one, if possible.</li>\n  <li>If a valid category reference cannot be found, remove the invalid reference to prevent data inconsistencies.</li>\n</ul>\n\n<h2>Step 3: Validate Category References</h2>\n<ul>\n  <li>Run a database query to validate all category references for the affected products.</li>\n  <li>Confirm that each product is associated with a valid category reference.</li>\n</ul>\n\n<h2>Step 4: Test and Verify</h2>\n<ul>\n  <li>Perform thorough testing to ensure that the repairs have resolved the issue.</li>\n  <li>Verify that the affected products are now correctly associated with valid category references.</li>\n</ul>\n\n<p>By following these repair steps, you should be able to resolve the invalid category references and maintain the integrity of your database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,411,1213,'2025-12-09 09:08:17','2025-12-09 09:08:19'),(2,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h2>Database Repair Recommendations: Invalid Category References</h2>\n\n<p>Upon analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h3>Step 1: Identify Affected Products</h3>\n<ul>\n  <li>Run a database query to retrieve the list of 6 products with invalid category references.</li>\n  <li>Verify the product IDs and corresponding invalid category IDs.</li>\n</ul>\n\n<h3>Step 2: Update or Remove Invalid References</h3>\n<ul>\n  <li>Update the category IDs for the affected products to valid references, if possible.</li>\n  <li>If the category IDs are no longer valid, remove the references and set the category ID to a default or null value.</li>\n</ul>\n\n<h3>Step 3: Validate Category IDs</h3>\n<ul>\n  <li>Run a database query to validate that all category IDs referenced by products exist in the categories table.</li>\n  <li>Use a JOIN or subquery to identify and remove any remaining invalid category references.</li>\n</ul>\n\n<h3>Step 4: Implement Preventative Measures</h3>\n<ul>\n  <li>Review and update the database schema to include foreign key constraints, if not already implemented.</li>\n  <li>Establish data validation rules to prevent future insertion of invalid category references.</li>\n</ul>\n\n<p>By following these repair steps, you can resolve the issue of invalid category references and maintain a healthy and consistent database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,425,1663,'2025-12-09 09:08:31','2025-12-09 09:08:32'),(3,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>After analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, we recommend the following repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Run a query to retrieve the IDs and names of the 6 products with invalid category references.</li>\n  <li>Use the following SQL query: <code>SELECT product_id, product_name FROM products WHERE category_id NOT IN (SELECT category_id FROM categories);</code></li>\n</ul>\n\n<h2>Step 2: Update or Remove Invalid References</h2>\n<ul>\n  <li>For each affected product, update the category_id to a valid reference or remove the invalid reference if it is no longer applicable.</li>\n  <li>Use the following SQL query to update the category_id: <code>UPDATE products SET category_id = [new_category_id] WHERE product_id = [product_id];</code></li>\n  <li>Alternatively, use the following SQL query to remove the invalid reference: <code>UPDATE products SET category_id = NULL WHERE product_id = [product_id];</code></li>\n</ul>\n\n<h2>Step 3: Validate Category References</h2>\n<ul>\n  <li>Run a query to validate that all product category references are valid and exist in the categories table.</li>\n  <li>Use the following SQL query: <code>SELECT * FROM products WHERE category_id NOT IN (SELECT category_id FROM categories);</code></li>\n  <li>Verify that the query returns no results, indicating that all category references are valid.</li>\n</ul>\n\n<h2>Conclusion</h2>\n<p>By following these repair steps, you can resolve the issue of invalid category references and maintain the integrity of your database. Regularly validating data references can help prevent similar issues in the future.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,505,1592,'2025-12-09 09:30:00','2025-12-09 09:30:01'),(4,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h2>Database Repair Recommendations: Invalid Category References</h2>\n\n<p>We\'ve identified 6 products with invalid category references in the database. To resolve this issue and maintain data integrity, follow these concise repair steps:</p>\n\n<ol>\n  <li><strong>Identify Affected Products</strong>: Retrieve a list of the 6 products with invalid category references. You can use a SQL query like:\n    <pre>SELECT * FROM products WHERE category_id NOT IN (SELECT id FROM categories);</pre>\n  </li>\n  <li><strong>Verify Category Existence</strong>: Check if the referenced categories exist in the <code>categories</code> table. If a category is missing, create a new entry or update the product to reference a valid category.</li>\n  <li><strong>Update or Remove Invalid References</strong>: For each affected product, either:\n    <ul>\n      <li>Update the <code>category_id</code> to a valid category reference using a SQL query like:\n        <pre>UPDATE products SET category_id = [new_category_id] WHERE id = [product_id];</pre>\n      </li>\n      <li>Remove the invalid category reference by setting <code>category_id</code> to <code>NULL</code> or a default value:\n        <pre>UPDATE products SET category_id = NULL WHERE id = [product_id];</pre>\n      </li>\n    </ul>\n  </li>\n  <li><strong>Validate Data Integrity</strong>: Run a SQL query to verify that all products have valid category references:\n    <pre>SELECT * FROM products WHERE category_id NOT IN (SELECT id FROM categories);</pre>\n    If this query returns no results, the issue is resolved.\n  </li>\n</ol>\n\n<p>By following these repair steps, you\'ll ensure data consistency and prevent potential errors in your application.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,485,1564,'2025-12-09 09:30:29','2025-12-09 09:30:30'),(5,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>After analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Run a query to retrieve the list of 6 products with invalid category references.</li>\n  <li>Record the product IDs and corresponding invalid category IDs for further analysis.</li>\n</ul>\n\n<h2>Step 2: Validate Category References</h2>\n<ul>\n  <li>Verify the existence of each invalid category ID in the categories table.</li>\n  <li>Check for any typos, duplicates, or deleted categories that may be causing the invalid references.</li>\n</ul>\n\n<h2>Step 3: Update or Remove Invalid References</h2>\n<ul>\n  <li>Update the product records with valid category IDs, if available.</li>\n  <li>Remove any invalid category references that are not resolvable.</li>\n  <li>Consider adding a default or unknown category for products without valid references.</li>\n</ul>\n\n<h2>Step 4: Implement Data Validation</h2>\n<ul>\n  <li>Modify the database schema to include foreign key constraints between the products and categories tables.</li>\n  <li>Implement data validation rules to prevent future insertion of invalid category references.</li>\n</ul>\n\n<h2>Step 5: Verify and Test</h2>\n<ul>\n  <li>Run a query to verify that all products have valid category references.</li>\n  <li>Test the database by inserting new products with valid and invalid category references to ensure the data validation rules are working correctly.</li>\n</ul>\n\n<p>By following these repair steps, you can resolve the invalid category references and maintain a clean and accurate database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,491,1368,'2025-12-09 09:42:39','2025-12-09 09:42:41'),(6,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>We\'ve identified 6 products with invalid category references in the database. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Run a query to retrieve the 6 products with invalid category references.</li>\n  <li>Record the product IDs and corresponding invalid category IDs for further analysis.</li>\n</ul>\n\n<h2>Step 2: Update or Remove Invalid References</h2>\n<ul>\n  <li>For each affected product, update the category ID to a valid reference or remove the invalid reference if it\'s no longer applicable.</li>\n  <li>Use the following SQL command to update the category ID: <code>UPDATE products SET category_id = [new_valid_category_id] WHERE product_id = [affected_product_id];</code></li>\n  <li>Alternatively, use the following SQL command to remove the invalid reference: <code>UPDATE products SET category_id = NULL WHERE product_id = [affected_product_id];</code></li>\n</ul>\n\n<h2>Step 3: Verify Data Integrity</h2>\n<ul>\n  <li>Run a query to verify that the invalid category references have been resolved.</li>\n  <li>Check for any other data inconsistencies or errors that may have arisen during the repair process.</li>\n</ul>\n\n<h2>Preventative Measures</h2>\n<ul>\n  <li>Implement data validation rules to prevent invalid category references from being inserted in the future.</li>\n  <li>Regularly run data integrity checks to identify and address any potential issues before they become critical.</li>\n</ul>\n\n<p>By following these repair steps, you\'ll be able to resolve the invalid category references and maintain the integrity of your database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,484,1394,'2025-12-09 09:43:50','2025-12-09 09:43:52'),(7,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h2>Database Repair Recommendations</h2>\n\n<p>To address the issue of invalid category references for 6 products, follow these concise and actionable repair steps:</p>\n\n<h3>Step 1: Identify Affected Products</h3>\n<ul>\n  <li>Run a query to retrieve the 6 products with invalid category references, using the following SQL command: <code>SELECT * FROM products WHERE category_id NOT IN (SELECT id FROM categories)</code></li>\n  <li>Record the product IDs and their corresponding invalid category IDs for further analysis</li>\n</ul>\n\n<h3>Step 2: Validate Category References</h3>\n<ul>\n  <li>Verify the existence of the referenced categories in the <code>categories</code> table</li>\n  <li>Check for any typographical errors or inconsistencies in the category IDs</li>\n</ul>\n\n<h3>Step 3: Update or Remove Invalid References</h3>\n<ul>\n  <li>For each affected product, update the <code>category_id</code> to a valid category ID using the following SQL command: <code>UPDATE products SET category_id = [new_valid_category_id] WHERE id = [product_id]</code></li>\n  <li>If a valid category ID cannot be determined, consider removing the product or setting its <code>category_id</code> to a default or \"unknown\" category</li>\n</ul>\n\n<h3>Step 4: Verify Repaired Data</h3>\n<ul>\n  <li>Run a query to re-check the products for invalid category references, using the same SQL command from Step 1</li>\n  <li>Verify that all 6 products have been successfully updated with valid category references</li>\n</ul>\n\n<p>By following these repair steps, you should be able to resolve the issue of invalid category references for the 6 affected products and maintain data consistency in your database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,491,1632,'2025-12-09 09:44:44','2025-12-09 09:44:46'),(8,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 1 product(s) with invalid category references\n\nProvide concise, actionable repair steps.',NULL,NULL,'llama-3.3-70b-versatile',1,'failed','Client error: `POST https://api.groq.com/openai/v1/chat/completions` resulted in a `401 Unauthorized` response:\n{\"error\":{\"message\":\"Invalid API Key\",\"type\":\"invalid_request_error\",\"code\":\"invalid_api_key\"}}\n\n',NULL,NULL,'2025-12-12 01:28:29','2025-12-12 01:28:29'),(9,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 21 product(s) with invalid category references\n\nProvide concise, actionable repair steps.',NULL,NULL,'llama-3.3-70b-versatile',1,'failed','Client error: `POST https://api.groq.com/openai/v1/chat/completions` resulted in a `401 Unauthorized` response:\n{\"error\":{\"message\":\"Invalid API Key\",\"type\":\"invalid_request_error\",\"code\":\"invalid_api_key\"}}\n\n',NULL,NULL,'2025-12-23 13:38:16','2025-12-23 13:38:16');
/*!40000 ALTER TABLE `ai_generations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_settings`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analytics_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_data` json DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `referer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_keys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `api_key_id` bigint unsigned DEFAULT NULL,
  `endpoint` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backup_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` enum('hourly','daily','weekly','monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `includes` json NOT NULL,
  `storage_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint unsigned NOT NULL,
  `status` enum('creating','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'creating',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('active_template','O:19:\"App\\Models\\Template\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:9:\"templates\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:22:{s:2:\"id\";i:6;s:4:\"name\";s:13:\"Codecraft Pro\";s:4:\"slug\";s:13:\"codecraft-pro\";s:11:\"description\";s:21:\"dark mode code blocks\";s:9:\"thumbnail\";N;s:7:\"version\";s:5:\"2.0.0\";s:6:\"author\";s:7:\"Unknown\";s:10:\"author_url\";s:0:\"\";s:3:\"css\";N;s:2:\"js\";N;s:6:\"config\";s:20:\"{\"responsive\": true}\";s:7:\"layouts\";s:28:\"[\"home\", \"about\", \"contact\"]\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:0;s:12:\"ai_generated\";i:0;s:16:\"ai_generation_id\";N;s:12:\"color_scheme\";s:46:\"{\"primary\": \"#00d9ff\", \"secondary\": \"#8b5cf6\"}\";s:10:\"typography\";s:55:\"{\"body_font\": \"Inter\", \"heading_font\": \"Space Grotesk\"}\";s:13:\"custom_fields\";N;s:10:\"created_at\";s:19:\"2025-12-07 08:09:55\";s:10:\"updated_at\";s:19:\"2025-12-07 08:10:41\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:22:{s:2:\"id\";i:6;s:4:\"name\";s:13:\"Codecraft Pro\";s:4:\"slug\";s:13:\"codecraft-pro\";s:11:\"description\";s:21:\"dark mode code blocks\";s:9:\"thumbnail\";N;s:7:\"version\";s:5:\"2.0.0\";s:6:\"author\";s:7:\"Unknown\";s:10:\"author_url\";s:0:\"\";s:3:\"css\";N;s:2:\"js\";N;s:6:\"config\";s:20:\"{\"responsive\": true}\";s:7:\"layouts\";s:28:\"[\"home\", \"about\", \"contact\"]\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:0;s:12:\"ai_generated\";i:0;s:16:\"ai_generation_id\";N;s:12:\"color_scheme\";s:46:\"{\"primary\": \"#00d9ff\", \"secondary\": \"#8b5cf6\"}\";s:10:\"typography\";s:55:\"{\"body_font\": \"Inter\", \"heading_font\": \"Space Grotesk\"}\";s:13:\"custom_fields\";N;s:10:\"created_at\";s:19:\"2025-12-07 08:09:55\";s:10:\"updated_at\";s:19:\"2025-12-07 08:10:41\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:9:{s:9:\"is_active\";s:7:\"boolean\";s:10:\"is_default\";s:7:\"boolean\";s:12:\"ai_generated\";s:7:\"boolean\";s:6:\"config\";s:5:\"array\";s:7:\"layouts\";s:5:\"array\";s:12:\"color_scheme\";s:5:\"array\";s:10:\"typography\";s:5:\"array\";s:13:\"custom_fields\";s:5:\"array\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:18:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:9:\"thumbnail\";i:4;s:7:\"version\";i:5;s:6:\"author\";i:6;s:10:\"author_url\";i:7;s:3:\"css\";i:8;s:2:\"js\";i:9;s:6:\"config\";i:10;s:7:\"layouts\";i:11;s:9:\"is_active\";i:12;s:10:\"is_default\";i:13;s:12:\"ai_generated\";i:14;s:16:\"ai_generation_id\";i:15;s:12:\"color_scheme\";i:16;s:10:\"typography\";i:17;s:13:\"custom_fields\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}',1766501373),('setting.admin_email','s:17:\"admin@example.com\";',1766495154),('setting.backup_enabled','b:1;',1766495154),('setting.backup_schedule','s:5:\"daily\";',1766495154),('setting.default_language','s:2:\"en\";',1766495154),('setting.favicon','N;',1766495215),('setting.logo','N;',1766495215),('setting.maintenance_mode','b:0;',1766495154),('setting.site_description','s:0:\"\";',1766495154),('setting.site_name','s:14:\"Carpathian CMS\";',1766495154),('setting.site_protocol','s:5:\"https\";',1766495154),('setting.site_url','s:21:\"https://carphatian.ro\";',1766495154),('spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:54:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"view orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"view own orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:21:\"create support ticket\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:17:\"view support chat\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"view posts\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"create posts\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"edit posts\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"delete posts\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"publish posts\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"view pages\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"create pages\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"edit pages\";s:1:\"c\";s:3:\"web\";}i:12;a:3:{s:1:\"a\";i:13;s:1:\"b\";s:12:\"delete pages\";s:1:\"c\";s:3:\"web\";}i:13;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:13:\"publish pages\";s:1:\"c\";s:3:\"web\";}i:14;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:3:\"web\";}i:15;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:3:\"web\";}i:16;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:3:\"web\";}i:17;a:3:{s:1:\"a\";i:18;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:3:\"web\";}i:18;a:3:{s:1:\"a\";i:19;s:1:\"b\";s:9:\"view tags\";s:1:\"c\";s:3:\"web\";}i:19;a:3:{s:1:\"a\";i:20;s:1:\"b\";s:11:\"create tags\";s:1:\"c\";s:3:\"web\";}i:20;a:3:{s:1:\"a\";i:21;s:1:\"b\";s:9:\"edit tags\";s:1:\"c\";s:3:\"web\";}i:21;a:3:{s:1:\"a\";i:22;s:1:\"b\";s:11:\"delete tags\";s:1:\"c\";s:3:\"web\";}i:22;a:3:{s:1:\"a\";i:23;s:1:\"b\";s:10:\"view media\";s:1:\"c\";s:3:\"web\";}i:23;a:3:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"upload media\";s:1:\"c\";s:3:\"web\";}i:24;a:3:{s:1:\"a\";i:25;s:1:\"b\";s:10:\"edit media\";s:1:\"c\";s:3:\"web\";}i:25;a:3:{s:1:\"a\";i:26;s:1:\"b\";s:12:\"delete media\";s:1:\"c\";s:3:\"web\";}i:26;a:3:{s:1:\"a\";i:27;s:1:\"b\";s:14:\"view templates\";s:1:\"c\";s:3:\"web\";}i:27;a:3:{s:1:\"a\";i:28;s:1:\"b\";s:16:\"create templates\";s:1:\"c\";s:3:\"web\";}i:28;a:3:{s:1:\"a\";i:29;s:1:\"b\";s:14:\"edit templates\";s:1:\"c\";s:3:\"web\";}i:29;a:3:{s:1:\"a\";i:30;s:1:\"b\";s:16:\"delete templates\";s:1:\"c\";s:3:\"web\";}i:30;a:3:{s:1:\"a\";i:31;s:1:\"b\";s:18:\"activate templates\";s:1:\"c\";s:3:\"web\";}i:31;a:3:{s:1:\"a\";i:32;s:1:\"b\";s:12:\"view plugins\";s:1:\"c\";s:3:\"web\";}i:32;a:3:{s:1:\"a\";i:33;s:1:\"b\";s:14:\"create plugins\";s:1:\"c\";s:3:\"web\";}i:33;a:3:{s:1:\"a\";i:34;s:1:\"b\";s:12:\"edit plugins\";s:1:\"c\";s:3:\"web\";}i:34;a:3:{s:1:\"a\";i:35;s:1:\"b\";s:14:\"delete plugins\";s:1:\"c\";s:3:\"web\";}i:35;a:3:{s:1:\"a\";i:36;s:1:\"b\";s:16:\"activate plugins\";s:1:\"c\";s:3:\"web\";}i:36;a:3:{s:1:\"a\";i:37;s:1:\"b\";s:17:\"use ai generation\";s:1:\"c\";s:3:\"web\";}i:37;a:3:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"manage ai settings\";s:1:\"c\";s:3:\"web\";}i:38;a:3:{s:1:\"a\";i:39;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";}i:39;a:3:{s:1:\"a\";i:40;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";}i:40;a:3:{s:1:\"a\";i:41;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";}i:41;a:3:{s:1:\"a\";i:42;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";}i:42;a:3:{s:1:\"a\";i:43;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";}i:43;a:3:{s:1:\"a\";i:44;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";}i:44;a:3:{s:1:\"a\";i:45;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";}i:45;a:3:{s:1:\"a\";i:46;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";}i:46;a:3:{s:1:\"a\";i:47;s:1:\"b\";s:12:\"assign roles\";s:1:\"c\";s:3:\"web\";}i:47;a:3:{s:1:\"a\";i:48;s:1:\"b\";s:16:\"view permissions\";s:1:\"c\";s:3:\"web\";}i:48;a:3:{s:1:\"a\";i:49;s:1:\"b\";s:13:\"view settings\";s:1:\"c\";s:3:\"web\";}i:49;a:3:{s:1:\"a\";i:50;s:1:\"b\";s:13:\"edit settings\";s:1:\"c\";s:3:\"web\";}i:50;a:3:{s:1:\"a\";i:51;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";}i:51;a:3:{s:1:\"a\";i:52;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";}i:52;a:3:{s:1:\"a\";i:53;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";}i:53;a:3:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:6:\"Client\";s:1:\"c\";s:3:\"web\";}}}',1766575121);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `path_pattern` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint unsigned DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `icon`, `color`, `order`, `is_active`, `meta`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'{\"ro\":\"Știri\"}','stiri','{\"ro\":\"Ultimele știri și actualizări\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56',NULL),(2,'{\"ro\":\"Tutoriale CMS\",\"en\":\"CMS Tutorials\"}','tutoriale-cms','{\"ro\":\"Tutoriale CMS\",\"en\":\"CMS Tutorials\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-08 07:17:43','2025-12-08 07:17:43',NULL),(3,'{\"ro\":\"Inteligență Artificială\",\"en\":\"Artificial Intelligence\"}','inteligenta-artificiala','{\"ro\":\"Inteligență Artificială\",\"en\":\"Artificial Intelligence\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-08 07:17:43','2025-12-08 07:17:43',NULL),(4,'{\"ro\":\"Tehnologie & Web\",\"en\":\"Technology & Web\"}','tehnologie-web','{\"ro\":\"Tehnologie & Web\",\"en\":\"Technology & Web\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-08 07:17:43','2025-12-08 07:17:43',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_conversations`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'support',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_admin_id` bigint unsigned DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_conversations_user_id_foreign` (`user_id`),
  KEY `chat_conversations_assigned_admin_id_foreign` (`assigned_admin_id`),
  CONSTRAINT `chat_conversations_assigned_admin_id_foreign` FOREIGN KEY (`assigned_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `chat_conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_conversations`
--

LOCK TABLES `chat_conversations` WRITE;
/*!40000 ALTER TABLE `chat_conversations` DISABLE KEYS */;
INSERT INTO `chat_conversations` (`id`, `user_id`, `guest_name`, `guest_email`, `guest_phone`, `type`, `status`, `subject`, `assigned_admin_id`, `last_message_at`, `created_at`, `updated_at`) VALUES (1,1,NULL,NULL,NULL,'support','closed','Hi, I need to update my phone number with a new one',1,'2025-12-22 16:55:20','2025-12-21 12:54:58','2025-12-23 11:19:12'),(2,2,NULL,NULL,NULL,'support','closed','In atenția unui General Manager',NULL,'2025-12-21 14:03:33','2025-12-21 14:03:33','2025-12-23 11:19:23'),(3,NULL,'Mircea silviu Rusu','ukmircearusu@gmail.com','07553133223','presale','closed','Product Inquiry',NULL,'2025-12-21 14:04:05','2025-12-21 14:03:54','2025-12-23 11:19:19');
/*!40000 ALTER TABLE `chat_conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_conversation_id_foreign` (`conversation_id`),
  KEY `chat_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `chat_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` (`id`, `conversation_id`, `user_id`, `is_admin`, `message`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES (1,1,1,0,'fsfvvv',1,'2025-12-21 12:55:21','2025-12-21 12:54:58','2025-12-21 12:55:21'),(2,1,1,1,'ok',1,'2025-12-21 12:59:14','2025-12-21 12:55:30','2025-12-21 12:59:14'),(3,1,1,1,'ok',1,'2025-12-21 12:59:14','2025-12-21 12:55:31','2025-12-21 12:59:14'),(4,1,1,1,'ok',1,'2025-12-21 12:59:14','2025-12-21 12:55:33','2025-12-21 12:59:14'),(5,1,1,1,'ok assca',1,'2025-12-21 12:59:14','2025-12-21 12:56:22','2025-12-21 12:59:14'),(6,1,1,1,'ok assca',1,'2025-12-21 12:59:14','2025-12-21 12:58:30','2025-12-21 12:59:14'),(7,1,1,1,'efef',1,'2025-12-21 12:59:14','2025-12-21 12:58:36','2025-12-21 12:59:14'),(8,1,1,1,'w af',1,'2025-12-21 12:59:14','2025-12-21 12:58:44','2025-12-21 12:59:14'),(9,1,1,0,'hi',1,'2025-12-21 12:59:32','2025-12-21 12:59:21','2025-12-21 12:59:32'),(10,1,1,0,'hi',1,'2025-12-21 13:09:49','2025-12-21 13:09:29','2025-12-21 13:09:49'),(11,2,2,0,'ma cac prune am mancat gem',1,'2025-12-23 11:19:21','2025-12-21 14:03:33','2025-12-23 11:19:21'),(12,3,NULL,0,'h   gig gi i',1,'2025-12-23 11:19:17','2025-12-21 14:03:54','2025-12-23 11:19:17'),(13,3,NULL,0,'bai vreau un site!!!',1,'2025-12-23 11:19:17','2025-12-21 14:04:05','2025-12-23 11:19:17'),(14,1,1,0,'ce faci',1,'2025-12-23 11:19:09','2025-12-21 21:18:35','2025-12-23 11:19:09'),(15,1,1,0,'ok',1,'2025-12-23 11:19:09','2025-12-22 16:55:14','2025-12-23 11:19:09'),(16,1,1,0,'ok',1,'2025-12-23 11:19:09','2025-12-22 16:55:17','2025-12-23 11:19:09'),(17,1,1,0,'ok',1,'2025-12-23 11:19:09','2025-12-22 16:55:20','2025-12-23 11:19:09');
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compliance_privacy_features`
--

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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','read','replied','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Web Agency',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_embed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `facebook_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_hours` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `receive_emails` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_settings`
--

LOCK TABLES `contact_settings` WRITE;
/*!40000 ALTER TABLE `contact_settings` DISABLE KEYS */;
INSERT INTO `contact_settings` (`id`, `company_name`, `email`, `phone`, `address`, `city`, `state`, `zip_code`, `country`, `map_embed`, `facebook_url`, `twitter_url`, `linkedin_url`, `instagram_url`, `working_hours`, `receive_emails`, `created_at`, `updated_at`) VALUES (1,'Carphatian by Aziz Ride Sharing SRL','info@carphatian.ro','+40774077860','Str Ploiesti 47-49','Cluj-Napoca','Cluj',NULL,'Romania',NULL,NULL,NULL,NULL,NULL,NULL,1,'2025-12-07 05:03:34','2025-12-09 11:04:03'),(2,'Web Agency','info@carphatian.ro','+40774077860','Str Ploiesti 47-49','Cluj-Napoca','Cluj',NULL,'Romania',NULL,NULL,NULL,NULL,NULL,NULL,1,'2025-12-09 11:09:58','2025-12-09 11:09:58');
/*!40000 ALTER TABLE `contact_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cookie_consents`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cookie_consents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` enum('percentage','fixed_amount','free_shipping') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_field_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `field_id` bigint unsigned NOT NULL,
  `fieldable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fieldable_id` bigint unsigned NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_type_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('text','textarea','wysiwyg','number','email','url','date','select','checkbox','radio','file','image','gallery','repeater') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_post_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supports` json NOT NULL,
  `taxonomies` json DEFAULT NULL,
  `is_hierarchical` tinyint(1) NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `menu_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_taxonomies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_type_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_deletion_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','processing','completed','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_export_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('pending','processing','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `database_query_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `query` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `execution_time` decimal(10,4) NOT NULL,
  `bindings` json DEFAULT NULL,
  `connection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mysql',
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
-- Table structure for table `email_logs`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `to_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_logs`
--

LOCK TABLES `email_logs` WRITE;
/*!40000 ALTER TABLE `email_logs` DISABLE KEYS */;
INSERT INTO `email_logs` (`id`, `to_email`, `to_name`, `subject`, `template_slug`, `status`, `error_message`, `created_at`, `updated_at`) VALUES (1,'msrusu87@gmail.com',NULL,'Test Email from Carphatian',NULL,'sent',NULL,'2025-12-22 10:37:09','2025-12-22 10:37:09');
/*!40000 ALTER TABLE `email_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_settings`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mail_driver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'smtp',
  `mail_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_port` int NOT NULL DEFAULT '587',
  `mail_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_encryption` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tls',
  `mail_from_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notification_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_preferences` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_settings`
--

LOCK TABLES `email_settings` WRITE;
/*!40000 ALTER TABLE `email_settings` DISABLE KEYS */;
INSERT INTO `email_settings` (`id`, `mail_driver`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from_address`, `mail_from_name`, `admin_notification_email`, `notification_preferences`, `created_at`, `updated_at`) VALUES (1,'smtp','mail.carphatian.ro',587,'contact@carphatian.ro','Maria1940!!!','tls','contact@carphatian.ro','Carphatian Software Development','msrusu87@gmail.com','{\"new_user\": true, \"new_order\": true, \"contact_form\": true, \"support_chat\": true}','2025-12-21 13:16:30','2025-12-22 10:37:08');
/*!40000 ALTER TABLE `email_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_html` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `variables` json DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_templates_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
INSERT INTO `email_templates` (`id`, `name`, `slug`, `subject`, `body_html`, `body_text`, `variables`, `attachments`, `is_active`, `created_at`, `updated_at`) VALUES (1,'Email Verification','email-verification','Verify Your Email Address - {{app_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Hello {{user_name}},</h2>\n            <p>Thank you for registering! Please verify your email address by clicking the button below:</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{verification_url}}\" class=\"btn\">Verify Email Address</a>\n            </p>\n            <p>If you did not create an account, no further action is required.</p>\n            <p>If you are having trouble clicking the button, copy and paste this URL into your browser:</p>\n            <p style=\"word-break: break-all; color: #6b46c1;\">{{verification_url}}</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','Please verify your email by clicking: {{verification_url}}','[\"user_name\", \"verification_url\", \"app_name\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(2,'Welcome Email','welcome','Welcome to {{app_name}}!','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Welcome, {{user_name}}!</h2>\n            <p>Thank you for joining {{app_name}}. We are excited to have you as part of our community.</p>\n            <p>You can now:</p>\n            <ul>\n                <li>Browse our products and services</li>\n                <li>Make purchases and track orders</li>\n                <li>Get customer support</li>\n            </ul>\n            <p style=\"text-align: center;\">\n                <a href=\"{{login_url}}\" class=\"btn\">Go to Your Dashboard</a>\n            </p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','Welcome {{user_name}}! Thank you for registering.','[\"user_name\", \"app_name\", \"login_url\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(3,'Password Reset','password-reset','Reset Your Password - {{app_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Hello {{user_name}},</h2>\n            <p>You are receiving this email because we received a password reset request for your account.</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{reset_url}}\" class=\"btn\">Reset Password</a>\n            </p>\n            <p>This password reset link will expire in {{expire_minutes}} minutes.</p>\n            <p>If you did not request a password reset, no further action is required.</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','Reset your password: {{reset_url}}','[\"user_name\", \"reset_url\", \"app_name\", \"expire_minutes\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(4,'Order Confirmation','order-confirmation','Order Confirmed #{{order_number}} - {{app_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Thank you for your order, {{user_name}}!</h2>\n            <div class=\"highlight\">\n                <strong>Order Number:</strong> #{{order_number}}<br>\n                <strong>Total:</strong> {{order_total}}\n            </div>\n            <h3>Order Details:</h3>\n            {{order_items}}\n            <p>We will notify you when your order ships.</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','Your order #{{order_number}} has been confirmed.','[\"user_name\", \"order_number\", \"order_total\", \"order_items\", \"app_name\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(5,'Order Shipped','order-shipped','Your Order Has Shipped #{{order_number}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Your order is on its way!</h2>\n            <p>Hello {{user_name}},</p>\n            <p>Great news! Your order #{{order_number}} has been shipped.</p>\n            <div class=\"highlight\">\n                <strong>Carrier:</strong> {{carrier}}<br>\n                <strong>Tracking Number:</strong> {{tracking_number}}\n            </div>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','Your order #{{order_number}} has been shipped.','[\"user_name\", \"order_number\", \"tracking_number\", \"carrier\", \"app_name\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(6,'Contact Form Notification','contact-form','New Contact Message from {{sender_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>New Contact Form Message</h2>\n            <div class=\"highlight\">\n                <strong>From:</strong> {{sender_name}}<br>\n                <strong>Email:</strong> {{sender_email}}<br>\n                <strong>Phone:</strong> {{sender_phone}}<br>\n                <strong>Subject:</strong> {{subject}}\n            </div>\n            <h3>Message:</h3>\n            <p>{{message}}</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','New message from {{sender_name}} ({{sender_email}}): {{message}}','[\"sender_name\", \"sender_email\", \"sender_phone\", \"subject\", \"message\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(7,'Support Chat Notification','support-chat','New Support Chat from {{participant_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>New Support Chat Started</h2>\n            <div class=\"highlight\">\n                <strong>From:</strong> {{participant_name}}<br>\n                <strong>Email:</strong> {{participant_email}}\n            </div>\n            <h3>Initial Message:</h3>\n            <p>{{message}}</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{chat_url}}\" class=\"btn\">View Chat</a>\n            </p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','New support chat started by {{participant_name}}.','[\"participant_name\", \"participant_email\", \"chat_url\", \"message\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(8,'New Chat Message','chat-message','New Message in Support Chat','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>New Support Message</h2>\n            <div class=\"highlight\">\n                <strong>From:</strong> {{sender_name}}\n            </div>\n            <h3>Message:</h3>\n            <p>{{message}}</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{chat_url}}\" class=\"btn\">Reply to Message</a>\n            </p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','You have a new message from {{sender_name}}.','[\"sender_name\", \"message\", \"chat_url\"]',NULL,1,'2025-12-21 13:12:24','2025-12-21 13:12:24'),(9,'Pre-Order Request','pre-order-request','Cerere nouă de pre-comandă: {product_name}','<h2>Cerere nouă de pre-comandă</h2>\n<h3>Detalii Produs:</h3>\n<ul>\n<li><strong>Produs:</strong> {product_name}</li>\n<li><strong>ID Produs:</strong> #{product_id}</li>\n<li><strong>Preț:</strong> {product_price} RON</li>\n</ul>\n<h3>Detalii Client:</h3>\n<ul>\n<li><strong>Nume:</strong> {customer_name}</li>\n<li><strong>Email:</strong> {customer_email}</li>\n<li><strong>Telefon:</strong> {customer_phone}</li>\n</ul>\n<h3>Mesaj:</h3>\n<p>{customer_message}</p>\n<hr>\n<p><small>Gestionați această cerere din panoul de administrare.</small></p>','Cerere nouă de pre-comandă\n\nProdus: {product_name} (#{product_id}) - {product_price} RON\n\nClient: {customer_name}\nEmail: {customer_email}\nTelefon: {customer_phone}\n\nMesaj: {customer_message}','[\"product_name\", \"product_id\", \"product_price\", \"customer_name\", \"customer_email\", \"customer_phone\", \"customer_message\"]',NULL,1,'2025-12-21 13:33:12','2025-12-21 13:33:12');
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feature_flags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `data` json NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fields` json NOT NULL,
  `settings` json DEFAULT NULL,
  `success_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_submissions` tinyint(1) NOT NULL DEFAULT '1',
  `send_email` tinyint(1) NOT NULL DEFAULT '0',
  `email_recipients` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_earnings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','available','withdrawn') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned DEFAULT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gig_id` bigint unsigned NOT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `package_type` enum('basic','standard','premium') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extras` json DEFAULT NULL,
  `requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `seller_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','in_progress','in_review','completed','cancelled','disputed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `freelancer_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tagline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` json DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `certifications` json DEFAULT NULL,
  `availability` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gig_extras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gig_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gig_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gig_id` bigint unsigned NOT NULL,
  `type` enum('basic','standard','premium') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gig_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gig_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` int unsigned NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ratings_breakdown` json DEFAULT NULL,
  `seller_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gigs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` json DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `delivery_days` int NOT NULL DEFAULT '7',
  `revisions` int NOT NULL DEFAULT '1',
  `status` enum('draft','active','paused','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `global_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'My CMS Website',
  `site_domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_favicon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `date_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y-m-d',
  `time_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H:i:s',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `maintenance_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
INSERT INTO `global_settings` (`id`, `site_name`, `site_domain`, `site_logo`, `site_favicon`, `admin_email`, `site_description`, `timezone`, `date_format`, `time_format`, `maintenance_mode`, `maintenance_message`, `social_links`, `custom_scripts`, `created_at`, `updated_at`) VALUES (1,'Web Agency CMS','cms.carphatian.ro',NULL,NULL,'admin@example.com','Professional web development and digital solutions','UTC','Y-m-d','H:i:s',0,NULL,NULL,NULL,'2025-12-07 04:49:18','2025-12-07 04:49:18');
/*!40000 ALTER TABLE `global_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_jobs`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `installed_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('plugin','template','theme','module') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `install_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `settings` json DEFAULT NULL,
  `requirements` json DEFAULT NULL,
  `update_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` enum('purchase','sale','return','adjustment','damage','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_before` int NOT NULL,
  `quantity_change` int NOT NULL,
  `quantity_after` int NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `invoice_items`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '19.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_company_reg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_vat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `payment_status` enum('unpaid','partial','paid','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '19.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `paid_at` date DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `footer_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_user_id_foreign` (`user_id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` enum('ltr','rtl') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` (`id`, `name`, `code`, `locale`, `direction`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES (1,'Română','ro','ro_RO','ltr',1,1,'2025-12-07 05:30:48','2025-12-07 05:38:58'),(2,'English','en','en_US','ltr',0,1,'2025-12-07 05:30:48','2025-12-12 03:40:21'),(3,'Español','es','es_ES','ltr',0,1,'2025-12-12 03:39:59','2025-12-12 03:39:59'),(4,'Italiano','it','it_IT','ltr',0,1,'2025-12-12 03:40:00','2025-12-12 03:40:00'),(5,'Deutsch','de','de_DE','ltr',0,1,'2025-12-12 03:40:01','2025-12-12 03:40:01'),(6,'Français','fr','fr_FR','ltr',0,1,'2025-12-12 03:40:03','2025-12-12 03:40:03');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_schedules`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_at` timestamp NOT NULL,
  `end_at` timestamp NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint unsigned NOT NULL,
  `width` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `alt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_cdn_caches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `media_id` bigint unsigned NOT NULL,
  `cdn_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cdn_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_conversions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `media_id` bigint unsigned NOT NULL,
  `conversion_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_folders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `reference_id` bigint unsigned DEFAULT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `css_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  KEY `menu_items_parent_id_foreign` (`parent_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `type`, `reference_id`, `target`, `icon`, `css_class`, `order`, `is_active`, `created_at`, `updated_at`) VALUES (21,5,NULL,'Freelancer','/freelancer','custom',NULL,'_self','briefcase',NULL,0,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(22,5,NULL,'Găsește Freelanceri','/freelancer/profiles','custom',NULL,'_self','users',NULL,1,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(23,5,NULL,'Servicii','/freelancer/gigs','custom',NULL,'_self','star',NULL,2,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(24,5,NULL,'Comenzi','/freelancer/orders','custom',NULL,'_self','shopping-cart',NULL,3,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(25,5,NULL,'Devino Freelancer','/freelancer/become','custom',NULL,'_self','rocket',NULL,4,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(26,6,NULL,'Shop','/shop','custom',NULL,'_self','shopping-bag',NULL,0,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(27,6,NULL,'Products','/shop/products','custom',NULL,'_self','cube',NULL,1,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(28,6,NULL,'Categories','/shop/categories','custom',NULL,'_self','folder',NULL,2,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(29,6,NULL,'Cart','/cart','custom',NULL,'_self','shopping-cart',NULL,3,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(30,6,NULL,'Checkout','/checkout','custom',NULL,'_self','credit-card',NULL,4,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(31,6,NULL,'Portfolios','/portfolios','page',2,'_self',NULL,NULL,6,1,'2025-12-11 22:40:30','2025-12-11 22:40:34');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_styles`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_styles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `html_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `css_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `config` json DEFAULT NULL,
  `preview_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` (`id`, `name`, `slug`, `location`, `description`, `is_active`, `order`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Freelancer','freelancer-menu','header',NULL,1,0,'2025-12-07 04:50:25','2025-12-07 04:57:27','2025-12-07 04:57:27'),(2,'Shop','shop-menu','header',NULL,1,0,'2025-12-07 04:55:54','2025-12-07 04:58:08','2025-12-07 04:58:08'),(3,'Freelancer','freelancer-menu','header',NULL,1,0,'2025-12-07 04:57:51','2025-12-07 05:14:55','2025-12-07 05:14:55'),(4,'Shop','shop-menu','header',NULL,1,0,'2025-12-07 04:58:30','2025-12-07 05:14:58','2025-12-07 05:14:58'),(5,'Freelancer','freelancer-menu','header',NULL,0,0,'2025-12-07 05:15:02','2025-12-08 05:59:10',NULL),(6,'Magazin','shop-menu','header',NULL,1,0,'2025-12-07 05:15:25','2025-12-08 05:58:45',NULL);
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_03_102526_create_ai_generations_table',1),(5,'2025_12_03_102527_b_create_templates_table',1),(6,'2025_12_03_102527_create_tags_table',1),(7,'2025_12_03_102528_create_categories_table',1),(8,'2025_12_03_102528_create_posts_table',1),(9,'2025_12_03_102529_create_media_table',1),(10,'2025_12_03_102529_create_pages_table',1),(11,'2025_12_03_102535_create_plugins_table',1),(12,'2025_12_03_102536_create_settings_table',1),(13,'2025_12_03_102536_create_template_blocks_table',1),(14,'2025_12_03_105000_create_template_zones_system',1),(15,'2025_12_03_110035_create_ecommerce_tables',1),(16,'2025_12_03_120008_create_contact_settings_table',1),(17,'2025_12_03_121906_create_menus_table',1),(18,'2025_12_03_121907_create_menu_items_table',1),(19,'2025_12_03_124605_create_ai_settings_table',1),(20,'2025_12_03_125000_create_global_settings_table',1),(21,'2025_12_03_125100_create_shop_settings_table',1),(22,'2025_12_03_125200_create_seo_settings_table',1),(23,'2025_12_03_141920_create_widgets_table',1),(24,'2025_12_05_022806_create_installed_packages_table',1),(25,'2025_12_05_022806_create_system_settings_table',1),(26,'2025_12_05_022806_create_update_history_table',1),(27,'2025_12_05_032249_create_contact_messages_table',1),(28,'2025_12_05_035427_convert_content_to_json',1),(29,'2025_12_06_040541_create_permission_tables',1),(30,'2025_12_06_040541_create_personal_access_tokens_table',1),(31,'2025_12_06_040543_add_two_factor_columns_to_users_table',1),(32,'2025_12_06_100356_create_freelancer_marketplace_tables',2),(33,'2025_12_07_045111_create_redirects_table',3),(34,'2025_12_06_041545_create_page_builder_blocks_table',4),(35,'2025_12_06_042504_add_performance_indexes_to_tables',4),(36,'2025_12_06_085129_create_redirects_table',5),(37,'2025_12_06_100619_enhance_products_for_ecommerce',5),(38,'2025_12_06_101159_create_custom_post_types_system',5),(39,'2025_12_06_101159_create_media_library_enhancements',5),(40,'2025_12_06_101159_create_multilanguage_system',5),(41,'2025_12_06_101200_create_compliance_privacy_features',5),(42,'2025_12_06_101200_create_custom_post_types_system',5),(43,'2025_12_06_101200_create_pwa_features',5),(44,'2025_12_06_101203_create_compliance_privacy_features',5),(45,'2025_12_06_101300_create_seo_analytics_tables',5),(46,'2025_12_06_101400_create_notification_system',5),(47,'2025_12_06_101401_create_workflow_automation',5),(48,'2025_12_06_101402_create_form_builder_system',5),(49,'2025_12_06_101403_create_backup_system',5),(50,'2025_12_06_101500_create_api_system',5),(51,'2025_12_06_101601_create_advanced_search',5),(52,'2025_12_06_101700_create_final_enterprise_features',5),(53,'2025_12_06_101800_create_multitenancy_system',5),(54,'2025_12_06_101900_create_system_monitoring',5),(55,'2025_12_06_102000_create_advanced_admin_features',5),(56,'2025_12_07_040848_create_missing_tables',5),(57,'2025_12_07_041428_create_page_builder_templates_table',6),(58,'2025_12_07_044850_add_slug_to_menus_table',7),(59,'2025_12_18_155253_create_notifications_table',8),(60,'2025_12_21_120000_create_chat_system_tables',9),(61,'2025_12_18_115337_create_portfolios_table',10),(62,'2025_12_18_140027_ensure_translatable_fields_are_json',10),(63,'2025_12_18_141452_add_seo_fields_to_pages_table',10),(64,'2025_12_18_144747_convert_widgets_title_content_to_json',10),(65,'2025_12_20_084338_add_seo_fields_to_products_table',10),(66,'2025_12_20_125224_add_currency_fields_to_products_table',10),(67,'2025_12_20_224709_create_pre_sale_inquiries_table',10),(68,'2025_12_21_130905_create_email_templates_table',11),(69,'2025_12_21_132936_create_pre_sale_requests_table',12),(70,'2025_12_22_102434_add_billing_fields_to_users_table',13),(71,'2025_12_22_102435_create_invoices_table',13),(72,'2025_12_22_102436_create_invoice_items_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',1),(3,'App\\Models\\User',1),(4,'App\\Models\\User',1),(5,'App\\Models\\User',1),(4,'App\\Models\\User',2),(5,'App\\Models\\User',2),(5,'App\\Models\\User',4),(4,'App\\Models\\User',5),(5,'App\\Models\\User',5);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multilanguage_system`
--

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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('email','sms','push','database') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variables` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notification_templates_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_templates`
--

LOCK TABLES `notification_templates` WRITE;
/*!40000 ALTER TABLE `notification_templates` DISABLE KEYS */;
INSERT INTO `notification_templates` (`id`, `name`, `slug`, `type`, `subject`, `body`, `variables`, `is_active`, `created_at`, `updated_at`) VALUES (1,'Email Verification','email-verification','email','Verify Your Email Address - {{app_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Hello {{user_name}},</h2>\n            <p>Thank you for registering! Please verify your email address by clicking the button below:</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{verification_url}}\" class=\"btn\">Verify Email Address</a>\n            </p>\n            <p>If you did not create an account, no further action is required.</p>\n            <p>If you are having trouble clicking the button, copy and paste this URL into your browser:</p>\n            <p style=\"word-break: break-all; color: #6b46c1;\">{{verification_url}}</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"user_name\", \"verification_url\", \"app_name\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43'),(2,'Welcome Email','welcome','email','Welcome to {{app_name}}!','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Welcome, {{user_name}}!</h2>\n            <p>Thank you for joining {{app_name}}. We are excited to have you as part of our community.</p>\n            <p>You can now:</p>\n            <ul>\n                <li>Browse our products and services</li>\n                <li>Make purchases and track orders</li>\n                <li>Get customer support</li>\n            </ul>\n            <p style=\"text-align: center;\">\n                <a href=\"{{login_url}}\" class=\"btn\">Go to Your Dashboard</a>\n            </p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"user_name\", \"app_name\", \"login_url\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43'),(3,'Password Reset','password-reset','email','Reset Your Password - {{app_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Hello {{user_name}},</h2>\n            <p>You are receiving this email because we received a password reset request for your account.</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{reset_url}}\" class=\"btn\">Reset Password</a>\n            </p>\n            <p>This password reset link will expire in {{expire_minutes}} minutes.</p>\n            <p>If you did not request a password reset, no further action is required.</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"user_name\", \"reset_url\", \"app_name\", \"expire_minutes\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43'),(4,'Order Confirmation','order-confirmation','email','Order Confirmed #{{order_number}} - {{app_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Thank you for your order, {{user_name}}!</h2>\n            <div class=\"highlight\">\n                <strong>Order Number:</strong> #{{order_number}}<br>\n                <strong>Total:</strong> {{order_total}}\n            </div>\n            <h3>Order Details:</h3>\n            {{order_items}}\n            <p>We will notify you when your order ships.</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"user_name\", \"order_number\", \"order_total\", \"order_items\", \"app_name\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43'),(5,'Order Shipped','order-shipped','email','Your Order Has Shipped #{{order_number}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>Your order is on its way!</h2>\n            <p>Hello {{user_name}},</p>\n            <p>Great news! Your order #{{order_number}} has been shipped.</p>\n            <div class=\"highlight\">\n                <strong>Carrier:</strong> {{carrier}}<br>\n                <strong>Tracking Number:</strong> {{tracking_number}}\n            </div>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"user_name\", \"order_number\", \"tracking_number\", \"carrier\", \"app_name\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43'),(6,'Contact Form Notification','contact-form','email','New Contact Message from {{sender_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>New Contact Form Message</h2>\n            <div class=\"highlight\">\n                <strong>From:</strong> {{sender_name}}<br>\n                <strong>Email:</strong> {{sender_email}}<br>\n                <strong>Phone:</strong> {{sender_phone}}<br>\n                <strong>Subject:</strong> {{subject}}\n            </div>\n            <h3>Message:</h3>\n            <p>{{message}}</p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"sender_name\", \"sender_email\", \"sender_phone\", \"subject\", \"message\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43'),(7,'Support Chat Notification','support-chat','email','New Support Chat from {{participant_name}}','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>New Support Chat Started</h2>\n            <div class=\"highlight\">\n                <strong>From:</strong> {{participant_name}}<br>\n                <strong>Email:</strong> {{participant_email}}\n            </div>\n            <h3>Initial Message:</h3>\n            <p>{{message}}</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{chat_url}}\" class=\"btn\">View Chat</a>\n            </p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"participant_name\", \"participant_email\", \"chat_url\", \"message\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43'),(8,'New Chat Message','chat-message','email','New Message in Support Chat','<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <style>\n        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }\n        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }\n        .header h1 { color: #fff; margin: 0; font-size: 24px; }\n        .content { padding: 30px; }\n        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }\n        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }\n        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }\n    </style>\n</head>\n<body>\n    <div class=\"container\">\n        <div class=\"header\">\n            <h1>{{app_name}}</h1>\n        </div>\n        <div class=\"content\">\n            <h2>New Support Message</h2>\n            <div class=\"highlight\">\n                <strong>From:</strong> {{sender_name}}\n            </div>\n            <h3>Message:</h3>\n            <p>{{message}}</p>\n            <p style=\"text-align: center;\">\n                <a href=\"{{chat_url}}\" class=\"btn\">Reply to Message</a>\n            </p>\n        </div>\n        <div class=\"footer\">\n            <p>&copy; 2025 {{app_name}}. All rights reserved.</p>\n        </div>\n    </div>\n</body>\n</html>','[\"sender_name\", \"message\", \"chat_url\"]',1,'2025-12-21 13:21:43','2025-12-21 13:21:43');
/*!40000 ALTER TABLE `notification_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_deliveries`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `files` json DEFAULT NULL,
  `status` enum('pending','accepted','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `price`, `quantity`, `total`, `attributes`, `created_at`, `updated_at`) VALUES (1,1,23,'Adăugare Produs Simplu','CONT-SIMP-006',9.00,1,9.00,NULL,'2025-12-21 09:12:06','2025-12-21 09:12:06'),(2,2,23,'Adăugare Produs Simplu','CONT-SIMP-006',9.00,1,9.00,NULL,'2025-12-21 09:14:16','2025-12-21 09:14:16'),(3,3,23,'Adăugare Produs Simplu','CONT-SIMP-006',9.00,1,9.00,NULL,'2025-12-21 09:18:18','2025-12-21 09:18:18'),(4,4,26,'Devirusare Profesională Site','INTER-DEV-001',749.00,1,749.00,NULL,'2025-12-21 13:37:18','2025-12-21 13:37:18'),(5,5,26,'Devirusare Profesională Site','INTER-DEV-001',749.00,1,749.00,NULL,'2025-12-21 14:03:04','2025-12-21 14:03:04'),(6,6,18,'Pachet Basic Maintenance','MAINT-BASIC-001',149.00,1,149.00,NULL,'2025-12-21 20:56:21','2025-12-21 20:56:21'),(7,7,26,'Devirusare Profesională Site','INTER-DEV-001',749.00,1,749.00,NULL,'2025-12-22 04:13:19','2025-12-22 04:13:19');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_revisions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_details` json DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `billing_address`, `subtotal`, `tax`, `shipping`, `total`, `payment_method`, `payment_status`, `order_status`, `payment_details`, `notes`, `created_at`, `updated_at`) VALUES (1,'ORD-6947B9E6C2ED3',1,'Admin','msrusu87@gmail.com','07553133223','127 Rabournmead Drive, NORTHOLT','127 Rabournmead Drive, NORTHOLT',9.00,0.00,0.00,9.00,'bank_transfer','pending','pending','\"{\\\"gateway_id\\\":3,\\\"gateway_name\\\":\\\"Transfer Bancar\\\",\\\"fee\\\":0}\"',NULL,'2025-12-21 09:12:06','2025-12-21 09:12:06'),(2,'ORD-6947BA6809B7B',1,'Admin','msrusu87@gmail.com','07553133223','127 Rabournmead Drive, NORTHOLT','127 Rabournmead Drive, NORTHOLT',9.00,0.00,0.00,9.00,'bank_transfer','pending','pending','\"{\\\"gateway_id\\\":3,\\\"gateway_name\\\":\\\"Transfer Bancar\\\",\\\"fee\\\":0}\"',NULL,'2025-12-21 09:14:16','2025-12-21 09:14:16'),(3,'ORD-6947BB5AF2628',1,'Admin','msrusu87@gmail.com','07553133223','127 Rabournmead Drive, NORTHOLT','127 Rabournmead Drive, NORTHOLT',9.00,0.00,0.00,9.00,'bank_transfer','pending','pending','\"{\\\"gateway_id\\\":3,\\\"gateway_name\\\":\\\"Transfer Bancar\\\",\\\"fee\\\":0}\"',NULL,'2025-12-21 09:18:18','2025-12-21 09:18:18'),(4,'ORD-6947F80EAF1A1',1,'fsdfv','mirceasilviurusu@gmail.com','0774077860','Str Principala Nr 13, Pădureni comuna Ciurila','Str Principala Nr 13, Pădureni comuna Ciurila',749.00,0.00,0.00,749.00,'bank_transfer','pending','pending','\"{\\\"gateway_id\\\":3,\\\"gateway_name\\\":\\\"Transfer Bancar\\\",\\\"fee\\\":0}\"',NULL,'2025-12-21 13:37:18','2025-12-21 13:37:18'),(5,'ORD-6947FE1892729',2,'Mircea silviu Rusu','ukmircearusu@gmail.com','07553133223','127 Rabournmead Drive, NORTHOLT','127 Rabournmead Drive, NORTHOLT',749.00,0.00,0.00,749.00,'bank_transfer','pending','pending','\"{\\\"gateway_id\\\":3,\\\"gateway_name\\\":\\\"Transfer Bancar\\\",\\\"fee\\\":0}\"',NULL,'2025-12-21 14:03:04','2025-12-21 14:03:04'),(6,'ORD-69485EF5D919F',1,'Admin','msrusu87@gmail.com','43645344356','Sat Pădureni Comuna Ciurila, Pădureni','Sat Pădureni Comuna Ciurila, Pădureni',149.00,0.00,0.00,149.00,'bank_transfer','pending','pending','\"{\\\"gateway_id\\\":3,\\\"gateway_name\\\":\\\"Transfer Bancar\\\",\\\"fee\\\":0}\"',NULL,'2025-12-21 20:56:21','2025-12-21 20:56:21'),(7,'ORD-6948C55FDD8E0',5,'Test Buyer','cococatelul@gmail.com','0774077860','Str Principala Nr 13, Pădureni comuna Ciurila','Str Principala Nr 13, Pădureni comuna Ciurila',749.00,0.00,0.00,749.00,'bank_transfer','pending','pending','\"{\\\"gateway_id\\\":3,\\\"gateway_name\\\":\\\"Transfer Bancar\\\",\\\"fee\\\":0}\"',NULL,'2025-12-22 04:13:19','2025-12-22 04:13:19');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_builder_blocks`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_builder_blocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `blockable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `blockable_id` bigint unsigned NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` json NOT NULL,
  `styles` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_builder_blocks_blockable_type_blockable_id_index` (`blockable_type`,`blockable_id`)
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_builder_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blocks` json NOT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` json DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json DEFAULT NULL,
  `excerpt` json DEFAULT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `user_id` bigint unsigned NOT NULL,
  `template_id` bigint unsigned DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_meta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index,follow',
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `excerpt`, `featured_image`, `status`, `user_id`, `template_id`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_meta`, `order`, `is_homepage`, `show_in_menu`, `menu_locations`, `custom_fields`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'{\"ro\": \"Home\"}','home','{\"ro\": \"{\\\"blocks\\\":[{\\\"type\\\":\\\"hero\\\",\\\"data\\\":{\\\"title\\\":\\\"Welcome to Carpathian CMS\\\",\\\"subtitle\\\":\\\"A powerful modular content management system\\\",\\\"buttonText\\\":\\\"Get Started\\\",\\\"buttonUrl\\\":\\\"\\\\/contact\\\"}}]}\"}',NULL,NULL,'published',1,NULL,'Carpathian CMS - Modular Content Management System','Welcome to Carpathian CMS',NULL,NULL,'index,follow',0,1,1,NULL,NULL,NULL,'2025-12-07 05:01:16','2025-12-07 05:06:33','2025-12-07 05:06:33'),(2,'{\"ro\": \"Portfolios\"}','portfolios','{\"ro\": \"<div class=\\\"min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-blue-50 py-20\\\">\\n    <div class=\\\"container mx-auto px-4\\\">\\n        <!-- Header Section with Animation -->\\n        <div class=\\\"text-center mb-20 animate-fade-in\\\">\\n            <span class=\\\"inline-block px-6 py-2 mb-6 text-sm font-semibold text-purple-700 bg-purple-100 rounded-full shadow-sm hover:shadow-md transition-shadow duration-300\\\">\\n                {{ __(\'messages.portfolio\') }}\\n            </span>\\n            <h1 class=\\\"text-6xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 mb-6 leading-tight\\\">\\n                {{ __(\'messages.our_portfolio\') }}\\n            </h1>\\n            <p class=\\\"text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed font-light\\\">\\n                {{ __(\'messages.portfolio_subtitle\') }}\\n            </p>\\n        </div>\\n\\n        <!-- Portfolio Grid with Modern Cards -->\\n        <div class=\\\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto mb-16\\\">\\n            \\n            <!-- Project 1: Carpathian AI SaaS Marketplace -->\\n            <div class=\\\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\\\">\\n                <!-- Gradient Overlay -->\\n                <div class=\\\"absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\\\"></div>\\n                \\n                <!-- Image Container with Gradient Background -->\\n                <div class=\\\"relative h-72 overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600\\\">\\n                    <img src=\\\"/images/portfolio/saas-marketplace.jpg\\\" \\n                         alt=\\\"Carpathian AI SaaS Marketplace\\\" \\n                         class=\\\"w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700\\\"\\n                         loading=\\\"lazy\\\"\\n                         onerror=\\\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\\\">\\n                    <div class=\\\"absolute inset-0 bg-gradient-to-br from-indigo-600/90 to-purple-600/90 flex items-center justify-center\\\" style=\\\"display:none;\\\">\\n                        <div class=\\\"text-center text-white p-6\\\">\\n                            <svg class=\\\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                                <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z\\\"/>\\n                            </svg>\\n                            <p class=\\\"text-base font-bold tracking-wide\\\">{{ __(\'messages.ai_platform\') }}</p>\\n                        </div>\\n                    </div>\\n                    <!-- Floating Badge -->\\n                    <div class=\\\"absolute top-4 right-4 z-20\\\">\\n                        <span class=\\\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-indigo-700 shadow-lg backdrop-blur-sm\\\">\\n                            <svg class=\\\"w-3.5 h-3.5\\\" fill=\\\"currentColor\\\" viewBox=\\\"0 0 20 20\\\">\\n                                <path fill-rule=\\\"evenodd\\\" d=\\\"M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z\\\" clip-rule=\\\"evenodd\\\"/>\\n                            </svg>\\n                            {{ __(\'messages.ai_platform\') }}\\n                        </span>\\n                    </div>\\n                </div>\\n                \\n                <!-- Content -->\\n                <div class=\\\"p-8 relative z-20\\\">\\n                    <h3 class=\\\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-600 transition-all duration-300\\\">\\n                        Carpathian AI SaaS Marketplace\\n                    </h3>\\n                    <p class=\\\"text-gray-600 mb-6 leading-relaxed line-clamp-3\\\">\\n                        AI-powered freelance platform connecting talented professionals with clients through intelligent matching and seamless collaboration.\\n                    </p>\\n                    \\n                    <a href=\\\"https://chat.carphatian.ro\\\" target=\\\"_blank\\\" rel=\\\"noopener noreferrer\\\" \\n                       class=\\\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\\\">\\n                        {{ __(\'messages.visit_site\') }}\\n                        <svg class=\\\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\\\"/>\\n                        </svg>\\n                    </a>\\n                </div>\\n            </div>\\n\\n            <!-- Project 2: Demo Tools Portfolio -->\\n            <div class=\\\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\\\">\\n                <div class=\\\"absolute inset-0 bg-gradient-to-br from-purple-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\\\"></div>\\n                \\n                <div class=\\\"relative h-72 overflow-hidden bg-gradient-to-br from-purple-600 via-blue-600 to-cyan-600\\\">\\n                    <img src=\\\"/images/portfolio/demo-tools.jpg\\\" \\n                         alt=\\\"Demo Tools Portfolio\\\" \\n                         class=\\\"w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700\\\"\\n                         loading=\\\"lazy\\\"\\n                         onerror=\\\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\\\">\\n                    <div class=\\\"absolute inset-0 bg-gradient-to-br from-purple-600/90 to-blue-600/90 flex items-center justify-center\\\" style=\\\"display:none;\\\">\\n                        <div class=\\\"text-center text-white p-6\\\">\\n                            <svg class=\\\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                                <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z\\\"/>\\n                            </svg>\\n                            <p class=\\\"text-base font-bold tracking-wide\\\">{{ __(\'messages.web_tools\') }}</p>\\n                        </div>\\n                    </div>\\n                    <div class=\\\"absolute top-4 right-4 z-20\\\">\\n                        <span class=\\\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-purple-700 shadow-lg backdrop-blur-sm\\\">\\n                            <svg class=\\\"w-3.5 h-3.5\\\" fill=\\\"currentColor\\\" viewBox=\\\"0 0 20 20\\\">\\n                                <path d=\\\"M10 12a2 2 0 100-4 2 2 0 000 4z\\\"/>\\n                                <path fill-rule=\\\"evenodd\\\" d=\\\"M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z\\\" clip-rule=\\\"evenodd\\\"/>\\n                            </svg>\\n                            {{ __(\'messages.web_tools\') }}\\n                        </span>\\n                    </div>\\n                </div>\\n                \\n                <div class=\\\"p-8 relative z-20\\\">\\n                    <h3 class=\\\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-blue-600 transition-all duration-300\\\">\\n                        Demo Tools Portfolio\\n                    </h3>\\n                    <p class=\\\"text-gray-600 mb-6 leading-relaxed line-clamp-3\\\">\\n                        Professional web tools showcasing various utilities and demonstrations for modern web development.\\n                    </p>\\n                    \\n                    <a href=\\\"https://social.carphatian.ro\\\" target=\\\"_blank\\\" rel=\\\"noopener noreferrer\\\" \\n                       class=\\\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\\\">\\n                        {{ __(\'messages.visit_site\') }}\\n                        <svg class=\\\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\\\"/>\\n                        </svg>\\n                    </a>\\n                </div>\\n            </div>\\n\\n            <!-- Project 3: PDF Summary Generator -->\\n            <div class=\\\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\\\">\\n                <div class=\\\"absolute inset-0 bg-gradient-to-br from-red-500/10 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\\\"></div>\\n                \\n                <div class=\\\"relative h-72 overflow-hidden bg-gradient-to-br from-red-600 via-orange-600 to-amber-600\\\">\\n                    <img src=\\\"/images/portfolio/pdf-generator.jpg\\\" \\n                         alt=\\\"PDF Summary Generator\\\" \\n                         class=\\\"w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700\\\"\\n                         loading=\\\"lazy\\\"\\n                         onerror=\\\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\\\">\\n                    <div class=\\\"absolute inset-0 bg-gradient-to-br from-red-600/90 to-orange-600/90 flex items-center justify-center\\\" style=\\\"display:none;\\\">\\n                        <div class=\\\"text-center text-white p-6\\\">\\n                            <svg class=\\\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                                <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\\\"/>\\n                            </svg>\\n                            <p class=\\\"text-base font-bold tracking-wide\\\">{{ __(\'messages.ai_powered\') }}</p>\\n                        </div>\\n                    </div>\\n                    <div class=\\\"absolute top-4 right-4 z-20\\\">\\n                        <span class=\\\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-red-700 shadow-lg backdrop-blur-sm\\\">\\n                            <svg class=\\\"w-3.5 h-3.5\\\" fill=\\\"currentColor\\\" viewBox=\\\"0 0 20 20\\\">\\n                                <path fill-rule=\\\"evenodd\\\" d=\\\"M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z\\\" clip-rule=\\\"evenodd\\\"/>\\n                            </svg>\\n                            {{ __(\'messages.ai_powered\') }}\\n                        </span>\\n                    </div>\\n                </div>\\n                \\n                <div class=\\\"p-8 relative z-20\\\">\\n                    <h3 class=\\\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-red-600 group-hover:to-orange-600 transition-all duration-300\\\">\\n                        PDF Summary Generator\\n                    </h3>\\n                    <p class=\\\"text-gray-600 mb-6 leading-relaxed line-clamp-3\\\">\\n                        AI-powered tool that automatically generates intelligent summaries from PDF documents using advanced algorithms.\\n                    </p>\\n                    \\n                    <a href=\\\"https://social.carphatian.ro\\\" target=\\\"_blank\\\" rel=\\\"noopener noreferrer\\\" \\n                       class=\\\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white font-semibold rounded-xl hover:from-red-700 hover:to-orange-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\\\">\\n                        {{ __(\'messages.visit_site\') }}\\n                        <svg class=\\\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\\\"/>\\n                        </svg>\\n                    </a>\\n                </div>\\n            </div>\\n\\n            <!-- Project 4: ATMN - Antimony Coin -->\\n            <div class=\\\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\\\">\\n                <div class=\\\"absolute inset-0 bg-gradient-to-br from-green-500/10 to-teal-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\\\"></div>\\n                \\n                <div class=\\\"relative h-72 overflow-hidden bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600\\\">\\n                    <img src=\\\"/images/portfolio/antimony-coin.jpg\\\" \\n                         alt=\\\"ATMN Antimony Coin\\\" \\n                         class=\\\"w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700\\\"\\n                         loading=\\\"lazy\\\"\\n                         onerror=\\\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\\\">\\n                    <div class=\\\"absolute inset-0 bg-gradient-to-br from-green-600/90 to-teal-600/90 flex items-center justify-center\\\" style=\\\"display:none;\\\">\\n                        <div class=\\\"text-center text-white p-6\\\">\\n                            <svg class=\\\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                                <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\\\"/>\\n                            </svg>\\n                            <p class=\\\"text-base font-bold tracking-wide\\\">{{ __(\'messages.blockchain\') }}</p>\\n                        </div>\\n                    </div>\\n                    <div class=\\\"absolute top-4 right-4 z-20\\\">\\n                        <span class=\\\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-green-700 shadow-lg backdrop-blur-sm\\\">\\n                            <svg class=\\\"w-3.5 h-3.5\\\" fill=\\\"currentColor\\\" viewBox=\\\"0 0 20 20\\\">\\n                                <path fill-rule=\\\"evenodd\\\" d=\\\"M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z\\\" clip-rule=\\\"evenodd\\\"/>\\n                                <path d=\\\"M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z\\\"/>\\n                            </svg>\\n                            {{ __(\'messages.blockchain\') }}\\n                        </span>\\n                    </div>\\n                </div>\\n                \\n                <div class=\\\"p-8 relative z-20\\\">\\n                    <h3 class=\\\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-green-600 group-hover:to-teal-600 transition-all duration-300\\\">\\n                        ATMN - Antimony Coin\\n                    </h3>\\n                    <p class=\\\"text-gray-600 mb-6 leading-relaxed line-clamp-3\\\">\\n                        Blockchain explorer and cryptocurrency platform for Antimony Coin with real-time transaction tracking and analytics.\\n                    </p>\\n                    \\n                    <a href=\\\"https://explorer.carphatian.ro\\\" target=\\\"_blank\\\" rel=\\\"noopener noreferrer\\\" \\n                       class=\\\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-teal-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\\\">\\n                        {{ __(\'messages.visit_site\') }}\\n                        <svg class=\\\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\\\"/>\\n                        </svg>\\n                    </a>\\n                </div>\\n            </div>\\n\\n            <!-- Project 5: Language Detection -->\\n            <div class=\\\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\\\">\\n                <div class=\\\"absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\\\"></div>\\n                \\n                <div class=\\\"relative h-72 overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600\\\">\\n                    <img src=\\\"/images/portfolio/language-detection.jpg\\\" \\n                         alt=\\\"Language Detection\\\" \\n                         class=\\\"w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700\\\"\\n                         loading=\\\"lazy\\\"\\n                         onerror=\\\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\\\">\\n                    <div class=\\\"absolute inset-0 bg-gradient-to-br from-blue-600/90 to-indigo-600/90 flex items-center justify-center\\\" style=\\\"display:none;\\\">\\n                        <div class=\\\"text-center text-white p-6\\\">\\n                            <svg class=\\\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                                <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.613 3 18.129\\\"/>\\n                            </svg>\\n                            <p class=\\\"text-base font-bold tracking-wide\\\">{{ __(\'messages.openai\') }}</p>\\n                        </div>\\n                    </div>\\n                    <div class=\\\"absolute top-4 right-4 z-20\\\">\\n                        <span class=\\\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-blue-700 shadow-lg backdrop-blur-sm\\\">\\n                            <svg class=\\\"w-3.5 h-3.5\\\" fill=\\\"currentColor\\\" viewBox=\\\"0 0 20 20\\\">\\n                                <path fill-rule=\\\"evenodd\\\" d=\\\"M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z\\\" clip-rule=\\\"evenodd\\\"/>\\n                            </svg>\\n                            {{ __(\'messages.openai\') }}\\n                        </span>\\n                    </div>\\n                </div>\\n                \\n                <div class=\\\"p-8 relative z-20\\\">\\n                    <h3 class=\\\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-indigo-600 transition-all duration-300\\\">\\n                        Language Detection\\n                    </h3>\\n                    <p class=\\\"text-gray-600 mb-6 leading-relaxed line-clamp-3\\\">\\n                        OpenAI-powered language detection tool that accurately identifies and analyzes text in multiple languages.\\n                    </p>\\n                    \\n                    <a href=\\\"https://antimony.carphatian.ro\\\" target=\\\"_blank\\\" rel=\\\"noopener noreferrer\\\" \\n                       class=\\\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\\\">\\n                        {{ __(\'messages.visit_site\') }}\\n                        <svg class=\\\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\\\"/>\\n                        </svg>\\n                    </a>\\n                </div>\\n            </div>\\n\\n            <!-- Project 6: Carpathian CMS -->\\n            <div class=\\\"group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer\\\">\\n                <div class=\\\"absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10\\\"></div>\\n                \\n                <div class=\\\"relative h-72 overflow-hidden bg-gradient-to-br from-purple-600 via-fuchsia-600 to-pink-600\\\">\\n                    <img src=\\\"/images/portfolio/carpathian-cms.jpg\\\" \\n                         alt=\\\"Carpathian CMS\\\" \\n                         class=\\\"w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700\\\"\\n                         loading=\\\"lazy\\\"\\n                         onerror=\\\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\\\">\\n                    <div class=\\\"absolute inset-0 bg-gradient-to-br from-purple-600/90 to-pink-600/90 flex items-center justify-center\\\" style=\\\"display:none;\\\">\\n                        <div class=\\\"text-center text-white p-6\\\">\\n                            <svg class=\\\"w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                                <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\\\"/>\\n                            </svg>\\n                            <p class=\\\"text-base font-bold tracking-wide\\\">{{ __(\'messages.ai_powered\') }}</p>\\n                        </div>\\n                    </div>\\n                    <div class=\\\"absolute top-4 right-4 z-20\\\">\\n                        <span class=\\\"inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-purple-700 shadow-lg backdrop-blur-sm\\\">\\n                            <svg class=\\\"w-3.5 h-3.5\\\" fill=\\\"currentColor\\\" viewBox=\\\"0 0 20 20\\\">\\n                                <path fill-rule=\\\"evenodd\\\" d=\\\"M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z\\\" clip-rule=\\\"evenodd\\\"/>\\n                            </svg>\\n                            {{ __(\'messages.ai_powered\') }}\\n                        </span>\\n                    </div>\\n                </div>\\n                \\n                <div class=\\\"p-8 relative z-20\\\">\\n                    <h3 class=\\\"text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-pink-600 transition-all duration-300\\\">\\n                        Carpathian CMS\\n                    </h3>\\n                    <p class=\\\"text-gray-600 mb-6 leading-relaxed line-clamp-3\\\">\\n                        Modern content management system powered by AI, featuring advanced page building and seamless content creation.\\n                    </p>\\n                    \\n                    <a href=\\\"https://cms.carphatian.ro\\\" target=\\\"_blank\\\" rel=\\\"noopener noreferrer\\\" \\n                       class=\\\"inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300\\\">\\n                        {{ __(\'messages.visit_site\') }}\\n                        <svg class=\\\"w-5 h-5 group-hover:translate-x-1 transition-transform duration-300\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14\\\"/>\\n                        </svg>\\n                    </a>\\n                </div>\\n            </div>\\n\\n        </div>\\n\\n        <!-- CTA Section with Modern Gradient -->\\n        <div class=\\\"relative mt-24 max-w-5xl mx-auto\\\">\\n            <!-- Animated Background -->\\n            <div class=\\\"absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-3xl blur-2xl opacity-20 animate-pulse\\\"></div>\\n            \\n            <!-- Content Card -->\\n            <div class=\\\"relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden\\\">\\n                <!-- Gradient Border Effect -->\\n                <div class=\\\"absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 opacity-10\\\"></div>\\n                \\n                <div class=\\\"relative p-12 md:p-16 text-center\\\">\\n                    <div class=\\\"inline-block p-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl mb-6\\\">\\n                        <svg class=\\\"w-12 h-12 text-purple-600\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M13 10V3L4 14h7v7l9-11h-7z\\\"/>\\n                        </svg>\\n                    </div>\\n                    \\n                    <h2 class=\\\"text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-6\\\">\\n                        {{ __(\'messages.interested_work\') }}\\n                    </h2>\\n                    \\n                    <p class=\\\"text-xl md:text-2xl text-gray-700 mb-10 max-w-3xl mx-auto leading-relaxed font-light\\\">\\n                        {{ __(\'messages.interested_work_desc\') }}\\n                    </p>\\n                    \\n                    <a href=\\\"/contact\\\" \\n                       class=\\\"inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 text-white text-lg font-bold rounded-2xl hover:from-purple-700 hover:via-pink-700 hover:to-blue-700 shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 group\\\">\\n                        {{ __(\'messages.get_in_touch\') }}\\n                        <svg class=\\\"w-6 h-6 group-hover:translate-x-2 transition-transform duration-300\\\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" viewBox=\\\"0 0 24 24\\\">\\n                            <path stroke-linecap=\\\"round\\\" stroke-linejoin=\\\"round\\\" stroke-width=\\\"2\\\" d=\\\"M13 7l5 5m0 0l-5 5m5-5H6\\\"/>\\n                        </svg>\\n                    </a>\\n                </div>\\n            </div>\\n        </div>\\n    </div>\\n</div>\\n\\n<style>\\n@keyframes fade-in {\\n    from {\\n        opacity: 0;\\n        transform: translateY(20px);\\n    }\\n    to {\\n        opacity: 1;\\n        transform: translateY(0);\\n    }\\n}\\n\\n.animate-fade-in {\\n    animation: fade-in 1s ease-out;\\n}\\n\\n.line-clamp-3 {\\n    display: -webkit-box;\\n    -webkit-line-clamp: 3;\\n    -webkit-box-orient: vertical;\\n    overflow: hidden;\\n}\\n</style>\"}',NULL,NULL,'published',1,NULL,'Our Portfolios - Carpathian','View our portfolio of projects and work',NULL,NULL,'index,follow',0,0,1,NULL,NULL,'2025-12-11 22:38:42','2025-12-11 22:38:42','2025-12-12 03:55:05',NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_gateways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `credentials` json NOT NULL,
  `settings` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_test_mode` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_gateways_provider_unique` (`provider`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_gateways`
--

LOCK TABLES `payment_gateways` WRITE;
/*!40000 ALTER TABLE `payment_gateways` DISABLE KEYS */;
INSERT INTO `payment_gateways` (`id`, `name`, `slug`, `code`, `provider`, `description`, `credentials`, `settings`, `is_active`, `is_test_mode`, `sort_order`, `created_at`, `updated_at`) VALUES (1,'Card Bancar','card-bancar','card-bancar','stripe','Plătește rapid și sigur cu cardul tău','{}','{}',0,0,1,'2025-12-21 08:16:28','2025-12-21 09:15:20'),(2,'PayPal','paypal','paypal','paypal','Plătește cu contul tău PayPal','{}','{}',0,0,2,'2025-12-21 08:16:28','2025-12-21 09:15:19'),(3,'Transfer Bancar','transfer-bancar','transfer-bancar','bank_transfer','Transfer bancar direct în contul nostru','{\"iban\": \"ro0000000000000000\", \"bank_name\": \"Banca Transilvania\", \"swift_bic\": \"BTRLRO22\", \"bank_address\": \"Cluj-Napoca, Romania\", \"account_holder\": \"Rusu Mircea Silviu\", \"account_currency\": \"RON\"}','{}',1,0,3,'2025-12-21 08:16:28','2025-12-21 09:17:52');
/*!40000 ALTER TABLE `payment_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_transactions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `gateway_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `type` enum('payment','refund','partial_refund','chargeback') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','completed','failed','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `performance_metrics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `metric_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metric_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (1,'view orders','web','2025-12-21 11:38:28','2025-12-21 11:38:28'),(2,'view own orders','web','2025-12-21 11:38:28','2025-12-21 11:38:28'),(3,'create support ticket','web','2025-12-21 11:38:28','2025-12-21 11:38:28'),(4,'view support chat','web','2025-12-21 11:38:28','2025-12-21 11:38:28'),(5,'view posts','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(6,'create posts','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(7,'edit posts','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(8,'delete posts','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(9,'publish posts','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(10,'view pages','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(11,'create pages','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(12,'edit pages','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(13,'delete pages','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(14,'publish pages','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(15,'view categories','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(16,'create categories','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(17,'edit categories','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(18,'delete categories','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(19,'view tags','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(20,'create tags','web','2025-12-21 17:44:43','2025-12-21 17:44:43'),(21,'edit tags','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(22,'delete tags','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(23,'view media','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(24,'upload media','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(25,'edit media','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(26,'delete media','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(27,'view templates','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(28,'create templates','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(29,'edit templates','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(30,'delete templates','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(31,'activate templates','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(32,'view plugins','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(33,'create plugins','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(34,'edit plugins','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(35,'delete plugins','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(36,'activate plugins','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(37,'use ai generation','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(38,'manage ai settings','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(39,'view users','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(40,'create users','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(41,'edit users','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(42,'delete users','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(43,'view roles','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(44,'create roles','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(45,'edit roles','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(46,'delete roles','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(47,'assign roles','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(48,'view permissions','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(49,'view settings','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(50,'edit settings','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(51,'view products','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(52,'create products','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(53,'edit products','web','2025-12-21 17:44:44','2025-12-21 17:44:44'),(54,'delete products','web','2025-12-21 17:44:44','2025-12-21 17:44:44');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plugins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0.0',
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `config` json DEFAULT NULL,
  `hooks` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `ai_generated` tinyint(1) NOT NULL DEFAULT '0',
  `ai_generation_id` bigint unsigned DEFAULT NULL,
  `dependencies` json DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plugins_slug_unique` (`slug`),
  KEY `plugins_ai_generation_id_foreign` (`ai_generation_id`),
  KEY `plugins_slug_index` (`slug`),
  KEY `plugins_is_active_index` (`is_active`),
  CONSTRAINT `plugins_ai_generation_id_foreign` FOREIGN KEY (`ai_generation_id`) REFERENCES `ai_generations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugins`
--

LOCK TABLES `plugins` WRITE;
/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;
INSERT INTO `plugins` (`id`, `name`, `slug`, `description`, `version`, `author`, `author_url`, `code`, `config`, `hooks`, `is_active`, `ai_generated`, `ai_generation_id`, `dependencies`, `icon`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Freelancer Marketplace','freelancer','Complete freelancing marketplace','1.0.0','Carphatian CMS',NULL,NULL,'{\"frontend_menus\": [{\"name\": \"Freelancer\", \"slug\": \"freelancer-menu\", \"items\": [{\"url\": \"/freelancer\", \"icon\": \"briefcase\", \"title\": \"Freelancer\"}, {\"url\": \"/freelancer/profiles\", \"icon\": \"users\", \"title\": \"Găsește Freelanceri\"}, {\"url\": \"/freelancer/gigs\", \"icon\": \"star\", \"title\": \"Servicii\"}, {\"url\": \"/freelancer/orders\", \"icon\": \"shopping-cart\", \"title\": \"Comenzi\"}, {\"url\": \"/freelancer/become\", \"icon\": \"rocket\", \"title\": \"Devino Freelancer\"}], \"location\": \"header\"}]}',NULL,0,0,NULL,NULL,NULL,'2025-12-07 04:50:18','2025-12-08 05:59:10',NULL),(2,'E-commerce','ecommerce','Complete e-commerce solution with products, orders, cart, checkout, and payments','1.0.0','Carphatian CMS',NULL,NULL,'{\"frontend_menus\": [{\"name\": \"Shop\", \"slug\": \"shop-menu\", \"items\": [{\"url\": \"/shop\", \"icon\": \"shopping-bag\", \"title\": \"Shop\"}, {\"url\": \"/shop/products\", \"icon\": \"cube\", \"title\": \"Products\"}, {\"url\": \"/shop/categories\", \"icon\": \"folder\", \"title\": \"Categories\"}, {\"url\": \"/cart\", \"icon\": \"shopping-cart\", \"title\": \"Cart\"}, {\"url\": \"/checkout\", \"icon\": \"credit-card\", \"title\": \"Checkout\"}], \"location\": \"header\"}]}',NULL,1,0,NULL,NULL,NULL,'2025-12-07 04:55:54','2025-12-08 05:58:45',NULL);
/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolios`
--

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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` json DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json DEFAULT NULL,
  `excerpt` json DEFAULT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `template_id` bigint unsigned DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `excerpt`, `featured_image`, `status`, `user_id`, `category_id`, `template_id`, `meta_title`, `meta_description`, `meta_keywords`, `views`, `featured`, `allow_comments`, `custom_fields`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'{\"ro\": \"Lansarea noului CMS Carphatian\"}','lansarea-noului-cms-carphatian','{\"ro\": \"<p>Carphatian CMS este o platformă modernă și puternică pentru crearea și gestionarea site-urilor web. Cu funcționalități avansate și interfață intuitivă, vă oferim instrumentele necesare pentru succes online.</p><p>Printre caracteristicile principale se numără: drag & drop builder, integrare AI, sistem de plugin-uri, și multe altele.</p>\"}','{\"ro\": \"Suntem bucuroși să anunțăm lansarea oficială a platformei noastre CMS cu integrare AI.\"}','/images/blog/lansarea-noului-cms-carphatian.jpg','published',1,1,NULL,NULL,NULL,NULL,60,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56','2025-12-11 13:47:44',NULL),(2,'{\"ro\": \"Cum să optimizezi SEO-ul site-ului tău\"}','cum-sa-optimizezi-seo-ul-site-ului-tau','{\"ro\": \"<p>Optimizarea pentru motoarele de căutare este esențială pentru succesul online. În acest articol, vei învăța tehnici avansate de SEO.</p><p>Începe cu cercetarea cuvintelor cheie, optimizează meta tag-urile, și creează conținut de calitate.</p>\"}','{\"ro\": \"Descoperă cele mai bune practici pentru optimizarea SEO și creșterea vizibilității online.\"}','/images/blog/cum-sa-optimizezi-seo-ul-site-ului-tau.jpg','published',1,1,NULL,NULL,NULL,NULL,26,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56','2025-12-11 13:47:41',NULL),(3,'{\"ro\": \"Integrarea AI în CMS modern\"}','integrarea-ai-in-cms-modern','{\"ro\": \"<p>Inteligența artificială transformă industria web development. De la generarea automată de conținut până la optimizarea experienței utilizatorului.</p><p>Carphatian CMS integrează AI pentru a-ți facilita munca și a îmbunătăți rezultatele.</p>\"}','{\"ro\": \"Află cum inteligența artificială revolutionează modul în care creăm și gestionăm conținutul web.\"}','/images/blog/integrarea-ai-in-cms-modern.jpg','published',1,1,NULL,NULL,NULL,NULL,23,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56','2025-12-11 13:47:45',NULL),(4,'{\"en\": \"Modern Design: Complete Guide for Intuitive Interfaces\", \"ro\": \"Design Modern: Ghidul Complet pentru Interfețe Intuitive\"}','design-modern-ghid-complet-interfete-intuitive','{\"en\": \"Modern design content...\", \"ro\": \"<h2>Introducere în Design-ul Modern CMS</h2>\\n<p>Design-ul modern nu este doar despre aspect vizual - este despre crearea unei experiențe fluide, intuitive și plăcute pentru utilizatori. Platforma CMS Carpathian pune accent pe design responsive și interfețe care facilitează munca utilizatorilor.</p>\\n\\n<h3>🎨 Principii de Design Implementate</h3>\\n<p>Carpathian CMS utilizează cele mai recente tendințe în design web:</p>\\n<ul>\\n<li><strong>Material Design</strong> - Elemente vizuale moderne cu umbre și animații subtile</li>\\n<li><strong>Flat Design</strong> - Interfață curată și simplă, fără elemente inutile</li>\\n<li><strong>Neumorphism</strong> - Efecte 3D moderne pentru butoane și carduri</li>\\n<li><strong>Dark Mode</strong> - Temă întunecată pentru reducerea oboselii ochilor</li>\\n</ul>\\n\\n<h3>📱 Design Responsive și Multi-Platform</h3>\\n<p>Unul dintre cele mai importante aspecte ale design-ului modern este adaptabilitatea. CMS-ul nostru oferă:</p>\\n\\n<h4>Responsive Breakpoints</h4>\\n<ul>\\n<li>Mobile (320px - 767px) - Design optimizat pentru telefoane</li>\\n<li>Tablet (768px - 1023px) - Layout adaptat pentru tablete</li>\\n<li>Desktop (1024px+) - Experiență completă pe ecrane mari</li>\\n</ul>\\n\\n<h3>🎯 Interfață Intuitivă</h3>\\n<p>Interfața utilizator este proiectată pentru a fi:</p>\\n<ol>\\n<li><strong>Ușor de învățat</strong> - Curba de învățare minimă</li>\\n<li><strong>Eficientă</strong> - Acțiuni rapide cu minimal clicks</li>\\n<li><strong>Memorabilă</strong> - Layout consistent și predictibil</li>\\n<li><strong>Fără erori</strong> - Validări și confirmări pentru acțiuni importante</li>\\n</ol>\\n\\n<h3>🔧 Customizare Avansată</h3>\\n<p>Personalizează designul după nevoile tale:</p>\\n<ul>\\n<li>Tema de culori personalizată</li>\\n<li>Logo și branding propriu</li>\\n<li>Fonturi custom</li>\\n<li>Layout-uri personalizate pentru pagini</li>\\n</ul>\\n\\n<h3>✨ Animații și Tranziții</h3>\\n<p>Design-ul modern include animații fluide care îmbunătățesc experiența:</p>\\n<ul>\\n<li>Fade-in pentru încărcarea conținutului</li>\\n<li>Slide transitions pentru meniuri</li>\\n<li>Hover effects pe butoane și linkuri</li>\\n<li>Loading animations elegante</li>\\n</ul>\\n\\n<h3>🎨 Paleta de Culori</h3>\\n<p>CMS Carpathian folosește o paletă de culori profesională:</p>\\n<ul>\\n<li><strong>Primary</strong>: #3B82F6 (Blue) - Pentru acțiuni principale</li>\\n<li><strong>Secondary</strong>: #8B5CF6 (Purple) - Pentru elemente secundare</li>\\n<li><strong>Success</strong>: #10B981 (Green) - Pentru confirmări</li>\\n<li><strong>Danger</strong>: #EF4444 (Red) - Pentru alerte</li>\\n</ul>\\n\\n<h3>📊 Dashboard Modern</h3>\\n<p>Dashboard-ul oferă o vedere de ansamblu clară:</p>\\n<ul>\\n<li>Widget-uri personalizabile</li>\\n<li>Grafice interactive</li>\\n<li>Statistici în timp real</li>\\n<li>Quick actions pentru taskuri frecvente</li>\\n</ul>\\n\\n<h3>🚀 Performanță și Optimizare</h3>\\n<p>Design-ul este optimizat pentru viteză:</p>\\n<ul>\\n<li>CSS și JavaScript minimizate</li>\\n<li>Lazy loading pentru imagini</li>\\n<li>Cache eficient pentru assets</li>\\n<li>CDN pentru resurse statice</li>\\n</ul>\\n\\n<h3>💡 Concluzie</h3>\\n<p>Design-ul modern al CMS Carpathian combină estetica cu funcționalitatea, oferind o experiență de utilizare superioară. Fie că gestionezi un blog personal sau un site corporativ complex, interfața intuitivă te ajută să îți atingi obiectivele rapid și eficient.</p>\"}','{\"en\": \"Discover how modern design and intuitive interfaces transform user experience in Carpathian CMS.\", \"ro\": \"Descoperă cum design-ul modern și interfețele intuitive transformă experiența utilizatorilor în platforma CMS Carpathian.\"}','images/blog/design-modern.jpg','published',1,2,NULL,NULL,NULL,NULL,10,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-21 08:40:52',NULL),(5,'{\"en\": \"High Performance: Optimization and Maximum Speed in CMS\", \"ro\": \"Performanță Ridicată: Optimizare și Viteză Maximă în CMS\"}','performanta-ridicata-optimizare-viteza-maxima','{\"en\": \"High performance content...\", \"ro\": \"<h2>Performanță de Nivel Enterprise</h2>\\n<p>Performanța este esențială pentru succesul oricărui site web. CMS Carpathian este construit de la zero cu focus pe viteză și eficiență.</p>\\n\\n<h3>⚡ Tehnologii de Performanță</h3>\\n<p>Platforma utilizează cele mai avansate tehnologii pentru viteză maximă:</p>\\n\\n<h4>1. Redis Caching</h4>\\n<p>Redis este implementat pentru:</p>\\n<ul>\\n<li>Cache de query-uri database</li>\\n<li>Session storage ultra-rapid</li>\\n<li>Cache de obiecte și view-uri</li>\\n<li>Queue management pentru taskuri background</li>\\n</ul>\\n\\n<h4>2. OPcache PHP</h4>\\n<p>Accelerarea codului PHP prin:</p>\\n<ul>\\n<li>Precompilarea bytecode</li>\\n<li>Cache în memorie pentru scripturi</li>\\n<li>Reducerea timpului de execuție cu până la 70%</li>\\n</ul>\\n\\n<h4>3. CDN Integration</h4>\\n<p>Content Delivery Network pentru:</p>\\n<ul>\\n<li>Distribuția globală a assets-urilor</li>\\n<li>Reducerea latency-ului</li>\\n<li>Încărcarea paralelă a resurselor</li>\\n</ul>\\n\\n<h3>🚀 Optimizări Database</h3>\\n<p>Database-ul este optimizat pentru performanță maximă:</p>\\n<ul>\\n<li><strong>Indexuri eficiente</strong> - Pe toate coloanele frecvent căutate</li>\\n<li><strong>Query optimization</strong> - Folosind Eager Loading și Query Builder</li>\\n<li><strong>Connection pooling</strong> - Reutilizarea conexiunilor</li>\\n<li><strong>Read replicas</strong> - Pentru scalabilitate</li>\\n</ul>\\n\\n<h3>📦 Asset Management</h3>\\n<p>Gestionarea eficientă a resurselor:</p>\\n\\n<h4>Minificare și Compresie</h4>\\n<ul>\\n<li>CSS minificat și combinat</li>\\n<li>JavaScript uglified și bundle-d</li>\\n<li>Gzip compression pentru toate assets-urile</li>\\n<li>Brotli compression pentru browsere moderne</li>\\n</ul>\\n\\n<h4>Image Optimization</h4>\\n<ul>\\n<li>WebP format pentru imagini moderne</li>\\n<li>Lazy loading pentru imagini off-screen</li>\\n<li>Responsive images cu srcset</li>\\n<li>Compresia automată la upload</li>\\n</ul>\\n\\n<h3>⚙️ Laravel Optimizations</h3>\\n<p>Framework-ul Laravel este optimizat prin:</p>\\n<ul>\\n<li><strong>Config caching</strong> - php artisan config:cache</li>\\n<li><strong>Route caching</strong> - php artisan route:cache</li>\\n<li><strong>View compilation</strong> - Blade templates pre-compilate</li>\\n<li><strong>Autoload optimization</strong> - Composer optimizat</li>\\n</ul>\\n\\n<h3>🔧 Server Configuration</h3>\\n<p>Server-ul este configurat pentru performanță:</p>\\n\\n<h4>Nginx Optimization</h4>\\n<ul>\\n<li>HTTP/2 enabled pentru multiplexing</li>\\n<li>FastCGI caching</li>\\n<li>Gzip compression</li>\\n<li>Browser caching headers</li>\\n</ul>\\n\\n<h4>PHP-FPM Tuning</h4>\\n<ul>\\n<li>Process manager optimizat</li>\\n<li>Memory limits ajustate</li>\\n<li>Max execution time optimizat</li>\\n</ul>\\n\\n<h3>📊 Monitoring și Analytics</h3>\\n<p>Monitorizarea continuă a performanței:</p>\\n<ul>\\n<li>New Relic pentru APM (Application Performance Monitoring)</li>\\n<li>Laravel Telescope pentru debugging</li>\\n<li>Query time logging</li>\\n<li>Error tracking cu Sentry</li>\\n</ul>\\n\\n<h3>🎯 Benchmarks Reale</h3>\\n<p>Rezultate măsurate:</p>\\n<ul>\\n<li><strong>Time to First Byte (TTFB)</strong>: < 100ms</li>\\n<li><strong>First Contentful Paint</strong>: < 1s</li>\\n<li><strong>Time to Interactive</strong>: < 2s</li>\\n<li><strong>PageSpeed Score</strong>: 95+/100</li>\\n</ul>\\n\\n<h3>🔄 Auto-Scaling</h3>\\n<p>Scalabilitate automată pentru traffic crescut:</p>\\n<ul>\\n<li>Horizontal scaling cu load balancer</li>\\n<li>Auto-scaling bazat pe CPU/Memory</li>\\n<li>Database sharding pentru volume mari</li>\\n</ul>\\n\\n<h3>💡 Best Practices</h3>\\n<p>Recomandări pentru performanță optimă:</p>\\n<ol>\\n<li>Activează toate cache-urile în producție</li>\\n<li>Folosește CDN pentru assets statice</li>\\n<li>Optimizează imaginile înainte de upload</li>\\n<li>Minimizează numărul de query-uri database</li>\\n<li>Folosește pagination pentru liste mari</li>\\n</ol>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>CMS Carpathian oferă performanță de top prin combinarea inteligentă a tehnologiilor moderne de caching, optimizare și configurare server. Rezultatul: un site rapid, responsive și scalabil.</p>\"}','{\"en\": \"How Carpathian CMS achieves incredible speeds and optimal performance through advanced caching and optimization.\", \"ro\": \"Cum CMS Carpathian atinge viteze incredibile și performanță optimă prin tehnologii avansate de caching și optimizare.\"}','images/blog/performanta.jpg','published',1,4,NULL,NULL,NULL,NULL,48,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-23 02:07:53',NULL),(6,'{\"en\": \"AI Integration: How Artificial Intelligence Revolutionizes CMS\", \"ro\": \"Integrare AI: Cum Inteligența Artificială Revoluționează CMS-ul\"}','integrare-ai-inteligenta-artificiala-cms','{\"en\": \"AI integration content...\", \"ro\": \"<h2>AI în CMS: Viitorul Este Acum</h2>\\n<p>Inteligența artificială nu mai este science fiction - este o realitate care transformă modul în care creăm și gestionăm conținutul web. CMS Carpathian integrează AI în mod nativ pentru a automatiza taskuri și a îmbunătăți productivitatea.</p>\\n\\n<h3>🤖 Funcționalități AI Integrate</h3>\\n\\n<h4>1. Content Generation AI</h4>\\n<p>Generarea automată de conținut folosind GPT-4:</p>\\n<ul>\\n<li><strong>Blog Posts</strong> - Scrie articole complete pe bază de subiect</li>\\n<li><strong>Product Descriptions</strong> - Descrieri profesionale pentru produse</li>\\n<li><strong>Meta Tags</strong> - Title și description optimizate pentru SEO</li>\\n<li><strong>Social Media</strong> - Postări pentru Facebook, Twitter, LinkedIn</li>\\n</ul>\\n\\n<h4>2. AI Content Editor</h4>\\n<p>Editor inteligent cu sugestii în timp real:</p>\\n<ul>\\n<li>Corectarea automată a gramaticii și ortografiei</li>\\n<li>Sugestii de îmbunătățire a stilului</li>\\n<li>Optimizări pentru readability</li>\\n<li>Tone adjustment (formal, casual, profesional)</li>\\n</ul>\\n\\n<h4>3. SEO AI Assistant</h4>\\n<p>Optimizare SEO automatizată:</p>\\n<ul>\\n<li>Keyword research și density analysis</li>\\n<li>Content scoring în timp real</li>\\n<li>Meta tags optimization</li>\\n<li>Internal linking suggestions</li>\\n<li>Schema markup generation</li>\\n</ul>\\n\\n<h3>🎨 AI Image Generator</h3>\\n<p>Generarea de imagini cu DALL-E și Stable Diffusion:</p>\\n<ul>\\n<li>Featured images pentru articole</li>\\n<li>Thumbnails personalizate</li>\\n<li>Social media graphics</li>\\n<li>Product images conceptuale</li>\\n</ul>\\n\\n<h3>📝 AI Writing Assistant</h3>\\n<p>Asistent de scriere inteligent care te ajută să:</p>\\n\\n<h4>Completare Automată</h4>\\n<ul>\\n<li>Predictive text în timp ce scrii</li>\\n<li>Continuarea automată a paragrafelor</li>\\n<li>Sugestii de fraze relevante</li>\\n</ul>\\n\\n<h4>Rewriting și Parafrazare</h4>\\n<ul>\\n<li>Rescrierea conținutului pentru unicitate</li>\\n<li>Schimbarea tonului și stilului</li>\\n<li>Simplificarea sau complexificarea textului</li>\\n</ul>\\n\\n<h3>🔍 AI Search și Discovery</h3>\\n<p>Căutare inteligentă cu înțelegere semantică:</p>\\n<ul>\\n<li>Natural language search</li>\\n<li>Rezultate relevante bazate pe intent</li>\\n<li>Auto-complete inteligent</li>\\n<li>Related content suggestions</li>\\n</ul>\\n\\n<h3>🎯 Personalizare AI</h3>\\n<p>Experiență personalizată pentru fiecare utilizator:</p>\\n<ul>\\n<li>Content recommendations bazate pe comportament</li>\\n<li>Dynamic layouts adaptate la preferințe</li>\\n<li>Predictive navigation</li>\\n<li>A/B testing automatizat</li>\\n</ul>\\n\\n<h3>📊 AI Analytics</h3>\\n<p>Analiză inteligentă a datelor:</p>\\n<ul>\\n<li>Predictive analytics pentru traffic</li>\\n<li>Anomaly detection</li>\\n<li>Trend identification</li>\\n<li>Automatic reporting</li>\\n</ul>\\n\\n<h3>🔒 AI Security</h3>\\n<p>Securitate îmbunătățită prin AI:</p>\\n<ul>\\n<li>Spam detection automatizat</li>\\n<li>Malware scanning</li>\\n<li>Suspicious activity detection</li>\\n<li>Fraud prevention</li>\\n</ul>\\n\\n<h3>💬 AI Chatbot Integration</h3>\\n<p>Chatbot inteligent pentru suport:</p>\\n<ul>\\n<li>Răspunsuri automate la întrebări comune</li>\\n<li>Natural language understanding</li>\\n<li>Context awareness</li>\\n<li>Escalation la human support când e nevoie</li>\\n</ul>\\n\\n<h3>🌐 AI Translation</h3>\\n<p>Traducere automată de calitate:</p>\\n<ul>\\n<li>Multi-language support</li>\\n<li>Context-aware translations</li>\\n<li>Automatic language detection</li>\\n<li>Cultural adaptation</li>\\n</ul>\\n\\n<h3>⚙️ Configurare AI</h3>\\n<p>Setări flexibile pentru AI:</p>\\n<ul>\\n<li>API keys pentru OpenAI, Anthropic</li>\\n<li>Tone și style preferences</li>\\n<li>Content length limits</li>\\n<li>Quality thresholds</li>\\n</ul>\\n\\n<h3>📈 ROI cu AI</h3>\\n<p>Beneficii măsurabile:</p>\\n<ul>\\n<li><strong>60% reducere</strong> în timpul de creare conținut</li>\\n<li><strong>40% creștere</strong> în productivitate</li>\\n<li><strong>80% automatizare</strong> a taskurilor repetitive</li>\\n<li><strong>3x mai mult</strong> conținut publicat</li>\\n</ul>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>AI în CMS Carpathian nu este un gimmick - este un tool real care economisește timp, îmbunătățește calitatea conținutului și oferă insights valoroase. Începe să folosești AI astăzi și vezi diferența!</p>\"}','{\"en\": \"Explore AI features integrated in Carpathian CMS for automatic content generation, SEO optimization and personalization.\", \"ro\": \"Explorează funcționalitățile AI integrate în CMS Carpathian pentru generarea automată de conținut, optimizare SEO și personalizare.\"}','images/blog/ai-integration.jpg','published',1,3,NULL,NULL,NULL,NULL,55,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-23 14:19:10',NULL),(7,'{\"en\": \"Advanced Security: Complete Data Protection in CMS\", \"ro\": \"Securitate Avansată: Protecția Completă a Datelor în CMS\"}','securitate-avansata-protectie-date-cms','{\"en\": \"Security content...\", \"ro\": \"<h2>Securitate de Nivel Enterprise</h2>\\n<p>Securitatea nu este opțională - este fundamentală. CMS Carpathian implementează cele mai stricte măsuri de securitate pentru protecția datelor tale.</p>\\n\\n<h3>🔒 Măsuri de Securitate Implementate</h3>\\n\\n<h4>1. SSL/TLS Encryption</h4>\\n<ul>\\n<li>Certificat SSL gratuit (Let\'s Encrypt)</li>\\n<li>TLS 1.3 pentru conexiuni sigure</li>\\n<li>HTTPS forțat pe toate paginile</li>\\n<li>HSTS (HTTP Strict Transport Security)</li>\\n</ul>\\n\\n<h4>2. Web Application Firewall (WAF)</h4>\\n<p>Protecție împotriva atacurilor comune:</p>\\n<ul>\\n<li>SQL Injection prevention</li>\\n<li>XSS (Cross-Site Scripting) protection</li>\\n<li>CSRF (Cross-Site Request Forgery) tokens</li>\\n<li>DDoS mitigation</li>\\n<li>Brute force protection</li>\\n</ul>\\n\\n<h4>3. Authentication & Authorization</h4>\\n<ul>\\n<li><strong>Two-Factor Authentication (2FA)</strong> - Google Authenticator, SMS</li>\\n<li><strong>Strong Password Policy</strong> - Minimum 12 caractere</li>\\n<li><strong>Role-Based Access Control</strong> - Permisiuni granulare</li>\\n<li><strong>Session Management</strong> - Timeout automat, session hijacking prevention</li>\\n</ul>\\n\\n<h3>💾 Backup Automat</h3>\\n<p>Backup-uri regulate și sigure:</p>\\n<ul>\\n<li><strong>Daily backups</strong> - Automate la ora 2 AM</li>\\n<li><strong>Incremental backups</strong> - Doar modificările noi</li>\\n<li><strong>Off-site storage</strong> - AWS S3, Google Cloud</li>\\n<li><strong>Retention policy</strong> - 30 zile istorice</li>\\n<li><strong>One-click restore</strong> - Restaurare rapidă</li>\\n</ul>\\n\\n<h3>🛡️ Data Protection</h3>\\n<p>Protecția datelor personale (GDPR compliant):</p>\\n<ul>\\n<li>Encryption at rest pentru database</li>\\n<li>Encryption in transit pentru toate comunicările</li>\\n<li>Data anonymization tools</li>\\n<li>Right to be forgotten implementation</li>\\n<li>Privacy policy generator</li>\\n</ul>\\n\\n<h3>🔐 Password Security</h3>\\n<p>Gestionarea sigură a parolelor:</p>\\n<ul>\\n<li>Bcrypt hashing cu cost factor 12</li>\\n<li>Salt unic pentru fiecare parolă</li>\\n<li>Password strength meter</li>\\n<li>Mandatory password changes</li>\\n<li>Password reset via email cu token</li>\\n</ul>\\n\\n<h3>📝 Audit Logging</h3>\\n<p>Tracking complet al activităților:</p>\\n<ul>\\n<li>Login/logout events</li>\\n<li>Content modifications</li>\\n<li>User management actions</li>\\n<li>Failed authentication attempts</li>\\n<li>System configuration changes</li>\\n</ul>\\n\\n<h3>🔍 Vulnerability Scanning</h3>\\n<p>Scanare automată pentru vulnerabilități:</p>\\n<ul>\\n<li>Dependency checking (composer outdated)</li>\\n<li>Security advisories monitoring</li>\\n<li>Automated patching când e posibil</li>\\n<li>Monthly security audits</li>\\n</ul>\\n\\n<h3>🚨 Intrusion Detection</h3>\\n<p>Detectarea și răspunsul la atacuri:</p>\\n<ul>\\n<li>Real-time monitoring</li>\\n<li>Suspicious activity alerts</li>\\n<li>Automatic IP blocking</li>\\n<li>Incident response plan</li>\\n</ul>\\n\\n<h3>📊 Security Dashboard</h3>\\n<p>Monitorizarea centralizată a securității:</p>\\n<ul>\\n<li>Security score overview</li>\\n<li>Recent security events</li>\\n<li>Vulnerability status</li>\\n<li>Compliance checks</li>\\n</ul>\\n\\n<h3>✅ Best Practices</h3>\\n<p>Recomandări pentru securitate maximă:</p>\\n<ol>\\n<li>Activează 2FA pentru toți adminii</li>\\n<li>Folosește parole puternice, unice</li>\\n<li>Actualizează regular CMS-ul</li>\\n<li>Limitează accesul SSH</li>\\n<li>Verifică log-urile periodic</li>\\n<li>Testează restore-ul din backup lunar</li>\\n</ol>\\n\\n<h3>🔒 Concluzie</h3>\\n<p>CMS Carpathian ia securitatea în serios, oferind protecție completă împotriva amenințărilor moderne. Datele tale sunt în siguranță!</p>\"}','{\"en\": \"How Carpathian CMS ensures maximum security through SSL, firewall, automatic backup and best security practices.\", \"ro\": \"Cum CMS Carpathian asigură securitatea maximă prin SSL, firewall, backup automat și cele mai bune practici de securitate.\"}','images/blog/securitate.jpg','published',1,4,NULL,NULL,NULL,NULL,4,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 13:47:11',NULL),(8,'{\"en\": \"Multi-Platform: Perfect Performance on Any Device\", \"ro\": \"Multi-Platform: Funcționare Perfectă pe Orice Dispozitiv\"}','multi-platform-functionare-orice-dispozitiv','{\"en\": \"Multi-platform content...\", \"ro\": \"<h2>Design Responsive și Multi-Device</h2>\\n<p>În lumea modernă, utilizatorii accesează web-ul de pe diverse dispozitive. CMS Carpathian este construit să ofere experiență perfectă pe orice ecran.</p>\\n\\n<h3>📱 Responsive Design</h3>\\n<p>Layout-ul se adaptează automat la orice dimensiune de ecran:</p>\\n\\n<h4>Breakpoints Inteligente</h4>\\n<ul>\\n<li><strong>Mobile Small</strong> (320px - 374px) - iPhone SE, Android compact</li>\\n<li><strong>Mobile</strong> (375px - 767px) - iPhone, Samsung Galaxy</li>\\n<li><strong>Tablet</strong> (768px - 1023px) - iPad, Android tablets</li>\\n<li><strong>Desktop</strong> (1024px - 1439px) - Laptops, monitoare</li>\\n<li><strong>Large Desktop</strong> (1440px+) - Monitoare mari, 4K</li>\\n</ul>\\n\\n<h4>Mobile-First Approach</h4>\\n<p>Dezvoltare pornind de la mobil:</p>\\n<ul>\\n<li>Design optimizat inițial pentru mobil</li>\\n<li>Progressive enhancement pentru ecrane mari</li>\\n<li>Touch-friendly interfaces</li>\\n<li>Gesturi intuitive (swipe, pinch-to-zoom)</li>\\n</ul>\\n\\n<h3>💻 Cross-Browser Compatibility</h3>\\n<p>Funcționare perfectă pe toate browserele:</p>\\n<ul>\\n<li><strong>Chrome</strong> - Desktop și mobile</li>\\n<li><strong>Safari</strong> - macOS și iOS</li>\\n<li><strong>Firefox</strong> - Desktop și mobile</li>\\n<li><strong>Edge</strong> - Windows, mobile</li>\\n<li><strong>Opera</strong> - Desktop și mobile</li>\\n</ul>\\n\\n<h3>🚀 Progressive Web App (PWA)</h3>\\n<p>Funcționalități native pe web:</p>\\n\\n<h4>Install to Home Screen</h4>\\n<ul>\\n<li>Iconița pe home screen ca o aplicație nativă</li>\\n<li>Splash screen personalizat</li>\\n<li>Standalone mode fără browser UI</li>\\n</ul>\\n\\n<h4>Offline Support</h4>\\n<ul>\\n<li>Service Worker pentru caching</li>\\n<li>Funcționare offline pentru conținut vizitat</li>\\n<li>Background sync când revii online</li>\\n</ul>\\n\\n<h4>Push Notifications</h4>\\n<ul>\\n<li>Notificări pentru conținut nou</li>\\n<li>Alerts pentru comentarii</li>\\n<li>Custom notifications pentru evenimente</li>\\n</ul>\\n\\n<h3>⚡ Performance pe Mobile</h3>\\n<p>Optimizări speciale pentru dispozitive mobile:</p>\\n<ul>\\n<li>Imagini responsive cu srcset</li>\\n<li>Lazy loading pentru imagini și video</li>\\n<li>Minificare agresivă CSS/JS</li>\\n<li>Reducerea request-urilor HTTP</li>\\n<li>Critical CSS inline</li>\\n</ul>\\n\\n<h3>🎨 Adaptive UI Components</h3>\\n<p>Componente care se adaptează la context:</p>\\n\\n<h4>Navigation</h4>\\n<ul>\\n<li><strong>Desktop</strong>: Horizontal menu cu dropdown</li>\\n<li><strong>Tablet</strong>: Hybrid menu cu toggle</li>\\n<li><strong>Mobile</strong>: Hamburger menu cu overlay</li>\\n</ul>\\n\\n<h4>Forms</h4>\\n<ul>\\n<li>Input types optimizate pentru mobile (tel, email, date)</li>\\n<li>Large touch targets (minimum 44x44px)</li>\\n<li>Auto-complete și suggestions</li>\\n<li>Inline validation</li>\\n</ul>\\n\\n<h4>Tables</h4>\\n<ul>\\n<li><strong>Desktop</strong>: Full table layout</li>\\n<li><strong>Tablet</strong>: Horizontal scroll</li>\\n<li><strong>Mobile</strong>: Card layout sau accordion</li>\\n</ul>\\n\\n<h3>🖱️ Touch și Keyboard Support</h3>\\n<p>Input methods multiple:</p>\\n<ul>\\n<li>Touch gestures (tap, swipe, pinch)</li>\\n<li>Keyboard shortcuts pentru power users</li>\\n<li>Mouse hover effects pe desktop</li>\\n<li>Accessibility support (ARIA labels)</li>\\n</ul>\\n\\n<h3>📊 Device Analytics</h3>\\n<p>Înțelegerea utilizatorilor tăi:</p>\\n<ul>\\n<li>Device breakdown (mobile vs desktop vs tablet)</li>\\n<li>Browser statistics</li>\\n<li>Screen resolution data</li>\\n<li>OS distribution</li>\\n</ul>\\n\\n<h3>🔧 Testing pe Dispozitive</h3>\\n<p>Asigurarea calității pe toate platformele:</p>\\n<ul>\\n<li>BrowserStack pentru cross-browser testing</li>\\n<li>Device emulators în Chrome DevTools</li>\\n<li>Real device testing</li>\\n<li>Automated visual regression tests</li>\\n</ul>\\n\\n<h3>📱 Native App Feel</h3>\\n<p>Experiență apropiată de aplicații native:</p>\\n<ul>\\n<li>Smooth animations (60fps)</li>\\n<li>Instant feedback la interacțiuni</li>\\n<li>Native-like transitions</li>\\n<li>Bottom navigation pe mobile</li>\\n</ul>\\n\\n<h3>🌐 International Support</h3>\\n<p>Adaptat pentru piețe globale:</p>\\n<ul>\\n<li>RTL support pentru limbi arabe</li>\\n<li>Date și time formats locale</li>\\n<li>Currency și number formatting</li>\\n<li>Timezone handling</li>\\n</ul>\\n\\n<h3>✅ Best Practices</h3>\\n<ol>\\n<li>Testează pe dispozitive reale, nu doar emulatoare</li>\\n<li>Optimizează imaginile pentru mobile (WebP, compression)</li>\\n<li>Folosește viewport meta tag corect</li>\\n<li>Evită hover-only interactions</li>\\n<li>Testează cu conexiuni lente (3G throttling)</li>\\n</ol>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>CMS Carpathian oferă experiență excepțională pe orice dispozitiv, asigurându-se că utilizatorii tăi au acces optim indiferent unde se află!</p>\"}','{\"en\": \"Discover how Carpathian CMS provides optimal experience on desktop, tablet and mobile through responsive design and PWA.\", \"ro\": \"Descoperă cum CMS Carpathian oferă experiență optimă pe desktop, tabletă și mobil prin responsive design și PWA.\"}','images/blog/multi-platform.jpg','published',1,2,NULL,NULL,NULL,NULL,10,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 18:36:26',NULL),(9,'{\"en\": \"Complete Customization: Configure CMS According to Your Needs\", \"ro\": \"Personalizare Completă: Configurează CMS-ul După Nevoile Tale\"}','personalizare-completa-configurare-cms','{\"en\": \"Customization content...\", \"ro\": \"<h2>Personalizare Fără Limite</h2>\\n<p>Fiecare business este unic, iar CMS-ul tău ar trebui să reflecte asta. CMS Carpathian oferă opțiuni complete de personalizare pentru orice nevoie.</p>\\n\\n<h3>🎨 Theme Customization</h3>\\n<p>Personalizează aspectul visual complet:</p>\\n\\n<h4>Color Schemes</h4>\\n<ul>\\n<li><strong>Primary Color</strong> - Culoarea principală a brand-ului</li>\\n<li><strong>Secondary Color</strong> - Culoare complementară</li>\\n<li><strong>Accent Colors</strong> - Pentru highlight-uri și CTA-uri</li>\\n<li><strong>Dark Mode</strong> - Paletă întunecată automată</li>\\n<li><strong>Custom Gradients</strong> - Gradient builder vizual</li>\\n</ul>\\n\\n<h4>Typography</h4>\\n<ul>\\n<li>Google Fonts integration (1000+ fonturi)</li>\\n<li>Custom font upload (OTF, TTF, WOFF)</li>\\n<li>Font pairing suggestions</li>\\n<li>Font size și spacing control</li>\\n<li>Heading și body font separate</li>\\n</ul>\\n\\n<h4>Layout Options</h4>\\n<ul>\\n<li><strong>Boxed vs Full Width</strong></li>\\n<li><strong>Sidebar Position</strong> (left, right, none)</li>\\n<li><strong>Header Styles</strong> (sticky, transparent, minimal)</li>\\n<li><strong>Footer Layouts</strong> (columns, mega footer)</li>\\n</ul>\\n\\n<h3>🧩 Widget System</h3>\\n<p>Construiește pagini cu drag-and-drop:</p>\\n\\n<h4>Available Widgets</h4>\\n<ul>\\n<li><strong>Hero Section</strong> - Banner principal cu CTA</li>\\n<li><strong>Features Grid</strong> - Prezentarea caracteristicilor</li>\\n<li><strong>Blog Posts</strong> - Ultimele articole</li>\\n<li><strong>Products Showcase</strong> - Produse din shop</li>\\n<li><strong>Testimonials</strong> - Recenzii clienți</li>\\n<li><strong>Contact Form</strong> - Formular personalizabil</li>\\n<li><strong>Gallery</strong> - Galerie foto/video</li>\\n<li><strong>FAQ Accordion</strong> - Întrebări frecvente</li>\\n<li><strong>Team Members</strong> - Echipa ta</li>\\n<li><strong>Pricing Tables</strong> - Planuri și prețuri</li>\\n</ul>\\n\\n<h4>Widget Settings</h4>\\n<ul>\\n<li>Background (solid, gradient, image, video)</li>\\n<li>Padding și margins personalizabile</li>\\n<li>Animation effects</li>\\n<li>Visibility rules (show/hide pe dispozitive)</li>\\n</ul>\\n\\n<h3>📄 Page Templates</h3>\\n<p>Template-uri pre-built pentru diverse scenarii:</p>\\n<ul>\\n<li><strong>Homepage</strong> - Landing page cu CTA</li>\\n<li><strong>About Us</strong> - Despre companie</li>\\n<li><strong>Services</strong> - Serviciile oferite</li>\\n<li><strong>Portfolio</strong> - Proiecte realizate</li>\\n<li><strong>Contact</strong> - Pagină de contact</li>\\n<li><strong>Blog</strong> - Index articole</li>\\n<li><strong>Shop</strong> - Magazin online</li>\\n</ul>\\n\\n<h3>🔧 Advanced Settings</h3>\\n\\n<h4>SEO Configuration</h4>\\n<ul>\\n<li>Meta titles și descriptions</li>\\n<li>Open Graph tags pentru social media</li>\\n<li>Schema.org markup</li>\\n<li>XML sitemap auto-generated</li>\\n<li>Robots.txt editor</li>\\n</ul>\\n\\n<h4>Performance Settings</h4>\\n<ul>\\n<li>Cache TTL configuration</li>\\n<li>Image optimization quality</li>\\n<li>CDN integration</li>\\n<li>Lazy loading options</li>\\n</ul>\\n\\n<h4>Security Options</h4>\\n<ul>\\n<li>2FA mandatory pentru roles</li>\\n<li>IP whitelist/blacklist</li>\\n<li>Login attempt limits</li>\\n<li>Session timeout duration</li>\\n</ul>\\n\\n<h3>🎯 Custom Post Types</h3>\\n<p>Creează tipuri de conținut personalizate:</p>\\n<ul>\\n<li>Custom fields builder</li>\\n<li>Field types (text, number, date, image, etc)</li>\\n<li>Taxonomies și tags</li>\\n<li>Custom archive pages</li>\\n</ul>\\n\\n<h3>🔌 Plugin System</h3>\\n<p>Extinde funcționalitatea cu plugin-uri:</p>\\n<ul>\\n<li><strong>E-commerce</strong> - Shop complet cu plăți</li>\\n<li><strong>Forms</strong> - Form builder avansat</li>\\n<li><strong>Newsletter</strong> - Email marketing integration</li>\\n<li><strong>Analytics</strong> - Google Analytics, Facebook Pixel</li>\\n<li><strong>Social Login</strong> - Login cu Facebook, Google</li>\\n</ul>\\n\\n<h3>�� User Roles & Permissions</h3>\\n<p>Control granular al accesului:</p>\\n<ul>\\n<li><strong>Administrator</strong> - Acces complet</li>\\n<li><strong>Editor</strong> - Gestionare conținut</li>\\n<li><strong>Author</strong> - Creare articole proprii</li>\\n<li><strong>Contributor</strong> - Submit pentru review</li>\\n<li><strong>Subscriber</strong> - Acces read-only</li>\\n<li><strong>Custom Roles</strong> - Creează role personalizate</li>\\n</ul>\\n\\n<h3>🌐 Multi-Language Support</h3>\\n<p>Site-uri multilingve:</p>\\n<ul>\\n<li>Unlimited languages</li>\\n<li>Per-page language selection</li>\\n<li>Automatic translation via AI</li>\\n<li>RTL support</li>\\n<li>Language switcher widget</li>\\n</ul>\\n\\n<h3>📊 Custom Dashboard</h3>\\n<p>Dashboard personalizat pentru fiecare user:</p>\\n<ul>\\n<li>Drag-and-drop dashboard widgets</li>\\n<li>Custom metrics și KPIs</li>\\n<li>Quick actions shortcuts</li>\\n<li>Recent activity feed</li>\\n</ul>\\n\\n<h3>🔗 API & Webhooks</h3>\\n<p>Integrări cu servicii externe:</p>\\n<ul>\\n<li>REST API pentru toate resursele</li>\\n<li>Webhook triggers pentru events</li>\\n<li>OAuth2 authentication</li>\\n<li>API rate limiting</li>\\n</ul>\\n\\n<h3>💡 Pro Tips</h3>\\n<ol>\\n<li>Începe cu un template și customizează gradual</li>\\n<li>Folosește preview mode pentru a testa modificările</li>\\n<li>Salvează setările ca presets pentru reutilizare</li>\\n<li>Documentează customizările tale</li>\\n<li>Testează pe multiple dispozitive după fiecare modificare</li>\\n</ol>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>CMS Carpathian oferă flexibilitate completă pentru a crea exact site-ul pe care îl vizualizezi. De la culori și fonturi până la funcționalități complexe - totul este personalizabil!</p>\"}','{\"en\": \"Complete guide for customizing Carpathian CMS: themes, widgets, layouts and advanced settings for the perfect site.\", \"ro\": \"Ghid complet pentru personalizarea CMS Carpathian: teme, widget-uri, layout-uri și setări avansate pentru site-ul perfect.\"}','images/blog/personalizare.jpg','published',1,2,NULL,NULL,NULL,NULL,6,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 13:47:25',NULL),(10,'{\"en\": \"Complete Guide: Web Design and Development in 2025\", \"ro\": \"Ghid Complet: Web Design și Dezvoltare Web în 2025\"}','ghid-complet-web-design-dezvoltare-2025','{\"en\": \"<h2>Why do you need a professional website?</h2><p>In the digital era, online presence is no longer optional. A professional website is your business card in the virtual world, working 24/7 to attract and convert potential customers.</p><h3>Premium Landing Page</h3><p>A <strong>Landing Page</strong> is a web page focused on a single action. Its the perfect tool for marketing campaigns, optimized for conversion using the AIDA structure.</p><h3>Standard Website</h3><p>Includes up to 5 essential pages: Home, About Us, Services, Portfolio, and Contact. Perfect for SMEs wanting a complete digital presence.</p><h3>Professional Blog</h3><p>Build authority in your field with an SEO-optimized blog. Attract organic traffic and convert visitors into loyal subscribers.</p><h3>Corporate Portal</h3><p>Enterprise solution for large companies. Includes advanced modules such as Catalog, Careers, Download Center, and multilingual structure.</p>\", \"ro\": \"<h2>De ce ai nevoie de un site web profesional?</h2><p>În era digitală, prezența online nu mai este opțională. Un site web profesional este cartea ta de vizită în mediul virtual, funcționând 24/7 pentru a atrage și converti potențiali clienți.</p><h3>Landing Page Premium</h3><p>Un <strong>Landing Page</strong> este o pagină web focalizată pe o singură acțiune. Este instrumentul perfect pentru campanii de marketing, optimizat pentru conversie folosind structura AIDA (Attention, Interest, Desire, Action).</p><h3>Website Standard</h3><p>Include până la 5 pagini esențiale: Acasă, Despre Noi, Servicii, Portofoliu și Contact. Perfect pentru IMM-uri care vor o prezență digitală completă.</p><h3>Blog Profesional</h3><p>Construiește autoritate în domeniul tău cu un blog optimizat SEO. Atrage trafic organic și transformă vizitatorii în abonați fideli.</p><h3>Portal Corporate</h3><p>Soluție enterprise pentru companii mari. Include module avansate precum Catalog, Cariere, Download Center și structură multilingvă.</p>\"}','{\"en\": \"Discover everything you need to know about creating a professional website in 2025. From landing pages to corporate portals.\", \"ro\": \"Descoperă tot ce trebuie să știi despre crearea unui site web profesional în 2025. De la landing pages la portaluri corporate.\"}',NULL,'published',1,4,NULL,NULL,NULL,NULL,6,1,1,NULL,'2025-12-21 09:00:54','2025-12-21 09:00:54','2025-12-23 11:03:48',NULL),(11,'{\"en\": \"How to Launch a Successful Online Store in Romania\", \"ro\": \"Cum să Lansezi un Magazin Online de Succes în România\"}','cum-sa-lansezi-magazin-online-succes-romania','{\"en\": \"<h2>E-Commerce in Romania: Opportunities and Challenges</h2><p>The e-commerce market in Romania has grown exponentially in recent years. With over 10 million online shoppers, the perfect time to open your virtual store is NOW.</p><h3>What Complete E-Commerce Solution Includes</h3><ul><li><strong>Payment Processor Integration</strong> - Accept payments via card, PayPal or bank transfer</li><li><strong>Courier Integration</strong> - Automatic AWB generation for main Romanian couriers</li><li><strong>Automatic Invoicing</strong> - Issue invoices automatically with each order</li><li><strong>Legal Pages</strong> - GDPR, Terms and Conditions, Return Policy</li></ul><h3>WooCommerce Advantages</h3><p>We use WooCommerce because it gives you full ownership of your data, with no monthly platform fees. You are independent from marketplaces and have full control.</p>\", \"ro\": \"<h2>E-Commerce în România: Oportunități și Provocări</h2><p>Piața de e-commerce din România a crescut exponențial în ultimii ani. Cu peste 10 milioane de cumpărători online, momentul perfect pentru a-ți deschide magazinul virtual este ACUM.</p><h3>Ce Include Soluția E-Commerce Complete</h3><ul><li><strong>Integrare Procesator Plăți</strong> - Acceptă plăți prin card, PayPal sau transfer bancar</li><li><strong>Integrare Curierat</strong> - Generare automată AWB pentru principalii curieri din România</li><li><strong>Facturare Automată</strong> - Emite facturi automat la fiecare comandă</li><li><strong>Pagini Legale</strong> - GDPR, Termeni și Condiții, Politica de Returnare</li></ul><h3>Avantajele WooCommerce</h3><p>Folosim WooCommerce pentru că îți oferă proprietate totală asupra datelor, fără comisioane lunare de platformă. Ești independent de marketplace-uri și ai control complet.</p>\"}','{\"en\": \"Step-by-step guide to creating a profitable online store. From choosing the platform to payment and courier integration.\", \"ro\": \"Ghid pas cu pas pentru crearea unui magazin online profitabil. De la alegerea platformei la integrarea plăților și curieratului.\"}',NULL,'published',1,4,NULL,NULL,NULL,NULL,12,1,1,NULL,'2025-12-21 09:01:15','2025-12-21 09:01:15','2025-12-23 11:49:31',NULL),(12,'{\"en\": \"Artificial Intelligence in Business: Chatbots and Automation\", \"ro\": \"Inteligența Artificială în Afaceri: Chatboți și Automatizări\"}','inteligenta-artificiala-afaceri-chatboti-automatizari','{\"en\": \"<h2>The AI Revolution in Business</h2><p>Artificial Intelligence is no longer just for tech giants. Today, any business can benefit from AI power to automate processes and improve services.</p><h3>AI Chatbot Agent</h3><p>A modern <strong>AI Agent</strong> understands natural language and responds based on your business-specific information. Its not about predefined answers anymore, but real, intelligent conversations.</p><p><strong>Benefits:</strong></p><ul><li>24/7 customer support without additional staff costs</li><li>Instant answers to frequently asked questions</li><li>Automatic lead qualification</li><li>Reduced response time</li></ul><h3>Workflow Automation</h3><p>Eliminate Copy-Paste! We automate repetitive processes between Excel files or web applications using Python or No-Code solutions.</p>\", \"ro\": \"<h2>Revoluția AI în Business</h2><p>Inteligența Artificială nu mai este doar pentru companiile tech gigant. Astăzi, orice afacere poate beneficia de puterea AI pentru a automatiza procese și a îmbunătăți serviciile.</p><h3>Agent AI Chatbot</h3><p>Un <strong>Agent AI</strong> modern înțelege limbajul natural și răspunde pe baza informațiilor specifice afacerii tale. Nu mai este vorba de răspunsuri predefinite, ci de conversații reale, inteligente.</p><p><strong>Beneficii:</strong></p><ul><li>Suport clienți 24/7 fără costuri suplimentare de personal</li><li>Răspunsuri instant la întrebări frecvente</li><li>Calificare automată a lead-urilor</li><li>Reducerea timpului de răspuns</li></ul><h3>Automatizare Fluxuri de Lucru</h3><p>Scapă de Copy-Paste! Automatizăm procesele repetitive dintre fișierele Excel sau aplicațiile web folosind Python sau soluții No-Code.</p>\"}','{\"en\": \"How AI chatbots and automation can reduce your costs and improve customer experience.\", \"ro\": \"Cum pot chatboții AI și automatizările să îți reducă costurile și să îmbunătățească experiența clienților.\"}',NULL,'published',1,3,NULL,NULL,NULL,NULL,8,1,1,NULL,'2025-12-21 09:01:50','2025-12-21 09:01:50','2025-12-23 14:18:42',NULL),(13,'{\"en\": \"Custom Software Development: From Idea to Application\", \"ro\": \"Dezvoltare Software Custom: De la Idee la Aplicație\"}','dezvoltare-software-custom-idee-aplicatie','{\"en\": \"<h2>When Do You Need Custom Software?</h2><p>If your business processes dont fit into existing solutions, or if you have an innovative idea that requires a dedicated platform, then you need custom development.</p><h3>Custom Web Application (MVP)</h3><p>An <strong>MVP (Minimum Viable Product)</strong> is the essential version of your software, built on solid foundations that allow for future development. It includes:</p><ul><li>Secure user authentication</li><li>Intuitive admin panel</li><li>Up to 3 functional data management modules</li><li>PHP Laravel + MySQL architecture for scalability</li></ul><h3>Mobile Application (iOS & Android)</h3><p>Get into your customers pockets with a modern mobile app. We use Cross-Platform technologies to deliver apps on both operating systems efficiently and at optimized costs.</p>\", \"ro\": \"<h2>Când Ai Nevoie de Software Custom?</h2><p>Dacă procesele tale de business nu se potrivesc în soluțiile existente, sau dacă ai o idee inovatoare care necesită o platformă dedicată, atunci ai nevoie de dezvoltare custom.</p><h3>Aplicație Web Custom (MVP)</h3><p>Un <strong>MVP (Minimum Viable Product)</strong> este versiunea esențială a software-ului tău, construită pe baze solide care permit dezvoltarea ulterioară. Include:</p><ul><li>Autentificare utilizatori securizată</li><li>Panou de administrare intuitiv</li><li>Până la 3 module funcționale de gestiune date</li><li>Arhitectură PHP Laravel + MySQL pentru scalabilitate</li></ul><h3>Aplicație Mobile (iOS & Android)</h3><p>Intră în buzunarul clienților tăi cu o aplicație mobilă modernă. Folosim tehnologii Cross-Platform pentru a livra aplicații pe ambele sisteme de operare eficient și la costuri optimizate.</p>\"}','{\"en\": \"Learn when you need a custom web or mobile app and how to validate your business idea with an MVP.\", \"ro\": \"Află când ai nevoie de o aplicație web sau mobilă custom și cum să îți validezi ideea de business cu un MVP.\"}',NULL,'published',1,4,NULL,NULL,NULL,NULL,7,0,1,NULL,'2025-12-21 09:01:50','2025-12-21 09:01:50','2025-12-23 10:31:30',NULL),(14,'{\"en\": \"The Importance of Branding: Logo and Visual Identity for Business\", \"ro\": \"Importanța Brandingului: Logo și Identitate Vizuală pentru Business\"}','importanta-branding-logo-identitate-vizuala','{\"en\": \"<h2>First Impression Matters</h2><p>In less than 7 seconds, a potential customer forms an opinion about your brand. Professional visual identity creates trust and differentiates you from the competition.</p><h3>Logo Design Start</h3><p>The logo is the core of your visual identity. We offer:</p><ul><li>3 unique graphic concepts created by professional designers</li><li>Files in vector format (SVG, AI) - scalable to any size</li><li>Raster format with transparency (PNG) for web</li><li>Simple usage guide</li></ul><h3>Complete Visual Identity Kit</h3><p>Everything you need to look professional from day one:</p><ul><li>Complete Logo Design</li><li>Official Color Palette with HEX/RGB codes</li><li>Font Selection (Typography)</li><li>Business Card Design ready for print</li><li>Letterhead Design</li><li>Social Media Kit (Facebook covers, LinkedIn, avatars)</li></ul>\", \"ro\": \"<h2>Prima Impresie Contează</h2><p>În mai puțin de 7 secunde, un potențial client își formează o părere despre brandul tău. Identitatea vizuală profesională creează încredere și te diferențiază de competiție.</p><h3>Logo Design Start</h3><p>Logo-ul este nucleul identității tale vizuale. Oferim:</p><ul><li>3 concepte grafice unice create de designeri profesioniști</li><li>Fișiere în format vectorial (SVG, AI) - scalabile la orice dimensiune</li><li>Format raster cu transparență (PNG) pentru web</li><li>Ghid simplu de utilizare</li></ul><h3>Kit Identitate Vizuală Complet</h3><p>Tot ce ai nevoie pentru a arăta profesionist din prima zi:</p><ul><li>Logo Design complet</li><li>Paleta de Culori oficială cu coduri HEX/RGB</li><li>Selecția de Fonturi (Tipografie)</li><li>Design Carte de Vizită gata de print</li><li>Design Foaie cu Antet</li><li>Kit Social Media (coperți Facebook, LinkedIn, avatare)</li></ul>\"}','{\"en\": \"Discover why visual identity is essential for your business success and what a complete branding kit includes.\", \"ro\": \"Descoperă de ce identitatea vizuală este esențială pentru succesul afacerii tale și ce include un kit complet de branding.\"}',NULL,'published',1,4,NULL,NULL,NULL,NULL,7,0,1,NULL,'2025-12-21 09:02:31','2025-12-21 09:02:31','2025-12-23 10:31:24',NULL),(15,'{\"en\": \"Why You Need Maintenance for Your Website\", \"ro\": \"De Ce Ai Nevoie de Mentenanță pentru Site-ul Tău Web\"}','de-ce-ai-nevoie-mentenanta-site-web','{\"en\": \"<h2>Maintenance: The Investment that Saves Money</h2><p>Many companies ignore website maintenance until something stops working. The cost of repair is always higher than the cost of prevention.</p><h3>What Happens Without Maintenance?</h3><ul><li>Outdated plugins = security breaches</li><li>Hacked sites = data and reputation loss</li><li>Progressive slowdown = customer loss</li><li>Non-existent backups = disaster on critical errors</li></ul><h3>Our Maintenance Packages</h3><p><strong>Basic (149 RON/month)</strong> - Passive security and stability</p><p><strong>Standard (349 RON/month)</strong> - Includes 2 hours content management</p><p><strong>Premium (649 RON/month)</strong> - 5 work hours + quarterly SEO audit</p><h3>For Online Stores</h3><p><strong>Shop Starter (899 RON/month)</strong> - For stores under 500 products</p><p><strong>Shop Growth (1999 RON/month)</strong> - Includes phone support and 8-10 development hours</p>\", \"ro\": \"<h2>Mentenanța: Investiția care Salvează Bani</h2><p>Multe companii ignoră mentenanța site-ului până când ceva nu mai funcționează. Costul reparării este întotdeauna mai mare decât costul prevenirii.</p><h3>Ce se Întâmplă Fără Mentenanță?</h3><ul><li>Plugin-uri neactualizate = breșe de securitate</li><li>Site-uri hackuite = pierderi de date și reputație</li><li>Încetinire progresivă = pierderi de clienți</li><li>Backup-uri inexistente = dezastru la erori critice</li></ul><h3>Pachetele Noastre de Mentenanță</h3><p><strong>Basic (149 RON/lună)</strong> - Securitate și stabilitate pasivă</p><p><strong>Standard (349 RON/lună)</strong> - Include 2 ore administrare conținut</p><p><strong>Premium (649 RON/lună)</strong> - 5 ore de lucru + audit SEO trimestrial</p><h3>Pentru Magazine Online</h3><p><strong>Shop Starter (899 RON/lună)</strong> - Pentru magazine sub 500 produse</p><p><strong>Shop Growth (1999 RON/lună)</strong> - Include suport telefonic și 8-10 ore dezvoltare</p>\"}','{\"en\": \"A website without maintenance is like a car without service. Find out what risks you avoid with a professional maintenance package.\", \"ro\": \"Un site web fără mentenanță este ca o mașină fără revizie. Află ce riscuri eviți cu un pachet de mentenanță profesională.\"}',NULL,'published',1,2,NULL,NULL,NULL,NULL,10,0,1,NULL,'2025-12-21 09:02:31','2025-12-21 09:02:31','2025-12-23 10:31:21',NULL),(16,'{\"en\": \"Web Security: Malware Removal and Site Protection\", \"ro\": \"Securitate Web: Devirusare și Protecție Site\"}','securitate-web-devirusare-protectie-site','{\"en\": \"<h2>Cyber Threats in 2025</h2><p>WordPress sites are targeted by over 90,000 attacks per minute. Without security measures, its only a matter of time until youre affected.</p><h3>Professional Malware Removal</h3><p>If your site has been compromised, we offer <strong>professional malware removal</strong> services that include:</p><ul><li>Complete malicious code cleanup</li><li>Identification and patching of breaches</li><li>Assistance for Google Blacklist removal</li><li>Detailed report of vulnerabilities found</li></ul><h3>Speed Optimization</h3><p>A slow site is not only unpleasant for users - it also affects your Google ranking. Our <strong>Speed Boost</strong> service includes:</p><ul><li>Complete performance audit</li><li>Advanced cache configuration</li><li>CSS/JS code minification</li><li>Next-gen image optimization (WebP)</li><li>Target: Green score in Google PageSpeed</li></ul>\", \"ro\": \"<h2>Amenințările Cibernetice în 2025</h2><p>Site-urile WordPress sunt ținta a peste 90.000 de atacuri pe minut. Dacă nu ai măsuri de securitate, este doar o chestiune de timp până când vei fi afectat.</p><h3>Devirusare Profesională</h3><p>Dacă site-ul tău a fost compromis, oferim servicii de <strong>devirusare profesională</strong> care includ:</p><ul><li>Curățare completă a codului malițios</li><li>Identificarea și securizarea breșelor</li><li>Asistență pentru scoaterea din Blacklist Google</li><li>Raport detaliat al vulnerabilităților găsite</li></ul><h3>Optimizare Viteză</h3><p>Un site lent nu este doar neplăcut pentru utilizatori - îți afectează și poziția în Google. Serviciul nostru de <strong>Speed Boost</strong> include:</p><ul><li>Audit complet de performanță</li><li>Configurare cache avansat</li><li>Minificare cod CSS/JS</li><li>Optimizare imagini next-gen (WebP)</li><li>Țintă: Scor verde în Google PageSpeed</li></ul>\"}','{\"en\": \"Complete guide to web security: how to prevent attacks and what to do if your site has been hacked.\", \"ro\": \"Ghid complet despre securitatea web: cum să previi atacurile și ce să faci dacă site-ul tău a fost hackuit.\"}',NULL,'published',1,2,NULL,NULL,NULL,NULL,13,1,1,NULL,'2025-12-21 09:02:56','2025-12-21 09:02:56','2025-12-23 12:27:06',NULL);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_sale_inquiries`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pre_sale_inquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inquiry_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','responded','converted','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_response` text COLLATE utf8mb4_unicode_ci,
  `responded_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pre_sale_inquiries_user_id_foreign` (`user_id`),
  KEY `pre_sale_inquiries_product_id_foreign` (`product_id`),
  KEY `pre_sale_inquiries_order_id_foreign` (`order_id`),
  KEY `pre_sale_inquiries_status_created_at_index` (`status`,`created_at`),
  KEY `pre_sale_inquiries_customer_email_index` (`customer_email`),
  CONSTRAINT `pre_sale_inquiries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pre_sale_inquiries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_sale_inquiries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_sale_inquiries`
--

LOCK TABLES `pre_sale_inquiries` WRITE;
/*!40000 ALTER TABLE `pre_sale_inquiries` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_sale_inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_sale_requests`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pre_sale_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','contacted','responded','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pre_sale_requests_product_id_foreign` (`product_id`),
  KEY `pre_sale_requests_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `pre_sale_requests_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_sale_requests`
--

LOCK TABLES `pre_sale_requests` WRITE;
/*!40000 ALTER TABLE `pre_sale_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_sale_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privacy_policy_acceptances`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `privacy_policy_acceptances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `policy_version` int NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_slug_unique` (`slug`),
  KEY `product_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `product_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `order`, `is_active`, `created_at`, `updated_at`) VALUES (10,'{\"ro\":\"Web Design & Development\",\"en\":\"Web Design & Development\",\"de\":\"Webdesign & Entwicklung\",\"es\":\"Diseño y Desarrollo Web\",\"fr\":\"Design et Développement Web\",\"it\":\"Web Design e Sviluppo\"}','web-design-development','{\"ro\":\"Soluții profesionale de web design și dezvoltare pentru afacerea ta\",\"en\":\"Professional web design and development solutions for your business\",\"de\":\"Professionelle Webdesign- und Entwicklungslösungen für Ihr Unternehmen\",\"es\":\"Soluciones profesionales de diseño y desarrollo web para su negocio\",\"fr\":\"Solutions professionnelles de design et développement web pour votre entreprise\",\"it\":\"Soluzioni professionali di web design e sviluppo per la tua azienda\"}','images/services/cat-web-design.jpg',NULL,1,1,'2025-12-21 08:36:50','2025-12-21 08:38:16'),(11,'{\"ro\":\"E-Commerce\",\"en\":\"E-Commerce\",\"de\":\"E-Commerce\",\"es\":\"Comercio Electrónico\",\"fr\":\"E-Commerce\",\"it\":\"E-Commerce\"}','e-commerce','{\"ro\":\"Magazine online complete și soluții de vânzare digitală\",\"en\":\"Complete online stores and digital sales solutions\",\"de\":\"Komplette Online-Shops und digitale Verkaufslösungen\",\"es\":\"Tiendas online completas y soluciones de venta digital\",\"fr\":\"Boutiques en ligne complètes et solutions de vente numérique\",\"it\":\"Negozi online completi e soluzioni di vendita digitale\"}','images/services/cat-ecommerce.jpg',NULL,2,1,'2025-12-21 08:36:50','2025-12-21 08:38:16'),(12,'{\"ro\":\"Software & Aplicații\",\"en\":\"Software & Applications\",\"de\":\"Software & Anwendungen\",\"es\":\"Software y Aplicaciones\",\"fr\":\"Logiciels et Applications\",\"it\":\"Software e Applicazioni\"}','software-aplicatii','{\"ro\":\"Dezvoltare aplicații web și mobile personalizate\",\"en\":\"Custom web and mobile application development\",\"de\":\"Entwicklung von benutzerdefinierten Web- und Mobilanwendungen\",\"es\":\"Desarrollo de aplicaciones web y móviles personalizadas\",\"fr\":\"Développement d\'applications web et mobiles sur mesure\",\"it\":\"Sviluppo di applicazioni web e mobile personalizzate\"}','images/services/cat-software.jpg',NULL,3,1,'2025-12-21 08:36:50','2025-12-21 08:38:16'),(13,'{\"ro\":\"AI & Automatizare\",\"en\":\"AI & Automation\",\"de\":\"KI & Automatisierung\",\"es\":\"IA y Automatización\",\"fr\":\"IA et Automatisation\",\"it\":\"IA e Automazione\"}','ai-automatizare','{\"ro\":\"Inteligență artificială și automatizări pentru eficiență maximă\",\"en\":\"Artificial intelligence and automation for maximum efficiency\",\"de\":\"Künstliche Intelligenz und Automatisierung für maximale Effizienz\",\"es\":\"Inteligencia artificial y automatización para máxima eficiencia\",\"fr\":\"Intelligence artificielle et automatisation pour une efficacité maximale\",\"it\":\"Intelligenza artificiale e automazione per la massima efficienza\"}','images/services/cat-ai.jpg',NULL,4,1,'2025-12-21 08:36:50','2025-12-21 08:38:16'),(14,'{\"ro\":\"Branding & Identitate\",\"en\":\"Branding & Identity\",\"de\":\"Branding & Identität\",\"es\":\"Branding e Identidad\",\"fr\":\"Branding et Identité\",\"it\":\"Branding e Identità\"}','branding-identitate','{\"ro\":\"Design de logo și identitate vizuală completă\",\"en\":\"Logo design and complete visual identity\",\"de\":\"Logo-Design und vollständige visuelle Identität\",\"es\":\"Diseño de logo e identidad visual completa\",\"fr\":\"Design de logo et identité visuelle complète\",\"it\":\"Design di logo e identità visiva completa\"}','images/services/cat-branding.jpg',NULL,5,1,'2025-12-21 08:36:50','2025-12-21 08:38:16'),(15,'{\"ro\":\"Mentenanță & Administrare\",\"en\":\"Maintenance & Management\",\"de\":\"Wartung & Verwaltung\",\"es\":\"Mantenimiento y Gestión\",\"fr\":\"Maintenance et Gestion\",\"it\":\"Manutenzione e Gestione\"}','mentenanta-administrare','{\"ro\":\"Pachete de mentenanță și administrare conținut pentru site-uri\",\"en\":\"Maintenance and content management packages for websites\",\"de\":\"Wartungs- und Inhaltsverwaltungspakete für Websites\",\"es\":\"Paquetes de mantenimiento y gestión de contenido para sitios web\",\"fr\":\"Forfaits de maintenance et gestion de contenu pour sites web\",\"it\":\"Pacchetti di manutenzione e gestione dei contenuti per siti web\"}','images/services/cat-mentenanta.jpg',NULL,6,1,'2025-12-21 08:36:50','2025-12-21 08:38:16'),(16,'{\"ro\":\"Intervenții Punctuale\",\"en\":\"On-Demand Services\",\"de\":\"Bedarfsservices\",\"es\":\"Servicios Bajo Demanda\",\"fr\":\"Services à la Demande\",\"it\":\"Servizi su Richiesta\"}','interventii-punctuale','{\"ro\":\"Servicii punctuale de devirusare și optimizare\",\"en\":\"One-time malware removal and optimization services\",\"de\":\"Einmalige Malware-Entfernung und Optimierungsdienste\",\"es\":\"Servicios puntuales de eliminación de malware y optimización\",\"fr\":\"Services ponctuels de suppression de malware et optimisation\",\"it\":\"Servizi puntuali di rimozione malware e ottimizzazione\"}','images/services/cat-interventii.jpg',NULL,7,1,'2025-12-21 08:36:50','2025-12-21 08:38:16');
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `rating` int unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributes` json NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `low_stock_threshold` int NOT NULL DEFAULT '5',
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` json DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `name` json DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` json DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` json DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `base_currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RON',
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `meta_title`, `meta_description`, `meta_keywords`, `content`, `sku`, `price`, `base_currency`, `sale_price`, `stock`, `images`, `attributes`, `meta`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (7,NULL,'{\"de\": \"Premium Landing Page\", \"en\": \"Premium Landing Page\", \"es\": \"Landing Page Premium\", \"fr\": \"Page de Destination Premium\", \"it\": \"Landing Page Premium\", \"ro\": \"Landing Page Premium\"}','landing-page-premium','{\"de\": \"<h3>🚀 Premium Landing Page - Maximale Konversionen</h3>\\n<p>Unsere Premium-Landingpage ist optimiert, um Besucher in Kunden zu verwandeln. Modernes, responsives Design mit Fokus auf Aktion.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Individuelles responsives Design (Mobile-First)</li>\\n<li>Hero-, Vorteile-, Testimonial-, CTA-Bereiche</li>\\n<li>Integriertes Kontaktformular</li>\\n<li>On-Page-SEO-Optimierung</li>\\n<li>Google Analytics & Facebook Pixel Integration</li>\\n<li>Moderne Animationen und Effekte</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>HTML5, CSS3/TailwindCSS, JavaScript, PHP/Laravel, Core Web Vitals Optimierung</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Konversionsrate um bis zu 40% erhöht</li>\\n<li>Ladezeit unter 2 Sekunden</li>\\n<li>Kompatibel mit allen Geräten</li>\\n<li>Einfach zu aktualisieren</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Einzelne Seite (keine Unterseiten)</li>\\n<li>Hosting nicht enthalten (optional separat)</li>\\n</ul>\", \"en\": \"<h3>🚀 Premium Landing Page - Maximum Conversions</h3>\\n<p>Our premium landing page is optimized to turn visitors into customers. Modern, responsive design focused on action.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Custom responsive design (mobile-first)</li>\\n<li>Hero, benefits, testimonials, CTA sections</li>\\n<li>Integrated contact form</li>\\n<li>On-page SEO optimization</li>\\n<li>Google Analytics & Facebook Pixel integration</li>\\n<li>Modern animations and effects</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>HTML5, CSS3/TailwindCSS, JavaScript, PHP/Laravel, Core Web Vitals optimization</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Conversion rate increased by up to 40%</li>\\n<li>Loading time under 2 seconds</li>\\n<li>Compatible with all devices</li>\\n<li>Easy to update</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Single page (no subpages)</li>\\n<li>Hosting not included (optional separately)</li>\\n</ul>\", \"es\": \"<h3>🚀 Landing Page Premium - Conversiones Máximas</h3>\\n<p>Nuestra landing page premium está optimizada para convertir visitantes en clientes. Diseño moderno y responsive enfocado en la acción.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Diseño responsive personalizado (mobile-first)</li>\\n<li>Secciones hero, beneficios, testimonios, CTA</li>\\n<li>Formulario de contacto integrado</li>\\n<li>Optimización SEO on-page</li>\\n<li>Integración Google Analytics y Facebook Pixel</li>\\n<li>Animaciones y efectos modernos</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>HTML5, CSS3/TailwindCSS, JavaScript, PHP/Laravel, optimización Core Web Vitals</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Tasa de conversión aumentada hasta un 40%</li>\\n<li>Tiempo de carga inferior a 2 segundos</li>\\n<li>Compatible con todos los dispositivos</li>\\n<li>Fácil de actualizar</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Una sola página (sin subpáginas)</li>\\n<li>Hosting no incluido (opcional por separado)</li>\\n</ul>\", \"fr\": \"<h3>🚀 Page de Destination Premium - Conversions Maximales</h3>\\n<p>Notre page de destination premium est optimisée pour transformer les visiteurs en clients. Design moderne et responsive axé sur l\'action.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Design responsive personnalisé (mobile-first)</li>\\n<li>Sections hero, avantages, témoignages, CTA</li>\\n<li>Formulaire de contact intégré</li>\\n<li>Optimisation SEO on-page</li>\\n<li>Intégration Google Analytics et Facebook Pixel</li>\\n<li>Animations et effets modernes</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>HTML5, CSS3/TailwindCSS, JavaScript, PHP/Laravel, optimisation Core Web Vitals</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Taux de conversion augmenté jusqu\'à 40%</li>\\n<li>Temps de chargement inférieur à 2 secondes</li>\\n<li>Compatible avec tous les appareils</li>\\n<li>Facile à mettre à jour</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Page unique (pas de sous-pages)</li>\\n<li>Hébergement non inclus (optionnel séparément)</li>\\n</ul>\", \"it\": \"<h3>🚀 Landing Page Premium - Massime Conversioni</h3>\\n<p>La nostra landing page premium è ottimizzata per trasformare i visitatori in clienti. Design moderno e responsive focalizzato sull\'azione.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Design responsive personalizzato (mobile-first)</li>\\n<li>Sezioni hero, benefici, testimonial, CTA</li>\\n<li>Modulo di contatto integrato</li>\\n<li>Ottimizzazione SEO on-page</li>\\n<li>Integrazione Google Analytics e Facebook Pixel</li>\\n<li>Animazioni ed effetti moderni</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>HTML5, CSS3/TailwindCSS, JavaScript, PHP/Laravel, ottimizzazione Core Web Vitals</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Tasso di conversione aumentato fino al 40%</li>\\n<li>Tempo di caricamento inferiore a 2 secondi</li>\\n<li>Compatibile con tutti i dispositivi</li>\\n<li>Facile da aggiornare</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Pagina singola (nessuna sottopagina)</li>\\n<li>Hosting non incluso (opzionale separatamente)</li>\\n</ul>\", \"ro\": \"<h3>🚀 Landing Page Premium - Conversii Maxime</h3>\\n<p>Landing page-ul nostru premium este optimizat pentru a transforma vizitatorii în clienți. Design modern, responsive și focusat pe acțiune.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Design custom responsiv (mobile-first)</li>\\n<li>Secțiuni hero, beneficii, testimoniale, CTA</li>\\n<li>Formular de contact integrat</li>\\n<li>Optimizare SEO on-page</li>\\n<li>Integrare Google Analytics & Facebook Pixel</li>\\n<li>Animații și efecte moderne</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>HTML5, CSS3/TailwindCSS, JavaScript, PHP/Laravel, optimizare Core Web Vitals</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Rată de conversie crescută cu până la 40%</li>\\n<li>Timp de încărcare sub 2 secunde</li>\\n<li>Compatibil cu toate dispozitivele</li>\\n<li>Ușor de actualizat</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>O singură pagină (fără subpagini)</li>\\n<li>Nu include hosting (opțional separat)</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<h3>What does Premium Landing Page include?</h3><ul><li>Design 100% focused on Call to Action</li><li>Optimized One-Page structure</li><li>Optimized loading speed</li><li>Google Analytics/Pixel integration</li><li>Responsive design for all devices</li></ul>\", \"ro\": \"<h3>Ce include Landing Page Premium?</h3><ul><li>Design focusat 100% pe Call to Action</li><li>Structură One-Page optimizată</li><li>Viteză de încărcare optimizată</li><li>Integrare Google Analytics/Pixel</li><li>Design responsive pentru toate dispozitivele</li></ul>\"}','WEB-LP-001',799.00,'RON',NULL,999,'\"[\\\"images\\\\/services\\\\/landing-page.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-21 08:55:32','2025-12-21 10:29:51'),(8,NULL,'{\"de\": \"Standard Website\", \"en\": \"Standard Website\", \"es\": \"Sitio Web Estándar\", \"fr\": \"Site Web Standard\", \"it\": \"Sito Web Standard\", \"ro\": \"Website Standard\"}','website-standard','{\"de\": \"<h3>🌐 Standard Website - Ihre professionelle Online-Präsenz</h3>\\n<p>Komplette Website mit bis zu 7 Seiten, perfekt für kleine und mittlere Unternehmen, die eine professionelle und effiziente Online-Präsenz wünschen.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Bis zu 7 Seiten (Startseite, Über uns, Dienstleistungen, Portfolio, Blog, Kontakt, etc.)</li>\\n<li>Modernes, responsives und individuelles Design</li>\\n<li>CMS für einfache Inhaltsverwaltung</li>\\n<li>Kontaktformular mit Anti-Spam-Schutz</li>\\n<li>Vollständige SEO-Optimierung</li>\\n<li>Google Maps, Analytics, Social Media Integration</li>\\n<li>SSL-Zertifikat inklusive</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>Laravel/PHP, MySQL, TailwindCSS, Alpine.js, Filament Admin Panel</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Einfache Verwaltung ohne technische Kenntnisse</li>\\n<li>Skalierbar - kann später erweitert werden</li>\\n<li>Optimierte Leistung (PageSpeed 90+)</li>\\n<li>30 Tage technischer Support inklusive</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>E-Commerce-Funktionen nicht enthalten</li>\\n<li>Inhalte müssen vom Kunden bereitgestellt werden</li>\\n</ul>\", \"en\": \"<h3>🌐 Standard Website - Your Professional Online Presence</h3>\\n<p>Complete website with up to 7 pages, perfect for small and medium businesses wanting a professional and efficient online presence.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Up to 7 pages (Home, About, Services, Portfolio, Blog, Contact, etc.)</li>\\n<li>Modern, responsive, and customized design</li>\\n<li>CMS for easy content management</li>\\n<li>Contact form with anti-spam protection</li>\\n<li>Complete SEO optimization</li>\\n<li>Google Maps, Analytics, social media integration</li>\\n<li>SSL certificate included</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>Laravel/PHP, MySQL, TailwindCSS, Alpine.js, Filament Admin Panel</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Easy management without technical knowledge</li>\\n<li>Scalable - can be extended later</li>\\n<li>Optimized performance (PageSpeed 90+)</li>\\n<li>30-day technical support included</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>E-commerce features not included</li>\\n<li>Content must be provided by the client</li>\\n</ul>\", \"es\": \"<h3>🌐 Sitio Web Estándar - Tu Presencia Online Profesional</h3>\\n<p>Sitio web completo con hasta 7 páginas, perfecto para pequeñas y medianas empresas que desean una presencia online profesional y eficiente.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Hasta 7 páginas (Inicio, Nosotros, Servicios, Portafolio, Blog, Contacto, etc.)</li>\\n<li>Diseño moderno, responsive y personalizado</li>\\n<li>CMS para gestión fácil del contenido</li>\\n<li>Formulario de contacto con protección anti-spam</li>\\n<li>Optimización SEO completa</li>\\n<li>Integración Google Maps, Analytics, redes sociales</li>\\n<li>Certificado SSL incluido</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>Laravel/PHP, MySQL, TailwindCSS, Alpine.js, Filament Admin Panel</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Gestión fácil sin conocimientos técnicos</li>\\n<li>Escalable - puede ampliarse posteriormente</li>\\n<li>Rendimiento optimizado (PageSpeed 90+)</li>\\n<li>Soporte técnico 30 días incluido</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Funcionalidades e-commerce no incluidas</li>\\n<li>El contenido debe ser proporcionado por el cliente</li>\\n</ul>\", \"fr\": \"<h3>🌐 Site Web Standard - Votre Présence en Ligne Professionnelle</h3>\\n<p>Site web complet avec jusqu\'à 7 pages, parfait pour les petites et moyennes entreprises souhaitant une présence en ligne professionnelle et efficace.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Jusqu\'à 7 pages (Accueil, À propos, Services, Portfolio, Blog, Contact, etc.)</li>\\n<li>Design moderne, responsive et personnalisé</li>\\n<li>CMS pour une gestion facile du contenu</li>\\n<li>Formulaire de contact avec protection anti-spam</li>\\n<li>Optimisation SEO complète</li>\\n<li>Intégration Google Maps, Analytics, réseaux sociaux</li>\\n<li>Certificat SSL inclus</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>Laravel/PHP, MySQL, TailwindCSS, Alpine.js, Filament Admin Panel</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Gestion facile sans connaissances techniques</li>\\n<li>Évolutif - peut être étendu ultérieurement</li>\\n<li>Performance optimisée (PageSpeed 90+)</li>\\n<li>Support technique 30 jours inclus</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Fonctionnalités e-commerce non incluses</li>\\n<li>Le contenu doit être fourni par le client</li>\\n</ul>\", \"it\": \"<h3>🌐 Sito Web Standard - La Tua Presenza Online Professionale</h3>\\n<p>Sito web completo con fino a 7 pagine, perfetto per piccole e medie imprese che desiderano una presenza online professionale ed efficiente.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Fino a 7 pagine (Home, Chi siamo, Servizi, Portfolio, Blog, Contatti, ecc.)</li>\\n<li>Design moderno, responsive e personalizzato</li>\\n<li>CMS per una gestione facile dei contenuti</li>\\n<li>Modulo di contatto con protezione anti-spam</li>\\n<li>Ottimizzazione SEO completa</li>\\n<li>Integrazione Google Maps, Analytics, social media</li>\\n<li>Certificato SSL incluso</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>Laravel/PHP, MySQL, TailwindCSS, Alpine.js, Filament Admin Panel</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Gestione facile senza conoscenze tecniche</li>\\n<li>Scalabile - può essere esteso successivamente</li>\\n<li>Prestazioni ottimizzate (PageSpeed 90+)</li>\\n<li>Supporto tecnico 30 giorni incluso</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Funzionalità e-commerce non incluse</li>\\n<li>I contenuti devono essere forniti dal cliente</li>\\n</ul>\", \"ro\": \"<h3>🌐 Website Standard - Prezența Ta Online Profesională</h3>\\n<p>Website complet cu până la 7 pagini, perfect pentru afaceri mici și medii care doresc o prezență online profesională și eficientă.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Până la 7 pagini (Acasă, Despre, Servicii, Portofoliu, Blog, Contact, etc.)</li>\\n<li>Design modern, responsive și personalizat</li>\\n<li>CMS pentru administrare ușoară a conținutului</li>\\n<li>Formular de contact cu protecție anti-spam</li>\\n<li>Optimizare SEO completă</li>\\n<li>Integrare Google Maps, Analytics, rețele sociale</li>\\n<li>Certificat SSL inclus</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>Laravel/PHP, MySQL, TailwindCSS, Alpine.js, Filament Admin Panel</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Administrare facilă fără cunoștințe tehnice</li>\\n<li>Scalabil - poate fi extins ulterior</li>\\n<li>Performanță optimizată (PageSpeed 90+)</li>\\n<li>Suport tehnic 30 de zile inclus</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Funcționalități e-commerce nu sunt incluse</li>\\n<li>Conținutul trebuie furnizat de client</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<h3>What does Standard Website include?</h3><ul><li>Up to 5 pages (Home, About, Services, Portfolio, Contact)</li><li>Basic SEO optimization</li><li>Responsive design</li><li>WordPress CMS for easy management</li><li>Functional contact form</li></ul>\", \"ro\": \"<h3>Ce include Website Standard?</h3><ul><li>Până la 5 pagini (Acasă, Despre, Servicii, Portofoliu, Contact)</li><li>Optimizare SEO de bază</li><li>Design responsive</li><li>CMS WordPress pentru administrare ușoară</li><li>Formular de contact funcțional</li></ul>\"}','WEB-STD-002',1499.00,'RON',NULL,999,'\"[\\\"images\\\\/services\\\\/website-standard.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-21 08:55:48','2025-12-21 10:30:39'),(9,NULL,'{\"de\": \"Professioneller Blog\", \"en\": \"Professional Blog\", \"es\": \"Blog Profesional\", \"fr\": \"Blog Professionnel\", \"it\": \"Blog Professionale\", \"ro\": \"Blog Profesional\"}','blog-profesional','{\"de\": \"<h3>📝 Professioneller Blog - Ihre Content-Marketing-Plattform</h3>\\n<p>Vollständig SEO-optimierter Blog, perfekt für Content-Marketing, Storytelling und den Aufbau einer Online-Community.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Blogging-Plattform mit fortschrittlichem visuellen Editor</li>\\n<li>Kategorien- und Tag-System</li>\\n<li>Kommentare mit Moderation</li>\\n<li>Individuelle Autorenseiten</li>\\n<li>Newsletter-Integration (Mailchimp/SendGrid)</li>\\n<li>Optimiertes Social Sharing</li>\\n<li>Schema-Markup für Artikel</li>\\n<li>Archiv und erweiterte Suche</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>Laravel/PHP, MySQL, TinyMCE Editor, TailwindCSS, RSS Feed, Dynamische XML-Sitemap</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>SEO-optimiert für Google-Ranking</li>\\n<li>Schnelle Ladezeit</li>\\n<li>Intuitiver Artikel-Editor</li>\\n<li>Integrierte Lesestatistiken</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Inhaltserstellung nicht enthalten</li>\\n<li>E-Commerce-Funktionen separat</li>\\n</ul>\", \"en\": \"<h3>📝 Professional Blog - Your Content Marketing Platform</h3>\\n<p>Fully SEO-optimized blog, perfect for content marketing, storytelling, and building an online community.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Blogging platform with advanced visual editor</li>\\n<li>Categories and tags system</li>\\n<li>Comments with moderation</li>\\n<li>Custom author pages</li>\\n<li>Newsletter integration (Mailchimp/SendGrid)</li>\\n<li>Optimized social sharing</li>\\n<li>Schema markup for articles</li>\\n<li>Archive and advanced search</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>Laravel/PHP, MySQL, TinyMCE Editor, TailwindCSS, RSS Feed, Dynamic XML Sitemap</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>SEO optimized for Google ranking</li>\\n<li>Fast loading time</li>\\n<li>Intuitive article editor</li>\\n<li>Integrated reading statistics</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Content writing not included</li>\\n<li>E-commerce features separate</li>\\n</ul>\", \"es\": \"<h3>📝 Blog Profesional - Tu Plataforma de Content Marketing</h3>\\n<p>Blog completamente optimizado para SEO, perfecto para content marketing, storytelling y construcción de comunidad online.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Plataforma de blogging con editor visual avanzado</li>\\n<li>Sistema de categorías y etiquetas</li>\\n<li>Comentarios con moderación</li>\\n<li>Páginas de autor personalizadas</li>\\n<li>Integración newsletter (Mailchimp/SendGrid)</li>\\n<li>Compartir en redes sociales optimizado</li>\\n<li>Schema markup para artículos</li>\\n<li>Archivo y búsqueda avanzada</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>Laravel/PHP, MySQL, TinyMCE Editor, TailwindCSS, RSS Feed, Sitemap XML dinámico</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>SEO optimizado para ranking en Google</li>\\n<li>Tiempo de carga rápido</li>\\n<li>Editor de artículos intuitivo</li>\\n<li>Estadísticas de lectura integradas</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Redacción de contenido no incluida</li>\\n<li>Funcionalidades e-commerce separadas</li>\\n</ul>\", \"fr\": \"<h3>📝 Blog Professionnel - Votre Plateforme de Content Marketing</h3>\\n<p>Blog entièrement optimisé pour le SEO, parfait pour le content marketing, le storytelling et la construction d\'une communauté en ligne.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Plateforme de blogging avec éditeur visuel avancé</li>\\n<li>Système de catégories et tags</li>\\n<li>Commentaires avec modération</li>\\n<li>Pages auteur personnalisées</li>\\n<li>Intégration newsletter (Mailchimp/SendGrid)</li>\\n<li>Partage social optimisé</li>\\n<li>Schema markup pour articles</li>\\n<li>Archives et recherche avancée</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>Laravel/PHP, MySQL, TinyMCE Editor, TailwindCSS, RSS Feed, Sitemap XML dynamique</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>SEO optimisé pour le classement Google</li>\\n<li>Temps de chargement rapide</li>\\n<li>Éditeur d\'articles intuitif</li>\\n<li>Statistiques de lecture intégrées</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Rédaction de contenu non incluse</li>\\n<li>Fonctionnalités e-commerce séparées</li>\\n</ul>\", \"it\": \"<h3>📝 Blog Professionale - La Tua Piattaforma di Content Marketing</h3>\\n<p>Blog completamente ottimizzato per SEO, perfetto per content marketing, storytelling e costruzione di una community online.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Piattaforma di blogging con editor visuale avanzato</li>\\n<li>Sistema di categorie e tag</li>\\n<li>Commenti con moderazione</li>\\n<li>Pagine autore personalizzate</li>\\n<li>Integrazione newsletter (Mailchimp/SendGrid)</li>\\n<li>Condivisione social ottimizzata</li>\\n<li>Schema markup per articoli</li>\\n<li>Archivio e ricerca avanzata</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>Laravel/PHP, MySQL, TinyMCE Editor, TailwindCSS, RSS Feed, Sitemap XML dinamica</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>SEO ottimizzato per ranking Google</li>\\n<li>Tempo di caricamento veloce</li>\\n<li>Editor articoli intuitivo</li>\\n<li>Statistiche di lettura integrate</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Scrittura contenuti non inclusa</li>\\n<li>Funzionalità e-commerce separate</li>\\n</ul>\", \"ro\": \"<h3>📝 Blog Profesional - Platforma Ta de Content Marketing</h3>\\n<p>Blog complet optimizat pentru SEO, perfect pentru content marketing, storytelling și construirea unei comunități online.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Platformă de blogging cu editor vizual avansat</li>\\n<li>Sistem de categorii și taguri</li>\\n<li>Comentarii cu moderare</li>\\n<li>Pagini autor personalizate</li>\\n<li>Newsletter integration (Mailchimp/SendGrid)</li>\\n<li>Social sharing optimizat</li>\\n<li>Schema markup pentru articole</li>\\n<li>Arhivă și căutare avansată</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>Laravel/PHP, MySQL, TinyMCE Editor, TailwindCSS, RSS Feed, Sitemap XML dinamic</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>SEO optimizat pentru ranking în Google</li>\\n<li>Timp de încărcare rapid</li>\\n<li>Editor intuitiv pentru articole</li>\\n<li>Statistici integrate de citire</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Scrierea conținutului nu este inclusă</li>\\n<li>Funcționalități e-commerce separate</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>CMS configuration for quick editing</li><li>Comment system</li><li>Social share buttons</li><li>Spaces for advertising banners or newsletter</li></ul>\", \"ro\": \"<ul><li>Configurare CMS pentru editare rapidă</li><li>Sistem de comentarii</li><li>Butoane de social share</li><li>Spații pentru bannere publicitare sau newsletter</li></ul>\"}','WEB-BLOG-003',999.00,'RON',NULL,999,'\"[\\\"images\\\\/services\\\\/blog-profesional.jpg\\\"]\"',NULL,NULL,0,1,'2025-12-21 08:56:13','2025-12-21 10:31:18'),(10,NULL,'{\"de\": \"Unternehmensportal\", \"en\": \"Corporate Portal\", \"es\": \"Portal Corporativo\", \"fr\": \"Portail Corporate\", \"it\": \"Portale Corporate\", \"ro\": \"Portal Corporate\"}','portal-corporate','{\"de\": \"<h3>🏢 Unternehmensportal - Komplette Enterprise-Lösung</h3>\\n<p>Komplexes Webportal für große Unternehmen mit erweiterten Management-, Intranet- und Unternehmenskommunikationsfunktionen.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Bis zu 25+ individuelle Seiten und Bereiche</li>\\n<li>Benutzerverwaltungssystem mit Rollen</li>\\n<li>Intranet mit Dokumenten und Ressourcen</li>\\n<li>Karriereportal mit Online-Bewerbungen</li>\\n<li>Newsroom und interne Kommunikation</li>\\n<li>CRM/ERP-Integrationen verfügbar</li>\\n<li>Mehrsprachige Unterstützung</li>\\n<li>Individuelle Dashboards</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>Laravel/PHP, MySQL/PostgreSQL, Vue.js/React, REST API, Redis, AWS/Cloud-Hosting</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Enterprise-Skalierbarkeit</li>\\n<li>Erweiterte Sicherheit (2FA, Audit-Logs)</li>\\n<li>Hohe Traffic-Performance</li>\\n<li>Dedizierter Support und Wartung</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Implementierungszeit: 6-12 Wochen</li>\\n<li>Erfordert detaillierte Anforderungsanalyse</li>\\n<li>Enterprise-Hosting empfohlen</li>\\n</ul>\", \"en\": \"<h3>🏢 Corporate Portal - Complete Enterprise Solution</h3>\\n<p>Complex web portal for large companies, with advanced management, intranet, and corporate communication features.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Up to 25+ custom pages and sections</li>\\n<li>User management system with roles</li>\\n<li>Intranet with documents and resources</li>\\n<li>Careers portal with online applications</li>\\n<li>Newsroom and internal communication</li>\\n<li>CRM/ERP integrations available</li>\\n<li>Multi-language support</li>\\n<li>Custom dashboards</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>Laravel/PHP, MySQL/PostgreSQL, Vue.js/React, REST API, Redis, AWS/Cloud hosting</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Enterprise scalability</li>\\n<li>Advanced security (2FA, audit logs)</li>\\n<li>High traffic performance</li>\\n<li>Dedicated support and maintenance</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Implementation time: 6-12 weeks</li>\\n<li>Requires detailed requirements analysis</li>\\n<li>Enterprise hosting recommended</li>\\n</ul>\", \"es\": \"<h3>🏢 Portal Corporativo - Solución Enterprise Completa</h3>\\n<p>Portal web complejo para grandes empresas, con funcionalidades avanzadas de gestión, intranet y comunicación corporativa.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Hasta 25+ páginas y secciones personalizadas</li>\\n<li>Sistema de gestión de usuarios con roles</li>\\n<li>Intranet con documentos y recursos</li>\\n<li>Portal de carreras con solicitudes online</li>\\n<li>Newsroom y comunicación interna</li>\\n<li>Integraciones CRM/ERP disponibles</li>\\n<li>Soporte multiidioma</li>\\n<li>Dashboards personalizados</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>Laravel/PHP, MySQL/PostgreSQL, Vue.js/React, REST API, Redis, AWS/Cloud hosting</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Escalabilidad enterprise</li>\\n<li>Seguridad avanzada (2FA, audit logs)</li>\\n<li>Rendimiento para alto tráfico</li>\\n<li>Soporte y mantenimiento dedicado</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Tiempo de implementación: 6-12 semanas</li>\\n<li>Requiere análisis detallado de requisitos</li>\\n<li>Hosting enterprise recomendado</li>\\n</ul>\", \"fr\": \"<h3>🏢 Portail Corporate - Solution Enterprise Complète</h3>\\n<p>Portail web complexe pour grandes entreprises, avec des fonctionnalités avancées de gestion, intranet et communication corporate.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Jusqu\'à 25+ pages et sections personnalisées</li>\\n<li>Système de gestion des utilisateurs avec rôles</li>\\n<li>Intranet avec documents et ressources</li>\\n<li>Portail carrières avec candidatures en ligne</li>\\n<li>Newsroom et communication interne</li>\\n<li>Intégrations CRM/ERP disponibles</li>\\n<li>Support multilingue</li>\\n<li>Tableaux de bord personnalisés</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>Laravel/PHP, MySQL/PostgreSQL, Vue.js/React, REST API, Redis, AWS/Cloud hosting</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Évolutivité enterprise</li>\\n<li>Sécurité avancée (2FA, journaux d\'audit)</li>\\n<li>Performance haute charge</li>\\n<li>Support et maintenance dédiés</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Délai d\'implémentation: 6-12 semaines</li>\\n<li>Nécessite une analyse détaillée des besoins</li>\\n<li>Hébergement enterprise recommandé</li>\\n</ul>\", \"it\": \"<h3>🏢 Portale Corporate - Soluzione Enterprise Completa</h3>\\n<p>Portale web complesso per grandi aziende, con funzionalità avanzate di gestione, intranet e comunicazione aziendale.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Fino a 25+ pagine e sezioni personalizzate</li>\\n<li>Sistema di gestione utenti con ruoli</li>\\n<li>Intranet con documenti e risorse</li>\\n<li>Portale carriere con candidature online</li>\\n<li>Newsroom e comunicazione interna</li>\\n<li>Integrazioni CRM/ERP disponibili</li>\\n<li>Supporto multilingua</li>\\n<li>Dashboard personalizzate</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>Laravel/PHP, MySQL/PostgreSQL, Vue.js/React, REST API, Redis, AWS/Cloud hosting</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Scalabilità enterprise</li>\\n<li>Sicurezza avanzata (2FA, audit log)</li>\\n<li>Performance per alto traffico</li>\\n<li>Supporto e manutenzione dedicati</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Tempo di implementazione: 6-12 settimane</li>\\n<li>Richiede analisi dettagliata dei requisiti</li>\\n<li>Hosting enterprise raccomandato</li>\\n</ul>\", \"ro\": \"<h3>🏢 Portal Corporate - Soluția Enterprise Completă</h3>\\n<p>Portal web complex pentru companii mari, cu funcționalități avansate de management, intranet și comunicare corporativă.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Până la 25+ pagini și secțiuni personalizate</li>\\n<li>Sistem de management utilizatori cu roluri</li>\\n<li>Intranet cu documente și resurse</li>\\n<li>Portal cariere cu aplicații online</li>\\n<li>Newsroom și comunicare internă</li>\\n<li>Integrări CRM/ERP disponibile</li>\\n<li>Multi-language support</li>\\n<li>Dashboard-uri personalizate</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>Laravel/PHP, MySQL/PostgreSQL, Vue.js/React, REST API, Redis, AWS/Cloud hosting</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Scalabilitate enterprise</li>\\n<li>Securitate avansată (2FA, audit logs)</li>\\n<li>Performanță pentru trafic mare</li>\\n<li>Suport și mentenanță dedicată</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Timp de implementare: 6-12 săptămâni</li>\\n<li>Necesită analiză detaliată a cerințelor</li>\\n<li>Hosting enterprise recomandat</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Custom design</li><li>Advanced modules (Catalog, Careers, Download Center)</li><li>Multilingual structure</li><li>Enhanced security</li></ul>\", \"ro\": \"<ul><li>Design custom</li><li>Module avansate (Catalog, Cariere, Download Center)</li><li>Structură multilingvă</li><li>Securitate sporită</li></ul>\"}','WEB-CORP-004',4999.00,'RON',NULL,999,'\"[\\\"images\\\\/services\\\\/portal-corporate.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-21 08:56:13','2025-12-21 10:32:05'),(11,NULL,'{\"de\": \"Kompletter E-Commerce\", \"en\": \"Complete E-Commerce\", \"es\": \"E-Commerce Completo\", \"fr\": \"E-Commerce Complet\", \"it\": \"E-Commerce Completo\", \"ro\": \"E-Commerce Complete\"}','e-commerce-complete','{\"de\": \"<h3>🛒 Kompletter E-Commerce - Ihr professioneller Online-Shop</h3>\\n<p>Komplette Online-Shop-Lösung mit allen Funktionen zum Verkauf physischer oder digitaler Produkte.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Unbegrenzter Produktkatalog mit Varianten</li>\\n<li>Warenkorb und optimierter Checkout</li>\\n<li>Online-Zahlungen (Stripe, PayPal, Karte)</li>\\n<li>Lager- und Bestellverwaltung</li>\\n<li>Gutschein- und Rabattsystem</li>\\n<li>Automatisierte Bestell-E-Mails</li>\\n<li>Verkaufsberichte-Dashboards</li>\\n<li>Versandintegration (optional)</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>Laravel/PHP, Filament Admin, MySQL, Stripe/PayPal API, TailwindCSS, Alpine.js</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Keine Verkaufsprovisionen</li>\\n<li>Volle Kontrolle über Daten</li>\\n<li>SEO-optimiert für Produkte</li>\\n<li>Schneller und sicherer Checkout</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Produktfotos nicht enthalten</li>\\n<li>Versandintegration erfordert separate API</li>\\n<li>Wir empfehlen mindestens 50 Produkte zum Start</li>\\n</ul>\", \"en\": \"<h3>🛒 Complete E-Commerce - Your Professional Online Store</h3>\\n<p>Complete online store solution with all the features needed to sell physical or digital products.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Unlimited product catalog with variants</li>\\n<li>Shopping cart and optimized checkout</li>\\n<li>Online payments (Stripe, PayPal, card)</li>\\n<li>Stock and order management</li>\\n<li>Coupons and discounts system</li>\\n<li>Automated order emails</li>\\n<li>Sales reports dashboards</li>\\n<li>Courier integration (optional)</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>Laravel/PHP, Filament Admin, MySQL, Stripe/PayPal API, TailwindCSS, Alpine.js</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>No sales commissions</li>\\n<li>Total control over data</li>\\n<li>SEO optimized for products</li>\\n<li>Fast and secure checkout</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Product photos not included</li>\\n<li>Courier integration requires separate API</li>\\n<li>We recommend minimum 50 products at launch</li>\\n</ul>\", \"es\": \"<h3>🛒 E-Commerce Completo - Tu Tienda Online Profesional</h3>\\n<p>Solución completa de tienda online con todas las funcionalidades necesarias para vender productos físicos o digitales.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Catálogo de productos ilimitado con variantes</li>\\n<li>Carrito de compras y checkout optimizado</li>\\n<li>Pagos online (Stripe, PayPal, tarjeta)</li>\\n<li>Gestión de stock y pedidos</li>\\n<li>Sistema de cupones y descuentos</li>\\n<li>Emails automáticos de pedidos</li>\\n<li>Dashboards de informes de ventas</li>\\n<li>Integración mensajería (opcional)</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>Laravel/PHP, Filament Admin, MySQL, Stripe/PayPal API, TailwindCSS, Alpine.js</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Sin comisiones por ventas</li>\\n<li>Control total sobre los datos</li>\\n<li>SEO optimizado para productos</li>\\n<li>Checkout rápido y seguro</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Fotos de productos no incluidas</li>\\n<li>Integración mensajería requiere API separada</li>\\n<li>Recomendamos mínimo 50 productos al lanzamiento</li>\\n</ul>\", \"fr\": \"<h3>🛒 E-Commerce Complet - Votre Boutique en Ligne Professionnelle</h3>\\n<p>Solution complète de boutique en ligne avec toutes les fonctionnalités pour vendre des produits physiques ou numériques.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Catalogue produits illimité avec variantes</li>\\n<li>Panier et checkout optimisé</li>\\n<li>Paiements en ligne (Stripe, PayPal, carte)</li>\\n<li>Gestion stocks et commandes</li>\\n<li>Système de coupons et réductions</li>\\n<li>Emails automatiques de commandes</li>\\n<li>Tableaux de bord rapports ventes</li>\\n<li>Intégration transporteur (optionnel)</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>Laravel/PHP, Filament Admin, MySQL, Stripe/PayPal API, TailwindCSS, Alpine.js</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Pas de commissions sur les ventes</li>\\n<li>Contrôle total sur les données</li>\\n<li>SEO optimisé pour les produits</li>\\n<li>Checkout rapide et sécurisé</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Photos produits non incluses</li>\\n<li>Intégration transporteur nécessite API séparée</li>\\n<li>Nous recommandons minimum 50 produits au lancement</li>\\n</ul>\", \"it\": \"<h3>🛒 E-Commerce Completo - Il Tuo Negozio Online Professionale</h3>\\n<p>Soluzione completa di negozio online con tutte le funzionalità per vendere prodotti fisici o digitali.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Catalogo prodotti illimitato con varianti</li>\\n<li>Carrello e checkout ottimizzato</li>\\n<li>Pagamenti online (Stripe, PayPal, carta)</li>\\n<li>Gestione magazzino e ordini</li>\\n<li>Sistema coupon e sconti</li>\\n<li>Email automatiche ordini</li>\\n<li>Dashboard report vendite</li>\\n<li>Integrazione corriere (opzionale)</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>Laravel/PHP, Filament Admin, MySQL, Stripe/PayPal API, TailwindCSS, Alpine.js</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Nessuna commissione sulle vendite</li>\\n<li>Controllo totale sui dati</li>\\n<li>SEO ottimizzato per prodotti</li>\\n<li>Checkout veloce e sicuro</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Foto prodotti non incluse</li>\\n<li>Integrazione corriere richiede API separata</li>\\n<li>Raccomandiamo minimo 50 prodotti al lancio</li>\\n</ul>\", \"ro\": \"<h3>🛒 E-Commerce Complete - Magazinul Tău Online Profesional</h3>\\n<p>Soluție completă de magazin online cu toate funcționalitățile necesare pentru a vinde produse fizice sau digitale.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Catalog produse nelimitat cu variante</li>\\n<li>Coș de cumpărături și checkout optimizat</li>\\n<li>Plăți online (Stripe, PayPal, card)</li>\\n<li>Gestiune stocuri și comenzi</li>\\n<li>Sistem de cupoane și reduceri</li>\\n<li>Email-uri automate pentru comenzi</li>\\n<li>Dashboard-uri rapoarte vânzări</li>\\n<li>Integrare curierat (opțional)</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>Laravel/PHP, Filament Admin, MySQL, Stripe/PayPal API, TailwindCSS, Alpine.js</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Fără comisioane pe vânzări</li>\\n<li>Control total asupra datelor</li>\\n<li>SEO optimizat pentru produse</li>\\n<li>Checkout rapid și sigur</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Fotografiile produselor nu sunt incluse</li>\\n<li>Integrarea curierat necesită API separat</li>\\n<li>Recomandăm minim 50 produse la lansare</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Payment processor integration</li><li>Courier integration (AWB generation)</li><li>Automatic invoicing</li><li>Mandatory legal pages</li><li>Full data ownership</li></ul>\", \"ro\": \"<ul><li>Integrare procesator plăți</li><li>Integrare curierat (generare AWB)</li><li>Facturare automată</li><li>Pagini legale obligatorii</li><li>Proprietate totală asupra datelor</li></ul>\"}','ECOM-001',2999.00,'RON',NULL,999,'\"[\\\"images\\\\/services\\\\/ecommerce-complete.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-21 08:56:29','2025-12-21 10:32:49'),(12,NULL,'{\"de\": \"Individuelle Web-Anwendung (MVP)\", \"en\": \"Custom Web Application (MVP)\", \"es\": \"Aplicación Web Personalizada (MVP)\", \"fr\": \"Application Web Sur Mesure (MVP)\", \"it\": \"Applicazione Web Personalizzata (MVP)\", \"ro\": \"Aplicație Web Custom (MVP)\"}','aplicatie-web-custom-mvp','{\"de\": \"<h3>💻 Individuelle Web-Anwendung (MVP) - Ihre Idee, Unser Code</h3>\\n<p>Wir entwickeln maßgeschneiderte Webanwendungen von Grund auf, perfekt für Startups und innovative Geschäftsideen. MVP = Minimum Viable Product.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Detaillierte Analyse und Planung</li>\\n<li>Individuelles UI/UX-Design</li>\\n<li>Full-Stack-Entwicklung</li>\\n<li>Authentifizierung und Benutzerverwaltung</li>\\n<li>Komplettes Admin-Dashboard</li>\\n<li>REST-API für Integrationen</li>\\n<li>Technische Dokumentation</li>\\n<li>Server-Deployment und Konfiguration</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>Laravel/PHP oder Node.js, React/Vue.js, PostgreSQL/MySQL, Redis, Docker, AWS/DigitalOcean</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>100% proprietärer Code gehört Ihnen</li>\\n<li>Skalierbar für Wachstum</li>\\n<li>Enterprise-Grade-Sicherheit</li>\\n<li>Post-Launch-Support inklusive</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Entwicklungszeit: 8-16 Wochen</li>\\n<li>Erfordert klare Spezifikationen</li>\\n<li>Komplexe Funktionen = zusätzliche Kosten</li>\\n</ul>\", \"en\": \"<h3>💻 Custom Web Application (MVP) - Your Idea, Our Code</h3>\\n<p>We develop custom web applications from scratch, perfect for startups and innovative business ideas. MVP = Minimum Viable Product.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Detailed analysis and planning</li>\\n<li>Custom UI/UX design</li>\\n<li>Full-stack development</li>\\n<li>Authentication and user management</li>\\n<li>Complete admin dashboard</li>\\n<li>REST API for integrations</li>\\n<li>Technical documentation</li>\\n<li>Server deployment and configuration</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>Laravel/PHP or Node.js, React/Vue.js, PostgreSQL/MySQL, Redis, Docker, AWS/DigitalOcean</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>100% proprietary code yours</li>\\n<li>Scalable for growth</li>\\n<li>Enterprise-grade security</li>\\n<li>Post-launch support included</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Development time: 8-16 weeks</li>\\n<li>Requires clear specifications</li>\\n<li>Complex features = additional cost</li>\\n</ul>\", \"es\": \"<h3>💻 Aplicación Web Personalizada (MVP) - Tu Idea, Nuestro Código</h3>\\n<p>Desarrollamos aplicaciones web personalizadas desde cero, perfectas para startups e ideas de negocio innovadoras. MVP = Producto Mínimo Viable.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Análisis y planificación detallada</li>\\n<li>Diseño UI/UX personalizado</li>\\n<li>Desarrollo full-stack</li>\\n<li>Autenticación y gestión de usuarios</li>\\n<li>Dashboard admin completo</li>\\n<li>API REST para integraciones</li>\\n<li>Documentación técnica</li>\\n<li>Deploy y configuración servidor</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>Laravel/PHP o Node.js, React/Vue.js, PostgreSQL/MySQL, Redis, Docker, AWS/DigitalOcean</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Código propietario 100% tuyo</li>\\n<li>Escalable para crecimiento</li>\\n<li>Seguridad de nivel enterprise</li>\\n<li>Soporte post-lanzamiento incluido</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Tiempo de desarrollo: 8-16 semanas</li>\\n<li>Requiere especificaciones claras</li>\\n<li>Funcionalidades complejas = coste adicional</li>\\n</ul>\", \"fr\": \"<h3>💻 Application Web Sur Mesure (MVP) - Votre Idée, Notre Code</h3>\\n<p>Nous développons des applications web personnalisées à partir de zéro, parfaites pour les startups et idées innovantes. MVP = Produit Minimum Viable.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Analyse et planification détaillées</li>\\n<li>Design UI/UX personnalisé</li>\\n<li>Développement full-stack</li>\\n<li>Authentification et gestion utilisateurs</li>\\n<li>Dashboard admin complet</li>\\n<li>API REST pour intégrations</li>\\n<li>Documentation technique</li>\\n<li>Déploiement et configuration serveur</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>Laravel/PHP ou Node.js, React/Vue.js, PostgreSQL/MySQL, Redis, Docker, AWS/DigitalOcean</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Code propriétaire 100% à vous</li>\\n<li>Évolutif pour la croissance</li>\\n<li>Sécurité niveau enterprise</li>\\n<li>Support post-lancement inclus</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Délai de développement: 8-16 semaines</li>\\n<li>Nécessite des spécifications claires</li>\\n<li>Fonctionnalités complexes = coût supplémentaire</li>\\n</ul>\", \"it\": \"<h3>💻 Applicazione Web Personalizzata (MVP) - La Tua Idea, Il Nostro Codice</h3>\\n<p>Sviluppiamo applicazioni web personalizzate da zero, perfette per startup e idee di business innovative. MVP = Prodotto Minimo Funzionante.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Analisi e pianificazione dettagliata</li>\\n<li>Design UI/UX personalizzato</li>\\n<li>Sviluppo full-stack</li>\\n<li>Autenticazione e gestione utenti</li>\\n<li>Dashboard admin completa</li>\\n<li>API REST per integrazioni</li>\\n<li>Documentazione tecnica</li>\\n<li>Deploy e configurazione server</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>Laravel/PHP o Node.js, React/Vue.js, PostgreSQL/MySQL, Redis, Docker, AWS/DigitalOcean</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Codice proprietario 100% tuo</li>\\n<li>Scalabile per la crescita</li>\\n<li>Sicurezza enterprise-grade</li>\\n<li>Supporto post-lancio incluso</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Tempo di sviluppo: 8-16 settimane</li>\\n<li>Richiede specifiche chiare</li>\\n<li>Funzionalità complesse = costo aggiuntivo</li>\\n</ul>\", \"ro\": \"<h3>💻 Aplicație Web Custom (MVP) - Ideea Ta, Codul Nostru</h3>\\n<p>Dezvoltăm aplicații web personalizate de la zero, perfecte pentru startup-uri și idei de business inovatoare. MVP = Minimum Viable Product.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Analiză și planificare detaliată</li>\\n<li>Design UI/UX personalizat</li>\\n<li>Dezvoltare full-stack</li>\\n<li>Autentificare și management utilizatori</li>\\n<li>Dashboard admin complet</li>\\n<li>API REST pentru integrări</li>\\n<li>Documentație tehnică</li>\\n<li>Deploy și configurare server</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>Laravel/PHP sau Node.js, React/Vue.js, PostgreSQL/MySQL, Redis, Docker, AWS/DigitalOcean</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Cod proprietar 100% al tău</li>\\n<li>Scalabil pentru creștere</li>\\n<li>Securitate enterprise-grade</li>\\n<li>Suport post-lansare inclus</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Timp dezvoltare: 8-16 săptămâni</li>\\n<li>Necesită specificații clare</li>\\n<li>Funcționalități complexe = cost adițional</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>User authentication</li><li>Admin panel</li><li>Up to 3 functional modules</li></ul>\", \"ro\": \"<ul><li>Autentificare utilizatori</li><li>Panou de administrare</li><li>Până la 3 module funcționale</li></ul>\"}','SOFT-MVP-001',5999.00,'RON',NULL,999,'[\"images/services/aplicatie-web.jpg\"]',NULL,NULL,1,1,'2025-12-21 08:57:19','2025-12-21 10:33:36'),(13,NULL,'{\"de\": \"Mobile App Starter\", \"en\": \"Mobile App Starter\", \"es\": \"Aplicación Móvil Inicial\", \"fr\": \"Application Mobile Starter\", \"it\": \"App Mobile Starter\", \"ro\": \"Aplicație Mobile Start\"}','aplicatie-mobile-start','{\"de\": \"<h3>📱 Mobile App Starter - Präsenz auf iOS und Android</h3>\\n<p>Cross-Platform-Mobile-App für Ihr Unternehmen, verfügbar im App Store und Google Play. Schnelle Entwicklung mit modernen Technologien.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>iOS- und Android-App (gemeinsame Codebasis)</li>\\n<li>Bis zu 10 Bildschirme/Funktionen</li>\\n<li>Benutzerauthentifizierung</li>\\n<li>Push-Benachrichtigungen</li>\\n<li>Backend/API-Integration</li>\\n<li>Modernes und intuitives UI-Design</li>\\n<li>Veröffentlichung im App Store und Google Play</li>\\n<li>Integrierte Analysen</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>React Native oder Flutter, Firebase, REST API, Push Notifications, Analytics SDK</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Einzelne Codebasis für beide Plattformen</li>\\n<li>Reduzierte Entwicklungszeit</li>\\n<li>Native Performance</li>\\n<li>Einfache Updates</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Apple/Google-Entwicklerkonten erforderlich (99/25 USD/Jahr)</li>\\n<li>Komplexe Funktionen = zusätzliche Kosten</li>\\n<li>Separates Backend falls nicht vorhanden</li>\\n</ul>\", \"en\": \"<h3>📱 Mobile App Starter - Presence on iOS and Android</h3>\\n<p>Cross-platform mobile app for your business, available on App Store and Google Play. Fast development with modern technologies.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>iOS and Android app (shared codebase)</li>\\n<li>Up to 10 screens/features</li>\\n<li>User authentication</li>\\n<li>Push notifications</li>\\n<li>Backend/API integration</li>\\n<li>Modern and intuitive UI design</li>\\n<li>App Store and Google Play publishing</li>\\n<li>Integrated analytics</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>React Native or Flutter, Firebase, REST API, Push Notifications, Analytics SDK</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Single codebase for both platforms</li>\\n<li>Reduced development time</li>\\n<li>Native performance</li>\\n<li>Easy updates</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Apple/Google developer accounts required (99/25 USD/year)</li>\\n<li>Complex features = additional cost</li>\\n<li>Separate backend if none exists</li>\\n</ul>\", \"es\": \"<h3>📱 Aplicación Móvil Inicial - Presencia en iOS y Android</h3>\\n<p>Aplicación móvil multiplataforma para tu negocio, disponible en App Store y Google Play. Desarrollo rápido con tecnologías modernas.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>App iOS y Android (código compartido)</li>\\n<li>Hasta 10 pantallas/funcionalidades</li>\\n<li>Autenticación de usuarios</li>\\n<li>Notificaciones push</li>\\n<li>Integración con backend/API</li>\\n<li>Diseño UI moderno e intuitivo</li>\\n<li>Publicación en App Store y Google Play</li>\\n<li>Analytics integrado</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>React Native o Flutter, Firebase, REST API, Push Notifications, Analytics SDK</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Un solo código para ambas plataformas</li>\\n<li>Tiempo de desarrollo reducido</li>\\n<li>Rendimiento nativo</li>\\n<li>Actualizaciones fáciles</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Cuentas developer Apple/Google necesarias (99/25 USD/año)</li>\\n<li>Funcionalidades complejas = coste adicional</li>\\n<li>Backend separado si no existe</li>\\n</ul>\", \"fr\": \"<h3>📱 Application Mobile Starter - Présence sur iOS et Android</h3>\\n<p>Application mobile multiplateforme pour votre entreprise, disponible sur App Store et Google Play. Développement rapide avec technologies modernes.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Application iOS et Android (code partagé)</li>\\n<li>Jusqu\'à 10 écrans/fonctionnalités</li>\\n<li>Authentification utilisateurs</li>\\n<li>Notifications push</li>\\n<li>Intégration backend/API</li>\\n<li>Design UI moderne et intuitif</li>\\n<li>Publication sur App Store et Google Play</li>\\n<li>Analytics intégré</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>React Native ou Flutter, Firebase, REST API, Push Notifications, Analytics SDK</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Code unique pour les deux plateformes</li>\\n<li>Temps de développement réduit</li>\\n<li>Performance native</li>\\n<li>Mises à jour faciles</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Comptes développeur Apple/Google requis (99/25 USD/an)</li>\\n<li>Fonctionnalités complexes = coût supplémentaire</li>\\n<li>Backend séparé si inexistant</li>\\n</ul>\", \"it\": \"<h3>📱 App Mobile Starter - Presenza su iOS e Android</h3>\\n<p>App mobile multipiattaforma per la tua azienda, disponibile su App Store e Google Play. Sviluppo rapido con tecnologie moderne.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>App iOS e Android (codice condiviso)</li>\\n<li>Fino a 10 schermate/funzionalità</li>\\n<li>Autenticazione utenti</li>\\n<li>Notifiche push</li>\\n<li>Integrazione backend/API</li>\\n<li>Design UI moderno e intuitivo</li>\\n<li>Pubblicazione su App Store e Google Play</li>\\n<li>Analytics integrato</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>React Native o Flutter, Firebase, REST API, Push Notifications, Analytics SDK</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Codice unico per entrambe le piattaforme</li>\\n<li>Tempo di sviluppo ridotto</li>\\n<li>Prestazioni native</li>\\n<li>Aggiornamenti facili</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Account developer Apple/Google richiesti (99/25 USD/anno)</li>\\n<li>Funzionalità complesse = costo aggiuntivo</li>\\n<li>Backend separato se non esistente</li>\\n</ul>\", \"ro\": \"<h3>📱 Aplicație Mobile Start - Prezență pe iOS și Android</h3>\\n<p>Aplicație mobilă cross-platform pentru afacerea ta, disponibilă pe App Store și Google Play. Dezvoltare rapidă cu tehnologii moderne.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Aplicație iOS și Android (codebase comun)</li>\\n<li>Până la 10 ecrane/funcționalități</li>\\n<li>Autentificare utilizatori</li>\\n<li>Push notifications</li>\\n<li>Integrare cu backend/API</li>\\n<li>Design UI modern și intuitiv</li>\\n<li>Publicare pe App Store și Google Play</li>\\n<li>Analytics integrat</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>React Native sau Flutter, Firebase, REST API, Push Notifications, Analytics SDK</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>O singură codebase pentru ambele platforme</li>\\n<li>Timp de dezvoltare redus</li>\\n<li>Performanță nativă</li>\\n<li>Actualizări ușoare</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Conturi developer Apple/Google necesare (99/25 USD/an)</li>\\n<li>Funcționalități complexe = cost adițional</li>\\n<li>Backend separat dacă nu există</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>App for iOS and Android</li><li>App Store and Google Play publishing assistance</li></ul>\", \"ro\": \"<ul><li>Aplicație pentru iOS și Android</li><li>Asistență publicare App Store și Google Play</li></ul>\"}','SOFT-MOB-002',9999.00,'RON',NULL,999,'[\"images/services/aplicatie-mobile.jpg\"]',NULL,NULL,1,1,'2025-12-21 08:57:19','2025-12-21 10:36:33'),(14,NULL,'{\"de\": \"KI-Chatbot Agent (Setup)\", \"en\": \"AI Chatbot Agent (Setup)\", \"es\": \"Agente Chatbot IA (Setup)\", \"fr\": \"Agent Chatbot IA (Setup)\", \"it\": \"Agente Chatbot IA (Setup)\", \"ro\": \"Agent AI Chatbot (Setup)\"}','agent-ai-chatbot-setup','{\"de\": \"<h3>🤖 KI-Chatbot Agent - Ihr digitaler Assistent 24/7</h3>\\n<p>Intelligenter KI-basierter Chatbot für Kundensupport, Vertrieb und Automatisierung. Antwortet Kunden sofort, rund um die Uhr.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Individueller KI-Chatbot für Ihr Unternehmen</li>\\n<li>Training mit Ihren Dokumenten und FAQs</li>\\n<li>Website-Integration (Widget oder Vollseite)</li>\\n<li>Antworten in mehreren Sprachen</li>\\n<li>Eskalation an menschlichen Operator</li>\\n<li>Konversationsanalysen</li>\\n<li>Vollständige visuelle Anpassung</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>OpenAI GPT-4/Claude, LangChain, Vector Database, WebSocket, Next.js/React</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Verfügbar 24/7 ohne Pausen</li>\\n<li>Reduziert Supportkosten</li>\\n<li>Konsistente und präzise Antworten</li>\\n<li>Lernt aus Interaktionen</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>OpenAI/Anthropic API-Kosten separat</li>\\n<li>Erfordert grundlegende Dokumentation</li>\\n<li>Initiales Training 1-2 Wochen</li>\\n</ul>\", \"en\": \"<h3>🤖 AI Chatbot Agent - Your 24/7 Digital Assistant</h3>\\n<p>Intelligent AI-based chatbot for customer support, sales, and automation. Responds to customers instantly, non-stop.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Custom AI chatbot for your business</li>\\n<li>Training on your documents and FAQs</li>\\n<li>Website integration (widget or full-page)</li>\\n<li>Responses in multiple languages</li>\\n<li>Escalation to human operator</li>\\n<li>Conversation analytics</li>\\n<li>Complete visual customization</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>OpenAI GPT-4/Claude, LangChain, Vector Database, WebSocket, Next.js/React</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Available 24/7 without breaks</li>\\n<li>Reduces support costs</li>\\n<li>Consistent and accurate responses</li>\\n<li>Learns from interactions</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>OpenAI/Anthropic API costs separate</li>\\n<li>Requires basic documentation</li>\\n<li>Initial training 1-2 weeks</li>\\n</ul>\", \"es\": \"<h3>🤖 Agente Chatbot IA - Tu Asistente Digital 24/7</h3>\\n<p>Chatbot inteligente basado en IA para soporte al cliente, ventas y automatización. Responde a clientes instantáneamente, sin parar.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Chatbot IA personalizado para tu negocio</li>\\n<li>Entrenamiento con tus documentos y FAQs</li>\\n<li>Integración web (widget o página completa)</li>\\n<li>Respuestas en múltiples idiomas</li>\\n<li>Escalado a operador humano</li>\\n<li>Analytics de conversaciones</li>\\n<li>Personalización visual completa</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>OpenAI GPT-4/Claude, LangChain, Vector Database, WebSocket, Next.js/React</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Disponible 24/7 sin pausas</li>\\n<li>Reduce costes de soporte</li>\\n<li>Respuestas consistentes y precisas</li>\\n<li>Aprende de las interacciones</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Costes API OpenAI/Anthropic separados</li>\\n<li>Requiere documentación básica</li>\\n<li>Entrenamiento inicial 1-2 semanas</li>\\n</ul>\", \"fr\": \"<h3>🤖 Agent Chatbot IA - Votre Assistant Digital 24/7</h3>\\n<p>Chatbot intelligent basé sur l\'IA pour support client, ventes et automatisation. Répond aux clients instantanément, non-stop.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Chatbot IA personnalisé pour votre entreprise</li>\\n<li>Formation sur vos documents et FAQs</li>\\n<li>Intégration site web (widget ou page complète)</li>\\n<li>Réponses en plusieurs langues</li>\\n<li>Escalade vers opérateur humain</li>\\n<li>Analytics des conversations</li>\\n<li>Personnalisation visuelle complète</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>OpenAI GPT-4/Claude, LangChain, Vector Database, WebSocket, Next.js/React</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Disponible 24/7 sans pause</li>\\n<li>Réduit les coûts de support</li>\\n<li>Réponses cohérentes et précises</li>\\n<li>Apprend des interactions</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Coûts API OpenAI/Anthropic séparés</li>\\n<li>Nécessite documentation de base</li>\\n<li>Formation initiale 1-2 semaines</li>\\n</ul>\", \"it\": \"<h3>🤖 Agente Chatbot IA - Il Tuo Assistente Digitale 24/7</h3>\\n<p>Chatbot intelligente basato su IA per supporto clienti, vendite e automazione. Risponde ai clienti istantaneamente, senza sosta.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Chatbot IA personalizzato per la tua azienda</li>\\n<li>Training sui tuoi documenti e FAQ</li>\\n<li>Integrazione sito web (widget o pagina completa)</li>\\n<li>Risposte in più lingue</li>\\n<li>Escalation a operatore umano</li>\\n<li>Analytics conversazioni</li>\\n<li>Personalizzazione visiva completa</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>OpenAI GPT-4/Claude, LangChain, Vector Database, WebSocket, Next.js/React</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Disponibile 24/7 senza pause</li>\\n<li>Riduce i costi di supporto</li>\\n<li>Risposte coerenti e precise</li>\\n<li>Impara dalle interazioni</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Costi API OpenAI/Anthropic separati</li>\\n<li>Richiede documentazione di base</li>\\n<li>Training iniziale 1-2 settimane</li>\\n</ul>\", \"ro\": \"<h3>🤖 Agent AI Chatbot - Asistentul Tău Digital 24/7</h3>\\n<p>Chatbot inteligent bazat pe AI pentru suport clienți, vânzări și automatizare. Răspunde clienților instant, non-stop.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Chatbot AI personalizat pentru businessul tău</li>\\n<li>Training pe documentele și FAQ-urile tale</li>\\n<li>Integrare website (widget sau full-page)</li>\\n<li>Răspunsuri în limba română și engleză</li>\\n<li>Escalare către operator uman</li>\\n<li>Analytics conversații</li>\\n<li>Personalizare vizuală completă</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>OpenAI GPT-4/Claude, LangChain, Vector Database, WebSocket, Next.js/React</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Disponibil 24/7 fără pauze</li>\\n<li>Reduce costurile cu suport</li>\\n<li>Răspunsuri consistente și precise</li>\\n<li>Învață din interacțiuni</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Costuri API OpenAI/Anthropic separate</li>\\n<li>Necesită documentație de bază</li>\\n<li>Training inițial 1-2 săptămâni</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>OpenAI/Claude integration</li><li>Training based on company documents</li><li>Modern chat widget</li></ul>\", \"ro\": \"<ul><li>Integrare OpenAI/Claude</li><li>Antrenare pe baza documentelor firmei</li><li>Widget de chat modern</li></ul>\"}','AI-CHAT-001',1999.00,'RON',NULL,999,'[\"images/services/ai-chatbot.jpg\"]',NULL,NULL,1,1,'2025-12-21 08:57:38','2025-12-21 10:37:17'),(15,NULL,'{\"de\": \"Workflow-Automatisierung (Excel/Daten)\", \"en\": \"Workflow Automation (Excel/Data)\", \"es\": \"Automatización de Flujos (Excel/Datos)\", \"fr\": \"Automatisation des Flux (Excel/Données)\", \"it\": \"Automazione Flussi (Excel/Dati)\", \"ro\": \"Automatizare Fluxuri (Excel/Data)\"}','automatizare-fluxuri-excel','{\"de\": \"<h3>⚡ Workflow-Automatisierung - Eliminieren Sie repetitive Aufgaben</h3>\\n<p>Wir automatisieren Ihre manuellen Prozesse: Excel, Berichte, Datensynchronisation, E-Mails, Benachrichtigungen. Sparen Sie täglich Stunden.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Analyse bestehender Prozesse</li>\\n<li>Excel/CSV-Workflow-Automatisierung</li>\\n<li>Datensynchronisation zwischen Systemen</li>\\n<li>Geplante automatische Berichte</li>\\n<li>Automatische E-Mails und Benachrichtigungen</li>\\n<li>Überwachungs-Dashboard</li>\\n<li>Dokumentation und Schulung</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>Python, Pandas, Apache Airflow, n8n/Zapier, REST APIs, Cron Jobs, Power Automate</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Erhebliche Zeitersparnis</li>\\n<li>Eliminierung menschlicher Fehler</li>\\n<li>24/7-Betrieb ohne Aufsicht</li>\\n<li>Skalierbar für große Volumen</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Erfordert Zugriff auf Ihre Systeme</li>\\n<li>Komplexität bestimmt Endkosten</li>\\n<li>Einige Integrationen erfordern kostenpflichtige API</li>\\n</ul>\", \"en\": \"<h3>⚡ Workflow Automation - Eliminate Repetitive Tasks</h3>\\n<p>We automate your manual processes: Excel, reports, data sync, emails, notifications. Save hours every day.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Existing process analysis</li>\\n<li>Excel/CSV workflow automation</li>\\n<li>Data synchronization between systems</li>\\n<li>Scheduled automatic reports</li>\\n<li>Automatic emails and notifications</li>\\n<li>Monitoring dashboard</li>\\n<li>Documentation and training</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>Python, Pandas, Apache Airflow, n8n/Zapier, REST APIs, Cron Jobs, Power Automate</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Significant time savings</li>\\n<li>Elimination of human errors</li>\\n<li>24/7 operation without supervision</li>\\n<li>Scalable for large volumes</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Requires access to your systems</li>\\n<li>Complexity determines final cost</li>\\n<li>Some integrations require paid API</li>\\n</ul>\", \"es\": \"<h3>⚡ Automatización de Flujos - Elimina Tareas Repetitivas</h3>\\n<p>Automatizamos tus procesos manuales: Excel, informes, sincronización de datos, emails, notificaciones. Ahorra horas cada día.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Análisis de procesos existentes</li>\\n<li>Automatización de flujos Excel/CSV</li>\\n<li>Sincronización de datos entre sistemas</li>\\n<li>Informes automáticos programados</li>\\n<li>Emails y notificaciones automáticas</li>\\n<li>Dashboard de monitorización</li>\\n<li>Documentación y formación</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>Python, Pandas, Apache Airflow, n8n/Zapier, REST APIs, Cron Jobs, Power Automate</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Ahorro de tiempo significativo</li>\\n<li>Eliminación de errores humanos</li>\\n<li>Funcionamiento 24/7 sin supervisión</li>\\n<li>Escalable para grandes volúmenes</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Requiere acceso a tus sistemas</li>\\n<li>La complejidad determina el coste final</li>\\n<li>Algunas integraciones requieren API de pago</li>\\n</ul>\", \"fr\": \"<h3>⚡ Automatisation des Flux - Éliminez les Tâches Répétitives</h3>\\n<p>Nous automatisons vos processus manuels: Excel, rapports, synchronisation données, emails, notifications. Économisez des heures chaque jour.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Analyse des processus existants</li>\\n<li>Automatisation des flux Excel/CSV</li>\\n<li>Synchronisation des données entre systèmes</li>\\n<li>Rapports automatiques programmés</li>\\n<li>Emails et notifications automatiques</li>\\n<li>Tableau de bord de surveillance</li>\\n<li>Documentation et formation</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>Python, Pandas, Apache Airflow, n8n/Zapier, REST APIs, Cron Jobs, Power Automate</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Gain de temps significatif</li>\\n<li>Élimination des erreurs humaines</li>\\n<li>Fonctionnement 24/7 sans supervision</li>\\n<li>Évolutif pour grands volumes</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Nécessite accès à vos systèmes</li>\\n<li>La complexité détermine le coût final</li>\\n<li>Certaines intégrations nécessitent API payante</li>\\n</ul>\", \"it\": \"<h3>⚡ Automazione Flussi - Elimina i Task Ripetitivi</h3>\\n<p>Automatizziamo i tuoi processi manuali: Excel, report, sincronizzazione dati, email, notifiche. Risparmia ore ogni giorno.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Analisi processi esistenti</li>\\n<li>Automazione flussi Excel/CSV</li>\\n<li>Sincronizzazione dati tra sistemi</li>\\n<li>Report automatici programmati</li>\\n<li>Email e notifiche automatiche</li>\\n<li>Dashboard di monitoraggio</li>\\n<li>Documentazione e formazione</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>Python, Pandas, Apache Airflow, n8n/Zapier, REST APIs, Cron Jobs, Power Automate</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Risparmio di tempo significativo</li>\\n<li>Eliminazione errori umani</li>\\n<li>Funzionamento 24/7 senza supervisione</li>\\n<li>Scalabile per grandi volumi</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Richiede accesso ai tuoi sistemi</li>\\n<li>La complessità determina il costo finale</li>\\n<li>Alcune integrazioni richiedono API a pagamento</li>\\n</ul>\", \"ro\": \"<h3>⚡ Automatizare Fluxuri - Scapă de Taskurile Repetitive</h3>\\n<p>Automatizăm procesele tale manuale: Excel, rapoarte, sincronizare date, email-uri, notificări. Economisești ore întregi zilnic.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Analiză procese existente</li>\\n<li>Automatizare fluxuri Excel/CSV</li>\\n<li>Sincronizare date între sisteme</li>\\n<li>Rapoarte automate programate</li>\\n<li>Email-uri și notificări automate</li>\\n<li>Dashboard monitorizare</li>\\n<li>Documentație și training</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>Python, Pandas, Apache Airflow, n8n/Zapier, REST APIs, Cron Jobs, Power Automate</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Economie de timp semnificativă</li>\\n<li>Eliminare erori umane</li>\\n<li>Rulare 24/7 fără supraveghere</li>\\n<li>Scalabil pentru volume mari</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Necesită acces la sistemele tale</li>\\n<li>Complexitatea determină costul final</li>\\n<li>Unele integrări necesită API plătit</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Report consolidation</li><li>Data sync between email and spreadsheets</li><li>Automatic document generation</li></ul>\", \"ro\": \"<ul><li>Consolidare rapoarte</li><li>Sincronizare date între email și tabele</li><li>Generare automată documente</li></ul>\"}','AI-AUTO-002',1299.00,'RON',NULL,999,'[\"images/services/automatizare-fluxuri.jpg\"]',NULL,NULL,0,1,'2025-12-21 08:57:38','2025-12-21 10:38:06'),(16,NULL,'{\"de\": \"Logo-Design Starter\", \"en\": \"Logo Design Starter\", \"es\": \"Diseño de Logo Inicial\", \"fr\": \"Design Logo Starter\", \"it\": \"Design Logo Starter\", \"ro\": \"Logo Design Start\"}','logo-design-start','{\"de\": \"<h3>🎨 Logo-Design Starter - Die visuelle Identität Ihrer Marke</h3>\\n<p>Professionelles Logo, das die Essenz Ihrer Marke perfekt repräsentiert. 3 erste Konzepte und Überarbeitungen inklusive.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Kreatives Briefing und Recherche</li>\\n<li>3 verschiedene Logo-Konzepte</li>\\n<li>3 Überarbeitungsrunden</li>\\n<li>Vektordateien (AI, EPS, SVG)</li>\\n<li>Web-Dateien (PNG, JPG)</li>\\n<li>Farb- und Monochrom-Varianten</li>\\n<li>Mini-Nutzungsanleitung</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Tools:</h4>\\n<p>Adobe Illustrator, Figma, individuelle Typografie, Markenrecherche</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Originelles Design, keine Vorlage</li>\\n<li>Dateien skalierbar auf jede Größe</li>\\n<li>Volle Nutzungsrechte</li>\\n<li>Kollaborativer Prozess</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Enthält kein vollständiges Branding</li>\\n<li>Extra Überarbeitungen = zusätzliche Kosten</li>\\n<li>Lieferzeit: 5-7 Tage</li>\\n</ul>\", \"en\": \"<h3>🎨 Logo Design Starter - Your Brand\'s Visual Identity</h3>\\n<p>Professional logo that perfectly represents your brand\'s essence. 3 initial concepts and revisions included.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Creative brief and research</li>\\n<li>3 different logo concepts</li>\\n<li>3 rounds of revisions</li>\\n<li>Vector files (AI, EPS, SVG)</li>\\n<li>Web files (PNG, JPG)</li>\\n<li>Color and monochrome variants</li>\\n<li>Mini usage guide</li>\\n</ul>\\n\\n<h4>🛠️ Tools used:</h4>\\n<p>Adobe Illustrator, Figma, custom typography, brand research</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Original design, not a template</li>\\n<li>Files scalable to any size</li>\\n<li>Full usage rights</li>\\n<li>Collaborative process</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Does not include complete branding</li>\\n<li>Extra revisions = additional cost</li>\\n<li>Delivery time: 5-7 days</li>\\n</ul>\", \"es\": \"<h3>🎨 Diseño de Logo Inicial - La Identidad Visual de Tu Marca</h3>\\n<p>Logo profesional que representa perfectamente la esencia de tu marca. 3 conceptos iniciales y revisiones incluidas.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Brief creativo e investigación</li>\\n<li>3 conceptos de logo diferentes</li>\\n<li>3 rondas de revisiones</li>\\n<li>Archivos vectoriales (AI, EPS, SVG)</li>\\n<li>Archivos web (PNG, JPG)</li>\\n<li>Variantes color y monocromo</li>\\n<li>Mini guía de uso</li>\\n</ul>\\n\\n<h4>🛠️ Herramientas utilizadas:</h4>\\n<p>Adobe Illustrator, Figma, tipografía personalizada, investigación de marca</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Diseño original, no plantilla</li>\\n<li>Archivos escalables a cualquier tamaño</li>\\n<li>Derechos completos de uso</li>\\n<li>Proceso colaborativo</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>No incluye branding completo</li>\\n<li>Revisiones extra = coste adicional</li>\\n<li>Tiempo de entrega: 5-7 días</li>\\n</ul>\", \"fr\": \"<h3>🎨 Design Logo Starter - L\'Identité Visuelle de Votre Marque</h3>\\n<p>Logo professionnel qui représente parfaitement l\'essence de votre marque. 3 concepts initiaux et révisions incluses.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Brief créatif et recherche</li>\\n<li>3 concepts de logo différents</li>\\n<li>3 séries de révisions</li>\\n<li>Fichiers vectoriels (AI, EPS, SVG)</li>\\n<li>Fichiers web (PNG, JPG)</li>\\n<li>Variantes couleur et monochrome</li>\\n<li>Mini guide d\'utilisation</li>\\n</ul>\\n\\n<h4>🛠️ Outils utilisés:</h4>\\n<p>Adobe Illustrator, Figma, typographie personnalisée, recherche de marque</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Design original, pas un modèle</li>\\n<li>Fichiers évolutifs à toute taille</li>\\n<li>Droits d\'utilisation complets</li>\\n<li>Processus collaboratif</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>N\'inclut pas le branding complet</li>\\n<li>Révisions supplémentaires = coût additionnel</li>\\n<li>Délai de livraison: 5-7 jours</li>\\n</ul>\", \"it\": \"<h3>🎨 Design Logo Starter - L\'Identità Visiva del Tuo Brand</h3>\\n<p>Logo professionale che rappresenta perfettamente l\'essenza del tuo brand. 3 concept iniziali e revisioni incluse.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Brief creativo e ricerca</li>\\n<li>3 concept logo diversi</li>\\n<li>3 round di revisioni</li>\\n<li>File vettoriali (AI, EPS, SVG)</li>\\n<li>File web (PNG, JPG)</li>\\n<li>Varianti colore e monocromo</li>\\n<li>Mini guida d\'uso</li>\\n</ul>\\n\\n<h4>🛠️ Strumenti utilizzati:</h4>\\n<p>Adobe Illustrator, Figma, tipografia personalizzata, ricerca brand</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Design originale, non template</li>\\n<li>File scalabili a qualsiasi dimensione</li>\\n<li>Diritti d\'uso completi</li>\\n<li>Processo collaborativo</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Non include branding completo</li>\\n<li>Revisioni extra = costo aggiuntivo</li>\\n<li>Tempo di consegna: 5-7 giorni</li>\\n</ul>\", \"ro\": \"<h3>🎨 Logo Design Start - Identitatea Vizuală a Brandului Tău</h3>\\n<p>Logo profesional care reprezintă perfect esența brandului tău. 3 concepte inițiale și revizuiri incluse.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Brief creativ și research</li>\\n<li>3 concepte logo diferite</li>\\n<li>3 runde de revizuiri</li>\\n<li>Fișiere vectoriale (AI, EPS, SVG)</li>\\n<li>Fișiere web (PNG, JPG)</li>\\n<li>Variante color și monocrom</li>\\n<li>Mini ghid de utilizare</li>\\n</ul>\\n\\n<h4>🛠️ Unelte utilizate:</h4>\\n<p>Adobe Illustrator, Figma, tipografie personalizată, cercetare brand</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Design original, nu template</li>\\n<li>Fișiere scalabile la orice dimensiune</li>\\n<li>Drepturi complete de utilizare</li>\\n<li>Proces colaborativ</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Nu include branding complet</li>\\n<li>Revizuiri extra = cost adițional</li>\\n<li>Timp de livrare: 5-7 zile</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>3 unique graphic concepts</li><li>Files in vector format (scalable)</li><li>Raster format (transparent)</li><li>Ready for web and print</li></ul>\", \"ro\": \"<ul><li>3 concepte grafice unice</li><li>Fișiere în format vectorial (scalabil)</li><li>Format raster (transparent)</li><li>Gata pentru web și print</li></ul>\"}','BRAND-LOGO-001',599.00,'RON',NULL,999,'[\"images/services/logo-design.jpg\"]',NULL,NULL,0,1,'2025-12-21 08:57:59','2025-12-21 10:38:54'),(17,NULL,'{\"de\": \"Komplettes visuelles Identitätskit\", \"en\": \"Complete Visual Identity Kit\", \"es\": \"Kit de Identidad Visual Completo\", \"fr\": \"Kit d\'Identité Visuelle Complet\", \"it\": \"Kit Identità Visiva Completo\", \"ro\": \"Kit Identitate Vizuală Complet\"}','kit-identitate-vizuala-complet','{\"de\": \"<h3>🎭 Komplettes visuelles Identitätskit - Ihre Marke, Komplett</h3>\\n<p>Komplettes Branding-Paket mit allem, was Sie für eine professionelle und kohärente visuelle Identität benötigen.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Hauptlogo + Varianten</li>\\n<li>Farbpalette (primär und sekundär)</li>\\n<li>Typografie (Schriften)</li>\\n<li>Unterstützende Grafikelemente</li>\\n<li>Visitenkarten-Design</li>\\n<li>Briefkopf und Favicon</li>\\n<li>Social-Media-Vorlage</li>\\n<li>Brand Guidelines PDF (20+ Seiten)</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Tools:</h4>\\n<p>Adobe Creative Suite, Figma, Wettbewerbsanalyse, Markenstrategie</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Kohärente Identität auf allen Plattformen</li>\\n<li>Professionalität und Glaubwürdigkeit</li>\\n<li>Einfach intern oder extern anzuwenden</li>\\n<li>Differenzierung vom Wettbewerb</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Enthält keine physischen Druckmaterialien</li>\\n<li>Lieferzeit: 2-3 Wochen</li>\\n<li>Größere Änderungen = zusätzliche Kosten</li>\\n</ul>\", \"en\": \"<h3>🎭 Complete Visual Identity Kit - Your Brand, Complete</h3>\\n<p>Complete branding package that includes everything you need for a professional and coherent visual identity.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Main logo + variations</li>\\n<li>Color palette (primary and secondary)</li>\\n<li>Typography (fonts)</li>\\n<li>Supporting graphic elements</li>\\n<li>Business card design</li>\\n<li>Letterhead and favicon</li>\\n<li>Social media template</li>\\n<li>Brand Guidelines PDF (20+ pages)</li>\\n</ul>\\n\\n<h4>🛠️ Tools used:</h4>\\n<p>Adobe Creative Suite, Figma, competitor research, brand strategy</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Coherent identity across all platforms</li>\\n<li>Professionalism and credibility</li>\\n<li>Easy to apply internally or externally</li>\\n<li>Differentiation from competition</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Does not include physical printed materials</li>\\n<li>Delivery time: 2-3 weeks</li>\\n<li>Major changes = additional cost</li>\\n</ul>\", \"es\": \"<h3>🎭 Kit de Identidad Visual Completo - Tu Marca, Completa</h3>\\n<p>Paquete completo de branding que incluye todo lo necesario para una identidad visual profesional y coherente.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Logo principal + variantes</li>\\n<li>Paleta de colores (primaria y secundaria)</li>\\n<li>Tipografía (fuentes)</li>\\n<li>Elementos gráficos de apoyo</li>\\n<li>Diseño de tarjetas de visita</li>\\n<li>Papel con membrete y favicon</li>\\n<li>Plantilla redes sociales</li>\\n<li>Brand Guidelines PDF (20+ páginas)</li>\\n</ul>\\n\\n<h4>🛠️ Herramientas utilizadas:</h4>\\n<p>Adobe Creative Suite, Figma, investigación competencia, estrategia de marca</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Identidad coherente en todas las plataformas</li>\\n<li>Profesionalismo y credibilidad</li>\\n<li>Fácil de aplicar interna o externamente</li>\\n<li>Diferenciación de la competencia</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>No incluye materiales impresos físicos</li>\\n<li>Tiempo de entrega: 2-3 semanas</li>\\n<li>Cambios mayores = coste adicional</li>\\n</ul>\", \"fr\": \"<h3>🎭 Kit d\'Identité Visuelle Complet - Votre Marque, Complète</h3>\\n<p>Package branding complet incluant tout ce dont vous avez besoin pour une identité visuelle professionnelle et cohérente.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Logo principal + variantes</li>\\n<li>Palette de couleurs (primaire et secondaire)</li>\\n<li>Typographie (polices)</li>\\n<li>Éléments graphiques de support</li>\\n<li>Design de cartes de visite</li>\\n<li>Papier à en-tête et favicon</li>\\n<li>Modèle réseaux sociaux</li>\\n<li>Brand Guidelines PDF (20+ pages)</li>\\n</ul>\\n\\n<h4>🛠️ Outils utilisés:</h4>\\n<p>Adobe Creative Suite, Figma, recherche concurrence, stratégie de marque</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Identité cohérente sur toutes les plateformes</li>\\n<li>Professionnalisme et crédibilité</li>\\n<li>Facile à appliquer en interne ou externe</li>\\n<li>Différenciation de la concurrence</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>N\'inclut pas les matériaux imprimés physiques</li>\\n<li>Délai de livraison: 2-3 semaines</li>\\n<li>Changements majeurs = coût supplémentaire</li>\\n</ul>\", \"it\": \"<h3>🎭 Kit Identità Visiva Completo - Il Tuo Brand, Completo</h3>\\n<p>Pacchetto branding completo che include tutto il necessario per un\'identità visiva professionale e coerente.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Logo principale + varianti</li>\\n<li>Palette colori (primaria e secondaria)</li>\\n<li>Tipografia (font)</li>\\n<li>Elementi grafici di supporto</li>\\n<li>Design biglietti da visita</li>\\n<li>Carta intestata e favicon</li>\\n<li>Template social media</li>\\n<li>Brand Guidelines PDF (20+ pagine)</li>\\n</ul>\\n\\n<h4>🛠️ Strumenti utilizzati:</h4>\\n<p>Adobe Creative Suite, Figma, ricerca concorrenza, strategia brand</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Identità coerente su tutte le piattaforme</li>\\n<li>Professionalità e credibilità</li>\\n<li>Facile da applicare internamente o esternamente</li>\\n<li>Differenziazione dalla concorrenza</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Non include materiali stampati fisici</li>\\n<li>Tempo di consegna: 2-3 settimane</li>\\n<li>Modifiche maggiori = costo aggiuntivo</li>\\n</ul>\", \"ro\": \"<h3>🎭 Kit Identitate Vizuală Complet - Brandul Tău, Complet</h3>\\n<p>Pachet complet de branding care include tot ce ai nevoie pentru o identitate vizuală profesională și coerentă.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Logo principal + variante</li>\\n<li>Paletă de culori (primară și secundară)</li>\\n<li>Tipografie (fonturi)</li>\\n<li>Elemente grafice suport</li>\\n<li>Cărți de vizită design</li>\\n<li>Hârtie cu antet și favicon</li>\\n<li>Template social media</li>\\n<li>Brand Guidelines PDF (20+ pagini)</li>\\n</ul>\\n\\n<h4>🛠️ Unelte utilizate:</h4>\\n<p>Adobe Creative Suite, Figma, cercetare competiție, strategie brand</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Identitate coerentă pe toate platformele</li>\\n<li>Profesionalism și credibilitate</li>\\n<li>Ușor de aplicat intern sau extern</li>\\n<li>Diferențiere față de competiție</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Nu include materiale printate fizic</li>\\n<li>Timp de livrare: 2-3 săptămâni</li>\\n<li>Modificări majore = cost adițional</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Complete Logo Design</li><li>Official Color Palette</li><li>Font Selection</li><li>Business Card Design</li><li>Letterhead Design</li><li>Social Media Kit</li></ul>\", \"ro\": \"<ul><li>Logo Design complet</li><li>Paleta de Culori oficială</li><li>Selecția de Fonturi</li><li>Design Carte de Vizită</li><li>Design Foaie cu Antet</li><li>Kit Social Media</li></ul>\"}','BRAND-KIT-002',1899.00,'RON',NULL,999,'[\"images/services/identitate-vizuala.jpg\"]',NULL,NULL,1,1,'2025-12-21 08:57:59','2025-12-21 10:39:38'),(18,NULL,'{\"de\": \"Basis-Wartungspaket\", \"en\": \"Basic Maintenance Package\", \"es\": \"Paquete Mantenimiento Básico\", \"fr\": \"Forfait Maintenance Basique\", \"it\": \"Pacchetto Manutenzione Base\", \"ro\": \"Pachet Basic Maintenance\"}','pachet-basic-maintenance','{\"de\": \"<h3>🔧 Basis-Wartungspaket - Seelenfrieden</h3>\\n<p>Monatliche Wartung für Ihre Website. Wir kümmern uns um Updates, Backups und Sicherheit. Sie konzentrieren sich auf das Geschäft.</p>\\n\\n<h4>📋 Was monatlich enthalten ist:</h4>\\n<ul>\\n<li>WordPress/Laravel/CMS-Updates</li>\\n<li>Plugin/Paket-Updates</li>\\n<li>Wöchentliches automatisches Backup</li>\\n<li>24/7-Uptime-Überwachung</li>\\n<li>Monatlicher Sicherheitsscan</li>\\n<li>Monatlicher Statusbericht</li>\\n<li>1 Stunde technischer Support inklusive</li>\\n</ul>\\n\\n<h4>🛠️ Was wir überwachen:</h4>\\n<p>Uptime, Leistung, Fehler, Sicherheit, SSL-Zertifikat, Backups</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Ihre Website immer aktuell</li>\\n<li>Probleme proaktiv erkannt</li>\\n<li>Backups für schnelle Wiederherstellung</li>\\n<li>Vorhersehbare monatliche Kosten</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Enthält keine Designänderungen</li>\\n<li>Zusätzliche Stunden = Extrakosten</li>\\n<li>Mindestens 3 Monate Vertrag</li>\\n</ul>\", \"en\": \"<h3>🔧 Basic Maintenance Package - Peace of Mind</h3>\\n<p>Monthly maintenance for your website. We handle updates, backups, and security. You focus on business.</p>\\n\\n<h4>📋 What\'s included monthly:</h4>\\n<ul>\\n<li>WordPress/Laravel/CMS updates</li>\\n<li>Plugin/package updates</li>\\n<li>Weekly automatic backup</li>\\n<li>24/7 uptime monitoring</li>\\n<li>Monthly security scan</li>\\n<li>Monthly status report</li>\\n<li>1 hour technical support included</li>\\n</ul>\\n\\n<h4>🛠️ What we monitor:</h4>\\n<p>Uptime, performance, errors, security, SSL certificate, backups</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Your site always updated</li>\\n<li>Problems detected proactively</li>\\n<li>Backups for quick recovery</li>\\n<li>Predictable monthly cost</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Does not include design changes</li>\\n<li>Additional hours = extra cost</li>\\n<li>Minimum 3-month contract</li>\\n</ul>\", \"es\": \"<h3>🔧 Paquete Mantenimiento Básico - Tranquilidad</h3>\\n<p>Mantenimiento mensual para tu sitio web. Nos encargamos de actualizaciones, copias de seguridad y seguridad. Tú te enfocas en el negocio.</p>\\n\\n<h4>📋 Qué incluye mensualmente:</h4>\\n<ul>\\n<li>Actualizaciones WordPress/Laravel/CMS</li>\\n<li>Actualizaciones de plugins/paquetes</li>\\n<li>Backup semanal automático</li>\\n<li>Monitorización uptime 24/7</li>\\n<li>Escaneo de seguridad mensual</li>\\n<li>Informe de estado mensual</li>\\n<li>1 hora de soporte técnico incluida</li>\\n</ul>\\n\\n<h4>🛠️ Qué monitorizamos:</h4>\\n<p>Uptime, rendimiento, errores, seguridad, certificado SSL, backups</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Tu sitio siempre actualizado</li>\\n<li>Problemas detectados proactivamente</li>\\n<li>Backups para recuperación rápida</li>\\n<li>Coste mensual predecible</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>No incluye cambios de diseño</li>\\n<li>Horas adicionales = coste extra</li>\\n<li>Contrato mínimo 3 meses</li>\\n</ul>\", \"fr\": \"<h3>🔧 Forfait Maintenance Basique - Tranquillité d\'Esprit</h3>\\n<p>Maintenance mensuelle pour votre site web. Nous gérons les mises à jour, sauvegardes et sécurité. Vous vous concentrez sur votre activité.</p>\\n\\n<h4>📋 Ce qui est inclus mensuellement:</h4>\\n<ul>\\n<li>Mises à jour WordPress/Laravel/CMS</li>\\n<li>Mises à jour plugins/packages</li>\\n<li>Sauvegarde automatique hebdomadaire</li>\\n<li>Surveillance uptime 24/7</li>\\n<li>Scan sécurité mensuel</li>\\n<li>Rapport d\'état mensuel</li>\\n<li>1 heure support technique incluse</li>\\n</ul>\\n\\n<h4>🛠️ Ce que nous surveillons:</h4>\\n<p>Uptime, performance, erreurs, sécurité, certificat SSL, sauvegardes</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Votre site toujours à jour</li>\\n<li>Problèmes détectés proactivement</li>\\n<li>Sauvegardes pour récupération rapide</li>\\n<li>Coût mensuel prévisible</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>N\'inclut pas les changements de design</li>\\n<li>Heures supplémentaires = coût extra</li>\\n<li>Contrat minimum 3 mois</li>\\n</ul>\", \"it\": \"<h3>🔧 Pacchetto Manutenzione Base - Tranquillità</h3>\\n<p>Manutenzione mensile per il tuo sito web. Ci occupiamo di aggiornamenti, backup e sicurezza. Tu ti concentri sul business.</p>\\n\\n<h4>📋 Cosa include mensilmente:</h4>\\n<ul>\\n<li>Aggiornamenti WordPress/Laravel/CMS</li>\\n<li>Aggiornamenti plugin/pacchetti</li>\\n<li>Backup settimanale automatico</li>\\n<li>Monitoraggio uptime 24/7</li>\\n<li>Scan sicurezza mensile</li>\\n<li>Report di stato mensile</li>\\n<li>1 ora supporto tecnico inclusa</li>\\n</ul>\\n\\n<h4>🛠️ Cosa monitoriamo:</h4>\\n<p>Uptime, prestazioni, errori, sicurezza, certificato SSL, backup</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Il tuo sito sempre aggiornato</li>\\n<li>Problemi rilevati proattivamente</li>\\n<li>Backup per recupero rapido</li>\\n<li>Costo mensile prevedibile</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Non include modifiche di design</li>\\n<li>Ore aggiuntive = costo extra</li>\\n<li>Contratto minimo 3 mesi</li>\\n</ul>\", \"ro\": \"<h3>🔧 Pachet Basic Maintenance - Liniște Sufletească</h3>\\n<p>Mentenanță lunară pentru site-ul tău. Ne ocupăm de actualizări, backup-uri și securitate. Tu te concentrezi pe business.</p>\\n\\n<h4>📋 Ce include lunar:</h4>\\n<ul>\\n<li>Actualizări WordPress/Laravel/CMS</li>\\n<li>Actualizări plugin-uri/pachete</li>\\n<li>Backup săptămânal automat</li>\\n<li>Monitorizare uptime 24/7</li>\\n<li>Scan securitate lunar</li>\\n<li>Raport lunar de stare</li>\\n<li>1 oră suport tehnic inclus</li>\\n</ul>\\n\\n<h4>🛠️ Ce monitorizăm:</h4>\\n<p>Uptime, performanță, erori, securitate, certificat SSL, backup-uri</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Site-ul tău mereu actualizat</li>\\n<li>Probleme detectate proactiv</li>\\n<li>Backup-uri pentru recuperare rapidă</li>\\n<li>Cost predictibil lunar</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Nu include modificări de design</li>\\n<li>Ore suplimentare = cost extra</li>\\n<li>Contract minim 3 luni</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Weekly WordPress and module updates</li><li>Weekly cloud backup</li><li>24/7 uptime monitoring</li><li>Monthly anti-malware scan</li><li>Monthly PDF report</li></ul>\", \"ro\": \"<ul><li>Actualizări săptămânale WordPress și module</li><li>Backup săptămânal cloud</li><li>Monitorizare uptime 24/7</li><li>Scanare lunară anti-malware</li><li>Raport PDF lunar</li></ul>\"}','MAINT-BASIC-001',149.00,'RON',NULL,999,'[\"images/services/mentenanta-basic.jpg\"]','{\"billing\": \"monthly\"}',NULL,0,1,'2025-12-21 08:58:26','2025-12-21 10:40:21'),(19,NULL,'{\"de\": \"Standard-Aktiv-Paket\", \"en\": \"Standard Active Package\", \"es\": \"Paquete Estándar Activo\", \"fr\": \"Forfait Standard Actif\", \"it\": \"Pacchetto Standard Attivo\", \"ro\": \"Pachet Standard Active\"}','pachet-standard-active','{\"de\": \"<h3>⚡ Standard-Aktiv-Paket - Wartung Plus</h3>\\n<p>Alles aus Basic, plus Inhaltsänderungen und Prioritäts-Support. Ideal für aktive Websites, die regelmäßige Updates benötigen.</p>\\n\\n<h4>📋 Was monatlich enthalten ist:</h4>\\n<ul>\\n<li>Alles aus dem Basis-Paket</li>\\n<li>3 Stunden Inhaltsänderungen/Monat</li>\\n<li>Prioritäts-Support (4 Stunden Antwort)</li>\\n<li>Tägliches automatisches Backup</li>\\n<li>Monatliche Leistungsoptimierung</li>\\n<li>SEO-Positionsüberwachung</li>\\n<li>Monatlicher Analytics-Bericht</li>\\n<li>Telefon-Support-Hotline</li>\\n</ul>\\n\\n<h4>🛠️ Enthaltene Dienste:</h4>\\n<p>Inhaltsaktualisierungen, Bilder, Texte, einfache Seitenergänzungen, kleine Fixes</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Schnelle Reaktion auf Anfragen</li>\\n<li>Ihre Website immer aktuell</li>\\n<li>Tägliche Backups = maximale Sicherheit</li>\\n<li>Einzelner Ansprechpartner</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Enthält keine neuen Funktionen</li>\\n<li>Ungenutzte Stunden werden nicht übertragen</li>\\n<li>Mindestens 3 Monate Vertrag</li>\\n</ul>\", \"en\": \"<h3>⚡ Standard Active Package - Maintenance Plus</h3>\\n<p>Everything in Basic, plus content changes and priority support. Ideal for active sites requiring regular updates.</p>\\n\\n<h4>📋 What\'s included monthly:</h4>\\n<ul>\\n<li>Everything from Basic package</li>\\n<li>3 hours content changes/month</li>\\n<li>Priority support (4-hour response)</li>\\n<li>Daily automatic backup</li>\\n<li>Monthly performance optimization</li>\\n<li>SEO position monitoring</li>\\n<li>Monthly analytics report</li>\\n<li>Phone support hotline</li>\\n</ul>\\n\\n<h4>🛠️ Included services:</h4>\\n<p>Content updates, images, texts, simple page additions, minor fixes</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Quick response to requests</li>\\n<li>Your site always fresh</li>\\n<li>Daily backups = maximum safety</li>\\n<li>Single point of contact</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Does not include new features</li>\\n<li>Unused hours do not roll over</li>\\n<li>Minimum 3-month contract</li>\\n</ul>\", \"es\": \"<h3>⚡ Paquete Estándar Activo - Mantenimiento Plus</h3>\\n<p>Todo lo de Básico, más cambios de contenido y soporte prioritario. Ideal para sitios activos que requieren actualizaciones regulares.</p>\\n\\n<h4>📋 Qué incluye mensualmente:</h4>\\n<ul>\\n<li>Todo del paquete Básico</li>\\n<li>3 horas cambios contenido/mes</li>\\n<li>Soporte prioritario (respuesta en 4 horas)</li>\\n<li>Backup diario automático</li>\\n<li>Optimización rendimiento mensual</li>\\n<li>Monitorización posiciones SEO</li>\\n<li>Informe analytics mensual</li>\\n<li>Línea telefónica de soporte</li>\\n</ul>\\n\\n<h4>🛠️ Servicios incluidos:</h4>\\n<p>Actualizaciones contenido, imágenes, textos, adición páginas simples, arreglos menores</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Respuesta rápida a solicitudes</li>\\n<li>Tu sitio siempre fresco</li>\\n<li>Backups diarios = máxima seguridad</li>\\n<li>Un solo punto de contacto</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>No incluye nuevas funcionalidades</li>\\n<li>Horas no usadas no se acumulan</li>\\n<li>Contrato mínimo 3 meses</li>\\n</ul>\", \"fr\": \"<h3>⚡ Forfait Standard Actif - Maintenance Plus</h3>\\n<p>Tout du Basique, plus changements de contenu et support prioritaire. Idéal pour sites actifs nécessitant des mises à jour régulières.</p>\\n\\n<h4>📋 Ce qui est inclus mensuellement:</h4>\\n<ul>\\n<li>Tout du forfait Basique</li>\\n<li>3 heures modifications contenu/mois</li>\\n<li>Support prioritaire (réponse en 4 heures)</li>\\n<li>Sauvegarde quotidienne automatique</li>\\n<li>Optimisation performance mensuelle</li>\\n<li>Surveillance positions SEO</li>\\n<li>Rapport analytics mensuel</li>\\n<li>Hotline téléphonique support</li>\\n</ul>\\n\\n<h4>🛠️ Services inclus:</h4>\\n<p>Mises à jour contenu, images, textes, ajouts pages simples, corrections mineures</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Réponse rapide aux demandes</li>\\n<li>Votre site toujours frais</li>\\n<li>Sauvegardes quotidiennes = sécurité maximale</li>\\n<li>Point de contact unique</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>N\'inclut pas les nouvelles fonctionnalités</li>\\n<li>Heures non utilisées non reportées</li>\\n<li>Contrat minimum 3 mois</li>\\n</ul>\", \"it\": \"<h3>⚡ Pacchetto Standard Attivo - Manutenzione Plus</h3>\\n<p>Tutto dal Base, più modifiche contenuto e supporto prioritario. Ideale per siti attivi che richiedono aggiornamenti regolari.</p>\\n\\n<h4>📋 Cosa include mensilmente:</h4>\\n<ul>\\n<li>Tutto dal pacchetto Base</li>\\n<li>3 ore modifiche contenuto/mese</li>\\n<li>Supporto prioritario (risposta in 4 ore)</li>\\n<li>Backup giornaliero automatico</li>\\n<li>Ottimizzazione prestazioni mensile</li>\\n<li>Monitoraggio posizioni SEO</li>\\n<li>Report analytics mensile</li>\\n<li>Hotline telefonica supporto</li>\\n</ul>\\n\\n<h4>🛠️ Servizi inclusi:</h4>\\n<p>Aggiornamenti contenuto, immagini, testi, aggiunta pagine semplici, fix minori</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Risposta rapida alle richieste</li>\\n<li>Il tuo sito sempre fresco</li>\\n<li>Backup giornalieri = massima sicurezza</li>\\n<li>Unico punto di contatto</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Non include nuove funzionalità</li>\\n<li>Ore non usate non si accumulano</li>\\n<li>Contratto minimo 3 mesi</li>\\n</ul>\", \"ro\": \"<h3>⚡ Pachet Standard Active - Mentenanță Plus</h3>\\n<p>Tot ce include Basic, plus modificări de conținut și suport prioritar. Ideal pentru site-uri active care necesită actualizări regulate.</p>\\n\\n<h4>📋 Ce include lunar:</h4>\\n<ul>\\n<li>Tot din pachetul Basic</li>\\n<li>3 ore modificări conținut/lună</li>\\n<li>Suport prioritar (răspuns în 4 ore)</li>\\n<li>Backup zilnic automat</li>\\n<li>Optimizare performanță lunară</li>\\n<li>Monitorizare poziții SEO</li>\\n<li>Raport analytics lunar</li>\\n<li>Hotline telefonic support</li>\\n</ul>\\n\\n<h4>🛠️ Servicii incluse:</h4>\\n<p>Actualizări conținut, imagini, texte, adăugare pagini simple, fix-uri minore</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Răspuns rapid la solicitări</li>\\n<li>Site-ul tău mereu fresh</li>\\n<li>Backup-uri zilnice = siguranță maximă</li>\\n<li>Un singur punct de contact</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Nu include funcționalități noi</li>\\n<li>Ore nefolosite nu se reportează</li>\\n<li>Contract minim 3 luni</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Everything from Basic package</li><li>Daily backup with free restore</li><li>2 hours content management included</li><li>Priority email/ticket support</li><li>Monthly speed check</li></ul>\", \"ro\": \"<ul><li>Tot din pachetul Basic</li><li>Backup zilnic cu restaurare gratuită</li><li>2 ore administrare conținut incluse</li><li>Suport prioritar email/tichet</li><li>Verificare lunară viteză</li></ul>\"}','MAINT-STD-002',349.00,'RON',NULL,999,'[\"images/services/mentenanta-standard.jpg\"]','{\"billing\": \"monthly\"}',NULL,1,1,'2025-12-21 08:58:26','2025-12-21 10:41:08'),(20,NULL,'{\"de\": \"Premium-Business-Paket\", \"en\": \"Premium Business Package\", \"es\": \"Paquete Premium Business\", \"fr\": \"Forfait Premium Business\", \"it\": \"Pacchetto Premium Business\", \"ro\": \"Pachet Premium Business\"}','pachet-premium-business','{\"de\": \"<h3>🏆 Premium-Business-Paket - Komplette Wartung</h3>\\n<p>Unser All-inclusive Premium-Service für seriöse Unternehmen. Dedizierter Partner, unbegrenzte Stunden, VIP-Support und kontinuierliche Entwicklung.</p>\\n\\n<h4>📋 Was monatlich enthalten ist:</h4>\\n<ul>\\n<li>Alles aus vorherigen Paketen</li>\\n<li>Unbegrenzte Interventionsstunden</li>\\n<li>Dedizierter Account-Manager</li>\\n<li>24/7 VIP-Support (1 Stunde Antwort)</li>\\n<li>Echtzeit-Backup mit sofortiger Wiederherstellung</li>\\n<li>Kontinuierliche Entwicklung (bis 10 Stunden/Monat)</li>\\n<li>Monatliches Sicherheitsaudit</li>\\n<li>Individuelle Executive-Berichte</li>\\n<li>Monatliche Status-Meetings</li>\\n<li>Maximale Priorität bei allen Anfragen</li>\\n</ul>\\n\\n<h4>🛠️ Technologien & Dienste:</h4>\\n<p>Unser gesamter Stack: Laravel, Next.js, DevOps, AI-Integrationen, E-Commerce</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Null Sorgen für Ihre Website</li>\\n<li>Vertrauenswürdiger Langzeitpartner</li>\\n<li>Garantierte 1-Stunden-Antwort</li>\\n<li>Kontinuierliche Entwicklung ohne Zusatzkosten</li>\\n<li>Enterprise-Audit und Sicherheit</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Mindestens 12 Monate Vertrag</li>\\n<li>Große neue Projekte = separater Tarif</li>\\n<li>Bester ROI für seriöses Business</li>\\n</ul>\", \"en\": \"<h3>🏆 Premium Business Package - Complete Maintenance</h3>\\n<p>Our all-inclusive premium service for serious businesses. Dedicated partner, unlimited hours, VIP support and continuous development.</p>\\n\\n<h4>📋 What\'s included monthly:</h4>\\n<ul>\\n<li>Everything from previous packages</li>\\n<li>Unlimited intervention hours</li>\\n<li>Dedicated account manager</li>\\n<li>24/7 VIP support (1-hour response)</li>\\n<li>Real-time backup with instant restore</li>\\n<li>Continuous development (up to 10 hours/month)</li>\\n<li>Monthly security audit</li>\\n<li>Custom executive reports</li>\\n<li>Monthly status meetings</li>\\n<li>Maximum priority on all requests</li>\\n</ul>\\n\\n<h4>🛠️ Technologies & Services:</h4>\\n<p>Our entire stack: Laravel, Next.js, DevOps, AI integrations, e-commerce</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Zero worries for your site</li>\\n<li>Trusted long-term partner</li>\\n<li>Guaranteed 1-hour response</li>\\n<li>Continuous development at no extra cost</li>\\n<li>Enterprise audit and security</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Minimum 12-month contract</li>\\n<li>Major new projects = separate rate</li>\\n<li>Best ROI for serious business</li>\\n</ul>\", \"es\": \"<h3>🏆 Paquete Premium Business - Mantenimiento Completo</h3>\\n<p>Nuestro servicio premium todo incluido para negocios serios. Socio dedicado, horas ilimitadas, soporte VIP y desarrollo continuo.</p>\\n\\n<h4>📋 Qué incluye mensualmente:</h4>\\n<ul>\\n<li>Todo de los paquetes anteriores</li>\\n<li>Horas ilimitadas de intervención</li>\\n<li>Account manager dedicado</li>\\n<li>Soporte VIP 24/7 (respuesta en 1 hora)</li>\\n<li>Backup en tiempo real con restauración instantánea</li>\\n<li>Desarrollo continuo (hasta 10 horas/mes)</li>\\n<li>Auditoría seguridad mensual</li>\\n<li>Informes ejecutivos personalizados</li>\\n<li>Reuniones mensuales de estado</li>\\n<li>Máxima prioridad en todas las solicitudes</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías y Servicios:</h4>\\n<p>Todo nuestro stack: Laravel, Next.js, DevOps, integraciones AI, e-commerce</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Cero preocupaciones para tu sitio</li>\\n<li>Socio de confianza a largo plazo</li>\\n<li>Respuesta garantizada en 1 hora</li>\\n<li>Desarrollo continuo sin costes extra</li>\\n<li>Auditoría y seguridad enterprise</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Contrato mínimo 12 meses</li>\\n<li>Proyectos nuevos grandes = tarifa separada</li>\\n<li>Mejor ROI para negocio serio</li>\\n</ul>\", \"fr\": \"<h3>🏆 Forfait Premium Business - Maintenance Complète</h3>\\n<p>Notre service premium tout compris pour les entreprises sérieuses. Partenaire dédié, heures illimitées, support VIP et développement continu.</p>\\n\\n<h4>📋 Ce qui est inclus mensuellement:</h4>\\n<ul>\\n<li>Tout des forfaits précédents</li>\\n<li>Heures d\'intervention illimitées</li>\\n<li>Account manager dédié</li>\\n<li>Support VIP 24/7 (réponse en 1 heure)</li>\\n<li>Sauvegarde temps réel avec restauration instantanée</li>\\n<li>Développement continu (jusqu\'à 10 heures/mois)</li>\\n<li>Audit sécurité mensuel</li>\\n<li>Rapports exécutifs personnalisés</li>\\n<li>Réunions mensuelles de statut</li>\\n<li>Priorité maximale sur toutes les demandes</li>\\n</ul>\\n\\n<h4>🛠️ Technologies et Services:</h4>\\n<p>Toute notre stack: Laravel, Next.js, DevOps, intégrations AI, e-commerce</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Zéro souci pour votre site</li>\\n<li>Partenaire de confiance à long terme</li>\\n<li>Réponse garantie en 1 heure</li>\\n<li>Développement continu sans frais supplémentaires</li>\\n<li>Audit et sécurité enterprise</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Contrat minimum 12 mois</li>\\n<li>Nouveaux grands projets = tarif séparé</li>\\n<li>Meilleur ROI pour business sérieux</li>\\n</ul>\", \"it\": \"<h3>🏆 Pacchetto Premium Business - Manutenzione Completa</h3>\\n<p>Il nostro servizio premium all-inclusive per business seri. Partner dedicato, ore illimitate, supporto VIP e sviluppo continuo.</p>\\n\\n<h4>📋 Cosa include mensilmente:</h4>\\n<ul>\\n<li>Tutto dai pacchetti precedenti</li>\\n<li>Ore di intervento illimitate</li>\\n<li>Account manager dedicato</li>\\n<li>Supporto VIP 24/7 (risposta in 1 ora)</li>\\n<li>Backup in tempo reale con ripristino istantaneo</li>\\n<li>Sviluppo continuo (fino a 10 ore/mese)</li>\\n<li>Audit sicurezza mensile</li>\\n<li>Report esecutivi personalizzati</li>\\n<li>Meeting mensili di stato</li>\\n<li>Massima priorità su tutte le richieste</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie e Servizi:</h4>\\n<p>Tutto il nostro stack: Laravel, Next.js, DevOps, integrazioni AI, e-commerce</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Zero preoccupazioni per il tuo sito</li>\\n<li>Partner affidabile a lungo termine</li>\\n<li>Risposta garantita in 1 ora</li>\\n<li>Sviluppo continuo senza costi extra</li>\\n<li>Audit e sicurezza enterprise</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Contratto minimo 12 mesi</li>\\n<li>Nuovi grandi progetti = tariffa separata</li>\\n<li>Miglior ROI per business serio</li>\\n</ul>\", \"ro\": \"<h3>🏆 Pachet Premium Business - Mentenanță Completă</h3>\\n<p>Serviciul nostru premium all-inclusive pentru business-uri serioase. Partener dedicat, ore nelimitate, suport VIP și dezvoltare continuă.</p>\\n\\n<h4>📋 Ce include lunar:</h4>\\n<ul>\\n<li>Tot din pachetele anterioare</li>\\n<li>Ore nelimitate de intervenții</li>\\n<li>Account manager dedicat</li>\\n<li>Suport VIP 24/7 (răspuns în 1 oră)</li>\\n<li>Backup în timp real cu restore instant</li>\\n<li>Dezvoltare continuă (până la 10 ore/lună)</li>\\n<li>Audit securitate lunar</li>\\n<li>Rapoarte executive personalizate</li>\\n<li>Meeting-uri lunare de status</li>\\n<li>Prioritate maximă la toate solicitările</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii & Servicii:</h4>\\n<p>Tot stack-ul nostru: Laravel, Next.js, DevOps, AI integrations, e-commerce</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Zero griji pentru site-ul tău</li>\\n<li>Partener de încredere pe termen lung</li>\\n<li>Răspuns garantat în 1 oră</li>\\n<li>Dezvoltare continuă fără costuri extra</li>\\n<li>Audit și securitate enterprise</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Contract minim 12 luni</li>\\n<li>Proiecte mari noi = tarif separat</li>\\n<li>Cel mai bun ROI pentru business serios</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>5 hours of monthly work</li><li>Premium plugin licenses included</li><li>Quarterly technical SEO audit</li><li>Staging testing before launch</li></ul>\", \"ro\": \"<ul><li>5 ore de lucru lunar</li><li>Licențe plugin-uri premium incluse</li><li>Audit SEO tehnic trimestrial</li><li>Testare pe staging înainte de lansare</li></ul>\"}','MAINT-PREM-003',649.00,'RON',NULL,999,'[\"images/services/mentenanta-premium.jpg\"]','{\"billing\": \"monthly\"}',NULL,1,1,'2025-12-21 08:58:26','2025-12-21 10:43:05'),(21,NULL,'{\"de\": \"Shop-Starter-Paket\", \"en\": \"Shop Starter Package\", \"es\": \"Paquete Shop Starter\", \"fr\": \"Forfait Shop Starter\", \"it\": \"Pacchetto Shop Starter\", \"ro\": \"Pachet Shop Starter\"}','pachet-shop-starter','{\"de\": \"<h3>🛒 Shop-Starter-Paket - Online verkaufen starten</h3>\\n<p>Das perfekte Paket für Ihren ersten Online-Shop. Komplette Einrichtung, professionelles Design und alle wesentlichen Funktionen.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>E-Commerce-Plattform Installation und Konfiguration</li>\\n<li>Individuelles responsives Design</li>\\n<li>Bis zu 50 initiale Produkte</li>\\n<li>Zahlungsintegration (Karte, PayPal)</li>\\n<li>Versand- und Steuerkonfiguration</li>\\n<li>Wesentliche Seiten (über uns, Kontakt, Richtlinien)</li>\\n<li>SSL-Zertifikat inklusive</li>\\n<li>Admin-Panel Schulung</li>\\n<li>30 Tage Support nach Launch</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>WooCommerce, PrestaShop oder Shopify, Stripe/PayPal Zahlungsintegrationen, SSL Let\'s Encrypt</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Schneller Start - Shop live in 7-10 Tagen</li>\\n<li>Alles was Sie zum Start brauchen</li>\\n<li>Professionelles Design out-of-the-box</li>\\n<li>Schulung inklusive - Sie sind unabhängig</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Enthält keine komplexen Konfigurationen (B2B, Marktplatz)</li>\\n<li>Produkte über 50 = Zusatzkosten</li>\\n<li>Hosting nicht inklusive</li>\\n</ul>\", \"en\": \"<h3>🛒 Shop Starter Package - Start Selling Online</h3>\\n<p>The perfect package to launch your first online store. Complete setup, professional design and all essential features to start selling.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>E-commerce platform installation and configuration</li>\\n<li>Custom responsive design</li>\\n<li>Up to 50 initial products</li>\\n<li>Payment integration (card, PayPal)</li>\\n<li>Shipping and tax configuration</li>\\n<li>Essential pages (about, contact, policies)</li>\\n<li>SSL certificate included</li>\\n<li>Admin panel usage training</li>\\n<li>30 days post-launch support</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>WooCommerce, PrestaShop or Shopify, Stripe/PayPal payment integrations, SSL Let\'s Encrypt</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Quick start - store live in 7-10 days</li>\\n<li>Everything you need to get started</li>\\n<li>Professional design out-of-the-box</li>\\n<li>Training included - you\'re independent</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Does not include complex configurations (B2B, marketplace)</li>\\n<li>Products over 50 = additional cost</li>\\n<li>Hosting not included</li>\\n</ul>\", \"es\": \"<h3>🛒 Paquete Shop Starter - Empieza a vender online</h3>\\n<p>El paquete perfecto para lanzar tu primera tienda online. Configuración completa, diseño profesional y todas las funcionalidades esenciales.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Instalación y configuración plataforma e-commerce</li>\\n<li>Diseño responsive personalizado</li>\\n<li>Hasta 50 productos iniciales</li>\\n<li>Integración pagos (tarjeta, PayPal)</li>\\n<li>Configuración envío e impuestos</li>\\n<li>Páginas esenciales (sobre, contacto, políticas)</li>\\n<li>Certificado SSL incluido</li>\\n<li>Formación uso panel admin</li>\\n<li>30 días soporte post-lanzamiento</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>WooCommerce, PrestaShop o Shopify, integraciones pago Stripe/PayPal, SSL Let\'s Encrypt</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Inicio rápido - tienda activa en 7-10 días</li>\\n<li>Todo lo que necesitas para empezar</li>\\n<li>Diseño profesional listo para usar</li>\\n<li>Formación incluida - eres independiente</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>No incluye configuraciones complejas (B2B, marketplace)</li>\\n<li>Productos sobre 50 = coste adicional</li>\\n<li>Hosting no incluido</li>\\n</ul>\", \"fr\": \"<h3>🛒 Forfait Shop Starter - Commencez à vendre en ligne</h3>\\n<p>Le forfait parfait pour lancer votre première boutique en ligne. Configuration complète, design professionnel et toutes les fonctionnalités essentielles.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Installation et configuration plateforme e-commerce</li>\\n<li>Design responsive personnalisé</li>\\n<li>Jusqu\'à 50 produits initiaux</li>\\n<li>Intégration paiements (carte, PayPal)</li>\\n<li>Configuration livraison et taxes</li>\\n<li>Pages essentielles (à propos, contact, politiques)</li>\\n<li>Certificat SSL inclus</li>\\n<li>Formation utilisation panel admin</li>\\n<li>30 jours support post-lancement</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>WooCommerce, PrestaShop ou Shopify, intégrations paiement Stripe/PayPal, SSL Let\'s Encrypt</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Démarrage rapide - boutique en ligne en 7-10 jours</li>\\n<li>Tout ce dont vous avez besoin pour commencer</li>\\n<li>Design professionnel prêt à l\'emploi</li>\\n<li>Formation incluse - vous êtes indépendant</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>N\'inclut pas configurations complexes (B2B, marketplace)</li>\\n<li>Produits au-delà de 50 = coût supplémentaire</li>\\n<li>Hébergement non inclus</li>\\n</ul>\", \"it\": \"<h3>🛒 Pacchetto Shop Starter - Inizia a vendere online</h3>\\n<p>Il pacchetto perfetto per lanciare il tuo primo negozio online. Configurazione completa, design professionale e tutte le funzionalità essenziali.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Installazione e configurazione piattaforma e-commerce</li>\\n<li>Design responsive personalizzato</li>\\n<li>Fino a 50 prodotti iniziali</li>\\n<li>Integrazione pagamenti (carta, PayPal)</li>\\n<li>Configurazione spedizione e tasse</li>\\n<li>Pagine essenziali (chi siamo, contatto, policy)</li>\\n<li>Certificato SSL incluso</li>\\n<li>Formazione uso pannello admin</li>\\n<li>30 giorni supporto post-lancio</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>WooCommerce, PrestaShop o Shopify, integrazioni pagamento Stripe/PayPal, SSL Let\'s Encrypt</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Avvio rapido - negozio online in 7-10 giorni</li>\\n<li>Tutto ciò di cui hai bisogno per iniziare</li>\\n<li>Design professionale pronto all\'uso</li>\\n<li>Formazione inclusa - sei indipendente</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Non include configurazioni complesse (B2B, marketplace)</li>\\n<li>Prodotti oltre 50 = costo aggiuntivo</li>\\n<li>Hosting non incluso</li>\\n</ul>\", \"ro\": \"<h3>🛒 Pachet Shop Starter - Începe să vinzi online</h3>\\n<p>Pachetul perfect pentru a-ți lansa primul magazin online. Configurare completă, design profesional și toate funcționalitățile esențiale pentru a începe să vinzi.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Instalare și configurare platformă e-commerce</li>\\n<li>Design responsive personalizat</li>\\n<li>Până la 50 produse inițiale</li>\\n<li>Integrare plăți (card, PayPal)</li>\\n<li>Configurare livrare și taxe</li>\\n<li>Pagini esențiale (despre, contact, politici)</li>\\n<li>SSL certificat inclus</li>\\n<li>Training utilizare admin panel</li>\\n<li>30 zile suport post-lansare</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>WooCommerce, PrestaShop sau Shopify, integrări plăți Stripe/PayPal, SSL Let\'s Encrypt</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Start rapid - magazin live în 7-10 zile</li>\\n<li>Tot ce ai nevoie pentru a începe</li>\\n<li>Design profesional out-of-the-box</li>\\n<li>Training inclus - ești independent</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Nu include configurări complexe (B2B, marketplace)</li>\\n<li>Produse peste 50 = cost suplimentar</li>\\n<li>Hosting nu este inclus</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Tested critical WooCommerce updates</li><li>Database optimization</li><li>Shopping cart monitoring</li><li>3 hours catalog management</li></ul>\", \"ro\": \"<ul><li>Actualizări critice WooCommerce testate</li><li>Optimizare bază de date</li><li>Monitorizare coș de cumpărături</li><li>3 ore administrare catalog</li></ul>\"}','MAINT-SHOP-004',899.00,'RON',NULL,999,'[\"images/services/shop-starter.jpg\"]',NULL,NULL,0,1,'2025-12-21 08:58:52','2025-12-21 10:43:58'),(22,NULL,'{\"de\": \"Shop-Wachstums-Paket\", \"en\": \"Shop Growth Package\", \"es\": \"Paquete Shop Growth\", \"fr\": \"Forfait Shop Growth\", \"it\": \"Pacchetto Shop Growth\", \"ro\": \"Pachet Shop Growth\"}','pachet-shop-growth','{\"de\": \"<h3>📈 Shop-Wachstums-Paket - Skalieren Sie Ihren Umsatz</h3>\\n<p>Für Online-Shops, die wachsen wollen. Erweiterte Funktionen, Automatisierungen, Marktplatz-Integrationen und Optimierungen für bessere Konversionen.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Alles aus Shop Starter</li>\\n<li>Bis zu 500 Produkte</li>\\n<li>Marktplatz-Integration (Amazon, eBay)</li>\\n<li>Bestands- und Preisautomatisierung</li>\\n<li>Integriertes E-Mail-Marketing</li>\\n<li>Konversionsoptimierter Checkout</li>\\n<li>Warenkorbabbruch-Wiederherstellung</li>\\n<li>Erweitertes SEO für Produkte</li>\\n<li>Analytics und Verkaufsberichte</li>\\n<li>ERP/Rechnungsintegration</li>\\n<li>60 Tage Support nach Launch</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>WooCommerce/Shopify Plus, Marktplatz-API-Integrationen, Klaviyo/Mailchimp, Google Analytics 4, ERP-Integrationen</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Verkaufen auf mehreren Kanälen von einem Ort</li>\\n<li>Zeitsparende Automatisierungen</li>\\n<li>Erhöhte Konversionen mit optimiertem Checkout</li>\\n<li>Daten und Insights für bessere Entscheidungen</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Lieferzeit 3-4 Wochen</li>\\n<li>Erfordert Marketing-Investment</li>\\n<li>Produkte über 500 = Upgrade nötig</li>\\n</ul>\", \"en\": \"<h3>📈 Shop Growth Package - Scale Your Sales</h3>\\n<p>For online stores ready to grow. Advanced features, automations, marketplace integrations and optimizations for better conversions.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Everything from Shop Starter</li>\\n<li>Up to 500 products</li>\\n<li>Marketplace integration (eMAG, OLX, eBay)</li>\\n<li>Stock and price automation</li>\\n<li>Integrated email marketing</li>\\n<li>Conversion-optimized checkout</li>\\n<li>Abandoned cart recovery</li>\\n<li>Advanced SEO for products</li>\\n<li>Analytics and sales reports</li>\\n<li>ERP/invoicing integration</li>\\n<li>60 days post-launch support</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>WooCommerce/Shopify Plus, marketplace API integrations, Klaviyo/Mailchimp, Google Analytics 4, SmartBill/Saga integrations</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Sell on multiple channels from one place</li>\\n<li>Time-saving automations</li>\\n<li>Increased conversions with optimized checkout</li>\\n<li>Data and insights for better decisions</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Delivery time 3-4 weeks</li>\\n<li>Requires marketing investment</li>\\n<li>Products over 500 = upgrade needed</li>\\n</ul>\", \"es\": \"<h3>📈 Paquete Shop Growth - Escala tus ventas</h3>\\n<p>Para tiendas online listas para crecer. Funcionalidades avanzadas, automatizaciones, integraciones marketplace y optimizaciones para mejores conversiones.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Todo de Shop Starter</li>\\n<li>Hasta 500 productos</li>\\n<li>Integración marketplace (Amazon, eBay)</li>\\n<li>Automatización stocks y precios</li>\\n<li>Email marketing integrado</li>\\n<li>Checkout optimizado para conversiones</li>\\n<li>Recuperación carritos abandonados</li>\\n<li>SEO avanzado para productos</li>\\n<li>Analytics e informes ventas</li>\\n<li>Integración ERP/facturación</li>\\n<li>60 días soporte post-lanzamiento</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>WooCommerce/Shopify Plus, integraciones API marketplace, Klaviyo/Mailchimp, Google Analytics 4, integraciones ERP</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Vende en múltiples canales desde un lugar</li>\\n<li>Automatizaciones que ahorran tiempo</li>\\n<li>Más conversiones con checkout optimizado</li>\\n<li>Datos e insights para mejores decisiones</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Tiempo entrega 3-4 semanas</li>\\n<li>Requiere inversión en marketing</li>\\n<li>Productos sobre 500 = upgrade necesario</li>\\n</ul>\", \"fr\": \"<h3>📈 Forfait Shop Growth - Scalez vos ventes</h3>\\n<p>Pour les boutiques en ligne prêtes à grandir. Fonctionnalités avancées, automatisations, intégrations marketplace et optimisations pour meilleures conversions.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Tout de Shop Starter</li>\\n<li>Jusqu\'à 500 produits</li>\\n<li>Intégration marketplace (Amazon, eBay)</li>\\n<li>Automatisation stocks et prix</li>\\n<li>Email marketing intégré</li>\\n<li>Checkout optimisé conversions</li>\\n<li>Récupération paniers abandonnés</li>\\n<li>SEO avancé pour produits</li>\\n<li>Analytics et rapports ventes</li>\\n<li>Intégration ERP/facturation</li>\\n<li>60 jours support post-lancement</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>WooCommerce/Shopify Plus, intégrations API marketplace, Klaviyo/Mailchimp, Google Analytics 4, intégrations ERP</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Vendez sur plusieurs canaux depuis un seul endroit</li>\\n<li>Automatisations qui font gagner du temps</li>\\n<li>Plus de conversions avec checkout optimisé</li>\\n<li>Données et insights pour meilleures décisions</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Délai livraison 3-4 semaines</li>\\n<li>Nécessite investissement marketing</li>\\n<li>Produits au-delà de 500 = upgrade nécessaire</li>\\n</ul>\", \"it\": \"<h3>📈 Pacchetto Shop Growth - Scala le tue vendite</h3>\\n<p>Per negozi online pronti a crescere. Funzionalità avanzate, automazioni, integrazioni marketplace e ottimizzazioni per conversioni migliori.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Tutto da Shop Starter</li>\\n<li>Fino a 500 prodotti</li>\\n<li>Integrazione marketplace (Amazon, eBay)</li>\\n<li>Automazione scorte e prezzi</li>\\n<li>Email marketing integrato</li>\\n<li>Checkout ottimizzato conversioni</li>\\n<li>Recupero carrelli abbandonati</li>\\n<li>SEO avanzato per prodotti</li>\\n<li>Analytics e report vendite</li>\\n<li>Integrazione ERP/fatturazione</li>\\n<li>60 giorni supporto post-lancio</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>WooCommerce/Shopify Plus, integrazioni API marketplace, Klaviyo/Mailchimp, Google Analytics 4, integrazioni ERP</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Vendi su più canali da un unico posto</li>\\n<li>Automazioni che fanno risparmiare tempo</li>\\n<li>Più conversioni con checkout ottimizzato</li>\\n<li>Dati e insights per decisioni migliori</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Tempo consegna 3-4 settimane</li>\\n<li>Richiede investimento marketing</li>\\n<li>Prodotti oltre 500 = upgrade necessario</li>\\n</ul>\", \"ro\": \"<h3>📈 Pachet Shop Growth - Scalează vânzările</h3>\\n<p>Pentru magazinele online care vor să crească. Funcționalități avansate, automatizări, integrări marketplace și optimizări pentru conversii mai bune.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Tot din Shop Starter</li>\\n<li>Până la 500 produse</li>\\n<li>Integrare marketplace (eMAG, OLX, eBay)</li>\\n<li>Automatizare stocuri și prețuri</li>\\n<li>Email marketing integrat</li>\\n<li>Checkout optimizat pentru conversii</li>\\n<li>Recuperare coșuri abandonate</li>\\n<li>SEO avansat pentru produse</li>\\n<li>Analytics și rapoarte vânzări</li>\\n<li>Integrare ERP/facturare</li>\\n<li>60 zile suport post-lansare</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>WooCommerce/Shopify Plus, API integrări marketplace, Klaviyo/Mailchimp, Google Analytics 4, integrări SmartBill/Saga</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Vinde pe multiple canale dintr-un singur loc</li>\\n<li>Automatizări care economisesc timp</li>\\n<li>Creștere conversii cu checkout optimizat</li>\\n<li>Date și insights pentru decizii mai bune</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Timp livrare 3-4 săptămâni</li>\\n<li>Necesită investiție în marketing</li>\\n<li>Produse peste 500 = upgrade necesar</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>8-10 hours administration and development</li><li>CDN configuration and management</li><li>Google Merchant/Facebook feed monitoring</li><li>Direct phone support for emergencies</li></ul>\", \"ro\": \"<ul><li>8-10 ore administrare și dezvoltare</li><li>Configurare și administrare CDN</li><li>Monitorizare feed-uri Google Merchant/Facebook</li><li>Suport telefonic direct pentru urgențe</li></ul>\"}','MAINT-SHOPG-005',1999.00,'RON',NULL,999,'[\"images/services/shop-growth.jpg\"]',NULL,NULL,1,1,'2025-12-21 08:58:52','2025-12-21 10:44:48'),(23,NULL,'{\"de\": \"Einfache Produkthinzufügung\", \"en\": \"Simple Product Addition\", \"es\": \"Adición Producto Simple\", \"fr\": \"Ajout Produit Simple\", \"it\": \"Aggiunta Prodotto Semplice\", \"ro\": \"Adăugare Produs Simplu\"}','adaugare-produs-simplu','{\"de\": \"<h3>📦 Einfache Produkthinzufügung - Schnell und Effizient</h3>\\n<p>Schneller Produkthinzufügungs-Service für Ihren Online-Shop. Ideal wenn Sie Hilfe beim Hinzufügen neuer Produkte brauchen.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Produkt zum Katalog hinzufügen</li>\\n<li>Optimierter Titel und Beschreibung</li>\\n<li>Upload und Optimierung von 3 Bildern</li>\\n<li>Preis- und Lagereinstellung</li>\\n<li>Kategoriezuordnung</li>\\n<li>Einfache Varianten (Größe, Farbe)</li>\\n<li>Lieferung innerhalb 24h</li>\\n</ul>\\n\\n<h4>🛠️ Unterstützte Plattformen:</h4>\\n<p>WooCommerce, PrestaShop, Shopify, Magento, jedes E-Commerce CMS</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Super schnell - Produkt in 24 Stunden online</li>\\n<li>Erschwinglicher Preis</li>\\n<li>Sparen Sie wertvolle Zeit</li>\\n<li>Professionelle Beschreibung inklusive</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Enthält kein erweitertes SEO-Copywriting</li>\\n<li>Maximal 3 Bilder pro Produkt</li>\\n<li>Komplexe Produkte = anderer Service</li>\\n</ul>\", \"en\": \"<h3>📦 Simple Product Addition - Fast and Efficient</h3>\\n<p>Quick product addition service for your online store. Ideal when you need help adding new products hassle-free.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Product addition to catalog</li>\\n<li>Optimized title and description</li>\\n<li>Upload and optimize 3 images</li>\\n<li>Price and stock setup</li>\\n<li>Category assignment</li>\\n<li>Simple variants (size, color)</li>\\n<li>Delivery within 24h</li>\\n</ul>\\n\\n<h4>🛠️ Supported platforms:</h4>\\n<p>WooCommerce, PrestaShop, Shopify, Magento, any e-commerce CMS</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Super fast - product live in 24 hours</li>\\n<li>Affordable price</li>\\n<li>Save precious time</li>\\n<li>Professional description included</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Does not include advanced SEO copywriting</li>\\n<li>Maximum 3 images per product</li>\\n<li>Complex products = different service</li>\\n</ul>\", \"es\": \"<h3>📦 Adición Producto Simple - Rápido y Eficiente</h3>\\n<p>Servicio rápido de adición de productos para tu tienda online. Ideal cuando necesitas ayuda para añadir nuevos productos sin complicaciones.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Adición producto al catálogo</li>\\n<li>Título y descripción optimizados</li>\\n<li>Subida y optimización de 3 imágenes</li>\\n<li>Configuración precio y stock</li>\\n<li>Asignación categoría</li>\\n<li>Variantes simples (talla, color)</li>\\n<li>Entrega en 24h</li>\\n</ul>\\n\\n<h4>🛠️ Plataformas soportadas:</h4>\\n<p>WooCommerce, PrestaShop, Shopify, Magento, cualquier CMS e-commerce</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Super rápido - producto activo en 24 horas</li>\\n<li>Precio asequible</li>\\n<li>Ahorra tiempo valioso</li>\\n<li>Descripción profesional incluida</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>No incluye copywriting SEO avanzado</li>\\n<li>Máximo 3 imágenes por producto</li>\\n<li>Productos complejos = otro servicio</li>\\n</ul>\", \"fr\": \"<h3>📦 Ajout Produit Simple - Rapide et Efficace</h3>\\n<p>Service rapide d\'ajout de produit pour votre boutique en ligne. Idéal quand vous avez besoin d\'aide pour ajouter de nouveaux produits.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Ajout produit au catalogue</li>\\n<li>Titre et description optimisés</li>\\n<li>Upload et optimisation de 3 images</li>\\n<li>Configuration prix et stock</li>\\n<li>Attribution catégorie</li>\\n<li>Variantes simples (taille, couleur)</li>\\n<li>Livraison sous 24h</li>\\n</ul>\\n\\n<h4>🛠️ Plateformes supportées:</h4>\\n<p>WooCommerce, PrestaShop, Shopify, Magento, tout CMS e-commerce</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Super rapide - produit en ligne en 24 heures</li>\\n<li>Prix abordable</li>\\n<li>Économisez du temps précieux</li>\\n<li>Description professionnelle incluse</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>N\'inclut pas copywriting SEO avancé</li>\\n<li>Maximum 3 images par produit</li>\\n<li>Produits complexes = autre service</li>\\n</ul>\", \"it\": \"<h3>📦 Aggiunta Prodotto Semplice - Veloce ed Efficiente</h3>\\n<p>Servizio rapido di aggiunta prodotto per il tuo negozio online. Ideale quando hai bisogno di aiuto per aggiungere nuovi prodotti senza problemi.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Aggiunta prodotto al catalogo</li>\\n<li>Titolo e descrizione ottimizzati</li>\\n<li>Upload e ottimizzazione 3 immagini</li>\\n<li>Impostazione prezzo e stock</li>\\n<li>Assegnazione categoria</li>\\n<li>Varianti semplici (taglia, colore)</li>\\n<li>Consegna entro 24h</li>\\n</ul>\\n\\n<h4>🛠️ Piattaforme supportate:</h4>\\n<p>WooCommerce, PrestaShop, Shopify, Magento, qualsiasi CMS e-commerce</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Super veloce - prodotto online in 24 ore</li>\\n<li>Prezzo accessibile</li>\\n<li>Risparmia tempo prezioso</li>\\n<li>Descrizione professionale inclusa</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Non include copywriting SEO avanzato</li>\\n<li>Massimo 3 immagini per prodotto</li>\\n<li>Prodotti complessi = altro servizio</li>\\n</ul>\", \"ro\": \"<h3>📦 Adăugare Produs Simplu - Rapid și Eficient</h3>\\n<p>Serviciu rapid de adăugare produs în magazinul tău online. Ideal când ai nevoie de ajutor pentru a adăuga produse noi fără bătăi de cap.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Adăugare produs în catalog</li>\\n<li>Titlu și descriere optimizate</li>\\n<li>Uploadare și optimizare 3 imagini</li>\\n<li>Setare preț și stoc</li>\\n<li>Atribuire categorie</li>\\n<li>Variante simple (mărime, culoare)</li>\\n<li>Livrare în 24h</li>\\n</ul>\\n\\n<h4>🛠️ Platforme suportate:</h4>\\n<p>WooCommerce, PrestaShop, Shopify, Magento, orice CMS e-commerce</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Super rapid - produs live în 24 ore</li>\\n<li>Preț accesibil</li>\\n<li>Economisești timp prețios</li>\\n<li>Descriere profesională inclusă</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Nu include copywriting avansat SEO</li>\\n<li>Maxim 3 imagini per produs</li>\\n<li>Produsele complexe = alt serviciu</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Copy provided data</li><li>Upload 1-2 images</li><li>Proper category placement</li></ul>\", \"ro\": \"<ul><li>Copiere date furnizate</li><li>Încărcare 1-2 imagini</li><li>Încadrare în categoria corectă</li></ul>\"}','CONT-SIMP-006',9.00,'RON',NULL,999,'[\"images/services/produs-simplu.jpg\"]',NULL,NULL,0,1,'2025-12-21 08:58:52','2025-12-21 10:45:31'),(24,NULL,'{\"de\": \"Komplexe Produkthinzufügung (SEO)\", \"en\": \"Complex Product Addition (SEO)\", \"es\": \"Adición Producto Complejo (SEO)\", \"fr\": \"Ajout Produit Complexe (SEO)\", \"it\": \"Aggiunta Prodotto Complesso (SEO)\", \"ro\": \"Adăugare Produs Complex (SEO)\"}','adaugare-produs-complex-seo','{\"de\": \"<h3>🎯 Komplexe Produkthinzufügung mit SEO - Maximale Sichtbarkeit</h3>\\n<p>Premium Produkthinzufügungs-Service mit kompletter SEO-Optimierung. Beschreibungen die verkaufen, professionelle Bilder und Suchmaschinen-Sichtbarkeit.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Alles aus Einfaches Produkt</li>\\n<li>Professionelle SEO-Copywriting Beschreibung</li>\\n<li>Bis zu 10 optimierte Bilder</li>\\n<li>Meta-Titel und Meta-Beschreibung</li>\\n<li>Optimierter URL-Slug</li>\\n<li>Mehrere Varianten mit Bildern</li>\\n<li>Verwandte Produkte/Cross-Sell Einrichtung</li>\\n<li>Schema-Markup für Rich Snippets</li>\\n<li>Formatierte technische Spezifikationen</li>\\n<li>Lieferung innerhalb 48h</li>\\n</ul>\\n\\n<h4>🛠️ Technologien & Tools:</h4>\\n<p>Yoast SEO, Rank Math, Keyword-Analyse, WebP Bildoptimierung, Schema.org Markup</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Produkt für Google optimiert</li>\\n<li>Beschreibungen die konvertieren</li>\\n<li>Rich Snippets in Suchergebnissen</li>\\n<li>Mehrere Bilder = Kundenvertrauen</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Erfordert detaillierte Informationen von Ihnen</li>\\n<li>SEO-Ergebnisse = Zeit (1-3 Monate)</li>\\n<li>Professionelle Fotos = Ihre Verantwortung</li>\\n</ul>\", \"en\": \"<h3>🎯 Complex Product Addition with SEO - Maximum Visibility</h3>\\n<p>Premium product addition service with complete SEO optimization. Descriptions that sell, professional images and search engine visibility.</p>\\n\\n<h4>📋 What\'s included:</h4>\\n<ul>\\n<li>Everything from Simple Product</li>\\n<li>Professional SEO copywriting description</li>\\n<li>Up to 10 optimized images</li>\\n<li>Meta title and meta description</li>\\n<li>Optimized URL slug</li>\\n<li>Multiple variants with images</li>\\n<li>Related products/cross-sell setup</li>\\n<li>Schema markup for rich snippets</li>\\n<li>Formatted technical specifications</li>\\n<li>Delivery within 48h</li>\\n</ul>\\n\\n<h4>🛠️ Technologies & Tools:</h4>\\n<p>Yoast SEO, Rank Math, keyword analysis, WebP image optimization, Schema.org markup</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Product optimized for Google</li>\\n<li>Descriptions that convert</li>\\n<li>Rich snippets in search results</li>\\n<li>Multiple images = customer trust</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Requires detailed information from you</li>\\n<li>SEO results = time (1-3 months)</li>\\n<li>Professional photos = your responsibility</li>\\n</ul>\", \"es\": \"<h3>🎯 Adición Producto Complejo con SEO - Visibilidad Máxima</h3>\\n<p>Servicio premium de adición de producto con optimización SEO completa. Descripciones que venden, imágenes profesionales y visibilidad en buscadores.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Todo de Producto Simple</li>\\n<li>Descripción SEO copywriting profesional</li>\\n<li>Hasta 10 imágenes optimizadas</li>\\n<li>Meta título y meta descripción</li>\\n<li>URL slug optimizado</li>\\n<li>Múltiples variantes con imágenes</li>\\n<li>Configuración productos relacionados/cross-sell</li>\\n<li>Schema markup para rich snippets</li>\\n<li>Especificaciones técnicas formateadas</li>\\n<li>Entrega en 48h</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías y Herramientas:</h4>\\n<p>Yoast SEO, Rank Math, análisis palabras clave, optimización imágenes WebP, Schema.org markup</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Producto optimizado para Google</li>\\n<li>Descripciones que convierten</li>\\n<li>Rich snippets en resultados búsqueda</li>\\n<li>Múltiples imágenes = confianza cliente</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Requiere información detallada de tu parte</li>\\n<li>Resultados SEO = tiempo (1-3 meses)</li>\\n<li>Fotos profesionales = tu responsabilidad</li>\\n</ul>\", \"fr\": \"<h3>🎯 Ajout Produit Complexe avec SEO - Visibilité Maximale</h3>\\n<p>Service premium d\'ajout de produit avec optimisation SEO complète. Descriptions qui vendent, images professionnelles et visibilité moteurs de recherche.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Tout de Produit Simple</li>\\n<li>Description SEO copywriting professionnel</li>\\n<li>Jusqu\'à 10 images optimisées</li>\\n<li>Meta titre et meta description</li>\\n<li>URL slug optimisé</li>\\n<li>Variantes multiples avec images</li>\\n<li>Configuration produits liés/cross-sell</li>\\n<li>Schema markup pour rich snippets</li>\\n<li>Spécifications techniques formatées</li>\\n<li>Livraison sous 48h</li>\\n</ul>\\n\\n<h4>🛠️ Technologies et Outils:</h4>\\n<p>Yoast SEO, Rank Math, analyse mots-clés, optimisation images WebP, Schema.org markup</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Produit optimisé pour Google</li>\\n<li>Descriptions qui convertissent</li>\\n<li>Rich snippets dans résultats recherche</li>\\n<li>Images multiples = confiance client</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Nécessite informations détaillées de votre part</li>\\n<li>Résultats SEO = temps (1-3 mois)</li>\\n<li>Photos professionnelles = votre responsabilité</li>\\n</ul>\", \"it\": \"<h3>🎯 Aggiunta Prodotto Complesso con SEO - Visibilità Massima</h3>\\n<p>Servizio premium di aggiunta prodotto con ottimizzazione SEO completa. Descrizioni che vendono, immagini professionali e visibilità sui motori di ricerca.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Tutto da Prodotto Semplice</li>\\n<li>Descrizione SEO copywriting professionale</li>\\n<li>Fino a 10 immagini ottimizzate</li>\\n<li>Meta title e meta description</li>\\n<li>URL slug ottimizzato</li>\\n<li>Varianti multiple con immagini</li>\\n<li>Configurazione prodotti correlati/cross-sell</li>\\n<li>Schema markup per rich snippets</li>\\n<li>Specifiche tecniche formattate</li>\\n<li>Consegna entro 48h</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie e Strumenti:</h4>\\n<p>Yoast SEO, Rank Math, analisi parole chiave, ottimizzazione immagini WebP, Schema.org markup</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Prodotto ottimizzato per Google</li>\\n<li>Descrizioni che convertono</li>\\n<li>Rich snippets nei risultati ricerca</li>\\n<li>Immagini multiple = fiducia cliente</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Richiede informazioni dettagliate da te</li>\\n<li>Risultati SEO = tempo (1-3 mesi)</li>\\n<li>Foto professionali = tua responsabilità</li>\\n</ul>\", \"ro\": \"<h3>🎯 Adăugare Produs Complex cu SEO - Vizibilitate Maximă</h3>\\n<p>Serviciu premium de adăugare produs cu optimizare SEO completă. Descrieri care vând, imagini profesionale și vizibilitate în motoarele de căutare.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Tot din Produs Simplu</li>\\n<li>Descriere SEO copywriting profesional</li>\\n<li>Până la 10 imagini optimizate</li>\\n<li>Meta title și meta description</li>\\n<li>URL slug optimizat</li>\\n<li>Variante multiple cu imagini</li>\\n<li>Produse înrudite/cross-sell setup</li>\\n<li>Schema markup pentru rich snippets</li>\\n<li>Specificații tehnice formatate</li>\\n<li>Livrare în 48h</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii & Unelte:</h4>\\n<p>Yoast SEO, Rank Math, analiza cuvinte cheie, optimizare imagini WebP, Schema.org markup</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Produs optimizat pentru Google</li>\\n<li>Descrieri care convertesc</li>\\n<li>Rich snippets în rezultate căutare</li>\\n<li>Imagini multiple = încredere client</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Necesită informații detaliate de la tine</li>\\n<li>Rezultatele SEO = timp (1-3 luni)</li>\\n<li>Fotografii profesionale = responsabilitatea ta</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Image resizing and editing</li><li>Watermark and compression</li><li>Unique SEO meta descriptions</li><li>Variable attribute configuration</li><li>Recommended products setup</li></ul>\", \"ro\": \"<ul><li>Redimensionare și editare imagini</li><li>Watermark și compresie</li><li>Meta-descrieri unice SEO</li><li>Configurare atribute variabile</li><li>Setare produse recomandate</li></ul>\"}','CONT-CPLX-007',25.00,'RON',NULL,999,'[\"images/services/produs-complex.jpg\"]',NULL,NULL,0,1,'2025-12-21 08:59:13','2025-12-21 10:46:22'),(25,NULL,'{\"de\": \"Produktimport-Automatisierung (Feed)\", \"en\": \"Product Import Automation (Feed)\", \"es\": \"Automatización Importación Productos (Feed)\", \"fr\": \"Automatisation Import Produits (Feed)\", \"it\": \"Automazione Importazione Prodotti (Feed)\", \"ro\": \"Automatizare Import Produse (Feed)\"}','automatizare-import-produse-feed','{\"de\": \"<h3>🔄 Produktimport-Automatisierung - Synchronisierter Feed</h3>\\n<p>Automatisiertes System für Import und Synchronisierung von Produkten aus externen Feeds. Sparen Sie Stunden manueller Arbeit.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Automatischer Import aus CSV/XML/JSON</li>\\n<li>Benutzerdefinierte Feldzuordnung</li>\\n<li>Echtzeit-Bestandssynchronisierung</li>\\n<li>Automatische Preisaktualisierungen</li>\\n<li>Automatische Kategorieerstellung</li>\\n<li>Import-Planung (täglich/stündlich)</li>\\n<li>Fehlerbenachrichtigungen und Berichte</li>\\n<li>Dokumentation und Schulung</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>WP All Import, Custom PHP Scripts, Cron Jobs, API-Integrationen, FTP/SFTP Sync</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Keine manuelle Arbeit für Updates</li>\\n<li>Bestand immer aktuell</li>\\n<li>Skalieren ohne Mehraufwand</li>\\n<li>Preise mit Lieferant synchronisiert</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Erfordert gültigen Feed vom Lieferanten</li>\\n<li>Bilder müssen zugängliche URLs sein</li>\\n<li>Erste Tests dauern 2-3 Tage</li>\\n</ul>\", \"en\": \"<h3>🔄 Product Import Automation - Synchronized Feed</h3>\\n<p>Automated system for importing and syncing products from external feeds. Save hours of manual work.</p>\\n\\n<h4>📋 What is included:</h4>\\n<ul>\\n<li>Automatic import setup from CSV/XML/JSON</li>\\n<li>Custom field mapping</li>\\n<li>Real-time stock sync</li>\\n<li>Automatic price updates</li>\\n<li>Automatic category creation</li>\\n<li>Import scheduling (daily/hourly)</li>\\n<li>Error notifications and reports</li>\\n<li>Documentation and training</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>WP All Import, Custom PHP scripts, Cron jobs, API integrations, FTP/SFTP sync</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Zero manual work for updates</li>\\n<li>Stock always up to date</li>\\n<li>Scale without extra effort</li>\\n<li>Prices synced with supplier</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Requires valid feed from supplier</li>\\n<li>Images must be accessible URLs</li>\\n<li>Initial testing takes 2-3 days</li>\\n</ul>\", \"es\": \"<h3>🔄 Automatización Importación Productos - Feed Sincronizado</h3>\\n<p>Sistema automatizado para importar y sincronizar productos desde feeds externos. Ahorra horas de trabajo manual.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Configuración importación automática CSV/XML/JSON</li>\\n<li>Mapeo campos personalizado</li>\\n<li>Sincronización stocks en tiempo real</li>\\n<li>Actualización automática precios</li>\\n<li>Creación categorías automáticas</li>\\n<li>Programación importación (diaria/horaria)</li>\\n<li>Notificaciones errores e informes</li>\\n<li>Documentación y formación</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>WP All Import, Scripts PHP personalizados, Cron jobs, integraciones API, sincronización FTP/SFTP</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Cero trabajo manual para actualizaciones</li>\\n<li>Stock siempre actualizado</li>\\n<li>Escalar sin esfuerzo extra</li>\\n<li>Precios sincronizados con proveedor</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Requiere feed válido del proveedor</li>\\n<li>Imágenes deben ser URLs accesibles</li>\\n<li>Pruebas iniciales duran 2-3 días</li>\\n</ul>\", \"fr\": \"<h3>🔄 Automatisation Import Produits - Feed Synchronisé</h3>\\n<p>Système automatisé pour importer et synchroniser produits depuis feeds externes. Économisez des heures de travail manuel.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Configuration import automatique CSV/XML/JSON</li>\\n<li>Mappage champs personnalisé</li>\\n<li>Synchronisation stocks temps réel</li>\\n<li>Mise à jour automatique prix</li>\\n<li>Création catégories automatiques</li>\\n<li>Planification import (quotidien/horaire)</li>\\n<li>Notifications erreurs et rapports</li>\\n<li>Documentation et formation</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisées:</h4>\\n<p>WP All Import, Scripts PHP personnalisés, Cron jobs, intégrations API, sync FTP/SFTP</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Zéro travail manuel pour mises à jour</li>\\n<li>Stock toujours à jour</li>\\n<li>Scalez sans effort supplémentaire</li>\\n<li>Prix synchronisés avec fournisseur</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Nécessite feed valide du fournisseur</li>\\n<li>Images doivent être URLs accessibles</li>\\n<li>Tests initiaux prennent 2-3 jours</li>\\n</ul>\", \"it\": \"<h3>🔄 Automazione Importazione Prodotti - Feed Sincronizzato</h3>\\n<p>Sistema automatizzato per importare e sincronizzare prodotti da feed esterni. Risparmia ore di lavoro manuale.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Configurazione import automatico CSV/XML/JSON</li>\\n<li>Mappatura campi personalizzata</li>\\n<li>Sincronizzazione scorte tempo reale</li>\\n<li>Aggiornamento automatico prezzi</li>\\n<li>Creazione categorie automatiche</li>\\n<li>Programmazione import (giornaliero/orario)</li>\\n<li>Notifiche errori e report</li>\\n<li>Documentazione e formazione</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>WP All Import, Script PHP personalizzati, Cron jobs, integrazioni API, sync FTP/SFTP</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Zero lavoro manuale per aggiornamenti</li>\\n<li>Stock sempre aggiornato</li>\\n<li>Scala senza sforzo extra</li>\\n<li>Prezzi sincronizzati con fornitore</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Richiede feed valido dal fornitore</li>\\n<li>Immagini devono essere URL accessibili</li>\\n<li>Test iniziali richiedono 2-3 giorni</li>\\n</ul>\", \"ro\": \"<h3>🔄 Automatizare Import Produse - Feed Sincronizat</h3>\\n<p>Sistem automatizat pentru importul și sincronizarea produselor din feed-uri externe. Economisești ore de muncă manuală.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Configurare import automat din CSV/XML/JSON</li>\\n<li>Mapare câmpuri personalizată</li>\\n<li>Sincronizare stocuri în timp real</li>\\n<li>Actualizare automată prețuri</li>\\n<li>Creare categorii automate</li>\\n<li>Programare import (zilnic/orar)</li>\\n<li>Notificări erori și rapoarte</li>\\n<li>Documentație și training</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>WP All Import, Custom PHP scripts, Cron jobs, API integrations, FTP/SFTP sync</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Zero muncă manuală pentru actualizări</li>\\n<li>Stocuri mereu la zi</li>\\n<li>Scalare fără efort suplimentar</li>\\n<li>Prețuri sincronizate cu furnizorul</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Necesită feed valid de la furnizor</li>\\n<li>Imagini trebuie să fie URL-uri accesibile</li>\\n<li>Testare inițială durează 2-3 zile</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Custom import script</li><li>Automatic stock sync</li><li>Price synchronization</li><li>Initial setup for one supplier</li></ul>\", \"ro\": \"<ul><li>Script personalizat import</li><li>Sincronizare automată stocuri</li><li>Sincronizare prețuri</li><li>Configurare inițială pentru un furnizor</li></ul>\"}','CONT-IMP-008',1499.00,'RON',NULL,999,'[\"images/services/import-produse.jpg\"]',NULL,NULL,0,1,'2025-12-21 08:59:13','2025-12-21 10:49:06'),(26,NULL,'{\"de\": \"Professionelle Website-Desinfektion\", \"en\": \"Professional Website Disinfection\", \"es\": \"Desinfección Profesional de Sitio Web\", \"fr\": \"Désinfection Professionnelle de Site\", \"it\": \"Disinfezione Professionale Sito Web\", \"ro\": \"Devirusare Profesională Site\"}','devirusare-profesionala-site','{\"de\": \"<h3>🛡️ Professionelle Website-Desinfektion - Sicherheit wiederhergestellt</h3>\\n<p>Kompletter Malware-Bereinigung und Sicherheitswiederherstellungsdienst. Wir beseitigen jede Infektion und schützen Ihre Site vor künftigen Angriffen.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Kompletter Datei- und Datenbank-Scan</li>\\n<li>Malware-Identifizierung und -Entfernung</li>\\n<li>Backdoor- und Shell-Bereinigung</li>\\n<li>Blacklist-Entfernung (Google, etc.)</li>\\n<li>CMS- und Plugin-Updates</li>\\n<li>Server-Sicherheitshärtung</li>\\n<li>Firewall und Brute-Force-Schutz</li>\\n<li>Detaillierter Schwachstellenbericht</li>\\n<li>30 Tage Überwachung nach Bereinigung</li>\\n</ul>\\n\\n<h4>🛠️ Tools und Technologien:</h4>\\n<p>Sucuri, Wordfence, ImunifyAV, ClamAV, manuelle Codeanalyse, Google Search Console</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Saubere und funktionale Site schnell</li>\\n<li>Komplette Entfernung - nicht nur Symptome</li>\\n<li>Schutz vor Reinfektion</li>\\n<li>Google-Reputationswiederherstellung</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Backup vor Intervention empfohlen</li>\\n<li>Kann Hosting-Upgrade erfordern</li>\\n<li>Lösungszeit 24-72 Stunden</li>\\n</ul>\", \"en\": \"<h3>🛡️ Professional Website Disinfection - Security Restored</h3>\\n<p>Complete malware cleanup and security restoration service. We eliminate any infection and protect your site against future attacks.</p>\\n\\n<h4>📋 What is included:</h4>\\n<ul>\\n<li>Complete file and database scan</li>\\n<li>Malware identification and removal</li>\\n<li>Backdoor and shell cleanup</li>\\n<li>Blacklist removal (Google, etc.)</li>\\n<li>CMS and plugin updates</li>\\n<li>Server security hardening</li>\\n<li>Firewall and brute force protection</li>\\n<li>Detailed vulnerability report</li>\\n<li>30 days post-cleanup monitoring</li>\\n</ul>\\n\\n<h4>🛠️ Tools and technologies:</h4>\\n<p>Sucuri, Wordfence, ImunifyAV, ClamAV, manual code analysis, Google Search Console</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Clean and functional site quickly</li>\\n<li>Complete removal - not just symptoms</li>\\n<li>Protection against reinfection</li>\\n<li>Google reputation recovery</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Backup recommended before intervention</li>\\n<li>May require hosting upgrade</li>\\n<li>Resolution time 24-72 hours</li>\\n</ul>\", \"es\": \"<h3>🛡️ Desinfección Profesional de Sitio Web - Seguridad Restaurada</h3>\\n<p>Servicio completo de limpieza malware y restauración de seguridad. Eliminamos cualquier infección y protegemos tu sitio contra ataques futuros.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Escaneo completo archivos y base de datos</li>\\n<li>Identificación y eliminación malware</li>\\n<li>Limpieza backdoors y shells</li>\\n<li>Eliminación de listas negras (Google, etc.)</li>\\n<li>Actualización CMS y plugins</li>\\n<li>Endurecimiento seguridad servidor</li>\\n<li>Firewall y protección fuerza bruta</li>\\n<li>Informe detallado vulnerabilidades</li>\\n<li>30 días monitorización post-limpieza</li>\\n</ul>\\n\\n<h4>🛠️ Herramientas y tecnologías:</h4>\\n<p>Sucuri, Wordfence, ImunifyAV, ClamAV, análisis manual código, Google Search Console</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Sitio limpio y funcional rápidamente</li>\\n<li>Eliminación completa - no solo síntomas</li>\\n<li>Protección contra reinfección</li>\\n<li>Recuperación reputación Google</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Backup recomendado antes de intervención</li>\\n<li>Puede requerir upgrade hosting</li>\\n<li>Tiempo resolución 24-72 horas</li>\\n</ul>\", \"fr\": \"<h3>🛡️ Désinfection Professionnelle de Site - Sécurité Restaurée</h3>\\n<p>Service complet de nettoyage malware et restauration sécurité. Nous éliminons toute infection et protégeons votre site contre les attaques futures.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Scan complet fichiers et base de données</li>\\n<li>Identification et suppression malware</li>\\n<li>Nettoyage backdoors et shells</li>\\n<li>Suppression des listes noires (Google, etc.)</li>\\n<li>Mise à jour CMS et plugins</li>\\n<li>Renforcement sécurité serveur</li>\\n<li>Firewall et protection force brute</li>\\n<li>Rapport détaillé vulnérabilités</li>\\n<li>30 jours surveillance post-nettoyage</li>\\n</ul>\\n\\n<h4>🛠️ Outils et technologies:</h4>\\n<p>Sucuri, Wordfence, ImunifyAV, ClamAV, analyse manuelle code, Google Search Console</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Site propre et fonctionnel rapidement</li>\\n<li>Suppression complète - pas que symptômes</li>\\n<li>Protection contre réinfection</li>\\n<li>Récupération réputation Google</li>\\n</ul>\\n\\n<h4>⚠️ À noter:</h4>\\n<ul>\\n<li>Backup recommandé avant intervention</li>\\n<li>Peut nécessiter upgrade hébergement</li>\\n<li>Temps résolution 24-72 heures</li>\\n</ul>\", \"it\": \"<h3>🛡️ Disinfezione Professionale Sito Web - Sicurezza Ripristinata</h3>\\n<p>Servizio completo di pulizia malware e ripristino sicurezza. Eliminiamo qualsiasi infezione e proteggiamo il tuo sito contro attacchi futuri.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Scansione completa file e database</li>\\n<li>Identificazione e rimozione malware</li>\\n<li>Pulizia backdoor e shell</li>\\n<li>Rimozione dalle blacklist (Google, etc.)</li>\\n<li>Aggiornamento CMS e plugin</li>\\n<li>Hardening sicurezza server</li>\\n<li>Firewall e protezione brute force</li>\\n<li>Report dettagliato vulnerabilità</li>\\n<li>30 giorni monitoraggio post-pulizia</li>\\n</ul>\\n\\n<h4>🛠️ Strumenti e tecnologie:</h4>\\n<p>Sucuri, Wordfence, ImunifyAV, ClamAV, analisi manuale codice, Google Search Console</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Sito pulito e funzionale rapidamente</li>\\n<li>Rimozione completa - non solo sintomi</li>\\n<li>Protezione contro reinfezione</li>\\n<li>Recupero reputazione Google</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Backup consigliato prima di intervento</li>\\n<li>Potrebbe richiedere upgrade hosting</li>\\n<li>Tempo risoluzione 24-72 ore</li>\\n</ul>\", \"ro\": \"<h3>🛡️ Devirusare Profesională Site - Securitate Restaurată</h3>\\n<p>Serviciu complet de curățare malware și restaurare securitate. Eliminăm orice infecție și protejăm site-ul împotriva atacurilor viitoare.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Scanare completă fișiere și bază de date</li>\\n<li>Identificare și eliminare malware</li>\\n<li>Curățare backdoors și shell-uri</li>\\n<li>Eliminare din blacklist-uri (Google, etc.)</li>\\n<li>Update CMS și plugin-uri</li>\\n<li>Hardening securitate server</li>\\n<li>Firewall și protecție brute force</li>\\n<li>Raport detaliat vulnerabilități</li>\\n<li>30 zile monitorizare post-curățare</li>\\n</ul>\\n\\n<h4>🛠️ Unelte și tehnologii:</h4>\\n<p>Sucuri, Wordfence, ImunifyAV, ClamAV, analiza manuală cod, Google Search Console</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Site curat și funcțional rapid</li>\\n<li>Eliminare completă - nu doar simptome</li>\\n<li>Protecție împotriva reinfectării</li>\\n<li>Recuperare reputație Google</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Backup recomandat înainte de intervenție</li>\\n<li>Poate necesita upgrade hosting</li>\\n<li>Timp rezolvare 24-72 ore</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Complete malicious code cleanup</li><li>Security breach patching</li><li>Google Blacklist removal assistance</li><li>Detailed report of issues found</li></ul>\", \"ro\": \"<ul><li>Curățare completă cod malițios</li><li>Securizare breșe de securitate</li><li>Asistență scoatere din Blacklist Google</li><li>Raport detaliat al problemelor găsite</li></ul>\"}','INTER-DEV-001',749.00,'RON',NULL,999,'[\"images/services/devirusare.jpg\"]',NULL,NULL,1,1,'2025-12-21 08:59:36','2025-12-21 10:50:15'),(27,NULL,'{\"de\": \"Geschwindigkeitsoptimierung (Speed Boost)\", \"en\": \"Speed Optimization (Speed Boost)\", \"es\": \"Optimización Velocidad (Speed Boost)\", \"fr\": \"Optimisation Vitesse (Speed Boost)\", \"it\": \"Ottimizzazione Velocità (Speed Boost)\", \"ro\": \"Optimizare Viteză (Speed Boost)\"}','optimizare-viteza-speed-boost','{\"de\": \"<h3>🚀 Geschwindigkeitsoptimierung - Speed Boost</h3>\\n<p>Kompletter Performance-Optimierungsdienst. Wir lassen Ihre Site fliegen und erreichen exzellente Google PageSpeed-Werte.</p>\\n\\n<h4>📋 Was enthalten ist:</h4>\\n<ul>\\n<li>Komplettes Performance-Audit</li>\\n<li>Bildoptimierung (WebP, Lazy Load)</li>\\n<li>CSS/JS-Minifizierung</li>\\n<li>Browser- und Server-Cache-Konfiguration</li>\\n<li>CDN-Setup und Konfiguration</li>\\n<li>Datenbankoptimierung</li>\\n<li>Entfernung schwerer Plugins</li>\\n<li>GZIP/Brotli-Komprimierung</li>\\n<li>Web-Font-Optimierung</li>\\n<li>Vorher/Nachher-Bericht mit Werten</li>\\n</ul>\\n\\n<h4>🛠️ Verwendete Technologien:</h4>\\n<p>Cloudflare CDN, WP Rocket/LiteSpeed Cache, ShortPixel, Redis/Memcached, HTTP/2</p>\\n\\n<h4>✅ Vorteile:</h4>\\n<ul>\\n<li>Ladezeit unter 3 Sekunden</li>\\n<li>PageSpeed-Score 90+</li>\\n<li>Verbessertes SEO (Core Web Vitals)</li>\\n<li>Höhere Conversions = mehr Verkäufe</li>\\n</ul>\\n\\n<h4>⚠️ Zu beachten:</h4>\\n<ul>\\n<li>Einige Plugins können inkompatibel sein</li>\\n<li>Ergebnisse hängen auch vom Hosting ab</li>\\n<li>Implementierungszeit 3-5 Tage</li>\\n</ul>\", \"en\": \"<h3>🚀 Speed Optimization - Speed Boost</h3>\\n<p>Complete performance optimization service. We make your site fly and achieve excellent Google PageSpeed scores.</p>\\n\\n<h4>📋 What is included:</h4>\\n<ul>\\n<li>Complete performance audit</li>\\n<li>Image optimization (WebP, lazy load)</li>\\n<li>CSS/JS minification</li>\\n<li>Browser and server cache configuration</li>\\n<li>CDN setup and configuration</li>\\n<li>Database optimization</li>\\n<li>Heavy plugin removal</li>\\n<li>GZIP/Brotli compression</li>\\n<li>Web font optimization</li>\\n<li>Before/after report with scores</li>\\n</ul>\\n\\n<h4>🛠️ Technologies used:</h4>\\n<p>Cloudflare CDN, WP Rocket/LiteSpeed Cache, ShortPixel, Redis/Memcached, HTTP/2</p>\\n\\n<h4>✅ Advantages:</h4>\\n<ul>\\n<li>Load time under 3 seconds</li>\\n<li>PageSpeed score 90+</li>\\n<li>Improved SEO (Core Web Vitals)</li>\\n<li>Higher conversions = more sales</li>\\n</ul>\\n\\n<h4>⚠️ Keep in mind:</h4>\\n<ul>\\n<li>Some plugins may be incompatible</li>\\n<li>Results also depend on hosting</li>\\n<li>Implementation time 3-5 days</li>\\n</ul>\", \"es\": \"<h3>🚀 Optimización Velocidad - Speed Boost</h3>\\n<p>Servicio completo de optimización de rendimiento. Hacemos que tu sitio vuele y consiga excelentes puntuaciones en Google PageSpeed.</p>\\n\\n<h4>📋 Qué incluye:</h4>\\n<ul>\\n<li>Auditoría completa de rendimiento</li>\\n<li>Optimización imágenes (WebP, lazy load)</li>\\n<li>Minificación CSS/JS</li>\\n<li>Configuración caché navegador y servidor</li>\\n<li>Setup y configuración CDN</li>\\n<li>Optimización base de datos</li>\\n<li>Eliminación plugins pesados</li>\\n<li>Compresión GZIP/Brotli</li>\\n<li>Optimización fuentes web</li>\\n<li>Informe antes/después con puntuaciones</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologías utilizadas:</h4>\\n<p>Cloudflare CDN, WP Rocket/LiteSpeed Cache, ShortPixel, Redis/Memcached, HTTP/2</p>\\n\\n<h4>✅ Ventajas:</h4>\\n<ul>\\n<li>Tiempo carga bajo 3 segundos</li>\\n<li>Puntuación PageSpeed 90+</li>\\n<li>SEO mejorado (Core Web Vitals)</li>\\n<li>Más conversiones = más ventas</li>\\n</ul>\\n\\n<h4>⚠️ A tener en cuenta:</h4>\\n<ul>\\n<li>Algunos plugins pueden ser incompatibles</li>\\n<li>Resultados también dependen del hosting</li>\\n<li>Tiempo implementación 3-5 días</li>\\n</ul>\", \"fr\": \"<h3>🚀 Optimisation Vitesse - Speed Boost</h3>\\n<p>Service complet d optimisation performance. Nous faisons voler votre site et obtenons d excellents scores Google PageSpeed.</p>\\n\\n<h4>📋 Ce qui est inclus:</h4>\\n<ul>\\n<li>Audit complet performance</li>\\n<li>Optimisation images (WebP, lazy load)</li>\\n<li>Minification CSS/JS</li>\\n<li>Configuration cache navigateur et serveur</li>\\n<li>Setup et configuration CDN</li>\\n<li>Optimisation base de donnees</li>\\n<li>Suppression plugins lourds</li>\\n<li>Compression GZIP/Brotli</li>\\n<li>Optimisation polices web</li>\\n<li>Rapport avant/apres avec scores</li>\\n</ul>\\n\\n<h4>🛠️ Technologies utilisees:</h4>\\n<p>Cloudflare CDN, WP Rocket/LiteSpeed Cache, ShortPixel, Redis/Memcached, HTTP/2</p>\\n\\n<h4>✅ Avantages:</h4>\\n<ul>\\n<li>Temps chargement sous 3 secondes</li>\\n<li>Score PageSpeed 90+</li>\\n<li>SEO ameliore (Core Web Vitals)</li>\\n<li>Plus de conversions = plus de ventes</li>\\n</ul>\\n\\n<h4>⚠️ A noter:</h4>\\n<ul>\\n<li>Certains plugins peuvent etre incompatibles</li>\\n<li>Resultats dependent aussi de l hebergement</li>\\n<li>Temps implementation 3-5 jours</li>\\n</ul>\", \"it\": \"<h3>🚀 Ottimizzazione Velocita - Speed Boost</h3>\\n<p>Servizio completo di ottimizzazione prestazioni. Facciamo volare il tuo sito e otteniamo eccellenti punteggi Google PageSpeed.</p>\\n\\n<h4>📋 Cosa include:</h4>\\n<ul>\\n<li>Audit completo prestazioni</li>\\n<li>Ottimizzazione immagini (WebP, lazy load)</li>\\n<li>Minificazione CSS/JS</li>\\n<li>Configurazione cache browser e server</li>\\n<li>Setup e configurazione CDN</li>\\n<li>Ottimizzazione database</li>\\n<li>Rimozione plugin pesanti</li>\\n<li>Compressione GZIP/Brotli</li>\\n<li>Ottimizzazione font web</li>\\n<li>Report prima/dopo con punteggi</li>\\n</ul>\\n\\n<h4>🛠️ Tecnologie utilizzate:</h4>\\n<p>Cloudflare CDN, WP Rocket/LiteSpeed Cache, ShortPixel, Redis/Memcached, HTTP/2</p>\\n\\n<h4>✅ Vantaggi:</h4>\\n<ul>\\n<li>Tempo caricamento sotto 3 secondi</li>\\n<li>Punteggio PageSpeed 90+</li>\\n<li>SEO migliorato (Core Web Vitals)</li>\\n<li>Piu conversioni = piu vendite</li>\\n</ul>\\n\\n<h4>⚠️ Da considerare:</h4>\\n<ul>\\n<li>Alcuni plugin potrebbero essere incompatibili</li>\\n<li>Risultati dipendono anche dall hosting</li>\\n<li>Tempo implementazione 3-5 giorni</li>\\n</ul>\", \"ro\": \"<h3>🚀 Optimizare Viteză - Speed Boost</h3>\\n<p>Serviciu complet de optimizare performanță. Îți facem site-ul să zboare și să obțină scoruri excelente în Google PageSpeed.</p>\\n\\n<h4>📋 Ce include:</h4>\\n<ul>\\n<li>Audit complet performanță</li>\\n<li>Optimizare imagini (WebP, lazy load)</li>\\n<li>Minificare CSS/JS</li>\\n<li>Configurare cache browser și server</li>\\n<li>CDN setup și configurare</li>\\n<li>Optimizare bază de date</li>\\n<li>Eliminare plugin-uri grele</li>\\n<li>Compresie GZIP/Brotli</li>\\n<li>Optimizare fonturi web</li>\\n<li>Raport before/after cu scoruri</li>\\n</ul>\\n\\n<h4>🛠️ Tehnologii utilizate:</h4>\\n<p>Cloudflare CDN, WP Rocket/LiteSpeed Cache, ShortPixel, Redis/Memcached, HTTP/2</p>\\n\\n<h4>✅ Avantaje:</h4>\\n<ul>\\n<li>Timp încărcare sub 3 secunde</li>\\n<li>Scor PageSpeed 90+</li>\\n<li>SEO îmbunătățit (Core Web Vitals)</li>\\n<li>Conversii mai mari = vânzări mai mari</li>\\n</ul>\\n\\n<h4>⚠️ De reținut:</h4>\\n<ul>\\n<li>Unele plugin-uri pot fi incompatibile</li>\\n<li>Rezultatele depind și de hosting</li>\\n<li>Timp implementare 3-5 zile</li>\\n</ul>\"}',NULL,NULL,NULL,'{\"en\": \"<ul><li>Complete performance audit</li><li>Advanced cache configuration</li><li>CSS/JS code minification</li><li>Next-gen image optimization</li><li>Database optimization</li></ul>\", \"ro\": \"<ul><li>Audit performanță complet</li><li>Configurare cache avansat</li><li>Minificare cod CSS/JS</li><li>Optimizare imagini next-gen</li><li>Optimizare bază de date</li></ul>\"}','INTER-SPD-002',999.00,'RON',NULL,999,'[\"images/services/optimizare-viteza.jpg\"]',NULL,NULL,1,1,'2025-12-21 08:59:36','2025-12-21 10:51:20');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pwa_features`
--

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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `redirects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL DEFAULT '301',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `hits` bigint unsigned NOT NULL DEFAULT '0',
  `last_hit_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `redirects_from_url_unique` (`from_url`),
  KEY `redirects_is_active_index` (`is_active`),
  KEY `redirects_from_url_index` (`from_url`)
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
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES (1,5),(2,5),(3,5),(4,5);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (1,'Super Admin','web','2025-12-11 05:02:24','2025-12-11 05:02:24'),(2,'Admin','web','2025-12-11 05:02:24','2025-12-11 05:02:24'),(3,'Editor','web','2025-12-11 05:02:24','2025-12-11 05:02:24'),(4,'Customer','web','2025-12-21 09:12:57','2025-12-21 09:12:57'),(5,'Client','web','2025-12-21 11:38:28','2025-12-21 11:38:28');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheduled_contents`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scheduled_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contentable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contentable_id` bigint unsigned NOT NULL,
  `action` enum('publish','unpublish','delete') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_for` timestamp NOT NULL,
  `status` enum('pending','executed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_indexes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `indexable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `indexable_id` bigint unsigned NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_queries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `results_count` int NOT NULL DEFAULT '0',
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_redirects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_synonyms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sent_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` enum('email','sms','push','database') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','sent','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seo_meta_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seo_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_txt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sitemap_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sitemap_frequencies` json DEFAULT NULL,
  `google_analytics` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `google_tag_manager` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `facebook_pixel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `custom_head_scripts` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `custom_body_scripts` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
INSERT INTO `seo_settings` (`id`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `robots_txt`, `sitemap_enabled`, `sitemap_frequencies`, `google_analytics`, `google_tag_manager`, `facebook_pixel`, `custom_head_scripts`, `custom_body_scripts`, `schema_markup`, `created_at`, `updated_at`) VALUES (1,'Web Agency - Professional Web Development','Professional web development and digital solutions for modern businesses',NULL,NULL,'User-agent: *\nAllow: /\nSitemap: http://cms.carphatian.ro/sitemap.xml',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-07 04:49:18','2025-12-07 04:49:18');
/*!40000 ALTER TABLE `seo_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('04YvnzYPBHP32w1FlJnAbkWx0OJgAwkXpjYnjTgW',NULL,'2604:a880:400:d1:0:3:7227:8001','Mozilla/5.0 zgrab/0.x','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InNIakwyVG93d1FpblhucUV2Q2FXRE0xNnphMnY1VHdRU01YYWVpRUIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL1syMDAxOjQxZDA6NzAxOjExMDA6OjkxMzJdIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766498159),('0dCfFqL8k5pmO9SvG8TS667GMi727DkNlfqjPGjc',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InN0ZzZGdkxncnIwRVRzWUdpWFZ3OG80b0NRMDMxV09aS045RTYxOGoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490479),('0IUYqtk2fFAUpVczG9z5ST8wH2hp2OvFb0T6KRT7',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkNXZXhnajFDZ3NudnRwSVRFV1BiU3ZnemlkNWw4MFZKczJEcVlVQU8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490497),('0kGN46tQWGc14s3sfjL16bcW861vdKOEniCGbHEG',NULL,'66.249.93.232','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlBabjVRY3BUbTNldmlpSnlIcjdweWp2VG4yVnluSFJLT001QTV5NmMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488847),('0SDv1OrmirzgJfrxQ26hSNHmhBUULFwys7tsh1vt',NULL,'74.241.128.125','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik5hQTFhdnNiMEJ3cDdubmtRcXFqSnRuRnkxR3pCOGpXdVdoVGV1c2QiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766473307),('0wELwBQm6Jr2pCprqWYjqHjbWuOeUUFgfocvQN66',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlVJN2xNRlFwaDlFZ0FtaEt3dnR0UFFsdXBhemcwQlVVdlZ5Zm9TSVkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490493),('0xzw1FOGPGkpcJvBOBZ5GtsN1jZehKOJjpp7pMde',NULL,'147.182.249.31','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkpMMlJKMlBPMlo0MjloMG1HMmRDQ3NuTHA4M3RZeVZpNTlmTXdNNmwiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC90ZW9yZW1hNTA1P3Q9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490942),('11gXHccIGjgOIIDbd8giRRnTxm1ECZhfEEofjlk7',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkFrMmNpR2R0bnFRUzljcHlOSXk1VUNCV3d3bnlqZVFPaWdxMk90cU0iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjc4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2VhcmNoP3E9JTNDc2NyaXB0JTNFYWxlcnQlMjglMjdYU1MlMjclMjklM0MlMkZzY3JpcHQlM0UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490471),('1GUhH4HruyA3KUWhFpisR9cu0S5fweBaXGPSgPpZ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlVJQlc1VEY3Mzd5bjduc3FramNBRFlKNlExMHBUMnBSNmZtSzRadVgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490497),('1gZgFPHVlxvMTOW8svDKHi4alsdUU2fJ8DK4MCzy',NULL,'51.254.204.161','Mozilla/5.0','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjdlbXV4ZnU4Y2ozS0puWE5vVzl2YjRMaVVDell2QTl6SjIyWEtwZGEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766475746),('1uoEZSkMREKEEdj8Uy0SZpF9YVfQILCFXECIxbdZ',NULL,'127.0.0.1','Mozilla/5.0','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im14TTJQTFh1cjE4VHBpdEZHN0c3ZEZNbmt3bUluRk9LZFViSEY0d1UiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492233),('1ZUNPiHhEi09kRcmeO9evZ6IC93GlersquHI4kqT',NULL,'92.63.197.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImpkNFFFYW9LNXUxalBVMVg3OVFCYWVESFJNTHptOVFuQjE4YmNIdXEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766470625),('24NRrvQpGYcag8H7DOZMuzyviaTKI39dk0y7oZOZ',NULL,'173.252.107.18','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlFmZUpSNXJab014N3NTdjFKalhwMVBsbXBOYnFwVjN2Mmg2bHBNenIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766469071),('28Wisv3qLiaTU3IKgkgve4ePKu2HqrdGx2ESZswp',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImE4M0xMS2xxUUlvY2dNSVhyb0Z3cTZwUFlkeGxUSkRTSWtPS2FjelAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490500),('2JO8VNQhgUhjwVurrzJS2fM2HOhatSC5Zhl7Kt0X',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InM3Qm81cnM1NHJKOWVXRU9uNGlvNGlybmNZVWd6Yko1UUVhR3ZDOFoiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492308),('2RafaZkKxdwgUkC2iXwOxUyfNxIU8nt3cASXARGa',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InhmUFVXUmVIRUFXS01vTVMxWk1XcllkRkdKa3pNVUhXUWhrV3dGRG0iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488937),('3552bEwO7MLqn84UJtLxHha4SQrSyYOhXNl3YuZG',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IklwVERnMWpKdlcwdjBGMzNITTFialllbEZRd3FTOWxSV0cwMktSYkIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488938),('35RpENSTQhLYWtooAcBDTuehRPg6gb9c9bFf915K',NULL,'35.195.180.151','python-requests/2.32.5','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ikljc0JTOXAwekM1MHBOZVVWQkswUlBHYWZTTk9QMEJ4TXRBcVd3Q0MiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488676),('3O9xRu1e2YHwgl6QoiZhlm6nBxmp9WGbJ3XMcu7P',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik5qZXdkajgwRGZOb3d1U1YyOVB4bEw2UXlBQUhRT29CNm1zOTF1Z1EiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490478),('4cJvEkTxQZh6gOZ4mFJwJ2Aoesiow3Nu24QQ1qSA',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkhzWjBTSHVaSUc1M0F0ME1JUUtCVjBBU0Y5MFNqT2pSWHhkbGJ4Zk8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766485032),('4o5duNQ1SuqSqPU6j3VIvS48uKCpsUGw26TXUYvL',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImdSZG0zMHJqVjRHUUIwZVZpWDZYWDZadExQM2M2VFhXS2ZoTWNFQ0MiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490778),('4TiSzs3xLAj2xJQO6ni5wrCPbYS9mmJOIwgbmaat',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjEyQTVXWlo3dGx1c1pEMVlyeGQ2TFVpVXRZN2REZjZ6SmY0VUlKNzUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488943),('54MeYjewswqKS7VIhKkjCnH7yM1puLBo2ENJYJoJ',NULL,'173.252.95.9','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjVSdDhoUW5PVDBaRXl2b2p6RTYwdzF1cHhwUk5NS0wxU1o5cDFyS0MiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766499524),('5alOK06tNust4slOoz6MBlbHgzLkYkUe0kSioAbu',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjNRYjFRRHB0b2kyenNGdHBkQXR4eVJFa080b1FYZURWeEFETEQ3VUIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488939),('5IeTOcn6PSsVNhsfZuTDEHt4ZUcvT6XVu04wMSmt',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IktYZXMyeTFCUjE2YVVDcW5qY0ZhcmxIWVcyVGREdXlyNzdMNkQwa1giO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490787),('5r5FMl0yPNcpBv6meWdWLRNer0timkwjh9Gmmipi',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjRNaVZCY0liYTdRQlRFUnFYWFVidEFPYWdmUVVHVE45dnhUY0duTFgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490478),('5wSsm5BkWmJ5iWn8LQ2vf74GTuUWwCZYKSDsX7mD',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Imd3TWF4alZVVVJkUVBaNVlOOHVpS0xjd1ZVako3cjhGNUFyZVJrUGciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490492),('63JTL67AtfiRkoPaZpJxAvTBuSHDIBO9cbQ7Nlqz',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImRNUmhnY0I1ZlRDS2R4OUpyNlg5dlF1Sng2ajJ1UzdOakxFTHF5OHIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488939),('63OiTulCXbmOKrkducRBeobrieCgS7PMC7RQGcCE',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlBaZUFBUUF1b1dHZ3dGaVp6c2ozT21DRUp2MllvR1lhNkF2TUUwSmgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490475),('6cCp0S9PzjURjOQ1qB3dEQjdPe7rZDURc3wbHoC0',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik05NjA5dEdCOUVqYXp6WGhKTHUzQUxkeWJjcWtBS2RGcGRyQmFnb28iO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766494702),('6CvjOS7nlamkcbcF2v8FD8Bby0GU9R77nOigC7Ey',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjNBa2d2M3ZvcGVDMVFiMnVKdzZ3SW5PSGEySFpHMW5CRWhKVExPZ2UiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488938),('6ixvAQPcHmTlU7s0oyMt5GhXaFl5TvNHMq6L4UcK',NULL,'213.233.110.121','Mozilla/5.0 (Linux; Android 16; SM-S911B Build/BP2A.250605.031.A3; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/143.0.7499.143 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/543.0.0.55.73;IABMV/1;]','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Imk3S0Q1NU1lZVczRmY1b2Vrd1pZMWp2Rzk2RXpZMEVvemZ1VnhzckciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766499551),('6j5g7NSKnRH29DaCDAJe2bGgfYqXQE78FHPsCXfv',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ikg2TnU3OHFPYmhRYTNuN1VpUXlwRFFrSXNJTTF6c3BSYVJGN3A1RjAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490497),('6LvjdCMLGy0rfrWPfTLARQxu1OuXcubRT0ghRSfD',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImlnSFlsU1d0dlhsOTFtQ0ZGTjRtcmM2anljekxqczB2VUdlTVJqTFQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490473),('6UKQxqM7XpX56kwFKNpZTQ1cn5GaR0zRFve9FHdd',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjlwUm1CazNsbXdwd3RlUzlnYjdqem5PYUZFS1NxTzNGcDcyRXBURHgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490478),('6xF2JYBunmXUPCUWmPYTfVGZN2orvOXDRArugTvq',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlI0cnhnUFIwUjBmYWRPNTZtQ2x5MmlCdHNWdVNQQXBteTI5bkJiV1kiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488938),('77PbIdBOXURDibUrrg2878FDX4vhrL8xVOnhbc37',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkprUENFeXFyR3BSdldrMmxIbWozc3pOeGtEdjJaaDg3VUJCN1FCVnAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490474),('78dySVLkGkP6OZrKiFD7Y381nwVuO9am66PnYSYl',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlpENU5oUk90cmxvQWFoRGZmYlBkUTdGcTNRT09veEc3RlY5Um5aRVEiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766497448),('7dFtDszp3Q5yzQtPbxM3MhLwo68nHXhywHICqFUU',NULL,'31.13.127.31','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZUaDZ0UFdDS3BmQkduWjhMNmRJZWpJcTFNVFpOSE1qWTdKVFROdjgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468732),('7iEOnejGDDgnaHKsDplnJlXBiLL02lsCiKWMXAtp',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlMwWHY4MDNheXI2N1hXNGhWVGpRUXJQa0J3bXFjT3JrU0FSWkhzd20iO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490562),('7lQgy8k2fY1q6Xnp97OIrvYZL999Es9gYVwd8cjz',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InBhQTlIbWRld0RNSG9RSlpvT1IwTk9ibk9TdVJQaFV6aVhIRzdBMEsiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490494),('7TXTs1hQnS4mrTujucs5K34DnfTKTxOoK5OflwnP',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkJ0YkFnWWl4bWFFaDRlMTZVQXZwY1c3VEVaMjBwU3k0MEFncGdUVjgiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766494797),('7ZTEbewncemszzJ0kNhxPYsPW80jSeEdO5ad3kzC',NULL,'127.0.0.1','Mozilla/5.0','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImZNeUl5ZjVwZWkxdDNScE1WcGNqYkpzRUp2cmI5NnRPNkkyYkNkS0ciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492237),('82egtmLq5mZ7ZvPXbZPLaLZP2jVYryeA2cGKZNke',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlQ3UGF5SXVmVklkNTVXb3dicU5FdmZXc1EzVTA3NTAwVlY1TDdya2siO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488938),('8dlNuVObQLqPeJ0IhvH052egQkzbOgw5sAdpAk4o',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlNWbGlYcnFZSnhCOFNIZVpmS2dmQ29oTVpkTExGYk1EcEZsTnRpT3giO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490494),('8drDdozkKpY7J4X77sIs3kDldbD7dnKVAeogPRp8',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZXa2lZQWRpWDVIYTNNVlRINDBJRk5BUml3ZE5pYVNNSFRJVkZlUWgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwczovL2NhcnBoYXRpYW4ucm8vLmVudiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490491),('8TVDsUJ2nBHKkjCBNoNuF9NMDhrC2OPZ84DBdZbv',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImVPbHd4QkNuZTFIcHZjdHRUTUxHWnVxUnliZU5USUh1azl1UXNPakYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490500),('96aIzuo2dLtcnweewORxNnxDU8R3glDPN92plRfz',NULL,'74.125.208.99','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImlsUVVhVUJEV0RqWnFiVzlySkd3NFczSDdieUgwZGp5OUFIekJVRGIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766482874),('9AVJxV5aqJFpOvJgN5Bh2hPlMFWLlVh6ctoWCDVC',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IndLNmtCMEFqaVJiWnVrT1BydWRLQldYNVluWlNWSXpIY0t5clJkSmsiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtbmV3cy1zaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766483111),('9jEkTFK6RYzFoZYEELRNXIKfktJV760AdAcHqsYe',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjFXVlRhazVoTlFUT1VNYTlFTU1ldVVRTWlZSVhINHhQOXdkZFY0T2MiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490473),('9oIxVINltjpmkNBAIXYDBO8Qz3FzqPtYK8kpjMbL',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImJ4cXBhR0R1dHVXMjJlMVI4MlRKWFZhT0sxOHdDNWhmV3FLQXJWcXMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtc2l0ZW1hcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766483112),('a2qgngTovfaXKImseC28fayVuCADzjUrzrHcKe4T',NULL,'127.0.0.1','Mozilla/5.0','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkVDUng1ODNJdk9YVDh5am1sMXNVaktZOHJyVDE1alJaS0diVE1oSkEiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492215),('a31RVNBMj7XUZFDX7SkN6UxpUjEo0iLGnnqx3v4S',NULL,'66.249.75.8','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlVJZXA4Rkc4WUs2alJDVTB5eUVpUjAxSTVDOU91eXJDU2p3VjdrNjMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcC9wcm9kdWN0cy93ZWJzaXRlLXN0YW5kYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766486990),('a4wwhMgtFlBEfAgH9RmyWzRBJshfQSlYJqg6hPIS',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InJxMGRLNnhka0ZOOFhidURXUlpZd2x4UWVqYjJEWEVWelRzYmYzdzUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtc2l0ZW1hcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766483569),('a7j22Z6wbEoB6P1pPM5lpf48cC3sIO227dW3hM6P',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkkyYWE4QTJNdnp1RzlFT2JHakxoWDZ4MjgzVXdqaXRoUVhUdFVNZXkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490474),('adraxGj09cRvjRKCAHGqTmzsRv24AYKyYFxfw5Ii',NULL,'74.125.208.102','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Inp6azVYaGk5YTlRRlo4Q0U0a0FvSUVUTmlQY1FYR3B6cEV1SnJIR1ciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488849),('AEaoqHv68QhWuNNTZefY2ENR5HpkErtfonQxAy9f',NULL,'127.0.0.1','Mozilla/5.0','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImtabUVrRjdsTDFsa21ZQ0RmelhwN2ZIUkxRaGRGV00xNTJVS2xVTVIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492233),('aFy8Abm4vbABkQb2TBMDFKz1Fc2nt7RBDjsqfL9j',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InpmQmZxQnRpNW1lNDFTYVhiM3dKT1J4UlZGWFFpaDZpaDNDQ3lSU0wiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488942),('AGFAug8grHvvMJvd7KDbtuHXC7GlJhJ2idSoJc1K',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InNHZWFLMmRSMVlHRWIyTDkySDd0bVhZMTMyRjhrYjRMVHJ4SW5WZjUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490476),('AGiXYikKkZvSdKNXBbhpRd4fbcM41FkNCnRW0lrW',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im5oQkh5TllUUmVHOVAwUXlsSG9zc2xQTXlrV2JlenZ1TFVxWm80RGIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490492),('aSQJ697cqhpietC7LN7kddpo1nDGA0c3pE3wKfCg',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkR0OHhMWm5IbVBGSTBpaDJQY2xlQzlteWxzRjdQU2RONVBTRlNWSGMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490479),('atxD7qRipB5xjGo8wmgKNyJIOEaoueCaN0ZffXOJ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik1JUWlDWWNneWpOUURjeUR0OTdGT0xrVk9ISGRYcnM1SVptUW1jZlEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488941),('au3ZijRIgnewkqaB6h7aNdhTM8J5qUBrohFhtB0k',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkVXUDRGUnR3MzNaOUV4S3B6TXNNZHdyRE9ReDE1RDgwNUF1ejhWWXgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490473),('aUjPbOhPLYA9Vm3rPcQ7LyAxQvPvXnVQApppK3kC',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjZSR2xXaVpXbTR3Z3BZZWgyUTNEWVQybkxNODlqMGw2MGt1OHRYMk4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488941),('AWKA2PweSUsTVPxkorNa1yjGmSBIoCAO4Wi9PQ4p',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjZoaFFTZllLbEZ4N1RadTNhOTdKWkcwMHJtQXlqQkJydE5kVzlhOFoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488941),('b2SzEdhVBjx3HnIQ4j0UxrwX2MJ3UQrykz9a64pQ',NULL,'66.249.75.1','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik8xWnh4TzU2QzI4Vks5WG14N0xNZllYNXdKY3BWU25JaDZsZERVR2QiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vcG9zdHMvZ2hpZC1jb21wbGV0LXdlYi1kZXNpZ24tZGV6dm9sdGFyZS0yMDI1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766487828),('B8mftGGq7xm9smExc8gXsbVdKNc7FAWX4N7SUuB9',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ikl3NGs5ZWNBZk5mbzdENjUzQXVzeWJ4YjhaVGRlQVJYOERRSlVaVEgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtbmV3cy1zaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766483279),('bHptBuUWDJJ3oR1qTRFimSrbLK50eze8Z1TplD60',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im8ydm1xbXoyOTFKbUc0VlpwcHo5MVVaZzJ4eDdwWW9GQWdWdE85TUsiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488938),('BJMAH95B3Ctq8cIw1jJJZsTBd1eXM094mR4wx2aJ',NULL,'173.252.127.24','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlBPSTczc2tIV05VV3Z1ek9wZGNTMTh5OVpxZnFaN1VPblJmQ1ZTMU8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcC9wcm9kdWN0cy93ZWJzaXRlLXN0YW5kYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766468881),('BsYXLdfhqyv9gAWOTOzpvzPcnn0pPP20MjZfzyGc',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlRmY0s1NUZxUnBSb2NDSDNWdFo3SERSclVsdkdvV3ozV2xXczVqalIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490499),('bUW3vT40GFUHCEMZVjJit7pIOvWV5f13lZULWnw6',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFtb2lFUEhRbjFJeUJsajZXajRUUTg4MWNlMGhESmpOVVlZUHhqajAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490496),('bwGqYoGbz8mtZ3DQLEfchcMxKknb0uzNVTkxGQmQ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlNBVVVGdFM5U1lRYmd2Mk8zWlRNbzhrR0FhZzFNYWRZbDRxekR0d2UiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490494),('BxaDH6i5u7wy0LoZTU93rlJoNRMWSKODiz3my9sS',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo3OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlF4OGdKYmxCa1hodFJDbFdmbno3VDBJUFNNazNkM2NNRXlTNHJ0b2kiO3M6MzoidXJsIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cHM6Ly9jYXJwaGF0aWFuLnJvL2FkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG02azRNa1MwbmNWd095SXhWWjd4OC5TV1VCd0R1djN3RUd6VWlLbjZXcTV3WS5QVzF0VklPIjt9',1766496253),('BZXIQZ0htU60GjvtkrmHFgKXjYsxQs6eWL69ddGk',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkVmd3lMNWVGbDdBaG9PaXRUMW1pMTQxSmlucjA2dE9GdjYxV25CYkMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjgwOiJodHRwczovL2NhcnBoYXRpYW4ucm8vYmxvZy9pbnRlbGlnZW50YS1hcnRpZmljaWFsYS1hZmFjZXJpLWNoYXRib3RpLWF1dG9tYXRpemFyaSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488115),('c4VHETv68CFGB0zBQwhSGjPkDiVNCS6LwBiTzYNC',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjR2MmdyMHNBd1F4cndxcTZyYTFNREQzWkw0UFJIY24xY21Gc3l4cFoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488936),('c5eZkfIFtCJDVBvPntEHPGGC8CDIVcZ5MvAnww7G',NULL,'127.0.0.1','Mozilla/5.0','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InpSWERrcjE1WGpHOUVlRk9OTktNeDI0cHBvQjBjd0puYm1GN1RLczUiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492281),('cbrasg9U7nRQa9cvDQsZqp3x8PzinKHdEDxZqH50',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImFZTWZ0bUFNcXpsQll3U3Fjc2sxOU1uZE9Oaml3cW03SWw4elo0NUIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490498),('cHruO3DKqBhRHOuRHcJszbJGIwsLbJVc5IMkQjpY',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InZQVEtiZ21XOVpDeXFFd1B3bHV0dXphSDdVV3JjQnJzVzlNbzNaclQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490498),('CIrrE5GFVBdxqvCIFywqpbOreW7u3X4qcpf976Vo',NULL,'147.182.249.31','Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 10.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.30729; .NET CLR 3.5.30729)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkRXYzRtWlZkclpMVGtzQmljWWlSTVhkdmx0V3lGZEhCV1JSSm9nNnciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490941),('ck96PItYRqRvltl6g3dt8fyMYe8pq773HGrA3UJZ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkdzZEVRMUtTZUd1QkV6OVFid0lxYzh1b3Z2dU1rdnpibWt4M2V5a0kiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490480),('CMIU1RtDSNMg6jiVZbFiCVEmAKttyYPIWpw4ElqS',NULL,'173.252.127.3','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlJEd3pkWXN0TjdHUEdnam1lcXkwZDNDZGQ4MlVLRlZ6Mnh4T29iY1kiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468748),('Cs3Q8Toz6RcHFvMfTKy0VjtTVxYgnhbCvAIwQmXr',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkRpUWNQWFJ4dU8wWGtlTXVGVzQ0MG8yMnNERlZyWDRDWmYwQ0xvSEoiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6NDI6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbi9zZWN1cml0eS1zdWl0ZSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4vc2VjdXJpdHktc3VpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490471),('cxrdnEDINXyjBLFnnff0EuMziuDMvwSMhe5HqMOt',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlhIbGhxNUlFa3U1d2hzQWY3dk5nSmQ4SXlJUmlENGRFTUJneGt6cHQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488940),('Cz1faQhwPjpMfya1MKw8JYnDK3aQFmprzgpZeuDr',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ijg3dDk4bnlJaVVqOVFuVWRHbXNCTWMyWUo0YkZXR0xqbjBzQWVIaGgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490494),('dAsNKYIhpAXKXDxQoreeTGF9cAbhvdPsd5sOvSO3',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImVpVFU0NVVHSDVRa1dTTEZqSzNaQkNRVHNUdjFqWm1yMnNGTU5CMmQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490481),('dfeZW6wxVXBjk3Q2CuUmlTgk8SnnJGudiHCEW1En',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im5jOGFyMExRakhXRzE5dlA3QTRxQTdwdmtjTFNERDBKbkZONXNPSmQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488937),('DsxUi27E8pgIgqWIVPM7AFUxImT6EoFIohIygWYB',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IldPcjE0MGNQOWxOYTBia2h3Rm1ycTJMQnVibm1qMWM5S2o1T2RwZHgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488941),('Dw852eHibjKbRmp15sTLTtMvbGW6eqIPXLDebMCs',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjJ5cUd1OVc4QUMzamJKaWptNnBleFlOWFU5d0ZTZU9uVldSeTl5MXQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490493),('E3JEibWgg3TztocdOsZgQZKRV7q4HFrrwvQ8AXfp',NULL,'127.0.0.1','BadBot','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im96RVpXUTlUU2lUcHpRS3pYMzdzRTNybmx2dDJya3JoMFcwWnNuejEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490500),('eDNTCkFaK743SvCdtecWwXwdfr3ks8IYzLcdXtwm',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlhMbVB4czliWUFCS2pSdzQxZWFoSTBodmxwZXlXSWF1eENXdWdudzMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488941),('EgZSdc8avagpyEzYDmwoG9dG7hWOtz9748r5f61E',NULL,'78.153.140.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5845.140 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InRGbjRDWHRteHdvbWNBZEt2b2lpNDJqNVVoZUlRcG5MY0pEOEpMbDUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC8uZW52Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766476998),('eNmvtfoqV2kHDV2QNkkjDtj7jxrVaQpxwAFK1VUM',NULL,'167.235.244.51','','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ino5RTJLU2k0NlY2YkpGZlFMY0Nmd25NcVF4bHRZbEo5OEhKbUlhaEsiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766481662),('eoWnwflbk7yi1S7VhfzaBS26ny8ZxJQw7BT6Hxau',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjdYY0IzcjREbnJjZ0JUUDVkcVVrUWFWY01RVVVhcmFacGlOR25qVWYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490499),('Eyw8t2LXAN6huG6iQK5wWlBsyEd0bCHtSQSEiSfE',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlNRWXFoZjhrS3YxY2M3TXoxaWdLMldoNk54aHhORE5vQlRZeUk3VnAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490478),('FhkZdpmzD7jfEWMYAvhpjsB4kIcLtZsRcUX6k7Yu',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InRBRFV6V1c4RFE4bkRxNGJKcnJUcjJzZDE1d2JDOWIxcVR0ZzVHTlgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488940),('Fk0KVe5PqjJKCXPxc2orXHtSnPsB68NEaOPq1mkh',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImZoUjRiVnFCbmNBQnprV1NLOGdTQTlMa3JtOW8zb3ZRWVRrYlE2MlkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490476),('FKxNjXYeWPuANUy8dOXG7qWRZO0OFCn7jrjHWOA1',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InQ5TkNmRDFNV1p1ajJRdDBieWRJWnlORGd1dzFUbTVWQUhXRTRBSm8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490481),('fQpkHYKyGIaDjjOYpdQbsTbn9HqavBeJ02wLx6Uv',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InJmUU92bExWQXkzSDNodzVBWWdlVmJXaHc4ZjdSN0pIOGxVZkpZZ0IiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO319',1766496017),('FsVjkOzMqGKPyktP7cGRvNPs940cVKwtbMKsF9Ue',NULL,'66.249.93.233','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InZQUzFVZEVacEtSbVFsQTk3NVQya2xCNXhIblBJdXVDa0dpT2lXbnUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766482882),('fvZ4OsmES8lGAjAsllQH2kq4YsKkp7CDRJEDQtAn',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImxWdTBDNUc1eHc3TTcyZHNVN2txT1JKdjVRMDExOW5ZakRBWWFEQnEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490480),('FwGXPlRXcal0RPCJnwf4bb8oEx69ZgsPSvr6a263',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImE0dzhkYXNucldFSzNFV0h0eEM5U1RpWURFWkhXZzhHbmpLSmxrOUwiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766495685),('FYuI2rlvAhG2dXCcS71ebkMYMzrDtDJVsI85oIVl',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImhqaHJybm13WEw0d2lwNzFYRmJTQXB6UzNka2NET0RzUGNkTlNpSGkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490474),('FZt4HMlyJd54oYzeBmTKZ8u3ZaamZMuEKBNdKLe7',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkhVd3FSdEJrcWpIMDcwVnZaQnp0WWhCdDBGR1dqd3Y2ZlFhT3Y3bDEiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488917),('g1Okdra51oUCVhzK8L3fEy9mPFgMe8qCt20tGZ1B',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ijd4V0VyMmFJRTlnNWV6RXczdlljbVRlZER2eFVlRTdxSE1seHU2WHAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490497),('G2uMDBd6boHWqHY0gCstRWM8HmRrCq17GtkLbQFd',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlRGZDdnTFhmZ3NLOWdYSmFqbkVxakxydHR5dTg4MjYwS2tiM3ZieXYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490492),('gEaDVWYeNNmON2Csy5fuUqRnI3jwv5nlfMEw8QqA',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlVBYWU3SWFOVk9LQnVxSGkxeGhOQ2pyQmhPS3dlWlBzaHJRM1pDV2ciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490494),('GEMC3hslD0wTBGJfOvL0ESbDMoD78G6Z7ldE7l9S',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlN2cFVhbUZtTElZajdpNTR3Zk9yVExMc3UyUDdza0FNNDUxaUlPNWMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwczovL2NhcnBoYXRpYW4ucm8vLmVudiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488934),('gfVSUdBkPFFeadO9UEnBGc6LzJhMX3o1Un2vfZY7',NULL,'5.39.109.190','Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlEyem14SDVXOFpySEJvcUlEZHQ5S29PaFlXbXhWaTVLQUJXYkNIYlEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766479463),('GImvLDA8PGLZn6swdqs4JK1Ftz5uYAqyP0TJuvvX',NULL,'173.252.107.112','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik80ZFVoemx2ZmdvSFRiZ2dCZTRNNm12TnlIS0p3alY0cEpLRlRublEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766474802),('gKfW9pbEIEr49Iddk6Pfqj5XSIv5UEK324jZL9j0',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlF2aEZtNDdZazkxUVhDMUZSRHk3VDllWmlUUDJIZmliY2o0T3FhSEMiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490491),('gKTJ9fyatVyosCqpeSkFyYj6bJGrvfry57scZwFZ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InMxenlqZVE4YVRuZFB6akxia1FaUDd0bjJpOWZRQUMzWVhmZWVpTk8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488936),('gl0vUEQVMcD4q4g14sg3fFDOR5daXGn6u5o5CBgZ',NULL,'78.153.140.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5845.140 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjBqVXIxOGpIUHVGQlJRT3F0Qml5cTdiNDJvVG1mZ3BvelM1SnNUbEkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC8uZW52Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766480141),('gNR3BpzbvAejQfvNRFP2XjYFA5OymGom01obIhUP',NULL,'173.252.83.3','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Iks3UVc3cFE5SUtIWGJOS0RhcldCcm51UXlPUG1NTUdEbUpyRTBiaUMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468800),('gpn7tyAPdj82ZO3bgWFM5I11ArbaW5NI75pA7B14',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFVQU1YN2tPdnNqUHVHUlRnVUF3VzR3TWlSZEVFS2ExTDVpT29ZRTkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtc2l0ZW1hcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766483613),('GRuFcQbpb51g139Fs7g7TxfMA3LLYlvsBr8Ez1zI',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Google-InspectionTool/1.0)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik8wYnpPZkZ2N0ViY1hXeGJLNzJFSW53UUpZc0lnU2JSRHpKUkRPTTYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488839),('grUhpsX3ZVjcSecnNONHKaXdwi47ZP4TogBdDwmC',NULL,'43.131.26.226','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkdkbTJwZlJDc1NMYVJHV0ZtRXlISWRLVHZOckIzTXVEVENxSVV4Y0UiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766485947),('gtVyoRGAeepLfsPiBP88QEtXwxsxYVBc3jX8fMcH',NULL,'66.220.149.115','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik5BS0N6bERXV3o0NEZpNVNpUzRPekpzaklXbG1INzhZYnllWlNnQzgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766468754),('gvALtzc2kpk3y32BjVcfTtrOC4auMN74vgKSKKst',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjB4aUhaWE4wNTN2THZ5bzJLeGpmYmV5aGRUSTBGRDI2WFVkVlA1MkEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490492),('GYGfe0sEkNLf8v9ccQkqOcW1IYppOchWq0nTZfAd',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImZmbW5aalFGNDZpcDU2RUxLS1hFOUplQ0MxY0I4RVZNSnNuYU9XWHIiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490454),('H0s4RnqYkg06IpUHAULSILH6hbmHap7vqOiwHtWx',NULL,'31.13.127.5','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkhWWVBERXRHcjdTdUFYeXlaN2VMa216MFBMSHRwNFZ4bUdhOW5mMzciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468749),('h5bI5iIj7Du1gVoBmLRwj7hZ1YnOzvLaXDS3IVJZ',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Imd1dlVqWVNyVXlGZmdoUDhYOTlWY2dyOEl4NnNSVmNLTkdON1NTYTciO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488610),('HCOZQsZLATXCn1vWcbK7mvP5QyvpRStKHyM9TaDm',NULL,'74.125.208.102','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImkyZ0t2UHFFQk5DbkdLb3oweW94TkpxT2pOa0RjbWRQUmpiTENWdGMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490204),('hDhfLAPlUSTShngaOpjX1gfVtZX6TIr10uvUNaVG',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik5UMG1WU0luYzhJRWdURkxEODN1RnFhTnhCNXJya3pmQk1uTVYzdFIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490475),('HjqE1WthvKmUggcUpTdfeLLhWMkkgBFRORXRJaAQ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjlIWWdIaExhams4MDI5TUtKUDk0RkVrMzUyVkNuYVpoaGxndEcyQnMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488943),('HQLNqAIMgwtsW3dxg4LKJNMGSAxF3jgZFMvJXm5G',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InIzbmxxdVR5ZnRxczFLSEp0SXBMT1lQN3NXQTRIc2pVTW1WM1RycloiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjcyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vcG9zdHMvY3VtLXNhLWxhbnNlemktbWFnYXppbi1vbmxpbmUtc3VjY2VzLXJvbWFuaWEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490571),('hSUPZk2Ru4kRUTt5aelLiGKvXgq1T4mXSMmfEfMy',NULL,'31.13.103.112','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkY1MWozem9xRzQ1YWhDdllIREZDQUdVcWhZdG5XSkJIR2Q4ZXdTaEgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcC9wcm9kdWN0cy93ZWJzaXRlLXN0YW5kYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766468880),('Hv1QKIEJPAdBxdCZxYITELuFVNeHbHyRyzJb12si',NULL,'147.182.249.31','Mozilla/5.0 zgrab/0.x','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImM5WHBzOXgxNmo0d1BTU2hpaWRzbTlQQ2NtaXI2TUVSd0V5eU1NRHkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC9hYjJnIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766490934),('hXF2GY6LS9CWqfzqp3ra9qnjT1T1kYEvuRQTttm0',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InBuTXJ4STMxQ2NjVE5RTFlvaWgycnlHbmtrcTFVTzFWVFNLR29CUWsiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488935),('I3TdHvPc8RxnDKFH0RDo2aKToG5f96jOdaOLUXSw',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo2OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik0zTDBlaTZ4eDRLMkFJMWtJVTFQQ0M3WEt1ZzA5YzdsczhxbkpONU8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkbTZrNE1rUzBuY1Z3T3lJeFZaN3g4LlNXVUJ3RHV2M3dFR3pVaUtuNldxNXdZLlBXMXRWSU8iO30=',1766492290),('ienQDI5fNl6UAKxhFqTAIz2B8sTlf25NiW4oKXZ9',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkVUeHl3Qm5LUzBYQTdHRlI3TlZPV0lpUTNYZFdEZ0lLOUFtVmNUSEgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488935),('iJBPzDCt80HRjuZ2NnI5q95wFWNxyjMfWgU8jN8C',NULL,'206.168.34.48','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkRZU1ZLUEFFTUZBeW5ZNEI3Q0MzSFRhV0RkSU90UXhGckZnNTkyYUUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766486933),('Iul6yfqpZak3IJ43ObBKFcDuVDIgoOBBt9rWlDfg',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkdqckxTaTM4R3dCR1M4YUJ0SFR1ZVhQNEt2T29HUndwUXhiYmpMUWUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490478),('J8vzcDr5CvhSLL0jIpKIzlQipKYFoOm3kL0ah46Z',NULL,'66.249.75.8','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Google-InspectionTool/1.0)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InY0RWRmNHdXZ1lWckpYSEluTTZFVG5zSUpOZU45c3pOcXV4QzNkcVciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766483145),('j9CQJJSKxuvmKHtuMlpH9vbVXKz3x7dZvTuUSNc3',NULL,'173.252.95.5','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjVNSW9sWVZWcDJyMVd2aGU3Tkk4U25VQU1PbVV1MloyRXhlR2YwQ2ciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468757),('jaPPmiMep2LLMIShjARroHlQueBCN2xjKa0tdRUE',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo5OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkV3ek5WMHdMR1BleEN1bDFvYU9wa3lDY2tkbWVMR0JtY1dmazIzak0iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkbTZrNE1rUzBuY1Z3T3lJeFZaN3g4LlNXVUJ3RHV2M3dFR3pVaUtuNldxNXdZLlBXMXRWSU8iO3M6ODoiZmlsYW1lbnQiO2E6MDp7fXM6NjoidGFibGVzIjthOjU6e3M6NDA6IjQ1OTZjZGQ5NTgwYWU0NGM4MGUzOTlhMWY4NTBhMzVhX2ZpbHRlcnMiO2E6Mzp7czo2OiJzdGF0dXMiO2E6MTp7czo1OiJ2YWx1ZSI7Tjt9czoxMToiaXNfaG9tZXBhZ2UiO2E6MTp7czo1OiJ2YWx1ZSI7Tjt9czoxMjoic2hvd19pbl9tZW51IjthOjE6e3M6NToidmFsdWUiO047fX1zOjM5OiI0NTk2Y2RkOTU4MGFlNDRjODBlMzk5YTFmODUwYTM1YV9zZWFyY2giO3M6MDoiIjtzOjQwOiIwOTkxYjFkMjI4NjRlNzJjMGU4M2ViMGEyY2QwNzcyN19maWx0ZXJzIjthOjM6e3M6MTE6ImNhdGVnb3J5X2lkIjthOjE6e3M6NjoidmFsdWVzIjthOjA6e319czo5OiJpc19hY3RpdmUiO2E6MTp7czo1OiJ2YWx1ZSI7Tjt9czoxMToiaXNfZmVhdHVyZWQiO2E6MTp7czo1OiJ2YWx1ZSI7Tjt9fXM6Mzk6IjA5OTFiMWQyMjg2NGU3MmMwZTgzZWIwYTJjZDA3NzI3X3NlYXJjaCI7czowOiIiO3M6Mzc6IjA5OTFiMWQyMjg2NGU3MmMwZTgzZWIwYTJjZDA3NzI3X3NvcnQiO2E6Mjp7czo2OiJjb2x1bW4iO047czo5OiJkaXJlY3Rpb24iO047fX1zOjQ6ImNhcnQiO2E6MTp7aToyNjthOjY6e3M6NDoibmFtZSI7czoyOToiRGV2aXJ1c2FyZSBQcm9mZXNpb25hbMSDIFNpdGUiO3M6Mzoic2t1IjtzOjEzOiJJTlRFUi1ERVYtMDAxIjtzOjU6InByaWNlIjtzOjY6Ijc0OS4wMCI7czo4OiJxdWFudGl0eSI7czoxOiIxIjtzOjU6ImltYWdlIjtzOjMwOiJpbWFnZXMvc2VydmljZXMvZGV2aXJ1c2FyZS5qcGciO3M6NDoic2x1ZyI7czoyODoiZGV2aXJ1c2FyZS1wcm9mZXNpb25hbGEtc2l0ZSI7fX19',1766492319),('JblU0cHKII2ewalRvY87CrQMIiEx7M3QMPPduICP',NULL,'66.249.93.230','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Imlpb1l3a1piMlpBd3JTempIRjRSTlF6S1Y4UEVWNmxaVUZyYTluTmQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490195),('JfngAliUkeiMzcKUQO0Z2RDXwQaOA9GsqKkrnRlJ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImpVUktXcDRxVjhCMDV3Ylhnb2ZkWjNtYVdUUHpLS1pPRlhOZ0dCdHoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490493),('JfqrTDepM7boFgcakrqJM0ChE7iyDhvxte4b37mP',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YToxMDp7czo2OiJsb2NhbGUiO3M6Mjoicm8iO3M6NjoiX3Rva2VuIjtzOjQwOiI1TXNKZWN5Y0hkZnpaNWs3ajRlNFZwdUFldHF5ZHlsYnpYTFVDT2hIIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cHM6Ly9jYXJwaGF0aWFuLnJvL2FkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG02azRNa1MwbmNWd095SXhWWjd4OC5TV1VCd0R1djN3RUd6VWlLbjZXcTV3WS5QVzF0VklPIjtzOjY6InRhYmxlcyI7YTo3OntzOjQwOiJjNjhiNmUxZWU2NjMyMzA2MzJkOTMyYmMwZDA0MDdlZl9maWx0ZXJzIjthOjI6e3M6MTE6ImNhdGVnb3J5X2lkIjthOjE6e3M6NToidmFsdWUiO047fXM6Njoic3RhdHVzIjthOjE6e3M6NToidmFsdWUiO047fX1zOjM5OiJjNjhiNmUxZWU2NjMyMzA2MzJkOTMyYmMwZDA0MDdlZl9zZWFyY2giO3M6MDoiIjtzOjQwOiI0NTk2Y2RkOTU4MGFlNDRjODBlMzk5YTFmODUwYTM1YV9maWx0ZXJzIjthOjM6e3M6Njoic3RhdHVzIjthOjE6e3M6NToidmFsdWUiO047fXM6MTE6ImlzX2hvbWVwYWdlIjthOjE6e3M6NToidmFsdWUiO047fXM6MTI6InNob3dfaW5fbWVudSI7YToxOntzOjU6InZhbHVlIjtOO319czozOToiNDU5NmNkZDk1ODBhZTQ0YzgwZTM5OWExZjg1MGEzNWFfc2VhcmNoIjtzOjA6IiI7czo0MDoiMDk5MWIxZDIyODY0ZTcyYzBlODNlYjBhMmNkMDc3MjdfZmlsdGVycyI7YTozOntzOjExOiJjYXRlZ29yeV9pZCI7YToxOntzOjY6InZhbHVlcyI7YTowOnt9fXM6OToiaXNfYWN0aXZlIjthOjE6e3M6NToidmFsdWUiO047fXM6MTE6ImlzX2ZlYXR1cmVkIjthOjE6e3M6NToidmFsdWUiO047fX1zOjM5OiIwOTkxYjFkMjI4NjRlNzJjMGU4M2ViMGEyY2QwNzcyN19zZWFyY2giO3M6MDoiIjtzOjM3OiIwOTkxYjFkMjI4NjRlNzJjMGU4M2ViMGEyY2QwNzcyN19zb3J0IjthOjI6e3M6NjoiY29sdW1uIjtOO3M6OToiZGlyZWN0aW9uIjtOO319czo0OiJjYXJ0IjthOjE6e2k6MjY7YTo2OntzOjQ6Im5hbWUiO3M6Mjk6IkRldmlydXNhcmUgUHJvZmVzaW9uYWzEgyBTaXRlIjtzOjM6InNrdSI7czoxMzoiSU5URVItREVWLTAwMSI7czo1OiJwcmljZSI7czo2OiI3NDkuMDAiO3M6ODoicXVhbnRpdHkiO3M6MToiMSI7czo1OiJpbWFnZSI7czozMDoiaW1hZ2VzL3NlcnZpY2VzL2RldmlydXNhcmUuanBnIjtzOjQ6InNsdWciO3M6Mjg6ImRldmlydXNhcmUtcHJvZmVzaW9uYWxhLXNpdGUiO319czo4OiJmaWxhbWVudCI7YTowOnt9czoxNjoiYWlfcmVwYWlyX2lzc3VlcyI7YTowOnt9fQ==',1766499896),('JgEGayWpLNNtn0aK80tmX7xhcoq18bKnchD8Dk9X',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InNNYXFmZGR2VjZxQU9Tdm1weUpmaVRCSWxIbEluQjRKazV6NXpwNTYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488942),('ji8TuIgaiUfGSG3Aw8ZBKeZfmvvYUKf2wyf1SRcb',NULL,'64.225.5.115','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im1UMzNsMFlHTDdYM1Q5TTZDVE9PTFh6dUFXVDlzT3ppRW04SFNlWVMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766474057),('JjjPKccPwIk8qiaNkH55jcp6nzwGZ7dgujTWlXb3',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImtodVM2NmJnRWdsaTg3M00xREhoeEhzcVRLZlNrVmVzWHVhQ2FscnQiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766491479),('JLMQ7wvb2AN8xzJUwqdRhZnpv8gjztna355jlK8u',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImNVM0MzdmlEMXdzb2VVN1ZhR2JvZnVIT09UYUZmTlhpNnlNT0FRbDgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490479),('Jnqfp3xQV9odFWMQDefPdcEdO1URwiSIorZsm55v',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFmcXg3UnVQMEhYcnZBdFBDWnlsTE5IUGlHR01yZDZ5SklKalBmVWsiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488937),('JOJo0zRzPRAUKg2C7lQ4jExSPpVe1dhL7p6f8lUr',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjZ4d3dLQVlzNGZoZG1OTkxmcmVwNTFWR0JyNlpNWVc5bHkwU1ZSWFMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490477),('jqJKqT6ILzN3py5nPEtJnQ4QlJ3bRNM3pVKcotI8',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkV5NGgxNFJQTngyVDRFc2dDQjltWjZkM20wZWx3UkFvYmREdU00eXEiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488933),('jY9t7c6mWUgYXYX1ZODHUpSCPIqWzSPFgM8NuADq',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjZzN01tc0huT3RzbkpKa0h2MWNSWmtwNDZ4dTludmZZSm90dHZCSGgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490477),('jzbgLfpVT96l5jp9g0hIDnVTUt1ibp5Bv628zTZl',NULL,'206.168.34.48','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ink2aG00aEtzRlUxZm5lVllmQUlxcjg4M1BuTnJUNkQxVnByb3h6TXUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM2OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC9zZWN1cml0eS50eHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766487077),('K13AyutAYBjcN96GZ2gCaYgfPGhKFwWp0qcSJKyp',NULL,'20.65.193.198','Mozilla/5.0 zgrab/0.x','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImxTc2xGc2FXOUcwaEJsQnJ2ZHFVckRBR052QWJNMzd5UGZ1Mm9SYWkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI5OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490083),('KHGqbcyfTQnk5TlRpdRHolY2ZcH6c49k6w2yVPmB',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImtIMXR2Uklnd1dnajRMV0xyWkRJUUhUN1lKTklxcGN2dEl2S3VoSzgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490493),('KIPo3Rq9keOuD8uFS3mEd4YVWCrchSfMqzoruHTF',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkJ2TVFqRXNCQkIwU05SN3RwTnhTOVlmM1cyZ2RyVlltWTJIbFE5Z2EiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488938),('KOjOOoGQHIKtp0qdXhp2wk4ajPqBVhvsDdtprOXj',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkVuTEJPbHhhbUdFMVRyb1U4VHJ2SmVQWlJBejM0bDMzN3dJcXRWS3EiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490471),('kQBeF4hLPykABbLmsJypOQ9AZ1H7BRuLPSsIouMD',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImRDYzVlTTRuNEFMZEpPMUtNekpyNU03SEh0STRWb1llTkZ4Y0E5UWkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490500),('KZbbUbn2Yhgad9hyI38uFyBdoBUGTT3Ebq92fG4G',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjNQTGtYQ3JBNlZaYUZZc2ppWlJPbVZhWk5KQWtLZ3pUNFc3endEN2QiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MjE6Imh0dHBzOi8vY2FycGhhdGlhbi5ybyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766494678),('L28g9OhRvFWev7xmzb5gmCbYsZI31CeFfGk37T90',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlFCVUs1TDJFVkhWUVRxcXVtZ3pXTjdBOHFLUXFiWU5PTlJoblRaNHciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtbmV3cy1zaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766483570),('l2JB2kvwfC28ptpwOR8otBeuCvrcB7xRN6NeS1kR',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IktDQVJZQmwzWnRlbEdFOFhaQThVcXdsNUVWODhraFdmWDRqdGJYUjUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO319',1766496022),('L3JYSExu2JAzocOdcmtgISbNe1Cnbh8i7PQXdSTC',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImhLc3JVSmg0OHJzMUJoWTFsaVI3RFBDSzZkcTJQOTFYdXJLWHpxT3QiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490481),('L45NckZ2W6cnKIMgPvxVkiAr7D7UEPxKp1NPyEzt',NULL,'127.0.0.1','BadBot','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Imh0OUxva3cwcHNUTENzTWF2RERkSkt6NjRLWXFZWXhmekJrOFV1STUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488943),('l9JvenSWRKd7La4ZM0a5o9rIPFUT5Ise0kV2ntj6',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InVZclJ5eGVYNUVFbllVNkZPMndnVmhLamV1cmE0RHBlSEN1UTBKVWkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492308),('lBB7m09kAsdyBju0R0wllT6XvJradxkzk7n7pHlg',NULL,'147.182.249.31','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ild4dHJVaThiMDlKZHlhRnZ5TXpBRFhOaXI5UXE4ZlNDMHRZSEwxVmoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490940),('LGycxyFBTMD85CWjShXJKnKALAECKcbVAImwuON9',NULL,'188.92.254.117','Mozilla/5.0 (Linux; Android 15; CPH2603 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/143.0.7499.105 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/542.0.0.46.151;IABMV/1;]','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkUzbnU0YXFqbFFlejV3TmdKeFR6Y2hNZ1dWaFNWRHg2VE5DTDNsVHEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766468850),('lIiyjcuHIbW99oNha0F5f38Tpg6B9sALjTYqzDaO',NULL,'127.0.0.1','BadBot','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImFYSHJHYlAybmRTNTNSdnRuWVZFUFJVaWtZRmxMV2thUGx3NGk3M1QiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490482),('Llbkl1sHzGDyktJxkVpznii5VM3TD0xU8jefeSra',NULL,'147.182.249.31','Mozilla/5.0 zgrab/0.x','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImQyZm1yUU9OOWE5MjJaMVBleUt0SG11c3NSWFpFNFM0QTNvVWZiY0UiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC9hYjJoIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766490935),('LmmvAinxZYJqbS1sIvEjH4OlTZtVs5EgUAp2XSHf',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkxZWHp2Y1F4TlE4TnUxZFhDTm8wT2NWMElUSWR4ajA0MHdOVlZxVjEiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490609),('lQcy0aWgVqF4hTmVJ9nebemkwkhBaNSojU12vEDI',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IndzNDJuMlVVa01CU3JUV3N2U0FUUUNOb09XMjBCdUhIN1ptQVlveUciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488939),('lqgL0NoMMSwXFubTVdzCE3RzoVfzNxvUfsuhEkiG',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlF3TzIyRzZYb25MMGR1NDJtZm9weXdjTXR1bjlUd1ZLT0pQT3I5eTIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490498),('LrIZr1Kcfye0cWUlgT3YLG5Vdnbvy28s5VxhZWb7',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlVseUQyWW9uemZsSWs4M1BFWU51NGFpOU5SM250N0tiNHd1dHRmcEwiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490493),('lsVxdWrj3oKj37kYdZh72icLFREpBQTyccKV10JQ',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImtsNHVhTWVhQWhGS1RIWGRPZlM4UXhxZmIxSzY4cTNnYXZ1Q3FaS0QiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488935),('lTmSLWDTu6TFhmdOpcoQipcZRDfWKAuTbVSsBLGh',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlBJNGppbks3VzlvdnZNRXRDc2RUczRKT3AwNENVZHp3Q05rY1V2dGMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490496),('LVWoVqpycODxK4MUzevouz7SSSRtrmPiukZi5BLA',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkNNeGJ4VG1nMnZETk16cjBtN3JncWlNMjNyaGtuTkRTMmxBZ0RwWG4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490472),('LYPo4AhbTYCUk2vqHBWEaVI1GnVJ2xrKr4NgzFR9',NULL,'52.186.170.77','Mozilla/5.0 zgrab/0.x','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImdscnFlUHFGdEZhdmdlSVNUN3haU0NkaE0zTTY0MUNHUXd6NkkyaWkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766498414),('m2W6Yv2aTRLXyYYRswyK7lHijdzm3xsKF7wXjWWK',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkxUUG1IckNsaXN5YmtvOGF1MUxoWVZYbE1nMGRFZkJTQWp4b2pEY0giO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490482),('M63PkdW7bRRxCBqjpAPe5jhSC7VSV2EHX2sqSmVH',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImZlb2xBQ1ExeUVWYTF5d0lCNW1mYmdXaExTclpnbE5ZbFV5STVSTHMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488940),('mc1Yanu3SgIzpu1CFDumVjDoqDJTYWArR7Ilh56D',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ilo3SGxOZ05iemYzYzlTNFpqR0gwNDU3QTdFMk1VUlpaanB5dkp5WjYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488936),('mG6J14gyIOmCmH5ouySjwUSWTvS1n73Li8riDioD',NULL,'74.241.128.125','python-requests/2.32.3','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IllRRWRpRVliRUdmbEI4TmVtdWVvVVVaSkpXWFFCdkd1c21kMW5BRFEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC8uZW52Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766473313),('mHn6Y8pkMChLWJps0G5P9qYFGTQAfhsyiKc0XqEL',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlJDdUhXYmc5SEdFcGMxejJsRURpUlZwYVhPYjc5MjlZcFZWeEp4QlQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490477),('Mr0kiyuzzsfIuGmGzkctu4NrxCexjaT6lhYSEvmT',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlhRdnlMWW9PMXc3M0Q1YzJPU0ZsaGMyUm5oVk1lbzNiOE5pcGhzMkIiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766495898),('MrDvGOI3ToXZ6RGyDjFVkBbBPXYunWJl4FKlJyUg',NULL,'66.249.75.8','Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkpJTHpwRTdEVTh0bldkdzdMRXlkTkZFNzVKaFQ2QkVOUW1KSGFOaEoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2l0ZW1hcC1uZXdzLnhtbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766482988),('MzJ6uCiCT9x5c8vii3kkjnTXgLa0wG9PqEzG7IoL',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Inp4OXZCeFU3dzVhU3dyeHlPWXVnTTA5ZXlJdWlESTBTNjd6amFYcVQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490496),('n9tvBjqDSseo2SHrvjonziaeaQIgyQK1wzJT1Ekz',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkNNeUJ3OEYzZE5SeXE4ck1mUHVtZlpLRHBwWFVTc0d0N0hKZ1A4Y0MiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488940),('nAAF6HlDU54E17uIdUXUGe9E4UXdHFqoA4wE2ZPG',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IndSVXA1OG1ySndHbHgxTHZXUXl1ZEVvZm8zQmd2UHhWWHRDc09oaDciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490493),('Ne9qlCOpSnV6ntM0gRPj31suBk7dLyU2IJsyxIYp',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkpNWTJzbTZSMndOaUFTa0Z3YXF3enk1ZUlTd0FrdXQ3ZUQzNWxFMnYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490475),('nflz29FoXALJpi31e5WQaYhanGlDDXQWUm2zc6pw',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ikc1bUV3RUxhRE9pQXRLOVZycFlRSTg4OVk0VXZjNGxTRzJmd2NDd3giO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490498),('ni7ClBY5kfq96jYV61VTAvO5fTdHv0QVRfrzL4bp',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImluNWhTN3diRmFCeGtMVEJRSE85czFxYmphMlZBVUl3YnZqVFhONTAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490495),('nKcdLTrCL2YVvpkgnxoSzsyeqwhouM4Y0ptDtVzO',NULL,'2a06:4883:b000::d6','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ijlqc2ZxRVVjczFKVnZvempLVzRHVW5vMkJCOTlkdWs1VWkxSmU3dUkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL1syMDAxOjQxZDA6NzAxOjExMDA6OjkxMzJdIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766482121),('NlclFwo4IZegX23VYK4rCKfM5sMu42w1EFAW8arL',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Img4Q1c3czljOG5sdWlFODNydVN3QVJjQmhHQkNsSGhiVE5hQjN6dUQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490492),('NmmRlqaeLowEWvTnndQovK8WZ4gD5I3w4Ky5Tspd',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjQ3WEZPb0tEVGVsTlhwNFEyUDhKa2hIbDB4dXZiUEo5dFFyTHprVFAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2l0ZW1hcC1uZXdzLnhtbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766483280),('nNjvXdBsjl3BjUELpeCUeQzKcTfQqgk2jY8mqOGN',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFpYXk0OXNQVWFtSjk3TWczN2NmRlZlMkpsbE92Z1pGbFRwZEZNSUIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488943),('nPQ9HI7MnCCvy1kgp7n8bg2FqgQEFeGJPX8oj43L',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlBHU25nWk9rOVZObERhMnY3ejh6T0xoRzI0WDNNM0JnR0M5bnp6M0giO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490473),('nqnGFvq1vcCfX8eMcbelaqPMzJPQP1ENyo1DeYyU',NULL,'69.171.230.4','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InZPT0hEbXBJVDU1NXNjQ2hzNnRGSVhnOVJrYWRkcTNaSGhQUEpCem0iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766469072),('nwW2U9MMZTVdZXJJ5gbdW0W7OfYrfEzkp2n7aQhV',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjBQaXBjT3lpUUkxMjdHR2VRdWRsWkhXQ1J5c0NMUGtjVDVwaW95S28iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490499),('nx5wQJ2UVlx9WfGqJooSA8mQa3SP1f3QSkrAzP9n',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkFkTURaU3ZKM1BENER3dHNzQ01YOXV0T2hsck9CaktDb0UxNlJqdmEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490480),('NxjtitXZieY8zsx5UM3E5zVgoEvZ1VIxynshRxYg',NULL,'176.31.139.19','Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImI4dms5MGFJRzJhM0FIcGkxbTNMdmZwVnJNaEZRQkkyNjNZRDVhbXEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766496422),('nZYk5cePJC5mnfaWlfJ8Cqq8v7lf6yYY2BDYfFAp',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZ0RmI0aENvUzIwMXhoZVNGU1dycmN6T3BWdXI5YVNmc2Z6OUZSd2oiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490499),('o6TaFo8rMyb9nqxbERfvZXNuUerRq3uwdXyZAylX',NULL,'31.13.103.112','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ilg5S3lKYmRnYXdxT1E5NVJxRFpKV0JCZDY4OTNCSm5zWGRVUlJsWnQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcC9wcm9kdWN0cy93ZWJzaXRlLXN0YW5kYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766468880),('o9EYI2TDyhM8Y1gjkoJkba8TR8P5zirUNCi9CScX',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFBUXRFTkF3Z0FjVDk1VHl6c0R0Q0NNU1BONGY0dTloRDVBSFZhZGUiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766492462),('o9XSQR5yUNx5zi4cDhy0Q4qYovrJkVitiKf7iPJl',NULL,'66.249.75.7','GoogleOther','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IktKNFVmVlJvOU4wOUswdXdEZ1FtYVVKR2ZjTkhrb2cwbjFjdWpNc24iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468837),('oE5jYgbVRTVmP6cPWTs62QHgva0vKHKt0WBnkrQd',NULL,'74.125.208.99','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im9WNHYzanRnZzlRcTRMR0d5WW1iWUhVeUNqTzFyOXBoNG4wekdPMWQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488839),('OFeblQn4iFOny36SHTr0RYRWIRIKtQ3RwsSpaEdP',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkY5Z3JQdnhhQTR0bzMwUEYwczFVZ3NMUzJkTWVCMUFzY2pEbTN3MGoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490478),('OHLuqTnE5mzNAfYjkAGuLU1kXRI5HLtMFw43DE50',NULL,'127.0.0.1','Mozilla/5.0','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik01SFVUNDA1REVqTXE2eGdwejhCd3pDRDQ4b2JtWUJrRUY3OTg3UGYiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492211),('ohVyVrqr6ix28qL4BRLEXK1eO3kYkcjAQtqMrANZ',NULL,'74.125.208.102','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjRUSElJdmt3WTVkcHh4eUhkS09NdWx2UHNLcVF5VlcwdTVKNVk5OXAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766481983),('ON8jHCmVknWXooxjod1yk93Oa4kusFhd6PBMeIun',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlJ5UnhiQnY2Mld4c0REY1M2WHRLQU5rc2ZMVTdyaWJpRUZUclpSQlEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488936),('Ooeq5a340V074XNpa45g3XK3a8OhthyhXg1AIZwH',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ikw1bjlmSzFnd2RRVkNUMnh4a0t5bFBXWUpLV0ZPT0xoS0h6aDFLVDciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488937),('oPmKDJoUfxmKmhrrAuHDYmXNkgjKumFMjxEcHLDp',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjFoQkk2VUN2dFY3Wm1qZHBKQU1vaVh4aHZXTWJtaGgxcHFCTGZtQjYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490495),('oSACq9SoqW7ifwYdot0VeUNVX2vubBDWstuEFQUU',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkxOVnd5QVh1VjVSSnc5blRNWVYycThocU1uUmxzamRkcTZZMktrUGwiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490477),('oUGs89Qf6uOvkVe8lzFeMmttuO9TDSkM6jCljJGu',NULL,'74.125.208.98','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImNsdlhUdmVpZjg1SXpQMnBpdHF2a2NZWXN6OGgzUDlMcE5EQzhTb2wiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766481974),('oxuhQPaRH9jPJBB9KqoV2mWu9uRKAvr2aTw3eHVj',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjhLWThwR1gyUUMzOEkzZkRuUmR0MkJKYm5VOFloMDMyb2ZaT0hhWU0iO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766497041),('OXvF7LTUlywCR4B765Qxscw3cLfVfoYBW5a02pBr',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik5CNG9wZGZBU2hqdm1sbHFQZ25JTmtHZEdZT3kwWmZZenFvQmk5RjYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtbmV3cy1zaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766483304),('P40xCx4we7iNUNu3kY4OEcKBban4g9UP8TmSqWgX',NULL,'128.14.237.43','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkpaQ2E1OVZ4Tlo0elBHNlVIQ2RDdmJxTkUzeFREand4akdoV1BXOVYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766479341),('pcK273WzlTAkoPFgyWLqpAg92Eb5ZyFNidB2kl3M',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkhCSEdRYmRYRkJEVFZVcm55QUhXMGNkbmx3R1lzQmFTNkxvdFR1RmkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjc4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2VhcmNoP3E9JTNDc2NyaXB0JTNFYWxlcnQlMjglMjdYU1MlMjclMjklM0MlMkZzY3JpcHQlM0UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488933),('PFxo9aYdkjOqRslnIJQagxngxQR5PCENn3iaZsFG',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IldFQ1J5Tm95UFpFTFByS2RmeFhUT3BnVENhVmZyZmkxaTBSNWRtYzAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766489040),('phYkh4UB2RH2zJugJ7nCh8ukncRRBUQOymMZEflq',NULL,'66.102.9.133','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjEzenU2cElxSldOdUhCcG1weDFYZGloVmlmWEdGRDFFOWFZdWpjOFkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490204),('PINZDkGOoyVavcXlipGSGxzBwJCl9gxHOGUKQdSy',NULL,'128.14.237.43','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InlmOUw3S01qOGY0M0dwRjlJcEVJM0JlNEJVWTVOcjhicXlYTVBqcXIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC9jb25maWcuanNvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766479369),('pojIwJIkSv6B3AefO0Dc4GPxdeD2uZFpyPNYZsSO',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ikpjc1pIVVNJVmxvWkxyV3g4YlRqOWNwUWRBY2JmbTFSSjE0aFRCOGwiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490481),('pscud9SZFG0pOvTSx2voJKMZ6VCXC9xokxBlayUs',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkVqZWlCejlZWVRMUmFnYTFnM0czOFNLdm1tWGVqelpvMWxaYm0zbEkiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488926),('PuAvTQdROa6upVy1s2gXOEHcuAu35qec2MFFuz57',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik9LWWpkWkVwUmNHT3lHcjZ0MUJzWGx3ZUJTV1BxMmRmZzdXTGgwOGwiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492302),('q3N3rxSIixrb7YgSKSsd9MkQ8pIc6LOiacTAfot3',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YToxMDp7czo2OiJsb2NhbGUiO3M6Mjoicm8iO3M6NjoiX3Rva2VuIjtzOjQwOiJQRXdOQ1ZyeUdVRHptQ3UzRjcyOHlLRzBJRVBNTTJXS0c1MXBJeVFHIjtzOjM6InVybCI7YTowOnt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRtNms0TWtTMG5jVndPeUl4Vlo3eDguU1dVQndEdXYzd0VHelVpS242V3E1d1kuUFcxdFZJTyI7czo2OiJ0YWJsZXMiO2E6NTp7czo0MDoiMDk5MWIxZDIyODY0ZTcyYzBlODNlYjBhMmNkMDc3MjdfZmlsdGVycyI7YTozOntzOjExOiJjYXRlZ29yeV9pZCI7YToxOntzOjY6InZhbHVlcyI7YTowOnt9fXM6OToiaXNfYWN0aXZlIjthOjE6e3M6NToidmFsdWUiO047fXM6MTE6ImlzX2ZlYXR1cmVkIjthOjE6e3M6NToidmFsdWUiO047fX1zOjM5OiIwOTkxYjFkMjI4NjRlNzJjMGU4M2ViMGEyY2QwNzcyN19zZWFyY2giO3M6MDoiIjtzOjM3OiIwOTkxYjFkMjI4NjRlNzJjMGU4M2ViMGEyY2QwNzcyN19zb3J0IjthOjI6e3M6NjoiY29sdW1uIjtOO3M6OToiZGlyZWN0aW9uIjtOO31zOjQwOiJjNjhiNmUxZWU2NjMyMzA2MzJkOTMyYmMwZDA0MDdlZl9maWx0ZXJzIjthOjI6e3M6MTE6ImNhdGVnb3J5X2lkIjthOjE6e3M6NToidmFsdWUiO047fXM6Njoic3RhdHVzIjthOjE6e3M6NToidmFsdWUiO047fX1zOjM5OiJjNjhiNmUxZWU2NjMyMzA2MzJkOTMyYmMwZDA0MDdlZl9zZWFyY2giO3M6MDoiIjt9czo4OiJmaWxhbWVudCI7YTowOnt9czoxNjoiYWlfcmVwYWlyX2lzc3VlcyI7YToxOntpOjA7YTozOntzOjQ6InR5cGUiO3M6MTc6Im9ycGhhbmVkX3Byb2R1Y3RzIjtzOjU6ImNvdW50IjtpOjIxO3M6NzoibWVzc2FnZSI7czo1MjoiRm91bmQgMjEgcHJvZHVjdChzKSB3aXRoIGludmFsaWQgY2F0ZWdvcnkgcmVmZXJlbmNlcyI7fX19',1766499597),('Q4mrdH8uOQNZfomWLf3bLv4eNVZHmHTyDUYlhGyb',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFkVEtoM3dTVWg2SEI5OVB3clhnWUlBMXdudW1ZbTM3VWdmeUVlU04iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490477),('Q9852dQ0i9uzaSuT5p9KWSBFjUzw1l8KiDIHcWjm',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InBBS3Mwb0VCRGpGZXBua1Zua0xyTWRoZmdPQjY0YmlSNFFLcndySnQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490480),('qcqbhdbyiVPWT3XFTBnHfXW0crxWIGVGsG6AvxSW',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ilh0dlJ6R3ZjUzc2M2syYUFZczNycGtGOHEzeWxBZ2NLdktNc3VQQUQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490480),('qgKm3PBqFgBmMggLFLp6kxfNMx4gF7pYZr7HUvmo',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkNBWmp2QlRUNXM1RzIxejZzaEF3MTUwbUhUNnFwNjZxOHN6dzJoY3kiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490496),('qIfYRGfCB5m7wgcM7yLOPN5pVPz2a5EyVXZQXH6I',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlNsNUhxbDhua2FUUld0aEFYVHRCYzhXYThWUWZINExyeEJ1YUdtWGQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488937),('qPkX4Ylr0RiZ3YgvhKgni6QNtJxRFPL5VZ6zzgjl',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo3OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InEyZFJSQmdIb2I0VHVVR3B6VDBiSGJ0MU1NeklVZWJ1UlZwN3U3MjMiO3M6MzoidXJsIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cHM6Ly9jYXJwaGF0aWFuLnJvL2FkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG02azRNa1MwbmNWd095SXhWWjd4OC5TV1VCd0R1djN3RUd6VWlLbjZXcTV3WS5QVzF0VklPIjt9',1766496548),('qPzEGqsF3tCUWDgJT06kgvjdPfBIXcxYIQkioVDP',NULL,'74.125.208.98','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFROUx6RnNBS0Q0cUtOZnJ3dlhHZGZyNjFJWkJpenY2b1daR1pPSkUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766482874),('QTWuoh5vFB4A2q341my9z9pcyAisGRgKK0dvF3af',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ik54VEFEenViaHhCd3JBdmRQZXA0bDlIZXpwS1RRZ2Y3bGNZRFA1NUUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vYmxvZy9kZS1jZS1haS1uZXZvaWUtbWVudGVuYW50YS1zaXRlLXdlYiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766485881),('qYtyfKLRr9LaZt66vqlImZzxhHUo2rYFmbiS3nk4',NULL,'127.0.0.1','Mozilla/5.0','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjkzS01vdGlwdEkxb3d2d0k5aWxrT0VtdDdZc25iamVIaVg1U29aZ0giO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492237),('r91fg3lsW6uhSeBV7yGLqKAxJ07uDSTP8Y7D7HK6',NULL,'94.23.188.206','Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFyc01tRHp1a24xMHp5OTR6Q3JaTHN4YlBhc01SUWZtNm9Dc09HUVkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcC9wcm9kdWN0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766499808),('rC9FQNos6d14r5c2PEhu81fRW0fy7E815Ll7DutX',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IklWczd0MGZDdzNEZzhKNVJxUVo4ZXR5THkwZW92dGJ1b2NvaWVYTEoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490500),('RdtRl8E67qZORKrVUDhfjoBWNNMCXBnheRkphHXG',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImJjZjhGM1hmM3l6UGg2NmRyUHZCa2pEWFdCNEJnR0o2YWx4dVFIZVciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488942),('re9JtjDtxpvp2DfUoE2GRSCxw8Ot2VocvBvnfasC',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImtBbnRUQXhVODdOaUVSbEgxc29EdWlpWFczYjhjMHZxY01mN3BhQk8iO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766495893),('RezJMuV220B6QEHzVbi8xMqPZIy9sBUQN1BLTvyv',NULL,'57.141.20.33','meta-externalagent/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZwRFZRN0djUVlWeEg2TW5qdEE5Y2gzUkpMOTVYQUFaMzV2Mjd2YVAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcC9jYXRlZ29yaWVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766483602),('RjhlHIiIanqFfp8AzhBa58lqHeJ4jDCiQI48dtkw',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlJQZGNxTTJXTElFaDdabWs5bDU3Zm5IWFg4emZUS3dDekpqbWtUVHIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490496),('RkpLHTbKgQoPhqd1Tj9VHqLuq3CguVQ3G6GmUKxM',NULL,'74.125.208.101','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjVNMHdOWkhpZDlkbGlNTEViTlVDeUZrdUY3cGVsN0FkNW1xWElFSXYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766481981),('rLSDO5DiI3M0AlxZNZeaLcohYolZsMDPvk47P4nA',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjRydlRGY1RNU1NUWUFnbDZIbnN3U1c0VHQyc3Jydkd0RVF2RzJIY2EiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490495),('rS21goy77G9ny9XV7u993Dp197gc1leb2d7Pke5C',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IncwZTBDYXljVXZTd1FLaUJLMHdZOXJFT0FDbmIwN0w0T2R6dlg1dHMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490473),('RuTBd2fG5z0V9NO8XgnrYI5lev3MSng04mpZwzas',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Inl4N0NrRDZ5MzRvV29mTHNscFlGc0ZJSzZZN3J0RkphZkNNY2dRcUIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488941),('SakYvUjqQpsjmPmetbChYBTqA4h5lPQ4swUKjFVn',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InhxdzQ2bmxQQkJrVXBYa0pKcXNlSkhRdk1MZG5TYjBmRTBoa1JIYm4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490438),('SdeS0HBPKWbwq9ihrtjRYngUzigcdpnoxC7GkiNi',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImlxWGF4T3FrVk5PNkVOVEtuNUVsQW1XaFc1cEE2Zzk3SHJhVFVEcVoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490496),('sdwyKSEqMwmPPnCwL2mRGpRq6VzX6loQV6D75V2w',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkplNmNBUWlON0I0NEh4c3ZYYVhIVkFrcTNsYjhkdVVQU3lCOHpsZmEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488942),('sggvbJJkBKyart5pprAzageQkscAmLeyBVErEuee',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo4OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InBRMWFWS1RQNFBnWEZKSnozVHRxS2lJR0dnZnpTTHF2aUxJNnhHWjAiO3M6MzoidXJsIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cHM6Ly9jYXJwaGF0aWFuLnJvL2FkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG02azRNa1MwbmNWd095SXhWWjd4OC5TV1VCd0R1djN3RUd6VWlLbjZXcTV3WS5QVzF0VklPIjtzOjY6InRhYmxlcyI7YTozOntzOjQwOiIwOTkxYjFkMjI4NjRlNzJjMGU4M2ViMGEyY2QwNzcyN19maWx0ZXJzIjthOjM6e3M6MTE6ImNhdGVnb3J5X2lkIjthOjE6e3M6NjoidmFsdWVzIjthOjA6e319czo5OiJpc19hY3RpdmUiO2E6MTp7czo1OiJ2YWx1ZSI7Tjt9czoxMToiaXNfZmVhdHVyZWQiO2E6MTp7czo1OiJ2YWx1ZSI7Tjt9fXM6Mzk6IjA5OTFiMWQyMjg2NGU3MmMwZTgzZWIwYTJjZDA3NzI3X3NlYXJjaCI7czowOiIiO3M6Mzc6IjA5OTFiMWQyMjg2NGU3MmMwZTgzZWIwYTJjZDA3NzI3X3NvcnQiO2E6Mjp7czo2OiJjb2x1bW4iO047czo5OiJkaXJlY3Rpb24iO047fX19',1766494376),('sLzE4Dg35YBQA8VfXseQBxPb6Kmns0MQnt59bBSJ',NULL,'66.249.75.8','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Google-InspectionTool/1.0)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InJ5MWdldFB4cTZwRno3UVpVWXJqOG1tVElIUUF6TU1vSGpnMk05WkciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766481947),('ssfkU0bdjRFOYzWhsLtpJLXgQZCvlgBcbyMh4h4Q',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjEzNlVJdG50MENEbEtFWjFnRkpWQ3N5TVc1NmRURGFUT1FqckVIWFMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjcwOiJodHRwczovL2NhcnBoYXRpYW4ucm8vYmxvZy9pbXBvcnRhbnRhLWJyYW5kaW5nLWxvZ28taWRlbnRpdGF0ZS12aXp1YWxhIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766485884),('stVp88Vls27Q0SKGWlK0HBrmKMz6F7gPc2HjVYft',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImJubVNRMTNsZnNXUnhNeTJrQlZZSEl4eEtIWlJJdk5lNDRhNjVWWFEiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766494706),('SubntsnS0rq3pU1ugqWxsNusukjZMojwpZi7oSfI',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlRSd0plTVhza0U2ZEVzZnpxNFJBMnphN203YUpYd0w2eE5FRzVoVm8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488935),('suL6i3leRlFIwj7E0gJ67kQ4z9ak1S5uDd1x7yXX',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im43cllYM0tHV2dza1ROZkhyRkx1UldpY1lUb1JhWkZZMFFCMVBuS0QiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488940),('T2DzSxCbIGE64IOuz0thOW7PU0jd7pNEDgm0z8xN',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InZJb2I4Y3p2SE8ycTF4aERZdkR1ZERwQkxyaXFEYWVEaEIzaDBkQmkiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766494850),('t6dun6NR0UsoF05ub1Ppm3XFLADTMr9lPeJMBG17',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjdoTmRXWTg0Y3VpQ2ZBSFRJREI0N3JaT2I5VEVtenFqRnVhRUNzU2YiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488918),('tE8n9sIOG0GjoeLSm3zOP18jTA2E1IUd4qTCwZaO',NULL,'74.125.208.100','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjVyc3lFNHp3WUJnSWZBWnViczh3UXh5eDFNalJEOENEMUp2NlhYRHkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488839),('TIMH5D5RaIPJccJePyEi2qRzi3yETGbTUi5rz30b',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InVMYWtiTXpxdGVHdDJHaFpDUTdrb1lMMVU1Z3o4TjlEdTFycG12cjgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488934),('Tkf3p69hElmtfXIB1VTiWg7ZWDGch1Ls6Ndl3qb0',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImYzWnFndHRZWEhSdmxicXp1MWd4WENxUFdNVTByOEpFZDY1c21uVXciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488937),('TnLeM0IsgCZ1pX6zjNQWlbhxTkfV30yAJUtzfeb1',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlQwaHl5Z05CWFM0Uk1uN1pJOWhJVGZZQUxrZEhsYjdaN2pMSFFtRkwiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490475),('TUF6iCU3h28qbPptnXYWMG1mPjxmDBjIUwlPFEJh',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InRkaWI1MnVJWmNleTlqVGE2bFZlQmYxa2Ria0hoZEg1MktZUk9LOEkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488940),('tWlIWYknP6w0bkKSzUo4RE3utUqoDmH5rMOJj8S2',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ilo3WVNNZ3AzT3FWRkFZZmNtMXpRUWU0aG1TbmhtaHJweWVWWTU5cXMiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766494843),('TwnXaT0jLtnGzIYe5XRgmPLu2VbgboHNHxxzabCa',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Imo2SWNMUHc1ZENyeXBJOXNidmxacmxUVDNwZWlHU2ltV0xpT2tUcVAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwczovL2NhcnBoYXRpYW4ucm8vY29tcG9zZXIuanNvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488934),('tZjqSPrjc74iU5nF1HjLY1ALzFEMdOpfGySfiq8k',NULL,'206.168.34.48','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImtvcmhxQk9UbXlLUDBndmFlUUM4NWlldzBrRkIzNGRWQkhyOWUwMk4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIzOiJodHRwczovLzEzNS4xMjUuMTc0LjIwOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766487032),('u16WJbsoHl3iODLc7kWgJvzC12bb2OGAGdGCp2en',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImdkTUplWDZYVWRzdlBRVEFud2R0aW96aTZHamhkZmJBMlpmQ21MM0kiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490498),('u64DDmO8KFmAHCyevpwaQzovVE6aoBEGiAQSiTqb',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjJOUGVkSzRXb2p0RXJFQjhXYnpwbjlRVEhJNUthZEI0b1R6a2l6UmUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490495),('UfmJtWukAcbrO7Ttr7RPkKv4leBj9D5bpXaSXFrI',NULL,'66.249.75.8','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZZYWg5UUQ4TXRKVW5yRU1JeG5BNFZVSnk5SVcwQ2ZxVGI2cko1ZmkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYmxvZy9zZWN1cml0YXRlLXdlYi1kZXZpcnVzYXJlLXByb3RlY3RpZS1zaXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766489083),('uGVJkhdb99IGqzRurJmn3IPochzmuOrTipdqeIVd',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZCNnNJbmlzY0ZVQ2hWTTZMd09yamVEY2h1TUNESTBVUFQ5bVRiaDAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766488579),('UMVG8FDXrBdfQ1hEuaJWO5JOXvXsc0qAoFmr8qzw',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjZHUExGQUFzczd5UEZlU3ZZdkFyM3dFa0swQVdYQTVXaHE3SmZEc2wiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjcxOiJodHRwczovL2NhcnBoYXRpYW4ucm8vYmxvZy9jdW0tc2EtbGFuc2V6aS1tYWdhemluLW9ubGluZS1zdWNjZXMtcm9tYW5pYSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490203),('upo4HQLcMt2nK0HZEKNmqZS4O7gKQ4rUwurSYat6',NULL,'66.249.93.230','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlBsMmVGZjhMSU5zTmpYMnRuMEZsNXh4WXVMeUcxTTJmSlhoSmtMVm8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490195),('uSDWMZF4SHO55Nx2DbZ3MgcGS2BnMIoxdLQN9XyX',NULL,'66.249.75.1','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlR4aXlZenYxSjk0MEJ4N2pxUmxVelE1Q2hJRTViWFRHSkU4WElLTVQiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vaW5kZXguaHRtbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766492778),('UTV3jj2Rd185OUBEdsx0uoAaHtGUaRpji6nZx9jT',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjJiQm9nSmRSRjFMa05Mb3JGSjBzZTJqOEZqWWdZdVhqc1ZDMnBrS2IiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490473),('uU9UkfasBsZNpXchcKXbpplZKMAc4mQ1LJFk7mMS',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InhwN0xoYkhWa3RMS29JdUNBN0JqN3duU1lDS283VHdrR1NCTHpMVXEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488936),('uYsrVVDVpgiGhvWHMkuiChiHwWr3Zb0zCkOnKVri',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImhIYnh3WG16eHdTcllOZVlSRjRvcloyOFRHb1VITzBpc1JZZ0dSbzYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488410),('V8gF4ffytjxgXwK1oeOYbW3KLmR6R62ITI2VrnMG',NULL,'86.124.34.22','Mozilla/5.0 (Linux; Android 16; Pixel 7 Build/BP4A.251205.006; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/143.0.7499.105 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/542.0.0.46.151;IABMV/1;]','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjZzcXZQT1drNGYyb3dheVpKUnRvbG5pazNoVW9EbGxHM09DRU9oWlEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766472745),('Vc1nkNLzFlr4NqUoiES84NuaTxwHrn3vcbWa7wrS',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImU2a0JrS2dORW42cGkyUDRtNHpUTGNIRGZYczJMZnZvYUNxd3NGMDIiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766497051),('VDDKEAmbR0BumRQMuBrxyj0aL1G4TAeS3YcPdXBv',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjRpbU1JdkpwdXJVSmhwcVVzQkZzUHFPRWowVGVsbERFeVBhY1JONmgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwczovL2NhcnBoYXRpYW4ucm8vY29tcG9zZXIuanNvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490491),('VDUPtAKxujXd8bje7UF1lOFpCPhdReZRWUbGaitO',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZrb1Ixd1MzbWZPQVl2Vk1kMUJGSE1VdElXeUU0Z0UzN25RMDVHN2oiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488939),('vg47Ui1YqptDhypLO2tRHtKPO2Wet6aedpeND7eT',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkhrQTR0V1dlUThVWElIUXp3T0YwbmttWkVhZTEzTHY4YTg4ZGQwcUIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490499),('vGQKNcY4S5dHjEfbs20bVsioxPhqawyGlkKWcdvX',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im4xMlNyQ1FJNVBjaEJjMHN0WWlvZGI2NnMzQkZtTEZlSHRoOUVhanMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490497),('VnHNZlQTAzQHrF4pfCIS3HSSj8FRAlOKmsaz3AaP',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InlyajRPQTZ2d3lwSU9SREl4bUJaeDUwM3hBMU5OUG4zTVBPRWJlb2QiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490477),('vogrL0TCQBQSRUrwlrR4SRl8Aqa523xbjkMYjdt9',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo2OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImNidEdYRlg3VWxkS3Myb2dCdkx1R1hzbUk5eE8zM0RZT0lEc3dCSXIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkbTZrNE1rUzBuY1Z3T3lJeFZaN3g4LlNXVUJ3RHV2M3dFR3pVaUtuNldxNXdZLlBXMXRWSU8iO30=',1766495578),('VqlQkuW3McpyBXYrTNdVK0NNQazjq8vyV0w5miO8',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ijg3VVNFMHpkeENudlA5Y3AwamZ6NWN2a2NEWXRQRXNvUlpnemE3dkUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488935),('VrCRbPQPvom8NnG3Jo9H4pjagsdBjx9oAWWvKuQ0',NULL,'86.120.238.117','Mozilla/5.0 (Linux; Android 15; SM-G998B Build/AP3A.240905.015.A2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/143.0.7499.105 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/542.0.0.46.151;IABMV/1;]','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im4xdWFOYkY2RUNFd0xjM3RTSmJWek55WklQTnN3SzZtMVZGM1BNNjEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2NhcnBoYXRpYW4ucm8vY2FydC9jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766474789),('vxUMKzfL4NXmibR7nDBnQL7Y5wuqZulWxlVyD7Jo',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkZaWmhabk1kTU9HSHpDcXMzc1dNNjlJQ3U0Qkw5SGRkR1hXSXM4TUkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490498),('VyfKvmkCKWzTZxM5yrmoQVA7RxqUWTHsdfAGMS7z',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlFtQzhLRGpyYmJIVXV0MHprYXpjRVZkcTdjNzA0d0p4SlN6d2ViSkUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488942),('w3sSovWGf5jVlGSsxqkM0WS4qhDKPjMuxvhY0Gxy',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjZHUVNjNWVHMm93YktHRnQ4aDRRaUZXTURDMEgyeTNJNkJ0VEN0RnMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490479),('W7b2wBi0bbrFmI1tjovX0gcigQNUZhEDBCqN9aXG',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImJoQjJoZzhDRk5NbkhuN1lNMG10VnkwMERRNVQxbUVBRk5EODlaYUciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490474),('WlJrnsI842NF4IE7pD79HxcelbEkd1VXf2tdQHkB',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlB0NnF0QXNIdTdYdW9DOHhwc2xWY1UycWFzYXFXNEIzd1NTejdaRE8iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490494),('WLTvKPZ1AlfOa3GPLFax7Mux6UM3foZuyruKsKDa',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjQ2bXY2VFhocFpyRzdVUm16cFdJNXhTWktJMzJXcE9RUlpibzVzb0ciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490495),('wNE8YiAlxArhSFtXmxpX56n9QMre2PGVxZfwGq3r',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjhuNmtpNFgyWHh0aDRZWlJhUVpDTGF5TllrY3RHRUhyUlFKUG9iNEIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtc2l0ZW1hcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766483280),('WPhG3VynCuRBIPzdywBiWuhXCa99pNHSvWbICjlm',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlNTMHpkS1lMT1hveFpJUUJEMU04SnZMcjVxd0pOdTBYc25lZWk4VjciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490476),('WpxzIHxmCyW8uN5Jdxb3gX9sKxIg33w8l51UUUMl',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjlaUGNBYzNvU243Zjk2dlVXMjhNRjFDQjFtUVlPdW5CaFU4aFVhZHkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490495),('wQs0epk8fjWWKqMi4zTpdpaBptmCBWXOAOgiiPJp',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InhTS21RSnVLY3ZQeWJQZTJScTZKaGdNZm1Id25KQkdNT09ZNmRUdmoiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490475),('WrBeGrbsoVdQJuPW1auVmz6HYUXwWjotFMMu94yN',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IklFV0ppU3lmaW01VUhuV0NzOVFUU0hZZ2p2eVVZb2tNNURJSFVjd2kiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwczovL2NhcnBoYXRpYW4ucm8vLmVudiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490472),('WRwTd9t4rPWsvoatmmEpfvrAXi3MOhg6MXSaJWvF',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InY5dWlrekVpbm5uUGxCdjhKV3ROSDlJV1FidEg4SFlaZmdMbllQR0wiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766489040),('wtWgqp5iWwxYU1l7L9rq20BYb3eKbBnf3rNGL8mT',NULL,'66.249.75.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjVmSnhjUUV3QmJpRVNOOEtodDV4UGs1ZnFZcWlSaUJhU1JjR04yY1YiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjU4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2hvcC9jYXRlZ29yeS93ZWItZGVzaWduLWRldmVsb3BtZW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766488578),('WyVOn9q42IMOcP20rDnkZKrfAx4YlAk6x60jze06',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InFVdzNnWEhHNmZIQmhHVXJPdmQ4NEM2SFRpcXlJMWI4bnJScGFHd0oiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490475),('X1Y59vdFO7d5HsvgC2UwOUN8kJVZISzVFCvIqQgP',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Im5NS2V6Q0YwbjZwVXlKM1VNdmhSdGZsVU1GMUlVWUpheVh3RXJwQ1EiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490538),('X5Wz9Upxfajc7bMcusVukCcenfotZWCkuHTKbUEr',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjY2cFdpcEt6NTRiNEE4WXZBckJMbzJGZUtjaElnOHg1WkgxT0piMzgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490479),('XBv9ULKEjVirhRVoKH9BOM4Vxe2pVjDdHg5iyCCx',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImVXc2NPRnpTb3o5RDB2a3JsajREMHFyRFpYZWpPb1FxWENmOWtnangiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490481),('XDeillVJU50atrhQtwS3fAQvW8a2t7WRKj1Zmmhg',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlNaNTd3emlnUU9pNklzWDdQUUM1azJLRXFIdW9tNXF2RGR3Y2wwejMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490474),('XLRM5yCcyw2FYgaHYuX04udgkf2ckz6UCevXiS2c',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo3OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IndvR1pJTTFLOE8xaENWbkJMVElkWkdDcFYyempLMTBOVlN5a1YwTVoiO3M6MzoidXJsIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cHM6Ly9jYXJwaGF0aWFuLnJvL2FkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG02azRNa1MwbmNWd095SXhWWjd4OC5TV1VCd0R1djN3RUd6VWlLbjZXcTV3WS5QVzF0VklPIjt9',1766495927),('xMcDUo6WJmLCWpezyyhiySFqr88PTTGhPnucnkV4',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlZjYkJqbVROcnVZVmlRRXJHY1J5T2JIWHF5MUdXUlJGazM4dGo0WFMiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488942),('XsFYK5P9Qv0LujygqnJn5UVPf1Uz23mye8h78aiR',NULL,'74.125.208.102','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ilh4Z0x4Y25uZ1JLeEZJcWN0bGFoZ3lKWXFrQ1FTWFJ6ajJieTBtdVgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766482884),('y7NS2SFHpo4N0kRmVJLFJ8CJybAAFQBldtqRuyaT',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlQ0dGtYOFpONHBQNjdTM2g5TWVEb1ZPNVo4OFFoeWJaNFRab2FaUnciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488939),('yfYZbMCDyN5l3b6lXu0Ba27yug67HhFwjxOXYHen',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkdraWhUSVB2OGhpNTk1WDBZRktsOUxkMkx6WjAza3NPZ0NjM3ZzaHIiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490476),('Yk5TWLvAVix0I8CyHezqmKf9L9xI9HedLIioe43n',NULL,'31.13.127.1','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ijg2VkNlQldTZmNBOXZ1R3BIYmdUWHJadGtNU1dLTllFNjlTSWIweTYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468748),('YkgIkGren7ZKWsNGBfMApBg6j1r11giALNJjgAbv',NULL,'31.13.127.41','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InU5dVBuRXBCbFFac0ZDRnFRM2NGTzdtN3dTblptRm5ZWk5qSExRbzkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766468745),('YPNyymKAvG7RtAg3k8K99hIh91j9kmLiM5K3Q0xl',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IllHRFExTENxSElkTUE2Q25vUWVHcTFFZ1ltckJkTjVTWDJlY0MwbXgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488936),('yR4XSN2LkzSi3gegrRgRtGPt81YnXkdUelSKIFmV',NULL,'127.0.0.1','BadBot','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkNyWXRKYXVYajQ2a1ptSFdDM0NRQlBmMFB6Wm9NSWxKUWtvaU9vYzUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766488918),('yxC4SLV47BrTjsFtITCrK7AnAd0HsDRZXkW3eJC1',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImFSYXRQc2lRS0x4a2RPSmZPQ1RETFE2WUF2RkVwNEJFeU02VFdUSmEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwczovL2NhcnBoYXRpYW4ucm8vY29tcG9zZXIuanNvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490472),('YXdq8TbNPfksJ1sE3KGIEAyEjkUwGU66aRw0WECA',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlFmTUVQcVJSWjBTYWpvSVhoOVh1dkt2eHZUYldUT1RobFJGa3JOZGYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490500),('z5oSXFIArDie1e6W7Vq7ZG5ooldKfjKhUE3Wmj9q',NULL,'62.146.227.155','python-requests/2.31.0','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjBRUFdvQVFYR25hYk05YVV6dUUzcHNHZFlucERUMlNZTUZ3Uk1HUlkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766482835),('Z70PNLTIOTTVrdjvRd7M2EzKBTVhimqEe1CdrVa1',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InpMaGJHN3VnY2pHdHhtV2JWUjVGN0FkUzZFd3QzNXM3SDJ5ZzVSRUciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490480),('Z771raJ3xJLaQx7W0eyUOy7nikQbF5KAOP72nMbf',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InU3MGxwRFZzSGd3bEZLRktiT3NCSFBnWDhSZ2l5REI0cENuaXFLYXYiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766490571),('zaBMFUNUAeSww3VmQ90Z9AYIDEzqLLGLfIBPbbh5',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IjB1QmpXenBSaWdsWG40MEZYbUZFQmxWa2hjdUNQZ09UT3Rvb3lWbVUiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjc4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vc2VhcmNoP3E9JTNDc2NyaXB0JTNFYWxlcnQlMjglMjdYU1MlMjclMjklM0MlMkZzY3JpcHQlM0UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766490491),('ZAQq65Pn9fhXylyzMt3NnPpH5FEEIdkj08BujFGZ',NULL,'78.153.140.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5845.140 Safari/537.36','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6InhRZ09vNjBDRWp2UTI2Wkx0aERXSTlFbHExejVsZDlNajVUU21zVGgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovLzEzNS4xMjUuMTc0LjIwOC8uZW52Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766497501),('ZDCe14AfbeHggUrVerE2nrwkGVkDVu0Ke6ch1LlD',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImJQNFlIRDVTV0U5RzBpN0hBTjZLRzMzbzloaHk2OThwVXpuemUyYTYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwczovL2NhcnBoYXRpYW4ucm8vZ2VuZXJhdGUtc2l0ZW1hcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766483305),('zh7W1A5NgW4qV7Vzx9Q7zjsXHCD0Fvd1NHhjqdjp',1,'188.92.254.117','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo3OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IkJaZGpHdHExNlJYWHBOdWhTRW44ZE1hNk5NZ2NEbUs1Yk5xQzJUYkQiO3M6MzoidXJsIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cHM6Ly9jYXJwaGF0aWFuLnJvL2FkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG02azRNa1MwbmNWd095SXhWWjd4OC5TV1VCd0R1djN3RUd6VWlLbjZXcTV3WS5QVzF0VklPIjt9',1766495728),('zLzBIkwu1sn5VboQS57zNIgdnGQs6qlcR8hgay5r',NULL,'74.125.208.100','Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36 Chrome-Lighthouse','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6ImhzQ2xBNmpCcmt0ZFloSk1pR2U0Vmc2dnRnaWpVZUhQd2JxWmRvMGgiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwczovL2NhcnBoYXRpYW4ucm8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766481974),('ZPuXPSR2MolMOFo9bxVLcWQexwbvRRn9AXOANREy',NULL,'127.0.0.1','curl/8.12.1','YTo1OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6IlJoZTA4RHQ0TkpaaXhmNHhRZjVsQVB3Rnd3djVPbkFDR3pnS21neTYiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHBzOi8vY2FycGhhdGlhbi5yby9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766495730),('ZT940wmtliUg7AtA8e3tT6QHcA7xrxyDhl0a1496',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6ImxvY2FsZSI7czoyOiJybyI7czo2OiJfdG9rZW4iO3M6NDA6Ijc1cTdSVzROR1RHdjQwYjlzdUY0SGFoQzdlbktBSjhsNElPbHFlNEkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL2NhcnBoYXRpYW4ucm8vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766492302);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_key_group_index` (`key`,`group`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `description`, `is_public`, `created_at`, `updated_at`) VALUES (1,'email','info@carphatian.ro','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(2,'phone','+40774077860','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(3,'address','Str Ploiesti 47-49','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(4,'city','Cluj-Napoca','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(5,'state','Cluj','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(6,'country','Romania','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_methods`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$',
  `currency_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before',
  `tax_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `shipping_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `payment_gateways` json DEFAULT NULL,
  `terms_and_conditions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `privacy_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `return_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ORD-',
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
INSERT INTO `shop_settings` (`id`, `currency`, `currency_symbol`, `currency_position`, `tax_enabled`, `tax_rate`, `shipping_enabled`, `payment_gateways`, `terms_and_conditions`, `privacy_policy`, `return_policy`, `order_prefix`, `inventory_management`, `low_stock_alert`, `low_stock_threshold`, `created_at`, `updated_at`) VALUES (1,'USD','$','before',0,0.00,0,'{\"stripe\": {\"enabled\": false, \"public_key\": \"\", \"secret_key\": \"\"}, \"paypal_api\": {\"secret\": \"\", \"enabled\": false, \"client_id\": \"\"}, \"bank_transfer\": {\"iban\": \"\", \"enabled\": false, \"bank_name\": \"\", \"bic_swift\": \"\", \"account_name\": \"\", \"instructions\": \"\"}, \"paypal_classic\": {\"email\": \"\", \"enabled\": false}}',NULL,NULL,NULL,'ORD-',1,1,5,'2025-12-07 04:49:18','2025-12-07 04:49:18');
/*!40000 ALTER TABLE `shop_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_health_checks`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_health_checks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `check_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('healthy','warning','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'healthy',
  `metrics` json DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
INSERT INTO `system_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES (1,'auto_updates','disabled','string','Automatic update mode: disabled, minor, all','2025-12-07 04:49:18','2025-12-07 04:49:18'),(2,'update_server','https://updates.carphatian.ro','url','Update server URL','2025-12-07 04:49:18','2025-12-07 04:49:18'),(3,'backup_retention_days','30','integer','Number of days to keep backups','2025-12-07 04:49:18','2025-12-07 04:49:18');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_blocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `css` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `js` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0.0',
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `css` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `js` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` (`id`, `name`, `slug`, `description`, `thumbnail`, `version`, `author`, `author_url`, `css`, `js`, `config`, `layouts`, `is_active`, `is_default`, `ai_generated`, `ai_generation_id`, `color_scheme`, `typography`, `custom_fields`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Carpathian Default','carpathian-default','Modern and professional template with purple accent colors, perfect for business and corporate websites.','/images/templates/carpathian-default.jpg','1.0.0','Carpathian CMS','https://carpathian.ro',NULL,NULL,'\"{\\\"supports_widgets\\\":true,\\\"supports_page_builder\\\":true,\\\"has_sidebar\\\":false,\\\"layout_type\\\":\\\"full-width\\\"}\"','\"[\\\"home\\\",\\\"page\\\",\\\"blog\\\",\\\"single-post\\\",\\\"contact\\\"]\"',0,1,0,NULL,'\"{\\\"primary\\\":\\\"#9333ea\\\",\\\"secondary\\\":\\\"#6b21a8\\\",\\\"accent\\\":\\\"#a855f7\\\",\\\"background\\\":\\\"#ffffff\\\",\\\"text\\\":\\\"#1f2937\\\"}\"','\"{\\\"font_family\\\":\\\"Inter, system-ui, sans-serif\\\",\\\"heading_font\\\":\\\"Inter, sans-serif\\\",\\\"body_font\\\":\\\"Inter, sans-serif\\\"}\"',NULL,'2025-12-07 05:44:47','2025-12-07 08:10:41',NULL),(2,'Ocean Blue','ocean-blue','Clean and modern template with calming blue colors, ideal for technology and service-based businesses.','/images/templates/ocean-blue.jpg','1.0.0','Carpathian CMS','https://carpathian.ro',NULL,NULL,'\"{\\\"supports_widgets\\\":true,\\\"supports_page_builder\\\":true,\\\"has_sidebar\\\":true,\\\"layout_type\\\":\\\"boxed\\\"}\"','\"[\\\"home\\\",\\\"page\\\",\\\"blog\\\",\\\"single-post\\\",\\\"contact\\\"]\"',0,0,0,NULL,'\"{\\\"primary\\\":\\\"#0ea5e9\\\",\\\"secondary\\\":\\\"#0284c7\\\",\\\"accent\\\":\\\"#38bdf8\\\",\\\"background\\\":\\\"#ffffff\\\",\\\"text\\\":\\\"#1e293b\\\"}\"','\"{\\\"font_family\\\":\\\"Poppins, system-ui, sans-serif\\\",\\\"heading_font\\\":\\\"Poppins, sans-serif\\\",\\\"body_font\\\":\\\"Poppins, sans-serif\\\"}\"',NULL,'2025-12-07 05:44:47','2025-12-07 06:36:52','2025-12-07 06:36:52'),(3,'TechWave Pro','techwave-pro','Modern IT Software Services theme with vibrant orange and professional blue color scheme. Features advanced button styles, gradient accents, and enterprise-ready design elements.',NULL,'1.0.0','Carpathian CMS','',NULL,NULL,'{\"layout\": \"full-width\", \"features\": {\"parallax\": true, \"dark_mode\": false, \"glassmorphism\": true, \"hover_effects\": true, \"rounded_corners\": true, \"gradient_buttons\": true, \"smooth_scrolling\": true, \"loading_animations\": true}, \"footer_style\": \"corporate\", \"header_style\": \"modern\", \"header_sticky\": true, \"widget_support\": true, \"sidebar_enabled\": false, \"sidebar_position\": \"right\", \"header_transparent\": false, \"responsive_breakpoints\": {\"wide\": \"1536px\", \"laptop\": \"1024px\", \"mobile\": \"640px\", \"tablet\": \"768px\", \"desktop\": \"1280px\"}}','[]',0,0,0,NULL,'{\"dark\": \"#1f2937\", \"info\": \"#3b82f6\", \"light\": \"#f9fafb\", \"accent\": \"#fbbf24\", \"border\": \"#e5e7eb\", \"danger\": \"#ef4444\", \"nav_bg\": \"#ffffff\", \"card_bg\": \"#ffffff\", \"overlay\": \"rgba(30, 64, 175, 0.95)\", \"primary\": \"#ff6b35\", \"success\": \"#10b981\", \"warning\": \"#f59e0b\", \"nav_text\": \"#111827\", \"footer_bg\": \"#1f2937\", \"nav_hover\": \"#ff6b35\", \"secondary\": \"#1e40af\", \"background\": \"#ffffff\", \"text_light\": \"#9ca3af\", \"card_shadow\": \"rgba(0, 0, 0, 0.1)\", \"footer_text\": \"#d1d5db\", \"border_focus\": \"#ff6b35\", \"gradient_end\": \"#1e40af\", \"text_primary\": \"#111827\", \"button_accent\": \"#fbbf24\", \"background_alt\": \"#f3f4f6\", \"button_primary\": \"#ff6b35\", \"gradient_start\": \"#ff6b35\", \"text_secondary\": \"#6b7280\", \"button_secondary\": \"#1e40af\", \"button_accent_hover\": \"#f59e0b\", \"button_primary_hover\": \"#ff5722\", \"button_secondary_hover\": \"#1e3a8a\"}','{\"font_size_lg\": \"18px\", \"font_size_sm\": \"14px\", \"font_size_xl\": \"20px\", \"font_size_2xl\": \"24px\", \"font_size_3xl\": \"30px\", \"font_size_4xl\": \"36px\", \"font_size_5xl\": \"48px\", \"font_size_6xl\": \"60px\", \"font_size_base\": \"16px\", \"font_family_mono\": \"\'Fira Code\', \'Consolas\', monospace\", \"font_weight_bold\": \"700\", \"font_weight_light\": \"300\", \"line_height_tight\": \"1.25\", \"font_weight_medium\": \"500\", \"font_weight_normal\": \"400\", \"line_height_normal\": \"1.5\", \"font_family_primary\": \"\'Inter\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif\", \"letter_spacing_wide\": \"0.025em\", \"line_height_relaxed\": \"1.75\", \"font_family_headings\": \"\'Poppins\', sans-serif\", \"font_weight_semibold\": \"600\", \"letter_spacing_tight\": \"-0.025em\", \"font_family_secondary\": \"\'Poppins\', sans-serif\", \"font_weight_extrabold\": \"800\", \"letter_spacing_normal\": \"0\"}',NULL,'2025-12-07 06:11:34','2025-12-07 06:36:49','2025-12-07 06:36:49'),(4,'ShopFlow Modern','shopflow-modern','Modern e-commerce template with product grids, shopping cart focus, and conversion-optimized layouts. Perfect for online stores.',NULL,'1.0.0','Carpathian CMS','',NULL,NULL,'{\"layout\": \"full-width\", \"features\": [\"Product Grids\", \"Shopping Cart\", \"Wishlist\", \"Reviews\"], \"industry\": \"E-commerce\", \"header_style\": \"modern\"}','[]',0,0,0,NULL,'{\"dark\": \"#1d3557\", \"info\": \"#3b82f6\", \"light\": \"#f8f9fa\", \"accent\": \"#f1faee\", \"border\": \"#e5e7eb\", \"danger\": \"#ef4444\", \"nav_bg\": \"#ffffff\", \"primary\": \"#e63946\", \"success\": \"#10b981\", \"warning\": \"#f59e0b\", \"footer_bg\": \"#1d3557\", \"secondary\": \"#457b9d\", \"background\": \"#f8f9fa\", \"text_primary\": \"#111827\", \"button_primary\": \"#e63946\", \"text_secondary\": \"#6b7280\", \"button_secondary\": \"#457b9d\", \"button_primary_hover\": \"#cf333f\", \"button_secondary_hover\": \"#3e6e8d\"}','{\"font_size_base\": \"16px\", \"font_weight_bold\": \"700\", \"font_weight_normal\": \"400\", \"line_height_normal\": \"1.5\", \"font_family_primary\": \"\'Roboto\', sans-serif\", \"font_family_headings\": \"\'Roboto\', sans-serif\", \"font_family_secondary\": \"\'Montserrat\', sans-serif\"}',NULL,'2025-12-07 06:31:23','2025-12-07 06:36:32','2025-12-07 06:36:32'),(5,'RetailPro Elite','retailpro-elite','Premium retail template with advanced filtering, mega menus, and luxury brand aesthetics.',NULL,'1.0.0','Carpathian CMS','',NULL,NULL,'{\"layout\": \"full-width\", \"features\": [\"Mega Menu\", \"Advanced Filters\", \"Luxury Design\", \"VIP Section\"], \"industry\": \"E-commerce\", \"header_style\": \"modern\"}','[]',1,0,0,NULL,'{\"dark\": \"#000000\", \"info\": \"#3b82f6\", \"light\": \"#f5f5f5\", \"accent\": \"#ffffff\", \"border\": \"#e5e7eb\", \"danger\": \"#ef4444\", \"nav_bg\": \"#ffffff\", \"primary\": \"#2d2d2d\", \"success\": \"#10b981\", \"warning\": \"#f59e0b\", \"footer_bg\": \"#000000\", \"secondary\": \"#d4af37\", \"background\": \"#f5f5f5\", \"text_primary\": \"#111827\", \"button_primary\": \"#2d2d2d\", \"text_secondary\": \"#6b7280\", \"button_secondary\": \"#d4af37\", \"button_primary_hover\": \"#282828\", \"button_secondary_hover\": \"#be9d31\"}','{\"font_size_base\": \"16px\", \"font_weight_bold\": \"700\", \"font_weight_normal\": \"400\", \"line_height_normal\": \"1.5\", \"font_family_primary\": \"\'Playfair Display\', sans-serif\", \"font_family_headings\": \"\'Playfair Display\', sans-serif\", \"font_family_secondary\": \"\'Lato\', sans-serif\"}',NULL,'2025-12-07 06:33:14','2025-12-07 06:36:29','2025-12-07 06:36:29'),(6,'Codecraft Pro','codecraft-pro','dark mode code blocks',NULL,'2.0.0','Unknown','',NULL,NULL,'{\"responsive\": true}','[\"home\", \"about\", \"contact\"]',1,0,0,NULL,'{\"primary\": \"#00d9ff\", \"secondary\": \"#8b5cf6\"}','{\"body_font\": \"Inter\", \"heading_font\": \"Space Grotesk\"}',NULL,'2025-12-07 08:09:55','2025-12-07 08:10:41',NULL);
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant_users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenant_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `database` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `update_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `package_slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plugin',
  `from_version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `changelog` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('success','failed','rolled_back') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `updated_by` bigint unsigned DEFAULT NULL,
  `backup_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `user_groups`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `permissions` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_groups_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` (`id`, `name`, `slug`, `description`, `permissions`, `is_active`, `created_at`, `updated_at`) VALUES (1,'Administrators','administrators','Full access to all features','[\"*\"]',1,'2025-12-21 13:16:40','2025-12-21 13:16:40'),(2,'Editors','editors','Can manage content and blog posts','[\"pages.view\", \"pages.create\", \"pages.edit\", \"posts.view\", \"posts.create\", \"posts.edit\", \"media.view\", \"media.upload\"]',1,'2025-12-21 13:16:40','2025-12-21 13:16:40'),(3,'Shop Managers','shop-managers','Can manage products and orders','[\"products.view\", \"products.create\", \"products.edit\", \"orders.view\", \"orders.manage\"]',1,'2025-12-21 13:16:40','2025-12-21 13:16:40'),(4,'Support Agents','support-agents','Can respond to support requests','[\"support.view\", \"support.respond\"]',1,'2025-12-21 13:16:40','2025-12-21 13:16:40');
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_sessions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `user_user_group`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_user_group` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `user_group_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_user_group_user_id_user_group_id_unique` (`user_id`,`user_group_id`),
  KEY `user_user_group_user_group_id_foreign` (`user_group_id`),
  CONSTRAINT `user_user_group_user_group_id_foreign` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_user_group_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_user_group`
--

LOCK TABLES `user_user_group` WRITE;
/*!40000 ALTER TABLE `user_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_reg_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Romania',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_tenant_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_created_at_index` (`created_at`),
  KEY `users_current_tenant_id_foreign` (`current_tenant_id`),
  CONSTRAINT `users_current_tenant_id_foreign` FOREIGN KEY (`current_tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `company_name`, `company_reg_number`, `vat_number`, `billing_address`, `billing_city`, `billing_state`, `billing_postal_code`, `billing_country`, `phone`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `current_tenant_id`) VALUES (1,'Admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Romania',NULL,'msrusu87@gmail.com','2025-12-21 12:36:33','$2y$12$m6k4MkS0ncVwOyIxVZ7x8.SWUBwDuv3wEGzUiKn6Wq5wY.PW1tVIO',NULL,NULL,NULL,'T66PN26AfhGcLosmRDQuOWzmyaeuHmP1TU1WdIolDlZEuSzmnVBYs05zNk3k','2025-12-07 04:53:54','2025-12-22 10:43:38',NULL),(2,'Mircea silviu Rusu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Romania',NULL,'ukmircearusu@gmail.com','2025-12-21 14:02:34','$2y$12$lG1x/h3vT8nMC61QPkKUTeCttCTM/HoYzQKh0ZgJHFiuU/wpnqWIq',NULL,NULL,NULL,NULL,'2025-12-21 13:40:32','2025-12-21 14:02:34',NULL),(3,'Test User',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Romania',NULL,'testuser@carphatian.test',NULL,'$2y$12$69w2DU/S.PY1RhhlpdlIcu5oM9ulgMg39z97b7cqNXkKUgsU11iQy',NULL,NULL,NULL,NULL,'2025-12-21 14:20:11','2025-12-21 14:20:11',NULL),(4,'Mircea silviu Rusu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Romania',NULL,'mirceasilviurusu@gmail.com','2025-12-21 20:42:11','$2y$12$ILyqyX7bOUmfIgIPj.E98OUmUVRk2oLsZD3Ie/KTjqdjjIQjJ.PLG',NULL,NULL,NULL,'hg9cI5L0rBJxZo4NUwdb3C9HsmaxAhbDrFPWJl004ehwGxOqKUZGFCGQLDF6','2025-12-21 20:41:59','2025-12-21 20:42:11',NULL),(5,'Test Buyer',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Romania',NULL,'cococatelul@gmail.com','2025-12-22 04:13:55','$2y$12$uTIm5GuumuVXanYjcqsnmOI.Pd1T5m9taXbtqE7k34c2V7Kmp1ewO',NULL,NULL,NULL,NULL,'2025-12-22 04:13:03','2025-12-22 04:13:55',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webhook_deliveries`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webhook_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `webhook_id` bigint unsigned NOT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json NOT NULL,
  `status_code` int DEFAULT NULL,
  `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','delivered','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webhooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `events` json NOT NULL,
  `secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `websocket_connections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `socket_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` json DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
INSERT INTO `widgets` (`id`, `title`, `type`, `content`, `settings`, `order`, `status`, `created_at`, `updated_at`) VALUES (1,'{\"en\": \"Hero Section\"}','hero','{\"en\": \"{\\\"heading\\\":\\\"Bine ai venit la Web Agency CMS\\\",\\\"subheading\\\":\\\"CMS profesional cu integrare AI\\\",\\\"description\\\":\\\"Platforma ta complet\\\\u0103 pentru crearea \\\\u0219i gestionarea site-urilor web moderne. Instrumente puternice, design responsive \\\\u0219i integrare AI.\\\",\\\"button_text\\\":\\\"\\\\u00cencepe Acum\\\",\\\"button_url\\\":\\\"\\\\/contact\\\",\\\"background_image\\\":\\\"\\\\/images\\\\/hero-bg.jpg\\\"}\"}','\"{\\\"enabled\\\":true}\"',1,'active','2025-12-07 05:11:38','2025-12-07 05:11:38'),(2,'{\"en\": \"Features Section\"}','features','{\"en\": \"{\\\"heading\\\":\\\"Funcționalități Puternice\\\",\\\"features\\\":[{\\\"icon\\\":\\\"🎨\\\",\\\"title\\\":\\\"Design Modern\\\",\\\"description\\\":\\\"Interfață intuitivă și design responsive pentru toate dispozitivele\\\",\\\"link\\\":\\\"\\\\/posts\\\\/design-modern-ghid-complet-interfete-intuitive\\\"},{\\\"icon\\\":\\\"⚡\\\",\\\"title\\\":\\\"Performanță Ridicată\\\",\\\"description\\\":\\\"Optimizat pentru viteză și performanță maximă\\\",\\\"link\\\":\\\"\\\\/posts\\\\/performanta-ridicata-optimizare-viteza-maxima\\\"},{\\\"icon\\\":\\\"🤖\\\",\\\"title\\\":\\\"Integrare AI\\\",\\\"description\\\":\\\"Utilizează inteligența artificială pentru automatizare\\\",\\\"link\\\":\\\"\\\\/posts\\\\/integrare-ai-inteligenta-artificiala-cms\\\"},{\\\"icon\\\":\\\"🔒\\\",\\\"title\\\":\\\"Securitate\\\",\\\"description\\\":\\\"Protecție avansată și backup automat\\\",\\\"link\\\":\\\"\\\\/posts\\\\/securitate-avansata-protectie-date-cms\\\"},{\\\"icon\\\":\\\"📱\\\",\\\"title\\\":\\\"Multi-Platform\\\",\\\"description\\\":\\\"Funcționează perfect pe desktop, tabletă și mobil\\\",\\\"link\\\":\\\"\\\\/posts\\\\/multi-platform-functionare-orice-dispozitiv\\\"},{\\\"icon\\\":\\\"🔧\\\",\\\"title\\\":\\\"Personalizabil\\\",\\\"description\\\":\\\"Configurează totul conform nevoilor tale\\\",\\\"link\\\":\\\"\\\\/posts\\\\/personalizare-completa-configurare-cms\\\"}]}\"}','\"{\\\"enabled\\\":true}\"',2,'active','2025-12-07 05:11:38','2025-12-08 07:26:19'),(3,'{\"en\": \"Products Section\"}','products','{\"en\": \"{\\\"heading\\\":\\\"Products Showcase\\\",\\\"subheading\\\":\\\"Descoper\\\\u0103 solu\\\\u021biile noastre\\\",\\\"limit\\\":6}\"}','\"{\\\"enabled\\\":true}\"',3,'active','2025-12-07 05:11:38','2025-12-07 05:11:38'),(4,'{\"en\": \"Blog Section\"}','blog','{\"en\": \"{\\\"heading\\\":\\\"Ultimele Articole\\\",\\\"subheading\\\":\\\"R\\\\u0103m\\\\u00e2i la curent cu cele mai noi articole\\\",\\\"limit\\\":3}\"}','\"{\\\"enabled\\\":true}\"',4,'active','2025-12-07 05:11:38','2025-12-07 05:11:38'),(5,'{\"en\": \"Footer Section\"}','footer','{\"en\": \"{\\\"company_name\\\":\\\"Carphatian Web Agency\\\",\\\"description\\\":\\\"CMS profesional pentru afaceri moderne\\\",\\\"copyright\\\":\\\"\\\\u00a9 2025 Carphatian. Toate drepturile rezervate.\\\",\\\"social_links\\\":[{\\\"platform\\\":\\\"facebook\\\",\\\"url\\\":\\\"https:\\\\/\\\\/facebook.com\\\"},{\\\"platform\\\":\\\"twitter\\\",\\\"url\\\":\\\"https:\\\\/\\\\/twitter.com\\\"},{\\\"platform\\\":\\\"linkedin\\\",\\\"url\\\":\\\"https:\\\\/\\\\/linkedin.com\\\"},{\\\"platform\\\":\\\"instagram\\\",\\\"url\\\":\\\"https:\\\\/\\\\/instagram.com\\\"}]}\"}','\"{\\\"enabled\\\":true}\"',5,'active','2025-12-07 05:11:38','2025-12-07 05:11:38');
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdrawal_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_details` json DEFAULT NULL,
  `status` enum('pending','processing','completed','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow_executions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workflow_id` bigint unsigned NOT NULL,
  `status` enum('running','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running',
  `context` json DEFAULT NULL,
  `results` json DEFAULT NULL,
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trigger_type` enum('schedule','event','webhook') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Dumping events for database 'carphatian_cms'
--

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

-- Dump completed on 2025-12-23 14:26:10
