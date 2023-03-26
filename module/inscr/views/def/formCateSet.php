<?php
if (!defined('ABSPATH'))
    exit;
$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_SANITIZE_NUMBER_INT);
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
if ($id_cate) {
    $cate = sql::get('inscr_categoria', '*', ['id_cate' => $id_cate], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/inscr/eventoSet" target="_parent" method="POST">
        <div class="row">
            <div class="col-2">
                <?= formErp::input(null, 'ID', $id_cate, ' desabled ') ?>
            </div>
            <div class="col-10">
                <?= formErp::input('1[n_cate]', 'Nome', @$cate['n_cate'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_cate]', @$cate['descr_cate'], 'Descrição') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-3">
                <?= formErp::select('1[at_cate]', ['Não', 'Sim'], 'Ativo', (empty($id_cate) ? 1 : $cate['at_cate'])) ?>
            </div>
                <div class="col-3">
                    <?= formErp::selectNum('1[ordem]', [1,20], 'Ordem', @$cate['ordem']) ?>
    </div>
    </div>
        <br />
        <div style="text-align: center; padding: 10px">
            <?=
            formErp::hidden([
                '1[id_cate]' => $id_cate,
                '1[fk_id_evento]' => $id_evento,
                'activeNav' => 3,
                'id_evento' => $id_evento
            ])
            . formErp::hiddenToken('inscr_categoria', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
