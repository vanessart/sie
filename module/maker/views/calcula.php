<?php
if (!defined('ABSPATH'))
    exit;
if (!in_array(tool::id_pessoa(), [1, 6])) {
    die();
}
$polos = sql::idNome('maker_polo');
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_turma_1 = filter_input(INPUT_POST, 'id_turma_1', FILTER_SANITIZE_NUMBER_INT);
$id_turma_2 = filter_input(INPUT_POST, 'id_turma_2', FILTER_SANITIZE_NUMBER_INT);
if (!$id_pl) {
    $id_pl = $model->setup();
}
$pls = ng_main::periodosPorSituacao([1, 2]);

if ($periodo && $id_polo) {
    $turmas = $model->turmasPolo($id_polo, $periodo);
}
?>
    <form method="POST">
        <div class="fieldTop">
            Permutar Turmas
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl) ?>
            </div>
            <div class="col">
                <?= formErp::select('id_polo', $polos, 'Polo', $id_polo) ?>
            </div>
            <div class="col">
                <?= formErp::select('periodo', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período', empty($periodo) ? 'T' : $periodo) ?>
            </div>
            <div class="col">
                <button class="btn btn-primary">
                    Selecionar
                </button>
            </div>
        </div>
        <br />
    </form>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td>

                    </td>
                    <?php
                    foreach (range(2, 6) as $ds) {
                        ?>
                        <td style="text-align: center; font-weight: bold">
                            <?= $diaSem[$ds] ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                if ($periodo == 'M') {
                    $l = 'A';
                } else {
                    $l = 'L';
                }
                foreach (range(1, 2) as $per) {
                    ?>
                    <tr>
                        <td>
                            <?= $per ?>º Horário
                        </td>
                        <?php
                        foreach (range(2, 6) as $ds) {
                            $posFixo = 'P' . $l++;
                            ?>
                            <td style="text-align: center; font-weight: bold">
                                <p>
                                    <?php
                                    if (in_array($periodo . $ds . $per, $temAlu)) {
                                        echo '<br>' . $tur[$periodo . $ds . $per] . $periodo . $ds . $per . $posFixo;
                                        echo formErp::hidden(['1[' . $periodo . $ds . $per . $posFixo . ']' => @$tur[$periodo . $ds . $per]]);
                                    } else {
                                        if (!empty($tur[$periodo . $ds . $per])) {
                                            $titulo = $tur[$periodo . $ds . $per] . $periodo . $ds . $per . $posFixo;
                                        } else {
                                            $titulo = null;
                                        }
                                        echo formErp::select('1[' . $periodo . $ds . $per . $posFixo . ']', $ciclos, $titulo, @$tur[$periodo . $ds . $per]);
                                    }
                                    ?>
                                </p>
                                <p>
                                    <?= formErp::checkbox('transp[' . $periodo . $ds . $per . $posFixo . ']', 1, 'Transporte', @$transp[$periodo . $ds . $per]) ?>
                                </p>
                                <p>
                                    <?= formErp::select('esc[' . $periodo . $ds . $per . $posFixo . ']', $escolas, null, @$esc[$periodo . $ds . $per]) ?>
                                </p>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
<?php
if ($periodo && $id_polo) {
    ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_turma_1', $turmas, 'Turma 1', $id_turma_1) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_turma_2', $turmas, 'Turma 1', $id_turma_2) ?>
        </div>
        <div class="col">
            <?= formErp::button('permutar') ?>
        </div>
    </div>
    <br />
    <?php
}
exit();
//alocar os alunos nas turmas
$sql = "SELECT `id_polo`, `fk_id_inst_maker` FROM `maker_polo` ";
$query = pdoSis::getInstance()->query($sql);
$polos = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($polos as $v) {
    foreach (['M', 'T'] as $p) {
        foreach ([2, 3, 4] as $c) {
            $turmaMain = $c . $p . '00' . str_pad($v['id_polo'], 2, "0", STR_PAD_LEFT);
            $sql = "SELECT `id_turma`, `n_turma`, `transporte` FROM `maker_gt_turma` "
                    . " WHERE `fk_id_inst` =  " . $v['fk_id_inst_maker']
                    . " AND `fk_id_ciclo` = $c "
                    . " AND `periodo` LIKE '$p' "
                    . " AND `fk_id_pl` = 111 "
                    . " and n_turma not like '$turmaMain' "
                    . " order by transporte desc";
            $query = pdoSis::getInstance()->query($sql);
            $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
            if (in_array($c, [2, 3])) {
                $mult = 6;
            } elseif (in_array($c, [4, 3])) {
                $mult = 5;
            }
            $sql = "SELECT `id_turma`, `n_turma` FROM `maker_gt_turma` "
                    . " WHERE `fk_id_inst` =  " . $v['fk_id_inst_maker']
                    . " AND `fk_id_pl` = 111 "
                    . " AND `periodo` LIKE '$p' "
                    . " AND `fk_id_ciclo` = $mult ";
            $query = pdoSis::getInstance()->query($sql);
            $multi = $query->fetchAll(PDO::FETCH_ASSOC);
            if (empty($multi) && !empty($turmas)) {
                $turmaTotal = count($turmas);
                $sql = "SELECT count(ta.fk_id_pessoa) ct FROM maker_gt_turma_aluno ta "
                        . " JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma "
                        . " AND t.fk_id_pl = 111 AND t.n_turma LIKE '$turmaMain' ";
                $query = pdoSis::getInstance()->query($sql);
                $alunosTotal = $query->fetch(PDO::FETCH_ASSOC)['ct'];
                $porTurma = ceil($alunosTotal / $turmaTotal);
                foreach ($turmas as $turma) {
                    echo '<br />' . $sql = "update maker_gt_turma_aluno "
                    . " set fk_id_turma =  " . $turma['id_turma']
                    . " where fk_id_turma = (SELECT id_turma FROM `maker_gt_turma` WHERE `n_turma` LIKE '$turmaMain' and fk_id_pl = 111) "
                    . " ORDER BY transporte DESC "
                    . " limit $porTurma ";
                    //   $query = pdoSis::getInstance()->query($sql);
                }
            }
        }
    }
}
        