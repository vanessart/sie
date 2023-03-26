<?php
ob_start();
$escola = new escola();
echo $escola->cabecalho();
?>
<head>
    <style>
        td{
            font-size: 9pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
        }
    </style> 
</head>

<div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
    Lista Piloto
</div>

<?php

$cab = sql::get(['ge_turmas'], '*', ['id_turma' => @$_REQUEST['twj']]);

foreach ($cab as $b) {
    ?>
    <div style="font-weight:bold; font-size:9pt; background-color: #ffffff; width: 679px; color:#000000; text-align: center; border-style: solid">
        <?php echo $b['n_turma'] . ' Ano Letivo: ' . $b['periodo_letivo'] . ' Período: ' . $b['periodo'] ?>
    </div>
    <?php
}
?>

<table class="table tabs-stacked table-bordered">';
<?php
$listapiloto = sql::get(['pessoa', 'ge_turma_aluno'], '*', ['fk_id_turma' => @$_REQUEST['twj'], '>' => 'chamada']);
?>

    <tr>
        <td >
            nº Ch       
        </td>
        <td >
            RSE       
        </td>
        <td>
            RA       
        </td>       
        <td>
            Nome do Aluno
        </td>
        <td>
            Data Matrícula
        </td>
        <td>
            Telefone
        </td>
        <td>
            Situação      
        </td>
        <td>
            Data Tansferência       
        </td>
    </tr>
<?php
foreach ($listapiloto as $v) {
    ?>

        <tr>
            <td>
    <?php echo $v['chamada'] ?>
            </td>
            <td>
    <?php echo $v['id_pessoa'] ?>
            </td>
            <td>
    <?php echo $v['ra'] ?>
            </td>
            <td style="text-align: left">
    <?php echo $v['n_pessoa'] ?>
            </td>
            <td> 
    <?php echo data::converteBr($v['dt_nasc']) ?>
            </td>
            <td>
    <?php echo $v['tel1'] ?>
            </td>
            <td>
    <?php echo $v['situacao'] ?>
            </td>
            <td>
    <?php echo $v['dt_transferencia'] != '0000-00-00' ? data::converteBr($v['dt_transferencia']) : '' ?>
            </td>

        </tr>

    <?php
}
?>
</table>
    <?php
    tool::pdf();
    ?>