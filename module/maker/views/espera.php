<?php
if (!defined('ABSPATH'))
    exit;

$esp = $model->espera();
?>
<style>
    .tc{
        text-align: center;
        font-weight: bold;
        font-size: 1.5em;
    }
</style>
<div class="body">
    <div class="fieldTop">
        Lista de Espera
    </div>
    <?php
    if ($esp) {
        ?>
        <table class="table table-bordered table-hover table-striped border" style="width: 60%; margin: auto" >
            <tr>
                <td colspan="5" style="font-weight: bold; text-align: center; font-size: 1.3em">
                    Total Geral
                </td>
            </tr>
            <tr>
                <td class="tc">
                    Básico
                </td>
                <td class="tc">
                    Intermediário
                </td>
                <td class="tc">
                    Avançado 1
                </td>
                <td class="tc">
                    Avançado 2
                </td>
                <td class="tc">
                    Total
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <?= intval(@$esp['total'][1]) ?>
                </td>
                <td class="tc">
                    <?= intval(@$esp['total'][2]) ?>
                </td>
                <td class="tc">
                   <?= intval(@$esp['total'][3]) ?>
                </td>
                <td class="tc">
                   <?= intval(@$esp['total'][4]) ?>
                </td>
                <td class="tc">
                    <?= intval(array_sum(@$esp['total'])) ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <?php
        unset($esp['total']);
        foreach ($esp as $v) {
            if ($v) {
                ?>
                <table class="table table-bordered table-hover table-striped border" style="width: 90%; margin: auto" >
                    <tr>
                        <td colspan="5" style="font-weight: bold; text-align: center; font-size: 1.3em">
                            <?= $v['polo'] ?>
                        </td>
                        <td class="tc">
                            Básico
                        </td>
                        <td class="tc">
                            Intermediário
                        </td>
                        <td class="tc">
                            Avançado 1
                        </td>
                        <td class="tc">
                            Avançado 2
                        </td>
                        <td class="tc">
                            Total
                        </td>
                    </tr>
                    <tr>
                        <td class="tc">
                            Escola
                        </td>
                        <td class="tc">
                            Básico
                        </td>
                        <td class="tc">
                            Intermediário
                        </td>
                        <td class="tc">
                            Avançado 1
                        </td>
                        <td class="tc">
                            Avançado 2
                        </td>
                        <td class="tc" rowspan="<?= count($v['esc']) + 1 ?>">
                            <br />
                            <?= intval(@$v['ciclos'][1]) ?>
                        </td>
                        <td class="tc" rowspan="<?= count($v['esc']) + 1 ?>">
                            <br />
                            <?= intval(@$v['ciclos'][2]) ?>
                        </td>
                        <td class="tc" rowspan="<?= count($v['esc']) + 1 ?>">
                            <br />
                            <?= intval(@$v['ciclos'][3]) ?>
                        </td>
                        <td class="tc" rowspan="<?= count($v['esc']) + 1 ?>">
                            <br />
                            <?= intval(@$v['ciclos'][4]) ?>
                        </td>
                        <td class="tc" rowspan="<?= count($v['esc']) + 1 ?>">
                            <br />
                            <?= intval(array_sum($v['ciclos'])) ?>
                        </td>
                    </tr>
                    <?php
                    foreach ($v['esc'] as $esc => $ci) {
                        ?>
                        <tr>
                            <td class="tc" style="width: 30%">
                                <?= $esc ?>
                            </td>
                            <td class="tc">
                                <?= intval(@$ci[1]) ?>
                            </td>
                            <td class="tc">
                                <?= intval(@$ci[2]) ?>
                            </td>
                            <td class="tc">
                                <?= intval(@$ci[3]) ?>
                            </td>
                            <td class="tc">
                                <?= intval(@$ci[4]) ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <br /><br />
                <?php
            }
        }
    }
    ?>
</div>
