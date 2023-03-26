<style>
    .azul{
        color: white;
        font-weight: bold;
        display: block;
        background-color: blue;
        padding: 6px;
        width: 40px;
        text-align: center;
        border-radius: 6px;
        margin: auto;
    }
    .vermelho{
        color: white;
        font-weight: bold;
        display: block;
        background-color: red;
        padding: 6px;
        width: 40px;
        text-align: center;
        border-radius: 6px;
        margin: auto;
    }
</style>

<?php
if (!defined('ABSPATH'))
    exit;
$hoje = date("d");
$esteMes = date("m");
$sql = "select id_pl, n_pl, ano from ge_periodo_letivo where ano >= 2023 order by ano desc";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$pls = toolErp::idName($array);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
if(empty($id_pl)){
    $id_pl = ng_main::periodoSet();
$id_pl_set = 1;
} else {
$id_pl_set = 0;
}
if (in_array(toolErp::id_nilvel(), [2, 10, 53, 54])) {
    $escolas = ng_escolas::idEscolas();
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} elseif (toolErp::id_nilvel() == 22) {
    $escolas = ng_escolas::idEscolasSupervisor(tool::id_pessoa(), [1]);
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = toolErp::id_inst();
}
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
$ano = str_pad(date("Y"), 2, "0", STR_PAD_LEFT);
if (empty($mes)) {
    if (date("m") > 1) {
        $mes = date("m");
    } else {
        $mes = '02';
    }
}
if ($id_inst && $mes && $id_pl) {
    $ano = toolErp::idName($array, 'id_pl', 'ano')[$id_pl];
    $diaMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    $sql = " select "
            . " t.id_turma, t.n_turma, ci.aulas, ci.id_ciclo, ci.fk_id_curso "
            . " from ge_turmas t "
            . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
            . " and t.fk_id_inst = $id_inst "
            . " and t.fk_id_pl = $id_pl "
            . " and t.fk_id_ciclo != 32";
    $query = pdoSis::getInstance()->query($sql);
    $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
    $diasLetivo = $model->diasLetivo($id_pl, (!empty($id_pl_set)?1:9), $mes);
    if (!empty($turmas)) {
        foreach ($turmas as $v) {
            $aulasTurma[$v['id_turma']] = $v['aulas'];
            $turmaCurso[$v['id_turma']] = $v['fk_id_curso'];
            $turmasIdNome[$v['id_turma']] = $v['n_turma'];
        }
        $idsTurmas = array_column($turmas, 'id_turma');
        $mongo = new mongoCrude('Diario');

        $ta = $mongo->query('presence_' . $id_pl, ['id_turma' => ['$in' => $idsTurmas], 'data' => ['$regex' => '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-']], ['projection' => ['id_turma' => 1, 'data' => 1, 'aula' => 1, 'id_disc' => 1, '_id' => 0]]);
        if ($ta) {
            foreach ($ta as $v) {
                $teveAula[$v->id_turma][substr($v->data, -2)][$v->aula] = $v->id_disc;
            }
        }
        $sql = "SELECT "
                . " `fk_id_turma`, `dia_semana`, `iddisc` "
                . " FROM `ge_horario` "
                . " WHERE `fk_id_turma` in (" . implode(', ', $idsTurmas) . ")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                @$turmaDiaDisc[$v['fk_id_turma']][$v['dia_semana']][$v['iddisc']]++;
            }
        }
        $conteudo_ = $mongo->query('Aula.' . $id_pl, ['id_turma' => ['$in' => $idsTurmas], 'data' => ['$regex' => '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-']], ['projection' => ['id_turma' => 1, 'data' => 1, 'id_disc' => 1, '_id' => 0]]);
        if ($conteudo_) {
            foreach ($conteudo_ as $v) {
                @$turmaDiaDiscTem[$v->id_turma][substr($v->data, -2)][$v->id_disc]++;
            }
        }
    }
}
$meses = data::meses();

unset($meses['01']);
if (empty($turmaDiaDisc)) {
    $turmaDiaDisc = [];
}
if (empty($turmaDiaDiscTem)) {
    $turmaDiaDiscTem = [];
}
?>
<div class="body">
    <div class="fieldTop">
        Controle de Aulas Dadas por Dia
    </div>
    <div class="row">
        <?php
        if (in_array(toolErp::id_nilvel(), [2, 10, 53, 54, 22])) {
            ?>
            <div class="col">
                <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1, ['mes' => $mes, 'id_pl' => $id_pl]) ?>
            </div>
            <?php
        }
        ?>
        <div class="col">
            <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl, 1, ['id_inst' => $id_inst, 'mes' => $mes]) ?>
        </div>
        <div class="col">
            <?= formErp::select('mes', $meses, 'Mês', $mes, 1, ['id_inst' => $id_inst, 'id_pl' => $id_pl]) ?>
        </div>
    </div>
    <br />
    <table>
        <tr>
            <td>
                <div class="vermelho" style="width: 60px">
                    CH
                    <br />
                    4 | 5
                </div>  
            </td>
            <td class="col-1" style="width: 30px;font-weight: bold;font-size: 1.6em; padding-top: 8px; padding-left: 8px">
                =
            </td>
            <td style="width: 300px;">
                <div class="vermelho" style="width: 340px">
                    Chamada
                    <br />
                    Laçamentos | Quantidade de Aulas
                </div>
            </td>
            <td style="width: 100px">

            </td>
            <td>
                <div class="vermelho" style="width: 60px">
                    CT
                    <br />
                    2 | 3
                </div>  
            </td>
            <td class="col-1" style="width: 30px;font-weight: bold;font-size: 1.6em; padding-top: 8px; padding-left: 8px">
                =
            </td>
            <td style="width: 300px;">
                <div class="vermelho" style="width: 340px">
                    Conteúdo
                    <br />
                    Laçamentos | Quantidade de Disciplinas
                </div>
            </td>
            <td style="width: 100px">

            </td>
            <td>
                <div class="azul" style="width: 200px">
                    Todos os lançamentos foram realizados
                </div>
            </td>
            <td style="width: 100px">

            </td>
            <td>
                <div class="vermelho" style="width: 200px">
                    Um ou mais lançamentos não foram realizados
                </div>
            </td>
        </tr>
    </table>
    <br />
    <?php
    if ($id_inst && $mes && $id_pl && !empty($turmas)) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td style="font-weight: bold; font-size: 1.6em !important; text-align: center">
                    Turma
                </td>
                <?php
                if (empty($diasLetivo)) {
                    ?>
                <div class="alert alert-danger" style="text-align: center; font-weight: bold">
                    Não disponível para este Período Letivo
                </div>
                <?php
            } else {
                foreach (range(1, $diaMes) as $d) {
                    $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
                    if (!in_array($sem, [0, 6]) && in_array($ano . '-' . $mes . '-' . str_pad($d, 2, "0", STR_PAD_LEFT), $diasLetivo)) {
                        ?>
                        <td style="font-weight: bold; font-size: 1.6em !important; text-align: center">
                            <?= $d ?>
                        </td>
                        <?php
                    }
                }
            }
            ?>
            </tr>
            <tr>
                <td></td>
                <?php
                if (!empty($diasLetivo)) {
                    foreach (range(1, $diaMes) as $d) {
                        $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
                        if (!in_array($sem, [0, 6]) && in_array($ano . '-' . $mes . '-' . str_pad($d, 2, "0", STR_PAD_LEFT), $diasLetivo)) {
                            ?>
                            <td style="font-weight: bold; font-size: 1.6em !important; text-align: center">
                                <?= dataErp::diasDaSemana($sem, 1) ?>
                            </td>
                            <?php
                        }
                    }
                }
                ?>
            </tr>
            <?php
            $cor = 1;
            foreach ($turmasIdNome as $id_turma => $n_turma) {
                ?>
                <tr>
                    <td style="padding-top: 65px !important; text-align: center">
                        <form action="<?= HOME_URI ?>/<?= $_SESSION['userdata']['arquivo'] ?>/controDiarioTurma" method="POST">
                            <?=
                            formErp::hidden([
                                'id_inst' => $id_inst,
                                'mes' => $mes,
                                'id_pl' => $id_pl,
                                'ano' => $ano,
                                'id_turma' => $id_turma,
                                'n_turma' => $n_turma
                            ])
                            ?>
                            <button class="btn btn-info">
                                <?= $n_turma ?>
                            </button>
                        </form>
                    </td>
                    <?php
                    if (!empty($diasLetivo)) {
                        foreach (range(1, $diaMes) as $d) {
                            $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
                            if (!in_array($sem, [0, 6]) && in_array($ano . '-' . $mes . '-' . str_pad($d, 2, "0", STR_PAD_LEFT), $diasLetivo)) {
                                $d = str_pad($d, 2, "0", STR_PAD_LEFT);
                                ?>
                                <td>
                                    <p>
                                        <?php
                                        if (!empty(@$teveAula[$id_turma][$d])) {
                                            if (count(@$teveAula[$id_turma][$d]) >= $aulasTurma[$id_turma]) {
                                                ?>
                                            <div class="azul">
                                                CH
                                                <br />
                                                <?= $aulasTurma[$id_turma] ?> | <?= $aulasTurma[$id_turma] ?>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="vermelho">
                                                CH
                                                <br />
                                                <?= count(@$teveAula[$id_turma][$d]) ?> | <?= $aulasTurma[$id_turma] ?>
                                            </div>
                                            <?php
                                        }
                                    } elseif (($d <= $hoje && $mes == $esteMes) || ($mes < $esteMes)) {
                                        ?>
                                        <div class="vermelho">
                                            CH
                                            <br />
                                            <?php
                                            if (!empty($aulasTurma[$id_turma])) {
                                                ?>
                                                0 | <?= $aulasTurma[$id_turma] ?>
                                                <?php
                                            } else {
                                                echo 'NC';
                                            }
                                            ?>

                                        </div>
                                        <?php
                                    }
                                    ?>
                                    </p>
                                    <?php
                                    if (in_array($turmaCurso[$id_turma], [1, 5, 9])) {
                                        ?>
                                        <p>
                                            <?php
                                            if (!empty($turmaDiaDisc[$id_turma][$sem])) {
                                                $discDia = count($turmaDiaDisc[$id_turma][$sem]);
                                            } else {
                                                $discDia = 0;
                                            }
                                            if (!empty($turmaDiaDiscTem[$id_turma][$d])) {
                                                $discDiaTem = count($turmaDiaDiscTem[$id_turma][$d]);

                                                if ($discDiaTem >= $discDia) {
                                                    ?>
                                                <div class="azul" >
                                                    CT
                                                    <br />
                                                    <?= $discDiaTem ?> | <?= $discDia ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="vermelho">
                                                    CT
                                                    <br />
                                                    <?= $discDiaTem ?> | <?= $discDia ?>
                                                </div>
                                                <?php
                                            }
                                        } elseif (($d <= $hoje && $mes == $esteMes) || ($mes < $esteMes)) {
                                            ?>
                                            <div class="vermelho">
                                                CT
                                                <br />
                                                0 | <?= intval(@$discDia) ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        </p>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                        }
                    }
                    ?>
                </tr>
                <?php
                $cor++;
            }
            ?>
        </table
        <?php
    }
    ?>
</div>