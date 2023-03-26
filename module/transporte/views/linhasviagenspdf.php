<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_em = filter_input(INPUT_POST, 'id_em', FILTER_SANITIZE_NUMBER_INT);
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);

$cor = '#F5F5F5';
$pdf = new pdf();

$subTitulo = "";
$periodo = null;
if (!empty($id_inst)) {
    $escola = new ng_escola($id_inst);
    $subTitulo = "<br>Escola: ". $escola->_nome;
}

if (!empty($id_em)) {
    $subTitulo = "<br>Empresa: ". trasnporteErp::nomeempresa($id_em);
}

if (!empty($id_li)) {
    $subTitulo = "<br>Linha: ". trasnporteErp::linhaGet($id_li);
}
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
$dados = $model->pegacabecalhorel($id_li, $id_inst, $id_em);
?>
<table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
    <thead>
        <tr>
            <td colspan="6" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                Linhas / Viagens <?php echo $subTitulo ?>
            </td>
        </tr>
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
    </thead>
    <tbody>
        <?php foreach ($dados as $k => $v) { ?>
            <tr>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php echo $v['n_em'] ?>
                </td>
                <td class="topo" style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo $v['n_inst'] ?>
                </td>
                <td class="topo" style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo $v['n_li'] ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php echo $v['viagem'] ?>
                </td>
                 <td class="topo" style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo $v['motorista'] ?>
                </td>
                <td class="topo" style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php
                    echo $v['monitor'];
                    $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
$pdf->orientation = 'L';
$pdf->exec();
?>