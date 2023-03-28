--
-- Table structure for table `tema_default`
--

DROP TABLE IF EXISTS `tema_default`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tema_default` (
  `id_td` int(11) NOT NULL AUTO_INCREMENT,
  `n_td` varchar(255) NOT NULL,
  PRIMARY KEY (`id_td`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tema_default`
--

LOCK TABLES `tema_default` WRITE;
/*!40000 ALTER TABLE `tema_default` DISABLE KEYS */;
INSERT INTO `tema_default` VALUES (1,'tema1');
/*!40000 ALTER TABLE `tema_default` ENABLE KEYS */;
UNLOCK TABLES;

