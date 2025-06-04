<?php
if (!defined('ABSPATH'))
    exit;
$id_ag = filter_input(INPUT_POST, 'id_ag', FILTER_SANITIZE_NUMBER_INT);
if ($id_ag) {
    $ag = sqlErp::get($model::$sistema . '_aval_group', '*', ['id_ag' => $id_ag], 'fetch');
}
$per = $model->periodoLetivos(1);
$id_pl = ng_main::periodoSet();
?>

<div class="body">
    <div class="fieldTop">
        Cadastro de Grupo de Avaliações
    </div>
    <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/avalGroup" target="_parent" method="POST">
        <div class="row"> 
            <div class="col"> 
                <?= formErp::input('1[n_ag]', 'Nome do Agrupamento', @$ag['n_ag'], ' required') ?>
            </div>
        </div>
        <br />
        <div class="row"> 
            <div class="col"> 
                <?= formErp::input(null, 'ID', $id_ag, ' readonly') ?>
            </div>
            <div class="col">
                <?= formErp::select('1[fk_id_pl]', $per, 'Período Letivo', (empty($ag['fk_id_pl']) ? $id_pl : $ag['fk_id_pl']), null, null, ' required ') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_ag]', 'Data', @$ag['dt_ag'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::select('1[at_ag]', [0 => 'Não', 1 => 'Sim'], 'Ativo', (empty($id_ag) ? 1 : $ag['at_ag']), null, null, ' required ') ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 50px">
            <?=
            formErp::hidden([
                '1[id_ag]' => $id_ag
            ])
            . formErp::hiddenToken($model::$sistema . '_aval_group', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>