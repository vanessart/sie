<?php
if (!defined('ABSPATH'))
    exit;
$polos = sql::idNome('maker_polo');
$escolas = ng_escolas::idEscolas();
$sql = "SELECT distinct a.fk_id_pessoa, a.fk_id_inst, a.fk_id_polo, au.sim FROM maker_aluno a "
        . " join ge_turma_aluno ta on ta.fk_id_pessoa = a.fk_id_pessoa and a.fk_id_as = 1 "
        . " join ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_ciclo in (1,2,3,4,5,6,7,8,9) "
        . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl=1 "
        . "left join maker_autoriza au on au.id_pessoa_aluno = a.fk_id_pessoa ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    @$fez[$v['fk_id_polo']][$v['fk_id_inst']][intval($v['sim'])]++;
    @$fezPolo[$v['fk_id_polo']][intval($v['sim'])]++;
    @$totalPolo[$v['fk_id_polo']]++;
}
ksort($fez);
foreach ($polos as $id_polo => $n_polo) {
    if (!empty($totalPolo[$id_polo])) {
        if (empty($fezPolo[$id_polo][1])) {
            $tp = 0;
        } else {
            $tp = round(($fezPolo[$id_polo][1] / $totalPolo[$id_polo]) * 100);
        }
        ?>
        <div class="border">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td colspan="2" style="text-align: center">
                        <?= $n_polo ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 40%">
                        Total de Alunos: <?= intval(@$totalPolo[$id_polo]) ?>
                    </td>
                    <td>
                        Corfirmados para a próxima fase: <?= intval(@$fezPolo[$id_polo][1]) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?= $tp ?>%;" aria-valuenow="<?= $tp ?>" aria-valuemin="0" aria-valuemax="100"><?= $tp ?>%</div>
                        </div>
                    </td>
                </tr>
                <?php
                if (!empty($fez[$id_polo])) {
                    foreach ($fez[$id_polo] as $id_inst => $e) {
                        $t= array_sum($e);
                        $porc = round((@$e[1]/$t)*100);
                        ?>
                        <tr>
                            <td>
                                <?= $escolas[$id_inst] ?> (<?= intval(@$e[1]) ?> de <?= $t ?>)
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $porc ?>%;" aria-valuenow="<?= $porc ?>" aria-valuemin="0" aria-valuemax="100"><?= $porc ?>%</div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
        <br /><br />
        <?php
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Controle de Rematricula
    </div>
</div>
