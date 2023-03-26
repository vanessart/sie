<?php
ob_start();
$cor = '#F5F5F5';
?>
<head>
    <style>
        td{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            
        }
        .quebra { 
            page-break-before: always;
        }
    </style>
</head>
<?php
$dados = $model->resumoinscricao();
$dadost = $model->resumoinscricaototal();
?>

<div style="width: 100%" class="fieldBody" >

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Resumo para Inscrição Reserva de Vagas - <?php echo date("Y") ?> 
    </div>
    <div style="font-weight:bold; font-size:8pt; border-width: 0.5px; border-style: solid;color: red; text-align: center">
        Atualizado em  <?php echo data::proximoDia(date("d/m/Y"), -1) ?>
    </div>

    <table class=" table table-bordered">
        <thead>
            <tr>
                <td rowspan="3" style="width: 30%">
                    Nome Escola
                </td>
                <td colspan="4" style="width: 14%">
                    Berçário
                </td>
                <td colspan="4" style="width: 14%">
                    1ª Fase - Maternal
                </td>
                <td colspan="4" style="width: 14%">
                    2ª Fase - Maternal
                </td>
                <td colspan="4" style="width: 14%">
                    3ª Fase - Maternal
                </td>
                <td colspan="2" style="width: 14%">
                    Total Escola
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 7%">
                    Deferido 
                </td>
                <td colspan="2" style="width: 7%">
                    Ag. Def.
                </td>
                <td colspan="2" style="width: 7%">
                    Deferido 
                </td>
                <td colspan="2" style="width: 7%">
                    Ag. Def.
                </td>
                <td colspan="2" style="width: 7%">
                    Deferido 
                </td>
                <td colspan="2" style="width: 7%">
                    Ag. Def.
                <td colspan="2" style="width: 7%">
                    Deferido 
                </td>
                <td colspan="2" style="width: 7%">
                    Ag. Def.
                </td>
                <td rowspan="2" style="width: 3%">
                    Deferido 
                </td>
                <td rowspan="2" style="width: 7%">
                    Ag. Def.
                </td>
            </tr>
            <tr>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 7%">
                    N.T.
                </td>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 3%">
                    N.T.
                </td>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 3%">
                    N.T.
                </td>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 3%">
                    N.T.
                </td>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 3%">
                    N.T.
                </td>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 3%">
                    N.T.
                </td>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 3%">
                    N.T.
                </td>
                <td style="width: 4%">
                    Trab.
                </td>
                <td style="width: 3%">
                    N.T.
                </td>             
            </tr>
        </thead>

        <?php
        foreach ($dados as $k => $v) {
            ?>
            <tbody>
                <tr>
                    <td style="width: 30%; text-align: left; background-color: <?php echo $cor ?>">
                        <?php echo $k ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['Berçário']['Deferido'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['Berçário']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['Berçário']['Edição'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['Berçário']['Edição'][0] ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['1ª Fase - Maternal']['Deferido'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['1ª Fase - Maternal']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['1ª Fase - Maternal']['Edição'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?> ">
                        <?php echo @$v['1ª Fase - Maternal']['Edição'][0] ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['2ª Fase - Maternal']['Deferido'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['2ª Fase - Maternal']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['2ª Fase - Maternal']['Edição'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['2ª Fase - Maternal']['Edição'][0] ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['3ª Fase - Maternal']['Deferido'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['3ª Fase - Maternal']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['3ª Fase - Maternal']['Edição'][1] ?>
                    </td>
                    <td style="width: 3%; background-color: <?php echo $cor ?>; ">
                        <?php echo @$v['3ª Fase - Maternal']['Edição'][0] ?>
                    </td>
                    <td style="width: 7%; background-color: <?php echo $cor ?>">
                        <?php echo @$v['Deferido']?>
                    </td>
                    <td style="width: 7%; background-color: <?php echo $cor ?>">
                        <?php 
                        echo @$v['Edição'];
                        $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                        ?>
                    </td>
            </tbody>
            <?php
        }
        ?>
            <tfoot>
                <tr>
                    <td style="width: 30%; text-align: left">
                        Total de Crianças
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['Berçário']['Deferido'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['Berçário']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['Berçário']['Edição'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['Berçário']['Edição'][0] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['1ª Fase - Maternal']['Deferido'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['1ª Fase - Maternal']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['1ª Fase - Maternal']['Edição'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['1ª Fase - Maternal']['Edição'][0] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['2ª Fase - Maternal']['Deferido'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['2ª Fase - Maternal']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['2ª Fase - Maternal']['Edição'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['2ª Fase - Maternal']['Edição'][0] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['3ª Fase - Maternal']['Deferido'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['3ª Fase - Maternal']['Deferido'][0] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['3ª Fase - Maternal']['Edição'][1] ?>
                    </td>
                    <td style="width: 4%">
                        <?php echo @$dadost['3ª Fase - Maternal']['Edição'][0] ?>
                    </td>
                    <td style="width: 6%">
                        <?php echo @$dadost['Deferido'] ?>
                    </td>
                    <td style="width: 6%">
                        <?php echo @$dadost['Edição'] ?>
                    </td>
            </tfoot>
    </table>
</div>

<?php
tool::pdfsecretaria2('L');
?>