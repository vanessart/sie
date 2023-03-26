<?php

if (!defined('ABSPATH'))
    exit;



$sql = "UPDATE `endereco` SET fkid = fk_id_pessoa WHERE fkid is null ";
try {
    $query = pdoSis::getInstance()->query($sql);
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}


//excluir os aluno do projeto maker
//$sql = "UPDATE maker_aluno a JOIN maker_gt_turma_aluno ta on ta.fk_id_pessoa = a.fk_id_pessoa SET a.fk_id_as = 4 WHERE ta.confirma LIKE 'MNC'";
//$query = pdoSis::getInstance()->query($sql);
//$sql = "INSERT INTO `maker_gt_turma_aluno_MNC` SELECT * FROM `maker_gt_turma_aluno` WHERE `confirma` LIKE 'MNC'";
//$query = pdoSis::getInstance()->query($sql);
//$sql = "DELETE FROM `maker_gt_turma_aluno` WHERE `confirma` LIKE 'MNC'";
//$query = pdoSis::getInstance()->query($sql);
//recupera tabela ge_turmas

$sql = "SELECT * FROM `ge_turmas` WHERE (`fk_id_grade` = 0 or fk_id_grade is null) AND id_turma != 1 ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
      echo '<br />'.  $sql = "UPDATE ge_turmas t "
                . " JOIN ge_curso_grade ci on ci.fk_id_ciclo = t.fk_id_ciclo and t.id_turma = " . $v['id_turma']
                . " SET t.fk_id_grade = ci.fk_id_grade ";
        try {
            $query = pdoSis::getInstance()->query($sql);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
}
// apagar as validações de formulario usadas
$mongo = new mongoCrude();

$mongo->delete('formtoken', []);

//Excluir projetos
$sql = "DELETE FROM `profe_projeto` WHERE `n_projeto` LIKE 'excluir' ";
$query = pdoSis::getInstance()->query($sql);
