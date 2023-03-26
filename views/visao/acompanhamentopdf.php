<?php
ob_start();
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
            font-size: 7pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 1px;
            padding: 2px;

        }
    </style>
</head>
<?php
$escola = sql::get('instancia', 'n_inst', ['id_inst' => tool::id_inst()], 'fetch');
?>
<div style="font-weight: bold; font-size: 9pt; padding-left: 5px; border-style: solid; border-width: 1px; text-align: left">
    <?php echo $escola['n_inst'] ?>
</div>
<div style="font-weight:bold; font-size:9pt; background-color: #000000; color:#ffffff; text-align: center">ACOMPANHAMENTO DO ALUNO - <?php echo date('Y') ?></div>
<?php

$rel = $model->relatorioacompanhamento();

if (!empty($rel)) {
    $id_escola = tool::id_inst();
    ?>
    <table style="width: 100%" class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td colspan="2" style="background-color: #99ff99; border-color: black; width: 38%" class="topo">
                    Unidade Escolar
                </td>
                <td colspan="7" style="background-color: #cccccc; border-color: black;  width: 42%" class="topo">
                    Secretaria de Saúde/Particular/Convênio
                </td>
                <td colspan="3" style="background-color: #9c3328; border-color: black; color: white; width: 20%" class="topo">
                    Promoção Social/Particular
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="background-color: #99ff99; border-color: black; width: 5%" class="topo">
                    Classe
                </td>
                <td rowspan="2" style="background-color: #99ff99; border-color: black; width: 33%" class="topo">
                    Nome Aluno
                </td>           
                <td rowspan="2" style="background-color: #cccccc; border-color: black; width: 5%" class="topo">
                    Consulta
                </td>
                <td rowspan="2" style="background-color: #cccccc; border-color: black; width: 11%" class="topo">
                    Local
                </td>
                <td rowspan="2" style="background-color: #cccccc; border-color: black; width: 6%" class="topo">
                    Data
                </td>
                <td colspan="2" style="background-color: #cccccc; border-color: black; width: 10%" class="topo">
                    Óculos
                </td>
                <td rowspan="2" style="background-color: #cccccc; border-color: black; widh: 5%" class="topo">
                    Exames
                </td>
                <td rowspan="2" style="background-color: #cccccc; border-color: black; width:5%" class="topo">
                    CID
                </td>
                <td colspan="3" style="background-color: #9c3328; border-color: black; color: white; width: 20%" class="topo">
                    Óculos
                </td>
            </tr>
            <tr>
                <td style="background-color: #cccccc; border-color: black; widh: 5%" class="topo">
                    Indicação
                </td>
                <td style="background-color: #cccccc; border-color: black; widh: 5%" class="topo">
                    Fazia Uso
                </td>           
                <td style="background-color: #9c3328; color: white; border-color: black; widh: 5%" class="topo">
                    Aquisição
                </td>
                <td style="background-color: #9c3328; color: white; border-color: black; widh: 10%" class="topo">
                    Local
                </td>
                <td style="background-color: #9c3328; color: white; border-color: black; widh: 5%" class="topo">
                    Data
                </td>               
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($rel as $v) {
                ?>
            <tr>
               <td class="topo2">
                    <?php echo  $v['codigo'] ?>
                </td>
                <td style="text-align: left" class="topo2">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>             
                <td class="topo2">
                    <?php echo $v['consulta_oftal'] ?>
                </td>
                <td class="topo2">
                    <?php echo $v['consulta_local'] ?>
                </td>
                <td class="topo2">
                    <?php echo data::converteBr($v['dt_consulta']) ?>
                </td>
                <td class="topo2">
                    <?php echo $v['indicacao_oculos'] ?>
                </td>
                <td class="topo2">
                    <?php echo $v['usouoculoslentes'] ?>
                </td>
                <td class="topo2">
                    <?php echo $v['exames_compl'] ?>
                </td>
                <td class="topo2">
                    <?php echo $v['cod_cid_10'] ?>
                </td>
                <td class="topo2">
                    <?php echo $v['aquisicao_oculos'] ?>
                </td>
                <td class="topo2">
                    <?php echo $v['aquisicao_local'] ?>
                </td>
                <td class="topo2">
                    <?php echo data::converteBr($v['dt_aquisicao']) ?>
                </td>
            </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
} else {
    echo "Não existe dado para relatório!!!!";
}
$model->pdfvisao('L');
?>