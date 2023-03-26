<?php
if (!defined('ABSPATH'))
    exit;
if ($id_pl && $atual_letiva && $id_curso && $id_turma && $id_disc) {
    $dt_dia_un_lt = sql::get('sed_letiva_data', '*', ['fk_id_curso' => $id_curso, 'fk_id_pl' => $id_pl, 'atual_letiva' => $atual_letiva], 'fetch');
    $sql = "SELECT ad.aulas FROM ge_turmas t "
            . " JOIN ge_aloca_disc ad on ad.fk_id_grade = t.fk_id_grade AND ad.fk_id_disc = $id_disc "
            . " WHERE id_turma = $id_turma;";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetch(PDO::FETCH_ASSOC);
    if ($array) {
        $ch['T'] = ceil(($dt_dia_un_lt['dias'] / 5) * $array['aulas']);
    }
}

if ($id_turma && $atual_letiva && $id_disc) {
    $ad = sql::get('hab`.`aval_aula_dadas', 'aula_dadas', ['atual_letiva' => $atual_letiva, 'fk_id_disc' => $id_disc, 'fk_id_turma' => $id_turma], 'fetch');
    if ($ad) {
        $ch['T'] = $ad['aula_dadas'];
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
            <td style="font-weight: bold">
                Nº
            </td>
            <td style="font-weight: bold">
                RSE
            </td>
            <td style="font-weight: bold">
                Nome
            </td>
            <td>
                <b>Média</b>
            </td>
            <td style="width: 100px">
                Faltas
            </td>
            <td style="width: 50px; white-space: nowrap;">
                Pres. %
            </td>
            <td style="width: 80px">
                Notas
            </td>
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
                        $total[] = @$value['notasAluno'][$v->uniqid];
                    }
                }
                ?>
                <td>
                    <?php
                    if (!empty($total)) {
                        echo $totalInstr = round((array_sum($total) / count($total)), 1);
                        echo formErp::hidden(['instr[' . $value['id_pessoa'] . ']' => $totalInstr]);
                    } else {
                        echo '0';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (!empty($ch['T'])) {
                        $max = ' max="' . intval(@$ch['T']) . '"';
                    } else {
                        $max = null;
                    }
                    ?>
                    <?= formErp::input('falta[' . $value['id_pessoa'] . ']', null, $falta, $max . ' onclick="this.select()" ', null, 'number') ?>
                </td>
                <td style="color: <?= $colorFalta ?>">
                    <?= $faltaPorc ?>
                </td>
                <?php
                if (!empty($notaFalta[$value['id_pessoa']]['nota'][$id_disc])) {
                    $nota = $notaFalta[$value['id_pessoa']]['nota'][$id_disc];
                } else {
                    $nota = '-';
                }
                ?>
                <td>
                    <?= formErp::input('nota[' . $id_disc . '][' . $value['id_pessoa'] . ']', null, $nota, ' onclick="this.select()" style="font-weight: bold; color:' . ($nota < 5 ? 'red' : 'blue') . '"') ?>
                </td>
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
                . formErp::hiddenToken('salvaNota')
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