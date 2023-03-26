ALTER TABLE `ge2`.`audicao_campanha` 
ADD COLUMN `liberar_aviso` TINYINT(1) NULL DEFAULT 0 AFTER `at_campanha`,
CHANGE COLUMN `at_campanha` `at_campanha` TINYINT(1) NULL DEFAULT '0' ;

ALTER TABLE `ge2`.`audicao_form_pessoa` 
ADD INDEX `idx_id_pessoa` (`fk_id_pessoa` ASC),
ADD INDEX `idx_id_campanha` (`fk_id_campanha` ASC),
ADD INDEX `idx_id_form` (`fk_id_form` ASC);
;

ALTER TABLE `ge2`.`audicao_form_resposta` 
ADD INDEX `idx_id_pessoa` (`fk_id_pessoa` ASC),
ADD INDEX `idx_id_form` (`fk_id_form` ASC),
ADD INDEX `idx_id_campanha` (`fk_id_campanha` ASC);
;

ALTER TABLE `ge2`.`audicao_campanha` 
ADD COLUMN `liberar_form` TINYINT(1) NULL DEFAULT 0 AFTER `liberar_aviso`;

CREATE TABLE `ge2`.`audicao_campanha_ciclo` (
  `id_campanha_ciclo` INT NOT NULL AUTO_INCREMENT,
  `fk_id_ciclo` INT NULL,
  `tipo` TINYINT(1) NULL COMMENT '1-liberar_form, 2- liberar_aviso',
  `fk_id_pessoa` INT NULL,
  `dt_update` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_campanha_ciclo`));

ALTER TABLE `ge2`.`audicao_campanha_ciclo` 
ADD COLUMN `fk_id_campanha` INT NULL AFTER `dt_update`;
