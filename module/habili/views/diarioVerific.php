<style>
    .inteiro{
        color: white;
        border-radius: 10px;
        height: 30px;
        padding: 5px;
        font-weight: bold;
        text-align: center ;
    }
    .comeca{
        color: white;
        border-radius: 10px 0 0 10px;
        height: 30px;
        padding: 5px;
        font-weight: bold;
        text-align: center ;
    }
    .termina{
        color: white;
        border-radius: 0 10px 10px 0;
        height: 30px;
        padding: 5px;
        font-weight: bold;
        text-align: center ;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
$ano = str_pad(date("Y"), 2, "0", STR_PAD_LEFT);
if (empty($mes)) {
    $mes = date("m");
}
$turmas = ng_escola::turmasSegAtiva($id_inst);

if (!empty($id_turma)) {
    if (!empty(gtTurmas::professores($id_turma))) {
        $profDisc = current(gtTurmas::professores($id_turma));
    }
    $horario = gtTurmas::horario($id_turma);
    $turma = sql::get('ge_turmas', 'n_turma, fk_id_ciclo, periodo, fk_id_grade, fk_id_pl', ['id_turma' => $id_turma], 'fetch');
    $grade = gtMain::discGrade($turma['fk_id_grade']);
    $diaMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    $mongo = new mongoCrude('Diario');
    $ta = $mongo->query('presence_' . $turma['fk_id_pl'], ['id_turma' => $id_turma, 'data' => ['$regex' => '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-']], ['projection' => ['aula' => 1, 'id_disc' => 1, 'data' => 1]]);
    if ($ta) {
        foreach ($ta as $v) {
            $teveAula[substr($v->data, -2)][$v->aula] = @$grade[$v->id_disc]['sg_disc'];
        }
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Controle do Diário por Calendário
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('mes', data::meses(), 'Mês', $mes, 1, ['id_turma' => $id_turma]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1, ['mes' => $mes]) ?>
        </div>
    </div>
    <br /><br />
    <?php
    if ($id_turma) {
        $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Disciplina
                </td>
                <?php
                foreach (range(1, $diaMes) as $d) {
                    $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
                    ?>
                    <td <?= in_array($sem, [0, 6]) ? 'style="background-color: pink"' : '' ?>>
                        <?= $d ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td></td>
                <?php
                foreach (range(1, $diaMes) as $d) {
                    $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
                    ?>
                    <td <?= in_array($sem, [0, 6]) ? 'style="background-color: pink"' : '' ?>>
                        <?= dataErp::diasDaSemana($sem, 1) ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            $cor = 1;
            foreach (range(1, 5) as $au) {
                ?>
                <tr>
                    <td>
                        <?= $au ?>
                    </td>
                    <?php
                    foreach (range(1, $diaMes) as $d) {
                        $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
                        $d = str_pad($d, 2, "0", STR_PAD_LEFT);
                        if (!empty($teveAula[$d][$au])) {
                            ?>
                            <td><span style="color: blue; font-weight: bold" ><?= $teveAula[$d][$au] ?></span></td>
                                <?php
                            } else {
                                ?>
                            <td <?= in_array($sem, [0, 6]) ? 'style="background-color: pink"' : '' ?>><span <?= in_array($sem, [1, 2, 3, 4, 5]) ? 'style="color: red; font-weight: bold"' : '' ?> ><?= @$grade[@$horario[$sem][$au]]['sg_disc'] ?></span></td>
                            <?php
                        }
                    }
                    ?>
                </tr>
                <?php
                $cor++;
            }
            ?>
        </table>
        <?php
    }
    ?>
</div>
