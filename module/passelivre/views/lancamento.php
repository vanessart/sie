<?php
if (!defined('ABSPATH'))
    exit;
?>
<head>
    <style>
        .topo{
            font-size: 12pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>

<?php

$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
$cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_NUMBER_INT);

$escolasGeral = $model->escolasGeral();
$st = $model->pegastatus();

if (tool::id_nivel() == '10') {  
    if ($cie) {
        $model->cie = $cie;
        $model->escola = $escolasGeral[$cie];
    }
}elseif (tool::id_nivel() == '8') {
    $cie = $model->cie;
}

if (empty($mes)) {
    $mes = date("n") - 1;
}
$selecao = $model->pegames($mes);
?>
<br />
<div class="body">
    <div style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 8pt">
        Lançamentos de Faltas
    </div>
    <br />
    <div class="row">
        <div class="col-6">
            <?php
            if (tool::id_nivel() == '10') {
                echo formErp::select('cie', $escolasGeral, 'Escola', $cie, 1);
            }
            ?>
        </div>
        <div class="col-6">
            <?= formErp::select('mes', $selecao, 'Selecione o mês', $mes, 1, ['cie' => $model->cie]) ?>
        </div>
    </div>
</div>

<?php

if ((!empty($mes)) && (!empty($cie))) {
    $model->povoatabela($mes, $cie);
    $dados = $model->geralancamento($mes, $cie);

    if (!empty($dados)) {
        ?>
        <div class="body">
            <form method="POST">
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <!--
                            <td rowspan="2" class="topo">
                                ID
                            </td>
                            -->
                            <td rowspan="2" class="topo">
                                RA
                            </td>
                            <td rowspan="2" class="topo">
                                Nome Aluno
                            </td>
                            <td rowspan="2" class="topo">
                                Status
                            </td>
                            <td colspan="2" class="topo">
                                Total Mês
                            </td>                   
                        </tr>
                        <tr>
                            <td class="topo">
                                Dias Letivos
                            </td>
                            <td class="topo">
                                Faltas
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dados as $v) {
                            ?>
                            <tr>
                                <!--
                                <td class="topo">
                                    <?= $v['id_passelivre'] ?>
                                </td>
                                -->
                                <td class="topo">
                                    <?= $v['ra'] ?>
                                </td>
                                <td class="topo" style="text-align:left">
                                    <?= ($v['acompanhante'] == "Sim") ? '*' . $v['nome'] : $v['nome'] ?>
                                </td>
                                <td class="topo">
                                    <?= $st[$v['fk_id_pl_status']] ?>
                                </td>
                                <td class="topo" style="vertical-align: middle">
                                    <input id="<?= $v['id_frequencia'] ?>" type="text" name="<?= $v['id_frequencia'] . '[dias_letivos]' ?>" value="<?= $v['dias_letivos'] ?>" style= "text-align: center" />
                                </td>
                                <td class="topo">
                                    <input id="<?= $v['id_frequencia'] ?>" type='text' name="<?= $v['id_frequencia'] . '[total_faltas]' ?>" value="<?= $v['total_faltas'] ?>" style= "text-align: center" />
                                    <input id="<?= $v['id_frequencia'] ?>" type="hidden" name="<?= $v['id_frequencia'] . '[id_frequencia]' ?>" value="<?= $v['id_frequencia'] ?>" />
                                    <input id="<?= $v['id_frequencia'] ?>" type="hidden" name="<?= $v['id_frequencia'] . '[mes]' ?>" value="<?= $mes ?>" />
                                    <input id="<?= $v['id_frequencia'] ?>" type="hidden" name="<?= $v['id_frequencia'] . '[dt_lanc]' ?>" value="<?= date("Y-m-d") ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>                
                </table>
                <!--
                <footer>
                    <div style="color: Red; font-size: 8pt; font-weight: bold ">
                        * = Acompanhante
                    </div>
                </footer>
                -->
                <br />
                <div style="text-align: center">
                    <input type="hidden" name="cie" value="<?= $cie ?>" />
                    <input type="hidden" name ="mes" value="<?= $mes ?>"/>
                    <input type="hidden" name="gravafalta" value="1" />
                    <input class="btn btn-success"type="submit" value="Salvar" />
                </div>
            </form>
        </div>
        <?php
    } else {
        ?>
        <div class="topo">
            Não Existe Dados para Lançamento
        </div>
        <?php
    }
}
?>
