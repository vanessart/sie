<?php
if (!defined('ABSPATH'))
    exit;
?>
<table class="table table-bordered table-hover table-striped border" style="width: 80%; margin: auto" >
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
        <td rowspan="2" style="padding: 15px">
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
        <td rowspan="2" style="padding: 15px">
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
</table>
<br />