<?php
ob_start();
$cor = '#F5F5F5';
$m = data::meses();
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
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
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

<?php
$dados = transporte::alunoFrequencia($_POST['mes']);

//$dados = $model->movimentacaogeral($_POST['mes']);
$escola = $model->pegaescola();
?>
<table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
    <thead>
        <tr>
            <td colspan="4" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                 Movimentações <?php echo $m[$_POST['mes']] . '/' . date('Y') ?>
            </td>
        </tr>
        <tr>     
            <td class="topo" style="width: 55%">
                Nome Escola
            </td>
            <td class="topo" style="width: 15%">
                Inclusão
            </td>
            <td class="topo" style="width: 15%">
                Exclusão
            </td>
            <td class="topo" style="width: 15%">
                Lista de Espera
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($escola as $k => $v) {
            ?>
            <tr>     
                <td class="topo" style= "text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo $v ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php echo (empty($dados['inclusao'][$k]) ? '-' : $dados['inclusao'][$k]) ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php echo (empty($dados['exclusao'][$k]) ? '-' : $dados['exclusao'][$k]) ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php
                    echo (empty($dados['espera'][$k]) ? '-' : $dados['espera'][$k]);
                    $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>     
            <td class="topo2" style="text-alig: left">
                Total
            </td>
            <td class="topo2">
                <?php echo (empty($dados['totali']) ? 0 : $dados['totali']) ?>
            </td>
            <td class="topo2">
                <?php echo (empty($dados['totale']) ? 0 : $dados['totale']) ?>
            </td>
            <td class="topo2">
                <?php echo (empty($dados['totals']) ? 0 : $dados['totals']) ?>
            </td>
        </tr>
    </tfoot>
</table>

<?php
tool::pdfsecretaria2('P');
?>