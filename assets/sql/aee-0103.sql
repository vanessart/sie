ALTER TABLE `ge2`.`apd_componente` 
ADD COLUMN `sit_didatica` TEXT NULL AFTER `fk_id_aluno_adaptacao`,
ADD COLUMN `recurso` TEXT NULL AFTER `sit_didatica`,
ADD COLUMN `atvd_estudo` TEXT NULL AFTER `recurso`,
ADD COLUMN `instr_avaliacao` TEXT NULL AFTER `atvd_estudo`,
CHANGE COLUMN `n_componente` `n_componente` TEXT NULL ,
CHANGE COLUMN `unidade` `unidade` TEXT NULL ,
CHANGE COLUMN `objeto` `objeto` TEXT NULL ,
CHANGE COLUMN `habilidade` `habilidade` TEXT NULL ,
CHANGE COLUMN `fk_id_nota` `fk_id_nota` TINYINT(4) NULL ,
CHANGE COLUMN `fk_id_aluno_adaptacao` `fk_id_aluno_adaptacao` INT(11) NULL ;
ALTER TABLE `ge2`.`apd_parecer` 
ADD COLUMN `atvd_estudo` TEXT NULL DEFAULT NULL AFTER `fk_id_aluno_adaptacao`,
ADD COLUMN `instr_avaliacao` TEXT NULL DEFAULT NULL AFTER `atvd_estudo`,
CHANGE COLUMN `n_parecer` `n_parecer` TEXT NULL ,
CHANGE COLUMN `fk_id_aluno_adaptacao` `fk_id_aluno_adaptacao` INT(11) NULL ;
ALTER TABLE `ge2`.`apd_componente` 
DROP COLUMN `instr_avaliacao`,
DROP COLUMN `atvd_estudo`;
