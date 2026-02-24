-- MySQL dump 10.13  Distrib 9.6.0, for macos14.8 (x86_64)
--
-- Host: 127.0.0.1    Database: manake_db
-- ------------------------------------------------------
-- Server version	9.6.0

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '814ab6b4-0968-11f1-9332-6133daafda3b:1-31343';

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  KEY `admins_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Fikri Rachmat','frahmat68@gmail.com','$2y$12$TedPa1Zh7cVAiInZVV3PPujEx4asUqKcZQjIOLwYxTk.cRRuK.lCu','super_admin','2026-02-19 23:18:40',NULL,'2026-02-17 19:11:05','2026-02-19 23:18:40');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload_json` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_admin_id_action_index` (`admin_id`,`action`),
  KEY `audit_logs_table_name_record_id_index` (`table_name`,`record_id`),
  CONSTRAINT `audit_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-17 19:15:13'),(2,1,'category.store','categories','1','{\"name\":\"Audio\",\"slug\":\"audio\"}','2026-02-17 19:19:10'),(3,1,'category.store','categories','2','{\"name\":\"Camera\",\"slug\":\"camera\"}','2026-02-17 19:19:24'),(4,1,'category.store','categories','3','{\"name\":\"Lighting\",\"slug\":\"lighting\"}','2026-02-17 19:19:40'),(5,1,'category.store','categories','4','{\"name\":\"Lens\",\"slug\":\"lensa\"}','2026-02-17 19:19:56'),(6,1,'equipment.store','equipments','1','{\"name\":\"Aputure Light Storm 600D Pro (Standard)\",\"slug\":\"aputure-600d\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:22:09'),(7,1,'equipment.update','equipments','1','{\"name\":\"Aputure Light Storm 600D Pro (Standard)\",\"slug\":\"aputure-600d\",\"category_id\":\"3\",\"price_per_day\":\"450000\",\"stock\":\"1\",\"status\":\"ready\",\"specifications\":\"Color: 5600K, CRI\\/TLCI: 96\\r\\nBeam Angle with 55° Reflector\\r\\nWireless DMX, Bluetooth Control\\r\\n4 Dimming Modes, Wireless Range: 328\'\\r\\nComparable to a 1200W HMI\\r\\n8 Built-In Lighting Effects\\r\\nAC 100-240V, 50\\/60Hz\\r\\nControl Box\\r\\nHyper Reflector\\r\\nWeatherproof Head Cable\\r\\nNeutrik AC Cable\\r\\nVA Remote RC1+\\r\\nLightning Clamp\\r\\nRolling Carry Case\\r\\nLighstand\",\"description\":\"Color: 5600K, CRI\\/TLCI: 96\\r\\nBeam Angle with 55° Reflector\\r\\nWireless DMX, Bluetooth Control\\r\\n4 Dimming Modes, Wireless Range: 328\'\\r\\nComparable to a 1200W HMI\\r\\n8 Built-In Lighting Effects\\r\\nAC 100-240V, 50\\/60Hz\\r\\nControl Box\\r\\nHyper Reflector\\r\\nWeatherproof Head Cable\\r\\nNeutrik AC Cable\\r\\nVA Remote RC1+\\r\\nLightning Clamp\\r\\nRolling Carry Case\\r\\nLighstand\"}','2026-02-17 19:23:35'),(8,1,'equipment.store','equipments','2','{\"name\":\"Eartec UltraLite 7-Person Full-Duplex Wireless Intercom\",\"slug\":\"eartech-ultralite7\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:29:28'),(9,1,'equipment.store','equipments','3','{\"name\":\"Godox TL60 Tube Light RGB Kit (1 Box isi 4 Pcs)\",\"slug\":\"godox-tl60\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:30:51'),(10,1,'equipment.store','equipments','4','{\"name\":\"GoPro Hero 4 Black\",\"slug\":\"gopro-hero4\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:32:25'),(11,1,'equipment.store','equipments','5','{\"name\":\"Lumix S1H Mirrorless (Body + Lens Adapter)\",\"slug\":\"lumix-s1h\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:33:56'),(12,1,'equipment.store','equipments','6','{\"name\":\"Samyang V-AF T1.9 Lens for Sony FE (24mm, 35mm, 75mm)\",\"slug\":\"samyang-sonyfe\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:34:50'),(13,1,'equipment.store','equipments','7','{\"name\":\"Light Meter Sekonic Speedmaster\",\"slug\":\"sekonic-speedmaster\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:36:13'),(14,1,'equipment.store','equipments','8','{\"name\":\"Sony a7S Mark III (Body Only)\",\"slug\":\"sony-a7s3\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:37:51'),(15,1,'equipment.store','equipments','9','{\"name\":\"Sony FE 85mm f\\/1.4 GM Lens\",\"slug\":\"sony-85mm\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:39:08'),(16,1,'equipment.store','equipments','10','{\"name\":\"Sony FE 135mm f\\/1.8 GM Lens\",\"slug\":\"sony-gm-135mm\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:39:51'),(17,1,'equipment.store','equipments','11','{\"name\":\"HT WLAN UHF\",\"slug\":\"wlan-uhf\",\"status\":\"ready\",\"stock\":20}','2026-02-17 19:45:21'),(18,1,'equipment.store','equipments','12','{\"name\":\"Audio Recorder Zoom F8 Multi Track\",\"slug\":\"zoom-f8\",\"status\":\"ready\",\"stock\":1}','2026-02-17 19:46:23'),(19,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-17 20:09:58'),(20,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-18 13:45:39'),(21,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-18 17:11:52'),(22,1,'order.update_operational_status','orders','7','{\"before\":\"lunas\",\"after\":\"barang_diambil\"}','2026-02-18 17:12:27'),(23,1,'order.update_operational_status','orders','7','{\"before\":\"barang_diambil\",\"after\":\"barang_kembali\"}','2026-02-18 17:13:29'),(24,1,'order.update_status','orders','7','{\"status_pembayaran\":\"paid\",\"status_pesanan\":\"barang_kembali\",\"status\":\"paid\",\"additional_fee\":100000,\"additional_fee_note\":\"terlambat pengembalian\",\"admin_note\":null}','2026-02-18 17:14:39'),(25,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-19 13:55:48'),(26,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-19 17:44:22'),(27,1,'website_settings.update','site_settings',NULL,'{\"keys\":[\"brand.name\",\"brand.tagline\",\"brand.logo_path\",\"brand.favicon_path\",\"seo.meta_title\",\"seo.meta_description\",\"contact.whatsapp\",\"social.instagram\",\"social.tiktok\",\"site.maintenance_enabled\"]}','2026-02-19 21:25:53'),(28,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-19 23:50:52'),(29,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-20 10:09:39'),(30,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/605.1.15 (KHTML, like Gecko) Version\\/26.3 Safari\\/605.1.15\"}','2026-02-20 17:47:05'),(31,1,'copy.update','site_settings',NULL,'{\"section\":\"footer\",\"keys\":[\"footer.about\",\"footer.address\",\"footer.whatsapp\",\"contact.email\",\"footer.instagram\",\"footer_copyright\",\"site_tagline\",\"footer.rules_title\",\"footer.rules_link\",\"footer.rules_note\"]}','2026-02-20 17:50:40'),(32,1,'equipment.store','equipments','13','{\"name\":\"Nikon ZR Cinema Camera 6K\",\"slug\":\"nikon-zr\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:15:23'),(33,1,'equipment.store','equipments','14','{\"name\":\"Blackmagic Design Pocket Cinema Camera 6K EF Mount (Body Only)\",\"slug\":\"blackmagic-6k\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:16:51'),(34,1,'equipment.store','equipments','15','{\"name\":\"FUJIFILM X-T4 Mirrorless Camera (Black)\",\"slug\":\"fujifilm-xt4\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:19:44'),(35,1,'equipment.store','equipments','16','{\"name\":\"Yamaha Audio Mixer MG12XU\",\"slug\":\"yamaha-mg12xu\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:29:05'),(36,1,'equipment.store','equipments','17','{\"name\":\"SARAMONIC BLINK 500 T4 WIRELESS MICROPHONE (4 Transmitter + 1 Receiver)\",\"slug\":\"saramonic-blink500\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:30:40'),(37,1,'equipment.store','equipments','18','{\"name\":\"Boom Mic Sennheiser MKH 816 + Rycote Ws 8 (Kit)\",\"slug\":\"sennheiser-mkh816\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:32:00'),(38,1,'equipment.store','equipments','19','{\"name\":\"Sigma 28-105mm f\\/2.8 DG DN Art (For Sony E-Mount)\",\"slug\":\"sigma-28-105mm\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:39:12'),(39,1,'equipment.update','equipments','19','{\"name\":\"Sigma 28-105mm f\\/2.8 DG DN Art (For Sony E-Mount)\",\"slug\":\"sigma-28-105mm\",\"category_id\":\"4\",\"price_per_day\":\"225000\",\"stock\":\"1\",\"status\":\"ready\",\"specifications\":\"Sigma 28-105mm f\\/2.8 DG DN Art Lens (Sony E)\\r\\nSigma LCF-82 III 82mm Lens Cap\\r\\nSigma LCR II Rear Lens Cap for Sony E\\r\\nSigma Lens Hood for 28-105mm f\\/2.8 Art DG DN Lens\\r\\nFull Frame | f\\/2.8 to f\\/22\\r\\nFast Wide-to-Telephoto Zoom\\r\\nHLA Autofocus\\r\\n15.8\\\" Minimum Focus Distance\\r\\nAperture Ring with Click & Lock Switches\\r\\nFLD, SLD & Aspherical Elements\\r\\nWater- and Oil-Repellant Coating\\r\\nDust- and Splash-Resistant Construction\",\"image\":{},\"description\":\"Sigma 28-105mm f\\/2.8 DG DN Art Lens (Sony E)\\r\\nSigma LCF-82 III 82mm Lens Cap\\r\\nSigma LCR II Rear Lens Cap for Sony E\\r\\nSigma Lens Hood for 28-105mm f\\/2.8 Art DG DN Lens\\r\\nFull Frame | f\\/2.8 to f\\/22\\r\\nFast Wide-to-Telephoto Zoom\\r\\nHLA Autofocus\\r\\n15.8\\\" Minimum Focus Distance\\r\\nAperture Ring with Click & Lock Switches\\r\\nFLD, SLD & Aspherical Elements\\r\\nWater- and Oil-Repellant Coating\\r\\nDust- and Splash-Resistant Construction\",\"image_path\":\"equipments\\/ebnrca7VCpwOALlwboD6huZ0P9ZvslGS74RIN8F0.png\"}','2026-02-20 18:39:52'),(40,1,'equipment.store','equipments','20','{\"name\":\"Laowa 10mm f\\/2.8 Zero-D FF Autofocus Lens (Sony E-Mount)\",\"slug\":\"laowa-10mm\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:43:07'),(41,1,'equipment.store','equipments','21','{\"name\":\"Nikon NIKKOR Z 70-200mm f\\/2.8 VR S Lens\",\"slug\":\"nikon-70-200mm\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:46:43'),(42,1,'equipment.store','equipments','22','{\"name\":\"Aputure NOVA II 2x1 Tunable Color LED Light Panel\",\"slug\":\"aputure-nova-ii\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:52:50'),(43,1,'equipment.store','equipments','23','{\"name\":\"Nanlux Evoke 600C RGB LED Spotlight\",\"slug\":\"nanlux-600c\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:55:25'),(44,1,'equipment.store','equipments','24','{\"name\":\"KUPO Crank Up\\/ Jumbo Stand With Braked Wheel\",\"slug\":\"kupo-crank\",\"status\":\"ready\",\"stock\":1}','2026-02-20 18:58:42'),(45,1,'category.store','categories','5','{\"name\":\"Monitor & Wireless Control\",\"slug\":\"monitor-wireless\"}','2026-02-20 19:11:17'),(46,1,'equipment.store','equipments','25','{\"name\":\"Monitor FeelWorld S7\",\"slug\":\"feelworld-s7\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:14:31'),(47,1,'equipment.store','equipments','26','{\"name\":\"Monitor pyro 7 kit\",\"slug\":\"pyro7\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:17:06'),(48,1,'equipment.store','equipments','27','{\"name\":\"Hollyland Mars 4K Wireless Video Transmission System\",\"slug\":\"hollyland-mars-4k\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:19:56'),(49,1,'equipment.store','equipments','28','{\"name\":\"TILTA Nucleus-N Wireless Lens Control System\",\"slug\":\"tilta-nucleus-n\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:23:36'),(50,1,'equipment.store','equipments','29','{\"name\":\"Feelworld L4 Multi Video format Mixer Switcher Streaming Layar Sentuh\",\"slug\":\"feelworld-l4\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:25:48'),(51,1,'equipment.update','equipments','29','{\"name\":\"Feelworld L4 Multi Video format Mixer Switcher Streaming Layar Sentuh\",\"slug\":\"feelworld-l4\",\"category_id\":\"5\",\"price_per_day\":\"250000\",\"stock\":\"1\",\"status\":\"ready\",\"specifications\":\"Layar sentuh 10.1 inci\\r\\nOperasi sentuh yang diikonisasi\\r\\nKompatibel dengan sumber sinyal HDMI dan SDI\\r\\nT-bar cukup mengganti sumber sinyal atau efek transisi\\r\\nUSB 3.0 cepat untuk streaming langsung\\r\\nTertanam & Sisipkan Audio dengan Sinkronisasi\\r\\nKunci Chroma + overlay LOGO\\r\\nCampur audio dari beberapa input\\r\\nHamparan video PIP yang dapat dikonfigurasi\\r\\nMendukung 13 efek transisi penyiaran\\r\\nKendali jarak jauh melalui aplikasi\",\"image\":{},\"description\":\"Layar sentuh 10.1 inci\\r\\nOperasi sentuh yang diikonisasi\\r\\nKompatibel dengan sumber sinyal HDMI dan SDI\\r\\nT-bar cukup mengganti sumber sinyal atau efek transisi\\r\\nUSB 3.0 cepat untuk streaming langsung\\r\\nTertanam & Sisipkan Audio dengan Sinkronisasi\\r\\nKunci Chroma + overlay LOGO\\r\\nCampur audio dari beberapa input\\r\\nHamparan video PIP yang dapat dikonfigurasi\\r\\nMendukung 13 efek transisi penyiaran\\r\\nKendali jarak jauh melalui aplikasi\",\"image_path\":\"equipments\\/gbW8tYazcAiB8dvcJ6PDY0JPJ5gcIMB3XZT2Qrzv.png\"}','2026-02-20 19:26:18'),(52,1,'equipment.store','equipments','30','{\"name\":\"FeelWorld 21.5\'\' Full HD IPS Carry-On Broadcast Monitor (Silver)\",\"slug\":\"feelworld-215\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:29:40'),(53,1,'equipment.update','equipments','30','{\"name\":\"FeelWorld 21.5\'\' Full HD IPS Carry-On Broadcast Monitor (Silver)\",\"slug\":\"feelworld-215\",\"category_id\":\"5\",\"price_per_day\":\"250000\",\"stock\":\"1\",\"status\":\"ready\",\"specifications\":\"FeelWorld 21.5\'\' Full HD IPS Carry-On Broadcast Monitor (Silver)\\r\\nMini HDMI Cable\\r\\nV-Mount Battery Plate (Pre-Installed)\\r\\n3A Power Adapter\\r\\nTally Kit\\r\\nSunshade\\r\\nCase\\r\\nFull HD Display, Supports up to 1080p60\\r\\nProtective Case for Efficient On-Set Use\\r\\nRemovable from Case, Rack Mountable\\r\\n178 \\/ 178° Viewing Angle\\r\\nHDMI, 3G-SDI & Analog Inputs\\/Outputs\\r\\nRCA Audio Input, Headphone Out, Speakers\\r\\nTally Port, 3-Color LED Tally Indicators\\r\\nIntegrated V-Mount Plate\\r\\nPeaking and Other Analysis Functions\\r\\n4-Pin XLR and DC Barrel Power Ports\",\"image\":{},\"description\":\"FeelWorld 21.5\'\' Full HD IPS Carry-On Broadcast Monitor (Silver)\\r\\nMini HDMI Cable\\r\\nV-Mount Battery Plate (Pre-Installed)\\r\\n3A Power Adapter\\r\\nTally Kit\\r\\nSunshade\\r\\nCase\\r\\nFull HD Display, Supports up to 1080p60\\r\\nProtective Case for Efficient On-Set Use\\r\\nRemovable from Case, Rack Mountable\\r\\n178 \\/ 178° Viewing Angle\\r\\nHDMI, 3G-SDI & Analog Inputs\\/Outputs\\r\\nRCA Audio Input, Headphone Out, Speakers\\r\\nTally Port, 3-Color LED Tally Indicators\\r\\nIntegrated V-Mount Plate\\r\\nPeaking and Other Analysis Functions\\r\\n4-Pin XLR and DC Barrel Power Ports\",\"image_path\":\"equipments\\/blQKVTYojXQgtAQVBzn8cyKyqtfXNZkSkhK0nifD.png\"}','2026-02-20 19:30:04'),(54,1,'category.store','categories','6','{\"name\":\"Aksesoris\",\"slug\":\"aksesoris\"}','2026-02-20 19:36:27'),(55,1,'equipment.store','equipments','31','{\"name\":\"AS Easyrig Vest 3-8kg\",\"slug\":\"easyrig-vest\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:38:27'),(56,1,'equipment.store','equipments','32','{\"name\":\"Zhiyun Smooth 4\",\"slug\":\"zhiyun-smooth-4\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:42:19'),(57,1,'equipment.store','equipments','33','{\"name\":\"DJI Osmo Mobile 3\",\"slug\":\"dji-osmo-3\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:44:22'),(58,1,'equipment.store','equipments','34','{\"name\":\"V-Mount Battery 98Wh (FXLION NANO TWO)\",\"slug\":\"vmount-battery\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:51:22'),(59,1,'equipment.store','equipments','35','{\"name\":\"V-Mount Charger 2 Slot\",\"slug\":\"vmount-charger\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:53:13'),(60,1,'equipment.store','equipments','36','{\"name\":\"V-Mount Plate to Rod 15mm\",\"slug\":\"vmount-plate\",\"status\":\"ready\",\"stock\":1}','2026-02-20 19:54:34'),(61,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-21 14:03:44'),(62,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-21 18:36:09'),(63,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-21 19:40:20'),(64,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-22 05:39:14'),(65,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-22 06:22:21'),(66,1,'order.update_operational_status','orders','1','{\"before\":\"lunas\",\"after\":\"barang_diambil\"}','2026-02-22 07:08:38'),(67,1,'order.update_operational_status','orders','1','{\"before\":\"barang_diambil\",\"after\":\"barang_kembali\"}','2026-02-22 07:08:41'),(68,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}','2026-02-23 12:17:56'),(69,1,'order.update_operational_status','orders','10','{\"before\":\"lunas\",\"after\":\"barang_diambil\"}','2026-02-23 13:36:33'),(70,1,'copy.update','site_settings',NULL,'{\"section\":\"landing\",\"keys\":[\"home.hero_title\",\"home.hero_subtitle\",\"hero_cta_text\",\"copy.landing.ready_panel_title\",\"copy.landing.ready_panel_subtitle\",\"copy.landing.flow_kicker\",\"copy.landing.flow_title\",\"copy.landing.flow_catalog_link\",\"copy.landing.step_1_title\",\"copy.landing.step_1_desc\",\"copy.landing.step_2_title\",\"copy.landing.step_2_desc\",\"copy.landing.step_3_title\",\"copy.landing.step_3_desc\",\"copy.landing.step_4_title\",\"copy.landing.step_4_desc\",\"copy.landing.quick_category_kicker\",\"copy.landing.quick_category_title\",\"copy.landing.quick_category_empty\"]}','2026-02-23 13:52:12'),(71,1,'category.store','categories','7','{\"name\":\"anuan\",\"slug\":\"anuan\"}','2026-02-23 13:55:29'),(72,1,'category.destroy','categories','7','{\"id\":7,\"name\":\"anuan\",\"slug\":\"anuan\"}','2026-02-23 13:57:27'),(73,1,'copy.update','site_settings',NULL,'{\"section\":\"contact\",\"keys\":[\"copy.trans.ui.contact.title\",\"copy.trans.ui.contact.subtitle\",\"copy.trans.ui.contact.info_title\",\"copy.trans.ui.contact.map_title\",\"copy.trans.ui.contact.map_empty\"]}','2026-02-23 14:01:42'),(74,1,'copy.update','site_settings',NULL,'{\"section\":\"contact\",\"keys\":[\"copy.trans.ui.contact.title\",\"copy.trans.ui.contact.subtitle\",\"copy.trans.ui.contact.info_title\",\"copy.trans.ui.contact.map_title\",\"copy.trans.ui.contact.map_empty\"]}','2026-02-23 14:04:18'),(75,1,'copy.update','site_settings',NULL,'{\"section\":\"contact\",\"keys\":[\"copy.trans.ui.contact.title\",\"copy.trans.ui.contact.subtitle\",\"copy.trans.ui.contact.info_title\",\"copy.trans.ui.contact.map_title\",\"copy.trans.ui.contact.map_empty\"]}','2026-02-23 14:04:38'),(76,1,'admin_login',NULL,NULL,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/605.1.15 (KHTML, like Gecko) Version\\/26.3 Safari\\/605.1.15\"}','2026-02-23 14:08:11'),(77,1,'website_settings.update','site_settings',NULL,'{\"keys\":[\"brand.name\",\"brand.tagline\",\"brand.logo_path\",\"brand.favicon_path\",\"seo.meta_title\",\"seo.meta_description\",\"contact.whatsapp\",\"social.instagram\",\"social.tiktok\",\"site.maintenance_enabled\"]}','2026-02-23 14:10:30'),(78,1,'website_settings.update','site_settings',NULL,'{\"keys\":[\"brand.name\",\"brand.tagline\",\"brand.logo_path\",\"brand.favicon_path\",\"seo.meta_title\",\"seo.meta_description\",\"contact.whatsapp\",\"social.instagram\",\"social.tiktok\",\"site.maintenance_enabled\"]}','2026-02-23 14:10:52'),(79,1,'content.update','site_settings',NULL,'{\"keys\":[\"home.hero_title\",\"home.hero_subtitle\",\"home.hero_image_path\",\"home.hero_image_path_alt\",\"home.overview_headline\",\"footer.about\",\"footer.address\",\"footer.whatsapp\",\"footer.instagram\",\"contact.email\",\"contact.phone\",\"contact.map_embed\"]}','2026-02-23 14:12:00'),(80,1,'user.view_detail','users','3','{\"email\":\"bck44565@gmail.com\"}','2026-02-23 14:16:14'),(81,1,'db_update','site_settings','64','{\"key\":\"copy.trans.ui.contact.map_empty\",\"value\":null,\"type\":\"text\",\"group\":\"contact\",\"updated_by_admin_id\":1}','2026-02-23 14:27:23');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_items`
--

DROP TABLE IF EXISTS `booking_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `equipment_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_items_booking_id_foreign` (`booking_id`),
  KEY `booking_items_equipment_id_foreign` (`equipment_id`),
  CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_items_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_items`
--

LOCK TABLES `booking_items` WRITE;
/*!40000 ALTER TABLE `booking_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_reference_unique` (`reference`),
  KEY `bookings_user_id_foreign` (`user_id`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
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
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:13;',1771856165),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1771856165;',1771856165),('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb','i:9;',1771856875),('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer','i:1771856875;',1771856875),('laravel-cache-copy.translation_overrides.lines','a:0:{}',1771795235),('laravel-cache-copy.translation_overrides.scoped','a:3:{s:1:\"*\";a:4:{s:21:\"ui.contact.info_title\";s:14:\"Address & Info\";s:20:\"ui.contact.map_title\";s:12:\"Location Map\";s:19:\"ui.contact.subtitle\";s:41:\"Contact us for booking and collaboration.\";s:16:\"ui.contact.title\";s:14:\"Contact Manake\";}s:2:\"id\";a:0:{}s:2:\"en\";a:0:{}}',1771858047),('laravel-cache-order_reminder_sync:1:20260222181','i:1;',1771760299),('laravel-cache-order_reminder_sync:1:20260223132','i:1;',1771829663),('laravel-cache-order_reminder_sync:3:20260223192','i:1;',1771851395),('laravel-cache-order_reminder_sync:3:20260223200','i:1;',1771852611),('laravel-cache-order_reminder_sync:3:20260223201','i:1;',1771853524),('laravel-cache-order_reminder_sync:3:20260223202','i:1;',1771854312),('laravel-cache-order_reminder_sync:3:20260223203','i:1;',1771855526),('laravel-cache-order_reminder_sync:3:20260223210','i:1;',1771856173),('laravel-cache-order_reminder_sync:3:20260223211','i:1;',1771857298),('laravel-cache-phone-otp:request:user:3:cooldown','b:1;',1771853324),('laravel-cache-phone-otp:request:user:3:hourly','i:2;',1771856864),('laravel-cache-site_content:contact.email','s:21:\"hello@manakerental.id\";',1771859544),('laravel-cache-site_content:footer.about','s:180:\"Manake is a professional production equipment rental service for cameras, lighting, drones, and audio. We focus on quality, speed, and transparent pricing to support your projects.\";',1771859544),('laravel-cache-site_content:footer.address','s:80:\"Manake Studio & Rental\nJl. Sutera Vision No. 12\nJakarta Selatan, Indonesia 12345\";',1771859545),('laravel-cache-site_content:footer.instagram','s:13:\"@manakerental\";',1771859544),('laravel-cache-site_content:footer.whatsapp','s:17:\"+62 812-3456-7890\";',1771859544),('laravel-cache-site_content:home.hero_subtitle','N;',1771859814),('laravel-cache-site_content:home.hero_title','N;',1771859814),('laravel-cache-site_setting:brand.logo_path','N;',1771860010),('laravel-cache-site_setting:brand.logo_path.en','N;',1771860010),('laravel-cache-site_setting:brand.logo_path.id','N;',1771860010),('laravel-cache-site_setting:brand.name','s:6:\"Manake\";',1771859452),('laravel-cache-site_setting:brand.name.en','N;',1771860457),('laravel-cache-site_setting:brand.name.id','N;',1771860443),('laravel-cache-site_setting:catalog.idle_hamburger_delay_ms','N;',1771858582),('laravel-cache-site_setting:catalog.idle_hamburger_delay_ms.en','N;',1771858582),('laravel-cache-site_setting:catalog.idle_hamburger_enabled','N;',1771858582),('laravel-cache-site_setting:catalog.idle_hamburger_enabled.en','N;',1771858582),('laravel-cache-site_setting:catalog.idle_hamburger_step_ms','N;',1771858582),('laravel-cache-site_setting:catalog.idle_hamburger_step_ms.en','N;',1771858582),('laravel-cache-site_setting:catalog.sidebar_submenu_duration_ms','N;',1771860010),('laravel-cache-site_setting:catalog.sidebar_submenu_duration_ms.en','N;',1771860010),('laravel-cache-site_setting:catalog.sidebar_submenu_duration_ms.id','N;',1771860010),('laravel-cache-site_setting:catalog.sidebar_submenu_enabled','N;',1771860010),('laravel-cache-site_setting:catalog.sidebar_submenu_enabled.en','N;',1771860010),('laravel-cache-site_setting:catalog.sidebar_submenu_enabled.id','N;',1771860010),('laravel-cache-site_setting:contact_email','N;',1771856227),('laravel-cache-site_setting:contact_email.en','N;',1771856227),('laravel-cache-site_setting:contact_map_embed','N;',1771859438),('laravel-cache-site_setting:contact_map_embed.en','N;',1771859438),('laravel-cache-site_setting:contact_whatsapp','N;',1771856227),('laravel-cache-site_setting:contact_whatsapp.en','N;',1771856227),('laravel-cache-site_setting:contact.email','N;',1771860010),('laravel-cache-site_setting:contact.email.en','N;',1771860010),('laravel-cache-site_setting:contact.email.id','N;',1771860010),('laravel-cache-site_setting:contact.phone.en','N;',1771859438),('laravel-cache-site_setting:copy.availability.busy_empty','N;',1771858488),('laravel-cache-site_setting:copy.availability.busy_empty.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.busy_title','N;',1771858488),('laravel-cache-site_setting:copy.availability.busy_title.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.calendar_title','N;',1771858488),('laravel-cache-site_setting:copy.availability.calendar_title.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.drag_hint','N;',1771858488),('laravel-cache-site_setting:copy.availability.drag_hint.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_available','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_available.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_busy','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_busy.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_total','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_total.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_units','N;',1771858488),('laravel-cache-site_setting:copy.availability.metric_units.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.modal_close','N;',1771858488),('laravel-cache-site_setting:copy.availability.modal_close.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.modal_date_title','N;',1771858488),('laravel-cache-site_setting:copy.availability.modal_date_title.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.modal_empty','N;',1771858488),('laravel-cache-site_setting:copy.availability.modal_empty.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.monthly_empty','N;',1771858488),('laravel-cache-site_setting:copy.availability.monthly_empty.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.monthly_title','N;',1771858488),('laravel-cache-site_setting:copy.availability.monthly_title.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_all_categories','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_all_categories.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_available_label','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_available_label.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_continue','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_continue.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_empty','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_empty.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_filter_label','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_filter_label.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_kicker','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_kicker.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_pick','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_pick.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_prefill_note','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_prefill_note.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_title','N;',1771858488),('laravel-cache-site_setting:copy.availability.range_title.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.ready_empty','N;',1771858488),('laravel-cache-site_setting:copy.availability.ready_empty.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.ready_title','N;',1771858488),('laravel-cache-site_setting:copy.availability.ready_title.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.reset_button','N;',1771858488),('laravel-cache-site_setting:copy.availability.reset_button.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.search_placeholder','N;',1771858488),('laravel-cache-site_setting:copy.availability.search_placeholder.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.selected_title','N;',1771858488),('laravel-cache-site_setting:copy.availability.selected_title.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.show_button','N;',1771858488),('laravel-cache-site_setting:copy.availability.show_button.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.subtitle','N;',1771858488),('laravel-cache-site_setting:copy.availability.subtitle.en','N;',1771858488),('laravel-cache-site_setting:copy.availability.title','N;',1771858488),('laravel-cache-site_setting:copy.availability.title.en','N;',1771858488),('laravel-cache-site_setting:copy.booking.active_title','N;',1771857442),('laravel-cache-site_setting:copy.booking.active_title.en','N;',1771857442),('laravel-cache-site_setting:copy.booking.active_title.id','N;',1771832383),('laravel-cache-site_setting:copy.booking.cta_text','N;',1771857442),('laravel-cache-site_setting:copy.booking.cta_text.en','N;',1771857442),('laravel-cache-site_setting:copy.booking.cta_text.id','N;',1771832383),('laravel-cache-site_setting:copy.booking.recent_title','N;',1771857442),('laravel-cache-site_setting:copy.booking.recent_title.en','N;',1771857442),('laravel-cache-site_setting:copy.booking.recent_title.id','N;',1771832383),('laravel-cache-site_setting:copy.booking.subtitle','N;',1771857442),('laravel-cache-site_setting:copy.booking.subtitle.en','N;',1771857442),('laravel-cache-site_setting:copy.booking.subtitle.id','N;',1771832383),('laravel-cache-site_setting:copy.booking.title','N;',1771857442),('laravel-cache-site_setting:copy.booking.title.en','N;',1771857442),('laravel-cache-site_setting:copy.booking.title.id','N;',1771832383),('laravel-cache-site_setting:copy.catalog.category_label','N;',1771858582),('laravel-cache-site_setting:copy.catalog.category_label.en','N;',1771858582),('laravel-cache-site_setting:copy.catalog.empty_subtitle','N;',1771858582),('laravel-cache-site_setting:copy.catalog.empty_subtitle.en','N;',1771858582),('laravel-cache-site_setting:copy.catalog.empty_title','N;',1771858582),('laravel-cache-site_setting:copy.catalog.empty_title.en','N;',1771858582),('laravel-cache-site_setting:copy.catalog.subtitle','N;',1771858582),('laravel-cache-site_setting:copy.catalog.subtitle.en','N;',1771858582),('laravel-cache-site_setting:copy.catalog.title','N;',1771858582),('laravel-cache-site_setting:copy.catalog.title.en','N;',1771858582),('laravel-cache-site_setting:copy.checkout.back_to_cart','N;',1771855818),('laravel-cache-site_setting:copy.checkout.back_to_cart.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.confirm_profile','N;',1771855818),('laravel-cache-site_setting:copy.checkout.confirm_profile.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.detail_title','N;',1771855818),('laravel-cache-site_setting:copy.checkout.detail_title.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.empty_cart','N;',1771855818),('laravel-cache-site_setting:copy.checkout.empty_cart.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.no_items','N;',1771855818),('laravel-cache-site_setting:copy.checkout.no_items.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.payment_note','N;',1771855818),('laravel-cache-site_setting:copy.checkout.payment_note.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.payment_title','N;',1771855818),('laravel-cache-site_setting:copy.checkout.payment_title.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.profile_hint','N;',1771855818),('laravel-cache-site_setting:copy.checkout.profile_hint.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.profile_title','N;',1771855818),('laravel-cache-site_setting:copy.checkout.profile_title.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.submit_button','N;',1771855818),('laravel-cache-site_setting:copy.checkout.submit_button.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.submit_processing','N;',1771855818),('laravel-cache-site_setting:copy.checkout.submit_processing.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.subtitle','N;',1771855818),('laravel-cache-site_setting:copy.checkout.subtitle.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_estimate','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_estimate.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_subtotal','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_subtotal.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_tax','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_tax.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_title','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_title.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_total','N;',1771855818),('laravel-cache-site_setting:copy.checkout.summary_total.en','N;',1771855818),('laravel-cache-site_setting:copy.checkout.title','N;',1771855818),('laravel-cache-site_setting:copy.checkout.title.en','N;',1771855818),('laravel-cache-site_setting:copy.landing.flow_catalog_link','s:16:\"Lihat semua alat\";',1771858346),('laravel-cache-site_setting:copy.landing.flow_catalog_link.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.flow_catalog_link.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.flow_kicker','s:11:\"Alur Rental\";',1771858346),('laravel-cache-site_setting:copy.landing.flow_kicker.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.flow_kicker.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.flow_title','s:28:\"Biar proses sewa tidak ribet\";',1771858346),('laravel-cache-site_setting:copy.landing.flow_title.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.flow_title.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.quick_category_empty','s:28:\"Belum ada kategori tersedia.\";',1771858346),('laravel-cache-site_setting:copy.landing.quick_category_empty.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.quick_category_empty.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.quick_category_kicker','s:14:\"Kategori Cepat\";',1771858346),('laravel-cache-site_setting:copy.landing.quick_category_kicker.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.quick_category_kicker.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.quick_category_title','s:36:\"Akses langsung ke kebutuhan produksi\";',1771858346),('laravel-cache-site_setting:copy.landing.quick_category_title.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.quick_category_title.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.ready_panel_subtitle','s:48:\"Item live dari inventory yang tersedia hari ini.\";',1771858346),('laravel-cache-site_setting:copy.landing.ready_panel_subtitle.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.ready_panel_subtitle.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.ready_panel_title','s:11:\"Siap Disewa\";',1771858346),('laravel-cache-site_setting:copy.landing.ready_panel_title.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.ready_panel_title.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_1_desc','s:60:\"Filter berdasarkan kategori, status siap, dan budget harian.\";',1771858346),('laravel-cache-site_setting:copy.landing.step_1_desc.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_1_desc.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_1_title','s:10:\"Pilih Alat\";',1771858346),('laravel-cache-site_setting:copy.landing.step_1_title.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_1_title.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_2_desc','s:73:\"Data identitas dan kontak disimpan agar transaksi berikutnya lebih cepat.\";',1771858346),('laravel-cache-site_setting:copy.landing.step_2_desc.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_2_desc.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_2_title','s:10:\"Isi Profil\";',1771858346),('laravel-cache-site_setting:copy.landing.step_2_title.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_2_title.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_3_desc','s:62:\"Pilih metode pembayaran favorit tanpa pindah halaman berulang.\";',1771858346),('laravel-cache-site_setting:copy.landing.step_3_desc.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_3_desc.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_3_title','s:18:\"Bayar via Midtrans\";',1771858346),('laravel-cache-site_setting:copy.landing.step_3_title.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_3_title.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_4_desc','s:73:\"Setelah lunas, resi bisa dibuka dan dicetak langsung dari detail pesanan.\";',1771858346),('laravel-cache-site_setting:copy.landing.step_4_desc.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_4_desc.id','N;',1771859798),('laravel-cache-site_setting:copy.landing.step_4_title','s:9:\"Buat Resi\";',1771858346),('laravel-cache-site_setting:copy.landing.step_4_title.en','N;',1771859814),('laravel-cache-site_setting:copy.landing.step_4_title.id','N;',1771859798),('laravel-cache-site_setting:copy.order_detail.back_label','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.back_label.en','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.items_title','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.items_title.en','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.order_number_label','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.order_number_label.en','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.payment_title','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.payment_title.en','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.progress_title','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.progress_title.en','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.subtitle','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.subtitle.en','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.title','N;',1771857017),('laravel-cache-site_setting:copy.order_detail.title.en','N;',1771857017),('laravel-cache-site_setting:copy.rules_page.cta_primary','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.cta_primary.en','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.cta_secondary','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.cta_secondary.en','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.kicker','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.kicker.en','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.operational_title','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.operational_title.en','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.subtitle','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.subtitle.en','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.title','N;',1771824683),('laravel-cache-site_setting:copy.rules_page.title.en','N;',1771824683),('laravel-cache-site_setting:footer_address','N;',1771860010),('laravel-cache-site_setting:footer_address.en','N;',1771860010),('laravel-cache-site_setting:footer_address.id','N;',1771860010),('laravel-cache-site_setting:footer_copyright','s:40:\"2026 Manake Rental. All rights reserved.\";',1771859999),('laravel-cache-site_setting:footer_copyright.en','N;',1771859999),('laravel-cache-site_setting:footer_copyright.id','N;',1771860010),('laravel-cache-site_setting:footer_description','N;',1771860010),('laravel-cache-site_setting:footer_description.en','N;',1771860010),('laravel-cache-site_setting:footer_description.id','N;',1771860010),('laravel-cache-site_setting:footer_email','N;',1771860010),('laravel-cache-site_setting:footer_email.en','N;',1771860010),('laravel-cache-site_setting:footer_email.id','N;',1771860010),('laravel-cache-site_setting:footer_phone','N;',1771860010),('laravel-cache-site_setting:footer_phone.en','N;',1771860010),('laravel-cache-site_setting:footer_phone.id','N;',1771860010),('laravel-cache-site_setting:footer.about','N;',1771860010),('laravel-cache-site_setting:footer.about.en','N;',1771860010),('laravel-cache-site_setting:footer.about.id','N;',1771860010),('laravel-cache-site_setting:footer.address','N;',1771860010),('laravel-cache-site_setting:footer.address.en','N;',1771860010),('laravel-cache-site_setting:footer.address.id','N;',1771860010),('laravel-cache-site_setting:footer.instagram','N;',1771860010),('laravel-cache-site_setting:footer.instagram.en','N;',1771860010),('laravel-cache-site_setting:footer.instagram.id','N;',1771860010),('laravel-cache-site_setting:footer.rules_link','s:22:\"Rules Sewa & Kebijakan\";',1771859999),('laravel-cache-site_setting:footer.rules_link.en','N;',1771859999),('laravel-cache-site_setting:footer.rules_link.id','N;',1771860010),('laravel-cache-site_setting:footer.rules_note','s:67:\"Pelajari aturan booking, reschedule, buffer, dan denda operasional.\";',1771859999),('laravel-cache-site_setting:footer.rules_note.en','N;',1771859999),('laravel-cache-site_setting:footer.rules_note.id','N;',1771860010),('laravel-cache-site_setting:footer.rules_title','s:12:\"Panduan Sewa\";',1771859999),('laravel-cache-site_setting:footer.rules_title.en','N;',1771859999),('laravel-cache-site_setting:footer.rules_title.id','N;',1771860010),('laravel-cache-site_setting:footer.whatsapp','N;',1771860010),('laravel-cache-site_setting:footer.whatsapp.en','N;',1771860010),('laravel-cache-site_setting:footer.whatsapp.id','N;',1771860010),('laravel-cache-site_setting:hero_subtitle','N;',1771859814),('laravel-cache-site_setting:hero_subtitle.en','N;',1771859814),('laravel-cache-site_setting:hero_subtitle.id','N;',1771859798),('laravel-cache-site_setting:hero_title','N;',1771859814),('laravel-cache-site_setting:hero_title.en','N;',1771859814),('laravel-cache-site_setting:hero_title.id','N;',1771859798),('laravel-cache-site_setting:home.hero_image_path','N;',1771859814),('laravel-cache-site_setting:home.hero_image_path_alt','N;',1771859814),('laravel-cache-site_setting:home.hero_image_path_alt.en','N;',1771859814),('laravel-cache-site_setting:home.hero_image_path_alt.id','N;',1771859798),('laravel-cache-site_setting:home.hero_image_path.en','N;',1771859814),('laravel-cache-site_setting:home.hero_image_path.id','N;',1771859798),('laravel-cache-site_setting:home.hero_subtitle','N;',1771859814),('laravel-cache-site_setting:home.hero_subtitle.en','N;',1771859814),('laravel-cache-site_setting:home.hero_subtitle.id','N;',1771859798),('laravel-cache-site_setting:home.hero_title','N;',1771859814),('laravel-cache-site_setting:home.hero_title.en','N;',1771859814),('laravel-cache-site_setting:home.hero_title.id','N;',1771859798),('laravel-cache-site_setting:meta_description','N;',1771860010),('laravel-cache-site_setting:meta_description.en','N;',1771860010),('laravel-cache-site_setting:meta_description.id','N;',1771860010),('laravel-cache-site_setting:meta_title','N;',1771860010),('laravel-cache-site_setting:meta_title.en','N;',1771860010),('laravel-cache-site_setting:meta_title.id','N;',1771860010),('laravel-cache-site_setting:seo.meta_description','N;',1771860010),('laravel-cache-site_setting:seo.meta_description.en','N;',1771860010),('laravel-cache-site_setting:seo.meta_description.id','N;',1771860010),('laravel-cache-site_setting:seo.meta_title','N;',1771860010),('laravel-cache-site_setting:seo.meta_title.en','N;',1771860010),('laravel-cache-site_setting:seo.meta_title.id','N;',1771860010),('laravel-cache-site_setting:site_name','N;',1771860010),('laravel-cache-site_setting:site_name.en','N;',1771860010),('laravel-cache-site_setting:site_name.id','N;',1771860010),('laravel-cache-site_setting:site_tagline','s:39:\"Professional rental equipment platform.\";',1771859999),('laravel-cache-site_setting:site_tagline.en','N;',1771859999),('laravel-cache-site_setting:site_tagline.id','N;',1771860010),('laravel-cache-site_setting:social_instagram','N;',1771860010),('laravel-cache-site_setting:social_instagram.en','N;',1771860010),('laravel-cache-site_setting:social_instagram.id','N;',1771860010),('laravel-cache-site_setting:social_whatsapp','N;',1771860010),('laravel-cache-site_setting:social_whatsapp.en','N;',1771860010),('laravel-cache-site_setting:social_whatsapp.id','N;',1771860010),('laravel-cache-site_setting:typography.body_color','N;',1771860457),('laravel-cache-site_setting:typography.body_color.en','N;',1771860457),('laravel-cache-site_setting:typography.body_color.id','N;',1771860443),('laravel-cache-site_setting:typography.body_scale','N;',1771860457),('laravel-cache-site_setting:typography.body_scale.en','N;',1771860457),('laravel-cache-site_setting:typography.body_scale.id','N;',1771860443),('laravel-cache-site_setting:typography.body_style','N;',1771860457),('laravel-cache-site_setting:typography.body_style.en','N;',1771860457),('laravel-cache-site_setting:typography.body_style.id','N;',1771860443),('laravel-cache-site_setting:typography.body_weight','N;',1771860457),('laravel-cache-site_setting:typography.body_weight.en','N;',1771860457),('laravel-cache-site_setting:typography.body_weight.id','N;',1771860443),('laravel-cache-site_setting:typography.heading_color','N;',1771860457),('laravel-cache-site_setting:typography.heading_color.en','N;',1771860457),('laravel-cache-site_setting:typography.heading_color.id','N;',1771860443),('laravel-cache-site_setting:typography.heading_scale','N;',1771860457),('laravel-cache-site_setting:typography.heading_scale.en','N;',1771860457),('laravel-cache-site_setting:typography.heading_scale.id','N;',1771860443),('laravel-cache-site_setting:typography.heading_style','N;',1771860457),('laravel-cache-site_setting:typography.heading_style.en','N;',1771860457),('laravel-cache-site_setting:typography.heading_style.id','N;',1771860443),('laravel-cache-site_setting:typography.heading_weight','N;',1771860457),('laravel-cache-site_setting:typography.heading_weight.en','N;',1771860457),('laravel-cache-site_setting:typography.heading_weight.id','N;',1771860443),('laravel-cache-site_setting:typography.subheading_color','N;',1771860457),('laravel-cache-site_setting:typography.subheading_color.en','N;',1771860457),('laravel-cache-site_setting:typography.subheading_color.id','N;',1771860443);
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
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Audio','audio',NULL,'2026-02-17 19:19:10','2026-02-17 19:19:10'),(2,'Camera','camera',NULL,'2026-02-17 19:19:24','2026-02-17 19:19:24'),(3,'Lighting','lighting',NULL,'2026-02-17 19:19:40','2026-02-17 19:19:40'),(4,'Lens','lensa',NULL,'2026-02-17 19:19:56','2026-02-17 19:19:56'),(5,'Monitor & Wireless Control','monitor-wireless',NULL,'2026-02-20 19:11:17','2026-02-20 19:11:17'),(6,'Aksesoris','aksesoris',NULL,'2026-02-20 19:36:27','2026-02-20 19:36:27');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment_maintenance_windows`
--

DROP TABLE IF EXISTS `equipment_maintenance_windows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment_maintenance_windows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipment_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_maintenance_range_idx` (`equipment_id`,`start_date`,`end_date`),
  CONSTRAINT `equipment_maintenance_windows_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_maintenance_windows`
--

LOCK TABLES `equipment_maintenance_windows` WRITE;
/*!40000 ALTER TABLE `equipment_maintenance_windows` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipment_maintenance_windows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipments`
--

DROP TABLE IF EXISTS `equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `specifications` text COLLATE utf8mb4_unicode_ci,
  `price_per_day` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ready',
  `stock` int NOT NULL DEFAULT '1',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipments_slug_unique` (`slug`),
  KEY `equipments_category_id_foreign` (`category_id`),
  CONSTRAINT `equipments_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipments`
--

LOCK TABLES `equipments` WRITE;
/*!40000 ALTER TABLE `equipments` DISABLE KEYS */;
INSERT INTO `equipments` VALUES (1,3,'Aputure Light Storm 600D Pro (Standard)','aputure-600d','Color: 5600K, CRI/TLCI: 96\r\nBeam Angle with 55° Reflector\r\nWireless DMX, Bluetooth Control\r\n4 Dimming Modes, Wireless Range: 328\'\r\nComparable to a 1200W HMI\r\n8 Built-In Lighting Effects\r\nAC 100-240V, 50/60Hz\r\nControl Box\r\nHyper Reflector\r\nWeatherproof Head Cable\r\nNeutrik AC Cable\r\nVA Remote RC1+\r\nLightning Clamp\r\nRolling Carry Case\r\nLighstand','Color: 5600K, CRI/TLCI: 96\r\nBeam Angle with 55° Reflector\r\nWireless DMX, Bluetooth Control\r\n4 Dimming Modes, Wireless Range: 328\'\r\nComparable to a 1200W HMI\r\n8 Built-In Lighting Effects\r\nAC 100-240V, 50/60Hz\r\nControl Box\r\nHyper Reflector\r\nWeatherproof Head Cable\r\nNeutrik AC Cable\r\nVA Remote RC1+\r\nLightning Clamp\r\nRolling Carry Case\r\nLighstand',450000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phps8pp8u3uel8r6v0uZsi','equipments/D2redM1dizZ7Z0U7pv7howAVH3tIsMiov1Kz78BZ.png','2026-02-17 19:22:09','2026-02-17 19:23:35'),(2,1,'Eartec UltraLite 7-Person Full-Duplex Wireless Intercom','eartech-ultralite7','Intercom System for 7 People\r\nNo Wires or Belt-Worn Items\r\n1 Single-Ear Master Headset\r\n6 Single-Ear Remote Headsets\r\n7 Rechargeable Batteries\r\n8-Bay Multi-Port Charger\r\nSoft Medium Case','Intercom System for 7 People\r\nNo Wires or Belt-Worn Items\r\n1 Single-Ear Master Headset\r\n6 Single-Ear Remote Headsets\r\n7 Rechargeable Batteries\r\n8-Bay Multi-Port Charger\r\nSoft Medium Case',500000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpqhd4ebam6s7k3dYNWXn','equipments/38pwQtGTJasq9AcjfHtGsFvoMh6MW50Wgi6d2FII.png','2026-02-17 19:29:28','2026-02-17 19:29:28'),(3,3,'Godox TL60 Tube Light RGB Kit (1 Box isi 4 Pcs)','godox-tl60','4 Tube Lights w/ Power Adapters\r\nColor: 2700-6500K, CRI/TLCI: 96/98\r\nRGB w/ HSI Control, 40 Built-In Filters\r\nLength: 29.5\" ~ 75cm\r\nBattery Runtime: 2 Hours\r\nPhone, DMX, 2.4 GHz Wireless Control\r\nDims 0-100%, 98-164\' Wireless Range\r\n32 Channels, 6 Groups\r\nPower Adapter, DC Cable, Carry Bag\r\n4 x Godox TL60 Tube Light\r\n4 x Power Adapters\r\n4 x DC Cables\r\n4 x Retaining Clips\r\n4 x Toggle Clips\r\n4 x Fixed Bases\r\n1 x Remote Control\r\n4 x RJ-45 Cables\r\n8 x Wire Ropes\r\n1 x Carrying Bag','4 Tube Lights w/ Power Adapters\r\nColor: 2700-6500K, CRI/TLCI: 96/98\r\nRGB w/ HSI Control, 40 Built-In Filters\r\nLength: 29.5\" ~ 75cm\r\nBattery Runtime: 2 Hours\r\nPhone, DMX, 2.4 GHz Wireless Control\r\nDims 0-100%, 98-164\' Wireless Range\r\n32 Channels, 6 Groups\r\nPower Adapter, DC Cable, Carry Bag\r\n4 x Godox TL60 Tube Light\r\n4 x Power Adapters\r\n4 x DC Cables\r\n4 x Retaining Clips\r\n4 x Toggle Clips\r\n4 x Fixed Bases\r\n1 x Remote Control\r\n4 x RJ-45 Cables\r\n8 x Wire Ropes\r\n1 x Carrying Bag',300000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpc0emfu98i0ht0Nr5igD','equipments/sGJbYiKMDdF7MXdIUTAtYHn79KS0BptEuuEjOBTz.png','2026-02-17 19:30:51','2026-02-17 19:30:51'),(4,2,'GoPro Hero 4 Black','gopro-hero4','Features 4K30, 2.7K50 dan 1080p120 video\r\nBuilt-in Wi-Fi & Bluetooth\r\n12MP / 30 fps Burst\r\nSuperView Mode\r\nAuto low Light Mode\r\nGoPro App + Remote Compatible\r\nWaterproof Housing (1x)\r\nBattery (3x)\r\nCharger (1x)\r\nMicro SD 32Gb (1x)\r\nSuction Cup (1x)\r\nFloating Monopod (1x)\r\nStandard Mount 3M (3x)\r\nStandard Mount Tripod (1x)','Features 4K30, 2.7K50 dan 1080p120 video\r\nBuilt-in Wi-Fi & Bluetooth\r\n12MP / 30 fps Burst\r\nSuperView Mode\r\nAuto low Light Mode\r\nGoPro App + Remote Compatible\r\nWaterproof Housing (1x)\r\nBattery (3x)\r\nCharger (1x)\r\nMicro SD 32Gb (1x)\r\nSuction Cup (1x)\r\nFloating Monopod (1x)\r\nStandard Mount 3M (3x)\r\nStandard Mount Tripod (1x)',125000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php4eeah7vsf4sn25jQjm1','equipments/KHtzNjc5wtURyOslPfLbD1h65tS3GwP4N7idoQQV.png','2026-02-17 19:32:25','2026-02-17 19:32:25'),(5,2,'Lumix S1H Mirrorless (Body + Lens Adapter)','lumix-s1h','24.2MP Full-Frame CMOS Sensor\r\n6K24p Video, 4:2:2 10-Bit DCI 4K/UHD 4K\r\nV-Log, Dual Native ISO, HFR with Sound\r\n5.76m-Dot 0.78x-Magnification OLED LVF\r\nPanasonic DMW-BLJ31 Battery (7.2V, 3100mAh) (3x)\r\nPanasonic DMW-BTC14 Battery Charger (1x)\r\nAC Adapter and AC Mains Lead (1x)\r\nUSB Type-C to USB Type-C Cable (1x)\r\nUSB Type-C to USB Type-A Cable (1x)\r\nShoulder Strap (1x)\r\nCable Holder (1x)\r\nSD Card Extreme pro 64gb (2x)\r\nEF/PL Adapter to L Mount (1x)','24.2MP Full-Frame CMOS Sensor\r\n6K24p Video, 4:2:2 10-Bit DCI 4K/UHD 4K\r\nV-Log, Dual Native ISO, HFR with Sound\r\n5.76m-Dot 0.78x-Magnification OLED LVF\r\nPanasonic DMW-BLJ31 Battery (7.2V, 3100mAh) (3x)\r\nPanasonic DMW-BTC14 Battery Charger (1x)\r\nAC Adapter and AC Mains Lead (1x)\r\nUSB Type-C to USB Type-C Cable (1x)\r\nUSB Type-C to USB Type-A Cable (1x)\r\nShoulder Strap (1x)\r\nCable Holder (1x)\r\nSD Card Extreme pro 64gb (2x)\r\nEF/PL Adapter to L Mount (1x)',650000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php8dg29vcctormblCRP2N','equipments/4bONfUM8WKI9EW5DDMdYSqmeE9lveXg3KokNxMkU.png','2026-02-17 19:33:56','2026-02-17 19:33:56'),(6,4,'Samyang V-AF T1.9 Lens for Sony FE (24mm, 35mm, 75mm)','samyang-sonyfe','Autofocus cine lens for full-frame sensors\r\nCompact size and lightweight for gimbals and drones\r\nLED Indicator for recording status\r\nSoft manual focus ring works with follow focus systems\r\nCustom Button with Focus Save function\r\nCustom Switch to alter focus ring function\r\n1x Samyang V-AF 24mm T1.9 Cine Lens\r\n1x Samyang V-AF 35mm T1.9 Cine Lens\r\n1x Samyang V-AF 75mm T1.9 Cine Lens','Autofocus cine lens for full-frame sensors\r\nCompact size and lightweight for gimbals and drones\r\nLED Indicator for recording status\r\nSoft manual focus ring works with follow focus systems\r\nCustom Button with Focus Save function\r\nCustom Switch to alter focus ring function\r\n1x Samyang V-AF 24mm T1.9 Cine Lens\r\n1x Samyang V-AF 35mm T1.9 Cine Lens\r\n1x Samyang V-AF 75mm T1.9 Cine Lens',400000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phprd5rdctmkim77r13q3g','equipments/8kPyGlEGbYD2ME5K0kI1rqykWJkNEpT3p5LjjcCL.png','2026-02-17 19:34:50','2026-02-17 19:34:50'),(7,3,'Light Meter Sekonic Speedmaster','sekonic-speedmaster','Incident Metering for Ambient & Flash\r\n1° Spot Metering with Viewfinder\r\nMeasuring Range: -5 to 22.9 EV (ISO 100)\r\nIlluminance Range: 0.1 to 2,000,000 lux\r\nExtensive Range of Cine & Video Settings\r\nHSS Flash & Flash Duration Measurements\r\nFlash Analyzing Function; Extended Range\r\n2.7\" Touchscreen LCD; All-Weather Design\r\nOptional Radio Control Modules','Incident Metering for Ambient & Flash\r\n1° Spot Metering with Viewfinder\r\nMeasuring Range: -5 to 22.9 EV (ISO 100)\r\nIlluminance Range: 0.1 to 2,000,000 lux\r\nExtensive Range of Cine & Video Settings\r\nHSS Flash & Flash Duration Measurements\r\nFlash Analyzing Function; Extended Range\r\n2.7\" Touchscreen LCD; All-Weather Design\r\nOptional Radio Control Modules',100000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpk149ptbbu63gfNKmJ9W','equipments/60qABlECPl6cy0DrHZ2o4SQbekQduUxf3fd5Vfbq.png','2026-02-17 19:36:13','2026-02-17 19:36:13'),(8,2,'Sony a7S Mark III (Body Only)','sony-a7s3','12MP Full-Frame Exmor R BSI CMOS Sensor\r\nUHD 4K 120p Video, 10-Bit 4:2:2 Internal\r\n16-Bit Raw Output, HLG & S-Log3 Gammas\r\n759-Point Fast Hybrid AF\r\n9.44m-Dot QXGA OLED EVF\r\n3.0\" 1.44m-Dot Vari-Angle Touchscreen\r\n5-Axis SteadyShot Image Stabilization\r\nExtended ISO 40-409600, 10 fps Shooting\r\nDual CFexpress Type A/SD Card Slots\r\nSony Alpha a7S III Mirrorless Camera (Body) (1x)\r\nSony NP-FZ100 Battery (2280mAh) (3x)\r\nSony BC-QZ1 Battery Charger (1x)\r\nCFExpress Memory 80Gb (2x)\r\nCF Express Reader (1x)\r\nUSB Type-C to Type-A Cable (1x)\r\nShoulder Strap (1x)','12MP Full-Frame Exmor R BSI CMOS Sensor\r\nUHD 4K 120p Video, 10-Bit 4:2:2 Internal\r\n16-Bit Raw Output, HLG & S-Log3 Gammas\r\n759-Point Fast Hybrid AF\r\n9.44m-Dot QXGA OLED EVF\r\n3.0\" 1.44m-Dot Vari-Angle Touchscreen\r\n5-Axis SteadyShot Image Stabilization\r\nExtended ISO 40-409600, 10 fps Shooting\r\nDual CFexpress Type A/SD Card Slots\r\nSony Alpha a7S III Mirrorless Camera (Body) (1x)\r\nSony NP-FZ100 Battery (2280mAh) (3x)\r\nSony BC-QZ1 Battery Charger (1x)\r\nCFExpress Memory 80Gb (2x)\r\nCF Express Reader (1x)\r\nUSB Type-C to Type-A Cable (1x)\r\nShoulder Strap (1x)',650000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php17e3v7sukdmk28QXU0w','equipments/4NHSpRFqyzNjv3JBzzuo437xtomuSEiTxTl2CBKy.png','2026-02-17 19:37:51','2026-02-17 19:37:51'),(9,4,'Sony FE 85mm f/1.4 GM Lens','sony-85mm','E-Mount Lens/Full-Frame Format\r\nAperture Range: f/1.4 to f/16\r\nOne XA Element and Three ED Elements\r\nNano AR Coating\r\nLinear Super Sonic Wave AF Motor\r\nAF/MF Switch; Internal Focus\r\nFocus Hold Button\r\nPhysical Aperture Ring; De-Click Switch\r\nDust and Moisture-Resistant Construction\r\nEleven-Blade Circular Diaphragm','E-Mount Lens/Full-Frame Format\r\nAperture Range: f/1.4 to f/16\r\nOne XA Element and Three ED Elements\r\nNano AR Coating\r\nLinear Super Sonic Wave AF Motor\r\nAF/MF Switch; Internal Focus\r\nFocus Hold Button\r\nPhysical Aperture Ring; De-Click Switch\r\nDust and Moisture-Resistant Construction\r\nEleven-Blade Circular Diaphragm',200000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpn454rvr8ad0t60FUuIT','equipments/wpiZBGaEDgR5YiJhUQJiHX7RhlrKNHrhFLvxr3Vj.png','2026-02-17 19:39:08','2026-02-17 19:39:08'),(10,4,'Sony FE 135mm f/1.8 GM Lens','sony-gm-135mm','ARAK FOKUS MINIMUM = 0,7 m ( 2,3 ft)\r\nRASIO PERBESARAN MAKSIMUM (X) = 0,25\r\nDIAMETER FILTER (MM) = 82\r\nBERAT = 950 g (33,6 oz.)','ARAK FOKUS MINIMUM = 0,7 m ( 2,3 ft)\r\nRASIO PERBESARAN MAKSIMUM (X) = 0,25\r\nDIAMETER FILTER (MM) = 82\r\nBERAT = 950 g (33,6 oz.)',200000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpdk7677iru5mfblV0mlH','equipments/MZsm5ooz3cQinPDZe0FsdTGKc77bjptfAQc7efiH.png','2026-02-17 19:39:51','2026-02-17 19:39:51'),(11,1,'HT WLAN UHF','wlan-uhf','Frequency Range: UHF 400-470 MHz (Single Band)\r\nChannel Capacity: 16 channels\r\nTransmit Output Power: 2W - 5W (variable)\r\nOperating Voltage: DC 3.7V (1500mAh Li-ion battery)\r\nRange: Approximately 1-6 km depending on terrain and conditions\r\nAntenna Impedance: 50 Ohms\r\nDimensions: Approx. 96mm x 55mm x 22mm to 110x50x32mm\r\nWeight: Approx. 198g (with battery and antenna) \r\nChannel Spacing: 25KHz\r\nFrequency Stability: ±2.5ppm\r\nReceiver Sensitivity: ≤0.16µV (12dB SINAD)\r\nModulation: F3E\r\nAudio Power Output: 1000mW\r\nFunctions: CTCSS/DCS, Time Out Timer (TOT), Scan Function, Voice Prompt (Chinese/English), Low Battery Alert\r\nCharging: USB charging capable (mini-USB connector)','Frequency Range: UHF 400-470 MHz (Single Band)\r\nChannel Capacity: 16 channels\r\nTransmit Output Power: 2W - 5W (variable)\r\nOperating Voltage: DC 3.7V (1500mAh Li-ion battery)\r\nRange: Approximately 1-6 km depending on terrain and conditions\r\nAntenna Impedance: 50 Ohms\r\nDimensions: Approx. 96mm x 55mm x 22mm to 110x50x32mm\r\nWeight: Approx. 198g (with battery and antenna) \r\nChannel Spacing: 25KHz\r\nFrequency Stability: ±2.5ppm\r\nReceiver Sensitivity: ≤0.16µV (12dB SINAD)\r\nModulation: F3E\r\nAudio Power Output: 1000mW\r\nFunctions: CTCSS/DCS, Time Out Timer (TOT), Scan Function, Voice Prompt (Chinese/English), Low Battery Alert\r\nCharging: USB charging capable (mini-USB connector)',10000,'ready',20,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpplv5geet1ib14xu9j2n','equipments/nrjKifQvmagq3uwmOLwtsWIh1ewWCr4aS1bv71Ek.png','2026-02-17 19:45:21','2026-02-17 19:45:21'),(12,1,'Audio Recorder Zoom F8 Multi Track','zoom-f8','8x XLR/TRS Combo Jacks with +75 dB Gain\r\nUp to 192 kHz/ 24-Bit PCM Recording\r\nRecord Up to 10 Tracks Simultaneously\r\nDual Channel Recordings at Two Levels\r\nWorks with Zoom Microphone Capsules\r\n2x SDXC Card Slots\r\nBuilt-In Slate Microphone\r\nAC, External DC Adaptor\r\nSupports SMPTE Timecode I/O\r\n+24V/+48V Phantom Power\r\nNot including battery','8x XLR/TRS Combo Jacks with +75 dB Gain\r\nUp to 192 kHz/ 24-Bit PCM Recording\r\nRecord Up to 10 Tracks Simultaneously\r\nDual Channel Recordings at Two Levels\r\nWorks with Zoom Microphone Capsules\r\n2x SDXC Card Slots\r\nBuilt-In Slate Microphone\r\nAC, External DC Adaptor\r\nSupports SMPTE Timecode I/O\r\n+24V/+48V Phantom Power\r\nNot including battery',250000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpi2nblmsfnrd66m7crKx','equipments/VphfWgOmSRAr4K76oxNfYSYF29gQqBmNeb8BonnP.png','2026-02-17 19:46:23','2026-02-17 19:46:23'),(13,2,'Nikon ZR Cinema Camera 6K','nikon-zr','Fusion of RED + Nikon Cinema Technology\r\n6K Full-Frame Z-Mount Cinema Camera\r\nREDCODE RAW R3D + N-RAW Recording\r\nProRes RAW/HQ, H.265/H.264 Codecs\r\n32-Bit Float Audio, Dual-Base ISO\r\nBright, Folding 4\" DCI-P3 Display\r\nBuilt-In 3D LUTs, Digital Accessory Shoe\r\nDual Built-In Microphones\r\n15+ Stops Advertised Dynamic Range\r\nNikon ZR Cinema Camera 6K\r\nNikon EN-EL15c Lithium-Ion Battery (2280mAh)\r\nNikon UC-E25 USB Cable\r\nNikon BS-D1 Digital Accessory Shoe Cover\r\nNikon BF-N1 Body Cap\r\nNikon AN-DC26 Camera Strap','Fusion of RED + Nikon Cinema Technology\r\n6K Full-Frame Z-Mount Cinema Camera\r\nREDCODE RAW R3D + N-RAW Recording\r\nProRes RAW/HQ, H.265/H.264 Codecs\r\n32-Bit Float Audio, Dual-Base ISO\r\nBright, Folding 4\" DCI-P3 Display\r\nBuilt-In 3D LUTs, Digital Accessory Shoe\r\nDual Built-In Microphones\r\n15+ Stops Advertised Dynamic Range\r\nNikon ZR Cinema Camera 6K\r\nNikon EN-EL15c Lithium-Ion Battery (2280mAh)\r\nNikon UC-E25 USB Cable\r\nNikon BS-D1 Digital Accessory Shoe Cover\r\nNikon BF-N1 Body Cap\r\nNikon AN-DC26 Camera Strap',500000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpu5k8cdbog1009VMzwnn','equipments/p5W7WO2ERUqAuwB9Tc3DShWOuqPqvj8yopAUOXO4.png','2026-02-20 18:15:23','2026-02-20 18:15:23'),(14,2,'Blackmagic Design Pocket Cinema Camera 6K EF Mount (Body Only)','blackmagic-6k','Active Canon EF Mount\r\nSuper 35-Sized HDR Sensor\r\nRecord 6K 6144 x 3456 up to 50 fps\r\nDual Native 400 & 3200 ISO to 25,600\r\nBattery LP-E6 (6x)\r\nBattery Charger (2x)\r\nAC Adaptor (1x)\r\nCFast Card 128gb (2x)\r\nSSD 500gb (1x)\r\nCFast Reader (1x)','Active Canon EF Mount\r\nSuper 35-Sized HDR Sensor\r\nRecord 6K 6144 x 3456 up to 50 fps\r\nDual Native 400 & 3200 ISO to 25,600\r\nBattery LP-E6 (6x)\r\nBattery Charger (2x)\r\nAC Adaptor (1x)\r\nCFast Card 128gb (2x)\r\nSSD 500gb (1x)\r\nCFast Reader (1x)',600000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpmh400ca1u10kcmMetYg','equipments/4uKf8Uqw1MMYplaQouRG7aKHPAJzRfK7EOu6KibS.png','2026-02-20 18:16:51','2026-02-20 18:16:51'),(15,2,'FUJIFILM X-T4 Mirrorless Camera (Black)','fujifilm-xt4','FUJIFILM X-T4 Mirrorless Camera (Black)\r\nFUJIFILM NP-W235 Lithium-Ion Battery (7.2V, 2200mAh)\r\nAC Power Adapter and Plug\r\nUSB Cable\r\nHeadphone Adapter\r\nShoulder Strap\r\nFUJIFILM Body Cap for FUJIFILM X-Mount Cameras\r\nHot Shoe Cover\r\nVertical Grip Connection Cover\r\nMemory Card Slot Cover\r\nSync Terminal Cover','FUJIFILM X-T4 Mirrorless Camera (Black)\r\nFUJIFILM NP-W235 Lithium-Ion Battery (7.2V, 2200mAh)\r\nAC Power Adapter and Plug\r\nUSB Cable\r\nHeadphone Adapter\r\nShoulder Strap\r\nFUJIFILM Body Cap for FUJIFILM X-Mount Cameras\r\nHot Shoe Cover\r\nVertical Grip Connection Cover\r\nMemory Card Slot Cover\r\nSync Terminal Cover',300000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php4e1032u4ndt71vqy4wG','equipments/Y0eE8RBgrfOhiEexqEI42aBCm246qm0nu5HJhBes.png','2026-02-20 18:19:44','2026-02-20 18:19:44'),(16,1,'Yamaha Audio Mixer MG12XU','yamaha-mg12xu','6x Mic Inputs\r\n12x Line Inputs\r\n2-In/2-Out USB Up to 24-Bit / 192kHz\r\nBuilt-In FX with 24 Presets\r\nXLR and TRS 1/4\" Stereo Outs\r\nTwo Group Buses & Two Aux Sends\r\n\"D-PRE\" Mic Preamps with 48V Phantom\r\nHPF & 3-Band EQ on Channels 1-8\r\n1/4\" Headphone Output with Level Control\r\nUSB Works with Windows, Mac and iOS','6x Mic Inputs\r\n12x Line Inputs\r\n2-In/2-Out USB Up to 24-Bit / 192kHz\r\nBuilt-In FX with 24 Presets\r\nXLR and TRS 1/4\" Stereo Outs\r\nTwo Group Buses & Two Aux Sends\r\n\"D-PRE\" Mic Preamps with 48V Phantom\r\nHPF & 3-Band EQ on Channels 1-8\r\n1/4\" Headphone Output with Level Control\r\nUSB Works with Windows, Mac and iOS',250000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpt8qt7s09c8hp9ZM0eT6','equipments/axhuL8QrAeqwM9xfITe2QQ3q0YcR3MXFCFVrIJ2l.png','2026-02-20 18:29:05','2026-02-20 18:29:05'),(17,1,'SARAMONIC BLINK 500 T4 WIRELESS MICROPHONE (4 Transmitter + 1 Receiver)','saramonic-blink500','Mixed and independent output for all four tracks\r\n48kHz/24 Bit, 120 dB Max. SPL, broadcast-level audio recording\r\nBuilt-in condenser Mic for highly sensitive sound pickup omnidirectionally\r\n7-level gain control; real-time monitoring for real time adjustments\r\nAlgorithm-based noise reduction that intelligently pickup human voice and filter background noises\r\nUp to 328 feet wireless transmission on 2.4GHz wireless frequency\r\nUp to 8-hour battery life for Transmitter and 12 hours for Receiver\r\nCable jack 3.5mm to jack 3.5mm microphone (1 to 4) that is designed for professional audio recording or mixing devices.\r\nCompatible with Blink 500 T2 \r\nCable Jack 3,5mm to Jack 3,5 mm microphone (1 to 4)','Mixed and independent output for all four tracks\r\n48kHz/24 Bit, 120 dB Max. SPL, broadcast-level audio recording\r\nBuilt-in condenser Mic for highly sensitive sound pickup omnidirectionally\r\n7-level gain control; real-time monitoring for real time adjustments\r\nAlgorithm-based noise reduction that intelligently pickup human voice and filter background noises\r\nUp to 328 feet wireless transmission on 2.4GHz wireless frequency\r\nUp to 8-hour battery life for Transmitter and 12 hours for Receiver\r\nCable jack 3.5mm to jack 3.5mm microphone (1 to 4) that is designed for professional audio recording or mixing devices.\r\nCompatible with Blink 500 T2 \r\nCable Jack 3,5mm to Jack 3,5 mm microphone (1 to 4)',200000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpofdmpc94kn1lazFDZ6x','equipments/kYaI3BvbjrlU8eylitUyQ0dVpwLedEXqXDBSf9uA.png','2026-02-20 18:30:40','2026-02-20 18:30:40'),(18,1,'Boom Mic Sennheiser MKH 816 + Rycote Ws 8 (Kit)','sennheiser-mkh816','Classic Softie for 18.8\" Shotgun Mics\r\nReduces Wind Noise\r\nSlip-On Open-Cell Foam with Fur Cover\r\nProtects Microphone\r\nRycote Classic Softie for Sennheiser MKH816 and Rode NTG 8 (18\" Long, 0.7 to 0.8\" Diameter Hole)','Classic Softie for 18.8\" Shotgun Mics\r\nReduces Wind Noise\r\nSlip-On Open-Cell Foam with Fur Cover\r\nProtects Microphone\r\nRycote Classic Softie for Sennheiser MKH816 and Rode NTG 8 (18\" Long, 0.7 to 0.8\" Diameter Hole)',250000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php6ofejjpr9f207YGpX59','equipments/ElwZzF6vyWxPZx4OcYAqnxYb2aKIFf3xuuSLOztj.png','2026-02-20 18:32:00','2026-02-20 18:32:00'),(19,4,'Sigma 28-105mm f/2.8 DG DN Art (For Sony E-Mount)','sigma-28-105mm','Sigma 28-105mm f/2.8 DG DN Art Lens (Sony E)\r\nSigma LCF-82 III 82mm Lens Cap\r\nSigma LCR II Rear Lens Cap for Sony E\r\nSigma Lens Hood for 28-105mm f/2.8 Art DG DN Lens\r\nFull Frame | f/2.8 to f/22\r\nFast Wide-to-Telephoto Zoom\r\nHLA Autofocus\r\n15.8\" Minimum Focus Distance\r\nAperture Ring with Click & Lock Switches\r\nFLD, SLD & Aspherical Elements\r\nWater- and Oil-Repellant Coating\r\nDust- and Splash-Resistant Construction','Sigma 28-105mm f/2.8 DG DN Art Lens (Sony E)\r\nSigma LCF-82 III 82mm Lens Cap\r\nSigma LCR II Rear Lens Cap for Sony E\r\nSigma Lens Hood for 28-105mm f/2.8 Art DG DN Lens\r\nFull Frame | f/2.8 to f/22\r\nFast Wide-to-Telephoto Zoom\r\nHLA Autofocus\r\n15.8\" Minimum Focus Distance\r\nAperture Ring with Click & Lock Switches\r\nFLD, SLD & Aspherical Elements\r\nWater- and Oil-Repellant Coating\r\nDust- and Splash-Resistant Construction',225000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php941jb45m0eeebYGCbKG','equipments/ebnrca7VCpwOALlwboD6huZ0P9ZvslGS74RIN8F0.png','2026-02-20 18:39:12','2026-02-20 18:39:52'),(20,4,'Laowa 10mm f/2.8 Zero-D FF Autofocus Lens (Sony E-Mount)','laowa-10mm','Full-Frame | f/2.8 to f/22\r\nFast and Lightweight Wide-Angle Prime\r\nAutofocus Design\r\nThree Extra-Low Dispersion Elements\r\nTwo Aspherical Elements\r\n77mm Filter Thread\r\nVenus Optics Laowa 10mm f/2.8 Zero-D FF Autofocus Lens (Sony E)\r\nFront Lens Cap\r\nRear Lens Cap','Full-Frame | f/2.8 to f/22\r\nFast and Lightweight Wide-Angle Prime\r\nAutofocus Design\r\nThree Extra-Low Dispersion Elements\r\nTwo Aspherical Elements\r\n77mm Filter Thread\r\nVenus Optics Laowa 10mm f/2.8 Zero-D FF Autofocus Lens (Sony E)\r\nFront Lens Cap\r\nRear Lens Cap',175000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpudlecveh9slh6CEuiTo','equipments/Yn1G0KaXcRVtoZjrWobrjyTW3opOeX21ioAV9OPF.png','2026-02-20 18:43:07','2026-02-20 18:43:07'),(21,4,'Nikon NIKKOR Z 70-200mm f/2.8 VR S Lens','nikon-70-200mm','Z-Mount Lens/FX Format\r\nAperture Range: f/2.8 to f/22\r\nED, SR, and Fluorite Elements\r\nARNEO and Nano Crystal Coatings\r\nMulti-Focus Stepping Motor AF System\r\nVibration Reduction Image Stabilization\r\nProgrammable Control Ring\r\nInformation OLED Panel and L.Fn Button\r\nWeather-Sealed Design, Fluorine Coating\r\n77mm UV Filter\r\nLC-77B Front Lens Cap\r\nNikon LF-N1 Rear Lens Cap\r\nHB-92 Lens Hood\r\nCL-C3 Lens Case','Z-Mount Lens/FX Format\r\nAperture Range: f/2.8 to f/22\r\nED, SR, and Fluorite Elements\r\nARNEO and Nano Crystal Coatings\r\nMulti-Focus Stepping Motor AF System\r\nVibration Reduction Image Stabilization\r\nProgrammable Control Ring\r\nInformation OLED Panel and L.Fn Button\r\nWeather-Sealed Design, Fluorine Coating\r\n77mm UV Filter\r\nLC-77B Front Lens Cap\r\nNikon LF-N1 Rear Lens Cap\r\nHB-92 Lens Hood\r\nCL-C3 Lens Case',300000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php4gld1745c2jndMMM7Tg','equipments/Zz9Lyt10CcELd6xKt1bvuMpxfzgi0aum23tWZ8zB.png','2026-02-20 18:46:43','2026-02-20 18:46:43'),(22,3,'Aputure NOVA II 2x1 Tunable Color LED Light Panel','aputure-nova-ii','For Studio & Film/TV Production\r\nOutput: 197,300 Lux at 3.3\' (35º, 5600K)\r\n1800-20,000K CCT; BLAIR-CG Chipset\r\nPlus/Minus Green Adjustment\r\n27.2 x 15.3\" Panel; AC Power\r\nCRI 95 | TLCI 95 | TM-30 Rf 95, Rg 100\r\nOnboard, DMX/RDM, CRMX & Art-Net/sACN\r\nFan Cooled & Front-Mounting Accessory\r\nIP65-Rated Weather Resistance\r\nIncludes Yoke, Flat Diffuser & Cable\r\nAputure NOVA II 2x1 Tunable Color LED Light Panel\r\nRemovable Yoke with Junior Pin\r\nFlat Diffuser\r\nAC Power Cable (19.6\')','For Studio & Film/TV Production\r\nOutput: 197,300 Lux at 3.3\' (35º, 5600K)\r\n1800-20,000K CCT; BLAIR-CG Chipset\r\nPlus/Minus Green Adjustment\r\n27.2 x 15.3\" Panel; AC Power\r\nCRI 95 | TLCI 95 | TM-30 Rf 95, Rg 100\r\nOnboard, DMX/RDM, CRMX & Art-Net/sACN\r\nFan Cooled & Front-Mounting Accessory\r\nIP65-Rated Weather Resistance\r\nIncludes Yoke, Flat Diffuser & Cable\r\nAputure NOVA II 2x1 Tunable Color LED Light Panel\r\nRemovable Yoke with Junior Pin\r\nFlat Diffuser\r\nAC Power Cable (19.6\')',750000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php79af3o9aleaabsXdhql','equipments/k3v8hvhv0QFpQnHo9GswGEz3Ltus004kp4uOd9CJ.png','2026-02-20 18:52:50','2026-02-20 18:52:50'),(23,3,'Nanlux Evoke 600C RGB LED Spotlight','nanlux-600c','Light, Trolley Case & Wired Controller\r\nOutput: 24,620 Lux at 3.3\' (Bare, 5600K)\r\n1000-20,000K CCT; Full RGB Color Control\r\n+/- 200 Green/Magenta Tint Adjustment\r\n8-Chip Light Engine & AC/DC Operation\r\nOnboard, DMX/RDM, CRMX & App Control\r\nCRI 96 | TLCI 97\r\nIP66 Rating & Bowens Compatibility\r\nIncludes Yoke & AC Power Cord\r\nIncludes 25° BE Mount Reflector\r\nNanlux Evoke 600C RGB LED Spotlight (Trolley Case Kit)\r\nYoke\r\nBE Mount Reflector (25°)\r\nAC Power Cable (19.6\')\r\nUSB Flash Drive\r\nMagnetic Base\r\nWired Controller\r\nConnection Cable (26.2\')\r\nTrolley Case','Light, Trolley Case & Wired Controller\r\nOutput: 24,620 Lux at 3.3\' (Bare, 5600K)\r\n1000-20,000K CCT; Full RGB Color Control\r\n+/- 200 Green/Magenta Tint Adjustment\r\n8-Chip Light Engine & AC/DC Operation\r\nOnboard, DMX/RDM, CRMX & App Control\r\nCRI 96 | TLCI 97\r\nIP66 Rating & Bowens Compatibility\r\nIncludes Yoke & AC Power Cord\r\nIncludes 25° BE Mount Reflector\r\nNanlux Evoke 600C RGB LED Spotlight (Trolley Case Kit)\r\nYoke\r\nBE Mount Reflector (25°)\r\nAC Power Cable (19.6\')\r\nUSB Flash Drive\r\nMagnetic Base\r\nWired Controller\r\nConnection Cable (26.2\')\r\nTrolley Case',750000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php8uhv0gbrbjvi3YGmInZ','equipments/YEKBkuHArn66vMgcSXlS5iNqaj1kN4dEovRYMNen.png','2026-02-20 18:55:25','2026-02-20 18:55:25'),(24,3,'KUPO Crank Up/ Jumbo Stand With Braked Wheel','kupo-crank','4 risers, 5 sections.\r\nLeg: 30 x 30mm\r\nChrome plated steel stand with braked wheels\r\nDia. 90mm, 80mm, 70mm, 60mm, 50mm\r\nWind bracing kit','4 risers, 5 sections.\r\nLeg: 30 x 30mm\r\nChrome plated steel stand with braked wheels\r\nDia. 90mm, 80mm, 70mm, 60mm, 50mm\r\nWind bracing kit',500000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phplooemvjt7rgr39gB8Ya','equipments/FvLMeXEHHYoS8LsTjQkl6kEIBHtmfCCjRmvu3lxZ.png','2026-02-20 18:58:42','2026-02-20 18:58:42'),(25,5,'Monitor FeelWorld S7','feelworld-s7','7\" 1920 x 1200 IPS LCD Monitor\r\nHDMI 2.0 and 12G-SDI Input/Output\r\n1600 cd/m² Brightness, Touchscreen\r\nUp to DCI 4K I/O, 10-Bit, HDR Support\r\nDual L-Series/NP-F Battery Plate\r\n1200:1 Contrast Ratio, 3D LUT Support\r\n160° Viewing Angle\r\nHDMI/SDI Cross Conversion Support\r\nBaterai Np-F770 4x\r\nCharger Baterai 1x\r\nMagic Arm/hot Shoe ball-joint mount 1x\r\nKabel HDMI','7\" 1920 x 1200 IPS LCD Monitor\r\nHDMI 2.0 and 12G-SDI Input/Output\r\n1600 cd/m² Brightness, Touchscreen\r\nUp to DCI 4K I/O, 10-Bit, HDR Support\r\nDual L-Series/NP-F Battery Plate\r\n1200:1 Contrast Ratio, 3D LUT Support\r\n160° Viewing Angle\r\nHDMI/SDI Cross Conversion Support\r\nBaterai Np-F770 4x\r\nCharger Baterai 1x\r\nMagic Arm/hot Shoe ball-joint mount 1x\r\nKabel HDMI',200000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpeqn3pqhqfj156Ly2LrS','equipments/QFYPglNIFIZGm27heEXEEzWeTkGpNh7zHal6tiZa.png','2026-02-20 19:14:31','2026-02-20 19:14:31'),(26,5,'Monitor pyro 7 kit','pyro7','Monitor Transmitter 1x\r\nMonitor Receiver 1x\r\nBaterai 8x\r\nDouble Charger 1x\r\nKabel D-Tap to DC 2x\r\nArm/ball Head Monitor 1x\r\nKabel HDMI/SDI secukupnya','Monitor Transmitter 1x\r\nMonitor Receiver 1x\r\nBaterai 8x\r\nDouble Charger 1x\r\nKabel D-Tap to DC 2x\r\nArm/ball Head Monitor 1x\r\nKabel HDMI/SDI secukupnya',500000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpcl7ihhlr98lf0eqVZV3','equipments/XwJNbHIFlHDZtrGrT318ktYXjweIf0oaYhpOj0n6.png','2026-02-20 19:17:06','2026-02-20 19:17:06'),(27,5,'Hollyland Mars 4K Wireless Video Transmission System','hollyland-mars-4k','Hollyland Mars 4K Wireless Video Transmission System\r\nMars 4K Transmitter\r\nMars 4K Receiver\r\n4 x Antenna\r\nTransmitter and Receiver Set\r\nTransmit up to UHD 4K30 Video up to 450\'\r\nSDI & HDMI Inputs & Outputs\r\nL-Series Battery/USB Type-C/DC Power\r\nSmart Channel Scanning, Touchscreen LCD\r\nLow 0.06s Latency, 12 Mb/s Data Rate\r\nDC, L-Series & USB Type-C Power Options\r\nTransmits to up to 4 x Apps as Monitor','Hollyland Mars 4K Wireless Video Transmission System\r\nMars 4K Transmitter\r\nMars 4K Receiver\r\n4 x Antenna\r\nTransmitter and Receiver Set\r\nTransmit up to UHD 4K30 Video up to 450\'\r\nSDI & HDMI Inputs & Outputs\r\nL-Series Battery/USB Type-C/DC Power\r\nSmart Channel Scanning, Touchscreen LCD\r\nLow 0.06s Latency, 12 Mb/s Data Rate\r\nDC, L-Series & USB Type-C Power Options\r\nTransmits to up to 4 x Apps as Monitor',400000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phplkip0vq8787vdFPVQGW','equipments/LOjdcktuYTMo2hn4C2NWw3BVOgOyO6A5Xbnf4TiJ.png','2026-02-20 19:19:56','2026-02-20 19:19:56'),(28,5,'TILTA Nucleus-N Wireless Lens Control System','tilta-nucleus-n','TILTA Nucleus-N Wireless Lens Control System','TILTA Nucleus-N Wireless Lens Control System',150000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpd1gb2cspvf2n9uX5sRH','equipments/KwZ0vSfcZKesHKxnsGWYcBCQQXeNgfIT2Dium2WE.png','2026-02-20 19:23:36','2026-02-20 19:23:36'),(29,5,'Feelworld L4 Multi Video format Mixer Switcher Streaming Layar Sentuh','feelworld-l4','Layar sentuh 10.1 inci\r\nOperasi sentuh yang diikonisasi\r\nKompatibel dengan sumber sinyal HDMI dan SDI\r\nT-bar cukup mengganti sumber sinyal atau efek transisi\r\nUSB 3.0 cepat untuk streaming langsung\r\nTertanam & Sisipkan Audio dengan Sinkronisasi\r\nKunci Chroma + overlay LOGO\r\nCampur audio dari beberapa input\r\nHamparan video PIP yang dapat dikonfigurasi\r\nMendukung 13 efek transisi penyiaran\r\nKendali jarak jauh melalui aplikasi','Layar sentuh 10.1 inci\r\nOperasi sentuh yang diikonisasi\r\nKompatibel dengan sumber sinyal HDMI dan SDI\r\nT-bar cukup mengganti sumber sinyal atau efek transisi\r\nUSB 3.0 cepat untuk streaming langsung\r\nTertanam & Sisipkan Audio dengan Sinkronisasi\r\nKunci Chroma + overlay LOGO\r\nCampur audio dari beberapa input\r\nHamparan video PIP yang dapat dikonfigurasi\r\nMendukung 13 efek transisi penyiaran\r\nKendali jarak jauh melalui aplikasi',250000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php6me5or51hm4s6uZsiXp','equipments/gbW8tYazcAiB8dvcJ6PDY0JPJ5gcIMB3XZT2Qrzv.png','2026-02-20 19:25:48','2026-02-20 19:26:18'),(30,5,'FeelWorld 21.5\'\' Full HD IPS Carry-On Broadcast Monitor (Silver)','feelworld-215','FeelWorld 21.5\'\' Full HD IPS Carry-On Broadcast Monitor (Silver)\r\nMini HDMI Cable\r\nV-Mount Battery Plate (Pre-Installed)\r\n3A Power Adapter\r\nTally Kit\r\nSunshade\r\nCase\r\nFull HD Display, Supports up to 1080p60\r\nProtective Case for Efficient On-Set Use\r\nRemovable from Case, Rack Mountable\r\n178 / 178° Viewing Angle\r\nHDMI, 3G-SDI & Analog Inputs/Outputs\r\nRCA Audio Input, Headphone Out, Speakers\r\nTally Port, 3-Color LED Tally Indicators\r\nIntegrated V-Mount Plate\r\nPeaking and Other Analysis Functions\r\n4-Pin XLR and DC Barrel Power Ports','FeelWorld 21.5\'\' Full HD IPS Carry-On Broadcast Monitor (Silver)\r\nMini HDMI Cable\r\nV-Mount Battery Plate (Pre-Installed)\r\n3A Power Adapter\r\nTally Kit\r\nSunshade\r\nCase\r\nFull HD Display, Supports up to 1080p60\r\nProtective Case for Efficient On-Set Use\r\nRemovable from Case, Rack Mountable\r\n178 / 178° Viewing Angle\r\nHDMI, 3G-SDI & Analog Inputs/Outputs\r\nRCA Audio Input, Headphone Out, Speakers\r\nTally Port, 3-Color LED Tally Indicators\r\nIntegrated V-Mount Plate\r\nPeaking and Other Analysis Functions\r\n4-Pin XLR and DC Barrel Power Ports',250000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phps4tmsippbn7ecJsar82','equipments/blQKVTYojXQgtAQVBzn8cyKyqtfXNZkSkhK0nifD.png','2026-02-20 19:29:40','2026-02-20 19:30:04'),(31,6,'AS Easyrig Vest 3-8kg','easyrig-vest','AS Easyrig Vest 3-8kg','AS Easyrig Vest 3-8kg',500000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php60ksc8o3mh805k11Kl1','equipments/VVVBbJlGW6onKFUp0GJtKRI5kP15i7pL8AGzGd5e.png','2026-02-20 19:38:27','2026-02-20 19:38:27'),(32,6,'Zhiyun Smooth 4','zhiyun-smooth-4',NULL,NULL,50000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpses84d907pkf3sf4pCA','equipments/7JWWIVyEJ6Nv4bMvGVWTLWrm1TEtaWLVt4DTslPl.png','2026-02-20 19:42:18','2026-02-20 19:42:18'),(33,6,'DJI Osmo Mobile 3','dji-osmo-3',NULL,NULL,60000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/php4rhofppetboccJhUSzy','equipments/0yn89mPblIWNcfTzQ3C5IcoyTX8uYxoyV2IAwuIR.png','2026-02-20 19:44:22','2026-02-20 19:44:22'),(34,6,'V-Mount Battery 98Wh (FXLION NANO TWO)','vmount-battery','98Wh Lithium-Ion V-Mount Battery\r\nSupports up to 10A Continuous Draw\r\nLCD Status Screen\r\n14.8V D-Tap Output','98Wh Lithium-Ion V-Mount Battery\r\nSupports up to 10A Continuous Draw\r\nLCD Status Screen\r\n14.8V D-Tap Output',75000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phps2p3l0u5di4139FJLAR','equipments/zIbX4O8IBOTKRXwsTyV09Y0xklU4gjs9FhBUL7vx.png','2026-02-20 19:51:22','2026-02-20 19:51:22'),(35,6,'V-Mount Charger 2 Slot','vmount-charger',NULL,NULL,50000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpaqnjbvfmit29fSquz4E','equipments/dPEAAupIJb47bN1ovg7YRA6lFRGwXeEGbvdnOa5G.png','2026-02-20 19:53:13','2026-02-20 19:53:13'),(36,6,'V-Mount Plate to Rod 15mm','vmount-plate',NULL,NULL,75000,'ready',1,'/private/var/folders/qm/t3mzs67n58v4g8_wlhq96ksh0000gn/T/phpmi7mtrmrva6s8v7HDbe','equipments/OMik1uJ1mtwMHuqwICDuG3OT3iFWms1SOI4vEVl4.png','2026-02-20 19:54:34','2026-02-20 19:54:34');
/*!40000 ALTER TABLE `equipments` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_01_180323_create_categories_table',1),(5,'2026_02_01_180444_create_equipments_table',1),(6,'2026_02_01_180737_create_admins_table',1),(7,'2026_02_01_181006_create_bookings_table',1),(8,'2026_02_01_181053_create_booking_items_table',1),(9,'2026_02_01_181135_create_payments_table',1),(10,'2026_02_05_150516_add_payment_fields_to_bookings_table',1),(11,'2026_02_07_000001_add_role_to_users_table',1),(12,'2026_02_07_000002_create_profiles_table',1),(13,'2026_02_11_000001_create_orders_table',1),(14,'2026_02_11_000002_create_order_items_table',1),(15,'2026_02_11_000003_add_city_notes_to_profiles_table',1),(16,'2026_02_11_000004_add_role_to_admins_table',1),(17,'2026_02_11_000005_create_audit_logs_table',1),(18,'2026_02_11_000006_create_sessions_table',1),(19,'2026_02_11_000007_add_admin_auth_columns',1),(20,'2026_02_11_000008_ensure_profiles_table',1),(21,'2026_02_11_000009_add_order_number_and_status_to_orders_table',1),(22,'2026_02_11_000010_update_payments_table_for_orders',1),(23,'2026_02_12_000001_add_city_and_notes_to_profiles_table',1),(24,'2026_02_12_000002_add_equipment_id_to_order_items_table',1),(25,'2026_02_12_000003_create_site_settings_table',1),(26,'2026_02_12_000004_add_slug_description_to_categories_table',1),(27,'2026_02_12_000005_add_slug_status_image_path_to_equipments_table',1),(28,'2026_02_12_000006_create_site_contents_table',1),(29,'2026_02_12_000007_add_type_and_admin_to_site_settings_table',1),(30,'2026_02_14_000001_add_group_to_site_settings_table',1),(31,'2026_02_14_000002_create_site_media_table',1),(32,'2026_02_14_000003_add_preferences_to_users_table',1),(33,'2026_02_16_000001_add_specifications_to_equipments_table',1),(34,'2026_02_17_000003_add_rental_dates_to_order_items_table',1),(35,'2026_02_17_000004_backfill_order_numbers_table',1),(36,'2026_02_17_000005_add_lifecycle_and_fee_fields_to_orders_table',1),(37,'2026_02_17_000006_create_order_notifications_table',1),(38,'2026_02_18_000001_harden_profile_schema_for_secure_checkout',1),(39,'2026_02_18_000002_create_phone_verifications_table',1),(40,'2026_02_18_000003_add_invoice_breakdown_fields_to_orders_table',1),(41,'2026_02_18_000004_create_equipment_maintenance_windows_table',1),(42,'2026_02_18_000005_harden_payments_table_for_launch',1),(43,'2026_02_18_000006_create_payment_webhook_events_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
  `equipment_id` bigint unsigned DEFAULT NULL,
  `qty` int unsigned NOT NULL DEFAULT '1',
  `price` bigint unsigned NOT NULL,
  `subtotal` bigint unsigned NOT NULL,
  `rental_start_date` date DEFAULT NULL,
  `rental_end_date` date DEFAULT NULL,
  `rental_days` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_equipment_id_foreign` (`equipment_id`),
  CONSTRAINT `order_items_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,12,1,250000,750000,'2026-02-21','2026-02-23',3,'2026-02-17 20:23:36','2026-02-17 20:23:36'),(2,1,2,1,500000,1500000,'2026-02-21','2026-02-23',3,'2026-02-17 20:23:36','2026-02-17 20:23:36'),(8,7,4,1,125000,500000,'2026-02-20','2026-02-23',4,'2026-02-18 14:32:06','2026-02-18 14:32:06'),(9,8,11,15,10000,600000,'2026-03-01','2026-03-04',4,'2026-02-18 17:02:24','2026-02-19 17:43:24'),(10,9,1,1,125000,500000,'2026-02-16','2026-02-19',4,'2026-02-19 10:33:42','2026-02-19 10:33:42'),(11,10,35,1,50000,450000,'2026-02-23','2026-03-03',9,'2026-02-23 13:10:31','2026-02-23 13:12:33');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_notifications`
--

DROP TABLE IF EXISTS `order_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'order_update',
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_notifications_user_id_read_at_index` (`user_id`,`read_at`),
  KEY `order_notifications_order_id_created_at_index` (`order_id`,`created_at`),
  CONSTRAINT `order_notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_notifications`
--

LOCK TABLES `order_notifications` WRITE;
/*!40000 ALTER TABLE `order_notifications` DISABLE KEYS */;
INSERT INTO `order_notifications` VALUES (1,1,1,'payment_update','Update pembayaran MNK-20260218-1-USHJVH','Status pembayaran kamu sekarang: Lunas. Invoice sudah tersedia.','2026-02-19 13:54:31','2026-02-17 20:24:15','2026-02-19 13:54:31'),(2,1,7,'payment_update','Update pembayaran MNK-20260218-7-IIPA3R','Status pembayaran kamu sekarang: Lunas. Invoice sudah tersedia.','2026-02-19 13:54:26','2026-02-18 14:33:24','2026-02-19 13:54:26'),(3,1,8,'payment_update','Update pembayaran MNK-20260219-8-JJXOQI','Status pembayaran kamu sekarang: Lunas. Invoice sudah tersedia.','2026-02-19 14:04:46','2026-02-18 17:03:19','2026-02-19 14:04:46'),(4,1,7,'order_update','Update operasional MNK-20260218-7-IIPA3R','Status rental diperbarui: Siap Diambil → Barang Diambil.','2026-02-19 13:54:26','2026-02-18 17:12:27','2026-02-19 13:54:26'),(5,1,7,'order_update','Update operasional MNK-20260218-7-IIPA3R','Status rental diperbarui: Barang Diambil → Barang Dikembalikan.','2026-02-19 13:54:26','2026-02-18 17:13:29','2026-02-19 13:54:26'),(6,1,7,'order_update','Update pesanan MNK-20260218-7-IIPA3R','Biaya tambahan diperbarui menjadi Rp 100.000. Catatan biaya: terlambat pengembalian.','2026-02-19 13:54:26','2026-02-18 17:14:39','2026-02-19 13:54:26'),(7,1,7,'damage_fee_update','Tagihan tambahan MNK-20260218-7-IIPA3R','Tagihan tambahan kerusakan sebesar Rp 100.000 sudah lunas.','2026-02-19 13:54:26','2026-02-19 09:35:58','2026-02-19 13:54:26'),(8,1,1,'order_update','Update operasional MNK-20260218-1-USHJVH','Status rental diperbarui: Siap Diambil → Barang Diambil.',NULL,'2026-02-22 07:08:38','2026-02-22 07:08:38'),(9,1,1,'order_update','Update operasional MNK-20260218-1-USHJVH','Status rental diperbarui: Barang Diambil → Barang Dikembalikan.',NULL,'2026-02-22 07:08:41','2026-02-22 07:08:41'),(10,3,10,'payment_update','Payment updates MNK-20260223-10-ULOW6B','Your current payment status: Paid off. Invoice is available.','2026-02-23 13:13:46','2026-02-23 13:13:45','2026-02-23 13:13:46'),(11,3,10,'order_update','Operational updates MNK-20260223-10-ULOW6B','Rental status updated: Ready for Pickup → Item Taken.',NULL,'2026-02-23 13:36:33','2026-02-23 13:36:33');
/*!40000 ALTER TABLE `order_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `status_pesanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_pembayaran',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_amount` bigint unsigned NOT NULL DEFAULT '0',
  `additional_fee` bigint unsigned NOT NULL DEFAULT '0',
  `penalty_amount` bigint unsigned NOT NULL DEFAULT '0',
  `shipping_amount` bigint unsigned NOT NULL DEFAULT '0',
  `additional_fee_note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `rental_start_date` date NOT NULL,
  `rental_end_date` date NOT NULL,
  `midtrans_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `picked_up_at` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `damaged_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_midtrans_order_id_unique` (`midtrans_order_id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_status_pembayaran_index` (`user_id`,`status_pembayaran`),
  KEY `orders_user_id_status_pesanan_index` (`user_id`,`status_pesanan`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,'MNK-20260218-1-USHJVH','paid','barang_kembali','paid',2250000,0,0,0,NULL,NULL,'2026-02-21','2026-02-23','MNK-20260218-1-USHJVH','277e86a7-127c-4c88-aefa-6fd4446da740','2026-02-17 20:24:15','2026-02-22 07:08:38','2026-02-22 07:08:41',NULL,'2026-02-17 20:23:36','2026-02-22 07:08:41'),(7,1,'MNK-20260218-7-IIPA3R','paid','selesai','paid',500000,100000,0,0,'terlambat pengembalian',NULL,'2026-02-20','2026-02-23','MNK-20260218-7-IIPA3R','9b332791-9cef-4728-82f8-b5b10c436179','2026-02-18 14:33:24','2026-02-18 17:12:27','2026-02-18 17:13:29',NULL,'2026-02-18 14:32:06','2026-02-19 09:35:58'),(8,1,'MNK-20260219-8-JJXOQI','paid','lunas','paid',600000,0,0,0,NULL,NULL,'2026-03-01','2026-03-04','MNK-20260219-8-JJXOQI','f055e0c6-7a4f-4994-bf6b-05f154edbfce','2026-02-18 17:03:19',NULL,NULL,NULL,'2026-02-18 17:02:24','2026-02-19 17:43:24'),(9,2,'INV-SHOT-20260219','paid','selesai','paid',500000,100000,100000,0,'Kerusakan ringan pada aksesoris',NULL,'2026-02-16','2026-02-19','INV-SHOT-20260219',NULL,'2026-02-19 08:33:42',NULL,NULL,NULL,'2026-02-19 10:33:42','2026-02-19 10:33:42'),(10,3,'MNK-20260223-10-ULOW6B','paid','barang_diambil','paid',450000,0,0,0,NULL,NULL,'2026-02-23','2026-03-03','MNK-20260223-10-ULOW6B','29eee397-6e62-4908-8f6e-201cd1ce3e90','2026-02-23 13:13:45','2026-02-23 13:36:33',NULL,NULL,'2026-02-23 13:10:31','2026-02-23 13:36:33');
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
-- Table structure for table `payment_webhook_events`
--

DROP TABLE IF EXISTS `payment_webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_webhook_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midtrans',
  `event_key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `payload_json` longtext COLLATE utf8mb4_unicode_ci,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_webhook_events_unique` (`provider`,`event_key`),
  KEY `payment_webhook_events_order_id_foreign` (`order_id`),
  KEY `payment_webhook_events_provider_idx` (`provider`,`created_at`),
  CONSTRAINT `payment_webhook_events_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_webhook_events`
--

LOCK TABLES `payment_webhook_events` WRITE;
/*!40000 ALTER TABLE `payment_webhook_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_webhook_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `payload_json` longtext COLLATE utf8mb4_unicode_ci,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gross_amount` bigint unsigned NOT NULL DEFAULT '0',
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_order_status_idx` (`order_id`,`status`),
  KEY `payments_midtrans_order_id_idx` (`midtrans_order_id`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,NULL,1,'midtrans','MNK-20260218-1-USHJVH','277e86a7-127c-4c88-aefa-6fd4446da740','paid','2026-02-17 20:24:15',NULL,'{\"status_code\":\"200\",\"transaction_id\":\"e902da99-e56a-44e3-8af5-281805f7256a\",\"gross_amount\":\"2497500.00\",\"currency\":\"IDR\",\"order_id\":\"MNK-20260218-1-USHJVH\",\"payment_type\":\"qris\",\"signature_key\":\"50540c525c816ee376e232c496a3c0de58ae216639ed78d53fa69ee6f01672ac1caa205dbaba6b520ca6e8a9fedf3d726b9a1717fa9411b27345e5c669a95a57\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G286799186\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2026-02-18 03:23:40\",\"settlement_time\":\"2026-02-18 03:23:56\",\"expiry_time\":\"2026-02-18 03:38:40\"}','qris','settlement',2497500,'e902da99-e56a-44e3-8af5-281805f7256a','2026-02-17 20:23:37','2026-02-17 20:24:15'),(2,NULL,7,'midtrans','MNK-20260218-7-IIPA3R','9b332791-9cef-4728-82f8-b5b10c436179','paid','2026-02-19 08:45:31',NULL,'{\"status_code\":\"200\",\"transaction_id\":\"eb28c0c4-8eff-4ab3-a61c-1506fa0bf2bc\",\"gross_amount\":\"555000.00\",\"currency\":\"IDR\",\"order_id\":\"MNK-20260218-7-IIPA3R\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"b1322080f9742dcf877f23cb6343496fdb9bf30d35cdc6ab2189ad8cc94c7b8f6e969dc1e719c1a227ed9934c45cb83e824dbe4552c9675bcd3157319a349146\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G286799186\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"99186881245987197539769\"}],\"payment_amounts\":[],\"transaction_time\":\"2026-02-18 21:32:48\",\"settlement_time\":\"2026-02-18 21:33:00\",\"expiry_time\":\"2026-02-19 21:32:48\"}','bank_transfer','settlement',555000,'eb28c0c4-8eff-4ab3-a61c-1506fa0bf2bc','2026-02-18 14:32:06','2026-02-19 08:45:31'),(3,NULL,8,'midtrans','MNK-20260219-8-JJXOQI','f055e0c6-7a4f-4994-bf6b-05f154edbfce','paid','2026-02-18 17:03:19',NULL,'{\"status_code\":\"200\",\"transaction_id\":\"e4fc256c-c4f7-4581-af5d-1538c986bf3d\",\"gross_amount\":\"666000.00\",\"currency\":\"IDR\",\"order_id\":\"MNK-20260219-8-JJXOQI\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"304fc0ef1a7517fffa78bf94ccd5f7316714d03b232729b3b857bc862f056db215eb53f24c2bfd22d211da7af3d19e12ad43094242c814be9f528832fa0f5aa9\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G286799186\",\"va_numbers\":[{\"bank\":\"bri\",\"va_number\":\"991867141902089012\"}],\"payment_amounts\":[],\"transaction_time\":\"2026-02-19 00:02:44\",\"settlement_time\":\"2026-02-19 00:03:04\",\"expiry_time\":\"2026-02-20 00:02:43\"}','bank_transfer','settlement',666000,'e4fc256c-c4f7-4581-af5d-1538c986bf3d','2026-02-18 17:02:24','2026-02-18 17:03:19'),(4,NULL,7,'midtrans_damage','MNK202602187IIPA3R-DMG-V6B8X','c47f382b-c88d-459a-85d0-08aaf53e09ba','paid','2026-02-19 09:35:58','2026-02-21 20:27:22','{\"status_code\":\"200\",\"transaction_id\":\"72b46337-0457-45ef-9ffb-37bac2613c08\",\"gross_amount\":\"100000.00\",\"currency\":\"IDR\",\"order_id\":\"MNK202602187IIPA3R-DMG-V6B8X\",\"payment_type\":\"qris\",\"signature_key\":\"bd24ee5807f75580895d262d958a09e65ddbd3b5927717701c0e3ac556aa42609e2f60898b966f9e28868851eea72bdc0dbf305eb7c6e0b6aaec945ecb1a1063\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G286799186\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2026-02-19 15:45:12\",\"settlement_time\":\"2026-02-19 16:35:45\",\"expiry_time\":\"2026-02-22 03:27:22\"}','qris','settlement',100000,'72b46337-0457-45ef-9ffb-37bac2613c08','2026-02-18 20:27:22','2026-02-19 09:35:58'),(5,NULL,9,'midtrans','INV-SHOT-20260219',NULL,'paid','2026-02-19 08:33:42',NULL,'{\"payment_type\":\"bank_transfer\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"1234567890123456\"}],\"transaction_id\":\"trx-inv-shot\"}','bank_transfer','settlement',555000,NULL,'2026-02-19 10:33:42','2026-02-19 10:33:42'),(6,NULL,10,'midtrans','MNK-20260223-10-ULOW6B','29eee397-6e62-4908-8f6e-201cd1ce3e90','paid','2026-02-23 13:13:45',NULL,'{\"status_code\":\"200\",\"transaction_id\":\"d0633da4-4a2d-4637-ac52-6ba92a7ecdc3\",\"gross_amount\":\"499500.00\",\"currency\":\"IDR\",\"order_id\":\"MNK-20260223-10-ULOW6B\",\"payment_type\":\"qris\",\"signature_key\":\"e90d4981dde650e90b2886585cf76a68080c7c8267c6783564f180a13bc574419ffed61d70d9a821cbf362010534de458a3dca71014f5bbb52133793d809e967\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G286799186\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2026-02-23 20:12:41\",\"settlement_time\":\"2026-02-23 20:13:37\",\"expiry_time\":\"2026-02-23 20:27:41\"}','qris','settlement',499500,'d0633da4-4a2d-4637-ac52-6ba92a7ecdc3','2026-02-23 13:10:31','2026-02-23 13:13:45');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phone_verifications`
--

DROP TABLE IF EXISTS `phone_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phone_verifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_expires_at` timestamp NOT NULL,
  `otp_attempts` tinyint unsigned NOT NULL DEFAULT '0',
  `last_requested_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phone_verifications_user_id_otp_expires_at_index` (`user_id`,`otp_expires_at`),
  CONSTRAINT `phone_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phone_verifications`
--

LOCK TABLES `phone_verifications` WRITE;
/*!40000 ALTER TABLE `phone_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `phone_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `address_line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelurahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maps_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `identity_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profiles_nik_unique` (`nik`),
  KEY `profiles_user_id_foreign` (`user_id`),
  KEY `profiles_phone_index` (`phone`),
  CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (1,1,'Fikri Mulya Rachmat','0123456789012345','2004-01-02','male','085156649015','2026-02-17 20:23:23','Jalan jalan ke Surabaya','Grogol','Limo','Jalan jalan ke Surabaya','Depok','Jawa Barat','16533',NULL,'Vindy','Pacar','085156649015',NULL,'0123456789012345','Vindy (Pacar) - 085156649015',1,'2026-02-17 19:50:58','2026-02-17 19:48:03','2026-02-17 20:23:23'),(2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2026-02-17 19:48:04','2026-02-17 19:48:04'),(3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2026-02-19 10:33:42','2026-02-19 10:33:42'),(4,3,'bck44565','0123456783290123','2026-02-21','male','081261266617','2026-02-23 13:28:14','Jalan jalan ke Surabaya','Grogol','Limo','Jalan jalan ke Surabaya','Depok','Jawa Barat','16533',NULL,'Vindy','Pacar','081261266617',NULL,'0123456783290123','Vindy (Pacar) - 081261266617',1,'2026-02-23 13:01:59','2026-02-23 12:41:31','2026-02-23 13:28:14'),(5,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2026-02-23 12:41:34','2026-02-23 12:41:34');
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('0mly1nSyPeG8mxvtMxw14BHSe6PUiwCngfZtOGmO',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Safari/605.1.15','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicVVXT1hOZU56Tk5hNmdyUW90QmdrNkM4MkZIUVpTRjAwOExVSWRkayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoibG9jYWxlIjtzOjI6ImVuIjt9',1771856215),('I5zmzHfpbuLpAOhbcRzMGCNN1VTGFGJDNwfjIVgt',3,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6IkxDWDFkbmZWWXFvS3U3QVVoYUFYSFhnN3F3alczc0VXVEN6ZGF2NUUiO3M6MzoidXJsIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2RiL3NpdGVfc2V0dGluZ3MiO3M6NToicm91dGUiO3M6MTQ6ImFkbWluLmRiLnRhYmxlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NToidGhlbWUiO3M6NDoiZGFyayI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxMjoib3RwX3ZlcmlmaWVkIjtiOjE7czo0OiJjYXJ0IjthOjA6e319',1771856857),('jpCDDhRbjM0QDtikGB5yVJnmE97QE10eLdt49aIm',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRjFDbkphWnZGV09NWlN1SHdQMzNGWjZaeDI3TDhwQ04wNDZMN3cydSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC92ZXJpZnktZW1haWwvMy9kZjZhNmRhMGFhMzAwYzQ3YmUzYzY4NmRkZDRkZTY5NDZjNDFkYmJhP2V4cGlyZXM9MTc3MTg1NDA5MSZzaWduYXR1cmU9YjFlNGRlZDU2OTU1MDNjYmIyNzRlMDZkY2ZiODA0ZDk0ZTY5ZGVjZTVjOGMwNzcyNWExOTk0Nzg4MGYzODg1ZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1771851865);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_contents`
--

DROP TABLE IF EXISTS `site_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `updated_by_admin_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_contents_key_unique` (`key`),
  KEY `site_contents_updated_by_admin_id_foreign` (`updated_by_admin_id`),
  CONSTRAINT `site_contents_updated_by_admin_id_foreign` FOREIGN KEY (`updated_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_contents`
--

LOCK TABLES `site_contents` WRITE;
/*!40000 ALTER TABLE `site_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_media`
--

DROP TABLE IF EXISTS `site_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by_admin_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_media_uploaded_by_admin_id_foreign` (`uploaded_by_admin_id`),
  KEY `site_media_key_index` (`key`),
  KEY `site_media_group_index` (`group`),
  CONSTRAINT `site_media_uploaded_by_admin_id_foreign` FOREIGN KEY (`uploaded_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_media`
--

LOCK TABLES `site_media` WRITE;
/*!40000 ALTER TABLE `site_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by_admin_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`),
  KEY `site_settings_updated_by_admin_id_foreign` (`updated_by_admin_id`),
  CONSTRAINT `site_settings_updated_by_admin_id_foreign` FOREIGN KEY (`updated_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name',NULL,'text','brand',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(2,'site_tagline','Professional rental equipment platform.','text','footer',1,'2026-02-17 19:11:05','2026-02-20 17:50:40'),(3,'meta_title',NULL,'text','seo',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(4,'meta_description',NULL,'textarea','seo',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(5,'brand.name','Manake','text','branding',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(6,'brand.tagline','Sewa Di Manake!','text','branding',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(7,'brand.logo_path',NULL,'file','branding',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(8,'brand.favicon_path',NULL,'file','branding',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(9,'seo.meta_title',NULL,'text','seo',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(10,'seo.meta_description',NULL,'textarea','seo',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(11,'site.maintenance_enabled','0','boolean','website',1,'2026-02-17 19:11:05','2026-02-23 14:10:52'),(12,'hero_title',NULL,'text','home',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(13,'hero_subtitle',NULL,'textarea','home',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(14,'hero_cta_text','View Catalog','text','landing',1,'2026-02-17 19:11:05','2026-02-23 13:52:12'),(15,'home.hero_title',NULL,'text','home',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(16,'home.hero_subtitle',NULL,'textarea','home',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(17,'home.hero_image_path',NULL,'image','home',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(18,'home.hero_image_path_alt',NULL,'text','home',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(19,'home.overview_headline',NULL,'text','home',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(20,'footer_description',NULL,'textarea','footer',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(21,'footer.about',NULL,'textarea','footer',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(22,'footer_address',NULL,'textarea','footer',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(23,'footer.address',NULL,'textarea','footer',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(24,'footer_email',NULL,'text','footer',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(25,'footer_phone',NULL,'text','footer',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(26,'footer_copyright','2026 Manake Rental. All rights reserved.','text','footer',1,'2026-02-17 19:11:05','2026-02-20 17:50:40'),(27,'footer.whatsapp',NULL,'text','footer',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(28,'footer.instagram',NULL,'text','footer',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(29,'contact.email',NULL,'text','contact',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(30,'contact.phone',NULL,'text','contact',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(31,'contact.whatsapp',NULL,'text','contact',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(32,'contact.map_embed',NULL,'textarea','contact',1,'2026-02-17 19:11:05','2026-02-23 14:12:00'),(33,'contact_map_embed',NULL,'textarea','contact',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(34,'contact_form_receiver_email',NULL,'text','contact',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(35,'social_instagram',NULL,'text','social',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(36,'social_whatsapp',NULL,'text','social',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(37,'social_youtube',NULL,'text','social',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(38,'social_tiktok',NULL,'text','social',NULL,'2026-02-17 19:11:05','2026-02-17 19:11:05'),(39,'social.instagram',NULL,'text','social',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(40,'social.tiktok',NULL,'text','social',1,'2026-02-17 19:11:05','2026-02-19 21:25:53'),(41,'footer.rules_title','Panduan Sewa','text','footer',1,'2026-02-20 17:50:40','2026-02-20 17:50:40'),(42,'footer.rules_link','Rules Sewa & Kebijakan','text','footer',1,'2026-02-20 17:50:40','2026-02-20 17:50:40'),(43,'footer.rules_note','Pelajari aturan booking, reschedule, buffer, dan denda operasional.','textarea','footer',1,'2026-02-20 17:50:40','2026-02-20 17:50:40'),(44,'copy.landing.ready_panel_title','Siap Disewa','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(45,'copy.landing.ready_panel_subtitle','Item live dari inventory yang tersedia hari ini.','textarea','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(46,'copy.landing.flow_kicker','Alur Rental','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(47,'copy.landing.flow_title','Biar proses sewa tidak ribet','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(48,'copy.landing.flow_catalog_link','Lihat semua alat','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(49,'copy.landing.step_1_title','Pilih Alat','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(50,'copy.landing.step_1_desc','Filter berdasarkan kategori, status siap, dan budget harian.','textarea','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(51,'copy.landing.step_2_title','Isi Profil','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(52,'copy.landing.step_2_desc','Data identitas dan kontak disimpan agar transaksi berikutnya lebih cepat.','textarea','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(53,'copy.landing.step_3_title','Bayar via Midtrans','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(54,'copy.landing.step_3_desc','Pilih metode pembayaran favorit tanpa pindah halaman berulang.','textarea','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(55,'copy.landing.step_4_title','Buat Resi','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(56,'copy.landing.step_4_desc','Setelah lunas, resi bisa dibuka dan dicetak langsung dari detail pesanan.','textarea','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(57,'copy.landing.quick_category_kicker','Kategori Cepat','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(58,'copy.landing.quick_category_title','Akses langsung ke kebutuhan produksi','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(59,'copy.landing.quick_category_empty','Belum ada kategori tersedia.','text','landing',1,'2026-02-23 13:52:12','2026-02-23 13:52:12'),(60,'copy.trans.ui.contact.title','Contact Manake','text','contact',1,'2026-02-23 14:01:42','2026-02-23 14:01:42'),(61,'copy.trans.ui.contact.subtitle','Contact us for booking and collaboration.','textarea','contact',1,'2026-02-23 14:01:42','2026-02-23 14:01:42'),(62,'copy.trans.ui.contact.info_title','Address & Info','text','contact',1,'2026-02-23 14:01:42','2026-02-23 14:01:42'),(63,'copy.trans.ui.contact.map_title','Location Map','text','contact',1,'2026-02-23 14:01:42','2026-02-23 14:01:42'),(64,'copy.trans.ui.contact.map_empty',NULL,'text','contact',1,'2026-02-23 14:01:42','2026-02-23 14:04:38');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
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
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `preferred_theme` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_locale` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `is_otp_verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Fikri Mulya Rachmat','kikirachmat214@gmail.com','2026-02-17 20:07:34','$2y$12$g86p.CYJ0JKZq12A.1G7MeWUBev/1uu/4zuUNhE/PwxBAEoh/TPrS','user','light','id',NULL,NULL,NULL,NULL,'2026-02-17 19:48:03','2026-02-22 10:48:01',NULL,NULL,1),(2,'Invoice Shot','invoice-shot@example.com','2026-02-19 10:33:42','$2y$12$joGa.XCDRzpvIFp9410PQu72o7ohzh3wtl8QfGwr8Iqi5qNuKJUGS','user','dark',NULL,NULL,NULL,'081234567890','Jl. Sutera Vision No. 12, Jakarta Selatan','2026-02-19 10:33:42','2026-02-19 11:21:08',NULL,NULL,1),(3,'bck44565','bck44565@gmail.com','2026-02-23 13:05:16','$2y$12$A5a/V4yBNJuPU5.P09g8CO.ucUGHvkEx.xvwVEjD3yfXspkyR3dCy','user','dark','en',NULL,NULL,NULL,NULL,'2026-02-23 12:41:31','2026-02-23 14:27:30',NULL,NULL,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-23 21:50:16
