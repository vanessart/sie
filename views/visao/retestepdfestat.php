<?php
ob_start();

if (!empty($_POST)){
    $idescola = $_POST['idinst'];
    $p = $_POST['periodo'];
}else{
    $idescola = tool::id_inst();
    $p = date('Y');
}

?>
<head>
    <style>      
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 1px;
            padding: 2px;

        }
        .topo2{
            font-size: 8pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 1px;
            padding: 2px;

        }
    </style>
</head>
<?php
$escola = sql::get('instancia', 'n_inst', ['id_inst' => $idescola], 'fetch');
?>
<div style="font-weight: bold; font-size: 9pt; padding-left: 5px; border-style: solid; border-width: 1px; text-align: left">
    <?php echo $escola['n_inst'] ?>
</div>
<div style="font-weight:bold; font-size:9pt; background-color: #000000; color:#ffffff; text-align: center">RESULTADOS DO RETESTE - <?php echo $p ?></div>
<?php
$resac = $model->resultadoreteste($idescola, $p);

if (!empty($resac)) {
    $idclasse = $model->pegaclassevisao($idescola, $p);
    // Não mudei o nome da variavel p não sair alternado
    $id_escola = $idescola;
    ?>
    <table style="width: 100%" class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td rowspan="2" style="width: 10%" class="topo">
                    Código Classe
                </td>
                <td rowspan="2" style="width: 10%" class="topo">
                    Frequente
                </td>
                <td colspan="2" style="width: 10%" class="topo">
                    Reteste
                </td>
                <td colspan="2" style="width: 10%" class="topo">
                    Óculos
                </td>
                <td colspan="3" style="width: 20%" class="topo">
                    Situação Reteste
                </td>
                <td colspan="2" style="width: 10%" class="topo">
                    Oftalmologista
                </td>
                <td colspan="2" style="width: 15%" class="topo">
                    Cartão
                </td>
                <td colspan="2" style="width: 15%" class="topo">
                    Sinais Reteste
                </td>
            </tr>
            <tr>
                <td style="width: 5%" class="topo">
                    Sim
                </td>
                <td style="width: 5%" class="topo"> 
                    Não
                </td>
                <td style="width: 5%" class="topo">
                    Sim
                </td>
                <td style="width: 5%" class="topo"> 
                    Não
                </td>
                <td style="width: 5%" class="topo">
                    PASSA
                </td>
                <td style="width: 5%" class="topo">
                    FALHA
                </td>
                <td style="width: 10%" class="topo">
                    Não Realizado
                </td>
                <td style="width: 5%" class="topo">
                    Sim
                </td>
                <td style="width: 5%" class="topo">
                    Não
                </td>
                <td style="width: 5%" class="topo">
                    Sim
                </td>
                <td style="width: 5%" class="topo">
                    Não
                </td>
                <td style="width: 5%" class="topo">
                    Sim
                </td>
                <td style="width: 5%" class="topo">
                    Não
                </td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style="color: red" class="topo">
                    Total
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Frequentereac'][$id_escola]) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Retesteac']['Sim']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Retesteac']['Não']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Oculosreac']['Sim']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Oculosreac']['Não']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Situacaoreac']['PASSA']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Situacaoreac']['FALHA']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Situacaoreac']['Não Submetido']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Oftalmologistaac']['Sim']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Oftalmologistaac']['Não']) ?>
                </td>
                <td class="topo"> 
                    <?php echo intval($resac[$id_escola]['Cartaoac']['Sim']) ?>
                </td>
                <td class="topo"> 
                    <?php echo intval($resac[$id_escola]['Cartaoac']['Não']) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][2] + $resac[$id_escola]['Sinaisreac'][3] + $resac[$id_escola]['Sinaisreac'][4] + $resac[$id_escola]['Sinaisreac'][5] + $resac[$id_escola]['Sinaisreac'][6] + $resac[$id_escola]['Sinaisreac'][7] + $resac[$id_escola]['Sinaisreac'][8]) ?>
                </td>
                <td style="color: red" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][1]) ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            foreach ($idclasse as $key => $v) {
                ?>
                <tr>
                    <td class="topo">
                        <?php echo $v ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Frequentere'][$id_escola]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Reteste']['Sim']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Reteste']['Não']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Oculosre']['Sim']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Oculosre']['Não']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Situacaore']['PASSA']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Situacaore']['FALHA']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Situacaore']['Não Submetido']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Oftalmologista']['Sim']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Oftalmologista']['Não']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Cartao']['Sim']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Cartao']['Não']) ?>
                    </td>
                    <td class="topo">
                        <?php echo intval($resac[$key]['Sinaisre'][2] + $resac[$key]['Sinaisre'][3] + $resac[$key]['Sinaisre'][4] + $resac[$key]['Sinaisre'][5] + $resac[$key]['Sinaisre'][6] + $resac[$key]['Sinaisre'][7] + $resac[$key]['Sinaisre'][8]) ?>
                    </td>
                    <td class="topo">
                        <?php echo intval($resac[$key]['Sinaisre'][1]) ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </tbody>

    </table>

    <div style="font-weight:bold; font-size:8pt; background-color: #000000; color:#ffffff; text-align: center">DESCRIÇÃO SINAIS RETESTE</div>
    <table style="width: 100%" class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td style="widht: 10%" class="topo">
                    Código Classe
                </td>
                <td style="width: 11%" class="topo">
                    Não Possui
                </td>
                <td style="width: 11%" class="topo">
                    Ardência
                </td>
                <td style="width: 11%" class="topo">
                    Coceira
                </td>
                <td style="width: 12%" class="topo">
                    Fadiga Visual
                </td>
                <<td style="width: 12%" class="topo">
                    Franzir a Testa
                </td>
                <td style="width: 11%" class="topo">
                    Lacrijamento
                </td>
                <td style="width: 11%" class="topo">
                    Tontura
                </td>
                <td style="width: 11%" class="topo">
                    Outros
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($idclasse as $key => $v) {
                ?>
                <tr>
                    <td class="topo">
                        <?php echo $v ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Sinaisre'][1]) ?>
                    </td>
                    <td class="topo">
                        <?php echo intval($resac[$key]['Sinaisre'][2]) ?>
                    </td>
                    <td class="topo">
                        <?php echo intval($resac[$key]['Sinaisre'][3]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Sinaisre'][4]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($resac[$key]['Sinaisre'][5]) ?>
                    </td>
                    <td class="topo">
                        <?php echo intval($resac[$key]['Sinaisre'][6]) ?>
                    </td>
                    <td class="topo">
                        <?php echo intval($resac[$key]['Sinaisre'][7]) ?>
                    </td>
                    <td class="topo">
                        <?php echo intval($resac[$key]['Sinaisre'][8]) ?>
                    </td>                
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td style="color: red; background-color: #ccffcc" class="topo"> 
                    Total
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo"> 
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][1]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][2]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][3]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][4]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][5]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][6]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][7]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($resac[$id_escola]['Sinaisreac'][8]) ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <?php
} else {
    echo "Não existe dado para relatório!!!!";
}
$model->pdfvisao('L');
?>