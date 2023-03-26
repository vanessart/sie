<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = $model->gerente(1);
$locais = sql::get('recurso_local', 'id_local, n_local', ['at_local' => 1,'fk_id_inst' => $id_inst]);
?>
<div class="fieldTop">
    <div class="fieldTop">
        Cadastro de Local / Armazenamento
    </div>
    <div class="row">
        <div class="col-2">
            <button onclick="acesso()" class="btn btn-warning">
                Novo Local / Armazenamento
            </button>
        </div>
    </div>
</div>
<br />
<?php
if (!empty($locais)) {
    $token = formErp::token('delLocal');
    foreach ($locais as $k => $v) {
            $locais[$k]['edit'] = formErp::button('Acessar', null, 'acesso(' . $v['id_local'] .','. ' \'' . $v['n_local']. '\' )');
            $locais[$k]['del'] = formErp::submit('Apagar', $token, ['id_local' => $v['id_local']],null,null, "Deseja apagar este Local?");
    }
    $form['array'] = $locais;
    $form['fields'] = [
        'ID' => 'id_local',
        'Categoria' => 'n_local',
        '||1' => 'del',
        '||2' => 'edit'
    ];
}
if (!empty($locais)) {
    report::simple($form);
}?>

<form id="formFrame" target="frame" action="" method="POST">
    <input type="hidden" id="id_local" name="id_local" value="" />
</form>
<?php
toolErp::modalInicio(null,'modal-md');
?>
<iframe style=" width: 100%; height: 50vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acesso(id,n_local) {
        if (id) {
            document.getElementById('id_local').value = id;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/cadLocal.php";
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Local: '+n_local+'</div>';
        } else {
            document.getElementById('id_local').value = '';
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/cadLocal.php";
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Novo Local / Armazenamento</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>