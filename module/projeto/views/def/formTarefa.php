<?php
if (!defined('ABSPATH'))
    exit;
$id_proj = filter_input(INPUT_POST, 'id_proj', FILTER_SANITIZE_NUMBER_INT);
$id_tar = filter_input(INPUT_POST, 'id_tar', FILTER_SANITIZE_NUMBER_INT);
if ($id_tar) {
    $dados = sql::get('projeto_tarefa', '*', ['id_tar' => $id_tar], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/projeto/projeto" target="_parent" method="POST">
        <div class="row">
            <div class="col-2">
                <?= formErp::input(null, 'ID', $id_tar, ' disabled ') ?>
            </div>
            <div class="col-10">
                <?= formErp::input('1[n_tar]', 'Tarefa', @$dados['n_tar'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[horas_previstas]', 'Horas Previstas', @$dados['horas_previstas'], null, null, 'number') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', @$dados['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', @$dados['dt_fim'], null, null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('projeto_tarefa_situacao', '1[fk_id_ts]', 'Situação', empty($id_tar) ? 1 : $dados['fk_id_ts']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[obs_tar]', @$dados['obs_tar'], 'Observações') ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 20px">
            <?=
            formErp::hidden([
                '1[id_tar]' => $id_tar,
                '1[fk_id_proj]' => $id_proj,
                'id_proj' => $id_proj,
                'activeNav' => 2
            ])
            . formErp::hiddenToken('tarefaSalvar')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
