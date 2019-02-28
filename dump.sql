-- MySQL dump 10.13  Distrib 5.7.25, for Linux (i686)
--
-- Host: localhost    Database: rezume_102
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.18.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `captcha`
--

DROP TABLE IF EXISTS `captcha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `word` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `captcha`
--

LOCK TABLES `captcha` WRITE;
/*!40000 ALTER TABLE `captcha` DISABLE KEYS */;
INSERT INTO `captcha` VALUES (132,1551349072,'178.155.72.6','KP3TSHPI'),(133,1551349075,'178.155.72.6','CP1KFLLV'),(134,1551349133,'178.155.72.6','N7U7KXES'),(135,1551349368,'178.155.72.6','KU9V4U11'),(136,1551349405,'178.155.72.6','CK8LHPJK'),(137,1551349428,'178.155.72.6','CXHY9W4C'),(138,1551349432,'178.155.72.6','RP3DZM2K'),(139,1551349440,'178.155.72.6','CC8Y39FK'),(140,1551349494,'178.155.72.6','8UTXI51B'),(141,1551349497,'178.155.72.6','AKSWFZJ3'),(142,1551349498,'178.155.72.6','VK3DWIM2'),(143,1551349500,'178.155.72.6','FTEVPHST'),(144,1551349502,'178.155.72.6','VE29FLFV'),(145,1551349504,'178.155.72.6','NE8RUIJS'),(146,1551349506,'178.155.72.6','95S5BNUM'),(147,1551349512,'178.155.72.6','JJX2PPID'),(148,1551349514,'178.155.72.6','R2DAWIVD'),(149,1551349516,'178.155.72.6','N3DTJ4KH'),(150,1551349518,'178.155.72.6','ZTCJFAIK'),(151,1551349519,'178.155.72.6','ELV2L2HP'),(152,1551349522,'178.155.72.6','342J2TRX'),(153,1551349544,'178.155.72.6','TB99MDCU'),(154,1551349546,'178.155.72.6','WA9YX77E'),(155,1551349547,'178.155.72.6','ILHRICB2'),(156,1551349548,'178.155.72.6','F9AJI19R'),(157,1551349839,'178.155.72.6','AYHSLBA1'),(158,1551350194,'178.155.72.6','YH4IYR44'),(159,1551350447,'178.155.72.6','J4J2WAZ2'),(160,1551350488,'178.155.72.6','YW43DC58'),(161,1551350643,'178.155.72.6','KKTTEJD1'),(162,1551350668,'178.155.72.6','P18B1LUI'),(163,1551350678,'178.155.72.6','WUNFNZK1'),(164,1551350688,'178.155.72.6','UAVN74EV'),(165,1551350707,'178.155.72.6','3P32J537'),(166,1551350729,'178.155.72.6','31W71PHE'),(167,1551350765,'178.155.72.6','HY9BZH1H'),(168,1551350826,'178.155.72.6','H8F7HAL7'),(169,1551350915,'178.155.72.6','SKCKDKSD'),(170,1551350924,'178.155.72.6','BUSFE7CR'),(172,1551351426,'178.155.72.6','RDBTK5DZ'),(173,1551351441,'178.155.72.6','ABSYC974'),(174,1551351487,'178.155.72.6','LPJ9FK1W'),(175,1551351620,'178.155.72.6','79P37SYH'),(176,1551351665,'178.155.72.6','7LA8JA4I'),(177,1551351730,'178.155.72.6','K9HWF4Z4'),(178,1551352002,'178.155.72.6','ZXJCHKR4'),(179,1551352032,'178.155.72.6','PV5T9EZV'),(180,1551352072,'178.155.72.6','PYHBPB4I'),(181,1551352106,'178.155.72.6','TRDAS9LD'),(182,1551352124,'178.155.72.6','HPCJJH3Z'),(183,1551352172,'178.155.72.6','51MDEPLF'),(184,1551352229,'178.155.72.6','LP2BZJSP'),(185,1551352348,'178.155.72.6','YSRV1U73'),(187,1551352474,'178.155.72.6','R21E8TDY'),(193,1551352691,'178.155.72.6','J7WKVXBE'),(194,1551352743,'178.155.72.6','RY3KS1RV'),(195,1551352845,'178.155.72.6','C1XNUFFC'),(196,1551353355,'178.155.72.6','VBWMY5P7'),(199,1551353443,'178.155.72.6','HN2ZEMY5'),(200,1551353847,'178.155.72.6','SYEUHI2T'),(201,1551353872,'178.155.72.6','MF85PB58'),(202,1551353908,'178.155.72.6','YH5DZJAU'),(203,1551354521,'178.155.72.6','UBUCWRFV'),(204,1551354563,'178.155.72.6','122S2VYW'),(205,1551354603,'178.155.72.6','CF4NWPHA'),(206,1551354655,'178.155.72.6','I54EMNZL'),(207,1551354664,'178.155.72.6','4CI4WME7'),(208,1551354683,'178.155.72.6','YED9J2XK'),(209,1551354701,'178.155.72.6','VNBH5ZKW'),(210,1551354717,'178.155.72.6','UDSBI3JN'),(212,1551354876,'178.155.72.6','7MET7TLX'),(213,1551354904,'178.155.72.6','4A4973D4');
/*!40000 ALTER TABLE `captcha` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'admin','Administrator'),(2,'members','General Users');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'Первый пост в гостевой!','2019-02-28'),(3,1,'Второй пост в гостевой','2019-02-28'),(4,1,'YED9J2XK','2019-02-28'),(5,1,'Четвертый пост','2019-02-28'),(6,4,'Это новый пост нового пользователя','2019-02-28');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'127.0.0.1','administrator','$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36','','admin@admin.com','',NULL,NULL,'JkvTupcr51T6EpH75Zflk.',1268889823,1551352844,1,'Admin','istrator','ADMIN','0'),(2,'178.155.72.6','main@main.ru test','$2y$08$U4LuFVsBonqE8j/z71JTCOVhGwHxZIFP6faiafuMcVD4NiQY0pevS',NULL,'mamontov.dp@gmail.com',NULL,'.OrM7Math3Ge95syvyVl4.503df51f31aaa1c1fe',1551270690,NULL,1551256128,1551256189,1,'mamontov.dp@gmall.com','Test','1','232323'),(3,'178.155.72.6','overanalyzer1@gmail.com','$2y$08$kPnSAA2LSia5S4vDjkB4Oev0ZCTMRwZ57zOSEnPglgc3A6s2xLTXu',NULL,'overanalyzer1@gmail.com',NULL,NULL,NULL,NULL,1551296447,1551296463,1,'overanalyzer1@gmail.com','overanalyzer1@gmail.com',NULL,NULL),(4,'178.155.72.6','test@test.ru','$2y$08$qZnN8OC7L.q613qOrfRq8OceJxZO0Rt9JFFy4piYnjUWTElKhH7r.',NULL,'test@test.ru',NULL,NULL,NULL,NULL,1551354861,1551354875,1,'test@test.ru','test@test.ru',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` VALUES (1,1,1),(2,1,2),(18,2,2),(19,3,2),(20,4,2);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-28 15:56:24
