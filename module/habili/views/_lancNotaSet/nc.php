<?php
if (!defined('ABSPATH'))
    exit;
if ($id_pl && $atual_letiva && $id_curso) {
    $dt_dia_un_lt = sql::get('sed_letiva_data', '*', ['fk_id_curso' => $id_curso, 'fk_id_pl' => $id_pl, 'atual_letiva' => $atual_letiva], 'fetch');
    $ch['T'] = $dt_dia_un_lt['dias'];
}

if ($id_turma && $atual_letiva && $id_disc) {
    $ad = sql::get('hab`.`aval_aula_dadas', 'aula_dadas', ['atual_letiva' => $atual_letiva, 'fk_id_disc' => $id_disc, 'fk_id_turma' => $id_turma], 'fetch');
    if ($ad) {
        $ch['T'] = $ad['aula_dadas'];
    }
}
if ($id_turma) {
    $grade = turma::disciplinas($id_turma);
    foreach ($grade as $k => $v) {
        if ($v['nucleo_comum'] != 1) {
            unset($grade[$k]);
        }
    }
}
if ($libera) {
    ?>
    <form id="form" method="POST">
        <?php
    }
    ?>
    <div class="border" style="width: 300px">
        <?= formErp::input('diasQt', 'Total de Aulas (dia)', $ch['T'], ' required id="diasQt" ' . ($libera ? '' : 'readonly'), null, 'number') ?>
    </div>
    <br />
    <table class="table table-bordered table-hover table-striped border">
        <tr>
            <td rowspan="2" style="font-weight: bold">
                Nº
            </td>
            <td rowspan="2" style="font-weight: bold">
                RSE
            </td>
            <td rowspan="2" style="font-weight: bold">
                Nome
            </td>
            <td colspan="<?= count($grade) ?>" style="text-align: center">
                <b>Média</b>
            </td>
            <td rowspan="2" style="width: 100px">
                Faltas
            </td>
            <!--
            <td rowspan="2" style="width: 50px; white-space: nowrap;">
                Pres. %
            </td>
            -->
            <td colspan="<?= count($grade) ?>" style="text-align: center">
                <b>Nota</b>
            </td>
        </tr>
        <tr>
            <?php
            foreach (range(1, 2)as $v) {
                foreach ($grade as $v) {
                    ?>
                    <td style="width: 80px">
                        <?= $v['sg_disc'] ?>
                    </td>
                    <?php
                }
            }
            ?>
        </tr>
        <?php
        foreach ($alunos as $key => $value) {
            $total = [];

            if (!empty($notaFalta[$value['id_pessoa']]['id_mf'])) {
                echo formErp::hidden(['id_mf[' . $value['id_pessoa'] . ']' => $notaFalta[$value['id_pessoa']]['id_mf']]);
            }

            if (!empty($notaFalta[$value['id_pessoa']]['falta'][$id_disc])) {
                $falta = $notaFalta[$value['id_pessoa']]['falta'][$id_disc];
            } elseif (!empty($ch['F'][$value['id_pessoa']])) {
                $falta = $ch['F'][$value['id_pessoa']];
            } else {
                $falta = '0';
            }
            if (@$ch['T'] > 0) {
                $faltaPorc = 100 - round((($falta / $ch['T']) * 100));
                if ($faltaPorc < 75) {
                    $colorFalta = 'red';
                } else {
                    $colorFalta = 'blue';
                }
                $faltaPorc .= '%';
            } else {
                $faltaPorc = '?';
            }
            ?>
            <tr>
                <td>

                    <?= $value['chamada'] ?>
                </td>

                <td>
                    <?= $value['id_pessoa'] ?>
                </td>
                <td>
                    <?= $value['n_pessoa'] ?>
                </td>
                <?php
                foreach ($instrumentos as $k => $v) {
                    if ($v->ativo == 1 && !empty($v->notas)) {
                        $total[$v->id_disc_nc][] = @$value['notasAluno'][$v->uniqid];
                    }
                }
                foreach ($grade as $v) {
                    ?>
                    <td style="width: 80px">
                        <?php
                        if (!empty($total[$v['id_disc']])) {
                            $totalDisc = $total[$v['id_disc']];
                            echo $totalInstr = round((array_sum($totalDisc) / count($totalDisc)), 1);
                            echo formErp::hidden(['instr[' . $value['id_pessoa'] . '][' . $v['id_disc'] . ']' => $totalInstr]);
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                    <?php
                }
                if (!empty($ch['T'])) {
                    $max = ' max="' . intval(@$ch['T']) . '"';
                } else {
                    $max = null;
                }
                ?>
                <td>
                    <?= formErp::input('falta[' . $value['id_pessoa'] . ']', null, $falta, $max . ' onclick="this.select()" ', null, 'number') ?>
                </td>
                <!--
                <td style="color: <?= $colorFalta ?>">
                <?= $faltaPorc ?>
                </td>
                -->
                <?php
                foreach ($grade as $v) {
                    if (!empty($notaFalta[$value['id_pessoa']]['nota'][$v['id_disc']])) {
                        $nota = $notaFalta[$value['id_pessoa']]['nota'][$v['id_disc']];
                    } else {
                        $nota = '-';
                    }
                    ?>
                    <td>
                        <?= formErp::input('nota[' . @$v['id_disc'] . '][' . $value['id_pessoa'] . ']', null, $nota, ' onclick="this.select()" style="font-weight: bold; color:' . ($nota < 5 ? 'red' : 'blue') . '" ', $v['sg_disc']) ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        <?php } ?>
    </table>
    <br /><br />
    <div class="row">
        <div class="col text-center">
            <?php
            if ($instrumentos && $libera) {
                ?>
                <input type="hidden" name="transpInstr" id="transpInstr" value="" />
                <button class="btn btn-primary" type="button" onclick="transp()" >
                    Transportar as médias para as menções Bimestrais
                </button>
                <?php
            } elseif (!$libera) {
                ?>
                <button class="btn btn-secondary" type="button" onclick="alert('Sistema Fechado para Lançamento')">
                    Transportar as médias para as menções Bimestrais
                </button>
                <?php
            } else {
                ?>
                <button class="btn btn-secondary" type="button" onclick="alert('Não houve lançamento de instrumentos avaliativos')">
                    Transportar as médias para as menções Bimestrais
                </button>
                <?php
            }
            ?>
        </div>
        <div class="col text-center">
            <?php
            if ($libera) {
                ?>
                <?=
                formErp::hidden($hidden)
                . formErp::hidden([
                    'atual_letiva' => $atual_letiva
                ])
                . formErp::hiddenToken('salvaNotaNc')
                . formErp::button('Salvar')
                ?>
                <?php
            } else {
                ?>
                <button class="btn btn-secondary" type="button" onclick="alert('Sistema Fechado para Lançamento')">
                    Salvar
                </button>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    if ($libera) {
        ?>
    </form>
    <?php
}