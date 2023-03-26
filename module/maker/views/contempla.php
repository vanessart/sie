<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
if ($id_inst) {
    $qt = sql::get('maker_nao_contempla', '*', ['id_inst_nc' => $id_inst], 'fetch');
    if (!empty($qt['times_stamp'])) {
        $data = data::converteBr($qt['times_stamp']);
        $timesStamp = $data . ' ' . substr($qt['times_stamp'], 11);
    } else {
        $timesStamp = null;
    }
}
?>
<div class="body">
    <?php
    if (toolErp::id_nilvel() != 8) {
        ?>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
            </div>
        </div>
        <br />
        <?php
    }
    if ($id_inst) {
        ?>
        <div class="fieldTop">
            Fichas Não Contempladas
        </div>
        <form method="POST">
            <table class="table table-bordered table-hover table-striped" style="width: 50%; margin: auto">
                <tr>
                    <td>

                    </td>
                    <td>
                        Quantidade de Fichas Não Contempladas
                    </td>
                    <td>
                        Última Atualização
                    </td>
                </tr>
                <tr>
                    <td>
                        Tarde
                    </td>
                    <td>
                        <?= formErp::input('1[qt_tarde]', null, @$qt['qt_tarde'], null, null, 'number') ?>
                    </td>
                    <td>
                        <?= formErp::input(null, null, $timesStamp, 'readOnly') ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Manhã
                    </td>
                    <td>
                        <?= formErp::input('1[qt_manha]', null, @$qt['qt_manha'], null, null, 'number') ?>
                    </td>
                    <td>
                        <?= formErp::input(null, null, $timesStamp, 'readOnly') ?>
                    </td>
                </tr>
                </tr>
                <tr>
                    <td>
                        Total Geral
                    </td>
                    <td colspan="2">
                        <div style="width: 50%;">
                            <?= formErp::input(null, null, intval(@$qt['qt_manha'] + @$qt['qt_tarde']), 'readOnly', null, 'number') ?>
                        </div>
                    </td>
                </tr>
            </table>
            <div style="text-align: center; padding: 20px">
                <?=
                formErp::hidden([
                    '1[id_inst_nc]' => $id_inst,
                    'id_inst' => $id_inst
                ])
                . formErp::hiddenToken('maker_nao_contempla', 'ireplace')
                . formErp::button("Salvar")
                ?>
            </div>
        </form>
    </div>
    <?php
}