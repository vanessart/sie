--
-- Table structure for table `tema_default`
--

DROP TABLE IF EXISTS `tema_default`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8 */;
CREATE TABLE `tema_default` (
  `id_td` int(11) NOT NULL AUTO_INCREMENT,
  `n_td` varchar(255) NOT NULL,
  PRIMARY KEY (`id_td`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tema_default`
--

INSERT INTO `tema_default` VALUES (1,'tema1');


--
-- Table structure for table `pessoa`
--
DROP TABLE IF EXISTS `pessoa`;
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

DROP TABLE IF EXISTS `instancia`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_escolas`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `acesso_pessoa`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE `grupo` (
  `id_gr` int(11) NOT NULL AUTO_INCREMENT,
  `n_gr` varchar(255) NOT NULL,
  `at_gr` int(11) NOT NULL,
  PRIMARY KEY (`id_gr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `acesso_gr`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `nivel`;
CREATE TABLE `nivel` (
  `id_nivel` int(11) NOT NULL AUTO_INCREMENT,
  `n_nivel` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL,
  `fk_id_nivel` int(11) NOT NULL,
  PRIMARY KEY (`id_nivel`),
  UNIQUE KEY `n_nivel` (`n_nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sistema`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `framework`;
CREATE TABLE `framework` (
  `id_fr` int(11) NOT NULL AUTO_INCREMENT,
  `n_fr` varchar(50) NOT NULL,
  `end_fr` text NOT NULL,
  `ativo` int(11) NOT NULL,
  PRIMARY KEY (`id_fr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dttie_suporte_trab`;
CREATE TABLE `dttie_suporte_trab` (
  `id_sup` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `n_pessoa` varchar(255) DEFAULT NULL,
  `fk_id_pessoa1` int(11) DEFAULT NULL,
  `n_pessoa1` varchar(255) DEFAULT NULL,
  `local_sup` varchar(254) NOT NULL,
  `tipo_sup` varchar(254) NOT NULL,
  `descr_sup` longtext NOT NULL,
  `devol_sup` longtext NOT NULL,
  `priori_sup` varchar(20) NOT NULL,
  `resp_sup` varchar(255) NOT NULL,
  `dt_prev_sup` date DEFAULT NULL,
  `status_sup` varchar(100) NOT NULL DEFAULT 'Não Visualizado',
  `obs_sup` longtext NOT NULL,
  `rastro_sup` varchar(255) NOT NULL,
  `retirar` int(11) NOT NULL,
  `rm` int(11) DEFAULT NULL,
  `fk_id_inst` int(11) DEFAULT NULL,
  `tel1` varchar(255) DEFAULT NULL,
  `tel2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel_ret` varchar(255) DEFAULT NULL,
  `email_ret` varchar(255) DEFAULT NULL,
  `dt_sup` date NOT NULL,
  `escola` int(11) DEFAULT NULL,
  `ultimo_lado` varchar(50) DEFAULT NULL,
  `tela_bloqueada` enum('S','N') DEFAULT 'N',
  `atendente` varchar(200) DEFAULT NULL,
  `rse_aluno` int(11) DEFAULT NULL,
  `tipo_cadamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_sup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_avise_me`;
CREATE TABLE `tdics_avise_me` (
  `id_avise` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `dt_avise` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_avise`),
  UNIQUE KEY `fk_id_pessoa_2` (`fk_id_pessoa`,`fk_id_turma`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_call_center`;
CREATE TABLE `tdics_call_center` (
  `id_pessoa` int(11) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `obs` varchar(500) DEFAULT NULL,
  `contactou` int(11) DEFAULT '0',
  `fk_id_sit` int(11) DEFAULT '0',
  PRIMARY KEY (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_call_center_sit`;
CREATE TABLE `tdics_call_center_sit` (
  `id_sit` int(11) NOT NULL AUTO_INCREMENT,
  `n_sit` varchar(20) NOT NULL,
  PRIMARY KEY (`id_sit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_curso`;
CREATE TABLE `tdics_curso` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `n_curso` varchar(255) NOT NULL,
  `abrev` varchar(2) DEFAULT NULL,
  `icone` varchar(20) DEFAULT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `descricao` text,
  PRIMARY KEY (`id_curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_horarios`;
CREATE TABLE `tdics_horarios` (
  `id_horarios` int(11) NOT NULL AUTO_INCREMENT,
  `periodo` varchar(2) NOT NULL,
  `horario` tinyint(4) NOT NULL,
  `inicio` varchar(5) NOT NULL,
  `termino` varchar(5) NOT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `dt_horarios` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_horarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_inscricao`;
CREATE TABLE `tdics_inscricao` (
  `id_inscricao` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `dt_inscricao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_inscricao`),
  KEY `fk_id_turma` (`fk_id_turma`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_pl`;
CREATE TABLE `tdics_pl` (
  `id_pl` int(11) NOT NULL AUTO_INCREMENT,
  `n_pl` varchar(100) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_polo`;
CREATE TABLE `tdics_polo` (
  `id_polo` int(11) NOT NULL,
  `n_polo` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_polo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_setup`;
CREATE TABLE `tdics_setup` (
  `id_setup` int(11) NOT NULL AUTO_INCREMENT,
  `qt_turma` int(11) DEFAULT NULL,
  `matri` int(11) DEFAULT NULL,
  `matri_prev` int(11) DEFAULT NULL,
  `qt_curso_aluno` int(11) DEFAULT '1',
  `fk_id_pl_certificado` int(11) DEFAULT NULL,
  `dt_certif` date DEFAULT NULL,
  PRIMARY KEY (`id_setup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_turma`;
CREATE TABLE `tdics_turma` (
  `id_turma` int(11) NOT NULL AUTO_INCREMENT,
  `n_turma` varchar(20) NOT NULL,
  `periodo` varchar(2) NOT NULL,
  `fk_id_pl` int(11) NOT NULL,
  `horario` int(11) NOT NULL,
  `fk_id_curso` int(11) NOT NULL,
  `fk_id_polo` int(11) NOT NULL,
  `dia_sem` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_turma`),
  UNIQUE KEY `n_turma` (`n_turma`,`fk_id_pl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tdics_turma_aluno`;
CREATE TABLE `tdics_turma_aluno` (
  `id_ta` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `times_tamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ta`),
  UNIQUE KEY `fk_id_pessoa` (`fk_id_pessoa`,`fk_id_turma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_inst_curso`;
CREATE TABLE `sed_inst_curso` (
  `id_ic` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_inst` int(11) NOT NULL,
  `fk_id_curso` int(11) NOT NULL,
  PRIMARY KEY (`id_ic`),
  UNIQUE KEY `fk_id_inst` (`fk_id_inst`,`fk_id_curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_turma_aluno`;
CREATE TABLE `ge_turma_aluno` (
  `id_turma_aluno` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_classe` varchar(20) DEFAULT NULL,
  `fk_id_turma` int(11) DEFAULT NULL,
  `periodo_letivo` varchar(20) DEFAULT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `fk_id_inst` int(11) DEFAULT NULL,
  `chamada` int(11) DEFAULT NULL,
  `situacao` varchar(100) DEFAULT NULL,
  `dt_matricula` date DEFAULT NULL,
  `dt_matricula_fim` date DEFAULT NULL,
  `dt_transferencia` date DEFAULT NULL,
  `origem_escola` varchar(100) DEFAULT NULL,
  `destino_escola` varchar(100) DEFAULT NULL,
  `destino_escola_cidade` varchar(255) DEFAULT NULL,
  `destino_escola_uf` varchar(10) DEFAULT NULL,
  `tp_destino` varchar(10) DEFAULT NULL,
  `justificativa_transf` varchar(100) DEFAULT NULL,
  `turma_status` varchar(10) DEFAULT NULL,
  `situacao_final` varchar(20) DEFAULT NULL,
  `conselho` int(11) DEFAULT NULL,
  `dt_gdae` date DEFAULT NULL,
  `gdae` varchar(255) DEFAULT NULL,
  `fk_id_sit_sed` int(11) DEFAULT NULL,
  `fk_id_ciclo_aluno` int(11) DEFAULT NULL,
  `fk_id_tas` int(11) DEFAULT NULL,
  `fk_id_sf` int(11) DEFAULT '0',
  PRIMARY KEY (`id_turma_aluno`),
  UNIQUE KEY `fk_id_turma` (`fk_id_turma`,`chamada`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`),
  KEY `id_turma` (`fk_id_turma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `ge_turmas`;
CREATE TABLE `ge_turmas` (
  `id_turma` int(11) NOT NULL AUTO_INCREMENT,
  `n_turma` varchar(255) NOT NULL,
  `fk_id_inst` int(11) NOT NULL,
  `fk_id_ciclo` int(11) NOT NULL,
  `fk_id_grade` int(11) DEFAULT NULL,
  `fk_id_sala` int(11) DEFAULT NULL,
  `professor` varchar(255) DEFAULT NULL,
  `rm_prof` varchar(20) DEFAULT NULL,
  `codigo` varchar(10) NOT NULL,
  `periodo` varchar(10) NOT NULL,
  `periodo_letivo` varchar(20) NOT NULL,
  `fk_id_pl` int(11) DEFAULT NULL,
  `letra` varchar(10) NOT NULL,
  `prodesp` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `transferencia` varchar(20) DEFAULT NULL,
  `dt_gdae` datetime DEFAULT NULL,
  `alunos_gdae` int(11) DEFAULT NULL,
  `gdae_set` int(11) DEFAULT NULL,
  `times_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_turma`),
  UNIQUE KEY `prodesp` (`prodesp`),
  KEY `fk_id_inst` (`fk_id_inst`),
  KEY `fk_id_ciclo` (`fk_id_ciclo`),
  KEY `fk_id_grade` (`fk_id_grade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `ge_periodo_letivo`;
CREATE TABLE `ge_periodo_letivo` (
  `id_pl` int(11) NOT NULL AUTO_INCREMENT,
  `n_pl` varchar(100) NOT NULL,
  `at_pl` int(2) NOT NULL,
  `ano` int(11) NOT NULL,
  `semestre` int(11) NOT NULL,
  `preferencial` int(2) DEFAULT '0',
  PRIMARY KEY (`id_pl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO ge2.pessoa (id_pessoa, n_pessoa, n_social, dt_nasc, email, ativo, cpf, cpf_old, sexo, ra, ra_dig, ra_uf, rg, rg_dig, rg_oe, rg_uf, dt_rg, fk_id_rt, certidao, sus, pai, cpf_pai, mae, cpf_mae, mae_rg, mae_rg_dig, mae_rg_oe, dt_mae_rg, pai_rg, pai_rg_dig, pai_rg_oe, dt_pai_rg, mae_rg_uf, pai_rg_uf, responsavel, dt_resp, cpf_respons, rg_respons, email_respons, nacionalidade, uf_nasc, cidade_nasc, deficiencia, cor_pele, tel1, ddd1, tel2, ddd2, tel3, ddd3, obs, novacert_cartorio, novacert_acervo, novacert_regcivil, novacert_ano, novacert_tipolivro, novacert_numlivro, novacert_folha, novacert_termo, novacert_controle, dt_gdae, at_google, nis, emailgoogle, google_user_id, duplicado_nome_data, inep, trabalho_pai, end_trab_pai, trabalho_mae, end_trab_mae, update_at, parentesco)
VALUES(1, 'CRISTIANO ARRUDA', NULL, '1986-07-27', 'ti.cristianoarruda@educbarueri.sp.gov.br', 1, '05330469988', NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'IVONETE ARRUDA DA SILVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'brasileiro', 'PR', 'Terra Rica', NULL, NULL, '981267738', 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'ti.cristianoarruda@educbarueri.sp.gov.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-02 16:05:17', NULL);


INSERT INTO ge2.users (id_user, user_password, fk_id_pessoa, user_session_id, ativo, expira, horas, google_id, google_token)
VALUES(1, '$2a$08$3Xe1WzYN6snvza1d98EujOkRKJNKvII6iaFrZAtePiYH7bdZyBY9i', 1, '8ptrf5hldsmkt0aji3a3kr6cr8', 1, '2023-03-01', '', '107750209957574070253', '230452642a003cdd6a5');

INSERT INTO ge2.grupo (id_gr, n_gr, at_gr)
VALUES(1, 'MASTER', 1);

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

INSERT INTO acesso_pessoa VALUES(NULL, 1, 1, 1);

INSERT INTO ge2.tdics_pl (id_pl, n_pl, ativo)
VALUES(2, '1º Semestre de 2023', 1);

INSERT INTO ge2.tdics_setup (id_setup, qt_turma, matri, matri_prev, qt_curso_aluno, fk_id_pl_certificado, dt_certif)
VALUES(1, 16, 0, 0, 1, 1, '2022-12-19');


