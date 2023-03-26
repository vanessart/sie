<?php
if (!defined('ABSPATH'))
    exit;

$polos = sql::idNome('tdics_polo');
@$id_pl = sql::get('tdics_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];
$sql = "SELECT * FROM tdics_turma_aluno ta JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
        . " and t.fk_id_pl = $id_pl";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
        @$q['total']++;
        @$q['totalDiaSem'][$v['dia_sem']]++;
        @$q['totalPer'][$v['periodo']][$v['dia_sem']]++;
        @$q['totalhorario'][$v['periodo']][$v['dia_sem']][$v['horario']]++;

        @$qp[$v['fk_id_polo']]['total']++;
        @$qp[$v['fk_id_polo']]['totalDiaSem'][$v['dia_sem']]++;
        @$qp[$v['fk_id_polo']]['totalPer'][$v['periodo']][$v['dia_sem']]++;
        @$qp[$v['fk_id_polo']]['totalhorario'][$v['periodo']][$v['dia_sem']][$v['horario']]++;
    }
    ksort($qp);

}
?>
<div class="body">
    <div class="fieldTop">
        Quadro de Alunos
    </div>
    <table class="table table-bordered table-hover table-responsive border">
        <tr>
            <td colspan="7" style="text-align: center; font-weight: bold; font-size: 1.4em">
                Quadro Geral
            </td>
        </tr>
        <tr>
            <td colspan="2">

            </td>
            <?php
            foreach ($model->diaSemana() as $v) {
                ?>
                <td>
                    <?= $v ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
        foreach (['M' => 'Manhã', 'T' => 'Tarde'] as $kper => $per) {
            ?>
            <tr>
                <td rowspan="3">
                    <?= $per ?>
                </td>
                <td>
                    1º Horário
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    ?>
                    <td>
                        <?php
                        if (!empty($q['totalhorario'][$kper][$k][1])) {
                            echo $q['totalhorario'][$kper][$k][1];
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td>
                    2º Horário
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    ?>
                    <td>
                        <?php
                        if (!empty($q['totalhorario'][$kper][$k][2])) {
                            echo $q['totalhorario'][$kper][$k][2];
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td>
                    Total da <?= $per ?>
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    ?>
                    <td>
                        <?php
                        if (!empty($q['totalPer'][$kper][$k])) {
                            echo $q['totalPer'][$kper][$k];
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>

            <?php
        }
        ?>
        <tr>
            <td colspan="2">
                Total do Dia da Semana
            </td>
            <?php
            foreach ($model->diaSemana() as $k => $v) {
                ?>
                <td>
                    <?php
                    if (!empty($q['totalDiaSem'][$k])) {
                        echo $q['totalDiaSem'][$k];
                    } else {
                        echo '0';
                    }
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td colspan="2">
                Total Geral
            </td>
            <td colspan="5">
                <?= @$q['total'] ?>
            </td>
        </tr>
    </table>
    <br /><br />
    <?php
    foreach ($polos as $id_polo => $n_polo) {
        ?>
        <table class="table table-bordered table-hover table-responsive border">
            <tr>
                <td colspan="7" style="text-align: center; font-weight: bold; font-size: 1.4em">
                    Quadro do Núcleo <?= $n_polo ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">

                </td>
                <?php
                foreach ($model->diaSemana() as $v) {
                    ?>
                    <td>
                        <?= $v ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            foreach (['M' => 'Manhã', 'T' => 'Tarde'] as $kper => $per) {
                ?>
                <tr>
                    <td rowspan="3">
                        <?= $per ?>
                    </td>
                    <td>
                        1º Horário
                    </td>
                    <?php
                    foreach ($model->diaSemana() as $k => $v) {
                        ?>
                        <td>
                            <?php
                            if (!empty($qp[$id_polo]['totalhorario'][$kper][$k][1])) {
                                echo $qp[$id_polo]['totalhorario'][$kper][$k][1];
                            } else {
                                echo '0';
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>
                        2º Horário
                    </td>
                    <?php
                    foreach ($model->diaSemana() as $k => $v) {
                        ?>
                        <td>
                            <?php
                            if (!empty($qp[$id_polo]['totalhorario'][$kper][$k][2])) {
                                echo $qp[$id_polo]['totalhorario'][$kper][$k][2];
                            } else {
                                echo '0';
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>
                        Total da <?= $per ?>
                    </td>
                    <?php
                    foreach ($model->diaSemana() as $k => $v) {
                        ?>
                        <td>
                            <?php
                            if (!empty($qp[$id_polo]['totalPer'][$kper][$k])) {
                                echo $qp[$id_polo]['totalPer'][$kper][$k];
                            } else {
                                echo '0';
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>

                <?php
            }
            ?>
            <tr>
                <td colspan="2">
                    Total do Dia da Semana
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    ?>
                    <td>
                        <?php
                        if (!empty($qp[$id_polo]['totalDiaSem'][$k])) {
                            echo $qp[$id_polo]['totalDiaSem'][$k];
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td colspan="2">
                    Total Geral
                </td>
                <td colspan="5">
                    <?= @$qp[$id_polo]['total'] ?>
                </td>
            </tr>
        </table>
        <?php
    }
    ?>
</div>