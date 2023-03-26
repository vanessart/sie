<?php
if (!defined('ABSPATH'))
    exit;
$inst = sql::idNome('instancia');
$id_cit = filter_input(INPUT_POST, 'id_cit', FILTER_SANITIZE_NUMBER_INT);
if ($id_cit) {
    $cit = sql::get('func_integra', '*', ['id_cit' => $id_cit], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/cit/idLotacao" target="_parent" method="POST">
        <div class="row">
            <div class="col - 4">
                <?php
                if ($id_cit) {
                    echo formErp::input(null, 'Id da Lotação', $id_cit, ' readonly ')
                    . formErp::hidden(['1[id_cit]' => $id_cit]);
                } else {
                    echo formErp::input('1[id_cit]', 'Id da Lotação', $id_cit);
                }
                ?>
            </div>
            <div class="col-8">
                <?php
                if ($id_cit) {
                    echo formErp::input(null, 'Lotação', $cit['local_trabalho'], ' readonly ');
                } else {
                    echo formErp::input('1[local_trabalho]', 'Lotação');
                }
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_inst]', $inst, 'Instância', @$cit['fk_id_inst']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hiddenToken('func_integra', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
