<?php
ob_start();
$cor = '#F5F5F5';
?>
<head>
    <style>
        td{
            font-weight:bolder;
            text-align: center;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .topo{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            white-space: nowrap;
        }
        .topo2{
            font-size: 8pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            background-color: #000000;
            color: #ffffff;

        }
    </style>
</head>
<div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
    Relatório de Linhas/Viagens 
</div>
<?php
$dados = $model->gerarelalinha();

if (!empty($proximaFolha)) {
    ?>
    <div style="page-break-after: always"></div>
    <?php
} else {
    $proximaFolha = 1;
}
?>
<table class="table tabs-stacked table-bordered">;
    <tr>
        <td class="topo">
            Empresa       
        </td> 
        <td class="topo">
            Escola      
        </td> 
        <td class="topo">
            Linha
        </td>          
        <td class="topo">
            Viagem     
        </td>
        <td class="topo">
            Motorista     
        </td>
       <td class="topo">
            Monitor     
        </td>
    </tr>       
    <?php
    foreach ($dados as $v) {
        ?>
        <tr>
            <td class="topo">
                <?php echo $v['n_em'] ?>       
            </td> 
            <td class="topo" style="text-align: left">
                <?php echo $v['n_inst'] ?>     
            </td> 
            <td class="topo" style="text-align: left">
                <?php echo $v['n_li'] ?>
            </td> 
            <td class="topo">
                <?php echo $v['viagem'] ?>     
            </td>
            <td class="topo" style="text-align: left">
                <?php echo $v['motorista'] ?>     
            </td>
            <td class="topo" style="text-align: left">
                <?php echo $v['monitor'] ?>     
            </td>
        </tr> 
        <?php
    }
    ?>
</table>

<?php
tool::pdfsecretaria2('L');
?>