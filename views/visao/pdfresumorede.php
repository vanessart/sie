<?php
ob_start();
$cor = '#cccccc';
if (!empty($_POST['periodo'])) {
    $per =filter_input(INPUT_POST, 'periodo', FILTER_UNSAFE_RAW);
} else {
    $per = date('Y');
}

?>
<head>
    <style>      
        .topo{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding: 2px;

        }
        .topocab{
            font-size: 8pt;
            color: red;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding: 2px;
        }

    </style>
</head>

<?php
$atztabela = $model->situacaoescolarede($per);
$dados = sql::get(['cv_resumo_visao', 'instancia'], '*', ['periodo_letivo' => $per, '>' => 'n_inst']);

$res = $model->wresumo('Relatorio', $per);
$seq = 1;
?>

<table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
    <thead>
        <tr>
            <td style="font-weight:bold; font-size: 9pt; background-color: #000000; color:#ffffff; text-align: center" colspan="11">
                Relatório de Resultados do Teste e Reteste - <?php echo $per ?>
            </td>
        </tr>
        <tr>
            <td rowspan="2" style="width: 5%" class="topo">
                Seq.
            </td>
            <td rowspan="2" style="width: 32%" class="topo">
                Nome Escola
            </td>
            <td rowspan="2" style="width: 7%" class="topo">
                Frequente
            </td>
            <td colspan="3" style="width: 21%" class="topo">
                Teste Situação
            </td>
            <td colspan="3" style="width: 21%" class="topo">
                Reteste Situação
            </td>
            <td colspan="2" style="width: 14%" class="topo">
                Encaminhamento Oftalmologista
            </td>
        </tr>
        <tr>
            <td style="width: 7%" class="topo">
                PASSA
            </td>
            <td style="width: 7%" class="topo">
                FALHA
            </td>
            <td style="width: 7%" class="topo">
                N.S.
            </td>
            <td style="width: 7%" class="topo">
                PASSA
            </td>
            <td style="width: 7%" class="topo">
                FALHA
            </td>
            <td style="width: 7%" class="topo">
                N.S.
            </td>
            <td style="width: 7%" class="topo">
                Sim
            </td>
            <td style="width: 7%" class="topo">
                Não
            </td>        
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($dados as $v) {
            ?>
            <tr>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo $seq++ ?>
                </td>
                <td style="text-align: left; background-color: <?php echo $cor ?>" class="topo">
                    <?php echo $v['n_inst'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['frequente']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['teste_p']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['teste_f']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['teste_nr']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['reteste_p']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['reteste_f']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['reteste_nr']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['encaminhamento_s']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>" class="topo">
                    <?php echo intval($v['encaminhamento_n']) ?>
                    <?php $cor = ($cor == '#cccccc') ? $cor = '#FAFAFA' : $cor = '#cccccc' ?>                
                </td>
            </tr>
        </tbody>

        <?php
    }
    ?>
    <tfoot>
        <tr>      
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px" colspan="2">
                Total
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['f']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['tp']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['tf']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['tnr']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['rtp']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['rtf']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['rtnr']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['es']) ?>
            </td>
            <td style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                <?php echo intval($res['en']) ?>
            </td>
        </tr>
    </tfoot>
</table>

<?php
$model->pdfvisao('L');
?>