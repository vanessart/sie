<?php
if (!defined('ABSPATH'))
    exit;
$id_me = filter_input(INPUT_POST, 'id_me', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$idInst = escolas::idInst();
if ($id_me) {
    $dados = sql::get('maker_escola', '*', ['id_me' => $id_me], 'fetch');
}
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/maker/polos" method="POST">
        <div class="row">
            <div class="col-9">
                <?= formErp::select('1[fk_id_inst]', $idInst, 'Escola', @$dados['fk_id_inst']) ?>
            </div>
            <div class="col-3">
                <?= formErp::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', empty($id_me) ? 1 : $dados['ativo']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[sede]', [1 => 'Sim', null => 'Não'], 'Sede', @$dados['sede']) ?>
            </div>

        </div>
        <br />
        <div class="row">

            <div class="col">
                <?= formErp::input('1[cota_m]', 'Cota da Manhã', (empty($dados['cota_m']) ? 80 : $dados['cota_m']), null, null, 'number') ?>
            </div>

            <div class="col">
                <?= formErp::input('1[cota_t]', 'Cota da Tarde', (empty($dados['cota_t']) ? 80 : $dados['cota_t']), null, null, 'number') ?>
            </div>

            <div class="col">
                <?= formErp::input('1[cota_n]', 'Cota da Noite', @$dados['cota_n'], null, null, 'number') ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                '1[id_me]' => $id_me,
                '1[fk_id_polo]' => $id_polo,
                'id_polo' => $id_polo,
                'activeNav' => 2
            ])
            . formErp::hiddenToken('maker_escola', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>