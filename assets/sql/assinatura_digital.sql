CREATE TABLE `asd_assinatura` (
	`id_assinatura` INT(11) NOT NULL AUTO_INCREMENT,
	`fk_id_inst` INT(11) NULL DEFAULT NULL,
	`fk_id_pessoa` INT(11) NULL DEFAULT NULL,
	`assinatura` TEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`tipo` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`extension` VARCHAR(4) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`IP` VARCHAR(17) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`dt_update` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id_assinatura`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
