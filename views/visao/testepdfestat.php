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
$escola = sql::get('instancia', 'n_inst', ['id_inst' => @$idescola], 'fetch');
?>
<div style="font-weight: bold; font-size: 9pt; padding-left: 5px; border-style: solid; border-width: 1px; text-align: left">
    <?php echo $escola['n_inst'] ?>
</div>
<div style="font-weight:bold; font-size: 9pt; background-color: #000000; color:#ffffff; text-align: center">RESULTADOS DO TESTE NO COMPUTADOR - <?php echo  $p ?></div>
<?php

$res = $model->resultadoteste($idescola, $p);

if (!empty($res)) {
    $idclasse = $model->pegaclassevisao($idescola, $p);
    $id_escola = $idescola;
    ?>
    <table style="width: 100%" class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td rowspan="2" style="width: 10%" class="topo">
                    Cód.Classe
                </td>
                <td rowspan="2" style="width: 6%" class="topo">
                    Frequente
                </td>
                <td colspan="2" style="width: 10%" class="topo">
                    Óculos
                </td>
                <td colspan="3" style="width: 19%" class="topo">
                    Situação
                </td>
                <td colspan="2" style="width: 15%" class="topo">
                    Necessidades Especiais
                </td>
                <td colspan="2" style="width: 10%" class="topo">
                    Deficiência
                </td>
                <td colspan="2" style="width: 10%" class="topo">
                    Sinais
                </td>
                <td colspan="2" class="topo" style="width: 20%">
                    Usou Óculos ou Lentes
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
                    PASSA
                </td>
                <td style="width: 5%" class="topo">
                    FALHA
                </td>
                <td style="width: 9%" class="topo">
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
                <td style="width: 10%" class="topo">
                    Sim
                </td>
                <td style="width: 10%" class="topo">
                    Não
                </td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style="color: red; background-color: #ccccff" class="topo">
                    Total
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Frequenteac'][$id_escola]) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Oculosac']['Sim']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Oculosac']['Não']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Situacaoac']['PASSA']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Situacaoac']['FALHA']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Situacaoac']['Não Submetido']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['NecesEspac']['Sim']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['NecesEspac']['Não']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][2] + $res[$id_escola]['Deficienciaac'][3] + $res[$id_escola]['Deficienciaac'][4] + $res[$id_escola]['Deficienciaac'][5] + $res[$id_escola]['Deficienciaac'][6] + $res[$id_escola]['Deficienciaac'][7]) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][1]) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Sinaisac'][2] + $res[$id_escola]['Sinaisac'][3] + $res[$id_escola]['Sinaisac'][4] + $res[$id_escola]['Sinaisac'][5] + $res[$id_escola]['Sinaisac'][6] + $res[$id_escola]['Sinaisac'][7] + $res[$id_escola]['Sinaisac'][8]) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['Sinaisac'][1]) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['usouoculosac']['Sim']) ?>
                </td>
                <td style="color: red; background-color: #ccccff" class="topo">
                    <?php echo intval($res[$id_escola]['usouoculosac']['Não']) ?>
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
                        <?php echo intval($res[$key]['Frequente'][$id_escola]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Oculos']['Sim']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Oculos']['Não']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Situacao']['PASSA']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Situacao']['FALHA']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Situacao']['Não Submetido']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['NecesEsp']['Sim']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['NecesEsp']['Não']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][2] + $res[$key]['Deficiencia'][3] + $res[$key]['Deficiencia'][4] + $res[$key]['Deficiencia'][5] + $res[$key]['Deficiencia'][6] + $res[$key]['Deficiencia'][7]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][1]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Sinais'][2] + $res[$key]['Sinais'][3] + $res[$key]['Sinais'][4] + $res[$key]['Sinais'][5] + $res[$key]['Sinais'][6] + $res[$key]['Sinais'][7] + $res[$key]['Sinais'][8]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Sinais'][1]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['UsouOculos']['Sim']) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['UsouOculos']['Não']) ?>
                    </td>      
                    <?php
                }
                ?>
            </tr>
        </tbody>
    </table>
    <!--
    Descrição Deficiência/Descrição Sinais
    -->
    <div style="font-weight:bold; font-size:8pt; background-color: #000000; color:#ffffff; text-align: center">DESCRIÇÃO DEFICIÊNCIA/SINAIS</div>

    <table style="width: 100%" class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td rowspan="2" style="color: red; width: 10%" class="topo">
                    Cód.Classe
                </td>
                <td colspan="7" style="color: red; width: 40%" class="topo">
                    Descrição Deficiência
                </td>
                <td colspan="8" style="color: red; background-color: #cccccc; width: 50%" class="topo">
                    Descrição Sinais
                </td>
            </tr>
            <tr>
                <td style="width: 6%" class="topo">
                    Não Possui
                </td>
                <td style="width: 6%" class="topo">
                    Física
                </td>
                <td style="width: 6%" class="topo">
                    Auditiva
                </td>
                <td style="width: 6%" class="topo">
                    Visual
                </td>
                <td style="width: 6%" class="topo">
                    Intelectual
                </td>
                <td style="width: 6%" class="topo">
                    Múltipla
                </td>
                <td style="width: 6%" class="topo">
                    TEA
                </td>
                <td style="width: 7%; background-color: #cccccc" class="topo2">
                    Não Possui
                </td>
                <td style="width: 5%; background-color: #cccccc" class="topo2">
                    Ardência
                </td>
                <td style="width: 5%; background-color: #cccccc" class="topo2">
                    Coceira
                </td>
                <td style="width: 8%; background-color: #cccccc" class="topo2">
                    Fadiga Visual
                </td>
                <td style="width: 8%; background-color: #cccccc" class="topo2">
                    Franzir a Testa
                </td>
                <td style="width: 6%; background-color: #cccccc" class="topo2">
                    Lacrijamento
                </td>
                <td style="width: 5%; background-color: #cccccc" class="topo2">
                    Tontura
                </td>
                <td style="width: 4%; background-color: #cccccc" class="topo2">
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
                        <?php echo intval($res[$key]['Deficiencia'][1]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][2]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][3]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][4]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][5]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][6]) ?>
                    </td>
                    <td class="topo"> 
                        <?php echo intval($res[$key]['Deficiencia'][7]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][1]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][2]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][3]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][4]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][5]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][6]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][7]) ?>
                    </td>
                    <td style="background-color: #cccccc" class="topo2"> 
                        <?php echo intval($res[$key]['Sinais'][8]) ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    Total
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][1]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][2]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][3]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][4]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][5]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][6]) ?>
                </td>
                <td style="color: red; background-color: #ccffcc" class="topo">
                    <?php echo intval($res[$id_escola]['Deficienciaac'][7]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo2">
                    <?php echo intval($res[$id_escola]['Sinaisac'][1]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo2">
                    <?php echo intval($res[$id_escola]['Sinaisac'][2]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo2">
                    <?php echo intval($res[$id_escola]['Sinaisac'][3]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo2">
                    <?php echo intval($res[$id_escola]['Sinaisac'][4]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo2">
                    <?php echo intval($res[$id_escola]['Sinaisac'][5]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo2">
                    <?php echo intval($res[$id_escola]['Sinaisac'][6]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo">
                    <?php echo intval($res[$id_escola]['Sinaisac'][7]) ?>
                </td>
                <td style="color: red; background-color: #cccccc" class="topo">
                    <?php echo intval($res[$id_escola]['Sinaisac'][8]) ?>
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