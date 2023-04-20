<?php
if (!defined('ABSPATH'))
    exit;
$id_func = filter_input(INPUT_POST, 'id_func', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (!$id_pessoa) {
    echo 'Algo errado não está certo. ;(';
    die();
}
if ($id_func) {
    $func = sqlErp::get('ge_funcionario', '*', ['id_func' => $id_func], 'fetch');
} else {
    $func['rm'] = uniqid();
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/admin/pessoaEdit" target="_parent" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input(null, 'Matrícula', @$func['rm'], ' readonly ') ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[funcao]', 'Função', @$func['funcao'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-6">
                <?= formErp::selectDB('instancia', '1[fk_id_inst]', 'Instâcia', @$func['fk_id_inst'], null, null, null, null, ' required ') ?>
            </div>
            <div class="col-3">
                <?= formErp::input('1[situacao]', 'Situacao', @$func['situacao']) ?>
            </div>
            <div class="col-3">
                <?= formErp::select('1[at_func]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$func['at_func']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 40px">
            <?=
            formErp::hidden([
                '1[id_func]' => $id_func,
                '1[fk_id_pessoa]' => $id_pessoa,
                '1[rm]' => $func['rm'],
                'id_pessoa' => $id_pessoa,
                'activeNav' => 4
            ])
            . formErp::hiddenToken('ge_funcionario', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
