<?php
if (!defined('ABSPATH'))
    exit;
$id_ee = filter_input(INPUT_POST, 'id_ee', FILTER_SANITIZE_NUMBER_INT);
if ($id_ee) {
    @$esc = sql::get('pl_escola_externa', '*', ['id_ee' => $id_ee], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/passelivre/escolaCad" target="_parent" method="POST">
        <div class="row">
            <div class="col-9">
                <?= formErp::input('1[n_ee]', 'Nome', @$esc['n_ee'], ' required ') ?>
            </div>
            <div class="col-3">
                <?= formErp::input('1[cie]', 'CIE', @$esc['cie'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[endereco]', 'Endereço', @$esc['endereco']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[tel1]', 'Tel.', @$esc['tel1']) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[tel2]', 'Tel.', @$esc['tel2']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[email]', 'E-mail', @$esc['email']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[maps]', 'Geolocalização', @$esc['maps']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
                '1[id_ee]' => $id_ee
            ])
            . formErp::hiddenToken('pl_escola_externa', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>