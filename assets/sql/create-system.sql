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

--
-- Table structure for table `pessoa`
--

CREATE TABLE `pessoa` (
  `id_pessoa` int(50) NOT NULL AUTO_INCREMENT,
  `n_pessoa` varchar(254) CHARACTER SET utf8 NOT NULL,
  `n_social` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `dt_nasc` date DEFAULT NULL,
  `email` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `ativo` int(10) DEFAULT NULL,
  `cpf` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `cpf_old` varchar(20) DEFAULT NULL,
  `sexo` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `ra` varchar(20) DEFAULT NULL,
  `ra_dig` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `ra_uf` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `rg` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `rg_dig` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `rg_oe` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `rg_uf` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `dt_rg` date DEFAULT NULL,
  `fk_id_rt` int(11) DEFAULT '1',
  `certidao` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `sus` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `pai` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `cpf_pai` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `mae` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `cpf_mae` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `mae_rg` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `mae_rg_dig` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `mae_rg_oe` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `dt_mae_rg` date DEFAULT NULL,
  `pai_rg` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `pai_rg_dig` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `pai_rg_oe` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `dt_pai_rg` date DEFAULT NULL,
  `mae_rg_uf` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `pai_rg_uf` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `responsavel` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `dt_resp` date DEFAULT NULL,
  `cpf_respons` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `rg_respons` varchar(20) DEFAULT NULL,
  `email_respons` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `nacionalidade` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `uf_nasc` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `cidade_nasc` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `deficiencia` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `cor_pele` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `tel1` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `ddd1` int(11) DEFAULT NULL,
  `tel2` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `ddd2` int(11) DEFAULT NULL,
  `tel3` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `ddd3` int(11) DEFAULT NULL,
  `obs` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_cartorio` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_acervo` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_regcivil` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_ano` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_tipolivro` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_numlivro` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_folha` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_termo` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `novacert_controle` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `dt_gdae` date DEFAULT NULL,
  `at_google` int(11) NOT NULL DEFAULT '0',
  `nis` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `emailgoogle` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `google_user_id` varchar(100) DEFAULT NULL,
  `duplicado_nome_data` int(11) DEFAULT NULL,
  `inep` varchar(20) DEFAULT NULL,
  `trabalho_pai` varchar(100) DEFAULT NULL,
  `end_trab_pai` varchar(254) DEFAULT NULL,
  `trabalho_mae` varchar(100) DEFAULT NULL,
  `end_trab_mae` varchar(254) DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parentesco` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`),
  UNIQUE KEY `ra` (`ra`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `emailgoogle` (`emailgoogle`),
  UNIQUE KEY `emailgoogle_2` (`emailgoogle`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `user_password` varchar(255) DEFAULT NULL,
  `fk_id_pessoa` int(50) DEFAULT NULL,
  `user_session_id` varchar(255) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `expira` date NOT NULL,
  `horas` text NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `google_token` text,
  PRIMARY KEY (`id_user`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;


CREATE TABLE `instancia` (
  `id_inst` int(11) NOT NULL AUTO_INCREMENT,
  `n_inst` varchar(255) NOT NULL,
  `fkid_inst_aut` int(11) NOT NULL,
  `ativo` int(10) NOT NULL,
  `fk_id_tp` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `terceirizada` int(11) DEFAULT NULL,
  `visualizar` int(11) NOT NULL DEFAULT '1',
  `manutencao` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_inst`),
  KEY `tp_inst` (`fk_id_tp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `ge_escolas` (
  `id_escola` int(3) NOT NULL AUTO_INCREMENT,
  `fk_id_inst` int(3) DEFAULT NULL,
  `cie_escola` int(6) DEFAULT NULL,
  `fk_id_tp_ens` varchar(50) DEFAULT NULL,
  `classe` int(11) NOT NULL,
  `ato_cria` varchar(255) NOT NULL,
  `ato_municipa` varchar(255) NOT NULL,
  `maps` varchar(500) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `esc_site` varchar(255) DEFAULT NULL,
  `esc_contato` varchar(255) DEFAULT NULL,
  `vizualizar` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id_escola`),
  KEY `fk_id_inst` (`fk_id_inst`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `acesso_pessoa` (
  `id_ac` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_gr` int(11) NOT NULL,
  `fk_id_inst` int(11) NOT NULL,
  PRIMARY KEY (`id_ac`),
  UNIQUE KEY `fk_id_pessoa_2` (`fk_id_pessoa`,`fk_id_gr`,`fk_id_inst`),
  KEY `fk_id_sistema` (`fk_id_gr`),
  KEY `fk_id_inst` (`fk_id_inst`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `grupo` (
  `id_gr` int(11) NOT NULL AUTO_INCREMENT,
  `n_gr` varchar(255) NOT NULL,
  `at_gr` int(11) NOT NULL,
  PRIMARY KEY (`id_gr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `acesso_gr` (
  `id_ag` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_gr` int(11) NOT NULL,
  `fk_id_sistema` int(11) NOT NULL,
  `fk_id_nivel` int(11) NOT NULL,
  PRIMARY KEY (`id_ag`),
  UNIQUE KEY `fk_id_pessoa_2` (`fk_id_gr`,`fk_id_sistema`,`fk_id_nivel`),
  KEY `fk_id_sistema` (`fk_id_sistema`),
  KEY `fk_id_nivel` (`fk_id_nivel`),
  KEY `fk_id_pessoa` (`fk_id_gr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ge2.nivel definition

CREATE TABLE `nivel` (
  `id_nivel` int(11) NOT NULL AUTO_INCREMENT,
  `n_nivel` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL,
  `fk_id_nivel` int(11) NOT NULL,
  PRIMARY KEY (`id_nivel`),
  UNIQUE KEY `n_nivel` (`n_nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ge2.sistema definition

CREATE TABLE `sistema` (
  `id_sistema` int(11) NOT NULL AUTO_INCREMENT,
  `arquivo` varchar(50) NOT NULL,
  `n_sistema` varchar(255) NOT NULL,
  `fk_id_fr` int(11) NOT NULL,
  `niveis` text NOT NULL,
  `descr_sistema` longtext NOT NULL,
  `ativo` int(10) NOT NULL,
  `fkid` int(11) DEFAULT NULL,
  `msg` text,
  `protegido` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_sistema`),
  KEY `fk_id_fr` (`fk_id_fr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ge2.framework definition

CREATE TABLE `framework` (
  `id_fr` int(11) NOT NULL AUTO_INCREMENT,
  `n_fr` varchar(50) NOT NULL,
  `end_fr` text NOT NULL,
  `ativo` int(11) NOT NULL,
  PRIMARY KEY (`id_fr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO instancia
VALUES (NULL, 'Administração', 0, 1,2, NULL, 0, 1, 0);


INSERT INTO ge2.framework (id_fr, n_fr, end_fr, ativo)
VALUES(1, 'Autenticador', '/sie/', 1);

INSERT INTO ge2.sistema (id_sistema, arquivo, n_sistema, fk_id_fr, niveis, descr_sistema, ativo, fkid, msg, protegido)
VALUES(1, 'princ', 'Sistema Principal', 1, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', '', 1, 0, '', 1);
INSERT INTO ge2.sistema (id_sistema, arquivo, n_sistema, fk_id_fr, niveis, descr_sistema, ativo, fkid, msg, protegido)
VALUES(2, 'admin', 'Administrador do Sistema (novo)', 1, 'a:1:{i:0;s:1:"2";}', '', 1, 0, NULL, NULL);
INSERT INTO ge2.sistema (id_sistema, arquivo, n_sistema, fk_id_fr, niveis, descr_sistema, ativo, fkid, msg, protegido)
VALUES(3, 'tdics', 'Núcleos WIT', 1, 'a:7:{i:0;s:1:"2";i:1;s:2:"39";i:2;s:1:"8";i:3;s:2:"10";i:4;s:2:"57";i:5;s:2:"24";i:6;s:2:"43";}', '', 1, 0, NULL, NULL);

INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(1, 'Administrador', 1, 0);


INSERT INTO acesso_gr VALUES(NULL, 1, 1, 1);
INSERT INTO acesso_gr VALUES(NULL, 1, 2, 1);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 1);

