<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$form = filter_input(INPUT_POST, 'id_inscr', FILTER_SANITIZE_NUMBER_INT);
?>
<div style="text-align: center">
    <img style="width: 300px" src="<?= HOME_URI ?>/includes/images/assinco/MeuFuturo_logo2.png"/>
</div>
<div style="text-align: center; font-weight: bold; font-size: 20px; padding: 10px; background-color: #ACC84D">
    Centro de Qualificação Profissional
    <br />
    Secretaria de Indústria, Comércio e Trabalho
</div>
<br />
<?php
include ABSPATH . '/module/quali/views/_deferido/body.php';
$pdf = new pdf();
$pdf->headerSet = null;
$pdf->mgt = 0;
$pdf->exec('Aprovados');
