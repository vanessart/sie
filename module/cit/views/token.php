<?php
if (!defined('ABSPATH'))
    exit;
$mongo = new mongoCrude('Cit');
$tokenArr = $mongo->query('setup', ['data' => date("Y-m-d")]);

    $tokenArr = cit::setup();
    $token = $tokenArr['token'];
    $data = $tokenArr['data'];
    $time = $tokenArr['time'];

?>
<div class="body">
    <div class="fieldTop">
        Token
    </div>
    <br /><br />
    <table class="table table-bordered table-hover table-striped" style="width: 100%">
        <tr>
            <td style="width: 150px;">
                Token
            </td>
            <td style=" word-break: break-all;">
                <?= $token ?>
            </td>
        </tr>
        <tr>
            <td>
                Data de criação
            </td>
            <td>
                <?= $data ?>
            </td>
        </tr>
        <tr>
            <td>
                Hora de criação
            </td>
            <td>
                <?= $time ?>
            </td>
        </tr>
    </table>
</div>
