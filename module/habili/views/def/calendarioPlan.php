<style>
    .inteiro{
        color: white; 
        border-radius: 10px; 
        height: 40px; 
        padding: 5px; 
        font-weight: bold; 
        text-align: center ;
    }
    .comeca{
        color: white; 
        border-radius: 10px 0 0 10px; 
        height: 40px; 
        padding: 5px; 
        font-weight: bold; 
        text-align: center ;
    }
    .termina{
        color: white; 
        border-radius: 0 10px 10px 0;
        height: 40px; 
        padding: 5px; 
        font-weight: bold; 
        text-align: center ;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
if (empty($mes)) {
    $mes = date("m");
}
$ano = sql::get('ge_periodo_letivo', 'ano', ['at_pl'=>1], 'fetch')['ano'];
//$ano = str_pad(date("Y"), 2, "0", STR_PAD_LEFT);
$mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
$diaMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
$tpd = $model->turmaDisciplina($id_pessoa);
$plan = $model->planoProfMes($tpd, $ano, $mes);
?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <td>
            Turma
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
    $cor=1;
    foreach ($tpd as $kg => $v) {
        if (!is_numeric($kg)) {
            $e= str_replace(' - ', ' ', $v['escola']);
            $esc = explode(' ', $e);
            $escola = $esc[1] . ' ' . @$esc[2] . ' ' . @$esc[3];
            ?>
            <tr>
                <td>
                    <?= $v['nome_turma'] ?> - <?= $v['nome_disc'] ?>
                    <br />
                    <?= $escola ?>
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
        }
        $cor++;
    }
    ?>
</table>
<?php
