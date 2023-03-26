<?php
if (!defined('ABSPATH'))
    exit;
$categorias = sql::get('recurso_cate_equipamento', 'id_cate, n_cate', ['at_cate' => 1]);
?>
<div class="fieldTop">
    <div class="fieldTop">
        Cadastro de Categoria
    </div>
    <div class="row">
        <div class="col-2">
            <button onclick="acesso()" class="btn btn-warning">
                Nova Categoria
            </button>
        </div>
    </div>
</div>
<br />
<?php
if (!empty($categorias)) {
    $token = formErp::token('delCate');
    foreach ($categorias as $k => $v) {
            $categorias[$k]['edit'] = formErp::button('Acessar', null, 'acesso(' . $v['id_cate'] .','. ' \'' . $v['n_cate']. '\' )');
            $categorias[$k]['del'] = formErp::submit('Apagar', $token, ['id_cate' => $v['id_cate']]);
    }
    $form['array'] = $categorias;
    $form['fields'] = [
        'ID' => 'id_cate',
        'Categoria' => 'n_cate',
        '||1' => 'del',
        '||2' => 'edit',
    ];
}
if (!empty($categorias)) {
    report::simple($form);
}

?>
<form id="formFrame" target="frame" action="" method="POST">
    <input type="hidden" id="id_cate" name="id_cate" value="" />
</form>
<?php
toolErp::modalInicio(null,'modal-md');
?>
<iframe style=" width: 100%; height: 50vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acesso(id,n_cate) {
        if (id) {
            document.getElementById('id_cate').value = id;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/cadCate.php";
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Categoria: '+n_cate+'</div>';
        } else {
            document.getElementById('id_cate').value = '';
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/cadCate.php";
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Nova Categoria</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>