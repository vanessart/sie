<?php
if (!defined('ABSPATH'))
    exit;

if (($model->_setup['abrir_falta'] == 1) || user::session('id_nivel') == 10) {
    $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);

    if (user::session('id_nivel') != 10) {
        $id_inst = tool::id_inst();
    } else {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    }
    
    $mes_sel = data::meses();

    $mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);

    if (empty($mes)) {
        $mes = date("m");
    }
    $ano = date("Y");
    $dia = filter_input(INPUT_POST, 'dia', FILTER_SANITIZE_NUMBER_INT);
    
    if (empty($dia)) {
        $dia = date("d");
    }
    $dias = data::diasUteis($mes, $ano);
    $feriado = data::feriadoMes(str_pad($mes, 2, "0", STR_PAD_LEFT));
    $igual = [];
    if (!empty($feriado['EI'])) {
        foreach ($feriado['EI'] as $v) {
            if (!empty($feriado['EF'][$v])) {
                $igual[$v] = $v;
            }
        }
    }
    $dataSet = $ano . '-' . str_pad($mes, 2, "0", STR_PAD_LEFT) . '-' . str_pad($dia, 2, "0", STR_PAD_LEFT);
    foreach ($dias as $k => $v) {
        if ($mes != date("m")) {
            if (in_array($v, $igual)) {
                unset($dias[$k]);
            }
        } elseif (in_array($v, $igual) || $v > date("d")) {
            unset($dias[$k]);
        }
    }
    if (!empty($id_li)) {
        $linhaDados = transporte::linhaGet($id_li);
        $alunos = transporte::LinhaAlunos($id_li, NULL, '0,2,6');

        $id_alus = array_column($alunos, 'id_alu');

        $fatas = $model->faltaDia($id_alus, $dataSet);
    }
    $justifica = sql::get('transp_falta_justifica', '*', ['fk_id_li' => $id_li, 'dt_falta' => $dataSet], 'fetch');
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            Controle de Faltas - Meses Anteriores
        </div>
        <br />
        <div class="row">
            <?php
            if (user::session('id_nivel') == 10) {
                ?>
                <div class="col-sm-6">
                    <?php echo form::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1) ?>
                    <br /><br />
                </div>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <?php               
                    echo form::select('mes', $mes_sel, 'Mês', $mes, 1);               
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <form method="POST">
                <div class="col-sm-5">
                    <?php
                    if (!empty($id_inst)) {

                        $transLinha = transporte::nomeLinha($id_inst);

                        if (!empty($transLinha)) {
                            echo form::select('id_li', $transLinha, 'Linha', $id_li);
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
                <div class="col-sm-4">
                    <?php
                    echo form::select('dia', $dias, 'Dia', $dia)
                    ?>
                </div>
                <div class="col-sm-2">
                    <input type="hidden" name="mes" value="<?php echo $mes ?>" />
                    <?php
                    echo form::hidden(['id_inst' => $id_inst]);
                    echo form::button('Continuar');
                    ?>
                </div>
            </form>
        </div>
        <br />
        <?php
        if (!empty($id_li) && !empty($mes) && !empty($dia)) {
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
                                    <?php echo $linhaDados['periodo'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Acessibilidade
                                </td>
                                <td>
                                    <?php echo tool::simnao($linhaDados['acessibilidade']) ?>
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

                        if (($dia != date("d") && ($dia + 1) != date("d")) || $mes != date("m")) {
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
                        <td>
                            Faltou 
                            <br />
                            <input type="checkbox" name="chkAll" onClick="checkAll(this)" /> todos
                        </td>
                    </tr>
                    <?php
                    $ct = 1;
                    foreach ($alunos as $v) {
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
                            <td>
                                <?php
                                if (in_array($v['fk_id_ciclo'], [19, 20, 21, 22, 23, 24])) {
                                    $tipoEnsino = "EI";
                                } else {
                                    $tipoEnsino = "EF";
                                }
                                if (empty($feriado[$tipoEnsino][str_pad($dia, 2, "0", STR_PAD_LEFT)])) {
                                    ?>
                                    <input <?php echo (!empty($fatas[$v['id_alu']]) ? 'checked' : '') ?> type="checkbox" name="alu[<?php echo $v['id_alu'] ?>]" value="<?php echo @$fatas[$v['id_alu']] ?>" />
                                    <?php
                                } else {
                                    ?>
                                    Sem Aula
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
                echo form::hidden([
                    'id_inst' => $id_inst,
                    'id_li' => $id_li,
                    'dia' => $dia,
                    'mes' => $mes,
                    'id_fj' => @$justifica['id_fj']
                ]);

                echo DB::hiddenKey('lancaFalta')
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
    <?php
} else {
   ?>
<div class="alert alert-danger text-center">
    Lançamento Fechado
</div>
        <?php 
}
?>
<script>
    function checkAll(o) {
        var boxes = document.getElementsByTagName("input");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }
</script>