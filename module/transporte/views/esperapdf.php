<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);

$cor = '#F5F5F5';
$pdf = new pdf();

$subTitulo = "";
$periodo = null;
if (!empty($mes)) {
    $periodo = date('Y') .'-'. $mes;
    $subTitulo = "<br>Período: ".$mes."/".date('Y');
}

if (!empty($id_inst)) {
    $escola = new ng_escola($id_inst);
    $subTitulo = "<br>Escola: ". $escola->_nome;
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
$dados = transporteErp::getListaEspera($periodo, $id_inst);
?>
<table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
    <thead>
        <tr>
            <td colspan="5" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                Lista de Espera <?php echo $subTitulo ?>
            </td>
        </tr>
        <tr>
            <td class="topo" style="width: 55%">
                Nome Aluno
            </td>
            <td class="topo" style="width: 15%">
                RA
            </td>
            <td class="topo" style="width: 15%">
                Turma
            </td>
            <td class="topo" style="width: 15%">
                Período
            </td>
            <td class="topo" style="width: 15%">
                Data da Solicitação
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $n_inst = "";
        foreach ($dados as $k => $v) { 
            if ($n_inst <> $v['n_inst']) {
                ?>
                <tr>
                    <td class="topo" style="text-align: left; background-color: #c2c2c2; font-weight: bold;" colspan="5">
                        <?php echo $v['n_inst'] ?>
                    </td>
                </tr>
                <?php
                $n_inst = $v['n_inst'];
            }
            ?>
            <tr>
                <td class="topo" style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo $v['n_pessoa'] ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php echo $v['ra'] ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php echo $v['n_turma'] ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php echo dataErp::periodoDoDia($v['periodo']) ?>
                </td>
                <td class="topo" style="background-color: <?php echo $cor ?>">
                    <?php
                    echo $v['dt_solicita'];
                    $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
$pdf->exec();
?>