<?php
if (!defined('ABSPATH'))
    exit;
?>

<div style="width: 80%; margin: auto" class="border">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td colspan="2">
            </td>
            <?php
            foreach ($diaSem as $v) {
                ?>
                <td>
                    <?= $v ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td rowspan="3" style="padding: 15px">
                Manhã
            </td>
            <td>
                1º Período
            </td>
            <?php
            foreach ($diaSem as $k => $v) {
                ?>
                <td>
                    <?= intval(@$a['aula']['M'][$k][1]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td>
                2º Período
            </td>
            <?php
            foreach ($diaSem as $k => $v) {
                ?>
                <td>
                    <?= intval(@$a['aula']['M'][$k][2]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td>
                Total da Manhã
            </td>
            <?php
            foreach ($diaSem as $k => $v) {
                ?>
                <td>
                    <?= intval(@$a['periodo']['M'][$k]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td rowspan="3" style="padding: 15px">
                Tarde
            </td>
            <td>
                1º Período
            </td>
            <?php
            foreach ($diaSem as $k => $v) {
                ?>
                <td>
                    <?= intval(@$a['aula']['T'][$k][1]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td>
                2º Período
            </td>
            <?php
            foreach ($diaSem as $k => $v) {
                ?>
                <td>
                    <?= intval(@$a['aula']['T'][$k][2]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td>
                Total da Tarde
            </td>
            <?php
            foreach ($diaSem as $k => $v) {
                ?>
                <td>
                    <?= intval(@$a['periodo']['T'][$k]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td colspan="2">
                Total do dia  da semana
            </td>
            <?php
            foreach ($diaSem as $k => $v) {
                ?>
                <td>
                    <?= intval(@$a['totalDia'][$k]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td colspan="2">
                Total Geral
            </td>
            <td colspan="5">
                <?= @$a['totalGeral'] ?>
            </td>
        </tr>
    </table>
</div>