<?php
if (!defined('ABSPATH'))
    exit;
$id_tel = filter_input(INPUT_POST, 'id_tel', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (!$id_pessoa) {
    die();
}
if ($id_tel) {
    $tel = sqlErp::get('telefones', '*', ['id_tel' => $id_tel], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Telefone
    </div>
    <form action="<?= HOME_URI ?>/admin/pessoaEdit" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[num]', 'Número', @$tel['num']) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[ddd]', 'DDD', @$tel['ddd'], null, null, 'number') ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('telefones_tipo', '1[fk_id_tt]', 'Tipo', @$tel['fk_id_tt']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 40px">
            <?=
            formErp::hidden([
                '1[id_tel]' => $id_tel,
                '1[fk_id_pessoa]' => $id_pessoa,
                'id_pessoa' => $id_pessoa,
                'activeNav' => 2
            ])
            . formErp::hiddenToken('telefones', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
