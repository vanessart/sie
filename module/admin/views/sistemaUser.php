<?php
if (!defined('ABSPATH'))
    exit;
$id_sistema = filter_input(INPUT_POST, 'id_sistema', FILTER_SANITIZE_NUMBER_INT);
$sis = sqlErp::idNome('sistema');
if ($id_sistema) {
    $sisUser = $model->sisUser($id_sistema);
    foreach ($sisUser as $k => $v) {
        $sisUser[$k]['user'] = $v['id_pessoa'];
        $sisUser[$k]['n_gr'] = toolErp::virgulaE(explode(',', $v['n_gr']));
    }
    $form['array'] = $sisUser;
    $form['fields'] = [
        'ID' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'CPF' => 'cpf',
        'E-mail' => 'emailgoogle',
        'Grupos' => 'n_gr',
        'Instância' => 'n_inst'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciamento de Acesso a Sistemas 
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::selectDB('sistema', 'id_sistema', 'Sistema', $id_sistema, 1, null, null, ['>' => 'n_sistema', 'ativo' => 1]) ?>
        </div>
    </div>
    <br />
    <?php
    if ($id_sistema) {
        report::forms($form, NULL, ['id_sistema' => $id_sistema], HOME_URI . '/admin/pessoaEdit');
    }
    ?>
</div>
