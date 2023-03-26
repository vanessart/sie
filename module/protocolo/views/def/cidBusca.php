<?php
if (!defined('ABSPATH'))
    exit;
$hidden = @$_POST["hidden"];
$pesquisaCid = filter_input(INPUT_POST, 'pesquisaCid', FILTER_SANITIZE_STRING);
if (!empty($pesquisaCid)) {
   $cid = $model->cidGet($pesquisaCid,$hidden); 
}
?>
<form method="POST" target="frame">
    <div class="row">
        <div class="col-8">
            <?= formErp::input('pesquisaCid', 'Informe o Código ou a Descrição: ', $pesquisaCid)?>
        </div>
        <div class="col-2">
            <?php foreach ($hidden as $key => $value) {
                echo '<input type="hidden" name="hidden['. $key .']" value="'. $value .'" >';
            } ?>
            <?=
                formErp::hidden($hidden);?> 
            <?= formErp::button('Pesquisar') ?>
        </div>
    </div>
</form>
<br>
<div class="row">
    <?php
    if (!empty($cid)) {
        report::simple($cid);
    } elseif ($pesquisaCid) {
        toolErp::divAlert('warning','CID não encontrado');
    }?>
</div>