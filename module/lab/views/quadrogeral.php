<?php
if (!defined('ABSPATH'))
    exit;
$dados = $model->pegaquadroescola();
$escola = $model->pegaescola();
$total = $model->pegatotalstatus();
$st = [
    0 => 'Indefinido',
    1 => 'Regular',
    2 => 'Em Manutenção',
    3 => 'Emprestado',
    //4 => 'Quebrado (enviado para manutenção)',
    6 => 'Não Alocado',
    8 => 'Foi dado Baixa',
];
?>
<head>
    <style>
        .topo{
            font-size: 10pt;
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

<div class="body">
    <div class="row">
        <div class="col-8"style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 8pt">
            Quadro Geral de Chromebook das Escolas
        </div>  
        <div class="col-2">
            <form target="_blank" action="<?= HOME_URI ?>/lab/pdf/pdfquadrogeral.php" method="POST">
                <button class="btn btn-info" style="width: 70%">
                    Imprimir
                </button>         
            </form>
        </div>
        <div class="col-2">
            <form target="_blank" action="<?= HOME_URI ?>/app/excel/doc/plamQuadro.php" method="POST">
                <button class="btn btn-info" style="width: 70%">
                    Exportar
                </button>         
            </form>
        </div>
    </div>

    <table style="width: 100%">
        <thead>
            <tr>
                <td class="topo" style="width: 30%">
                    Escola
                </td>
                <?php
                foreach ($st as $v) {
                    ?>
                    <td class="topo" style="width: 10%">
                        <?= $v ?>
                    </td>
                    <?php
                }
                ?>
                <td class="topo">
                    Total
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($escola as $k => $v) {
                ?>
                <tr>
                    <td class="topo" style="text-align:left">
                        <?= $v ?>
                    </td>
                    <?php
                    $conta[$k] = 0;
                    foreach ($st as $k2 => $v2) {
                        ?>
                        <td class="topo">
                            <?= $dados[$k][$k2] ?>
                        </td>
                        <?php
                        $conta[$k] = $conta[$k] + $dados[$k][$k2];
                    }
                    ?>
                    <td class="topo">
                        <?= $conta[$k] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <footer>
        <table style="width: 100%">
            <tr>
                <td class="topo" style="width: 30%">
                    Total
                </td>
                <?php
                $conta_g = 0;
                foreach ($st as $k3 => $v3) {
                    ?>
                    <td class="topo" style="width: 10%">
                        <?php
                        echo $total[$k3];
                        $conta_g = $conta_g + $total[$k3];
                        ?>                   
                    </td>
                    <?php
                }
                ?>
                <td class="topo" style="width: 10%">
                    <?= $conta_g ?>
                </td>
            </tr>
        </table>
        <div class="topo" style="color: red; border-color: black">
            * Inclui somente escolas. Nào inclui: Equipamentos alocados com Funcionários em Geral, Professores, Departamentos e Inservíveis.
        </div>
    </footer>

</div>