<?php
if (!defined('ABSPATH'))
    exit;
$frequencia = filter_input(INPUT_POST, 'frequencia');
$id_pl_migra = filter_input(INPUT_POST, 'id_pl_migra', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo');
$polos = sql::idNome('maker_polo');
$ciclos = sql::idNome('maker_ciclo');
$diaSem = [
    2 => 'Segunda',
    3 => 'Terça',
    4 => 'Quarta',
    5 => 'Quinta',
    6 => 'Sexta'
];
$id_pl = $model->setup();
$pls = ng_main::periodosPorSituacao([1, 2]);

if ($id_polo && $periodo) {
    $escolas_ = sql::get(['maker_escola', 'instancia'], 'id_inst, n_inst', ['fk_id_polo' => $id_polo]);
    foreach ($escolas_ as $v) {
        $escolas[$v['id_inst']] = abrev($v['n_inst']);
    }

    $id_inst = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
    $turmas = sql::get('maker_gt_turma', 'n_turma, id_turma, transporte, fk_id_inst_sieb', ['fk_id_inst' => $id_inst, '>' => 'n_turma', 'fk_id_pl' => $id_pl, 'periodo' => $periodo]);

    if ($turmas) {
        foreach ($turmas as $v) {
            $tur[substr($v['n_turma'], 1, 3)] = substr($v['n_turma'], 0, 1);
            $transp[substr($v['n_turma'], 1, 3)] = $v['transporte'];
            $esc[substr($v['n_turma'], 1, 3)] = $v['fk_id_inst_sieb'];
        }
    }

    $sql = "SELECT DISTINCT substring(t.n_turma, 2,3) turma FROM maker_gt_turma t JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma and t.fk_id_pl = $id_pl AND t.fk_id_inst = $id_inst";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    $temAlu = array_column($array, 'turma');
    $esp = $model->espera($id_polo, $periodo);
    $espera = $esp['ciclos'];
    $espEsc = $esp['esc'];

    $ciclosQtg = $model->relatCriarTurma($id_polo, $periodo, $id_pl_migra, $frequencia);
    $ciclosQt = $ciclosQtg['total'];
    $ciclosQtEsc = $ciclosQtg['esc'];
}
?>
<div class="body">
    <form method="POST">
        <div class="fieldTop">
            Criar Turmas
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_polo', $polos, 'Polo', $id_polo) ?>
            </div>
            <div class="col">
                <?= formErp::select('periodo', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período', empty($periodo) ? 'T' : $periodo) ?>
            </div>
            <div class="col">
                <?php
                foreach (range(5, 9) as $v) {
                    $fl[$v] = $v . '0 ou mais';
                }
                echo formErp::select('frequencia', $fl, 'Frequência', empty($frequencia) ? 7 : $frequencia);
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('id_pl_migra', $pls, 'Migrar Turmas', empty($id_pl_migra) ? 88 : $id_pl_migra) ?>
            </div>
            <div class="col">
                <button class="btn btn-primary">
                    Selecionar
                </button>
            </div>
        </div>
        <br />
    </form>
    <?php
    if ($id_polo && $periodo) {
        ?>
    <style>
        #tb td{
            border: #000000 2px solid;
            padding: 5px;
        }
    </style>
    <table id="tb" style="width: 100%;" border=1 cellspacing=0 cellpadding=2 class="border"> 
            <tr>
                <td>

                </td>
                <td>
                    Básico
                </td>
                <td>
                    Intermediário
                </td>
                <td>
                    Avançado 1
                </td>
                <td>
                    Avançado 2
                </td>
            </tr>
            <?php
            foreach ($espEsc as $escSet => $de) {
                ?>
                <tr>
                    <td>
                        Fila de Espera - <?= $escSet ?>
                    </td>
                    <td>
                        <?= intval(@$de[1]) ?>
                    </td>
                    <td>
                        <?= intval(@$de[2]) ?>
                    </td>
                    <td>
                        <?= intval(@$de[3]) ?>
                    </td>
                    <td>
                        <?= intval(@$de[4]) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr style="background-color: black; color: white">
                <td>
                    Fila de Espera - TOTAL
                </td>
                <td>
                    <?= intval(@$espera[1]) ?>
                </td>
                <td>
                    <?= intval(@$espera[2]) ?>
                </td>
                <td>
                    <?= intval(@$espera[3]) ?>
                </td>
                <td>
                    <?= intval(@$espera[4]) ?>
                </td>
            </tr>
            <?php
            foreach ($ciclosQtEsc as $idEsc => $v) {
                if (!empty($escolas[$idEsc])) {
                    ?>
                    <tr>
                        <td>
                            Turmas Anteriores - <?= $escolas[$idEsc] ?>
                        </td>
                        <td>
                            <?= intval(@$v[1]) ?>
                        </td>
                        <td>
                            <?= intval(@$v[2]) ?>
                        </td>
                        <td>
                            <?= intval(@$v[3]) ?>
                        </td>
                        <td>
                            <?= intval(@$v[4]) ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr style="background-color: black; color: white">
                <td>
                    Turmas Anteriores - TOTAL
                </td>
                <td>
                    <?= intval(@$ciclosQt[1]) ?>
                </td>
                <td>
                    <?= intval(@$ciclosQt[2]) ?>
                </td>
                <td>
                    <?= intval(@$ciclosQt[3]) ?>
                </td>
                <td>
                    <?= intval(@$ciclosQt[4]) ?>
                </td>
            </tr>
            <tr style="background-color: blue; color: white">
                <td>
                    Total
                </td>
                <?php
                foreach (range(1, 4) as $v) {
                    ?>
                    <td>
                        <?php
                        $valor = intval(@$ciclosQt[$v]) + intval(@$espera[$v])
                        ?>
                        <?= $valor ?> (<?= round($valor / 28, 2) ?>)
                    </td>
                    <?php
                }
                ?>


            </tr>
        </table>
        <br /><br />
        <form method="POST">
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
                            <?= $per ?>º Período
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
            <div style="text-align:  center; padding: 20px">
                <?=
                formErp::hiddenToken('criarTurmas')
                . formErp::hidden([
                    'id_polo' => $id_polo,
                    'periodo' => $periodo,
                    'id_inst' => $id_inst,
                    'id_pl_migra' => $id_pl_migra,
                    'frequencia' => $frequencia
                ])
                . formErp::button("Salvar")
                ?>
            </div>
        </form>
        <?php
    }
    ?>
</div>
<?php

function abrev($nome) {
    $e = explode(' ', $nome);
    unset($e[0]);

    return substr(implode(' ', $e), 0, 14);
}
?>