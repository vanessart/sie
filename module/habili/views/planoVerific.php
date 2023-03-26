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

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
$ano = str_pad(date("Y"), 2, "0", STR_PAD_LEFT);
if (empty($mes)) {
    $mes = date("m");
}
$turmas = ng_escola::turmasSegAtiva(toolErp::id_inst());
if (!empty($id_turma)) {
    $profDisc = current(gtTurmas::professores($id_turma));
    $turma = sql::get('ge_turmas', 'n_turma, fk_id_ciclo, periodo, fk_id_grade', ['id_turma' => $id_turma], 'fetch');
    $grade = gtMain::discGrade($turma['fk_id_grade']);
    $plan = $model->planoTurmaMes($id_turma, $ano, $mes);
    $diaMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
}
?>
<div class="body">
    <div class="fieldTop">
        Plano de Aula
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
            foreach ($grade as $kg => $g) {
                ?>
                <tr>
                    <td>
                        <?= $g['n_disc'] ?>
                        <br />
                        <?= @$profDisc[$kg]['abrev'] ?>
                    </td>
                    <?php
                    $dias = 0;
                    foreach (range(1, $diaMes) as $d) {
                        $sem = date("w", mktime(0, 0, 0, $mes, $d, $ano));
                        $d = str_pad($d, 2, "0", STR_PAD_LEFT);
                        if (!empty($plan[$kg]['i'])) {
                            $pa = $plan[$kg]['i'];
                            unset($plan[$kg]['i']);
                            $class = "termina";
                            $dias = explode('-', $pa['dt_fim'])[2];
                            ?>
                            <td colspan="<?= $dias ?>" <?= in_array($sem, [0, 6]) ? 'style="background-color: pink"' : '' ?>>
                                <div class="<?= $class ?>" style="background-color: <?= toolErp::cor($cor) ?>">
                                    <?= $pa['dias'] ?> Dias
                                </div>
                            </td>
                            <?php
                        } elseif (!empty($plan[$kg][$ano . '-' . $mes . '-' . $d])) {
                            $pa = $plan[$kg][$ano . '-' . $mes . '-' . $d];
                            $dias = dataErp::diferencaDias($pa['dt_inicio'], $pa['dt_fim']) + 1;
                            if (($d + $dias) > $diaMes) {
                                $class = "comeca";
                            } else {
                                $class = "inteiro";
                            }
                            ?>
                            <td colspan="<?= $dias ?>" <?= in_array($sem, [0, 6]) ? 'style="background-color: pink"' : '' ?>>
                                <div class="<?= $class ?>" style="background-color: <?= toolErp::cor($cor) ?>">
                                    <?= $pa['dias'] ?> Dias
                                </div>
                            </td>
                            <?php
                        } elseif ($dias < 1) {
                            ?>
                            <td <?= in_array($sem, [0, 6]) ? 'style="background-color: pink"' : '' ?>></td>
                            <?php
                        }
                        $dias--;
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
