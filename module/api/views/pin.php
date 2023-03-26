<?php
if (!defined('ABSPATH'))
    exit;
$u = sql::get(['api_user', 'pessoa'], 'id_pessoa, n_pessoa, api, id_user ');
if ($u) {
    $token = formErp::token('api_user', 'delete');
    foreach ($u as $k => $v) {
        
        $u[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_user]' => $v['id_user']]);
    }
    $form['array'] = $u;
    $form['fields'] = [
        'ID' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'API' => 'api',
        '||1' => 'del'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Acesso as APIs
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-info" onclick="edit()">
                Novo usuário
            </button>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form id="form" target="frame" action="<?= HOME_URI ?>/api/def/formUser" method="POST">
    <input type="hidden" name="id_user" id="id_user" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_user.value = id;
        } else {
            id_user.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>