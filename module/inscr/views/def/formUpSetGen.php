<?php
if (!defined('ABSPATH'))
    exit;
$id_up = filter_input(INPUT_POST, 'id_up', FILTER_SANITIZE_NUMBER_INT);
$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_SANITIZE_NUMBER_INT);
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
if ($id_up) {
    $up = sql::get('inscr_upload_generico', '*', ['id_up' => $id_up], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/inscr/eventoSet" target="_parent" method="POST">
        <div class="row">
            <div class="col-2">
                <?= formErp::input(null, 'ID', $id_up) ?>
            </div>
            <div class="col-10">
                <?= formErp::input('1[n_up]', 'Nome', @$up['n_up']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_up]', @$up['descr_up'], 'Descrição') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::selectNum('1[ordem]', [1, 20], 'Ordem', @$up['ordem']) ?>
            </div>
            <div class="col">
                <?= formErp::checkbox('1[obrigatorio]', 1, 'Obrigatório', @$up['obrigatorio']) ?>
            </div>
            <div class="col">
                <?= formErp::selectNum('1[quant]', [1, 20], 'Quantidade', (empty($id_up)?1:$up['quant'])) ?>
            </div>
            <div class="col">
                <?= formErp::selectNum('1[pontos]', [1, 20], ['Pontos', 0], @$up['pontos']) ?>
            </div>

        </div>
        <br />
        <div style="text-align: center; padding: 10px">
            <?=
            formErp::hidden([
                '1[id_up]' => $id_up,
                '1[fk_id_evento]' => $id_evento,
                'id_cate' => $id_cate,
                'id_evento' => $id_evento,
                'activeNav' => 2
            ])
            . formErp::hiddenToken('inscr_upload_generico', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
