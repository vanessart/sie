<?php
if (!defined('ABSPATH'))
    exit;
$user = sql::get(['pl_acesso', 'pessoa', 'pl_escola_externa'], 'id_pessoa, n_pessoa, pl_acesso.*, n_ee, id_ee, cie, cpf ', ['>' => 'n_pessoa']);
if ($user) {
    $token = formErp::token('userCadDel');
    foreach ($user as $k => $v) {
        $user[$k]['ac'] = '<button class="btn btn-info" onclick="edit(' . $v['id_pessoa'] . ')">Editar</button>';
        $user[$k]['del'] = formErp::submit('Excluir', $token, ['id_pessoa' => $v['id_pessoa']]);
        $user[$k]['snh'] = '<button style="height: 40px; width: 40px" class="btn btn-warning" onclick="document.getElementById('.$v['id_acesso'].').style.display=\'\'"></button><span  style="display: none" id="'.$v['id_acesso'].'">'.$v['senha'].'</span>';
    }
    $form['array'] = $user;
    $form['fields'] = [
        'Id' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'CPF'=>'cpf',
        'Escola' => 'n_ee',
        'CIE'=>'cie',
        'Senha'=>'snh',
        '||1' => 'del',
        '||2' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de usuário
    </div>
    <button class="btn btn-info" onclick="edit()">
        Novo Usuário
    </button>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form id="form" target="frame" action="<?= HOME_URI ?>/passelivre/def/formUser.php" method="POST">
    <input id="id_pessoa" type="hidden" name="id_pessoa" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh;border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById('id_pessoa').value = id;
        } else {
            document.getElementById('id_pessoa').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>