<?php
if (!defined('ABSPATH'))
    exit;
$id_proj = filter_input(INPUT_POST, 'id_proj', FILTER_SANITIZE_NUMBER_INT);
if ($id_proj) {
    $proj = sql::get('projeto_projeto', '*', ['id_proj' => $id_proj], 'fetch');
} else {
    $proj = null;
}
?>
<div class="body">
    <div class="fieldTop">
        Projeto
    </div>
    <form action="<?= HOME_URI ?>/projeto/projeto" target="_parent" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input(null, 'ID', $id_proj, ' disabled ') ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[n_proj]', 'Nome', empty($proj['n_proj'])?null:$proj['n_proj']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', @$proj['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', @$proj['dt_fim'], null, null, 'date') ?>
                '            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[at_proj]', [0 => 'Não', 1 => 'Sim'], 'Ativo', @$proj['at_proj']) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('projeto_setor', '1[fk_id_setor]', 'Setor', @$proj['fk_id_setor']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_proj]', @$proj['descr_proj'], 'Descrição') ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 10pz">

            <?=
            formErp::hidden([
                '1[id_proj]' => $id_proj
            ])
            . formErp::hiddenToken('projeto_projeto', 'ireplace')
            . formErp::button("Salvar")
            ?>
        </div>
    </form>
</div>
