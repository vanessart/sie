<?php
if (!defined('ABSPATH'))
    exit;
ini_set('memory_limit', '200000M');

$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$pls = ng_main::periodosPorSituacao();
$id_inst = tool::id_inst();
$turmas = gtTurmas::idNome($id_inst, $id_pl);
$diasSem = [
    1 => 'Seguda',
    2 => 'Terça',
    3 => 'Quarta',
    4 => 'Quinta',
    5 => 'Sexta'
];
?>
<div class="body">
    <div class="fieldTop">
        Controle de faltas
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl, 1) ?>
        </div>

    </div>
    <br />
    <?php
    if ($turmas) {
        foreach ($turmas as $id_turma => $n_turma) {
            $faltas = $model->chamadaControl($id_pl, (string) $id_turma);
            ?>
            <div class="border">
                <div style="text-align: center; font-weight: bold; font-size: 1.3em">
                    <?= $n_turma ?>
                </div>
                <br /><br />
                <?php
                foreach ($faltas as $v) {
                    if (!empty($v['faltas'])) {
                        $cf = 0;
                        foreach ($v['faltas'] as $mes => $dias) {
                            if (count($dias) > $cf) {

                                $cf = count($dias);
                            }
                        }
                        if ($cf >2) {
                            @$conta++;
                            ?>
                            <table class="table table-bordered table-hover table-striped border">
                                <tr style="font-weight: bold; font-size: 1.4em">
                                    <td style="width: 200px">
                                        RSE: <?= $v['dados']['id_pessoa'] ?>
                                    </td>
                                    <td>
                                        Nome: <?= $v['dados']['n_pessoa'] ?>
                                    </td>
                                    <td>
                                        Situação: <?= $v['dados']['situacao'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table style="width: 100%">
                                            <tr>
                                                <td style="width: 50%; padding: 10px">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <tr>
                                                            <td colspan="2" style="text-align: center">
                                                                Faltas por Mês
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        foreach ($v['faltas'] as $mes => $dias) {
                                                            ?>
                                                            <tr>
                                                                <td style="width: 200px">
                                                                    Faltas de  <?= data::mes($mes) ?>
                                                                </td>
                                                                <td>
                                                                    <?= toolErp::virgulaE($dias) ?>  
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table>

                                                </td>
                                                <td align="top" style="padding: 10px">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <tr>
                                                            <td colspan="6" style="text-align: center">
                                                                Faltas por Mês e dia da semana
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Mês
                                                            </td>
                                                            <?php
                                                            foreach ($diasSem as $kds => $ds) {
                                                                ?>
                                                                <td>
                                                                    <?= $ds ?>
                                                                </td>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                        <?php
                                                        foreach ($v['faltaDiaSem'] as $mesSem => $sem) {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?= data::mes($mesSem) ?>
                                                                </td>
                                                                <?php
                                                                foreach ($diasSem as $kds => $ds) {
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if (!empty($sem[$kds])) {
                                                                            echo $sem[$kds];
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
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br /><br />
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <br />
            <?php
        }
    }
    ?>
</div>

