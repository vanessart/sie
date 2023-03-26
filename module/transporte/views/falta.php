<?php
if (!defined('ABSPATH'))
    exit;

$abrirFalta = $model->abrirFalta();
if (!empty($abrirFalta)) {
?>
    <div class="alert alert-danger" style="text-align: center; font-weight: bold;font-size: 18px">
        <?php echo $abrirFalta ?>
    </div>
    <?php
    return;
}

$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);

if (user::session('id_nivel') != 10) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}

$semana = filter_input(INPUT_POST, 'semana', FILTER_SANITIZE_STRING);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
if (empty($mes)) {
    $mes = date("m");
}
$ano = date("Y");
$dia = filter_input(INPUT_POST, 'dia', FILTER_SANITIZE_NUMBER_INT);
if (empty($dia)) {
    $dia = date("d");
}
$dias = dataErp::diasUteis($mes, null, $ano, 'N');

$rDias = transporteErp::trataDias($mes, $dias);
$dias = $rDias['dias'];
$feriado[$mes] = $rDias['feriado'];

foreach ($dias as $k => $v) {
    $d = $ano . '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-' . str_pad($k, 2, "0", STR_PAD_LEFT);
    $d = DateTime::createFromFormat('Y-m-d', $d);
    $dias[$k] = $k .' - '. dataErp::diasDaSemana($d->format('w'), 3);
}

$dtIni = $ano . '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-' . str_pad(min(array_keys($dias)), 2, "0", STR_PAD_LEFT);
$dtFim = $ano . '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-' . str_pad(max(array_keys($dias)), 2, "0", STR_PAD_LEFT);
$semanas = dataErp::semanas($dtIni, $dtFim);

$week = null;
$year = null;
if (!empty($semana)) {
    $week = explode('-', $semana)[0];
    $year = explode('-', $semana)[1];
    $dataSet = dataErp::dataPorSemana($week, $year)["data_fim"];
} else {
    $dataSet = $ano . '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-' . str_pad($dia, 2, "0", STR_PAD_LEFT);
}

if (!empty($id_li)) {
    $linhaDados = transporteErp::linhaGet($id_li);
    $alunos = transporteErp::LinhaAlunos($id_li, NULL, '0,2,6', null, $dataSet);

    $id_alus = array_column($alunos, 'id_alu');
}
$justifica = sqlErp::get('transporte_falta_justifica', '*', ['fk_id_li' => $id_li, 'dt_falta' => $dataSet], 'fetch');
?>
<div class="body">
    <div class="fieldTop">
        Controle de Faltas
    </div>
    <br />
    <div class="row">
        <?php
        if (user::session('id_nivel') == 10) {
            ?>
            <div class="col-sm-6">
                <?php echo formErp::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1) ?>
                <br /><br />
            </div>
            <?php
        }
        ?>
    </div>
    <?php //if (date("d") <= 5) { ?>
    <div class="row">
        <div class="col-sm-6">
            <?php
                echo formErp::select('mes', [date("m") => dataErp::meses(date("m")), (date("m") - 1) => dataErp::meses(date("m") - 1)], 'Mês', $mes, 1);
            ?>
        </div>
    </div>
    <br /><br />
    <?php //} ?>
    <div >
        <form method="POST" class="row">
            <div class="col-sm-5">
                <?php
                if (!empty($id_inst)) {

                    $transLinha = transporteErp::nomeLinha($id_inst);

                    if (!empty($transLinha)) {
                        echo formErp::select('id_li', $transLinha, 'Linha', $id_li);
                    } else {
                        ?>
                        <div class="alert alert-danger">
                            Não há ônibus alocado nesta Escola
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="col-sm-2">
                <?php
                echo formErp::select('dia', $dias, 'Dia', $dia);
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo formErp::select('semana', $semanas, 'Semana', $semana);
                ?>
            </div>
            <div class="col-sm-2">
                <input type="hidden" name="mes" value="<?php echo $mes ?>" />
                <?php
                echo formErp::hidden(['id_inst' => $id_inst]);
                echo formErp::button('Continuar');
                ?>
            </div>
        </form>
    </div>
    <br /><br />
    <?php
    if (!empty($id_li) && !empty($mes) && (!empty($dia) || !empty($semana)))
    {
        if (!empty($semana)) {
            $diasSemana = dataErp::dataPorSemana($week, $year);
        } elseif (!empty($dia)){
            $date = new DateTime($dataSet);
            $diasSemana = dataErp::dataPorSemana($date->format("W"), $year);
        }

        $lDias = dataErp::DiasUteisPorPeriodo($diasSemana["data_inicio"], $diasSemana["data_fim"], null, false);
        $faltas = $model->faltaDia($id_alus, $diasSemana["data_inicio"], $diasSemana["data_fim"]);
        ?>
        <form method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-bordered table-hover table-responsive table-striped">
                        <tr>
                            <td>
                                Linha
                            </td>
                            <td>
                                <?php echo $linhaDados['n_li'] ?> (Viagem: <?php echo $linhaDados['viagem'] ?>)
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Motorista 
                            </td>
                            <td>
                                <?php echo $linhaDados['motorista'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Período
                            </td>
                            <td>
                                <?php echo dataErp::periodoDoDia($linhaDados['periodo']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Acessibilidade
                            </td>
                            <td>
                                <?php echo toolErp::simnao($linhaDados['acessibilidade']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Monitor
                            </td>
                            <td>
                                <?php echo $linhaDados['monitor'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Abrangência
                            </td>
                            <td>
                                <?php echo $linhaDados['abrangencia'] ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo "<!--";
                    if (empty(@$justifica['dt_falta'])) {
                        ?>
                        <div class="alert alert-danger text-center">
                            Nao Lançado
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="alert alert-info text-center">
                            Lançado
                        </div>
                        <?php
                    }
                    echo "-->";

                    if ((!empty($dia) && $dia != date("d") && ($dia + 1) != date("d")) || $mes != date("m") || (!empty($week) && date($week) != date('W'))) {
                        ?>
                        <div class="fieldBorder2">
                            Por favor Justifique o atraso no lançamento das faltas
                            <textarea name="justificativa" required style="width: 100%" ><?php echo @$justifica['justificativa'] ?></textarea>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <table class="table table-bordered table-hover table-responsive table-striped">
                <tr>
                    <td>
                        Nº
                    </td>
                    <td>
                        RSE
                    </td>
                    <td>
                        Nome
                    </td>
                    <td>
                        Turma
                    </td>
                    <td align="center">
                        Faltou 
                        <br /><br /><br />
                        <label for="chkAll" style="cursor: pointer;">
                            <input type="checkbox" name="chkAll" id="chkAll" onclick="checkAll(this, 'chkall')" style="cursor: pointer;" /> todos
                        </label>
                    </td>
                    <?php foreach ($lDias as $kd => $vd) { ?>
                    <td align="center">
                        <?php 
                        echo $vd['dia'].'/'.$vd['mes'] .' <br /> '. $vd['semana'].' <br /><br /> ';

                        if (!empty($vd['feriado'])) { ?>
                            Sem Aula 
                        <?php } else { ?>
                            <label for="chkDay-<?= $kd ?>" style="cursor: pointer;">
                                <input type="checkbox" name="chkDay" id="chkDay-<?= $kd ?>" onclick="checkAll(this, 'dia-<?= $kd ?>')" class="chkall" style="cursor: pointer;" /> todos
                            </label>
                            <input type="hidden" name="dias[<?= $kd ?>]" value="<?= $kd ?>" />
                        <?php } ?>
                    </td>    
                    <?php } ?>
                </tr>
                <?php
                $ct = 1;
                foreach ($alunos as $v) {
                    $tipoEnsino = transporteErp::getTipoEnsino($v['fk_id_ciclo']);
                    $diasFalta = !empty($faltas[$v['id_alu']]) ? array_column($faltas[$v['id_alu']], 'dt_falta') : [];
                    ?>
                    <tr>
                        <td>
                            <?php echo $ct++ ?>
                        </td>
                        <td>
                            <?php echo $v['id_pessoa'] ?>
                        </td>
                        <td>
                            <?php echo $v['n_pessoa'] ?>
                        </td>
                        <td>
                            <?php echo $v['n_turma'] ?>
                        </td>
                        <td align="center">
                            <label for="td-alu-<?= $v['id_alu'] ?>" style="cursor: pointer;">
                                <input type="checkbox" name="chk-alu[<?php echo $v['id_alu'] ?>]" class="chkall td-alu-<?= $v['id_alu'] ?>" id="td-alu-<?= $v['id_alu'] ?>" onclick="checkAll(this, 'aluno-<?= $v['id_alu'] ?>')" style="cursor: pointer;" />
                            </label>
                        </td>
                        <?php foreach ($lDias as $kd => $vd) { ?>
                        <td align="center">
                            <?php
                            if (!empty($tipoEnsino) && isset($vd['feriadoTipEnsino'][$tipoEnsino])) {
                                ?>
                                Sem Aula
                                <?php
                            } else {
                                $f = (!empty($diasFalta) && array_search($kd, $diasFalta) !== false);
                                $id_falta = $f ? $faltas[$v['id_alu']][array_search($kd, $diasFalta)]["id_falta"] : '';
                                ?>
                                <label for="chkAlu-<?= $v['id_alu'] .'-'. $kd ?>" style="cursor: pointer;">
                                    <input <?php echo ($f ? 'checked' : '') ?> type="checkbox" name="alu[<?php echo $v['id_alu'] ?>][<?php echo $kd ?>]" id="chkAlu-<?= $v['id_alu'] .'-'. $kd ?>" value="<?php echo $id_falta ?>" class="chkall aluno-<?= $v['id_alu'] ?> dia-<?= $kd ?>" style="cursor: pointer;" />
                                </label>
                                <?php
                            }
                            ?>
                        </td>    
                        <?php } ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
            echo formErp::hidden([
                'id_inst' => $id_inst,
                'id_li' => $id_li,
                'dia' => $dia,
                'mes' => $mes,
                'semana' => $semana,
                'id_fj' => @$justifica['id_fj']
            ]);

            echo formErp::hiddenToken('lancaFalta')
            ?>
            <div style="text-align: center">
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </form>
        <br /><br />
        <?php
    }
    ?>
</div>
<script>
    function checkAll(o, cl) {
        $('.'+ cl).prop('checked', o.checked);
    }
</script>