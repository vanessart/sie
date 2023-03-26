CREATE TABLE `form` (
  `id_form` int(11) NOT NULL AUTO_INCREMENT,
  `n_form` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_form`)
);
CREATE TABLE `form_opcoes` (
  `id_opcao` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pergunta` int(11) DEFAULT NULL,
  `n_opcao` varchar(100) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT NULL,
  `fk_id_pai` int(11) DEFAULT NULL,
  `acao` varchar(200) DEFAULT NULL,
  `tem_resposta` tinyint(4) DEFAULT NULL,
  `ordem` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id_opcao`)
);
CREATE TABLE `form_perguntas` (
  `id_pergunta` int(11) NOT NULL AUTO_INCREMENT,
  `n_pergunta` varchar(100) DEFAULT NULL,
  `fk_id_form` int(11) DEFAULT NULL,
  `ordem` tinyint(4) DEFAULT NULL,
  `tem_resposta` tinyint(4) DEFAULT NULL,
  `fk_id_pai` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pergunta`)
);
CREATE TABLE `form_pessoa` (
  `id_form_pessoa` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_form` int(11) DEFAULT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `respondido` int(11) DEFAULT NULL,
  `dt_update` datetime DEFAULT CURRENT_TIMESTAMP,
  `fk_id_pessoa_responde` int(11) DEFAULT NULL,
  `fk_id_campanha` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_form_pessoa`)
);
CREATE TABLE `form_resposta` (
  `id_resposta` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_opcao` int(11) DEFAULT NULL,
  `n_resposta` varchar(250) DEFAULT NULL,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `dt_update` datetime DEFAULT CURRENT_TIMESTAMP,
  `fk_id_form` int(11) DEFAULT NULL,
  `fk_id_pessoa_responde` int(11) DEFAULT NULL,
  `fk_id_campanha` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_resposta`)
);
ALTER TABLE `ge2`.`form_pessoa` 
ADD COLUMN `fk_id_pl` INT NULL AFTER `fk_id_campanha`;
CREATE TABLE `pse_campanha` (
  `id_campanha` int(11) NOT NULL AUTO_INCREMENT,
  `dt_update` datetime DEFAULT CURRENT_TIMESTAMP,
  `fk_id_pessoa` int(11) DEFAULT NULL,
  `ano` tinyint(4) DEFAULT NULL,
  `at_campanha` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id_campanha`)
);
ALTER TABLE `ge2`.`pse_campanha` 
CHANGE COLUMN `ano` `fk_id_pl` INT NULL DEFAULT NULL ;
INSERT INTO `ge2`.`form` (`n_form`) VALUES ('Autorização PSE');
ALTER TABLE `ge2`.`form_resposta` 
CHANGE COLUMN `fk_id_campanha` `fk_id_pl` INT(11) NULL DEFAULT NULL ;
INSERT INTO `ge2`.`form_perguntas` (`id_pergunta`, `n_pergunta`, `fk_id_form`, `ordem`, `tem_resposta`, `fk_id_pai`) VALUES (NULL, 'Seu filho(a) possui alguma doença? ', '1', '1', '1', NULL);
INSERT INTO `ge2`.`form_perguntas` (`n_pergunta`, `fk_id_form`, `ordem`, `tem_resposta`) VALUES ('Seu filho(a) faz uso regular de alguma medicação?', '1', '2', '1');
INSERT INTO `ge2`.`form_perguntas` (`n_pergunta`, `fk_id_form`, `ordem`, `tem_resposta`) VALUES ('Seu filho(a) possui alguma deficiência?', '1', '3', '1');
INSERT INTO `ge2`.`form_perguntas` (`n_pergunta`, `fk_id_form`, `ordem`, `tem_resposta`) VALUES ('Seu filho (a) tem alguma restrição de participar das atividades físicas?', '1', '4', '1');
INSERT INTO `ge2`.`form_perguntas` (`n_pergunta`, `fk_id_form`, `ordem`, `tem_resposta`) VALUES ('Tem alguma restrição alimentar? ', '1', '5', '1');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES (NULL, '1', 'Sim', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES (NULL, '1', 'Não', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES (NULL, '1', 'Qual?', '0', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('2', 'Sim', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('2', 'Não', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('2', 'Qual?', '0', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('4', 'Sim', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('4', 'Não', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('4', 'Qual?', '0', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('5', 'Sim', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('5', 'Não', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES ('5', 'Qual?', '0', '');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES (NULL, '4', 'Sim', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES (NULL, '4', 'Não', '2', '');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES (NULL, '4', 'Auditiva', '1', '');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`, `acao`) VALUES (NULL, '4', 'Visual', '1', '');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `fk_id_pergunta`, `n_opcao`, `tipo`) VALUES (NULL, '4', 'Motora/Física', '1');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`) VALUES ('4', 'Intelectual', '1');
INSERT INTO `ge2`.`form_opcoes` (`fk_id_pergunta`, `n_opcao`, `tipo`) VALUES ('4', 'Outros', '0');
UPDATE `ge2`.`form_opcoes` SET `fk_id_pergunta` = '3' WHERE (`id_opcao` = '13');
UPDATE `ge2`.`form_opcoes` SET `fk_id_pergunta` = '3' WHERE (`id_opcao` = '14');
UPDATE `ge2`.`form_opcoes` SET `fk_id_pergunta` = '3' WHERE (`id_opcao` = '15');
UPDATE `ge2`.`form_opcoes` SET `fk_id_pergunta` = '3' WHERE (`id_opcao` = '16');
UPDATE `ge2`.`form_opcoes` SET `fk_id_pergunta` = '3' WHERE (`id_opcao` = '17');
UPDATE `ge2`.`form_opcoes` SET `fk_id_pergunta` = '3' WHERE (`id_opcao` = '18');
UPDATE `ge2`.`form_opcoes` SET `fk_id_pergunta` = '3' WHERE (`id_opcao` = '19');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = 'Seu filh{oa} possui alguma doença? ' WHERE (`id_pergunta` = '1');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = '{seu} filh{oa} possui alguma doença? ' WHERE (`id_pergunta` = '1');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = 'Seu filh{oa} faz uso regular de alguma medicação?' WHERE (`id_pergunta` = '2');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = 'Seu  filh{oa} possui alguma deficiência?' WHERE (`id_pergunta` = '3');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = 'Seu  filh{oa} tem alguma restrição de participar das atividades físicas?' WHERE (`id_pergunta` = '4');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = '{seu} filh{oa} faz uso regular de alguma medicação?' WHERE (`id_pergunta` = '2');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = '{seu}  filh{oa} possui alguma deficiência?' WHERE (`id_pergunta` = '3');
UPDATE `ge2`.`form_perguntas` SET `n_pergunta` = '{seu}  filh{oa} tem alguma restrição de participar das atividades físicas?' WHERE (`id_pergunta` = '4');
INSERT INTO `ge2`.`form_opcoes` (`n_opcao`, `tipo`) VALUES ('RG', '-1');
INSERT INTO `ge2`.`form_opcoes` (`id_opcao`, `n_opcao`, `tipo`) VALUES (NULL, 'Nome Pai', '-1');
UPDATE `ge2`.`form_opcoes` SET `n_opcao` = 'RG Pai' WHERE (`id_opcao` = '20');
UPDATE `ge2`.`form_opcoes` SET `n_opcao` = 'rg' WHERE (`id_opcao` = '20');
UPDATE `ge2`.`form_opcoes` SET `n_opcao` = 'nome_responsavel' WHERE (`id_opcao` = '21');
UPDATE `ge2`.`form_opcoes` SET `n_opcao` = 'Outros:' WHERE (`id_opcao` = '19');
ALTER TABLE `ge2`.`form_pessoa` 
ADD COLUMN `fk_id_assinatura` INT NULL AFTER `fk_id_pl`;
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required onclick=\"desabilitar3()\"' WHERE (`id_opcao` = '14');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '13');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '1');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '2');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '4');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '5');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '7');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '8');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '10');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '11');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '20');
UPDATE `ge2`.`form_opcoes` SET `acao` = 'required' WHERE (`id_opcao` = '21');

