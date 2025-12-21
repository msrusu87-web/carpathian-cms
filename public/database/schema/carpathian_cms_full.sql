mysqldump: [Warning] Using a password on the command line interface can be insecure.
-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: modularcms
-- ------------------------------------------------------
-- Server version	8.0.44-0ubuntu0.24.04.1

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
mysqldump: Error: 'Access denied; you need (at least one of) the PROCESS privilege(s) for this operation' when trying to dump tablespaces

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_generations`
--

LOCK TABLES `ai_generations` WRITE;
/*!40000 ALTER TABLE `ai_generations` DISABLE KEYS */;
INSERT INTO `ai_generations` VALUES (1,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>Upon analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Query the database to retrieve the list of 6 products with invalid category references.</li>\n  <li>Verify the product IDs and corresponding category references to confirm the issue.</li>\n</ul>\n\n<h2>Step 2: Update or Remove Invalid References</h2>\n<ul>\n  <li>For each affected product, update the category reference to a valid one, if possible.</li>\n  <li>If a valid category reference cannot be found, remove the invalid reference to prevent data inconsistencies.</li>\n</ul>\n\n<h2>Step 3: Validate Category References</h2>\n<ul>\n  <li>Run a database query to validate all category references for the affected products.</li>\n  <li>Confirm that each product is associated with a valid category reference.</li>\n</ul>\n\n<h2>Step 4: Test and Verify</h2>\n<ul>\n  <li>Perform thorough testing to ensure that the repairs have resolved the issue.</li>\n  <li>Verify that the affected products are now correctly associated with valid category references.</li>\n</ul>\n\n<p>By following these repair steps, you should be able to resolve the invalid category references and maintain the integrity of your database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,411,1213,'2025-12-09 09:08:17','2025-12-09 09:08:19'),(2,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h2>Database Repair Recommendations: Invalid Category References</h2>\n\n<p>Upon analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h3>Step 1: Identify Affected Products</h3>\n<ul>\n  <li>Run a database query to retrieve the list of 6 products with invalid category references.</li>\n  <li>Verify the product IDs and corresponding invalid category IDs.</li>\n</ul>\n\n<h3>Step 2: Update or Remove Invalid References</h3>\n<ul>\n  <li>Update the category IDs for the affected products to valid references, if possible.</li>\n  <li>If the category IDs are no longer valid, remove the references and set the category ID to a default or null value.</li>\n</ul>\n\n<h3>Step 3: Validate Category IDs</h3>\n<ul>\n  <li>Run a database query to validate that all category IDs referenced by products exist in the categories table.</li>\n  <li>Use a JOIN or subquery to identify and remove any remaining invalid category references.</li>\n</ul>\n\n<h3>Step 4: Implement Preventative Measures</h3>\n<ul>\n  <li>Review and update the database schema to include foreign key constraints, if not already implemented.</li>\n  <li>Establish data validation rules to prevent future insertion of invalid category references.</li>\n</ul>\n\n<p>By following these repair steps, you can resolve the issue of invalid category references and maintain a healthy and consistent database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,425,1663,'2025-12-09 09:08:31','2025-12-09 09:08:32'),(3,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>After analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, we recommend the following repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Run a query to retrieve the IDs and names of the 6 products with invalid category references.</li>\n  <li>Use the following SQL query: <code>SELECT product_id, product_name FROM products WHERE category_id NOT IN (SELECT category_id FROM categories);</code></li>\n</ul>\n\n<h2>Step 2: Update or Remove Invalid References</h2>\n<ul>\n  <li>For each affected product, update the category_id to a valid reference or remove the invalid reference if it is no longer applicable.</li>\n  <li>Use the following SQL query to update the category_id: <code>UPDATE products SET category_id = [new_category_id] WHERE product_id = [product_id];</code></li>\n  <li>Alternatively, use the following SQL query to remove the invalid reference: <code>UPDATE products SET category_id = NULL WHERE product_id = [product_id];</code></li>\n</ul>\n\n<h2>Step 3: Validate Category References</h2>\n<ul>\n  <li>Run a query to validate that all product category references are valid and exist in the categories table.</li>\n  <li>Use the following SQL query: <code>SELECT * FROM products WHERE category_id NOT IN (SELECT category_id FROM categories);</code></li>\n  <li>Verify that the query returns no results, indicating that all category references are valid.</li>\n</ul>\n\n<h2>Conclusion</h2>\n<p>By following these repair steps, you can resolve the issue of invalid category references and maintain the integrity of your database. Regularly validating data references can help prevent similar issues in the future.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,505,1592,'2025-12-09 09:30:00','2025-12-09 09:30:01'),(4,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h2>Database Repair Recommendations: Invalid Category References</h2>\n\n<p>We\'ve identified 6 products with invalid category references in the database. To resolve this issue and maintain data integrity, follow these concise repair steps:</p>\n\n<ol>\n  <li><strong>Identify Affected Products</strong>: Retrieve a list of the 6 products with invalid category references. You can use a SQL query like:\n    <pre>SELECT * FROM products WHERE category_id NOT IN (SELECT id FROM categories);</pre>\n  </li>\n  <li><strong>Verify Category Existence</strong>: Check if the referenced categories exist in the <code>categories</code> table. If a category is missing, create a new entry or update the product to reference a valid category.</li>\n  <li><strong>Update or Remove Invalid References</strong>: For each affected product, either:\n    <ul>\n      <li>Update the <code>category_id</code> to a valid category reference using a SQL query like:\n        <pre>UPDATE products SET category_id = [new_category_id] WHERE id = [product_id];</pre>\n      </li>\n      <li>Remove the invalid category reference by setting <code>category_id</code> to <code>NULL</code> or a default value:\n        <pre>UPDATE products SET category_id = NULL WHERE id = [product_id];</pre>\n      </li>\n    </ul>\n  </li>\n  <li><strong>Validate Data Integrity</strong>: Run a SQL query to verify that all products have valid category references:\n    <pre>SELECT * FROM products WHERE category_id NOT IN (SELECT id FROM categories);</pre>\n    If this query returns no results, the issue is resolved.\n  </li>\n</ol>\n\n<p>By following these repair steps, you\'ll ensure data consistency and prevent potential errors in your application.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,485,1564,'2025-12-09 09:30:29','2025-12-09 09:30:30'),(5,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>After analyzing the database issues, we have identified 6 products with invalid category references. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Run a query to retrieve the list of 6 products with invalid category references.</li>\n  <li>Record the product IDs and corresponding invalid category IDs for further analysis.</li>\n</ul>\n\n<h2>Step 2: Validate Category References</h2>\n<ul>\n  <li>Verify the existence of each invalid category ID in the categories table.</li>\n  <li>Check for any typos, duplicates, or deleted categories that may be causing the invalid references.</li>\n</ul>\n\n<h2>Step 3: Update or Remove Invalid References</h2>\n<ul>\n  <li>Update the product records with valid category IDs, if available.</li>\n  <li>Remove any invalid category references that are not resolvable.</li>\n  <li>Consider adding a default or unknown category for products without valid references.</li>\n</ul>\n\n<h2>Step 4: Implement Data Validation</h2>\n<ul>\n  <li>Modify the database schema to include foreign key constraints between the products and categories tables.</li>\n  <li>Implement data validation rules to prevent future insertion of invalid category references.</li>\n</ul>\n\n<h2>Step 5: Verify and Test</h2>\n<ul>\n  <li>Run a query to verify that all products have valid category references.</li>\n  <li>Test the database by inserting new products with valid and invalid category references to ensure the data validation rules are working correctly.</li>\n</ul>\n\n<p>By following these repair steps, you can resolve the invalid category references and maintain a clean and accurate database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,491,1368,'2025-12-09 09:42:39','2025-12-09 09:42:41'),(6,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h1>Database Repair Recommendations: Invalid Category References</h1>\n\n<p>We\'ve identified 6 products with invalid category references in the database. To resolve this issue and maintain data integrity, follow these concise and actionable repair steps:</p>\n\n<h2>Step 1: Identify Affected Products</h2>\n<ul>\n  <li>Run a query to retrieve the 6 products with invalid category references.</li>\n  <li>Record the product IDs and corresponding invalid category IDs for further analysis.</li>\n</ul>\n\n<h2>Step 2: Update or Remove Invalid References</h2>\n<ul>\n  <li>For each affected product, update the category ID to a valid reference or remove the invalid reference if it\'s no longer applicable.</li>\n  <li>Use the following SQL command to update the category ID: <code>UPDATE products SET category_id = [new_valid_category_id] WHERE product_id = [affected_product_id];</code></li>\n  <li>Alternatively, use the following SQL command to remove the invalid reference: <code>UPDATE products SET category_id = NULL WHERE product_id = [affected_product_id];</code></li>\n</ul>\n\n<h2>Step 3: Verify Data Integrity</h2>\n<ul>\n  <li>Run a query to verify that the invalid category references have been resolved.</li>\n  <li>Check for any other data inconsistencies or errors that may have arisen during the repair process.</li>\n</ul>\n\n<h2>Preventative Measures</h2>\n<ul>\n  <li>Implement data validation rules to prevent invalid category references from being inserted in the future.</li>\n  <li>Regularly run data integrity checks to identify and address any potential issues before they become critical.</li>\n</ul>\n\n<p>By following these repair steps, you\'ll be able to resolve the invalid category references and maintain the integrity of your database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,484,1394,'2025-12-09 09:43:50','2025-12-09 09:43:52'),(7,'content','As a database expert, analyze these database issues and provide repair recommendations:\n\nIssues found:\nFound 6 product(s) with invalid category references\n\nProvide concise, actionable repair steps.','<h2>Database Repair Recommendations</h2>\n\n<p>To address the issue of invalid category references for 6 products, follow these concise and actionable repair steps:</p>\n\n<h3>Step 1: Identify Affected Products</h3>\n<ul>\n  <li>Run a query to retrieve the 6 products with invalid category references, using the following SQL command: <code>SELECT * FROM products WHERE category_id NOT IN (SELECT id FROM categories)</code></li>\n  <li>Record the product IDs and their corresponding invalid category IDs for further analysis</li>\n</ul>\n\n<h3>Step 2: Validate Category References</h3>\n<ul>\n  <li>Verify the existence of the referenced categories in the <code>categories</code> table</li>\n  <li>Check for any typographical errors or inconsistencies in the category IDs</li>\n</ul>\n\n<h3>Step 3: Update or Remove Invalid References</h3>\n<ul>\n  <li>For each affected product, update the <code>category_id</code> to a valid category ID using the following SQL command: <code>UPDATE products SET category_id = [new_valid_category_id] WHERE id = [product_id]</code></li>\n  <li>If a valid category ID cannot be determined, consider removing the product or setting its <code>category_id</code> to a default or \"unknown\" category</li>\n</ul>\n\n<h3>Step 4: Verify Repaired Data</h3>\n<ul>\n  <li>Run a query to re-check the products for invalid category references, using the same SQL command from Step 1</li>\n  <li>Verify that all 6 products have been successfully updated with valid category references</li>\n</ul>\n\n<p>By following these repair steps, you should be able to resolve the issue of invalid category references for the 6 affected products and maintain data consistency in your database.</p>',NULL,'llama-3.3-70b-versatile',1,'completed',NULL,491,1632,'2025-12-09 09:44:44','2025-12-09 09:44:46');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'{\"ro\":\"Știri\"}','stiri','{\"ro\":\"Ultimele știri și actualizări\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56',NULL),(2,'{\"ro\":\"Tutoriale CMS\",\"en\":\"CMS Tutorials\"}','tutoriale-cms','{\"ro\":\"Tutoriale CMS\",\"en\":\"CMS Tutorials\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-08 07:17:43','2025-12-08 07:17:43',NULL),(3,'{\"ro\":\"Inteligență Artificială\",\"en\":\"Artificial Intelligence\"}','inteligenta-artificiala','{\"ro\":\"Inteligență Artificială\",\"en\":\"Artificial Intelligence\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-08 07:17:43','2025-12-08 07:17:43',NULL),(4,'{\"ro\":\"Tehnologie & Web\",\"en\":\"Technology & Web\"}','tehnologie-web','{\"ro\":\"Tehnologie & Web\",\"en\":\"Technology & Web\"}',NULL,NULL,NULL,0,1,NULL,'2025-12-08 07:17:43','2025-12-08 07:17:43',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Mircea Rusu','msrusu87@gmail.com',NULL,'Hi, I need to update my phone number with a new one','test test','new',NULL,'2025-12-09 11:02:19','2025-12-09 11:02:19');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_settings`
--

LOCK TABLES `contact_settings` WRITE;
/*!40000 ALTER TABLE `contact_settings` DISABLE KEYS */;
INSERT INTO `contact_settings` VALUES (1,'Carphatian by Aziz Ride Sharing SRL','info@carphatian.ro','+40774077860','Str Ploiesti 47-49','Cluj-Napoca','Cluj',NULL,'Romania',NULL,NULL,NULL,NULL,NULL,NULL,1,'2025-12-07 05:03:34','2025-12-09 11:04:03'),(2,'Web Agency','info@carphatian.ro','+40774077860','Str Ploiesti 47-49','Cluj-Napoca','Cluj',NULL,'Romania',NULL,NULL,NULL,NULL,NULL,NULL,1,'2025-12-09 11:09:58','2025-12-09 11:09:58');
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
INSERT INTO `global_settings` VALUES (1,'Web Agency CMS','cms.carphatian.ro',NULL,NULL,'admin@example.com','Professional web development and digital solutions','UTC','Y-m-d','H:i:s',0,NULL,NULL,NULL,'2025-12-07 04:49:18','2025-12-07 04:49:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'Română','ro','ro_RO','ltr',1,1,'2025-12-07 05:30:48','2025-12-07 05:38:58'),(2,'English','en','en_US','ltr',0,0,'2025-12-07 05:30:48','2025-12-07 05:40:53');
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (21,5,NULL,'Freelancer','/freelancer','custom',NULL,'_self','briefcase',NULL,0,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(22,5,NULL,'Găsește Freelanceri','/freelancer/profiles','custom',NULL,'_self','users',NULL,1,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(23,5,NULL,'Servicii','/freelancer/gigs','custom',NULL,'_self','star',NULL,2,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(24,5,NULL,'Comenzi','/freelancer/orders','custom',NULL,'_self','shopping-cart',NULL,3,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(25,5,NULL,'Devino Freelancer','/freelancer/become','custom',NULL,'_self','rocket',NULL,4,1,'2025-12-07 05:15:02','2025-12-07 05:15:02'),(26,6,NULL,'Shop','/shop','custom',NULL,'_self','shopping-bag',NULL,0,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(27,6,NULL,'Products','/shop/products','custom',NULL,'_self','cube',NULL,1,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(28,6,NULL,'Categories','/shop/categories','custom',NULL,'_self','folder',NULL,2,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(29,6,NULL,'Cart','/cart','custom',NULL,'_self','shopping-cart',NULL,3,1,'2025-12-07 05:15:25','2025-12-07 05:15:25'),(30,6,NULL,'Checkout','/checkout','custom',NULL,'_self','credit-card',NULL,4,1,'2025-12-07 05:15:25','2025-12-07 05:15:25');
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
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `menus` VALUES (1,'Freelancer','freelancer-menu','header',NULL,1,0,'2025-12-07 04:50:25','2025-12-07 04:57:27','2025-12-07 04:57:27'),(2,'Shop','shop-menu','header',NULL,1,0,'2025-12-07 04:55:54','2025-12-07 04:58:08','2025-12-07 04:58:08'),(3,'Freelancer','freelancer-menu','header',NULL,1,0,'2025-12-07 04:57:51','2025-12-07 05:14:55','2025-12-07 05:14:55'),(4,'Shop','shop-menu','header',NULL,1,0,'2025-12-07 04:58:30','2025-12-07 05:14:58','2025-12-07 05:14:58'),(5,'Freelancer','freelancer-menu','header',NULL,0,0,'2025-12-07 05:15:02','2025-12-08 05:59:10',NULL),(6,'Magazin','shop-menu','header',NULL,1,0,'2025-12-07 05:15:25','2025-12-08 05:58:45',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_03_102526_create_ai_generations_table',1),(5,'2025_12_03_102527_b_create_templates_table',1),(6,'2025_12_03_102527_create_tags_table',1),(7,'2025_12_03_102528_create_categories_table',1),(8,'2025_12_03_102528_create_posts_table',1),(9,'2025_12_03_102529_create_media_table',1),(10,'2025_12_03_102529_create_pages_table',1),(11,'2025_12_03_102535_create_plugins_table',1),(12,'2025_12_03_102536_create_settings_table',1),(13,'2025_12_03_102536_create_template_blocks_table',1),(14,'2025_12_03_105000_create_template_zones_system',1),(15,'2025_12_03_110035_create_ecommerce_tables',1),(16,'2025_12_03_120008_create_contact_settings_table',1),(17,'2025_12_03_121906_create_menus_table',1),(18,'2025_12_03_121907_create_menu_items_table',1),(19,'2025_12_03_124605_create_ai_settings_table',1),(20,'2025_12_03_125000_create_global_settings_table',1),(21,'2025_12_03_125100_create_shop_settings_table',1),(22,'2025_12_03_125200_create_seo_settings_table',1),(23,'2025_12_03_141920_create_widgets_table',1),(24,'2025_12_05_022806_create_installed_packages_table',1),(25,'2025_12_05_022806_create_system_settings_table',1),(26,'2025_12_05_022806_create_update_history_table',1),(27,'2025_12_05_032249_create_contact_messages_table',1),(28,'2025_12_05_035427_convert_content_to_json',1),(29,'2025_12_06_040541_create_permission_tables',1),(30,'2025_12_06_040541_create_personal_access_tokens_table',1),(31,'2025_12_06_040543_add_two_factor_columns_to_users_table',1),(32,'2025_12_06_100356_create_freelancer_marketplace_tables',2),(33,'2025_12_07_045111_create_redirects_table',3),(34,'2025_12_06_041545_create_page_builder_blocks_table',4),(35,'2025_12_06_042504_add_performance_indexes_to_tables',4),(36,'2025_12_06_085129_create_redirects_table',5),(37,'2025_12_06_100619_enhance_products_for_ecommerce',5),(38,'2025_12_06_101159_create_custom_post_types_system',5),(39,'2025_12_06_101159_create_media_library_enhancements',5),(40,'2025_12_06_101159_create_multilanguage_system',5),(41,'2025_12_06_101200_create_compliance_privacy_features',5),(42,'2025_12_06_101200_create_custom_post_types_system',5),(43,'2025_12_06_101200_create_pwa_features',5),(44,'2025_12_06_101203_create_compliance_privacy_features',5),(45,'2025_12_06_101300_create_seo_analytics_tables',5),(46,'2025_12_06_101400_create_notification_system',5),(47,'2025_12_06_101401_create_workflow_automation',5),(48,'2025_12_06_101402_create_form_builder_system',5),(49,'2025_12_06_101403_create_backup_system',5),(50,'2025_12_06_101500_create_api_system',5),(51,'2025_12_06_101601_create_advanced_search',5),(52,'2025_12_06_101700_create_final_enterprise_features',5),(53,'2025_12_06_101800_create_multitenancy_system',5),(54,'2025_12_06_101900_create_system_monitoring',5),(55,'2025_12_06_102000_create_advanced_admin_features',5),(56,'2025_12_07_040848_create_missing_tables',5),(57,'2025_12_07_041428_create_page_builder_templates_table',6),(58,'2025_12_07_044850_add_slug_to_menus_table',7);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'{\"ro\":\"Home\"}','home','{\"ro\":\"{\\\"blocks\\\":[{\\\"type\\\":\\\"hero\\\",\\\"data\\\":{\\\"title\\\":\\\"Welcome to Carpathian CMS\\\",\\\"subtitle\\\":\\\"A powerful modular content management system\\\",\\\"buttonText\\\":\\\"Get Started\\\",\\\"buttonUrl\\\":\\\"\\\\/contact\\\"}}]}\"}',NULL,NULL,'published',1,NULL,'Carpathian CMS - Modular Content Management System','Welcome to Carpathian CMS',NULL,0,1,1,NULL,NULL,NULL,'2025-12-07 05:01:16','2025-12-07 05:06:33','2025-12-07 05:06:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugins`
--

LOCK TABLES `plugins` WRITE;
/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;
INSERT INTO `plugins` VALUES (1,'Freelancer Marketplace','freelancer','Complete freelancing marketplace','1.0.0','Carphatian CMS',NULL,NULL,'{\"frontend_menus\": [{\"name\": \"Freelancer\", \"slug\": \"freelancer-menu\", \"items\": [{\"url\": \"/freelancer\", \"icon\": \"briefcase\", \"title\": \"Freelancer\"}, {\"url\": \"/freelancer/profiles\", \"icon\": \"users\", \"title\": \"Găsește Freelanceri\"}, {\"url\": \"/freelancer/gigs\", \"icon\": \"star\", \"title\": \"Servicii\"}, {\"url\": \"/freelancer/orders\", \"icon\": \"shopping-cart\", \"title\": \"Comenzi\"}, {\"url\": \"/freelancer/become\", \"icon\": \"rocket\", \"title\": \"Devino Freelancer\"}], \"location\": \"header\"}]}',NULL,0,0,NULL,NULL,NULL,'2025-12-07 04:50:18','2025-12-08 05:59:10',NULL),(2,'E-commerce','ecommerce','Complete e-commerce solution with products, orders, cart, checkout, and payments','1.0.0','Carphatian CMS',NULL,NULL,'{\"frontend_menus\": [{\"name\": \"Shop\", \"slug\": \"shop-menu\", \"items\": [{\"url\": \"/shop\", \"icon\": \"shopping-bag\", \"title\": \"Shop\"}, {\"url\": \"/shop/products\", \"icon\": \"cube\", \"title\": \"Products\"}, {\"url\": \"/shop/categories\", \"icon\": \"folder\", \"title\": \"Categories\"}, {\"url\": \"/cart\", \"icon\": \"shopping-cart\", \"title\": \"Cart\"}, {\"url\": \"/checkout\", \"icon\": \"credit-card\", \"title\": \"Checkout\"}], \"location\": \"header\"}]}',NULL,1,0,NULL,NULL,NULL,'2025-12-07 04:55:54','2025-12-08 05:58:45',NULL);
/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'{\"ro\":\"Lansarea noului CMS Carphatian\"}','lansarea-noului-cms-carphatian','{\"ro\":\"<p>Carphatian CMS este o platformă modernă și puternică pentru crearea și gestionarea site-urilor web. Cu funcționalități avansate și interfață intuitivă, vă oferim instrumentele necesare pentru succes online.</p><p>Printre caracteristicile principale se numără: drag & drop builder, integrare AI, sistem de plugin-uri, și multe altele.</p>\"}','{\"ro\":\"Suntem bucuroși să anunțăm lansarea oficială a platformei noastre CMS cu integrare AI.\"}','/images/blog/lansarea-noului-cms-carphatian.jpg','published',1,1,NULL,NULL,NULL,NULL,59,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56','2025-12-11 02:08:24',NULL),(2,'{\"ro\":\"Cum să optimizezi SEO-ul site-ului tău\"}','cum-sa-optimizezi-seo-ul-site-ului-tau','{\"ro\":\"<p>Optimizarea pentru motoarele de căutare este esențială pentru succesul online. În acest articol, vei învăța tehnici avansate de SEO.</p><p>Începe cu cercetarea cuvintelor cheie, optimizează meta tag-urile, și creează conținut de calitate.</p>\"}','{\"ro\":\"Descoperă cele mai bune practici pentru optimizarea SEO și creșterea vizibilității online.\"}','/images/blog/cum-sa-optimizezi-seo-ul-site-ului-tau.jpg','published',1,1,NULL,NULL,NULL,NULL,25,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56','2025-12-11 02:08:23',NULL),(3,'{\"ro\":\"Integrarea AI în CMS modern\"}','integrarea-ai-in-cms-modern','{\"ro\":\"<p>Inteligența artificială transformă industria web development. De la generarea automată de conținut până la optimizarea experienței utilizatorului.</p><p>Carphatian CMS integrează AI pentru a-ți facilita munca și a îmbunătăți rezultatele.</p>\"}','{\"ro\":\"Află cum inteligența artificială revolutionează modul în care creăm și gestionăm conținutul web.\"}','/images/blog/integrarea-ai-in-cms-modern.jpg','published',1,1,NULL,NULL,NULL,NULL,21,0,1,NULL,'2025-12-07 05:11:56','2025-12-07 05:11:56','2025-12-11 02:08:25',NULL),(4,'{\"ro\":\"Design Modern: Ghidul Complet pentru Interfețe Intuitive\",\"en\":\"Modern Design: Complete Guide for Intuitive Interfaces\"}','design-modern-ghid-complet-interfete-intuitive','{\"ro\":\"<h2>Introducere în Design-ul Modern CMS</h2>\\n<p>Design-ul modern nu este doar despre aspect vizual - este despre crearea unei experiențe fluide, intuitive și plăcute pentru utilizatori. Platforma CMS Carpathian pune accent pe design responsive și interfețe care facilitează munca utilizatorilor.</p>\\n\\n<h3>🎨 Principii de Design Implementate</h3>\\n<p>Carpathian CMS utilizează cele mai recente tendințe în design web:</p>\\n<ul>\\n<li><strong>Material Design</strong> - Elemente vizuale moderne cu umbre și animații subtile</li>\\n<li><strong>Flat Design</strong> - Interfață curată și simplă, fără elemente inutile</li>\\n<li><strong>Neumorphism</strong> - Efecte 3D moderne pentru butoane și carduri</li>\\n<li><strong>Dark Mode</strong> - Temă întunecată pentru reducerea oboselii ochilor</li>\\n</ul>\\n\\n<h3>📱 Design Responsive și Multi-Platform</h3>\\n<p>Unul dintre cele mai importante aspecte ale design-ului modern este adaptabilitatea. CMS-ul nostru oferă:</p>\\n\\n<h4>Responsive Breakpoints</h4>\\n<ul>\\n<li>Mobile (320px - 767px) - Design optimizat pentru telefoane</li>\\n<li>Tablet (768px - 1023px) - Layout adaptat pentru tablete</li>\\n<li>Desktop (1024px+) - Experiență completă pe ecrane mari</li>\\n</ul>\\n\\n<h3>🎯 Interfață Intuitivă</h3>\\n<p>Interfața utilizator este proiectată pentru a fi:</p>\\n<ol>\\n<li><strong>Ușor de învățat</strong> - Curba de învățare minimă</li>\\n<li><strong>Eficientă</strong> - Acțiuni rapide cu minimal clicks</li>\\n<li><strong>Memorabilă</strong> - Layout consistent și predictibil</li>\\n<li><strong>Fără erori</strong> - Validări și confirmări pentru acțiuni importante</li>\\n</ol>\\n\\n<h3>🔧 Customizare Avansată</h3>\\n<p>Personalizează designul după nevoile tale:</p>\\n<ul>\\n<li>Tema de culori personalizată</li>\\n<li>Logo și branding propriu</li>\\n<li>Fonturi custom</li>\\n<li>Layout-uri personalizate pentru pagini</li>\\n</ul>\\n\\n<h3>✨ Animații și Tranziții</h3>\\n<p>Design-ul modern include animații fluide care îmbunătățesc experiența:</p>\\n<ul>\\n<li>Fade-in pentru încărcarea conținutului</li>\\n<li>Slide transitions pentru meniuri</li>\\n<li>Hover effects pe butoane și linkuri</li>\\n<li>Loading animations elegante</li>\\n</ul>\\n\\n<h3>🎨 Paleta de Culori</h3>\\n<p>CMS Carpathian folosește o paletă de culori profesională:</p>\\n<ul>\\n<li><strong>Primary</strong>: #3B82F6 (Blue) - Pentru acțiuni principale</li>\\n<li><strong>Secondary</strong>: #8B5CF6 (Purple) - Pentru elemente secundare</li>\\n<li><strong>Success</strong>: #10B981 (Green) - Pentru confirmări</li>\\n<li><strong>Danger</strong>: #EF4444 (Red) - Pentru alerte</li>\\n</ul>\\n\\n<h3>📊 Dashboard Modern</h3>\\n<p>Dashboard-ul oferă o vedere de ansamblu clară:</p>\\n<ul>\\n<li>Widget-uri personalizabile</li>\\n<li>Grafice interactive</li>\\n<li>Statistici în timp real</li>\\n<li>Quick actions pentru taskuri frecvente</li>\\n</ul>\\n\\n<h3>🚀 Performanță și Optimizare</h3>\\n<p>Design-ul este optimizat pentru viteză:</p>\\n<ul>\\n<li>CSS și JavaScript minimizate</li>\\n<li>Lazy loading pentru imagini</li>\\n<li>Cache eficient pentru assets</li>\\n<li>CDN pentru resurse statice</li>\\n</ul>\\n\\n<h3>💡 Concluzie</h3>\\n<p>Design-ul modern al CMS Carpathian combină estetica cu funcționalitatea, oferind o experiență de utilizare superioară. Fie că gestionezi un blog personal sau un site corporativ complex, interfața intuitivă te ajută să îți atingi obiectivele rapid și eficient.</p>\",\"en\":\"Modern design content...\"}','{\"ro\":\"Descoperă cum design-ul modern și interfețele intuitive transformă experiența utilizatorilor în platforma CMS Carpathian.\",\"en\":\"Discover how modern design and intuitive interfaces transform user experience in Carpathian CMS.\"}','images/blog/design-modern.jpg','published',1,2,NULL,NULL,NULL,NULL,7,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 02:08:03',NULL),(5,'{\"ro\":\"Performanță Ridicată: Optimizare și Viteză Maximă în CMS\",\"en\":\"High Performance: Optimization and Maximum Speed in CMS\"}','performanta-ridicata-optimizare-viteza-maxima','{\"ro\":\"<h2>Performanță de Nivel Enterprise</h2>\\n<p>Performanța este esențială pentru succesul oricărui site web. CMS Carpathian este construit de la zero cu focus pe viteză și eficiență.</p>\\n\\n<h3>⚡ Tehnologii de Performanță</h3>\\n<p>Platforma utilizează cele mai avansate tehnologii pentru viteză maximă:</p>\\n\\n<h4>1. Redis Caching</h4>\\n<p>Redis este implementat pentru:</p>\\n<ul>\\n<li>Cache de query-uri database</li>\\n<li>Session storage ultra-rapid</li>\\n<li>Cache de obiecte și view-uri</li>\\n<li>Queue management pentru taskuri background</li>\\n</ul>\\n\\n<h4>2. OPcache PHP</h4>\\n<p>Accelerarea codului PHP prin:</p>\\n<ul>\\n<li>Precompilarea bytecode</li>\\n<li>Cache în memorie pentru scripturi</li>\\n<li>Reducerea timpului de execuție cu până la 70%</li>\\n</ul>\\n\\n<h4>3. CDN Integration</h4>\\n<p>Content Delivery Network pentru:</p>\\n<ul>\\n<li>Distribuția globală a assets-urilor</li>\\n<li>Reducerea latency-ului</li>\\n<li>Încărcarea paralelă a resurselor</li>\\n</ul>\\n\\n<h3>🚀 Optimizări Database</h3>\\n<p>Database-ul este optimizat pentru performanță maximă:</p>\\n<ul>\\n<li><strong>Indexuri eficiente</strong> - Pe toate coloanele frecvent căutate</li>\\n<li><strong>Query optimization</strong> - Folosind Eager Loading și Query Builder</li>\\n<li><strong>Connection pooling</strong> - Reutilizarea conexiunilor</li>\\n<li><strong>Read replicas</strong> - Pentru scalabilitate</li>\\n</ul>\\n\\n<h3>📦 Asset Management</h3>\\n<p>Gestionarea eficientă a resurselor:</p>\\n\\n<h4>Minificare și Compresie</h4>\\n<ul>\\n<li>CSS minificat și combinat</li>\\n<li>JavaScript uglified și bundle-d</li>\\n<li>Gzip compression pentru toate assets-urile</li>\\n<li>Brotli compression pentru browsere moderne</li>\\n</ul>\\n\\n<h4>Image Optimization</h4>\\n<ul>\\n<li>WebP format pentru imagini moderne</li>\\n<li>Lazy loading pentru imagini off-screen</li>\\n<li>Responsive images cu srcset</li>\\n<li>Compresia automată la upload</li>\\n</ul>\\n\\n<h3>⚙️ Laravel Optimizations</h3>\\n<p>Framework-ul Laravel este optimizat prin:</p>\\n<ul>\\n<li><strong>Config caching</strong> - php artisan config:cache</li>\\n<li><strong>Route caching</strong> - php artisan route:cache</li>\\n<li><strong>View compilation</strong> - Blade templates pre-compilate</li>\\n<li><strong>Autoload optimization</strong> - Composer optimizat</li>\\n</ul>\\n\\n<h3>🔧 Server Configuration</h3>\\n<p>Server-ul este configurat pentru performanță:</p>\\n\\n<h4>Nginx Optimization</h4>\\n<ul>\\n<li>HTTP/2 enabled pentru multiplexing</li>\\n<li>FastCGI caching</li>\\n<li>Gzip compression</li>\\n<li>Browser caching headers</li>\\n</ul>\\n\\n<h4>PHP-FPM Tuning</h4>\\n<ul>\\n<li>Process manager optimizat</li>\\n<li>Memory limits ajustate</li>\\n<li>Max execution time optimizat</li>\\n</ul>\\n\\n<h3>📊 Monitoring și Analytics</h3>\\n<p>Monitorizarea continuă a performanței:</p>\\n<ul>\\n<li>New Relic pentru APM (Application Performance Monitoring)</li>\\n<li>Laravel Telescope pentru debugging</li>\\n<li>Query time logging</li>\\n<li>Error tracking cu Sentry</li>\\n</ul>\\n\\n<h3>🎯 Benchmarks Reale</h3>\\n<p>Rezultate măsurate:</p>\\n<ul>\\n<li><strong>Time to First Byte (TTFB)</strong>: < 100ms</li>\\n<li><strong>First Contentful Paint</strong>: < 1s</li>\\n<li><strong>Time to Interactive</strong>: < 2s</li>\\n<li><strong>PageSpeed Score</strong>: 95+/100</li>\\n</ul>\\n\\n<h3>🔄 Auto-Scaling</h3>\\n<p>Scalabilitate automată pentru traffic crescut:</p>\\n<ul>\\n<li>Horizontal scaling cu load balancer</li>\\n<li>Auto-scaling bazat pe CPU/Memory</li>\\n<li>Database sharding pentru volume mari</li>\\n</ul>\\n\\n<h3>💡 Best Practices</h3>\\n<p>Recomandări pentru performanță optimă:</p>\\n<ol>\\n<li>Activează toate cache-urile în producție</li>\\n<li>Folosește CDN pentru assets statice</li>\\n<li>Optimizează imaginile înainte de upload</li>\\n<li>Minimizează numărul de query-uri database</li>\\n<li>Folosește pagination pentru liste mari</li>\\n</ol>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>CMS Carpathian oferă performanță de top prin combinarea inteligentă a tehnologiilor moderne de caching, optimizare și configurare server. Rezultatul: un site rapid, responsive și scalabil.</p>\",\"en\":\"High performance content...\"}','{\"ro\":\"Cum CMS Carpathian atinge viteze incredibile și performanță optimă prin tehnologii avansate de caching și optimizare.\",\"en\":\"How Carpathian CMS achieves incredible speeds and optimal performance through advanced caching and optimization.\"}','images/blog/performanta.jpg','published',1,4,NULL,NULL,NULL,NULL,5,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 02:08:07',NULL),(6,'{\"ro\":\"Integrare AI: Cum Inteligența Artificială Revoluționează CMS-ul\",\"en\":\"AI Integration: How Artificial Intelligence Revolutionizes CMS\"}','integrare-ai-inteligenta-artificiala-cms','{\"ro\":\"<h2>AI în CMS: Viitorul Este Acum</h2>\\n<p>Inteligența artificială nu mai este science fiction - este o realitate care transformă modul în care creăm și gestionăm conținutul web. CMS Carpathian integrează AI în mod nativ pentru a automatiza taskuri și a îmbunătăți productivitatea.</p>\\n\\n<h3>🤖 Funcționalități AI Integrate</h3>\\n\\n<h4>1. Content Generation AI</h4>\\n<p>Generarea automată de conținut folosind GPT-4:</p>\\n<ul>\\n<li><strong>Blog Posts</strong> - Scrie articole complete pe bază de subiect</li>\\n<li><strong>Product Descriptions</strong> - Descrieri profesionale pentru produse</li>\\n<li><strong>Meta Tags</strong> - Title și description optimizate pentru SEO</li>\\n<li><strong>Social Media</strong> - Postări pentru Facebook, Twitter, LinkedIn</li>\\n</ul>\\n\\n<h4>2. AI Content Editor</h4>\\n<p>Editor inteligent cu sugestii în timp real:</p>\\n<ul>\\n<li>Corectarea automată a gramaticii și ortografiei</li>\\n<li>Sugestii de îmbunătățire a stilului</li>\\n<li>Optimizări pentru readability</li>\\n<li>Tone adjustment (formal, casual, profesional)</li>\\n</ul>\\n\\n<h4>3. SEO AI Assistant</h4>\\n<p>Optimizare SEO automatizată:</p>\\n<ul>\\n<li>Keyword research și density analysis</li>\\n<li>Content scoring în timp real</li>\\n<li>Meta tags optimization</li>\\n<li>Internal linking suggestions</li>\\n<li>Schema markup generation</li>\\n</ul>\\n\\n<h3>🎨 AI Image Generator</h3>\\n<p>Generarea de imagini cu DALL-E și Stable Diffusion:</p>\\n<ul>\\n<li>Featured images pentru articole</li>\\n<li>Thumbnails personalizate</li>\\n<li>Social media graphics</li>\\n<li>Product images conceptuale</li>\\n</ul>\\n\\n<h3>📝 AI Writing Assistant</h3>\\n<p>Asistent de scriere inteligent care te ajută să:</p>\\n\\n<h4>Completare Automată</h4>\\n<ul>\\n<li>Predictive text în timp ce scrii</li>\\n<li>Continuarea automată a paragrafelor</li>\\n<li>Sugestii de fraze relevante</li>\\n</ul>\\n\\n<h4>Rewriting și Parafrazare</h4>\\n<ul>\\n<li>Rescrierea conținutului pentru unicitate</li>\\n<li>Schimbarea tonului și stilului</li>\\n<li>Simplificarea sau complexificarea textului</li>\\n</ul>\\n\\n<h3>🔍 AI Search și Discovery</h3>\\n<p>Căutare inteligentă cu înțelegere semantică:</p>\\n<ul>\\n<li>Natural language search</li>\\n<li>Rezultate relevante bazate pe intent</li>\\n<li>Auto-complete inteligent</li>\\n<li>Related content suggestions</li>\\n</ul>\\n\\n<h3>🎯 Personalizare AI</h3>\\n<p>Experiență personalizată pentru fiecare utilizator:</p>\\n<ul>\\n<li>Content recommendations bazate pe comportament</li>\\n<li>Dynamic layouts adaptate la preferințe</li>\\n<li>Predictive navigation</li>\\n<li>A/B testing automatizat</li>\\n</ul>\\n\\n<h3>📊 AI Analytics</h3>\\n<p>Analiză inteligentă a datelor:</p>\\n<ul>\\n<li>Predictive analytics pentru traffic</li>\\n<li>Anomaly detection</li>\\n<li>Trend identification</li>\\n<li>Automatic reporting</li>\\n</ul>\\n\\n<h3>🔒 AI Security</h3>\\n<p>Securitate îmbunătățită prin AI:</p>\\n<ul>\\n<li>Spam detection automatizat</li>\\n<li>Malware scanning</li>\\n<li>Suspicious activity detection</li>\\n<li>Fraud prevention</li>\\n</ul>\\n\\n<h3>💬 AI Chatbot Integration</h3>\\n<p>Chatbot inteligent pentru suport:</p>\\n<ul>\\n<li>Răspunsuri automate la întrebări comune</li>\\n<li>Natural language understanding</li>\\n<li>Context awareness</li>\\n<li>Escalation la human support când e nevoie</li>\\n</ul>\\n\\n<h3>🌐 AI Translation</h3>\\n<p>Traducere automată de calitate:</p>\\n<ul>\\n<li>Multi-language support</li>\\n<li>Context-aware translations</li>\\n<li>Automatic language detection</li>\\n<li>Cultural adaptation</li>\\n</ul>\\n\\n<h3>⚙️ Configurare AI</h3>\\n<p>Setări flexibile pentru AI:</p>\\n<ul>\\n<li>API keys pentru OpenAI, Anthropic</li>\\n<li>Tone și style preferences</li>\\n<li>Content length limits</li>\\n<li>Quality thresholds</li>\\n</ul>\\n\\n<h3>📈 ROI cu AI</h3>\\n<p>Beneficii măsurabile:</p>\\n<ul>\\n<li><strong>60% reducere</strong> în timpul de creare conținut</li>\\n<li><strong>40% creștere</strong> în productivitate</li>\\n<li><strong>80% automatizare</strong> a taskurilor repetitive</li>\\n<li><strong>3x mai mult</strong> conținut publicat</li>\\n</ul>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>AI în CMS Carpathian nu este un gimmick - este un tool real care economisește timp, îmbunătățește calitatea conținutului și oferă insights valoroase. Începe să folosești AI astăzi și vezi diferența!</p>\",\"en\":\"AI integration content...\"}','{\"ro\":\"Explorează funcționalitățile AI integrate în CMS Carpathian pentru generarea automată de conținut, optimizare SEO și personalizare.\",\"en\":\"Explore AI features integrated in Carpathian CMS for automatic content generation, SEO optimization and personalization.\"}','images/blog/ai-integration.jpg','published',1,3,NULL,NULL,NULL,NULL,53,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 02:08:02',NULL),(7,'{\"ro\":\"Securitate Avansată: Protecția Completă a Datelor în CMS\",\"en\":\"Advanced Security: Complete Data Protection in CMS\"}','securitate-avansata-protectie-date-cms','{\"ro\":\"<h2>Securitate de Nivel Enterprise</h2>\\n<p>Securitatea nu este opțională - este fundamentală. CMS Carpathian implementează cele mai stricte măsuri de securitate pentru protecția datelor tale.</p>\\n\\n<h3>🔒 Măsuri de Securitate Implementate</h3>\\n\\n<h4>1. SSL/TLS Encryption</h4>\\n<ul>\\n<li>Certificat SSL gratuit (Let\'s Encrypt)</li>\\n<li>TLS 1.3 pentru conexiuni sigure</li>\\n<li>HTTPS forțat pe toate paginile</li>\\n<li>HSTS (HTTP Strict Transport Security)</li>\\n</ul>\\n\\n<h4>2. Web Application Firewall (WAF)</h4>\\n<p>Protecție împotriva atacurilor comune:</p>\\n<ul>\\n<li>SQL Injection prevention</li>\\n<li>XSS (Cross-Site Scripting) protection</li>\\n<li>CSRF (Cross-Site Request Forgery) tokens</li>\\n<li>DDoS mitigation</li>\\n<li>Brute force protection</li>\\n</ul>\\n\\n<h4>3. Authentication & Authorization</h4>\\n<ul>\\n<li><strong>Two-Factor Authentication (2FA)</strong> - Google Authenticator, SMS</li>\\n<li><strong>Strong Password Policy</strong> - Minimum 12 caractere</li>\\n<li><strong>Role-Based Access Control</strong> - Permisiuni granulare</li>\\n<li><strong>Session Management</strong> - Timeout automat, session hijacking prevention</li>\\n</ul>\\n\\n<h3>💾 Backup Automat</h3>\\n<p>Backup-uri regulate și sigure:</p>\\n<ul>\\n<li><strong>Daily backups</strong> - Automate la ora 2 AM</li>\\n<li><strong>Incremental backups</strong> - Doar modificările noi</li>\\n<li><strong>Off-site storage</strong> - AWS S3, Google Cloud</li>\\n<li><strong>Retention policy</strong> - 30 zile istorice</li>\\n<li><strong>One-click restore</strong> - Restaurare rapidă</li>\\n</ul>\\n\\n<h3>🛡️ Data Protection</h3>\\n<p>Protecția datelor personale (GDPR compliant):</p>\\n<ul>\\n<li>Encryption at rest pentru database</li>\\n<li>Encryption in transit pentru toate comunicările</li>\\n<li>Data anonymization tools</li>\\n<li>Right to be forgotten implementation</li>\\n<li>Privacy policy generator</li>\\n</ul>\\n\\n<h3>🔐 Password Security</h3>\\n<p>Gestionarea sigură a parolelor:</p>\\n<ul>\\n<li>Bcrypt hashing cu cost factor 12</li>\\n<li>Salt unic pentru fiecare parolă</li>\\n<li>Password strength meter</li>\\n<li>Mandatory password changes</li>\\n<li>Password reset via email cu token</li>\\n</ul>\\n\\n<h3>📝 Audit Logging</h3>\\n<p>Tracking complet al activităților:</p>\\n<ul>\\n<li>Login/logout events</li>\\n<li>Content modifications</li>\\n<li>User management actions</li>\\n<li>Failed authentication attempts</li>\\n<li>System configuration changes</li>\\n</ul>\\n\\n<h3>🔍 Vulnerability Scanning</h3>\\n<p>Scanare automată pentru vulnerabilități:</p>\\n<ul>\\n<li>Dependency checking (composer outdated)</li>\\n<li>Security advisories monitoring</li>\\n<li>Automated patching când e posibil</li>\\n<li>Monthly security audits</li>\\n</ul>\\n\\n<h3>🚨 Intrusion Detection</h3>\\n<p>Detectarea și răspunsul la atacuri:</p>\\n<ul>\\n<li>Real-time monitoring</li>\\n<li>Suspicious activity alerts</li>\\n<li>Automatic IP blocking</li>\\n<li>Incident response plan</li>\\n</ul>\\n\\n<h3>📊 Security Dashboard</h3>\\n<p>Monitorizarea centralizată a securității:</p>\\n<ul>\\n<li>Security score overview</li>\\n<li>Recent security events</li>\\n<li>Vulnerability status</li>\\n<li>Compliance checks</li>\\n</ul>\\n\\n<h3>✅ Best Practices</h3>\\n<p>Recomandări pentru securitate maximă:</p>\\n<ol>\\n<li>Activează 2FA pentru toți adminii</li>\\n<li>Folosește parole puternice, unice</li>\\n<li>Actualizează regular CMS-ul</li>\\n<li>Limitează accesul SSH</li>\\n<li>Verifică log-urile periodic</li>\\n<li>Testează restore-ul din backup lunar</li>\\n</ol>\\n\\n<h3>🔒 Concluzie</h3>\\n<p>CMS Carpathian ia securitatea în serios, oferind protecție completă împotriva amenințărilor moderne. Datele tale sunt în siguranță!</p>\",\"en\":\"Security content...\"}','{\"ro\":\"Cum CMS Carpathian asigură securitatea maximă prin SSL, firewall, backup automat și cele mai bune practici de securitate.\",\"en\":\"How Carpathian CMS ensures maximum security through SSL, firewall, automatic backup and best security practices.\"}','images/blog/securitate.jpg','published',1,4,NULL,NULL,NULL,NULL,3,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 02:08:09',NULL),(8,'{\"ro\":\"Multi-Platform: Funcționare Perfectă pe Orice Dispozitiv\",\"en\":\"Multi-Platform: Perfect Performance on Any Device\"}','multi-platform-functionare-orice-dispozitiv','{\"ro\":\"<h2>Design Responsive și Multi-Device</h2>\\n<p>În lumea modernă, utilizatorii accesează web-ul de pe diverse dispozitive. CMS Carpathian este construit să ofere experiență perfectă pe orice ecran.</p>\\n\\n<h3>📱 Responsive Design</h3>\\n<p>Layout-ul se adaptează automat la orice dimensiune de ecran:</p>\\n\\n<h4>Breakpoints Inteligente</h4>\\n<ul>\\n<li><strong>Mobile Small</strong> (320px - 374px) - iPhone SE, Android compact</li>\\n<li><strong>Mobile</strong> (375px - 767px) - iPhone, Samsung Galaxy</li>\\n<li><strong>Tablet</strong> (768px - 1023px) - iPad, Android tablets</li>\\n<li><strong>Desktop</strong> (1024px - 1439px) - Laptops, monitoare</li>\\n<li><strong>Large Desktop</strong> (1440px+) - Monitoare mari, 4K</li>\\n</ul>\\n\\n<h4>Mobile-First Approach</h4>\\n<p>Dezvoltare pornind de la mobil:</p>\\n<ul>\\n<li>Design optimizat inițial pentru mobil</li>\\n<li>Progressive enhancement pentru ecrane mari</li>\\n<li>Touch-friendly interfaces</li>\\n<li>Gesturi intuitive (swipe, pinch-to-zoom)</li>\\n</ul>\\n\\n<h3>💻 Cross-Browser Compatibility</h3>\\n<p>Funcționare perfectă pe toate browserele:</p>\\n<ul>\\n<li><strong>Chrome</strong> - Desktop și mobile</li>\\n<li><strong>Safari</strong> - macOS și iOS</li>\\n<li><strong>Firefox</strong> - Desktop și mobile</li>\\n<li><strong>Edge</strong> - Windows, mobile</li>\\n<li><strong>Opera</strong> - Desktop și mobile</li>\\n</ul>\\n\\n<h3>🚀 Progressive Web App (PWA)</h3>\\n<p>Funcționalități native pe web:</p>\\n\\n<h4>Install to Home Screen</h4>\\n<ul>\\n<li>Iconița pe home screen ca o aplicație nativă</li>\\n<li>Splash screen personalizat</li>\\n<li>Standalone mode fără browser UI</li>\\n</ul>\\n\\n<h4>Offline Support</h4>\\n<ul>\\n<li>Service Worker pentru caching</li>\\n<li>Funcționare offline pentru conținut vizitat</li>\\n<li>Background sync când revii online</li>\\n</ul>\\n\\n<h4>Push Notifications</h4>\\n<ul>\\n<li>Notificări pentru conținut nou</li>\\n<li>Alerts pentru comentarii</li>\\n<li>Custom notifications pentru evenimente</li>\\n</ul>\\n\\n<h3>⚡ Performance pe Mobile</h3>\\n<p>Optimizări speciale pentru dispozitive mobile:</p>\\n<ul>\\n<li>Imagini responsive cu srcset</li>\\n<li>Lazy loading pentru imagini și video</li>\\n<li>Minificare agresivă CSS/JS</li>\\n<li>Reducerea request-urilor HTTP</li>\\n<li>Critical CSS inline</li>\\n</ul>\\n\\n<h3>🎨 Adaptive UI Components</h3>\\n<p>Componente care se adaptează la context:</p>\\n\\n<h4>Navigation</h4>\\n<ul>\\n<li><strong>Desktop</strong>: Horizontal menu cu dropdown</li>\\n<li><strong>Tablet</strong>: Hybrid menu cu toggle</li>\\n<li><strong>Mobile</strong>: Hamburger menu cu overlay</li>\\n</ul>\\n\\n<h4>Forms</h4>\\n<ul>\\n<li>Input types optimizate pentru mobile (tel, email, date)</li>\\n<li>Large touch targets (minimum 44x44px)</li>\\n<li>Auto-complete și suggestions</li>\\n<li>Inline validation</li>\\n</ul>\\n\\n<h4>Tables</h4>\\n<ul>\\n<li><strong>Desktop</strong>: Full table layout</li>\\n<li><strong>Tablet</strong>: Horizontal scroll</li>\\n<li><strong>Mobile</strong>: Card layout sau accordion</li>\\n</ul>\\n\\n<h3>🖱️ Touch și Keyboard Support</h3>\\n<p>Input methods multiple:</p>\\n<ul>\\n<li>Touch gestures (tap, swipe, pinch)</li>\\n<li>Keyboard shortcuts pentru power users</li>\\n<li>Mouse hover effects pe desktop</li>\\n<li>Accessibility support (ARIA labels)</li>\\n</ul>\\n\\n<h3>📊 Device Analytics</h3>\\n<p>Înțelegerea utilizatorilor tăi:</p>\\n<ul>\\n<li>Device breakdown (mobile vs desktop vs tablet)</li>\\n<li>Browser statistics</li>\\n<li>Screen resolution data</li>\\n<li>OS distribution</li>\\n</ul>\\n\\n<h3>🔧 Testing pe Dispozitive</h3>\\n<p>Asigurarea calității pe toate platformele:</p>\\n<ul>\\n<li>BrowserStack pentru cross-browser testing</li>\\n<li>Device emulators în Chrome DevTools</li>\\n<li>Real device testing</li>\\n<li>Automated visual regression tests</li>\\n</ul>\\n\\n<h3>📱 Native App Feel</h3>\\n<p>Experiență apropiată de aplicații native:</p>\\n<ul>\\n<li>Smooth animations (60fps)</li>\\n<li>Instant feedback la interacțiuni</li>\\n<li>Native-like transitions</li>\\n<li>Bottom navigation pe mobile</li>\\n</ul>\\n\\n<h3>🌐 International Support</h3>\\n<p>Adaptat pentru piețe globale:</p>\\n<ul>\\n<li>RTL support pentru limbi arabe</li>\\n<li>Date și time formats locale</li>\\n<li>Currency și number formatting</li>\\n<li>Timezone handling</li>\\n</ul>\\n\\n<h3>✅ Best Practices</h3>\\n<ol>\\n<li>Testează pe dispozitive reale, nu doar emulatoare</li>\\n<li>Optimizează imaginile pentru mobile (WebP, compression)</li>\\n<li>Folosește viewport meta tag corect</li>\\n<li>Evită hover-only interactions</li>\\n<li>Testează cu conexiuni lente (3G throttling)</li>\\n</ol>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>CMS Carpathian oferă experiență excepțională pe orice dispozitiv, asigurându-se că utilizatorii tăi au acces optim indiferent unde se află!</p>\",\"en\":\"Multi-platform content...\"}','{\"ro\":\"Descoperă cum CMS Carpathian oferă experiență optimă pe desktop, tabletă și mobil prin responsive design și PWA.\",\"en\":\"Discover how Carpathian CMS provides optimal experience on desktop, tablet and mobile through responsive design and PWA.\"}','images/blog/multi-platform.jpg','published',1,2,NULL,NULL,NULL,NULL,7,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 02:08:06',NULL),(9,'{\"ro\":\"Personalizare Completă: Configurează CMS-ul După Nevoile Tale\",\"en\":\"Complete Customization: Configure CMS According to Your Needs\"}','personalizare-completa-configurare-cms','{\"ro\":\"<h2>Personalizare Fără Limite</h2>\\n<p>Fiecare business este unic, iar CMS-ul tău ar trebui să reflecte asta. CMS Carpathian oferă opțiuni complete de personalizare pentru orice nevoie.</p>\\n\\n<h3>🎨 Theme Customization</h3>\\n<p>Personalizează aspectul visual complet:</p>\\n\\n<h4>Color Schemes</h4>\\n<ul>\\n<li><strong>Primary Color</strong> - Culoarea principală a brand-ului</li>\\n<li><strong>Secondary Color</strong> - Culoare complementară</li>\\n<li><strong>Accent Colors</strong> - Pentru highlight-uri și CTA-uri</li>\\n<li><strong>Dark Mode</strong> - Paletă întunecată automată</li>\\n<li><strong>Custom Gradients</strong> - Gradient builder vizual</li>\\n</ul>\\n\\n<h4>Typography</h4>\\n<ul>\\n<li>Google Fonts integration (1000+ fonturi)</li>\\n<li>Custom font upload (OTF, TTF, WOFF)</li>\\n<li>Font pairing suggestions</li>\\n<li>Font size și spacing control</li>\\n<li>Heading și body font separate</li>\\n</ul>\\n\\n<h4>Layout Options</h4>\\n<ul>\\n<li><strong>Boxed vs Full Width</strong></li>\\n<li><strong>Sidebar Position</strong> (left, right, none)</li>\\n<li><strong>Header Styles</strong> (sticky, transparent, minimal)</li>\\n<li><strong>Footer Layouts</strong> (columns, mega footer)</li>\\n</ul>\\n\\n<h3>🧩 Widget System</h3>\\n<p>Construiește pagini cu drag-and-drop:</p>\\n\\n<h4>Available Widgets</h4>\\n<ul>\\n<li><strong>Hero Section</strong> - Banner principal cu CTA</li>\\n<li><strong>Features Grid</strong> - Prezentarea caracteristicilor</li>\\n<li><strong>Blog Posts</strong> - Ultimele articole</li>\\n<li><strong>Products Showcase</strong> - Produse din shop</li>\\n<li><strong>Testimonials</strong> - Recenzii clienți</li>\\n<li><strong>Contact Form</strong> - Formular personalizabil</li>\\n<li><strong>Gallery</strong> - Galerie foto/video</li>\\n<li><strong>FAQ Accordion</strong> - Întrebări frecvente</li>\\n<li><strong>Team Members</strong> - Echipa ta</li>\\n<li><strong>Pricing Tables</strong> - Planuri și prețuri</li>\\n</ul>\\n\\n<h4>Widget Settings</h4>\\n<ul>\\n<li>Background (solid, gradient, image, video)</li>\\n<li>Padding și margins personalizabile</li>\\n<li>Animation effects</li>\\n<li>Visibility rules (show/hide pe dispozitive)</li>\\n</ul>\\n\\n<h3>📄 Page Templates</h3>\\n<p>Template-uri pre-built pentru diverse scenarii:</p>\\n<ul>\\n<li><strong>Homepage</strong> - Landing page cu CTA</li>\\n<li><strong>About Us</strong> - Despre companie</li>\\n<li><strong>Services</strong> - Serviciile oferite</li>\\n<li><strong>Portfolio</strong> - Proiecte realizate</li>\\n<li><strong>Contact</strong> - Pagină de contact</li>\\n<li><strong>Blog</strong> - Index articole</li>\\n<li><strong>Shop</strong> - Magazin online</li>\\n</ul>\\n\\n<h3>🔧 Advanced Settings</h3>\\n\\n<h4>SEO Configuration</h4>\\n<ul>\\n<li>Meta titles și descriptions</li>\\n<li>Open Graph tags pentru social media</li>\\n<li>Schema.org markup</li>\\n<li>XML sitemap auto-generated</li>\\n<li>Robots.txt editor</li>\\n</ul>\\n\\n<h4>Performance Settings</h4>\\n<ul>\\n<li>Cache TTL configuration</li>\\n<li>Image optimization quality</li>\\n<li>CDN integration</li>\\n<li>Lazy loading options</li>\\n</ul>\\n\\n<h4>Security Options</h4>\\n<ul>\\n<li>2FA mandatory pentru roles</li>\\n<li>IP whitelist/blacklist</li>\\n<li>Login attempt limits</li>\\n<li>Session timeout duration</li>\\n</ul>\\n\\n<h3>🎯 Custom Post Types</h3>\\n<p>Creează tipuri de conținut personalizate:</p>\\n<ul>\\n<li>Custom fields builder</li>\\n<li>Field types (text, number, date, image, etc)</li>\\n<li>Taxonomies și tags</li>\\n<li>Custom archive pages</li>\\n</ul>\\n\\n<h3>🔌 Plugin System</h3>\\n<p>Extinde funcționalitatea cu plugin-uri:</p>\\n<ul>\\n<li><strong>E-commerce</strong> - Shop complet cu plăți</li>\\n<li><strong>Forms</strong> - Form builder avansat</li>\\n<li><strong>Newsletter</strong> - Email marketing integration</li>\\n<li><strong>Analytics</strong> - Google Analytics, Facebook Pixel</li>\\n<li><strong>Social Login</strong> - Login cu Facebook, Google</li>\\n</ul>\\n\\n<h3>�� User Roles & Permissions</h3>\\n<p>Control granular al accesului:</p>\\n<ul>\\n<li><strong>Administrator</strong> - Acces complet</li>\\n<li><strong>Editor</strong> - Gestionare conținut</li>\\n<li><strong>Author</strong> - Creare articole proprii</li>\\n<li><strong>Contributor</strong> - Submit pentru review</li>\\n<li><strong>Subscriber</strong> - Acces read-only</li>\\n<li><strong>Custom Roles</strong> - Creează role personalizate</li>\\n</ul>\\n\\n<h3>🌐 Multi-Language Support</h3>\\n<p>Site-uri multilingve:</p>\\n<ul>\\n<li>Unlimited languages</li>\\n<li>Per-page language selection</li>\\n<li>Automatic translation via AI</li>\\n<li>RTL support</li>\\n<li>Language switcher widget</li>\\n</ul>\\n\\n<h3>📊 Custom Dashboard</h3>\\n<p>Dashboard personalizat pentru fiecare user:</p>\\n<ul>\\n<li>Drag-and-drop dashboard widgets</li>\\n<li>Custom metrics și KPIs</li>\\n<li>Quick actions shortcuts</li>\\n<li>Recent activity feed</li>\\n</ul>\\n\\n<h3>🔗 API & Webhooks</h3>\\n<p>Integrări cu servicii externe:</p>\\n<ul>\\n<li>REST API pentru toate resursele</li>\\n<li>Webhook triggers pentru events</li>\\n<li>OAuth2 authentication</li>\\n<li>API rate limiting</li>\\n</ul>\\n\\n<h3>💡 Pro Tips</h3>\\n<ol>\\n<li>Începe cu un template și customizează gradual</li>\\n<li>Folosește preview mode pentru a testa modificările</li>\\n<li>Salvează setările ca presets pentru reutilizare</li>\\n<li>Documentează customizările tale</li>\\n<li>Testează pe multiple dispozitive după fiecare modificare</li>\\n</ol>\\n\\n<h3>🚀 Concluzie</h3>\\n<p>CMS Carpathian oferă flexibilitate completă pentru a crea exact site-ul pe care îl vizualizezi. De la culori și fonturi până la funcționalități complexe - totul este personalizabil!</p>\",\"en\":\"Customization content...\"}','{\"ro\":\"Ghid complet pentru personalizarea CMS Carpathian: teme, widget-uri, layout-uri și setări avansate pentru site-ul perfect.\",\"en\":\"Complete guide for customizing Carpathian CMS: themes, widgets, layouts and advanced settings for the perfect site.\"}','images/blog/personalizare.jpg','published',1,2,NULL,NULL,NULL,NULL,5,0,1,NULL,'2025-12-08 07:18:10','2025-12-08 07:18:10','2025-12-11 02:08:08',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (7,'{\"en\":\"Social Media Services\",\"ro\":\"Servicii Social Media\"}','servicii-social-media','{\"en\":\"Professional social media management and marketing services\",\"ro\":\"Servicii profesionale de management și marketing pe rețelele sociale\"}','/images/categories/social-media.jpg',NULL,1,1,'2025-12-08 06:24:37','2025-12-08 06:36:04'),(8,'{\"en\":\"Web Services\",\"ro\":\"Servicii Web\"}','servicii-web','{\"en\":\"Professional web development and design services\",\"ro\":\"Servicii profesionale de dezvoltare și design web\"}','/images/categories/web-services.jpg',NULL,2,1,'2025-12-08 06:24:37','2025-12-08 06:36:04'),(9,'{\"en\":\"Programming Services\",\"ro\":\"Servicii Programare\"}','servicii-programare','{\"en\":\"Custom software development and programming solutions\",\"ro\":\"Soluții personalizate de dezvoltare software și programare\"}','/images/categories/programming.jpg',NULL,3,1,'2025-12-08 06:24:37','2025-12-08 06:36:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,NULL,'{\"ro\":\"Website Standard\"}','website-standard','{\"ro\":\"Site web profesional cu design modern și funcționalități complete\"}',NULL,'WEB4014',1499.00,NULL,999,'\"[\\\"images\\\\/products\\\\/website-standard.jpg\\\"]\"',NULL,NULL,0,1,'2025-12-07 05:12:11','2025-12-08 06:33:10'),(2,NULL,'{\"ro\":\"E-Commerce Complete\"}','e-commerce-complete','{\"ro\":\"Magazin online cu sistem de plăți, gestiune stocuri și rapoarte\"}',NULL,'E-C9283',2999.00,NULL,999,'\"[\\\"images\\\\/products\\\\/ecommerce.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-07 05:12:11','2025-12-08 06:33:10'),(3,NULL,'{\"ro\":\"Landing Page Premium\"}','landing-page-premium','{\"ro\":\"Pagină de destinație optimizată pentru conversii maxime\"}',NULL,'LAN4937',799.00,NULL,999,'\"[\\\"images\\\\/products\\\\/landing-page.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-07 05:12:11','2025-12-08 06:33:10'),(4,NULL,'{\"ro\":\"Portal Corporate\"}','portal-corporate','{\"ro\":\"Soluție complexă pentru companii cu multiple funcționalități\"}',NULL,'POR4706',4999.00,NULL,999,'\"[\\\"images\\\\/products\\\\/portal-corporate.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-07 05:12:11','2025-12-08 06:33:10'),(5,7,'{\"ro\":\"Blog Profesional\"}','blog-profesional','{\"ro\":\"Platform de blogging cu SEO avansat și analytics\"}',NULL,'BLO6466',999.00,NULL,999,'\"[\\\"images\\\\/products\\\\/blog.jpg\\\"]\"',NULL,NULL,0,1,'2025-12-07 05:12:11','2025-12-09 17:04:19'),(6,NULL,'{\"ro\":\"Aplicație Web Custom\"}','aplicatie-web-custom','{\"ro\":\"Dezvoltare personalizată conform cerințelor tale specifice\"}',NULL,'APL6828',5999.00,NULL,999,'\"[\\\"images\\\\/products\\\\/custom-app.jpg\\\"]\"',NULL,NULL,1,1,'2025-12-07 05:12:11','2025-12-08 06:33:10');
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
  `from_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL DEFAULT '301',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `hits` bigint unsigned NOT NULL DEFAULT '0',
  `last_hit_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
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
INSERT INTO `seo_settings` VALUES (1,'Web Agency - Professional Web Development','Professional web development and digital solutions for modern businesses',NULL,NULL,'User-agent: *\nAllow: /\nSitemap: http://cms.carphatian.ro/sitemap.xml',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-07 04:49:18','2025-12-07 04:49:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'email','info@carphatian.ro','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(2,'phone','+40774077860','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(3,'address','Str Ploiesti 47-49','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(4,'city','Cluj-Napoca','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(5,'state','Cluj','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53'),(6,'country','Romania','text','contact',NULL,0,'2025-12-09 11:05:53','2025-12-09 11:05:53');
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
INSERT INTO `shop_settings` VALUES (1,'USD','$','before',0,0.00,0,'{\"stripe\": {\"enabled\": false, \"public_key\": \"\", \"secret_key\": \"\"}, \"paypal_api\": {\"secret\": \"\", \"enabled\": false, \"client_id\": \"\"}, \"bank_transfer\": {\"iban\": \"\", \"enabled\": false, \"bank_name\": \"\", \"bic_swift\": \"\", \"account_name\": \"\", \"instructions\": \"\"}, \"paypal_classic\": {\"email\": \"\", \"enabled\": false}}',NULL,NULL,NULL,'ORD-',1,1,5,'2025-12-07 04:49:18','2025-12-07 04:49:18');
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
INSERT INTO `system_settings` VALUES (1,'auto_updates','disabled','string','Automatic update mode: disabled, minor, all','2025-12-07 04:49:18','2025-12-07 04:49:18'),(2,'update_server','https://updates.carphatian.ro','url','Update server URL','2025-12-07 04:49:18','2025-12-07 04:49:18'),(3,'backup_retention_days','30','integer','Number of days to keep backups','2025-12-07 04:49:18','2025-12-07 04:49:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (1,'Carpathian Default','carpathian-default','Modern and professional template with purple accent colors, perfect for business and corporate websites.','/images/templates/carpathian-default.jpg','1.0.0','Carpathian CMS','https://carpathian.ro',NULL,NULL,'\"{\\\"supports_widgets\\\":true,\\\"supports_page_builder\\\":true,\\\"has_sidebar\\\":false,\\\"layout_type\\\":\\\"full-width\\\"}\"','\"[\\\"home\\\",\\\"page\\\",\\\"blog\\\",\\\"single-post\\\",\\\"contact\\\"]\"',0,1,0,NULL,'\"{\\\"primary\\\":\\\"#9333ea\\\",\\\"secondary\\\":\\\"#6b21a8\\\",\\\"accent\\\":\\\"#a855f7\\\",\\\"background\\\":\\\"#ffffff\\\",\\\"text\\\":\\\"#1f2937\\\"}\"','\"{\\\"font_family\\\":\\\"Inter, system-ui, sans-serif\\\",\\\"heading_font\\\":\\\"Inter, sans-serif\\\",\\\"body_font\\\":\\\"Inter, sans-serif\\\"}\"',NULL,'2025-12-07 05:44:47','2025-12-07 08:10:41',NULL),(2,'Ocean Blue','ocean-blue','Clean and modern template with calming blue colors, ideal for technology and service-based businesses.','/images/templates/ocean-blue.jpg','1.0.0','Carpathian CMS','https://carpathian.ro',NULL,NULL,'\"{\\\"supports_widgets\\\":true,\\\"supports_page_builder\\\":true,\\\"has_sidebar\\\":true,\\\"layout_type\\\":\\\"boxed\\\"}\"','\"[\\\"home\\\",\\\"page\\\",\\\"blog\\\",\\\"single-post\\\",\\\"contact\\\"]\"',0,0,0,NULL,'\"{\\\"primary\\\":\\\"#0ea5e9\\\",\\\"secondary\\\":\\\"#0284c7\\\",\\\"accent\\\":\\\"#38bdf8\\\",\\\"background\\\":\\\"#ffffff\\\",\\\"text\\\":\\\"#1e293b\\\"}\"','\"{\\\"font_family\\\":\\\"Poppins, system-ui, sans-serif\\\",\\\"heading_font\\\":\\\"Poppins, sans-serif\\\",\\\"body_font\\\":\\\"Poppins, sans-serif\\\"}\"',NULL,'2025-12-07 05:44:47','2025-12-07 06:36:52','2025-12-07 06:36:52'),(3,'TechWave Pro','techwave-pro','Modern IT Software Services theme with vibrant orange and professional blue color scheme. Features advanced button styles, gradient accents, and enterprise-ready design elements.',NULL,'1.0.0','Carpathian CMS','',NULL,NULL,'{\"layout\": \"full-width\", \"features\": {\"parallax\": true, \"dark_mode\": false, \"glassmorphism\": true, \"hover_effects\": true, \"rounded_corners\": true, \"gradient_buttons\": true, \"smooth_scrolling\": true, \"loading_animations\": true}, \"footer_style\": \"corporate\", \"header_style\": \"modern\", \"header_sticky\": true, \"widget_support\": true, \"sidebar_enabled\": false, \"sidebar_position\": \"right\", \"header_transparent\": false, \"responsive_breakpoints\": {\"wide\": \"1536px\", \"laptop\": \"1024px\", \"mobile\": \"640px\", \"tablet\": \"768px\", \"desktop\": \"1280px\"}}','[]',0,0,0,NULL,'{\"dark\": \"#1f2937\", \"info\": \"#3b82f6\", \"light\": \"#f9fafb\", \"accent\": \"#fbbf24\", \"border\": \"#e5e7eb\", \"danger\": \"#ef4444\", \"nav_bg\": \"#ffffff\", \"card_bg\": \"#ffffff\", \"overlay\": \"rgba(30, 64, 175, 0.95)\", \"primary\": \"#ff6b35\", \"success\": \"#10b981\", \"warning\": \"#f59e0b\", \"nav_text\": \"#111827\", \"footer_bg\": \"#1f2937\", \"nav_hover\": \"#ff6b35\", \"secondary\": \"#1e40af\", \"background\": \"#ffffff\", \"text_light\": \"#9ca3af\", \"card_shadow\": \"rgba(0, 0, 0, 0.1)\", \"footer_text\": \"#d1d5db\", \"border_focus\": \"#ff6b35\", \"gradient_end\": \"#1e40af\", \"text_primary\": \"#111827\", \"button_accent\": \"#fbbf24\", \"background_alt\": \"#f3f4f6\", \"button_primary\": \"#ff6b35\", \"gradient_start\": \"#ff6b35\", \"text_secondary\": \"#6b7280\", \"button_secondary\": \"#1e40af\", \"button_accent_hover\": \"#f59e0b\", \"button_primary_hover\": \"#ff5722\", \"button_secondary_hover\": \"#1e3a8a\"}','{\"font_size_lg\": \"18px\", \"font_size_sm\": \"14px\", \"font_size_xl\": \"20px\", \"font_size_2xl\": \"24px\", \"font_size_3xl\": \"30px\", \"font_size_4xl\": \"36px\", \"font_size_5xl\": \"48px\", \"font_size_6xl\": \"60px\", \"font_size_base\": \"16px\", \"font_family_mono\": \"\'Fira Code\', \'Consolas\', monospace\", \"font_weight_bold\": \"700\", \"font_weight_light\": \"300\", \"line_height_tight\": \"1.25\", \"font_weight_medium\": \"500\", \"font_weight_normal\": \"400\", \"line_height_normal\": \"1.5\", \"font_family_primary\": \"\'Inter\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif\", \"letter_spacing_wide\": \"0.025em\", \"line_height_relaxed\": \"1.75\", \"font_family_headings\": \"\'Poppins\', sans-serif\", \"font_weight_semibold\": \"600\", \"letter_spacing_tight\": \"-0.025em\", \"font_family_secondary\": \"\'Poppins\', sans-serif\", \"font_weight_extrabold\": \"800\", \"letter_spacing_normal\": \"0\"}',NULL,'2025-12-07 06:11:34','2025-12-07 06:36:49','2025-12-07 06:36:49'),(4,'ShopFlow Modern','shopflow-modern','Modern e-commerce template with product grids, shopping cart focus, and conversion-optimized layouts. Perfect for online stores.',NULL,'1.0.0','Carpathian CMS','',NULL,NULL,'{\"layout\": \"full-width\", \"features\": [\"Product Grids\", \"Shopping Cart\", \"Wishlist\", \"Reviews\"], \"industry\": \"E-commerce\", \"header_style\": \"modern\"}','[]',0,0,0,NULL,'{\"dark\": \"#1d3557\", \"info\": \"#3b82f6\", \"light\": \"#f8f9fa\", \"accent\": \"#f1faee\", \"border\": \"#e5e7eb\", \"danger\": \"#ef4444\", \"nav_bg\": \"#ffffff\", \"primary\": \"#e63946\", \"success\": \"#10b981\", \"warning\": \"#f59e0b\", \"footer_bg\": \"#1d3557\", \"secondary\": \"#457b9d\", \"background\": \"#f8f9fa\", \"text_primary\": \"#111827\", \"button_primary\": \"#e63946\", \"text_secondary\": \"#6b7280\", \"button_secondary\": \"#457b9d\", \"button_primary_hover\": \"#cf333f\", \"button_secondary_hover\": \"#3e6e8d\"}','{\"font_size_base\": \"16px\", \"font_weight_bold\": \"700\", \"font_weight_normal\": \"400\", \"line_height_normal\": \"1.5\", \"font_family_primary\": \"\'Roboto\', sans-serif\", \"font_family_headings\": \"\'Roboto\', sans-serif\", \"font_family_secondary\": \"\'Montserrat\', sans-serif\"}',NULL,'2025-12-07 06:31:23','2025-12-07 06:36:32','2025-12-07 06:36:32'),(5,'RetailPro Elite','retailpro-elite','Premium retail template with advanced filtering, mega menus, and luxury brand aesthetics.',NULL,'1.0.0','Carpathian CMS','',NULL,NULL,'{\"layout\": \"full-width\", \"features\": [\"Mega Menu\", \"Advanced Filters\", \"Luxury Design\", \"VIP Section\"], \"industry\": \"E-commerce\", \"header_style\": \"modern\"}','[]',1,0,0,NULL,'{\"dark\": \"#000000\", \"info\": \"#3b82f6\", \"light\": \"#f5f5f5\", \"accent\": \"#ffffff\", \"border\": \"#e5e7eb\", \"danger\": \"#ef4444\", \"nav_bg\": \"#ffffff\", \"primary\": \"#2d2d2d\", \"success\": \"#10b981\", \"warning\": \"#f59e0b\", \"footer_bg\": \"#000000\", \"secondary\": \"#d4af37\", \"background\": \"#f5f5f5\", \"text_primary\": \"#111827\", \"button_primary\": \"#2d2d2d\", \"text_secondary\": \"#6b7280\", \"button_secondary\": \"#d4af37\", \"button_primary_hover\": \"#282828\", \"button_secondary_hover\": \"#be9d31\"}','{\"font_size_base\": \"16px\", \"font_weight_bold\": \"700\", \"font_weight_normal\": \"400\", \"line_height_normal\": \"1.5\", \"font_family_primary\": \"\'Playfair Display\', sans-serif\", \"font_family_headings\": \"\'Playfair Display\', sans-serif\", \"font_family_secondary\": \"\'Lato\', sans-serif\"}',NULL,'2025-12-07 06:33:14','2025-12-07 06:36:29','2025-12-07 06:36:29'),(6,'Codecraft Pro','codecraft-pro','dark mode code blocks',NULL,'2.0.0','Unknown','',NULL,NULL,'{\"responsive\": true}','[\"home\", \"about\", \"contact\"]',1,0,0,NULL,'{\"primary\": \"#00d9ff\", \"secondary\": \"#8b5cf6\"}','{\"body_font\": \"Inter\", \"heading_font\": \"Space Grotesk\"}',NULL,'2025-12-07 08:09:55','2025-12-07 08:10:41',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','msrusu87@gmail.com',NULL,'$2y$12$/rt/rwKhSbmXSEblLkDb9.sZgGbijGjzZO08o0ahKdHtbzuT.eKZi',NULL,NULL,NULL,'sNt5PKDHs0I08z8JffiALhxJBl7CqjOfGENqjdJwElDTGjo7Z5yNkQgLGWUv','2025-12-07 04:53:54','2025-12-07 04:53:54',NULL);
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
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `settings` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
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
INSERT INTO `widgets` VALUES (1,'Hero Section','hero','{\"heading\":\"Bine ai venit la Web Agency CMS\",\"subheading\":\"CMS profesional cu integrare AI\",\"description\":\"Platforma ta complet\\u0103 pentru crearea \\u0219i gestionarea site-urilor web moderne. Instrumente puternice, design responsive \\u0219i integrare AI.\",\"button_text\":\"\\u00cencepe Acum\",\"button_url\":\"\\/contact\",\"background_image\":\"\\/images\\/hero-bg.jpg\"}','\"{\\\"enabled\\\":true}\"',1,'active','2025-12-07 05:11:38','2025-12-07 05:11:38'),(2,'Features Section','features','{\"heading\":\"Funcționalități Puternice\",\"features\":[{\"icon\":\"🎨\",\"title\":\"Design Modern\",\"description\":\"Interfață intuitivă și design responsive pentru toate dispozitivele\",\"link\":\"\\/posts\\/design-modern-ghid-complet-interfete-intuitive\"},{\"icon\":\"⚡\",\"title\":\"Performanță Ridicată\",\"description\":\"Optimizat pentru viteză și performanță maximă\",\"link\":\"\\/posts\\/performanta-ridicata-optimizare-viteza-maxima\"},{\"icon\":\"🤖\",\"title\":\"Integrare AI\",\"description\":\"Utilizează inteligența artificială pentru automatizare\",\"link\":\"\\/posts\\/integrare-ai-inteligenta-artificiala-cms\"},{\"icon\":\"🔒\",\"title\":\"Securitate\",\"description\":\"Protecție avansată și backup automat\",\"link\":\"\\/posts\\/securitate-avansata-protectie-date-cms\"},{\"icon\":\"📱\",\"title\":\"Multi-Platform\",\"description\":\"Funcționează perfect pe desktop, tabletă și mobil\",\"link\":\"\\/posts\\/multi-platform-functionare-orice-dispozitiv\"},{\"icon\":\"🔧\",\"title\":\"Personalizabil\",\"description\":\"Configurează totul conform nevoilor tale\",\"link\":\"\\/posts\\/personalizare-completa-configurare-cms\"}]}','\"{\\\"enabled\\\":true}\"',2,'active','2025-12-07 05:11:38','2025-12-08 07:26:19'),(3,'Products Section','products','{\"heading\":\"Products Showcase\",\"subheading\":\"Descoper\\u0103 solu\\u021biile noastre\",\"limit\":6}','\"{\\\"enabled\\\":true}\"',3,'active','2025-12-07 05:11:38','2025-12-07 05:11:38'),(4,'Blog Section','blog','{\"heading\":\"Ultimele Articole\",\"subheading\":\"R\\u0103m\\u00e2i la curent cu cele mai noi articole\",\"limit\":3}','\"{\\\"enabled\\\":true}\"',4,'active','2025-12-07 05:11:38','2025-12-07 05:11:38'),(5,'Footer Section','footer','{\"company_name\":\"Carphatian Web Agency\",\"description\":\"CMS profesional pentru afaceri moderne\",\"copyright\":\"\\u00a9 2025 Carphatian. Toate drepturile rezervate.\",\"social_links\":[{\"platform\":\"facebook\",\"url\":\"https:\\/\\/facebook.com\"},{\"platform\":\"twitter\",\"url\":\"https:\\/\\/twitter.com\"},{\"platform\":\"linkedin\",\"url\":\"https:\\/\\/linkedin.com\"},{\"platform\":\"instagram\",\"url\":\"https:\\/\\/instagram.com\"}]}','\"{\\\"enabled\\\":true}\"',5,'active','2025-12-07 05:11:38','2025-12-07 05:11:38');
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-11  4:03:38
