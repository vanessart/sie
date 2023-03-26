<?php
if (!defined('ABSPATH'))
    exit;
if ($cates) {
    foreach ($cates as $k => $v) {
        $cates[$k]['at_cate'] = toolErp::simnao($v['at_cate']);
        $cates[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_cate'] . ')">Editar</button>';
        $cates[$k]['ac'] = formErp::submit('Uploads', null, ['id_evento' => $id_evento, 'id_cate' => $v['id_cate'], 'activeNav' => 4]);
    }
    $form['array'] = $cates;
    $form['fields'] = [
        'ID' => 'id_cate',
        'Nome' => 'n_cate',
        'Ordem'=>'ordem',
        'Ativo' => 'at_cate',
        '||1' => 'edit',
        '||2' => 'ac'
    ];
}
?>
<button class="btn btn-info" onclick="edit()">
    Nova Categoria
</button>
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/inscr/def/formCateSet.php" target="frame" id="form" method="POST">
    <input type="hidden" name="id_cate" id="id_cate" value="" />
    <?= formErp::hidden(['id_evento' => $id_evento]) ?>
</form>
<?php
toolErp::modalInicio(null, null, null, 'Cadastro de Categoria');
?>
<iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_cate.value = id;
        } else {
            id_cate.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>