CREATE TABLE `tdics_aval_group` (
  `id_ag` int(11) NOT NULL AUTO_INCREMENT,
  `n_ag` varchar(255) NOT NULL,
  `at_ag` int(11) NOT NULL DEFAULT 1,
  `fk_id_pl` int(11) NOT NULL,
  `dt_ag` date DEFAULT NULL,
  PRIMARY KEY (`id_ag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tdics_aval` (
  `id_aval` int(11) NOT NULL AUTO_INCREMENT,
  `n_aval` varchar(500) NOT NULL,
  `descri` text DEFAULT NULL,
  `fk_id_ag` int(11) NOT NULL,
  `fk_id_curso` varchar(255) NOT NULL,
  `fk_id_pl` int(11) NOT NULL,
  PRIMARY KEY (`id_aval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tdics_aval_quest` (
  `id_quest` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_aval` int(11) NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT 0,
  `n_quest` text NOT NULL,
  `momento` varchar(255) DEFAULT NULL,
  `resp_1` varchar(1000) DEFAULT NULL,
  `resp_2` varchar(1000) DEFAULT NULL,
  `resp_3` varchar(1000) DEFAULT NULL,
  `resp_4` varchar(1000) DEFAULT NULL,
  `resp_5` varchar(1000) DEFAULT NULL,
  `valor_1` int(11) DEFAULT 0,
  `valor_2` int(11) DEFAULT 0,
  `valor_3` int(11) DEFAULT 0,
  `valor_4` int(11) DEFAULT 0,
  `valor_5` int(11) DEFAULT 0,
  PRIMARY KEY (`id_quest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tdics_aval_resp` (
  `id_ar` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_aval` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `respostas` text DEFAULT NULL,
  `nota` decimal(4,2) NOT NULL DEFAULT 0.00,
  `fk_id_pessoa_prof` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
