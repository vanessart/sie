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
  `fk_id_tp` int DEFAULT NULL
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

DROP TABLE IF EXISTS `ge_aluno_necessidades_especiais`;
CREATE TABLE `ge_aluno_necessidades_especiais` (
  `id_def` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `ra` int(11) NOT NULL,
  `ra_uf` varchar(11) DEFAULT NULL,
  `fk_id_ne` int(11) NOT NULL,
  `fk_id_porte` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id_def`),
  UNIQUE KEY `ra` (`ra`,`fk_id_ne`),
  KEY `id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_aluno_necessidades_especiais_porte`;
CREATE TABLE `ge_aluno_necessidades_especiais_porte` (
  `id_porte` int(11) NOT NULL AUTO_INCREMENT,
  `n_porte` varchar(255) NOT NULL,
  PRIMARY KEY (`id_porte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pagina`;
CREATE TABLE `pagina` (
  `id_pag` int(11) NOT NULL AUTO_INCREMENT,
  `pagina` text,
  `n_pag` varchar(100) NOT NULL,
  `descr_page` mediumtext,
  `ord_pag` int(11) NOT NULL,
  `fk_id_sistema` int(11) NOT NULL,
  `posi_pag` varchar(11) DEFAULT NULL,
  `view` text,
  `ativo` int(11) NOT NULL,
  PRIMARY KEY (`id_pag`),
  KEY `fk_id_sistema` (`fk_id_sistema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sis_nivel_pag`;
CREATE TABLE `sis_nivel_pag` (
  `id_sn` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_sistema` int(11) NOT NULL,
  `fk_id_nivel` int(11) NOT NULL,
  `paginas` longtext,
  `fk_id_pag` int(11) NOT NULL,
  PRIMARY KEY (`id_sn`),
  KEY `fk_id_sistema` (`fk_id_sistema`),
  KEY `fk_id_nivel` (`fk_id_nivel`),
  KEY `fk_id_pag` (`fk_id_pag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `tipo_user`;
CREATE TABLE `tipo_user` (
  `id_tp` int(11) NOT NULL AUTO_INCREMENT,
  `n_tp` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_funcionario`;
CREATE TABLE `ge_funcionario` (
  `id_func` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `rm` varchar(50) NOT NULL,
  `funcao` varchar(255) NOT NULL,
  `situacao` varchar(255) DEFAULT NULL,
  `fk_id_inst` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `cel` varchar(255) DEFAULT NULL,
  `at_func` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_func`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`,`fk_id_inst`),
  KEY `fk_id_inst` (`fk_id_inst`),
  KEY `fk_id_pessoa_2` (`fk_id_pessoa`),
  KEY `rm` (`rm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `telefones`;
CREATE TABLE `telefones` (
  `id_tel` int(11) NOT NULL AUTO_INCREMENT,
  `fkid` int(11) DEFAULT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `fk_id_tp` int(11) NOT NULL DEFAULT '1',
  `ddd` int(11) DEFAULT NULL,
  `num` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT 'Fixo',
  `complemento` varchar(255) DEFAULT NULL,
  `fk_id_tt` int(11) DEFAULT '1',
  PRIMARY KEY (`id_tel`),
  KEY `telefones_fk_id_pessoa_IDX` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `telefones_tipo`;
CREATE TABLE `telefones_tipo` (
  `id_tt` int(11) NOT NULL AUTO_INCREMENT,
  `n_tt` varchar(20) NOT NULL,
  PRIMARY KEY (`id_tt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `endereco`;
CREATE TABLE `endereco` (
  `id_end` int(11) NOT NULL AUTO_INCREMENT,
  `fkid` int(11) DEFAULT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `fk_id_tp` int(11) NOT NULL DEFAULT '1',
  `cep` varchar(20) DEFAULT NULL,
  `logradouro` varchar(254) DEFAULT NULL,
  `logradouro_gdae` varchar(255) DEFAULT NULL,
  `num` varchar(50) DEFAULT NULL,
  `num_gdae` varchar(100) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(254) DEFAULT NULL,
  `cidade` varchar(254) DEFAULT NULL,
  `uf` varchar(254) DEFAULT NULL,
  `dt_barueri` date DEFAULT NULL,
  `latitude` varchar(100) DEFAULT '0',
  `longitude` varchar(100) DEFAULT '0',
  `distancia` varchar(100) DEFAULT NULL,
  `tempo` varchar(100) DEFAULT NULL,
  `bloco` varchar(100) DEFAULT NULL,
  `torre` varchar(100) DEFAULT NULL,
  `apart` varchar(100) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_end`),
  UNIQUE KEY `fkid` (`fkid`),
  UNIQUE KEY `fk_id_pessoa_2` (`fk_id_pessoa`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `sed_quadro`;
CREATE TABLE `sed_quadro` (
  `id_q` int(11) NOT NULL AUTO_INCREMENT,
  `n_q` varchar(255) NOT NULL,
  `descr_q` text,
  `tp_ensino` varchar(255) DEFAULT NULL,
  `dt_ini` date DEFAULT NULL,
  `dt_fim` date DEFAULT NULL,
  `at_q` int(11) DEFAULT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_q`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `protocolo_cadastro`;
CREATE TABLE `protocolo_cadastro` (
  `id_protocolo` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_area` int(11) DEFAULT NULL,
  `fk_id_status` int(11) DEFAULT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `dt_update` datetime DEFAULT CURRENT_TIMESTAMP,
  `fk_id_pessoa_cadastra` int(11) DEFAULT NULL,
  `fk_id_ne` int(11) DEFAULT NULL,
  `fk_id_inst` int(11) DEFAULT NULL,
  `descricao` text,
  `dt_resp_form1` datetime DEFAULT CURRENT_TIMESTAMP,
  `fk_id_inst_AEE` int(11) DEFAULT NULL,
  `fk_id_turma_AEE` int(11) DEFAULT NULL,
  `fk_id_pl` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_protocolo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `protocolo_termo`;
CREATE TABLE `protocolo_termo` (
  `id_protocolo_termo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('A','R') NOT NULL,
  `fk_id_protocolo` int(11) NOT NULL,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_pessoa_aluno` int(11) NOT NULL,
  `fk_id_assinatura` int(11) NOT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `dias` varchar(100) DEFAULT NULL,
  `horarios` varchar(100) DEFAULT NULL,
  `motivo` varchar(250) DEFAULT NULL,
  `autorizado` tinyint(4) DEFAULT NULL,
  `autorizado_img` tinyint(2) DEFAULT NULL,
  `conduzido_por` varchar(150) DEFAULT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `dt_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_protocolo_termo`) USING BTREE,
  KEY `fk_id_protocolo` (`fk_id_protocolo`) USING BTREE,
  KEY `ativo` (`ativo`) USING BTREE,
  KEY `tipo` (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_decl_vaga_comp`;
CREATE TABLE `ge_decl_vaga_comp` (
  `id_vaga_c` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_inst` int(11) NOT NULL,
  `nome_solicitante` varchar(100) NOT NULL,
  `rg` varchar(20) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `n_ciclo` varchar(20) DEFAULT NULL,
  `dt_emissao` date NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `h_inicio` time DEFAULT NULL,
  `h_final` time DEFAULT NULL,
  `rse` int(10) DEFAULT NULL,
  `nome_aluno` varchar(100) DEFAULT NULL,
  `codigo` varchar(10) NOT NULL,
  `sexo_aluno` varchar(10) NOT NULL,
  `dt_comp` date DEFAULT NULL,
  `tipodec` int(11) DEFAULT NULL,
  `fk_id_ciclo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_vaga_c`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `ge_turma_aluno_situacao`;
CREATE TABLE `ge_turma_aluno_situacao` (
  `id_tas` int(11) NOT NULL AUTO_INCREMENT,
  `n_tas` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_ciclos`;
CREATE TABLE `ge_ciclos` (
  `id_ciclo` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_curso` int(11) NOT NULL,
  `n_ciclo` varchar(255) NOT NULL,
  `sg_ciclo` varchar(50) NOT NULL,
  `aprova_automatico` int(11) NOT NULL,
  `fk_id_grade` int(11) DEFAULT NULL,
  `periodicidade` varchar(20) NOT NULL,
  `ativo` int(11) NOT NULL,
  `SerieAno` int(11) DEFAULT NULL,
  `aulas` int(11) DEFAULT '1',
  `dias_semana` text,
  PRIMARY KEY (`id_ciclo`),
  KEY `fk_id_curso` (`fk_id_curso`,`fk_id_grade`),
  KEY `fk_id_grade` (`fk_id_grade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_cursos`;
CREATE TABLE `ge_cursos` (
  `id_curso` int(1) NOT NULL AUTO_INCREMENT,
  `n_curso` varchar(255) NOT NULL,
  `sg_curso` varchar(20) NOT NULL,
  `descr_curso` varchar(255) DEFAULT NULL,
  `fk_id_tp_ens` int(11) NOT NULL,
  `fk_id_ta` int(11) NOT NULL,
  `fk_id_calcaval` int(11) NOT NULL,
  `notas` varchar(255) DEFAULT NULL,
  `notas_legenda` text,
  `corte` varchar(10) DEFAULT NULL,
  `ativo` int(11) NOT NULL,
  `un_letiva` varchar(255) NOT NULL,
  `sg_letiva` varchar(20) NOT NULL,
  `qt_letiva` int(11) NOT NULL,
  `atual_letiva` varchar(11) DEFAULT NULL,
  `conceito_final` int(11) DEFAULT NULL,
  `extra` int(11) DEFAULT NULL,
  `TipoEnsino` int(11) NOT NULL,
  PRIMARY KEY (`id_curso`),
  KEY `fk_id_tp_ens` (`fk_id_tp_ens`,`fk_id_ta`,`fk_id_calcaval`),
  KEY `fk_id_ta` (`fk_id_ta`),
  KEY `fk_id_calcaval` (`fk_id_calcaval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_eventos`;
CREATE TABLE `ge_eventos` (
  `id_evento` int(11) NOT NULL AUTO_INCREMENT,
  `evento` varchar(100) NOT NULL,
  `dt_evento` date NOT NULL,
  `h_inicio` time NOT NULL,
  `h_final` time NOT NULL,
  `local_evento` varchar(100) NOT NULL,
  `fk_id_inst` int(11) NOT NULL,
  `ano_letivo` int(11) NOT NULL,
  `ev_resp` varchar(100) NOT NULL,
  `titulo_evento` varchar(100) NOT NULL,
  PRIMARY KEY (`id_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `instancia_predio`;
CREATE TABLE `instancia_predio` (
  `id_ip` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_inst` int(11) NOT NULL,
  `fk_id_predio` int(11) NOT NULL,
  `sede` int(11) NOT NULL,
  PRIMARY KEY (`id_ip`),
  KEY `fk_id_inst` (`fk_id_inst`,`fk_id_predio`),
  KEY `fk_id_predio` (`fk_id_predio`),
  KEY `fk_id_inst_2` (`fk_id_inst`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `predio`;
CREATE TABLE `predio` (
  `id_predio` int(11) NOT NULL AUTO_INCREMENT,
  `n_predio` varchar(255) NOT NULL,
  `sigla` varchar(50) DEFAULT NULL,
  `descricao` text,
  `ativo` int(11) NOT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `num` varchar(50) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `uf` varchar(255) DEFAULT NULL,
  `tel1` varchar(20) DEFAULT NULL,
  `tel2` varchar(20) DEFAULT NULL,
  `tel3` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_predio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `salas`;
CREATE TABLE `salas` (
  `id_sala` int(11) NOT NULL AUTO_INCREMENT,
  `n_sala` varchar(255) NOT NULL,
  `fk_id_predio` int(11) NOT NULL,
  `largura` varchar(255) NOT NULL,
  `comprimento` varchar(255) NOT NULL,
  `piso` int(11) NOT NULL,
  `cadeirante` int(11) NOT NULL,
  `fk_id_ts` int(11) NOT NULL,
  `tomadas` int(11) NOT NULL,
  `computadores` int(11) NOT NULL,
  `ar` int(11) NOT NULL,
  `datashow` int(11) NOT NULL,
  `cortinas` int(11) NOT NULL,
  `wifi` int(11) NOT NULL,
  `cabo_rede` int(11) NOT NULL,
  `alunos_sala` int(11) NOT NULL,
  `alunos_gdae` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_sala`),
  KEY `fk_id_predio` (`fk_id_predio`,`fk_id_ts`),
  KEY `fk_id_ts` (`fk_id_ts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tipo_sala`;
CREATE TABLE `tipo_sala` (
  `id_ts` int(11) NOT NULL AUTO_INCREMENT,
  `n_ts` varchar(255) NOT NULL,
  `sg_ts` varchar(20) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  PRIMARY KEY (`id_ts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_prof_esc`;
CREATE TABLE `ge_prof_esc` (
  `id_pe` int(11) NOT NULL AUTO_INCREMENT,
  `n_pe` varchar(255) DEFAULT NULL,
  `fk_id_inst` int(11) NOT NULL,
  `rm` varchar(50) NOT NULL,
  `disciplinas` varchar(255) DEFAULT NULL,
  `hac_dia` int(11) DEFAULT NULL,
  `hac_periodo` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nao_hac` int(11) DEFAULT NULL,
  `fk_id_psc` int(11) DEFAULT NULL,
  `times_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pe`),
  UNIQUE KEY `fk_id_inst` (`fk_id_inst`,`rm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_lista_branca_prof_esc`;
CREATE TABLE `sed_lista_branca_prof_esc` (
  `id_pessoa` int(11) NOT NULL,
  PRIMARY KEY (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_disciplinas`;
CREATE TABLE `ge_disciplinas` (
  `id_disc` int(11) NOT NULL AUTO_INCREMENT,
  `n_disc` varchar(255) NOT NULL,
  `sg_disc` varchar(10) NOT NULL,
  `fk_id_area` int(11) NOT NULL,
  `status_disc` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_disc`),
  KEY `fk_id_area` (`fk_id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_prof_sit_classe`;
CREATE TABLE `ge_prof_sit_classe` (
  `id_psc` int(11) NOT NULL AUTO_INCREMENT,
  `n_psc` varchar(100) NOT NULL,
  `ativo` int(11) NOT NULL,
  PRIMARY KEY (`id_psc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_grades`;
CREATE TABLE `ge_grades` (
  `id_grade` int(11) NOT NULL AUTO_INCREMENT,
  `n_grade` varchar(255) NOT NULL,
  `fk_id_ta` int(11) NOT NULL,
  `ativo` int(11) NOT NULL,
  PRIMARY KEY (`id_grade`),
  KEY `fk_id_ta` (`fk_id_ta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_turma_status`;
CREATE TABLE `ge_turma_status` (
  `id_ts` int(11) NOT NULL AUTO_INCREMENT,
  `n_st` varchar(100) NOT NULL,
  PRIMARY KEY (`id_ts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_aloca_prof`;
CREATE TABLE `ge_aloca_prof` (
  `fk_id_turma` int(11) NOT NULL,
  `iddisc` varchar(11) NOT NULL,
  `fk_id_inst` int(11) NOT NULL,
  `rm` varchar(255) NOT NULL,
  `prof2` int(11) NOT NULL DEFAULT '0',
  `suplementar` int(11) DEFAULT NULL,
  `cit` int(11) NOT NULL DEFAULT '0',
  `update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tmp` int(11) DEFAULT '0',
  PRIMARY KEY (`fk_id_turma`,`iddisc`,`prof2`),
  KEY `index2` (`rm`,`fk_id_inst`),
  KEY `IDX_rm` (`rm`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_aloca_disc`;
CREATE TABLE `ge_aloca_disc` (
  `id_aloca` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_grade` int(11) NOT NULL,
  `fk_id_disc` int(11) NOT NULL,
  `aulas` int(11) NOT NULL,
  `ordem` int(11) NOT NULL,
  `nucleo_comum` int(2) DEFAULT NULL,
  `fk_id_adb` int(11) NOT NULL,
  PRIMARY KEY (`id_aloca`),
  KEY `fk_id_grade` (`fk_id_grade`,`fk_id_disc`),
  KEY `fk_id_disc` (`fk_id_disc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_situacao_sed`;
CREATE TABLE `ge_situacao_sed` (
  `id_sit_sed` int(11) NOT NULL AUTO_INCREMENT,
  `sit_sed` varchar(100) NOT NULL,
  `sit_sieb` varchar(100) NOT NULL,
  `sit_agrupamento` int(10) NOT NULL,
  `sit_layout` varchar(50) NOT NULL,
  PRIMARY KEY (`id_sit_sed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_mural`;
CREATE TABLE `sed_mural` (
  `id_mural` int(11) NOT NULL AUTO_INCREMENT,
  `n_mural` varchar(255) NOT NULL,
  `fk_id_inst` int(11) DEFAULT NULL,
  `fk_id_turma` int(11) DEFAULT NULL,
  `dt_inicio` date DEFAULT NULL,
  `dt_fim` date DEFAULT NULL,
  `at_mural` int(11) NOT NULL DEFAULT '1',
  `msg` text NOT NULL,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_gr` int(11) DEFAULT NULL,
  `atualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mural`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_grupo`;
CREATE TABLE `sed_grupo` (
  `id_gr` int(11) NOT NULL AUTO_INCREMENT,
  `n_gr` varchar(255) DEFAULT NULL,
  `descr_gr` text,
  `fk_id_inst` int(11) NOT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `at_gr` int(11) NOT NULL DEFAULT '1',
  `cor` varchar(20) DEFAULT NULL,
  `atualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_gr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_encaminhamento`;
CREATE TABLE `ge_encaminhamento` (
  `id_encam` int(11) NOT NULL AUTO_INCREMENT,
  `escola_origem` int(11) NOT NULL,
  `escola_destino` int(11) NOT NULL,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `ciclo_futuro` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id_encam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_erro`;
CREATE TABLE `sed_erro` (
  `id_se` int(11) NOT NULL AUTO_INCREMENT,
  `erro` text NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_se`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_tp_ensino`;
CREATE TABLE `ge_tp_ensino` (
  `id_tp_ens` int(11) NOT NULL AUTO_INCREMENT,
  `n_tp_ens` varchar(255) NOT NULL,
  `sigla` varchar(20) NOT NULL,
  `sequencia` varchar(100) DEFAULT NULL,
  `at_seg` int(11) DEFAULT '1',
  PRIMARY KEY (`id_tp_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_curso_grade`;
CREATE TABLE `ge_curso_grade` (
  `id_cg` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_ciclo` int(11) NOT NULL,
  `fk_id_grade` int(11) NOT NULL,
  `padrao` int(11) NOT NULL,
  PRIMARY KEY (`id_cg`),
  UNIQUE KEY `fk_id_ciclo` (`fk_id_ciclo`,`fk_id_grade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_areas`;
CREATE TABLE `ge_areas` (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `n_area` varchar(255) NOT NULL,
  `sg_area` varchar(20) NOT NULL,
  PRIMARY KEY (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_tp_aval`;
CREATE TABLE `ge_tp_aval` (
  `id_ta` int(11) NOT NULL AUTO_INCREMENT,
  `n_ta` varchar(255) NOT NULL,
  PRIMARY KEY (`id_ta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ge_aloca_disc_base`;
CREATE TABLE `ge_aloca_disc_base` (
  `id_adb` int(11) NOT NULL AUTO_INCREMENT,
  `n_adb` varchar(100) NOT NULL,
  `descr_adb` varchar(255) DEFAULT NULL,
  `at_adb` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_adb`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_letiva_data`;
CREATE TABLE `sed_letiva_data` (
  `fk_id_curso` int(11) NOT NULL,
  `fk_id_pl` int(11) NOT NULL,
  `atual_letiva` int(11) NOT NULL,
  `dt_inicio` date NOT NULL,
  `dt_fim` date NOT NULL,
  `dias` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_id_curso`,`fk_id_pl`,`atual_letiva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_feriados`;
CREATE TABLE `sed_feriados` (
  `id_feriado` int(11) NOT NULL AUTO_INCREMENT,
  `n_feriado` varchar(255) DEFAULT NULL,
  `fk_id_curso` int(11) NOT NULL,
  `fk_id_pl` int(11) NOT NULL,
  `dt_feriado` date NOT NULL,
  PRIMARY KEY (`id_feriado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sed_carga_horaria_pl`;
CREATE TABLE `sed_carga_horaria_pl` (
  `id_chpl` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_ciclo` int(11) NOT NULL,
  `fk_id_pl` int(11) NOT NULL,
  `bncc` int(11) DEFAULT '0',
  `bd` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  PRIMARY KEY (`id_chpl`),
  UNIQUE KEY `fk_id_ciclo` (`fk_id_ciclo`,`fk_id_pl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ge2.ge_cursos MODIFY COLUMN fk_id_ta int NULL;
ALTER TABLE ge2.ge_cursos MODIFY COLUMN fk_id_calcaval int NULL;
ALTER TABLE ge2.ge_cursos MODIFY COLUMN TipoEnsino int NULL;
ALTER TABLE ge2.ge_ciclos MODIFY COLUMN aprova_automatico int NULL;
ALTER TABLE ge2.ge_ciclos MODIFY COLUMN periodicidade varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL;
ALTER TABLE ge2.ge_aloca_disc MODIFY COLUMN ordem int DEFAULT 0 NULL;

INSERT INTO ge_tp_aval (id_ta, n_ta) VALUES
   (1, 'Disciplinas'),
   (2, 'Habilidades');

INSERT INTO ge_aloca_disc_base (id_adb,n_adb,descr_adb,at_adb) VALUES
   (1,'BNCC','Base Nacional Comum Curricular',1),
   (2,'BD','Base Diversificada',1);


INSERT INTO ge_ciclos (fk_id_curso,n_ciclo,sg_ciclo,aprova_automatico,fk_id_grade,periodicidade,ativo,SerieAno,aulas,dias_semana) VALUES
   (1,'Pré Fase 1','1',1,1,'1',1,1,1,'1,2,3,4,5'),
   (1,'Pré Fase 2','2',1,1,'1',1,2,1,'1,2,3,4,5');

INSERT INTO ge2.pessoa (id_pessoa, n_pessoa, n_social, dt_nasc, email, ativo, cpf, cpf_old, sexo, ra, ra_dig, ra_uf, rg, rg_dig, rg_oe, rg_uf, dt_rg, fk_id_rt, certidao, sus, pai, cpf_pai, mae, cpf_mae, mae_rg, mae_rg_dig, mae_rg_oe, dt_mae_rg, pai_rg, pai_rg_dig, pai_rg_oe, dt_pai_rg, mae_rg_uf, pai_rg_uf, responsavel, dt_resp, cpf_respons, rg_respons, email_respons, nacionalidade, uf_nasc, cidade_nasc, deficiencia, cor_pele, tel1, ddd1, tel2, ddd2, tel3, ddd3, obs, novacert_cartorio, novacert_acervo, novacert_regcivil, novacert_ano, novacert_tipolivro, novacert_numlivro, novacert_folha, novacert_termo, novacert_controle, dt_gdae, at_google, nis, emailgoogle, google_user_id, duplicado_nome_data, inep, trabalho_pai, end_trab_pai, trabalho_mae, end_trab_mae, update_at, parentesco)
VALUES(1, 'CRISTIANO ARRUDA', NULL, '1986-07-27', 'crisarruda.silva@gmail.com', 1, '05330469988', NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'IVONETE ARRUDA DA SILVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'brasileiro', 'PR', 'Terra Rica', NULL, NULL, '981267738', 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'crisarruda.silva@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-02 16:05:17', NULL);


INSERT INTO ge2.users (id_user, user_password, fk_id_pessoa, user_session_id, ativo, expira, horas, google_id, google_token)
VALUES(1, '$2a$08$3Xe1WzYN6snvza1d98EujOkRKJNKvII6iaFrZAtePiYH7bdZyBY9i', 1, '8ptrf5hldsmkt0aji3a3kr6cr8', 1, '2023-03-01', '', '107750209957574070253', '230452642a003cdd6a5');

INSERT INTO ge2.grupo (id_gr, n_gr, at_gr)
VALUES (1, 'MASTER', 1), (26, 'Supervisor', 1), (61, 'Suporte Técnico', 1);


INSERT INTO instancia
VALUES (NULL, 'Administração', 0, 1,, NULL, 0, 1, 0);

INSERT INTO predio (n_predio,sigla,descricao,ativo,cep,logradouro,num,complemento,bairro,cidade,uf,tel1,tel2,tel3) VALUES
   ('Administração','Adm','',1,'00000-000','RUA A','1','','BAIRRO','CIDADE','UF','1111-2222',NULL,NULL);

INSERT INTO instancia_predio values (null, 1, 1, 1);

INSERT INTO ge2.ge_escolas (fk_id_inst,fk_id_tp_ens,classe,ato_cria,ato_municipa,vizualizar)
  VALUES (1,'1|2',1,'','',1);


INSERT INTO ge2.framework (id_fr, n_fr, end_fr, ativo)
VALUES(1, 'Autenticador', '/sie/', 1);

INSERT INTO ge2.sistema (id_sistema, arquivo, n_sistema, fk_id_fr, niveis, descr_sistema, ativo, fkid, msg, protegido)
VALUES(1, 'princ', 'Sistema Principal', 1, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', '', 1, 0, '', 1);
INSERT INTO ge2.sistema (id_sistema, arquivo, n_sistema, fk_id_fr, niveis, descr_sistema, ativo, fkid, msg, protegido)
VALUES(2, 'admin', 'Administrador do Sistema (novo)', 1, 'a:1:{i:0;s:1:"2";}', '', 1, 0, NULL, NULL);
INSERT INTO ge2.sistema (id_sistema, arquivo, n_sistema, fk_id_fr, niveis, descr_sistema, ativo, fkid, msg, protegido)
VALUES(3, 'tdics', 'Núcleos WIT', 1, 'a:7:{i:0;s:1:"2";i:1;s:2:"39";i:2;s:1:"8";i:3;s:2:"10";i:4;s:2:"57";i:5;s:2:"24";i:6;s:2:"43";}', '', 1, 0, NULL, NULL);
INSERT INTO ge2.sistema (id_sistema, arquivo, n_sistema, fk_id_fr, niveis, descr_sistema, ativo, fkid, msg, protegido)
VALUES(4, 'sed', 'Gestão Educacional', 1, 'a:6:{i:0;s:1:"2";i:1;s:1:"8";i:2;s:2:"10";i:3;s:2:"31";i:4;s:2:"43";i:5;s:2:"44";}', '', 1, 0, NULL, 0);


INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(1, 'Desenvolvedor', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(2, 'Administrador', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(4, 'Atendente                     ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(6, 'Gestão de Processos           ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(7, 'DTTIE                         ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(8, 'Escola', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(9, 'Relatório                     ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(10, 'Gerente                       ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(11, 'Secretaria de Educação', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(12, 'Usuário                       ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(13, 'Avaliação                     ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(14, 'Gestão de Pessoal', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(15, 'Contabilidade                 ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(16, 'Banca Corretora               ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(17, 'Diretor', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(18, 'Avaliação In Loco             ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(19, 'Avaliação In Loco S.          ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(20, 'Abrangência                   ', 1, 20);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(21, 'Fundamental                   ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(22, 'Supervisor                    ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(23, 'Infantil                      ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(24, 'Professor(a)', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(25, 'Secretaria de Escola', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(26, 'Coordenadoria', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(27, 'Maternal', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(28, 'Pré', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(29, 'Apoio Pedagógico', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(30, 'Aluno', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(31, 'Informações', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(32, 'Responsáveis', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(34, 'DAE', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(35, 'Organização Social', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(36, 'Equipe de Gestão', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(37, 'Consulta', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(38, 'Exportação de Dados', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(39, 'Call Center', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(40, 'Validação Avaliação Global', 1, 10);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(41, 'Terceirizado', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(43, 'Professor(a) Volante', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(44, 'Vida Escolar', 1, 10);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(45, 'Apoio Secretaria', 1, 45);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(46, 'Administrador Email Google', 1, 10);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(47, 'Telefonia', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(48, 'Coordenador', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(49, 'Suporte Técnico', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(50, 'Professor Infantil', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(51, 'Transporte', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(52, 'Professor Maker', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(53, 'Coordenadoria Infantil ', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(54, 'Coordenadoria Fundamental', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(55, 'Coordenador Fundamental', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(56, 'Coordenador Infantil', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(57, 'Núcleo TDICS', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(58, 'Saúde Bucal', 1, 0);
INSERT INTO ge2.nivel (id_nivel, n_nivel, ativo, fk_id_nivel)
VALUES(59, 'Lançamento de Dados', 1, 0);

INSERT INTO acesso_gr VALUES(NULL, 1, 1, 2);
INSERT INTO acesso_gr VALUES(NULL, 1, 2, 2);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 2);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 10);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 24);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 57);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 39);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 43);
INSERT INTO acesso_gr VALUES(NULL, 1, 3, 8);
INSERT INTO acesso_gr VALUES(NULL, 1, 4, 2);
INSERT INTO acesso_gr VALUES(NULL, 1, 4, 31);
INSERT INTO acesso_gr VALUES(NULL, 1, 4, 43);
INSERT INTO acesso_gr VALUES(NULL, 1, 4, 44);
INSERT INTO acesso_gr VALUES(NULL, 1, 4, 8);
INSERT INTO acesso_gr VALUES(NULL, 1, 4, 10);



INSERT INTO acesso_pessoa VALUES(NULL, 1, 1, 1);

INSERT INTO ge2.tdics_pl (id_pl, n_pl, ativo)
VALUES(2, '1º Semestre de 2023', 1);

INSERT INTO ge2.tdics_setup (id_setup, qt_turma, matri, matri_prev, qt_curso_aluno, fk_id_pl_certificado, dt_certif)
VALUES(1, 16, 0, 0, 1, 1, '2022-12-19');


ALTER TABLE ge2.tdics_polo MODIFY COLUMN id_polo int auto_increment NOT NULL;

SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';


INSERT INTO pagina (pagina,n_pag,descr_page,ord_pag,fk_id_sistema,posi_pag,`view`,ativo) VALUES
   ('princ/paginas','Gerenciar|>Páginas','',1,1,NULL,NULL,1),
   ('princ/menu','Gerenciar|>Menu','',2,1,NULL,NULL,1),
   ('princ/sistema','CADASTRO|>SUBSISTEMA','',3,1,NULL,NULL,1),
   ('princ/usuario','Gerenciar|>Usuários','',0,1,NULL,NULL,1),
   ('princ/usersis','Gerenciar|>Usuários/Sistema','',11,1,NULL,NULL,1),
   ('Dropdown/1','Gerenciar||','',0,1,NULL,NULL,1),
   ('princ/index','Início','',0,1,NULL,NULL,1),
   ('princ/temas','Gerenciar|>Temas','',12,1,NULL,NULL,1),
   ('princ/frame','CADASTRO|>FRAMEWORK','',1,1,NULL,NULL,1),
   ('Dropdown/2','CADASTRO||','',2,1,NULL,NULL,1);
INSERT INTO pagina (pagina,n_pag,descr_page,ord_pag,fk_id_sistema,posi_pag,`view`,ativo) VALUES
   ('adm/nivel','CADASTRO|>NÍVEIS','',2,1,NULL,NULL,1),
   ('princ/migra','Importar Dados','',14,1,NULL,NULL,1),
   ('princ/correcoes','CORREÇÕES','',20,1,NULL,NULL,1);

insert into sis_nivel_pag
select null, 1, 2, null, id_pag
from pagina where fk_id_sistema = 1;

INSERT INTO ge2.telefones_tipo (id_tt, n_tt)
VALUES(1, 'Celular');
INSERT INTO ge2.telefones_tipo (id_tt, n_tt)
VALUES(2, 'Residencial');
INSERT INTO ge2.telefones_tipo (id_tt, n_tt)
VALUES(3, 'Comercial');
INSERT INTO ge2.telefones_tipo (id_tt, n_tt)
VALUES(4, 'Recados');


/*
INSERT INTO `pessoa` (n_pessoa,n_social,dt_nasc,email,ativo,cpf,cpf_old,sexo,ra,ra_dig,ra_uf,rg,rg_dig,rg_oe,rg_uf,dt_rg,fk_id_rt,certidao,sus,pai,cpf_pai,mae,cpf_mae,mae_rg,mae_rg_dig,mae_rg_oe,dt_mae_rg,pai_rg,pai_rg_dig,pai_rg_oe,dt_pai_rg,mae_rg_uf,pai_rg_uf,responsavel,dt_resp,cpf_respons,rg_respons,email_respons,nacionalidade,uf_nasc,cidade_nasc,deficiencia,cor_pele,tel1,ddd1,tel2,ddd2,tel3,ddd3,obs,novacert_cartorio,novacert_acervo,novacert_regcivil,novacert_ano,novacert_tipolivro,novacert_numlivro,novacert_folha,novacert_termo,novacert_controle,dt_gdae,at_google,nis,emailgoogle,google_user_id,duplicado_nome_data,inep,trabalho_pai,end_trab_pai,trabalho_mae,end_trab_mae,update_at,parentesco) VALUES
   ('HADASSA ANTONELLI CONCEIÇÃO DE JESUS','','2011-05-19','DENISDEJESUS@UOL.COM',1,NULL,NULL,'F','124543045','2','SP','','',NULL,'',NULL,1,'','','DENIS SOUZA DE JESUS',NULL,'VANESSA DE JESUS CONCEICAO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','BA','CATU','','PARDA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','','','2023-04-22',0,'','1228152022',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-22 04:16:02',NULL),
   ('EDUARDO DE OLIVEIRA CARVALHO','','2022-10-30','',1,'60606587845',NULL,'M','124544973','4','SP','','',NULL,'',NULL,1,'','701401689574630','LEONARDO DE CARVALHO COSTA',NULL,'THAIS SANTOS OLIVEIRA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','BRANCA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115055','01','55','2022','1','638','167','430860','75','2023-04-20',0,'','1228152013',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 17:30:03',NULL),
   ('ANA SOFIE DA SILVA','','2022-07-15','',1,'60398711801',NULL,'F','124544962','X','SP','','',NULL,'',NULL,1,'','702608254568249','JOSE RAMOS DA SILVA',NULL,'MARIA APARECIDA DA SILVA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','PARDA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2022','1','308','205','183549','95','2023-04-20',0,'','1228152011',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 17:20:03',NULL),
   ('ESTER NUNES DA SILVA','','2014-05-03','',1,'57912216812',NULL,'F','120112383','5','SP','00000066716592','7',NULL,'SP',NULL,1,'','708201131884548','JOSE TALLES ISIDIO DA SILVA',NULL,'JUCELI NUNES DE SOUSA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','OSASCO','','BRANCA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'118059','01','55','2014','1','54','93','28126','12','2023-04-20',0,'','1228152002',NULL,NULL,'179279500960',NULL,NULL,NULL,NULL,'2023-04-20 15:52:02',NULL),
   ('NOAH MARCELO DE ARAUJO SOUZA','','2018-07-07','',1,'57062789895',NULL,'M','123494116','8','SP','65775813','9',NULL,NULL,NULL,1,'','898005955299353','MARCELO APARECIDO ALVES DE SOUZA',NULL,'ANA CRISTINA DE ARAUJO SOUZA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Estrangeira','','','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','','','2023-04-20',0,'','1228151994',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 15:34:17',NULL),
   ('AMANDA KESSIA ALMEIDA FEITOSA','','2014-08-20','',1,'62945418320',NULL,'F','124544566','2','SP','64892352018','6',NULL,'CE',NULL,1,'','706406689044289','ALEXANDRE PEREIRA FEITOSA',NULL,'MARIA ARIELLE MARTINS DE ALMEIDA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','CE','PIQUET CARNEIRO','','PARDA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'19737','01','55','2014','1','15','149','349','15','2023-04-20',0,'','1228151990',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 15:12:02',NULL),
   ('ESTER CORDEIRO DA SILVA','','2021-06-16','',1,'59432648842',NULL,'F','124544568','6','SP','','',NULL,'',NULL,1,'','','CLOVES CORDEIRO DA SILVA FILHO',NULL,'SHEILA ALVES DA SILVA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115063','01','55','2021','1','233','26','139754','37','2023-04-20',0,'','1228151989',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 15:02:02',NULL),
   ('REBECCA MOMBELE LONGUES RODRIGUES','','2018-05-20','',1,'55106134838',NULL,'F','122223920','6','SP','','',NULL,'',NULL,1,'','','RENATO DA SILVA CONCEICAO RODRIGUES',NULL,'DEBORA CAROLINE CONCEICAO RODRIGUES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','SAO PAULO','','BRANCA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'111286','01','55','2018','1','620','61','170246','56','2023-04-20',0,'','1228151975',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 14:02:02',NULL),
   ('AGATHA FERREIRA DA SILVA','','2015-03-02','',1,NULL,NULL,'F','124544291','0','SP','','',NULL,'',NULL,1,'','898004608346824','NAIROLDO FERREIRA DE SOUZA',NULL,'LUCINEIA CIRILO DA SILVA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2015','1','257','77','152796','76','2023-04-20',0,'','1228151973',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 13:32:03',NULL),
   ('PEDRO ROCHA FRADE','','2013-03-31','',1,'57094813864',NULL,'M','114927830','4','SP','00000060231500','1',NULL,'SP',NULL,1,'','702307155365112','EDER DE SOUZA SANTOS FRADE',NULL,'CAMILA ROCHA SANTIAGO PEREIRA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','SAO PAULO','','BRANCA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'113225','01','55','2013','1','84','178','61449','51','2023-04-20',0,'','1228151972',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 13:06:03',NULL);
INSERT INTO `pessoa` (n_pessoa,n_social,dt_nasc,email,ativo,cpf,cpf_old,sexo,ra,ra_dig,ra_uf,rg,rg_dig,rg_oe,rg_uf,dt_rg,fk_id_rt,certidao,sus,pai,cpf_pai,mae,cpf_mae,mae_rg,mae_rg_dig,mae_rg_oe,dt_mae_rg,pai_rg,pai_rg_dig,pai_rg_oe,dt_pai_rg,mae_rg_uf,pai_rg_uf,responsavel,dt_resp,cpf_respons,rg_respons,email_respons,nacionalidade,uf_nasc,cidade_nasc,deficiencia,cor_pele,tel1,ddd1,tel2,ddd2,tel3,ddd3,obs,novacert_cartorio,novacert_acervo,novacert_regcivil,novacert_ano,novacert_tipolivro,novacert_numlivro,novacert_folha,novacert_termo,novacert_controle,dt_gdae,at_google,nis,emailgoogle,google_user_id,duplicado_nome_data,inep,trabalho_pai,end_trab_pai,trabalho_mae,end_trab_mae,update_at,parentesco) VALUES
   ('MARIA FLOR ROCHA FRADE','','2016-02-09',NULL,1,'57094688865',NULL,'F','120435270','7','SP','00000064875230','6',NULL,'SP',NULL,1,'','898005118430366','EDER DE SOUZA SANTOS FRADE',NULL,'CAMILA ROCHA SANTIAGO PEREIRA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','PRAIA GRANDE','','PARDA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'122440','01','55','2016','1','129','251','81507','20','2023-04-20',0,'','1228151969',NULL,NULL,'179355036301',NULL,NULL,NULL,NULL,'2023-04-20 12:59:53',NULL),
   ('DEREK MOMBELE LONGUES RODRIGUES','','2020-04-09','',1,NULL,NULL,'M','122515922','2','SP',NULL,NULL,NULL,NULL,NULL,1,'',NULL,'RENATO DA SILVA CONCEICAO RODRIGUES',NULL,'DEBORA CAROLINE RODRIGUES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','SAO PAULO','','BRANCA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'111286','01','55','2020','1','649','204','187873','50','2023-04-20',0,'','1228151968',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 13:20:08',NULL),
   ('EMILLY SOPHIA ANDRADE DE SOUZA','','2021-08-08','',1,'59551994892',NULL,'F','124544109','7','SP','','',NULL,'',NULL,1,'','898006251093562','MARCELO DOS SANTOS DE ANDRADE',NULL,'GABRIELA ANDRADE DE SOUZA MARCELINO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2021','1','303','46','180241','45','2023-04-20',0,'','1228151967',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 12:14:03',NULL),
   ('ASAFE FERREIRA SILVARES','','2021-12-23','',1,'59899951803',NULL,'M','124544076','7','SP',NULL,NULL,NULL,NULL,NULL,1,'','708909726674710','GABRIEL MOREIRA SILVARES',NULL,'LUIZA FERREIRA SILVARES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','CARAPICUIBA','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115568','01','55','2021','1','391','203','233659','46','2023-04-20',0,'','1228151966',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 11:38:15',NULL),
   ('SARAH VITORIA BESERRA DA SILVA NUNES','','2022-04-08','GILDANUNESDASILVA70@GMAIL.COM',1,'60269029885',NULL,'F','124543726','4','SP','','',NULL,'',NULL,1,'','706406176271185','JOEL BESERRA DA SILVA',NULL,'GILDA NUNES DA SILVA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2022','1','307','194','182930','51','2023-04-20',0,'','1228151955',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 10:02:02',NULL),
   ('KAUA CARDOSO DE JESUS','','2021-09-23','',1,'59713825861',NULL,'M','124543732','X','SP','','',NULL,'',NULL,1,'','702306594787320','SINEVALDO CARDOSO DE SOUZA FILHO',NULL,'GRACILANE DE JESUS BATATEIRA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2021','1','304','17','180782','11','2023-04-20',0,'','1228151954',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 10:07:45',NULL),
   ('HELLENA RAMOS CORREA GOMES','','2022-02-28','',1,'60097260851',NULL,'F','124543301','5','SP','','',NULL,'',NULL,1,'','704807013208647','VITOR GOMES DA SILVA',NULL,'JAQUELINE DE FATIMA RAMOS CORREA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2022','1','306','114','182172','31','2023-04-20',0,'','1228151938',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-20 12:50:33',NULL),
   ('HENRY BARBOSA URTEGO','','2020-04-07','',1,'58427658885',NULL,'M','124543246','1','SP','','',NULL,'',NULL,1,'','','JOÃO VITOR GOMES SILVA URTEGO',NULL,'STEPHANE BARBOSA DA SILVA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2020','1','294','189','175146','25','2023-04-19',0,'','1228151931',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-19 17:32:03',NULL),
   ('ENZO LIMA SIQUEIRA','','2021-09-03','',1,'59625535861',NULL,'M','124543178','X','SP','','',NULL,'',NULL,1,'','700502342909152','JEANES RIBEIRO DE SIQUEIRA',NULL,'SANDRELÚCIA RIBEIRO LIMA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','OSASCO','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115238','01','55','2021','1','547','151','326809','56','2023-04-19',0,'','1228151925',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-19 16:46:03',NULL),
   ('HELOA DE FREITAS ARAUJO','','2022-04-04','',1,'60190578874',NULL,'F','124543107','9','SP','','',NULL,'',NULL,1,'','','MIGUEL FELIPE DE ARAUJO NETO',NULL,'YASMIN FREITAS BARBOSA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Brasileira','SP','BARUERI','','NÃO DECLARADA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'115840','01','55','2022','1','307','14','182570','11','2023-04-19',0,'','1228151922',NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-04-19 16:22:03',NULL);

  INSERT INTO `ge_turma_aluno` (codigo_classe,fk_id_turma,periodo_letivo,fk_id_pessoa,fk_id_inst,chamada,situacao,dt_matricula,dt_matricula_fim,dt_transferencia,origem_escola,destino_escola,destino_escola_cidade,destino_escola_uf,tp_destino,justificativa_transf,turma_status,situacao_final,conselho,dt_gdae,gdae,fk_id_sit_sed,fk_id_ciclo_aluno,fk_id_tas,fk_id_sf) VALUES
   ('EFT7A ',1,'2023',3,1,33,'Frequente','2023-04-22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-22',NULL,0,NULL,0,0),
   ('EBI0A',1,'2023',4,1,11,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EBI0E',1,'2023',5,1,15,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EFM4A',1,'2023',6,1,32,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EIT1A',1,'2023',7,1,26,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EFT3D',1,'2023',8,1,23,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EMI1C',1,'2023',9,1,21,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EIT1A',1,'2023',10,1,25,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EFM3B',1,'2023',11,1,31,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EFM5A',1,'2023',12,1,22,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0);
   INSERT INTO `ge_turma_aluno` (codigo_classe,fk_id_turma,periodo_letivo,fk_id_pessoa,fk_id_inst,chamada,situacao,dt_matricula,dt_matricula_fim,dt_transferencia,origem_escola,destino_escola,destino_escola_cidade,destino_escola_uf,tp_destino,justificativa_transf,turma_status,situacao_final,conselho,dt_gdae,gdae,fk_id_sit_sed,fk_id_ciclo_aluno,fk_id_tas,fk_id_sf) VALUES
   ('EFT2B',41901,'2023',13,1,27,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EMI2A',43182,'2023',14,1,37,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EMI1B',41625,'2023',15,1,8,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EMI1B',41059,'2023',16,1,1,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EBI0A',40835,'2023',17,1,9,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EMI1B',40810,'2023',18,1,18,'Frequente','2023-04-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-20',NULL,0,NULL,0,0),
   ('EMI1A',41058,'2023',19,1,2,'Frequente','2023-04-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-19',NULL,0,NULL,0,0),
   ('EMI2A',40846,'2023',20,1,3,'Frequente','2023-04-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-19',NULL,0,NULL,0,0),
   ('EMI1C',40772,'2023',21,1,4,'Frequente','2023-04-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-19',NULL,0,NULL,0,0),
   ('EBI0A',40840,'2023',22,1,5,'Frequente','2023-04-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-19',NULL,0,NULL,0,0);

*/


INSERT INTO ge_cursos (id_curso,n_curso,sg_curso,descr_curso,fk_id_tp_ens,fk_id_ta,fk_id_calcaval,notas,notas_legenda,corte,ativo,un_letiva,sg_letiva,qt_letiva,atual_letiva,conceito_final,extra,TipoEnsino) VALUES
   (1,'Ensino Fundamental','EF','Programa Padrão Ensino Fundamental',4,1,1,'0;0,5;1;1,5;2;2,5;3;3,5;4;4,5;5;5,5;6;6,5;7;7,5;8;8,5;9;9,5;10;D;d','','4,5',1,'Bimestre','B',4,'1',NULL,0,14),
   (3,'Pré-Escola','EI','Programa Padrão Pré-Escola',3,1,1,'','','',1,'Bimestre','B',4,'1',NULL,0,6),
   (5,'EJA 1º Segmento','EJAI','Educação de Jovens e Adultos',5,1,1,'1;2;3;4;5;6;7;8;9;10;D;d','','4',1,'Bimestre','B',4,'1',NULL,0,3),
   (7,'Berçário','EB','',3,1,2,'','','',1,'Bimestre','B',4,'1',NULL,0,6),
   (8,'Maternal','EM','',3,1,2,'','','',1,'Bimestre','B',4,'1',NULL,0,6),
   (9,' EJA 2º Segmento','EJAII','Educação de Jovens e Adultos',5,1,1,'1;2;3;4;5;6;7;8;9;10;D;d','','4',1,'Bimestre','B',2,'1',NULL,0,4),
   (10,'AEE','AEE','',6,1,2,'','','',1,'Bimestre','B',4,'1',NULL,1,32);
  
INSERT INTO ge_ciclos (id_ciclo,fk_id_curso,n_ciclo,sg_ciclo,aprova_automatico,fk_id_grade,periodicidade,ativo,SerieAno,aulas,dias_semana) VALUES
   (1,1,'1º Ano','1',1,4,'1',1,1,5,'1,2,3,4,5'),
   (2,1,'2º Ano','2',0,4,'1',1,2,5,'1,2,3,4,5'),
   (3,1,'3º Ano','3',0,6,'1',1,3,5,'1,2,3,4,5'),
   (4,1,'4º Ano','4',0,6,'1',1,4,5,'1,2,3,4,5'),
   (5,1,'5º Ano','5',0,6,'1',1,5,5,'1,2,3,4,5'),
   (6,1,'6º Ano','6',0,1,'1',1,6,5,'1,2,3,4,5'),
   (7,1,'7º Ano','7',0,1,'1',1,7,5,'1,2,3,4,5'),
   (8,1,'8º Ano','8',0,1,'1',1,8,5,'1,2,3,4,5'),
   (9,1,'9º Ano','9',0,1,'1',1,9,5,'1,2,3,4,5'),
   (19,3,'Pré Fase 1','1',1,12,'1',1,1,1,'1,2,3,4,5');
INSERT INTO ge_ciclos (id_ciclo,fk_id_curso,n_ciclo,sg_ciclo,aprova_automatico,fk_id_grade,periodicidade,ativo,SerieAno,aulas,dias_semana) VALUES
   (20,3,'Pré Fase 2','2',1,12,'1',1,2,1,'1,2,3,4,5'),
   (21,7,'Berçário','0',1,12,'1',1,4,1,'1,2,3,4,5'),
   (22,8,'Maternal Fase 1','1',1,12,'1',1,5,1,'1,2,3,4,5'),
   (23,8,'Maternal Fase 2','2',1,12,'1',1,6,1,'1,2,3,4,5'),
   (24,8,'Maternal Fase 3','3',1,12,'1',1,7,1,'1,2,3,4,5'),
   (25,5,'Termo 1','1',1,9,'2',1,9,5,'1,2,3,4,5'),
   (26,5,'Termo 2','2',1,9,'2',1,10,5,'1,2,3,4,5'),
   (27,9,'Termo 1','1',1,10,'2',1,9,5,'1,2,3,4,5'),
   (28,9,'Termo 2','2',1,10,'2',1,10,5,'1,2,3,4,5'),
   (29,9,'Termo 3','3',1,10,'2',1,11,5,'1,2,3,4,5');
INSERT INTO ge_ciclos (id_ciclo,fk_id_curso,n_ciclo,sg_ciclo,aprova_automatico,fk_id_grade,periodicidade,ativo,SerieAno,aulas,dias_semana) VALUES
   (30,9,'Termo 4','4',1,10,'2',1,12,5,'1,2,3,4,5'),
   (31,1,'Multisseriada','M',0,7,'1',1,0,5,'1,2,3,4,5'),
   (32,10,'AEE','U',1,13,'1',1,0,5,'1,2,3,4,5'),
   (34,9,'Termo 4 Diurno','4X',0,1,'2',1,112,5,'1,2,3,4,5'),
   (35,5,'Multisseriada - EJA','M',1,9,'2',1,0,5,'1,2,3,4,5'),
   (36,5,'Multisseriada - Eja - TMP','M',1,9,'2',1,0,5,'1,2,3,4,5'),
   (37,5,'Multisseriada - Eja Tarde','T',1,9,'2',1,0,5,'1,2,3,4,5');

INSERT INTO ge_tp_ensino (id_tp_ens,n_tp_ens,sigla,sequencia,at_seg) VALUES
   (3,'Ensino Infantil','EI',NULL,1),
   (4,'Ensino Fundamental','EF',NULL,1),
   (5,'EJA','EJ',NULL,1),
   (6,'Extracurricular','EXT',NULL,1);

INSERT INTO ge_curso_grade (id_cg,fk_id_ciclo,fk_id_grade,padrao) VALUES
   (14,6,1,1),
   (16,7,1,1),
   (18,8,1,1),
   (23,9,1,1),
   (25,10,2,1),
   (26,11,2,1),
   (27,12,2,1),
   (28,14,2,1),
   (31,25,9,1),
   (41,26,9,1);
INSERT INTO ge_curso_grade (id_cg,fk_id_ciclo,fk_id_grade,padrao) VALUES
   (42,27,10,1),
   (43,28,10,1),
   (44,29,10,1),
   (45,30,10,1),
   (46,1,4,1),
   (47,2,4,1),
   (48,3,6,1),
   (49,4,6,1),
   (50,5,6,1),
   (51,31,7,1);
INSERT INTO ge_curso_grade (id_cg,fk_id_ciclo,fk_id_grade,padrao) VALUES
   (52,31,8,0),
   (54,21,12,1),
   (55,24,12,1),
   (56,23,12,1),
   (57,22,12,1),
   (58,19,12,1),
   (59,20,12,1),
   (60,32,13,1),
   (61,33,1,1),
   (62,34,1,1);
  
INSERT INTO ge_areas (id_area, n_area,sg_area) VALUES
   (1,'Ciências Humanas','CH');
   (2, 'Ciências da Natureza','CNT'),
   (3, 'Linguagens','LCT'),
   (4, 'Matemática','MT'),
   (9, 'Parte Diversificada','PD'),
   (10, 'Complementar Integral','CI'),
   (11, 'Infantil','I');

INSERT INTO ge_disciplinas (id_disc,n_disc,sg_disc,fk_id_area,status_disc) VALUES
   (1,'genérica','',0,1),
   (6,'Matemática','Mat',4,1),
   (9,'Língua Portuguesa','Port',3,1),
   (10,'Arte','Arte',3,1),
   (11,'Educação Física','EdFis',3,1),
   (12,'Ciências Naturais','Cien',2,1),
   (13,'História','Hist',1,1),
   (14,'Geografia','Geo',1,1),
   (15,'L.E.Inglês','Ing',9,1),
   (16,'I.Filosofia','Filo',9,1);
INSERT INTO ge_disciplinas (id_disc,n_disc,sg_disc,fk_id_area,status_disc) VALUES
   (17,'Música','Mus',9,1),
   (18,'Ativ. Musicais','AtM',10,0),
   (19,'Ativ. Exp. Corporal','AEC',10,0),
   (20,'Ativ. Informática','AtInf',10,1),
   (21,'Educ. Reflexão','ER',10,0),
   (22,'L.E.Espanhol','Esp',10,0),
   (23,'Orient. Estudos','OE',10,0),
   (24,'Orient. Leitura e Escrita','OLE',10,0),
   (25,'Orient. Práticas Operatórias','OPO',10,0),
   (26,'Libras','Lib',9,1);
INSERT INTO ge_disciplinas (id_disc,n_disc,sg_disc,fk_id_area,status_disc) VALUES
   (27,'Infantil','Inf',11,1),
   (28,'AEE','AEE',9,1),
   (29,'Produção Textual','PT',3,0),
   (30,'Língua Inglesa','LI',3,1),
   (49,'Infantil -  PDI','PDI',11,1);
