<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
if ($id_polo) {
    $dados = sql::get('maker_polo', '*', ['id_polo' => $id_polo], 'fetch');
}
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/maker/polos" method="POST">
        <div class="row">
            <div class="col-9">
                <?= formErp::input('1[n_polo]', 'Nome', @$dados['n_polo']) ?>
            </div>
            <div class="col-3">
                <?= formErp::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', empty($id_polo) ? 1 : $dados['ativo']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                '1[id_polo]' => $id_polo
            ])
            . formErp::hiddenToken('maker_polo', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
