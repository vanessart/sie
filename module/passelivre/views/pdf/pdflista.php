<?php
if (!defined('ABSPATH'))
    exit;

$criterio = filter_input(INPUT_POST, 'criterio', FILTER_SANITIZE_STRING);

ob_start();
$cor = '#F5F5F5';

$dados = sql::get('pl_passelivre', '*', $criterio);
$esc = $model->pegaescolas();
$st = $model->pegastatus();
?>

<head>
    <style>
        .topo{
            font-size: 6pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>

<?php
if (!empty($dados)) {
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
                <td colspan="8" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                    Lista de Alunos
                </td>
            </tr>
            <tr>
                <td class="topo" style="width: 5%">
                    Seq.
                </td>
                <!--
                <td class="topo" style="width: 5%">
                    ID
                </td>
                -->
                <td class="topo" style="width: 25%">
                    Nome Escola
                </td>
                <td class="topo" style="width: 30%">
                    Nome Aluno
                </td>
                <td class="topo" style="width: 5%">
                    Data Nasc.
                </td>
                <td class="topo" style="width: 10%">
                    Reg. Municipal
                </td>
                <td class="topo" style="width: 15%">
                    Status
                </td>
                <td class="topo" style="width: 5%">
                    Lote
                </td> 
                <td class="topo" style="width: 5%">
                    Início
                </td>
            </tr>
        </thead>
        <?php
        $seq = 1;
        foreach ($dados as $v) {
            ?>
            <tbody>
                <tr>
                    <td class="topo" style="width: 5%">
                        <?= $seq++ ?>
                    </td>
                    <!--
                    <td class="topo" style="width: 5%">
                        <?= str_pad($v['id_passelivre'], 5, '0', STR_PAD_LEFT) ?>
                    </td>
                    -->
                    <td class="topo" style="width: 25%; text-align: left">
                        <?= $esc[$v['cie']] ?>
                    </td>
                    <td class="topo" style="width: 30%; text-align: left">
                        <?= $v['nome'] ?>
                    </td>
                    <td class="topo" style="width: 5%">
                        <?= data::converteBr($v['dt_nasc']) ?>
                    </td>
                    <td class="topo" style="width: 10%">
                        <?= $v['reg_mun'] ?>
                    </td>
                    <td class="topo" style="width: 15%">
                        <?= ($v['fk_id_pl_status'] == 3 ? "Ag.Def." : $st[$v['fk_id_pl_status']]) ?>
                    </td>
                    <td class="topo" style="width: 5%">
                        <?= $v['lote'] ?>
                    </td>
                    <td class="topo" style="width: 5%">
                        <?= data::converteBr($v['dt_inicio_passe']) ?>
                    </td>
                </tr>
            </tbody>
            <?php
        }
        ?>
    </table>

    <?php
} else {
    echo "Não existe dados para relatório";
}
if (tool::id_nivel() == '10') {
    tool::pdfsecretaria2('L');
} else {
    tool::pdfescola('L');
}
?>
