<?php
if (!defined('ABSPATH'))
    exit;
?>
<table style="width: 100%">
    <tr>
        <td style="width: 200px">
            <img style="width: 100%;" src="<?= HOME_URI ?>/includes/images/topo1.png"/>
        </td>
        <td style="text-align: center; font-weight: bold; font-size: 2em">
            <?= $evento['n_evento'] ?>
        </td>
        <td style="width: 200px">
            <img style="width: 100%;" src="<?= HOME_URI ?>/includes/images/topo2.png"/>
        </td>
    </tr>
</table>
<div class="alert alert-info" style="margin-top: 20px; font-weight: bold">
            <?= $evento['descr_evento'] ?>
</div>