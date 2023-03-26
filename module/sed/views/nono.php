<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = ng_main::periodoSet();
$sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM ge_turmas t JOIN instancia i on i.id_inst = t.fk_id_inst AND `fk_id_ciclo` = 9 AND `fk_id_pl` = $id_pl ORDER BY i.n_inst DESC ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$esc = toolErp::idName($array);
$sql = "SELECT * FROM `aa_nono_opt` ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
        @$lanc[$v['fk_id_inst']]++;
    }
}
$sql = "SELECT t.fk_id_inst id_inst, COUNT(ta.fk_id_pessoa) n_ct FROM ge_turma_aluno ta "
        . "  JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_pl=$id_pl AND t.fk_id_ciclo= 9 and fk_id_tas = 0"
        . "  GROUP BY t.fk_id_inst ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$ta = toolErp::idName($array);
?>
<div class="body">
    <div class="fieldTop">
        Definição de Matrícula para o Ensino Médio 2023
    </div>
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td style="width: 30%">
                Escola
            </td>
            <td style="width: 10%">
                Lançados
            </td>
            <td style="width: 10%">
                Total de Alunos
            </td>
            <td style="width: 50%">
            </td>
        </tr> 
        <?php
        foreach ($esc as $k => $v) {
            ?>
            <tr>
                <td>
                    <?= $v ?>  
                </td>
                <td>
                    <?= @$lanc[$k] ?>
                </td>
                <td>
                    <?= $ta[$k] ?>
                </td>
                <td>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?= intval((@$lanc[$k] / $ta[$k]) * 100) ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"><?= intval((@$lanc[$k] / $ta[$k]) * 100) ?>%</div>
                    </div>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>