-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: lechoppedb
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.3

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
-- Table structure for table `consumer`
--

DROP TABLE IF EXISTS `consumer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consumer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consumer`
--

LOCK TABLES `consumer` WRITE;
/*!40000 ALTER TABLE `consumer` DISABLE KEYS */;
INSERT INTO `consumer` VALUES (1,'cristiano','0766666666','cr7@gmail.com');
/*!40000 ALTER TABLE `consumer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_order`
--

DROP TABLE IF EXISTS `customer_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `total` double NOT NULL,
  `created_at` datetime NOT NULL,
  `consumer_id` int NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'to_prepare',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3B1CE6A337FDBD6D` (`consumer_id`),
  CONSTRAINT `FK_3B1CE6A337FDBD6D` FOREIGN KEY (`consumer_id`) REFERENCES `consumer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_order`
--

LOCK TABLES `customer_order` WRITE;
/*!40000 ALTER TABLE `customer_order` DISABLE KEYS */;
INSERT INTO `customer_order` VALUES (1,122,'2026-07-03 03:24:22',1,'to_prepare');
/*!40000 ALTER TABLE `customer_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_order_item`
--

DROP TABLE IF EXISTS `customer_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_order_item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantity` int NOT NULL,
  `unit_price_at_purchase` double NOT NULL,
  `product_id` int NOT NULL,
  `customer_order_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AF231B8B4584665A` (`product_id`),
  KEY `IDX_AF231B8BA15A2E17` (`customer_order_id`),
  CONSTRAINT `FK_AF231B8B4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_AF231B8BA15A2E17` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_order` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_order_item`
--

LOCK TABLES `customer_order_item` WRITE;
/*!40000 ALTER TABLE `customer_order_item` DISABLE KEYS */;
INSERT INTO `customer_order_item` VALUES (1,2,55,6,1),(2,1,12,4,1);
/*!40000 ALTER TABLE `customer_order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20260316153031','2026-04-01 09:01:54',19),('DoctrineMigrations\\Version20260406114833','2026-04-06 11:49:06',166),('DoctrineMigrations\\Version20260411103438','2026-04-11 10:34:57',181),('DoctrineMigrations\\Version20260417131512','2026-04-17 13:15:35',100),('DoctrineMigrations\\Version20260422083922','2026-04-22 08:39:56',216),('DoctrineMigrations\\Version20260423121244','2026-04-23 12:12:55',19),('DoctrineMigrations\\Version20260423123825','2026-04-23 12:38:37',77),('DoctrineMigrations\\Version20260423123931','2026-04-23 12:39:35',21),('DoctrineMigrations\\Version20260423174026','2026-04-23 17:40:55',48),('DoctrineMigrations\\Version20260424075642','2026-04-24 07:56:56',26),('DoctrineMigrations\\Version20260424075754','2026-04-24 07:58:08',34),('DoctrineMigrations\\Version20260424081036','2026-04-24 08:10:44',88),('DoctrineMigrations\\Version20260507133231','2026-05-07 13:32:50',30),('DoctrineMigrations\\Version20260511204857','2026-05-11 20:49:10',60),('DoctrineMigrations\\Version20260515183046','2026-05-15 18:31:09',63),('DoctrineMigrations\\Version20260522132022','2026-05-22 13:20:36',186),('DoctrineMigrations\\Version20260522135607','2026-05-22 13:56:11',52),('DoctrineMigrations\\Version20260702181440','2026-07-02 18:18:37',334),('DoctrineMigrations\\Version20260712151057','2026-07-12 16:33:37',28);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` longtext,
  `unit` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `producer_id` int DEFAULT NULL,
  `image_file_name` varchar(255) DEFAULT NULL,
  `certifications` json DEFAULT NULL,
  `state` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04AD89B658FE` (`producer_id`),
  CONSTRAINT `FK_D34A04AD89B658FE` FOREIGN KEY (`producer_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'qqqqqqqqqq',NULL,'zzzzzzzzzzzzz','zzzzzz',33,NULL,NULL,NULL,NULL,NULL,'active'),(2,'hh',NULL,'hhhh','hh',22,NULL,NULL,NULL,NULL,NULL,'active'),(3,'ff   ff fff fff de mon pp','ff-ff-fff-fff-de-mon-pp','dd','dd',22,NULL,NULL,NULL,NULL,NULL,'active'),(4,'DD   ééééé ddd','DD-eeeee-ddd-','ddd','dd',12,NULL,NULL,NULL,NULL,NULL,'active'),(5,'rrrr','rrrr-','rr','eee',55,NULL,NULL,NULL,'69ea59cb25638.jpg',NULL,'active'),(6,'dd','dd-','dd','dd',55,NULL,NULL,1,'69ea676967422.jpg','[\"AOP\", \"AOC\", \"Nature & Progrès\"]','inactive'),(7,'fraise','fraise-','joliee fraises','barquette 250g',10,NULL,NULL,1,'69fafc3b5cb8d.png','[\"Nature & Progrès\"]','inactive'),(8,'pommes','pommes-','rouge et brillantes','la pièce',1,NULL,NULL,1,'6a0230e619025.jpg','[\"AOP\"]','active'),(9,'miel','miel-','miel de lavande','pot de 500g',15,NULL,NULL,5,NULL,'[\"Bio\"]','inactive'),(10,'abricots','abricots-','ee','1kg',5,NULL,NULL,5,'6a03156b23468.jpg','[\"Nature & Progrès\"]','active'),(11,'pommes','pommes-','pommes rouges','1kg',4,NULL,NULL,5,'6a070b7284cc0.jpg','[\"Bio\"]','active');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_sell_slot`
--

DROP TABLE IF EXISTS `product_sell_slot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_sell_slot` (
  `product_id` int NOT NULL,
  `sell_slot_id` int NOT NULL,
  PRIMARY KEY (`product_id`,`sell_slot_id`),
  KEY `IDX_DDA5BD3D4584665A` (`product_id`),
  KEY `IDX_DDA5BD3D308116D7` (`sell_slot_id`),
  CONSTRAINT `FK_DDA5BD3D308116D7` FOREIGN KEY (`sell_slot_id`) REFERENCES `sell_slot` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_DDA5BD3D4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_sell_slot`
--

LOCK TABLES `product_sell_slot` WRITE;
/*!40000 ALTER TABLE `product_sell_slot` DISABLE KEYS */;
INSERT INTO `product_sell_slot` VALUES (2,1),(3,1),(4,1),(5,2),(6,1),(6,2),(7,2),(7,3),(8,2),(9,5),(9,6),(10,5),(11,6);
/*!40000 ALTER TABLE `product_sell_slot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sell_slot`
--

DROP TABLE IF EXISTS `sell_slot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sell_slot` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `shop_id` int DEFAULT NULL,
  `state` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B3050BB04D16C4DD` (`shop_id`),
  CONSTRAINT `FK_B3050BB04D16C4DD` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sell_slot`
--

LOCK TABLES `sell_slot` WRITE;
/*!40000 ALTER TABLE `sell_slot` DISABLE KEYS */;
INSERT INTO `sell_slot` VALUES (1,'2026-04-22 19:20:00','2026-04-08 18:19:00',NULL,NULL,5,'active'),(2,'2026-05-02 17:59:00','2026-04-06 15:56:00',NULL,NULL,4,'active'),(3,'2026-04-08 13:56:00','2026-04-04 15:57:00',NULL,NULL,5,'active'),(4,'2026-05-08 15:47:00','2026-05-08 18:50:00',NULL,NULL,6,'inactive'),(5,'2026-05-14 17:00:00','2026-05-14 19:00:00',NULL,NULL,12,'active'),(6,'2026-04-20 17:00:00','2026-04-20 19:00:00',NULL,NULL,13,'active');
/*!40000 ALTER TABLE `sell_slot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop`
--

DROP TABLE IF EXISTS `shop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image_file_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `description` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `producer_id` int DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AC6A4CA289B658FE` (`producer_id`),
  CONSTRAINT `FK_AC6A4CA289B658FE` FOREIGN KEY (`producer_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop`
--

LOCK TABLES `shop` WRITE;
/*!40000 ALTER TABLE `shop` DISABLE KEYS */;
INSERT INTO `shop` VALUES (1,NULL,'ss','ss','ss','ssss',NULL,NULL,NULL,NULL,NULL,NULL),(2,NULL,'dd','ddd','ddd','ddd','ddd',NULL,NULL,1,NULL,NULL),(4,'69f9e83146cd7.png','ma ferme','issi','laudpostal','dd','dd','2026-04-11 12:41:48','2026-05-05 14:53:05',1,NULL,NULL),(5,'69da42e25eeed.jpg','la ferme des Zoldik','20 traverse du blabla','04300','ggggg','mmy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsu','2026-04-11 12:47:30','2026-04-23 11:37:00',1,NULL,NULL),(6,NULL,'ff','fff','fff','fff','fffff','2026-04-12 10:17:37','2026-04-12 10:17:37',1,NULL,NULL),(9,'69e53a4974f8d.jpg','domaine de Kure','127 plage de l(estaque','13016','marseille','t ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2026-04-19 20:25:45','2026-05-17 22:47:49',5,43.3575499,5.319544),(12,'69e77c2febbc3.jpg','souribigbig','chémoa','fff','vilici','descripssion','2026-04-21 13:31:27','2026-04-21 13:31:27',5,NULL,NULL),(13,'69e77c3ccf770.avif','nouvelle','20 traverse de la montre','13012','marseille','descripssion','2026-04-21 13:31:40','2026-05-19 09:24:56',5,43.2939435,5.4744292),(14,'6a0230541accf.jpg','blabla','50 blblaland','04300','mbk','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2026-05-11 21:39:00',NULL,1,NULL,NULL),(15,NULL,'ihishdf sdjbiob','13 impasse ds cyprées','04300','forcalquier','ihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiob','2026-07-13 01:18:12',NULL,1,NULL,NULL),(16,NULL,'FDFHFDH','DDGD','HFGHGHF','FFFHFH','ihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiob','2026-07-13 01:18:53',NULL,1,NULL,NULL),(17,NULL,'FGDHD','HFDHE','HRHHT','HREHHRHH','ihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiob','2026-07-13 01:19:19',NULL,1,NULL,NULL),(18,NULL,'ZYTRG','SDDFH','HDHRTHR','DFHTRHR','ZREZihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiobihishdf sdjbiob','2026-07-13 01:19:37',NULL,1,NULL,NULL),(19,'6a564bbf5e9e3.jpg',NULL,NULL,NULL,NULL,NULL,'2026-07-14 16:46:24',NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `shop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `roles` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'eeeeeeeeee','eeeeeeeee','mister@gmail.com','$2y$13$0A6.Ce2VdZDUyoY1WtarSeHYqpH0quE6miZyMkNX9sbQVdkvHJkkG','[]',NULL,NULL),(2,'zz','aa','rambo@gmail.com','$2y$13$ZkaBE5uAsBf.vNQJ8Tr07uFtQ.xNQfUHsBKUloFCCouBOVHh/LFl.','[]',NULL,NULL),(3,'zoro','juro','zoro@gmail.com','$2y$13$zPm3Us0Qhsqzl3FsHbdGOO9IRpjY00CxvbLJ9NXEmtpXjdUVFp1qq','[]',NULL,NULL),(4,'barto','kuma','kuma@gmail.com','$2y$13$9K9LZUeQNCKYlOSnhor8XOfKYNaZiZ6aS6p.omU4sB7aw2AQA2IJa','[]',NULL,NULL),(5,'zoldik','haruka','haruka@gmail.com','$2y$13$ui3huA1bv8OVmPtm7CLstOSPhkgcBO9GLm/A7LLj6gwVbNJQnwaIq','[]',NULL,NULL),(6,'friks','gon','gon@gmail.com','$2y$13$UZ6zr1SpOptyhSnwP1Dal.jeJjytKcr7NzzUvvRv2DRRzCezSxRAu','[]',NULL,NULL),(7,'ff','llaa','ll@gmail.com','$2y$13$DzPLxaOkMTyHLR.pBPzW2.5KrVdB0Al41502/5R.S.ox5n7YiCQda','[]',NULL,NULL),(8,'ff','llaa','lldd@gmail.com','$2y$13$I/KuZgTvZ4/mkRaIa6P.duV55lWp2/s9L9h/Re8bJTI5Em4obk.Sq','[]',NULL,NULL),(9,'Rani','Kabbouri','rani.kabbouri+5@gmail.com','$2y$13$ZUpF5lhGBU8V7WGa/0fzzeqECoHr0HMeMXqLqjTkQVh/RExC.sh7G','[]',NULL,NULL),(10,'marco','marco','marco@gmail.com','$2y$13$zPL6jJ6tZ3XT/pivGjiXGejoYbPokgQjaj67JutwFeGmSp8GaxKZC','[]',NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-15  2:21:42
