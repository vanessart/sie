<?php
if (!defined('ABSPATH'))
    exit;
$id_mp = filter_input(INPUT_POST, 'id_mp');
if ($id_mp) {
    $m = sql::get('profe_msg_prof', '*', ['id_mp' => $id_mp], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Avisos para os Professores
    </div>
    <form target="_parent" action="<?= HOME_URI ?>/profe/profMsg" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input(null, 'ID', @$m['id_mp'], 'desabled') ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[n_mp]', 'Título', @$m['n_mp']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_mp]', @$m['descr_mp'], 'Texto', null, 1) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[tp_prof]', [12 => 'Todos', 1 => 'Fundamental', 2 => 'Infantil'], 'Segmento', empty($m['tp_prof']) ? 12 : $m['tp_prof']) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', empty($id_mp) ? date("Y-m-d") : $m['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', empty($id_mp) ? date("Y-m-d") : $m['dt_fim'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::select('1[at_mp]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$m['at_mp']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 50px">
            <?=
            formErp::hidden([
                '1[id_mp]' => $id_mp,
                '1[fk_id_pessoa]' => toolErp::id_pessoa()
            ])
            . formErp::hiddenToken('profe_msg_prof', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>