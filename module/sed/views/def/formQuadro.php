<?php
if (!defined('ABSPATH'))
    exit;
$id_q = filter_input(INPUT_POST, 'id_q', FILTER_SANITIZE_NUMBER_INT);
if ($id_q) {
    $q = sql::get('sed_quadro', '*', ['id_q' => $id_q], 'fetch');
    if ($q['tp_ensino']) {
        $tp_ensino = explode(':', $q['tp_ensino']);
    } else {
        $tp_ensino = [];
    }
} else {
 $tp_ensino = [];   
}
$tpEnsino = sql::idNome('ge_tp_ensino');
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/sed/quadro" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input(null, 'ID', $id_q, ' readonly') ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[n_q]', 'Título', @$q['n_q'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_q]', @$q['descr_q'], 'Insira o texto aqui', null, 1) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_ini]', 'Iniciar', @$q['dt_ini'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Encerrar', @$q['dt_fim'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::select('1[at_q]', [0 => 'Não', 1 => 'Sim'], 'Ativo', @$q['at_q']) ?>
            </div>
        </div>
        <br />
        <?php
        foreach ($tpEnsino as $k => $v) {
            echo formErp::checkbox('tpe[' . $k . ']', 1, $v, (in_array($k, $tp_ensino) ? 1 : 0)) . '<br />';
        }
        ?>
        <div style="text-align: center">
            <?=
            formErp::hidden([
                '1[id_q]' => $id_q
            ])
            . formErp::hiddenToken('salvaQuadro')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
