CREATE TABLE `ge2`.`apd_doc_entrevista_atualiza` (
  `id_entre_atual` INT NOT NULL,
  `fk_id_entre` INT NULL,
  `fk_id_pessoa_insere` INT NULL,
  `dt_update` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_id_pl` INT NULL,
  PRIMARY KEY (`id_entre_atual`));

ALTER TABLE `ge2`.`apd_doc_entrevista_atualiza` 
ADD COLUMN `atualizacao` TEXT NULL AFTER `fk_id_pl`;
ALTER TABLE `ge2`.`apd_doc_entrevista_atualiza` 
CHANGE COLUMN `fk_id_pessoa_insere` `fk_id_pessoa_prof` INT(11) NULL DEFAULT NULL ;
ALTER TABLE `ge2`.`apd_doc_entrevista_atualiza` 
CHANGE COLUMN `id_entre_atual` `id_entre_atual` INT(11) NOT NULL AUTO_INCREMENT ;
