<?php
if (!defined('ABSPATH'))
    exit;
$hoje = date("d");
$esteMes = date("m");
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma');
if (!$id_inst || !$id_pl || !$mes || !$ano || !$id_turma) {
    die();
}
$n_inst = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
if (!empty(gtTurmas::professores($id_turma))) {
    $profDisc = current(gtTurmas::professores($id_turma));
}
$horario = gtTurmas::horario($id_turma);
$turma = sql::get(['ge_turmas', 'ge_ciclos'], 'n_turma, fk_id_ciclo, periodo, ge_turmas.fk_id_grade, fk_id_pl, fk_id_curso, ge_ciclos.aulas', ['id_turma' => $id_turma], 'fetch');
$grade = gtMain::discGrade($turma['fk_id_grade']);
$diaMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
$mongo = new mongoCrude('Diario');
$diasLetivo = $model->diasLetivo($turma['fk_id_pl'], $turma['fk_id_curso'], $mes);
$ta = $mongo->query('presence_' . $id_pl, ['id_turma' => $id_turma, 'data' => ['$regex' => '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-']], ['projection' => ['aula' => 1, 'id_disc' => 1, 'data' => 1]]);
if ($ta) {
    foreach ($ta as $v) {
        $teveAula[substr($v->data, -2)][$v->aula] = @$grade[$v->id_disc]['sg_disc'];
    }
}
if (in_array($turma['fk_id_curso'], [1, 5, 9])) {

    $conteudo_ = $mongo->query('Aula.' . $id_pl, ['id_turma' => $id_turma, 'data' => ['$in' => $diasLetivo]], ['projection' => ['_id' => 0, 'data' => 1, 'id_disc' => 1]]);
    foreach ($conteudo_ as $v) {
        $conteudo[substr($v->data, -2)][$v->id_disc] = 1;
    }
}
$meses = data::meses();
unset($meses['01']);
$hidden = [
    'id_inst' => $id_inst,
    'mes' => $mes,
    'id_pl' => $id_pl,
    'ano' => $ano,
    'id_turma' => $id_turma,
    'n_turma' => $n_turma
];
if (in_array($turma['fk_id_curso'], [1, 5, 9])) {
    $PlanoTurma = $model->diasPlanoTurma($id_turma, $mes);
}
if (empty($PlanoTurma)) {
    $PlanoTurma = [];
}
?>
<div class="body">
    <div class="fieldTop">
        <?= $n_turma ?> da <?= $n_inst ?>
    </div>
    <div class="row">
        <div class="col-8">
            <?= formErp::select('mes', $meses, 'Mês', $mes, 1, $hidden) ?>
        </div>
        <div class="col-4">
            <form action="<?= HOME_URI ?>/<?= $_SESSION['userdata']['arquivo'] ?>/controDiario" method="POST">
                <?=
                formErp::hidden($hidden)
                ?>
                <button class="btn btn-warning">
                    Voltar
                </button>
            </form>
        </div>
    </div>
    <br />
    <?php
    $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
    ?>
    <style>
        #ho td{
            padding: 3px;
        }
    </style>
    <table id="ho" style="width: 100%">
        <tr>
            <td>
                <div class="border text-center">
                    Data
                </div>
            </td>
            <?php
            foreach (range(1, $turma['aulas']) as $au) {
                ?>
                <td>
                    <div class="border text-center">
                        <?= $au ?>ª Aula
                    </div>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
        foreach (range(1, $diaMes) as $d) {
            $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
            if (!in_array($sem, [0, 6]) && in_array($ano . '-' . $mes . '-' . str_pad($d, 2, "0", STR_PAD_LEFT), $diasLetivo)) {
                ?>
                <tr>
                    <td>
                        <div class="border" style="text-align: center; height: 150px; padding-top: 30px; font-size: 1.4em">
                            <p>
                                <?= $d ?>/<?= $mes ?>
                            </p>
                            <p>
                                <?= dataErp::diasDaSemana($sem) ?>
                            </p>
                        </div>
                    </td>
                    <?php
                    foreach (range(1, $turma['aulas']) as $au) {
                        if (!in_array($sem, [0, 6]) && in_array($ano . '-' . $mes . '-' . str_pad($d, 2, "0", STR_PAD_LEFT), $diasLetivo)) {
                            $d = str_pad($d, 2, "0", STR_PAD_LEFT);
                            if (!empty($teveAula[$d][$au])) {
                                $img = 's';
                                $alt = 'Sim';
                            } else {
                                $alt = 'Não';
                                $img = 'n';
                            }
                            if (in_array($turma['fk_id_curso'], [1, 5, 9])) {
                                if (!empty($conteudo[$d][@$horario[$sem][$au]])) {
                                    $img2 = 's';
                                    $alt2 = 'Sim';
                                } else {
                                    $alt2 = 'Não';
                                    $img2 = 'n';
                                }
                                if (!empty($PlanoTurma[@$horario[$sem][$au]])) {
                                    if (in_array($d, $PlanoTurma[@$horario[$sem][$au]])) {
                                        $img3 = 's';
                                        $alt3 = 'Sim';
                                    } else {
                                        $alt3 = 'Não';
                                        $img3 = 'a';
                                    }
                                } else {
                                    $alt3 = 'Não';
                                    $img3 = 'a';
                                }
                            }
                            ?>
                            <td>
                                <div class="border" style="min-height: 150px">
                                    <p>
                                        <?= @$grade[@$horario[$sem][$au]]['n_disc'] ?>
                                    </p>
                                    <?php
                                    if (($d <= $hoje && $mes == $esteMes) || ($mes < $esteMes)) {
                                        ?>
                                        <p style="white-space: nowrap">
                                            <img src="<?= HOME_URI ?>/includes/images/<?= $img ?>.png" width="16" height="16" alt="<?= $alt ?>"/> Chamada
                                        </p>
                                        <?php
                                    }
                                    if (in_array($turma['fk_id_curso'], [1, 5, 9])) {
                                        if (($d <= $hoje && $mes == $esteMes) || ($mes < $esteMes)) {
                                            ?>
                                            <p style="white-space: nowrap">
                                                <img src="<?= HOME_URI ?>/includes/images/<?= $img2 ?>.png" width="16" height="16" alt="<?= $alt2 ?>"/> Conteúdo
                                            </p>
                                            <?php
                                        }
                                        ?>
                                        <p style="white-space: nowrap">
                                            <img src="<?= HOME_URI ?>/includes/images/<?= $img3 ?>.png" width="16" height="16" alt="<?= $alt3 ?>"/> Plano de Aula
                                        </p>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </td>
                            <?php
                        }
                    }
                    ?>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>