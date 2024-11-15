select * from sistema;
select * from acesso_gr ag where fk_id_sistema = 3;

insert into acesso_gr 
select null, fk_id_gr, 5, fk_id_nivel 
from acesso_gr ag where fk_id_sistema = 3;

select * from tdics_curso

alter table tdics_curso
 add column fk_id_sistema int not null default 3 after id_curso;

select * from information_schema.tables t where t.TABLE_NAME like 'tdics_%';


CREATE TABLE `wit_avise_me` (
  `id_avise` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `dt_avise` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_avise`),
  UNIQUE KEY `fk_id_pessoa_2` (`fk_id_pessoa`,`fk_id_turma`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;


CREATE TABLE `wit_curso` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `n_curso` varchar(255) NOT NULL,
  `abrev` varchar(2) DEFAULT NULL,
  `icone` varchar(20) DEFAULT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `descricao` text,
  PRIMARY KEY (`id_curso`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `wit_horarios` (
  `id_horarios` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_polo` int(11) DEFAULT NULL,
  `periodo` varchar(2) NOT NULL,
  `horario` tinyint(4) NOT NULL,
  `inicio` varchar(5) NOT NULL,
  `termino` varchar(5) NOT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `dt_horarios` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_horarios`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `wit_inscricao` (
  `id_inscricao` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `dt_inscricao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_inscricao`),
  KEY `fk_id_turma` (`fk_id_turma`),
  KEY `fk_id_pessoa` (`fk_id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `wit_pl` (
  `id_pl` int(11) NOT NULL AUTO_INCREMENT,
  `n_pl` varchar(100) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pl`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `wit_polo` (
  `id_polo` int(11) NOT NULL AUTO_INCREMENT,
  `n_polo` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_polo`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `wit_setup` (
  `id_setup` int(11) NOT NULL AUTO_INCREMENT,
  `qt_turma` int(11) DEFAULT NULL,
  `matri` int(11) DEFAULT NULL,
  `matri_prev` int(11) DEFAULT NULL,
  `qt_curso_aluno` int(11) DEFAULT '1',
  `fk_id_pl_certificado` int(11) DEFAULT NULL,
  `dt_certif` date DEFAULT NULL,
  PRIMARY KEY (`id_setup`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `wit_turma` (
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
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `wit_turma_aluno` (
  `id_ta` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(11) NOT NULL,
  `fk_id_turma` int(11) NOT NULL,
  `times_tamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ta`),
  UNIQUE KEY `fk_id_pessoa` (`fk_id_pessoa`,`fk_id_turma`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;


INSERT INTO wit_pl (n_pl,ativo) VALUES
   ('2º Semestre de 2024',1);

INSERT INTO wit_setup (qt_turma,matri,matri_prev,qt_curso_aluno,fk_id_pl_certificado,dt_certif) VALUES
   (30,1,1,1,1,'2024-12-19');

INSERT INTO wit_polo (n_polo,ativo) VALUES
   ('Wit Gaspar',1);

INSERT INTO wit_horarios (fk_id_polo,periodo,horario,inicio,termino,ativo,dt_horarios) VALUES
   (1,'M',1,'07h30','09h00',1,'2024-11-15 00:09:31'),
   (1,'M',2,'9h30','11h00',1,'2024-11-15 00:09:31'),
   (1,'T',1,'13h20','14h50',1,'2024-11-15 00:09:31'),
   (1,'T',2,'15h10','16h40',1,'2024-11-15 00:09:31');

INSERT INTO wit_curso (n_curso,abrev,icone,ativo,descricao) VALUES
   ('WIT','WT',NULL,1,NULL);

