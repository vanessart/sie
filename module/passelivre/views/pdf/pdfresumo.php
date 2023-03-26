<?php
if (!defined('ABSPATH'))
    exit;

ob_start();

$es = $model->pegaescolasativo();
$st = $model->pegastatus();
$es_n = $model->pegaescolas();
$dados = $model->pegaresumo();
$res = $model->pegaresumogeral();
//$ac = $model->pegaacompanhante();
$cota = $model->pegacota();
?>
<head>
    <style>
        .topo{
            font-size: 8pt;
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
    <div class="col-10" style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 2pt">
        Resumo - Escola
    </div>
</div>

<?php
if (!empty($dados)) {
    ?>
    <div class="body">
        <table style="width: 100%">
            <thead>
                <tr>
                    <td class="topo">
                        Seq.
                    </td>
                    <td class="topo">
                        Nome Escola
                    </td>
                    <?php
                    foreach ($st as $w) {
                        ?>
                        <td class="topo">
                            <?= $w ?>
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
                $seq = 1;
                foreach ($es as $k => $v) {
                    ?>
                    <tr>
                        <td class="topo">
                            <?= $seq++ ?>
                        </td>
                        <td class="topo" style="text-align: left">
                            <?= $es_n[$k] ?>
                        </td>
                        <?php
                        $conta = 0;
                        foreach ($st as $k2 => $v2) {
                            ?>
                            <td class="topo">
                                <?= $dados[$k][$k2] ?>
                            </td>
                            <?php
                            $conta = $conta + $dados[$k][$k2];
                        }
                        ?>
                        </td>
                        <td class="topo" style="vertical-align: middle">
                            <?= $conta ?>
                        </td>

                    </tr>
                    <?php
                }
                ?>
            </tbody>  
        </table>
        <footer>
            <div style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 2pt">
                Resumo - Rede
            </div>
        </footer>
        <table style="width: 100%">
            <thead>
                <tr>
                    <?php
                    foreach ($st as $w) {
                        ?>
                        <td rowspan="2" class="topo">
                            <?= $w ?>
                        </td>
                        <?php
                    }
                    ?>
                    <!--
                    <td class="topo">
                        Acompanhante
                    </td>
                    <td class="topo">
                        Total Passe
                    </td>
                    -->
                    <td colspan="2" class="topo">
                        Cota
                    </td>
                </tr>
                <tr>
                    <td class="topo">
                        Total de Passe
                    </td>
                    <td class="topo">
                        Disponível
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    foreach ($st as $k => $v) {
                        ?>
                        <td class="topo">
                            <?= $res[$k] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <!--
                    <td class="topo">
                    <?= $ac['Total'] ?>
                    </td>
                    -->
                    <td class="topo">
                        <?= $cota['qtde_passe'] ?>
                    </td>
                    <td class="topo">
                        <?= $cota['qtde_passe'] - ($res['7'] * 2) ?>
                    </td>
                </tr>
            </tbody>  
        </table>
    </div>
    <?php
}
tool::pdfsecretaria2('L');
?>
