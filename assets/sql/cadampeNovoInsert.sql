 #lista novos cadampes
 SELECT * from inscr_incritos_3 where migrado = 0 AND presencial = 1;

#atualiza nome e telefones de quem já existe na tabela pessoa
INSERT INTO `ge2`.`pessoa`
(`n_pessoa`,
`n_social`,
`tel1`,
`tel2`,
`tel3`)
SELECT nome, nome_social ,celular,fixo,recado
 FROM ge2.inscr_incritos_3 z
 WHERE id_cpf IN (SELECT cpf FROM pessoa p WHERE p.cpf = z.id_cpf )
 AND migrado = 0 AND presencial = 1;
 
 #insere pessoas que nao tem id_pessoa na tabela pessoa
INSERT INTO `ge2`.`pessoa`
(`n_pessoa`,
`n_social`,
`dt_nasc`,
`email`,
`ativo`,
`cpf`,
`sexo`,
`rg`,
`rg_dig`,
`rg_oe`,
`dt_rg`,
`pai`,
`mae`,
`nacionalidade`,
`uf_nasc`,
`cidade_nasc`,
`tel1`,
`tel2`,
`tel3`,
`obs`)
SELECT nome, nome_social, dt_nasc, email,1,id_cpf,sexo,rg,rg_dig,rg_oe,dt_rg,pai,mae,nacionalidade,estado_nasc,municipio_nasc,celular,fixo,recado,obs 
 FROM ge2.inscr_incritos_3 z
 WHERE id_cpf NOT IN (SELECT cpf FROM pessoa p WHERE p.cpf = z.id_cpf )
 AND presencial = 1 AND migrado = 0;
 
 ##insere novos inscritos
 INSERT INTO `ge2`.`cadampe_inscr_evento_cat`
(`fk_id_pessoa`,
`fk_id_evento`,
`fk_id_cpf`,
`fk_id_cate`,
`fk_id_sit`,
`dt_inscr`,
`times_stamp`,
`fk_id_seletiva`,
prioridade,
class)
SELECT p.id_pessoa,3,ins.id_cpf,ec.fk_id_cate,ec.fk_id_sit,ec.dt_inscr,ec.times_stamp,3,0,ic.class FROM ge2.inscr_incritos_3 ins
JOIN pessoa p ON p.cpf = ins.id_cpf
JOIN inscr_evento_categoria ec ON ec.fk_id_cpf = ins.id_cpf
JOIN inscr_classifica ic ON ic.fk_id_ec = ec.id_ec
WHERE ins.presencial = 1 AND migrado = 0;

 ##update migrado
 UPDATE ge2.inscr_incritos_3 ins
JOIN inscr_evento_categoria ec ON ec.fk_id_cpf = ins.id_cpf
JOIN inscr_classifica ic ON ic.fk_id_ec = ec.id_ec
SET ins.migrado = 1
WHERE ins.presencial = 1 AND migrado = 0
;