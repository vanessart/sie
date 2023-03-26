CREATE TABLE `ge2`.`pse_atend_odonto` (
  `id_atend_odonto` INT NOT NULL AUTO_INCREMENT,
  `id_pessoa` INT NULL,
  `id_pessoa_cadastra` INT NULL,
  `dt_update` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dt_avaliacao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `orientacao_odonto` TINYINT(1) NULL,
  `avalia_odonto` TINYINT(1) NULL,
  `necssita_tratamento` TINYINT(1) NULL,
  `realizou_tratamento` TINYINT(1) NULL,
  PRIMARY KEY (`id_atend_odonto`));

ALTER TABLE `ge2`.`pse_atend_odonto` 
ADD COLUMN `fk_id_pl` INT NULL AFTER `fk_id_pessoa_cadastra`,
CHANGE COLUMN `dt_avaliacao` `dt_avaliacao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `realizou_tratamento`,
CHANGE COLUMN `dt_update` `dt_update` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `dt_avaliacao`,
CHANGE COLUMN `id_pessoa` `fk_id_pessoa` INT(11) NULL DEFAULT NULL ,
CHANGE COLUMN `id_pessoa_cadastra` `fk_id_pessoa_cadastra` INT(11) NULL DEFAULT NULL ;
