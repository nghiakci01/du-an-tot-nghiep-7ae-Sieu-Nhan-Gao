-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: du_an_tot_nghiep
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint unsigned NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'updated','App\\Models\\Product',9,'{\"price\": \"149000.00\", \"updated_at\": \"2026-03-05T17:23:53.000000Z\"}','{\"price\": 0, \"updated_at\": \"2026-03-10 19:30:49\"}','http://127.0.0.1:8000/admin/products/9','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','2026-03-10 12:30:49','2026-03-10 12:30:49'),(2,1,'updated','App\\Models\\Product',9,'{\"price\": \"0.00\"}','{\"price\": \"149000.00\"}','http://127.0.0.1:8000/admin/products/9','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','2026-03-10 12:30:49','2026-03-10 12:30:49'),(3,1,'created','App\\Models\\Order',13,NULL,'{\"id\": 13, \"name\": \"Bằng Nguyễn Công\", \"note\": null, \"email\": \"bang@gmail.com\", \"phone\": \"0359756805\", \"status\": \"pending\", \"address\": \"Đường Phan Trọng Tuệ, Phường Thanh Liệt\", \"user_id\": 1, \"province\": \"Hà Nội\", \"created_at\": \"2026-03-10 19:45:09\", \"updated_at\": \"2026-03-10 19:45:09\", \"coupon_code\": null, \"final_total\": 171000, \"total_price\": 149000, \"shipping_fee\": \"22000\", \"payment_method\": \"COD\", \"discount_amount\": 0, \"shipping_address\": \"Đường Phan Trọng Tuệ, Phường Thanh Liệt, Hà Nội - 0359756805 - Bằng Nguyễn Công\", \"shipping_provider\": \"ghn\", \"shipping_service_name\": \"Giao Hàng Nhanh (Chuẩn)\"}','http://127.0.0.1:8000/checkout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','2026-03-10 12:45:09','2026-03-10 12:45:09');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_settings`
--

DROP TABLE IF EXISTS `bank_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_settings`
--

LOCK TABLES `bank_settings` WRITE;
/*!40000 ALTER TABLE `bank_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'slider',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
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
INSERT INTO `cache` VALUES ('elite-cache-chatbot_setting_ai_provider','s:6:\"gemini\";',1772737520),('elite-cache-chatbot_setting_chatbot_enabled','s:1:\"1\";',1773672883),('elite-cache-chatbot_setting_chatbot_mode','s:2:\"ai\";',1773672883),('elite-cache-chatbot_setting_email','s:14:\"bang@gmail.com\";',1772737520),('elite-cache-chatbot_setting_gemini_api_key','s:8:\"12345678\";',1772737520),('elite-cache-chatbot_setting_hotline','s:10:\"0359756805\";',1772737520),('elite-cache-chatbot_setting_system_instruction','s:76:\"đóng vai một người tư vấn/ hỗ trợ khách hàng mua đồ dùng\";',1772737520),('elite-cache-chatbot_suggested_questions','a:0:{}',1773672883),('elite-cache-global_settings','a:15:{s:9:\"bank_name\";s:34:\"MB Bank (Ngân hàng Quân Đội)\";s:19:\"bank_account_number\";s:10:\"0359756805\";s:17:\"bank_account_name\";s:16:\"NGUYEN CONG BANG\";s:7:\"bank_id\";s:2:\"MB\";s:13:\"store_address\";s:75:\"Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam\";s:11:\"store_phone\";s:10:\"0354869999\";s:11:\"store_email\";s:15:\"Elite@gmail.com\";s:16:\"store_map_iframe\";s:352:\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.6575765790473!2d105.71077797584149!3d21.04638368717544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134546536093551%3A0x673199834278e993!2sNg.%2091%20Lai%20X%C3%A1%2C%20Kim%20Chung%2C%20Ho%C3%A0i%20%C4%90%E1%BB%A9c%2C%20H%C3%A0%20N%E1%BB%99i!5e0!3m2!1svi!2s!4v1710000000000!5m2!1svi!2s\";s:10:\"site_title\";s:18:\"Sieu Nhan Gao Shop\";s:12:\"shipping_fee\";s:5:\"30000\";s:10:\"site_email\";s:15:\"Elite@gmail.com\";s:10:\"site_phone\";s:10:\"0354869999\";s:12:\"site_address\";s:75:\"Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam\";s:15:\"social_facebook\";s:1:\"#\";s:16:\"social_instagram\";s:1:\"#\";}',1773672883),('laravel-cache-chatbot_setting_chatbot_enabled','s:1:\"0\";',1770824474),('laravel-cache-chatbot_setting_chatbot_mode','s:5:\"rules\";',1770824474),('laravel-cache-chatbot_suggested_questions','a:0:{}',1770824474);
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
-- Table structure for table `cart_abandonments`
--

DROP TABLE IF EXISTS `cart_abandonments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_abandonments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_data` json NOT NULL,
  `cart_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `item_count` int NOT NULL DEFAULT '0',
  `status` enum('abandoned','recovered','converted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'abandoned',
  `abandoned_at` timestamp NOT NULL,
  `recovered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_abandonments_user_id_foreign` (`user_id`),
  KEY `cart_abandonments_session_id_index` (`session_id`),
  CONSTRAINT `cart_abandonments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_abandonments`
--

LOCK TABLES `cart_abandonments` WRITE;
/*!40000 ALTER TABLE `cart_abandonments` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_abandonments` ENABLE KEYS */;
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
  `parent_id` bigint unsigned DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (7,'Áo','ao',NULL,NULL,'2026-03-05 17:15:53','2026-03-05 17:15:53'),(8,'Áo Nỉ','ao-ni',7,NULL,'2026-03-05 17:16:03','2026-03-05 17:16:03'),(9,'Áo Phông','ao-phong',7,NULL,'2026-03-05 17:33:40','2026-03-05 17:33:40'),(10,'Quần','quan',NULL,NULL,'2026-03-05 17:39:54','2026-03-05 17:39:54'),(11,'Quần dài','quan-dai',10,NULL,'2026-03-05 17:40:07','2026-03-05 17:40:07'),(12,'Quần jeans','quan-jeans',10,NULL,'2026-03-05 17:44:02','2026-03-05 17:44:02'),(13,'Dép','dep',NULL,NULL,'2026-03-05 17:50:02','2026-03-05 17:50:02'),(14,'Dây Lưng','day-lung',NULL,NULL,'2026-03-05 17:52:30','2026-03-05 17:52:30'),(15,'Tất','tat',NULL,NULL,'2026-03-05 17:55:43','2026-03-05 17:55:43');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci,
  `sender_type` enum('user','bot','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_user_id_foreign` (`user_id`),
  KEY `chat_messages_session_id_index` (`session_id`),
  CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` VALUES (1,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',1,'mua quần ở đâu',NULL,'user',1,'2026-03-05 18:05:20','2026-03-05 18:08:29',NULL),(2,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',1,'Có lỗi khi kết nối với AI (Gemini). Vui lòng thử lại sau! 🤖','{\"products\":[]}','bot',0,'2026-03-05 18:05:23','2026-03-05 18:05:23',NULL),(3,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',1,'chào cậu',NULL,'user',1,'2026-03-05 18:06:07','2026-03-05 18:08:29',NULL),(4,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',1,'Có lỗi khi kết nối với AI (Gemini). Vui lòng thử lại sau! 🤖','{\"products\":[]}','bot',0,'2026-03-05 18:06:08','2026-03-05 18:06:08',NULL),(5,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',1,'chào chị',NULL,'staff',1,'2026-03-05 18:08:03','2026-03-05 18:08:03',NULL),(6,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',1,'hello',NULL,'user',1,'2026-03-05 18:08:14','2026-03-05 18:08:29',NULL),(7,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',1,'dạ e nghe',NULL,'staff',1,'2026-03-05 18:08:28','2026-03-05 18:08:28',NULL);
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_sessions`
--

DROP TABLE IF EXISTS `chat_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_bot_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_sessions_session_id_unique` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_sessions`
--

LOCK TABLES `chat_sessions` WRITE;
/*!40000 ALTER TABLE `chat_sessions` DISABLE KEYS */;
INSERT INTO `chat_sessions` VALUES (1,'9cuKo150bMNBqgRc0MT3HetbDvAOT7L3pjAzdWuI',0,'2026-03-05 18:08:28','2026-03-05 18:07:45','2026-03-05 18:08:28');
/*!40000 ALTER TABLE `chat_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatbot_settings`
--

DROP TABLE IF EXISTS `chatbot_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatbot_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chatbot_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatbot_settings`
--

LOCK TABLES `chatbot_settings` WRITE;
/*!40000 ALTER TABLE `chatbot_settings` DISABLE KEYS */;
INSERT INTO `chatbot_settings` VALUES (1,'chatbot_mode','ai','2026-02-04 04:18:32','2026-03-05 18:04:58'),(2,'greeting_message','Kính chào khách hàng','2026-02-04 04:18:32','2026-03-05 18:04:58'),(3,'fallback_message','Tôi chưa hiểu điều bạn nói','2026-02-04 04:18:32','2026-03-05 18:04:58'),(4,'hotline','0359756805','2026-02-04 04:18:32','2026-03-05 18:02:17'),(5,'email','bang@gmail.com','2026-02-04 04:18:32','2026-03-05 18:02:17'),(6,'ai_provider','gemini','2026-02-04 04:18:32','2026-02-04 04:18:32'),(7,'system_instruction','đóng vai một người tư vấn/ hỗ trợ khách hàng mua đồ dùng','2026-02-04 04:18:32','2026-03-05 18:04:58'),(8,'gemini_api_key','12345678','2026-02-04 04:18:32','2026-03-05 18:02:17'),(9,'openai_api_key','','2026-02-04 04:18:32','2026-02-04 04:18:32'),(10,'keyword_rules','','2026-03-05 18:02:17','2026-03-05 18:02:17'),(11,'chatbot_enabled','1','2026-03-05 18:02:17','2026-03-05 18:04:58');
/*!40000 ALTER TABLE `chatbot_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatbot_suggested_questions`
--

DROP TABLE IF EXISTS `chatbot_suggested_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatbot_suggested_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatbot_suggested_questions`
--

LOCK TABLES `chatbot_suggested_questions` WRITE;
/*!40000 ALTER TABLE `chatbot_suggested_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `chatbot_suggested_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `colors_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'Red','#FF0000',0,1,NULL,NULL),(2,'black','#000000',0,1,'2026-02-04 09:16:55','2026-02-04 09:16:55'),(3,'blue','#000000',0,1,'2026-02-25 16:33:31','2026-02-25 16:33:31'),(4,'be','#000000',0,1,'2026-03-05 17:24:21','2026-03-05 17:24:21'),(5,'white','#000000',0,1,'2026-03-05 17:24:43','2026-03-05 17:24:43'),(6,'brown','#000000',0,1,'2026-03-05 17:26:39','2026-03-05 17:26:39');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply_message` text COLLATE utf8mb4_unicode_ci,
  `replied_at` timestamp NULL DEFAULT NULL,
  `status` enum('unread','read','replied') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `contact_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,NULL,'bang','bang2005@gmail.com','ko','123',NULL,NULL,'unread','2026-02-04 09:03:05','2026-02-04 09:03:05'),(2,NULL,'bang','bang2005@gmail.com','ko','123',NULL,NULL,'unread','2026-02-04 09:03:06','2026-02-04 09:03:06'),(3,NULL,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','trang web rất mượt mà, mua đồ vô cùng nhanh chóng',NULL,NULL,'unread','2026-02-23 17:00:08','2026-02-23 17:00:08'),(4,NULL,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','trang web rất mượt mà, mua đồ vô cùng nhanh chóng',NULL,NULL,'unread','2026-02-23 17:00:32','2026-02-23 17:00:32'),(5,NULL,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','trang web rất mượt mà, mua đồ vô cùng nhanh chóng',NULL,NULL,'unread','2026-02-23 17:00:37','2026-02-23 17:00:37'),(6,NULL,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','trang web rất mượt mà, mua đồ vô cùng nhanh chóng',NULL,NULL,'unread','2026-02-23 17:00:42','2026-02-23 17:00:42'),(7,NULL,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','trang web rất mượt mà, mua đồ vô cùng nhanh chóng',NULL,NULL,'unread','2026-02-23 17:00:48','2026-02-23 17:00:48'),(8,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:09:44','2026-02-23 17:09:44'),(9,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:09:48','2026-02-23 17:09:48'),(10,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:09:51','2026-02-23 17:09:51'),(11,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:09:55','2026-02-23 17:09:55'),(12,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:09:59','2026-02-23 17:09:59'),(13,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:10:02','2026-02-23 17:10:02'),(14,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:10:07','2026-02-23 17:10:07'),(15,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:10:12','2026-02-23 17:10:12'),(16,1,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:10:16','2026-02-23 17:10:16'),(17,3,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:12:06','2026-02-23 17:12:06'),(18,3,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:12:10','2026-02-23 17:12:10'),(19,3,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:12:14','2026-02-23 17:12:14'),(20,3,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:12:17','2026-02-23 17:12:17'),(21,3,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111',NULL,NULL,'unread','2026-02-23 17:12:20','2026-02-23 17:12:20'),(23,3,'nguyen cong bang','nguyencongbang20042005@gmail.com','ko','111','ok ạ','2026-02-23 17:16:56','replied','2026-02-23 17:15:49','2026-02-23 17:16:56');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_user_id_foreign` (`user_id`),
  CONSTRAINT `coupons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'123456','fixed',50000.00,100000.00,NULL,78,3,'2026-02-23 23:50:00','2026-02-26 15:50:00',1,NULL,'2026-02-23 16:50:38','2026-02-26 15:07:31',NULL),(2,'WELCOME-FCKG7K8M','fixed',100000.00,1000000.00,NULL,1,1,NULL,NULL,1,'Mã giảm giá chào mừng thành viên mới (Giảm 100k cho đơn hàng từ 1M)','2026-02-26 14:34:14','2026-02-26 14:49:44',4),(3,'421412','percentage',10.00,100000.00,200000.00,55,0,'2026-02-26 21:59:00','2026-03-09 21:59:00',1,'thưởng tết','2026-02-26 15:00:06','2026-02-26 15:00:06',NULL),(4,'1234569','fixed',50000.00,100000.00,NULL,78,2,NULL,NULL,1,NULL,'2026-02-26 15:08:49','2026-03-05 18:11:44',NULL);
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_tiers`
--

DROP TABLE IF EXISTS `customer_tiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_tiers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_tiers`
--

LOCK TABLES `customer_tiers` WRITE;
/*!40000 ALTER TABLE `customer_tiers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_tiers` ENABLE KEYS */;
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
-- Table structure for table `inventory_voucher_details`
--

DROP TABLE IF EXISTS `inventory_voucher_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_voucher_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_voucher_id` bigint unsigned NOT NULL,
  `product_variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_voucher_details_inventory_voucher_id_foreign` (`inventory_voucher_id`),
  KEY `inventory_voucher_details_product_variant_id_foreign` (`product_variant_id`),
  CONSTRAINT `inventory_voucher_details_inventory_voucher_id_foreign` FOREIGN KEY (`inventory_voucher_id`) REFERENCES `inventory_vouchers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_voucher_details_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_voucher_details`
--

LOCK TABLES `inventory_voucher_details` WRITE;
/*!40000 ALTER TABLE `inventory_voucher_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_voucher_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_vouchers`
--

DROP TABLE IF EXISTS `inventory_vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('INBOUND','OUTBOUND') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INBOUND',
  `warehouse_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `voucher_date` timestamp NOT NULL,
  `status` enum('PENDING','COMPLETED','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_vouchers_voucher_code_unique` (`voucher_code`),
  KEY `inventory_vouchers_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_vouchers_supplier_id_foreign` (`supplier_id`),
  KEY `inventory_vouchers_user_id_foreign` (`user_id`),
  CONSTRAINT `inventory_vouchers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_vouchers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_vouchers_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_vouchers`
--

LOCK TABLES `inventory_vouchers` WRITE;
/*!40000 ALTER TABLE `inventory_vouchers` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_vouchers` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"b60f3d70-31f7-4566-802a-a23bd1d9b98b\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:14:\\\"bang@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1772039665,\"delay\":null}',0,NULL,1772039665,1772039665),(2,'default','{\"uuid\":\"9e6c1228-f278-4378-93bf-aa107bec43f0\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"nguyen1@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1772117390,\"delay\":null}',0,NULL,1772117390,1772117390),(3,'default','{\"uuid\":\"48c81e9e-ef90-47b0-b3a2-4d115f10f591\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:14:\\\"bang@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1772733562,\"delay\":null}',0,NULL,1772733563,1772733563),(4,'default','{\"uuid\":\"b8733c0f-3c9f-4292-bab2-cb60e37c17b4\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:14:\\\"bang@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1772734304,\"delay\":null}',0,NULL,1772734304,1772734304),(5,'default','{\"uuid\":\"bd991a28-8a21-4638-a9f3-151118e54032\",\"displayName\":\"App\\\\Events\\\\CartUpdatedEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:27:\\\"App\\\\Events\\\\CartUpdatedEvent\\\":3:{s:9:\\\"cartCount\\\";i:1;s:9:\\\"sessionId\\\";s:40:\\\"BKdnVwBxZ0DnRH6IMvhFKG38uebgS3sQ8ixs6G2V\\\";s:6:\\\"userId\\\";i:1;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1773145799,\"delay\":null}',0,NULL,1773145799,1773145799),(6,'default','{\"uuid\":\"234aab74-0d88-4b2c-a001-b38877c9ec3f\",\"displayName\":\"App\\\\Events\\\\CartUpdatedEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:27:\\\"App\\\\Events\\\\CartUpdatedEvent\\\":3:{s:9:\\\"cartCount\\\";i:2;s:9:\\\"sessionId\\\";s:40:\\\"BKdnVwBxZ0DnRH6IMvhFKG38uebgS3sQ8ixs6G2V\\\";s:6:\\\"userId\\\";i:1;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1773146634,\"delay\":null}',0,NULL,1773146634,1773146634),(7,'default','{\"uuid\":\"55349357-5bc9-4fa7-808e-b6dc3faf8dc7\",\"displayName\":\"App\\\\Events\\\\CartUpdatedEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:27:\\\"App\\\\Events\\\\CartUpdatedEvent\\\":3:{s:9:\\\"cartCount\\\";i:1;s:9:\\\"sessionId\\\";s:40:\\\"BKdnVwBxZ0DnRH6IMvhFKG38uebgS3sQ8ixs6G2V\\\";s:6:\\\"userId\\\";i:1;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1773146661,\"delay\":null}',0,NULL,1773146661,1773146661),(8,'default','{\"uuid\":\"6df6a0b0-71ce-48dc-8149-9ffe591aaae7\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:14:\\\"bang@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1773146711,\"delay\":null}',0,NULL,1773146711,1773146711),(9,'default','{\"uuid\":\"c8dcaae0-d1b8-4f1b-9a45-5bf99f064a15\",\"displayName\":\"App\\\\Events\\\\CartUpdatedEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:27:\\\"App\\\\Events\\\\CartUpdatedEvent\\\":3:{s:9:\\\"cartCount\\\";i:1;s:9:\\\"sessionId\\\";s:40:\\\"BKdnVwBxZ0DnRH6IMvhFKG38uebgS3sQ8ixs6G2V\\\";s:6:\\\"userId\\\";i:1;}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1773154220,\"delay\":null}',0,NULL,1773154221,1773154221);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_points`
--

DROP TABLE IF EXISTS `loyalty_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_points` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `points` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loyalty_points_user_id_foreign` (`user_id`),
  KEY `loyalty_points_order_id_foreign` (`order_id`),
  CONSTRAINT `loyalty_points_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `loyalty_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_points`
--

LOCK TABLES `loyalty_points` WRITE;
/*!40000 ALTER TABLE `loyalty_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `loyalty_points` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_28_000001_create_categories_table',1),(5,'2026_01_28_000002_create_products_table',1),(6,'2026_01_28_000003_create_product_variants_table',1),(7,'2026_01_28_000004_create_orders_table',1),(8,'2026_01_28_000005_create_order_items_table',1),(9,'2026_01_28_155127_add_staff_role_to_users_table',2),(10,'2026_01_29_160221_create_reviews_table',3),(11,'2026_01_29_160225_add_short_description_to_products_table',3),(12,'2026_01_30_012602_add_is_featured_to_products_table',3),(13,'2026_01_30_035521_create_product_images_table',3),(14,'2026_01_31_065823_create_chatbot_settings_table',3),(15,'2026_01_31_065825_create_chat_messages_table',3),(16,'2026_01_31_165344_add_soft_deletes_to_chat_messages_table',3),(17,'2026_01_31_171501_add_payload_to_chat_messages_table',3),(18,'2026_01_31_174533_create_chat_sessions_table',3),(19,'2026_02_01_042536_create_chatbot_suggested_questions_table',3),(20,'2026_02_03_150540_create_sizes_table',4),(21,'2026_02_03_150543_create_colors_table',5),(22,'2026_02_03_150546_add_size_color_ids_to_product_variants_table',6),(23,'2026_02_03_152948_add_price_fields_to_product_variants_table',7),(24,'2026_02_03_153544_add_sale_price_to_products_table',8),(25,'2026_02_04_155556_create_contact_messages_table',8),(26,'2026_02_08_170010_create_banners_table',9),(27,'2026_02_03_155530_make_user_id_nullable_in_orders_table',10),(28,'2026_02_05_151641_create_social_accounts_table',10),(29,'2026_02_05_154850_add_avatar_to_users_table',10),(30,'2026_02_06_153228_create_settings_table',10),(31,'2026_02_08_082720_add_missing_columns_to_products_table',10),(32,'2026_02_08_165011_create_wishlists_table',10),(33,'2026_02_11_030000_create_coupons_table',11),(34,'2026_02_12_204832_add_coupon_fields_to_orders_table',11),(35,'2026_02_13_145735_update_status_enum_in_orders_table',11),(36,'2026_02_13_145744_create_order_histories_table',11),(37,'2026_02_23_000001_create_suppliers_table',11),(38,'2026_02_23_000002_create_warehouses_table',11),(39,'2026_02_23_000003_create_inventory_vouchers_table',11),(40,'2026_02_23_000004_create_inventory_voucher_details_table',11),(41,'2026_02_23_000005_create_warehouse_stocks_table',11),(42,'2026_02_23_000006_add_alert_threshold_to_product_variants_table',11),(43,'2026_02_23_130920_create_inventory_voucher_details_table',11),(44,'2026_02_23_130920_create_inventory_vouchers_table',11),(45,'2026_02_23_130921_add_alert_threshold_to_product_variants_table',11),(46,'2026_02_23_130921_create_warehouse_stocks_table',11),(47,'2026_02_24_000745_add_user_id_and_reply_fields_to_contact_messages_table',12),(48,'2026_02_24_010000_create_brands_table_and_add_to_products',13),(49,'2026_02_24_010001_create_tags_table',13),(50,'2026_02_24_010548_add_shipping_fee_to_orders_table',14),(51,'2026_02_24_100708_add_payment_fields_to_orders_table',15),(52,'2026_02_25_000544_add_customer_info_to_orders_table',15),(53,'2026_02_26_213043_add_user_id_to_coupons_table',16),(54,'2026_03_06_001019_add_soft_deletes_to_products_and_variants',17),(55,'2026_02_28_125402_create_loyalty_points_table',18),(56,'2026_02_28_125402_create_post_categories_table',18),(57,'2026_02_28_125402_create_posts_table',18),(58,'2026_03_02_220648_create_reward_points_table',18),(59,'2026_03_02_220649_create_reward_point_histories_table',18),(60,'2026_03_02_220650_create_customer_tiers_table',18),(61,'2026_03_02_220651_create_promotions_table',18),(62,'2026_03_02_233210_add_reward_points_to_users_and_orders_table',18),(63,'2026_03_03_145131_add_shipping_provider_to_orders_table',18),(64,'2026_03_03_154747_create_audit_logs_table',18),(65,'2026_03_03_155023_add_cost_price_to_product_variants_and_order_items',18),(66,'2026_03_03_165329_create_notifications_table',18),(67,'2026_03_04_223718_create_bank_settings_table',18),(68,'2026_03_06_132932_drop_warehouse_tables',18),(69,'2026_03_06_162732_add_consultation_text_to_ai_model_previews_table',18),(70,'2026_03_07_115752_add_sale_dates_to_products_table',18),(71,'2026_03_07_120500_create_cart_abandonments_table',18);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
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
INSERT INTO `notifications` VALUES ('b3926903-ec9e-40c1-a8f2-3f1fa23cb7f6','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"type\":\"new_order\",\"order_id\":13,\"customer_name\":\"B\\u1eb1ng Nguy\\u1ec5n C\\u00f4ng\",\"total_price\":171000,\"message\":\"B\\u1ea1n c\\u00f3 m\\u1ed9t \\u0111\\u01a1n h\\u00e0ng m\\u1edbi t\\u1eeb B\\u1eb1ng Nguy\\u1ec5n C\\u00f4ng\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/13\"}',NULL,'2026-03-10 12:45:10','2026-03-10 12:45:10');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_histories`
--

DROP TABLE IF EXISTS `order_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `previous_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_histories_order_id_foreign` (`order_id`),
  KEY `order_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_histories`
--

LOCK TABLES `order_histories` WRITE;
/*!40000 ALTER TABLE `order_histories` DISABLE KEYS */;
INSERT INTO `order_histories` VALUES (13,11,1,'pending','confirmed',NULL,'2026-03-05 17:59:49','2026-03-05 17:59:49'),(14,11,1,'confirmed','shipped',NULL,'2026-03-05 17:59:55','2026-03-05 17:59:55'),(15,12,1,'pending','cancelled','Customer cancelled order themselves','2026-03-05 18:13:17','2026-03-05 18:13:17');
/*!40000 ALTER TABLE `order_histories` ENABLE KEYS */;
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
  `variant_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (15,11,9,11,20,149000.00,0.00,'2026-03-05 17:59:20','2026-03-05 17:59:20'),(16,12,17,75,4,19000.00,0.00,'2026-03-05 18:11:44','2026-03-05 18:11:44'),(17,12,17,76,2,19000.00,0.00,'2026-03-05 18:11:44','2026-03-05 18:11:44'),(18,13,9,16,1,149000.00,149000.00,'2026-03-10 12:45:09','2026-03-10 12:45:09');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','shipped','completed','cancelled','failed','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_price` decimal(12,2) NOT NULL,
  `coupon_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `shipping_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (11,1,'Bằng Nguyễn Công','bang@gmail.com','0359756805','Hà Nội','Đường Phan Trọng Tuệ, Phường Thanh Liệt','shipped',2980000.00,'1234569',50000.00,0.00,NULL,NULL,2930000.00,'COD','pending',NULL,'Đường Phan Trọng Tuệ, Phường Thanh Liệt, Hà Nội - 0359756805 - Bằng Nguyễn Công',NULL,'2026-03-05 17:59:20','2026-03-05 17:59:55'),(12,1,'Bằng Nguyễn Công','bang@gmail.com','0359756805','Hà Nội','Đường Phan Trọng Tuệ, Phường Thanh Liệt','cancelled',114000.00,'1234569',50000.00,30000.00,NULL,NULL,94000.00,'BANK_TRANSFER','pending',NULL,'Đường Phan Trọng Tuệ, Phường Thanh Liệt, Hà Nội - 0359756805 - Bằng Nguyễn Công','123','2026-03-05 18:11:44','2026-03-05 18:13:17'),(13,1,'Bằng Nguyễn Công','bang@gmail.com','0359756805','Hà Nội','Đường Phan Trọng Tuệ, Phường Thanh Liệt','pending',149000.00,NULL,0.00,22000.00,'ghn','Giao Hàng Nhanh (Chuẩn)',171000.00,'COD','pending',NULL,'Đường Phan Trọng Tuệ, Phường Thanh Liệt, Hà Nội - 0359756805 - Bằng Nguyễn Công',NULL,'2026-03-10 12:45:09','2026-03-10 12:45:09');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
-- Table structure for table `post_categories`
--

DROP TABLE IF EXISTS `post_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_categories`
--

LOCK TABLES `post_categories` WRITE;
/*!40000 ALTER TABLE `post_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_category_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_post_category_id_foreign` (`post_category_id`),
  CONSTRAINT `posts_post_category_id_foreign` FOREIGN KEY (`post_category_id`) REFERENCES `post_categories` (`id`) ON DELETE CASCADE
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
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (3,9,'products/gallery/JJsXgBDrB87EcvT6upN6ruVIiV9JjuDk2mDoQbBT.jpg',0,'2026-03-05 17:23:13','2026-03-05 17:23:13'),(4,10,'products/gallery/LMvvHMvZCQvSyAALZ9rIDpDfunP5K9mc27Nu7vM8.jpg',0,'2026-03-05 17:28:54','2026-03-05 17:28:54'),(5,10,'products/gallery/krKDSMFjtIuVzSNjs1s0qTvLHfrQwLEmuMja3iFA.jpg',1,'2026-03-05 17:28:54','2026-03-05 17:28:54'),(6,10,'products/gallery/wzXroA6a9wgBDi17fllXeXkMOZLsLrj3vpo1uFpD.jpg',2,'2026-03-05 17:28:54','2026-03-05 17:28:54'),(7,11,'products/gallery/xHgDGoi07ez548syESpGZYwfRxKN4Lwxcsk5AGvy.png',0,'2026-03-05 17:39:17','2026-03-05 17:39:17'),(8,11,'products/gallery/SVGWJEEj03rSl5zg2bUag2qIJ7tTlm3u2aQv1DlX.png',1,'2026-03-05 17:39:17','2026-03-05 17:39:17'),(9,11,'products/gallery/Fez7ig1WjGwzPYj8z6dqzwbRnCJtFbGESS7KmHLf.png',2,'2026-03-05 17:39:17','2026-03-05 17:39:17'),(10,12,'products/gallery/m9eP0DwRJ6atxTPBQnZL8qQ58kr4KStwd9pcIqn4.jpg',0,'2026-03-05 17:43:09','2026-03-05 17:43:09'),(11,12,'products/gallery/pQEtEA6itbdwKiAKWJ1MxGAPX8GQhWNcN7SWlMxY.jpg',1,'2026-03-05 17:43:09','2026-03-05 17:43:09'),(12,12,'products/gallery/xqKohqdSFWJzT8pgbjrCj9CNVwMXp00fOBcShjEJ.jpg',2,'2026-03-05 17:43:09','2026-03-05 17:43:09'),(13,12,'products/gallery/QAOY6zVUPInCvH0GnlQSDvn38CVMW3sCuQDQiCfx.jpg',3,'2026-03-05 17:43:09','2026-03-05 17:43:09'),(14,13,'products/gallery/wJyajztoc0jr8r7QHK0TavOCfgb4uppQI2ajTV0q.jpg',0,'2026-03-05 17:45:33','2026-03-05 17:45:33'),(15,13,'products/gallery/n891NTd8WvwBYKoanGsineZQxCGswE72ioF0Xi3F.jpg',1,'2026-03-05 17:45:33','2026-03-05 17:45:33'),(16,14,'products/gallery/YIq2VrJrLy0clgrWEnyFm1PP6e07k2g2zVqUjVfk.jpg',0,'2026-03-05 17:47:59','2026-03-05 17:47:59'),(17,14,'products/gallery/2WN6rFKytU2EoxyqrIMjMBDkIGlU8B0uQaUXNN3f.jpg',1,'2026-03-05 17:47:59','2026-03-05 17:47:59'),(18,15,'products/gallery/kC9Psp7i68XLW6heb8HMZr1GLup3Ki4Ptg4711n5.jpg',0,'2026-03-05 17:51:54','2026-03-05 17:51:54'),(19,15,'products/gallery/PZC55QZe3HHiNZOobpkmlnEOVAm1advMWFDMWsJr.jpg',1,'2026-03-05 17:51:54','2026-03-05 17:51:54'),(20,15,'products/gallery/vfk9gWkefvuIyjxoPOYy4kiAoWcm9XlHFdiEDmHn.jpg',2,'2026-03-05 17:51:55','2026-03-05 17:51:55'),(21,15,'products/gallery/XBdK5cJi9w3qKujkfzrL5zCuWHyS1hcCg0Jgji8S.jpg',3,'2026-03-05 17:51:55','2026-03-05 17:51:55'),(22,16,'products/gallery/kjOdvz1ZC45QUBXz6vpTGJMQMZyu0AbsbYVquyL8.jpg',0,'2026-03-05 17:54:09','2026-03-05 17:54:09'),(23,16,'products/gallery/UbONjIefR9cCvkIY3dHwPqU4p5V4f9AEgo4Andun.jpg',1,'2026-03-05 17:54:09','2026-03-05 17:54:09'),(24,16,'products/gallery/eds05dgycMuP1VVRpkYcNGAQgzgMSSJmP4zifI7u.jpg',2,'2026-03-05 17:54:09','2026-03-05 17:54:09'),(25,17,'products/gallery/kOaKzXbP8J4LkhWGo06VUUgZpKrYajScIeh2VziC.jpg',0,'2026-03-05 17:56:42','2026-03-05 17:56:42'),(26,17,'products/gallery/CxiNk1ivgs4PJDCn3nqCFRAOC5bKqAQ1OZiRINOF.jpg',1,'2026-03-05 17:56:42','2026-03-05 17:56:42'),(27,17,'products/gallery/G53LEDf8hbkJr5zhHxuoMqgsJuULKvLs7mPRvjV8.jpg',2,'2026-03-05 17:56:42','2026-03-05 17:56:42'),(28,17,'products/gallery/83lSiidNlBpQBxqFJ80lOg2HZoMJ302KFdqsT0UI.jpg',3,'2026-03-05 17:56:42','2026-03-05 17:56:42');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_tag`
--

DROP TABLE IF EXISTS `product_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_tag` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_tag_product_id_foreign` (`product_id`),
  KEY `product_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_tag`
--

LOCK TABLES `product_tag` WRITE;
/*!40000 ALTER TABLE `product_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_tag` ENABLE KEYS */;
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
  `size_id` bigint unsigned DEFAULT NULL,
  `color_id` bigint unsigned DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `cost_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(15,2) DEFAULT NULL,
  `size` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `alert_threshold` int NOT NULL DEFAULT '10',
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  KEY `product_variants_size_id_foreign` (`size_id`),
  KEY `product_variants_color_id_foreign` (`color_id`),
  CONSTRAINT `product_variants_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_variants_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (11,9,2,1,149000.00,0.00,NULL,'L','Red',0,10,'NILRED','2026-03-05 17:23:12','2026-03-10 12:30:49',NULL),(12,9,1,1,149000.00,0.00,NULL,'M','Red',50,10,'NIMRED','2026-03-05 17:23:12','2026-03-05 17:23:12',NULL),(13,9,3,1,149000.00,0.00,NULL,'XL','Red',50,10,'NIXLRED','2026-03-05 17:23:12','2026-03-05 17:23:12',NULL),(14,9,4,1,149000.00,0.00,NULL,'2XL','Red',50,10,'NIXXLRED','2026-03-05 17:23:12','2026-03-05 17:23:12',NULL),(15,9,2,2,149000.00,0.00,NULL,'L','black',50,10,'AO-NI-FITTED-L47862-L-BLACK-69A9BC00DF399','2026-03-05 17:23:12','2026-03-05 17:23:12',NULL),(16,9,1,2,149000.00,0.00,NULL,'M','black',49,10,'AO-NI-FITTED-L47862-M-BLACK-69A9BC00DFC57','2026-03-05 17:23:12','2026-03-10 12:45:09',NULL),(17,9,3,2,149000.00,0.00,NULL,'XL','black',50,10,'AO-NI-FITTED-L47862-XL-BLACK-69A9BC00E0756','2026-03-05 17:23:12','2026-03-05 17:23:12',NULL),(18,9,4,2,149000.00,0.00,NULL,'2XL','black',50,10,'AO-NI-FITTED-L47862-2XL-BLACK-69A9BC00E1158','2026-03-05 17:23:12','2026-03-05 17:23:12',NULL),(19,10,2,2,229000.00,0.00,NULL,'L','black',40,10,'AO-NI-FITTED-L37828-L-BLACK-69A9BD56E7A3C','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(20,10,1,2,229000.00,0.00,NULL,'M','black',40,10,'AO-NI-FITTED-L37828-M-BLACK-69A9BD56E8EF9','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(21,10,3,2,229000.00,0.00,NULL,'XL','black',40,10,'AO-NI-FITTED-L37828-XL-BLACK-69A9BD56E96EB','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(22,10,4,2,229000.00,0.00,NULL,'2XL','black',40,10,'AO-NI-FITTED-L37828-2XL-BLACK-69A9BD56E9ED7','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(23,10,2,6,229000.00,0.00,NULL,'L','brown',40,10,'AO-NI-FITTED-L37828-L-BROWN-69A9BD56EA641','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(24,10,1,6,229000.00,0.00,NULL,'M','brown',40,10,'AO-NI-FITTED-L37828-M-BROWN-69A9BD56EB001','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(25,10,3,6,229000.00,0.00,NULL,'XL','brown',40,10,'AO-NI-FITTED-L37828-XL-BROWN-69A9BD56EB8C3','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(26,10,4,6,229000.00,0.00,NULL,'2XL','brown',40,10,'AO-NI-FITTED-L37828-2XL-BROWN-69A9BD56EC02F','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(27,10,2,5,229000.00,0.00,NULL,'L','white',40,10,'AO-NI-FITTED-L37828-L-WHITE-69A9BD56EC7BC','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(28,10,1,5,229000.00,0.00,NULL,'M','white',40,10,'AO-NI-FITTED-L37828-M-WHITE-69A9BD56ECF15','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(29,10,3,5,229000.00,0.00,NULL,'XL','white',40,10,'AO-NI-FITTED-L37828-XL-WHITE-69A9BD56ED663','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(30,10,4,5,229000.00,0.00,NULL,'2XL','white',40,10,'AO-NI-FITTED-L37828-2XL-WHITE-69A9BD56EDDC9','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(31,11,2,2,129000.00,0.00,NULL,'L','black',35,10,'AO-PHONG-LOOSE-L-BLACK-69A9BEFE1D26C','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(32,11,1,2,129000.00,0.00,NULL,'M','black',35,10,'AO-PHONG-LOOSE-M-BLACK-69A9BEFE1EDA5','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(33,11,3,2,129000.00,0.00,NULL,'XL','black',35,10,'AO-PHONG-LOOSE-XL-BLACK-69A9BEFE1F704','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(34,11,4,2,129000.00,0.00,NULL,'2XL','black',35,10,'AO-PHONG-LOOSE-2XL-BLACK-69A9BEFE1FFB2','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(35,11,2,5,129000.00,0.00,NULL,'L','white',35,10,'AO-PHONG-LOOSE-L-WHITE-69A9BEFE20909','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(36,11,1,5,129000.00,0.00,NULL,'M','white',35,10,'AO-PHONG-LOOSE-M-WHITE-69A9BEFE21272','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(37,11,3,5,129000.00,0.00,NULL,'XL','white',35,10,'AO-PHONG-LOOSE-XL-WHITE-69A9BEFE21C4F','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(38,11,4,5,129000.00,0.00,NULL,'2XL','white',35,10,'AO-PHONG-LOOSE-2XL-WHITE-69A9BEFE225D8','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(39,11,2,6,129000.00,0.00,NULL,'L','brown',35,10,'AO-PHONG-LOOSE-L-BROWN-69A9BEFE22EC8','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(40,11,1,6,129000.00,0.00,NULL,'M','brown',35,10,'AO-PHONG-LOOSE-M-BROWN-69A9BEFE23729','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(41,11,3,6,129000.00,0.00,NULL,'XL','brown',35,10,'AO-PHONG-LOOSE-XL-BROWN-69A9BEFE23FB1','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(42,11,4,6,129000.00,0.00,NULL,'2XL','brown',35,10,'AO-PHONG-LOOSE-2XL-BROWN-69A9BEFE248C0','2026-03-05 17:35:58','2026-03-05 17:35:58',NULL),(43,12,5,2,289000.00,0.00,NULL,'28','black',32,10,'QUAN-AU-SLIM-28-BLACK-69A9C0AD673C9','2026-03-05 17:43:09','2026-03-05 17:43:09',NULL),(44,12,6,2,289000.00,0.00,NULL,'29','black',32,10,'QUAN-AU-SLIM-29-BLACK-69A9C0AD688A9','2026-03-05 17:43:09','2026-03-05 17:43:09',NULL),(45,12,7,2,289000.00,0.00,NULL,'30','black',32,10,'QUAN-AU-SLIM-30-BLACK-69A9C0AD69270','2026-03-05 17:43:09','2026-03-05 17:43:09',NULL),(46,12,8,2,289000.00,0.00,NULL,'31','black',32,10,'QUAN-AU-SLIM-31-BLACK-69A9C0AD69CAC','2026-03-05 17:43:09','2026-03-05 17:43:09',NULL),(47,12,10,2,289000.00,0.00,NULL,'32','black',32,10,'QUAN-AU-SLIM-32-BLACK-69A9C0AD6A509','2026-03-05 17:43:09','2026-03-05 17:43:09',NULL),(48,12,9,2,289000.00,0.00,NULL,'33','black',32,10,'QUAN-AU-SLIM-33-BLACK-69A9C0AD6AE0B','2026-03-05 17:43:09','2026-03-05 17:43:09',NULL),(49,13,5,2,229000.00,0.00,NULL,'28','black',60,10,'QUAN-JEANS-STRAIGHT-28-BLACK-69A9C13DD87A2','2026-03-05 17:45:33','2026-03-05 17:45:33',NULL),(50,13,6,2,229000.00,0.00,NULL,'29','black',60,10,'QUAN-JEANS-STRAIGHT-29-BLACK-69A9C13DD9E3D','2026-03-05 17:45:33','2026-03-05 17:45:33',NULL),(51,13,7,2,229000.00,0.00,NULL,'30','black',60,10,'QUAN-JEANS-STRAIGHT-30-BLACK-69A9C13DDA8E3','2026-03-05 17:45:33','2026-03-05 17:45:33',NULL),(52,13,8,2,229000.00,0.00,NULL,'31','black',60,10,'QUAN-JEANS-STRAIGHT-31-BLACK-69A9C13DDB368','2026-03-05 17:45:33','2026-03-05 17:45:33',NULL),(53,13,10,2,229000.00,0.00,NULL,'32','black',60,10,'QUAN-JEANS-STRAIGHT-32-BLACK-69A9C13DDBD45','2026-03-05 17:45:33','2026-03-05 17:45:33',NULL),(54,13,9,2,229000.00,0.00,NULL,'33','black',60,10,'QUAN-JEANS-STRAIGHT-33-BLACK-69A9C13DDC756','2026-03-05 17:45:33','2026-03-05 17:45:33',NULL),(55,14,5,2,359000.00,0.00,NULL,'28','black',55,10,'QUAN-AU-28-BLACK-69A9C1CF3CBF2','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(56,14,6,2,359000.00,0.00,NULL,'29','black',55,10,'QUAN-AU-29-BLACK-69A9C1CF3EC95','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(57,14,7,2,359000.00,0.00,NULL,'30','black',55,10,'QUAN-AU-30-BLACK-69A9C1CF3FB3F','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(58,14,8,2,359000.00,0.00,NULL,'31','black',55,10,'QUAN-AU-31-BLACK-69A9C1CF409C8','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(59,14,10,2,359000.00,0.00,NULL,'32','black',55,10,'QUAN-AU-32-BLACK-69A9C1CF4154B','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(60,14,9,2,359000.00,0.00,NULL,'33','black',55,10,'QUAN-AU-33-BLACK-69A9C1CF42022','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(61,14,5,6,359000.00,0.00,NULL,'28','brown',55,10,'QUAN-AU-28-BROWN-69A9C1CF42A3A','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(62,14,6,6,359000.00,0.00,NULL,'29','brown',55,10,'QUAN-AU-29-BROWN-69A9C1CF434B9','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(63,14,7,6,359000.00,0.00,NULL,'30','brown',55,10,'QUAN-AU-30-BROWN-69A9C1CF44075','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(64,14,8,6,359000.00,0.00,NULL,'31','brown',55,10,'QUAN-AU-31-BROWN-69A9C1CF44BD0','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(65,14,10,6,359000.00,0.00,NULL,'32','brown',55,10,'QUAN-AU-32-BROWN-69A9C1CF455F5','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(66,15,11,2,189000.00,0.00,NULL,'39','black',105,10,'DEP-DA-39-BLACK-69A9C2BAE8A67','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(67,15,12,2,189000.00,0.00,NULL,'40','black',105,10,'DEP-DA-40-BLACK-69A9C2BAEA7D5','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(68,15,13,2,189000.00,0.00,NULL,'41','black',105,10,'DEP-DA-41-BLACK-69A9C2BAEB639','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(69,15,14,2,189000.00,0.00,NULL,'42','black',105,10,'DEP-DA-42-BLACK-69A9C2BAEC23B','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(70,15,11,6,189000.00,0.00,NULL,'39','brown',105,10,'DEP-DA-39-BROWN-69A9C2BAECDE0','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(71,15,12,6,189000.00,0.00,NULL,'40','brown',105,10,'DEP-DA-40-BROWN-69A9C2BAEDB54','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(72,15,13,6,189000.00,0.00,NULL,'41','brown',105,10,'DEP-DA-41-BROWN-69A9C2BAEE94A','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(73,15,14,6,189000.00,0.00,NULL,'42','brown',105,10,'DEP-DA-42-BROWN-69A9C2BAEF496','2026-03-05 17:51:54','2026-03-05 17:51:54',NULL),(74,16,2,2,189000.00,0.00,NULL,'L','black',60,10,'DAY-LUNG-DA-BO-L-BLACK-69A9C341A65C9','2026-03-05 17:54:09','2026-03-05 17:54:09',NULL),(75,17,15,2,19000.00,0.00,NULL,'Không','black',250,10,'TAT-KHONG-CO-KHONG-BLACK-69A9C3DA96A83','2026-03-05 17:56:42','2026-03-05 18:13:17',NULL),(76,17,15,5,19000.00,0.00,NULL,'Không','white',250,10,'TAT-KHONG-CO-KHONG-WHITE-69A9C3DA985AF','2026-03-05 17:56:42','2026-03-05 18:13:17',NULL),(77,17,15,6,19000.00,0.00,NULL,'Không','brown',241,10,'TAT-KHONG-CO-KHONG-BROWN-69A9C3DA98F8D','2026-03-05 17:56:42','2026-03-05 17:56:42',NULL);
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
  `category_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `sale_start` timestamp NULL DEFAULT NULL,
  `sale_end` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (9,8,NULL,'Áo Nỉ Fitted L.4.7862',NULL,'ao-ni-fitted-l47862-9','free ship\r\nGIAO HÀNG NỘI THÀNH TRONG 24 GIỜ\r\nReturn\r\nĐỔI HÀNG TRONG 30 NGÀY\r\n\r\nHotline\r\nTỔNG ĐÀI BÁN HÀNG 096728.4444',149000.00,NULL,NULL,NULL,1,0,'products/C9Ve3FUkTVYOWXbsuiw3cFIpoSiDxHX68CQEknBW.jpg','2026-03-05 17:23:12','2026-03-10 12:30:49',NULL),(10,8,NULL,'Áo Nỉ Fitted L.3.7828',NULL,'ao-ni-fitted-l37828-69a9bd56e2060','free ship\r\nGIAO HÀNG NỘI THÀNH TRONG 24 GIỜ\r\nReturn\r\nĐỔI HÀNG TRONG 30 NGÀY\r\n\r\nHotline\r\nTỔNG ĐÀI BÁN HÀNG 096728.4444',229000.00,NULL,NULL,NULL,1,0,'products/KkYbZ0IGe6GhTXy7QVt9YQbXXKUyc7CdIOgVyGTf.jpg','2026-03-05 17:28:54','2026-03-05 17:28:54',NULL),(11,9,NULL,'Áo Phông Loose',NULL,'ao-phong-loose-11',NULL,129000.00,NULL,NULL,NULL,1,0,'products/veL0QHXFCKAYIKzxmzkwEOyKVdIdLet59aeX1GL5.png','2026-03-05 17:35:58','2026-03-05 17:39:17',NULL),(12,11,NULL,'Quần Âu Slim',NULL,'quan-au-slim-69a9c0ad624ad',NULL,289000.00,NULL,NULL,NULL,1,0,'products/7vwJ3oTZk05AvwjV1T5oXN8KrqegGNdjGMdN23Lz.jpg','2026-03-05 17:43:09','2026-03-05 17:43:09',NULL),(13,12,NULL,'Quần Jeans Straight',NULL,'quan-jeans-straight-69a9c13dd2222',NULL,229000.00,NULL,NULL,NULL,1,0,'products/WCrx4krthDyUp2iKdRgPNB2Fkt13KJzGfAn9GHrU.jpg','2026-03-05 17:45:33','2026-03-05 17:45:33',NULL),(14,11,NULL,'Quần Âu',NULL,'quan-au-69a9c1cf35a90',NULL,359000.00,NULL,NULL,NULL,1,0,'products/BGkgUxJ4PT4YqICNHTttfF911G0dUNOcPsxObfmM.jpg','2026-03-05 17:47:59','2026-03-05 17:47:59',NULL),(15,13,NULL,'Dép Da',NULL,'dep-da-69a9c2bae207f',NULL,189000.00,NULL,NULL,NULL,1,0,'products/tBpV1o8PzWnNOUpRULucb7MViUQatE42JnnDybci.jpg','2026-03-05 17:51:54','2026-03-05 17:51:55',NULL),(16,14,NULL,'Dây Lưng Da Bò',NULL,'day-lung-da-bo-69a9c341a1071',NULL,189000.00,NULL,NULL,NULL,1,0,'products/eaeXw1vehRIqDTsRZ71iJktLcNr7z8quvI1p37cG.jpg','2026-03-05 17:54:09','2026-03-05 17:54:09',NULL),(17,15,NULL,'Tất không cổ',NULL,'tat-khong-co-69a9c3da908e7',NULL,19000.00,NULL,NULL,NULL,1,0,'products/tkXnn3Q65WW1r6WBqlGi9DO4CPekb5jSNOn4KxHS.jpg','2026-03-05 17:56:42','2026-03-05 17:56:42',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reward_point_histories`
--

DROP TABLE IF EXISTS `reward_point_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reward_point_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reward_point_histories`
--

LOCK TABLES `reward_point_histories` WRITE;
/*!40000 ALTER TABLE `reward_point_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `reward_point_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reward_points`
--

DROP TABLE IF EXISTS `reward_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reward_points` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reward_points`
--

LOCK TABLES `reward_points` WRITE;
/*!40000 ALTER TABLE `reward_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `reward_points` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('CIWAOF4Jg0pZiRVgl61fV02QwYrDXbURyytvu4LP',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiN1NaUVdXbnVRUTlNWW5ST2FpR0pjNVBDOFlyTWJ6YjlsNmtnNFV0dCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvY2hhdC9tZXNzYWdlcyI7czo1OiJyb3V0ZSI7czoxNzoiYXBpLmNoYXQubWVzc2FnZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE1OiJyZWNlbnRseV92aWV3ZWQiO2E6Mzp7aTowO2k6MTE7aToxO2k6OTtpOjI7aToxNzt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NzM2NzA3Mjk7fX0=',1773671143),('FHKsPAgxNLoiQT2BqAxOTGIODCfsKz0IqMHrUI59',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXNKN2NTYmM2dFdUa0JiS0U1OXVBcFdwemdNVENVbldKVklYQm5LayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvY2hhdC9tZXNzYWdlcyI7czo1OiJyb3V0ZSI7czoxNzoiYXBpLmNoYXQubWVzc2FnZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1773670262);
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
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'bank_name','MB Bank (Ngân hàng Quân Đội)','payment','2026-02-23 09:41:40','2026-02-25 17:16:39'),(2,'bank_account_number','0359756805','payment','2026-02-23 09:41:40','2026-02-25 17:16:39'),(3,'bank_account_name','NGUYEN CONG BANG','payment','2026-02-23 09:41:40','2026-02-25 17:16:39'),(4,'bank_id','MB','payment','2026-02-23 09:41:40','2026-02-25 17:16:39'),(5,'store_address','Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam','contact','2026-02-23 17:02:29','2026-02-23 17:02:29'),(6,'store_phone','0354869999','contact','2026-02-23 17:02:29','2026-02-23 17:02:29'),(7,'store_email','Elite@gmail.com','contact','2026-02-23 17:02:29','2026-02-23 17:02:29'),(8,'store_map_iframe','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.6575765790473!2d105.71077797584149!3d21.04638368717544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134546536093551%3A0x673199834278e993!2sNg.%2091%20Lai%20X%C3%A1%2C%20Kim%20Chung%2C%20Ho%C3%A0i%20%C4%90%E1%BB%A9c%2C%20H%C3%A0%20N%E1%BB%99i!5e0!3m2!1svi!2s!4v1710000000000!5m2!1svi!2s','contact','2026-02-23 17:02:29','2026-02-23 17:02:29'),(9,'site_title','Sieu Nhan Gao Shop','general','2026-02-23 18:06:51','2026-02-23 18:06:51'),(10,'shipping_fee','30000','general','2026-02-23 18:06:51','2026-02-25 17:16:39'),(11,'site_email','Elite@gmail.com','contact','2026-02-23 18:06:51','2026-02-25 17:16:39'),(12,'site_phone','0354869999','contact','2026-02-23 18:06:51','2026-02-25 17:16:39'),(13,'site_address','Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam','contact','2026-02-23 18:06:51','2026-02-25 17:16:39'),(14,'social_facebook','#','social','2026-02-23 18:06:51','2026-02-23 18:06:51'),(15,'social_instagram','#','social','2026-02-23 18:06:51','2026-02-23 18:06:51');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sizes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sizes_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sizes`
--

LOCK TABLES `sizes` WRITE;
/*!40000 ALTER TABLE `sizes` DISABLE KEYS */;
INSERT INTO `sizes` VALUES (1,'M',1,1,NULL,NULL),(2,'L',0,1,'2026-02-25 16:33:19','2026-02-25 16:33:19'),(3,'XL',2,1,'2026-03-05 17:17:45','2026-03-05 17:17:45'),(4,'2XL',3,1,'2026-03-05 17:17:54','2026-03-05 17:17:54'),(5,'28',0,1,'2026-03-05 17:41:15','2026-03-05 17:41:15'),(6,'29',0,1,'2026-03-05 17:41:22','2026-03-05 17:41:22'),(7,'30',0,1,'2026-03-05 17:41:26','2026-03-05 17:41:26'),(8,'31',0,1,'2026-03-05 17:41:30','2026-03-05 17:41:30'),(9,'33',0,1,'2026-03-05 17:41:41','2026-03-05 17:41:41'),(10,'32',0,1,'2026-03-05 17:41:47','2026-03-05 17:41:47'),(11,'39',0,1,'2026-03-05 17:50:12','2026-03-05 17:50:12'),(12,'40',0,1,'2026-03-05 17:50:18','2026-03-05 17:50:18'),(13,'41',0,1,'2026-03-05 17:50:22','2026-03-05 17:50:22'),(14,'42',0,1,'2026-03-05 17:50:27','2026-03-05 17:50:27'),(15,'Không',0,1,'2026-03-05 17:55:01','2026-03-05 17:55:01');
/*!40000 ALTER TABLE `sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_accounts`
--

DROP TABLE IF EXISTS `social_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_accounts_user_id_foreign` (`user_id`),
  CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_accounts`
--

LOCK TABLES `social_accounts` WRITE;
/*!40000 ALTER TABLE `social_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
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
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `role` enum('admin','staff','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'bang','bang@gmail.com',NULL,'$2y$12$wU5pHkgAzGY8MsFlDh8PHuGmvSzYJLsWkHMQHLm07sHT/g4aTISia','avatars/y3Wx4eZWTyWsBRyxBes3KRAoRUMdiUwQIB3nWHfU.jpg',NULL,NULL,'admin',NULL,'2026-01-28 07:25:41','2026-02-24 14:56:34'),(2,'nghialol','nghia@gmail.com',NULL,'$2y$12$jVp2oJ4zW8Y6cyeplovs3.RDxDw0NvETPcAEl1IulTqv0OagQV0wu',NULL,'0684597815','Vietnam','staff',NULL,'2026-01-28 08:56:41','2026-01-28 08:58:26'),(3,'Bằng Nguyễn Công','bang123@gmail.com',NULL,'$2y$12$1BIB8USfknqlD/4cgiUYieXC9Gl6IlJEtceWbF66YF2qftxAtnjNy',NULL,'0359756805',NULL,'user',NULL,'2026-02-23 17:11:55','2026-02-23 17:11:55'),(4,'Bằng Nguyễn Công','nguyen1@gmail.com',NULL,'$2y$12$WYSF0KULfT.u8/FF1W9m4eJz7ib0M3EpRcVtvuWonYiAAQqgrcjfG',NULL,'0359756805',NULL,'user',NULL,'2026-02-26 14:34:13','2026-02-26 14:34:13');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse_stocks`
--

DROP TABLE IF EXISTS `warehouse_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouse_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint unsigned NOT NULL,
  `product_variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_stocks_warehouse_id_product_variant_id_unique` (`warehouse_id`,`product_variant_id`),
  KEY `warehouse_stocks_product_variant_id_foreign` (`product_variant_id`),
  CONSTRAINT `warehouse_stocks_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse_stocks`
--

LOCK TABLES `warehouse_stocks` WRITE;
/*!40000 ALTER TABLE `warehouse_stocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouse_stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-16 21:26:10
