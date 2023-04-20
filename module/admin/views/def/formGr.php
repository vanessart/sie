<?php
if (!defined('ABSPATH'))
    exit;
$id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
if ($id_gr) {
    @$gr = sqlErp::get('grupo', '*', ['id_gr' => $id_gr], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Grupos
    </div>
    <form action="<?= HOME_URI ?>/admin/grupoCad" method="POST" target="_parent">
        <div class="row">
            <div class="col-2">
                <?= formErp::input(null, 'ID', @$gr['id_gr'], ' disabled ') ?>
            </div>
            <div class="col-7">
                <?= formErp::input('1[n_gr]', 'Grupo', @$gr['n_gr'], ' required ') ?>
            </div>
            <div class="col-3">
                <?= formErp::select('1[at_gr]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$gr['at_gr']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 40px">
            <?=
            formErp::hidden([
                '1[id_gr]' => $id_gr,
            ])
            . formErp::hiddenToken('grupo', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
