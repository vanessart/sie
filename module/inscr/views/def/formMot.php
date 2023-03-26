<?php
if (!defined('ABSPATH'))
    exit;
$id_mot = filter_input(INPUT_POST, 'id_mot', FILTER_SANITIZE_NUMBER_INT);
if ($id_mot) {
    $mot = sql::get('inscr_motivo', '*', ['id_mot' => $id_mot], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/inscr/motivo" target="_parent" method="POST">
        <div class="row">
            <div class="col-2">
                <?= formErp::input(null, 'ID', $id_mot, ' disabled ') ?>
            </div>
            <div class="col-10">
                <?= formErp::input('1[n_mot]', 'Motivo', @$mot['n_mot']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                '1[id_mot]' => $id_mot
            ])
            . formErp::hiddenToken('inscr_motivo', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
