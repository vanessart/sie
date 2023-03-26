<?php
if (!defined('ABSPATH'))
    exit;
$ups = sql::get('inscr_upload', '*', ['fk_id_evento' => $id_evento, '>' => 'ordem', 'fk_id_cate' => '0']);
if ($ups) {
    foreach ($ups as $k => $v) {
        $ups[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_up'] . ')">Editar</button>';
        $ups[$k]['obrigatorio'] = toolErp::simnao($v['obrigatorio']);
    }
    $form['array'] = $ups;
    $form['fields'] = [
        'ID' => 'id_up',
        'Nome' => 'descr_up',
        'Ordem' => 'ordem',
        'Obrigatório' => 'obrigatorio',
        'Quantidade' => 'quant',
        'Pontos' => 'pontos',
        '||1' => 'edit',
    ];
}
?>
<button class="btn btn-info" onclick="edit()">
    Novo Upload Genérico
</button>
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/inscr/def/formUpSet.php" target="frame" id="form" method="POST">
    <input type="hidden" name="id_up" id="id_up" value="" />
    <?=
    formErp::hidden([
        'id_evento' => $id_evento,
        'activeNav'=> 2
    ])
    ?>
</form>
<?php
toolErp::modalInicio(null, null, null, 'Cadastro de Upload Genérico');
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_up.value = id;
        } else {
            id_up.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>