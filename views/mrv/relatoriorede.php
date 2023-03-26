<?php
if (!defined('ABSPATH'))
    exit;

$dados = $model->pegadadosrelatorio();
$st = $model->pegastatus();
$escola = $model->pegaescolarel();

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
<br />
<div class="body">
    <div class="row" style="width: 100%">
        <div class="col-md-9"style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 5pt">
            Quadro situação
        </div>  
        <div class="col-md-3">
            <form target="_blank" action="<?= HOME_URI ?>/mrv/relatorioredepdf" method="POST">
                <button class="btn btn-info" style="width: 70%">
                    Imprimir
                </button>         
            </form>
        </div>
        
    </div>
    <table style="width: 100%">
        <thead>
            <tr>
                <td class="topo" style="width: 36%">
                    Escola
                </td>
                <?php
                foreach ($st as $v) {
                    ?>
                    <td class="topo" style="width: 8%">
                        <?= $v ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($escola as $k => $v) {
                ?>
                <tr>
                    <td class="topo" style="text-align:left; width: 36%">
                        <?= $v ?>
                    </td>
                    <?php
                    foreach ($st as $w) {
                        ?>
                        <td class="topo" style="width: 8%">
                            <?= $dados[$k][$w] ?>
                        </td>
                        <?php
                        @$conta[$w] = @$conta[$w] + $dados[$k][$w];
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
<footer>
        <table style="width: 100%">
            <tr>
                <td class="topo" style="width: 36%">
                    Total
                </td>
                <?php
                foreach ($st as $k3 => $v3) {
                    ?>
                    <td class="topo" style="width: 8%">
                        <?= $conta[$k3] ?>                   
                    </td>
                    <?php
                }
                ?>

            </tr>
        </table>
    </footer>
</div>