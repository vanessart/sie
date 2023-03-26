<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
if ($id_polo) {
    $p = sql::get('tdics_polo', '*', ['id_polo' => $id_polo], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Núcleo
    </div>
    <form action="<?= HOME_URI ?>/tdics/poloCad" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_polo]', 'Polo', @$p['n_polo']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[ativo]', [1 => 'Sim', 2 => 'Não'], 'Ativo', @$p['ativo']) ?>
            </div>
            <div class="col">
                <div style="text-align: center; padding: 5px">
                    <?=
                    formErp::hidden([
                        '1[id_polo]' => $id_polo
                    ])
                    . formErp::hiddenToken('tdics_polo', 'ireplace')
                    . formErp::button('Salvar')
                    ?>
                </div>
            </div>
        </div>
        <br />
    </form>
</div>