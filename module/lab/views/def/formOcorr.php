<?php
if (!defined('ABSPATH'))
    exit;
$id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_ce)) {
    $empretimo = $model->emppretimo($id_ce, 1);
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/lab/emprestRede" target="_parent" method="POST">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Funcionário
                </td>
                <td>
                    <?= $empretimo['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Serial do Equipamenrto
                </td>
                <td>
                    <?= $empretimo['serial'] ?>
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('lab_chrome_emprestimo_ocorrencia_tipo', '1[fk_id_ceot]', 'Ocorrência', null, null, null, null, null, ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[obs]') ?>
            </div>
        </div>
        <br />
        <div class="text-center">
            <?=
            formErp::hidden([
                'id_ce' => $id_ce,
                '1[fk_id_ce]' => $id_ce,
                '1[fk_id_pessoa]' => $empretimo['id_pessoa'],
                '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                'activeNav' => 2
            ])
            . formErp::hiddenToken('lab_chrome_emprestimo_ocorrencia', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
