<?php
if (!defined('ABSPATH'))
    exit;
$id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_pessoa() == 1) {
    $grh = ['>' => 'n_gr'];
} else {
    $grh = ['>' => 'n_gr', 'at_gr' => 1];
}
if (!empty($id_gr)) {
    $user = $model->userGr($id_gr);
    $token = formErp::token('acesso_pessoa', 'delete');
    foreach ($user as $k => $v) {
        $user[$k]['acesso'] = formErp::submit('Acessar Usuário', NULL, ['id_pessoa' => $v['id_pessoa']], HOME_URI . '/admin/pessoaEdit', null, null, null, 'white-space: nowrap');
        $user[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_ac]' => $v['id_ac'], 'id_gr' => $id_gr]);
    }
    $form['array'] = $user;
    $form['fields'] = [
        'ID' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'CPF' => 'cpf',
        'Instância' => 'n_inst',
        '||2' => 'del',
        '||1' => 'acesso'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciamento de Acesso a Grupos 
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::selectDB('grupo', 'id_gr', 'Grupo', $id_gr, 1, null, null, $grh) ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($id_gr)) {
        report::simple($form);
    }
    ?>
</div>
