<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$cor = '#F5F5F5';
$m = dataErp::meses();
$escola = $model->pegaescola();
$quebra = [0 => 'Exclusão', 1 => 'Inclusão'];
$pdf = new pdf();
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
            padding-left: 3px;
            padding-right: 3px;
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
if (!empty($_POST['mes']) && ( !empty($_POST['idinst']))) {
    $dados = $model->movimentacaoaluno($_POST['mes'], $_POST['idinst']);
    foreach ($quebra as $w) {

        if (!empty($proximaFolha)) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        } else {
            $proximaFolha = 1;
        }
        ?>
        <table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
            <thead>
                <tr>
                    <td colspan="10" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                        Movimentação <?php echo $w . ' ' . $m[$_POST['mes']] . '/' . date('Y') ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="10" style="font-weight:bold; font-size:10pt; text-align: left; color: red; border-style: solid; border-width: 1px">
                        <?php echo $escola[$_POST['idinst']] ?>
                    </td>
                </tr>
                <tr>   
                    <td class="topo" style="width: 3%">
                        Seq.
                    </td>
                    <td class="topo" style="width: 6%">
                        Data Mov.
                    </td>
                    <td class="topo" style="width: 22%">
                        Nome Aluno
                    </td>
                    <td class="topo" style="width: 7%">
                        RA
                    </td>
                    <td class="topo" style="width: 5%">
                        Turma
                    </td>
                    <td class="topo" style="width: 10%">
                        Linha
                    </td>
                    <td class="topo" style="width: 22%">
                        Endereço
                    </td>
                    <td class="topo" style="width: 10%">
                        Bairro
                    </td>
                    <td class="topo" style="width: 4%">
                        Distância
                    </td>
                    <td class="topo" style="width: 5%">
                        Período
                    </td>
                </tr>
            </thead>
            <?php
            $seq = 1;
            foreach ($dados[$w] as $v) {
                ?>

                <tbody>
                    <tr>   
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $seq++ ?>
                        </td>
                        <td class="topo" style= "text-align: left; background-color: <?php echo $cor ?>">
                            <?php echo dataErp::converteBr($v['Data']) ?>
                        </td>
                        <td class="topo" style="text-align:left; background-color: <?php echo $cor ?>">
                            <?php echo toolErp::abrevia($v['n_pessoa']) ?>
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $v['ra'] . '-' . $v['ra_dig'] ?>
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $v['codigo'] ?>
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $v['n_li'] ?>
                        </td>
                        <td class="topo" style="text-align:left; font-size: 6pt; background-color: <?php echo $cor ?>">
                            <?php echo $v['logradouro_gdae'] . ' nº. ' . $v['num_gdae'] ?>
                        </td>
                        <td class="topo" style="font-size: 6pt; background-color: <?php echo $cor ?>">
                            <?php echo $v['bairro'] ?>
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $v['distancia_esc'] ?>
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php
                            echo $v['periodo'];
                            $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                            ?>
                        </td>            
                    </tr>
                </tbody>
                <?php
            }
            ?>
        </table>
        <?php
    }
} else {
    echo "Favor Selecionar Escola";
}
$pdf->orientation = 'L';
$pdf->exec();
?>