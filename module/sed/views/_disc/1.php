<?php
if (!defined('ABSPATH'))
    exit;
$areas = disciplina::areas();
if ($areas) {
    $token = formErp::token('ge_areas', 'delete');
    foreach ($areas as $k => $v) {
        $areas[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_area]' => $v['id_area']]);
        $areas[$k]['ac'] = '<button class="btn btn-info" onclick="nova(' . $v['id_area'] . ')">Editar</button>';
    }
    $form['array'] = $areas;
    $form['fields'] = [
        'ID' => 'id_area',
        'Área' => 'n_area',
        'Sigla' => 'sg_area',
        '||1' => 'del',
        '||2' => 'ac'
    ];
}
?>
<div class="row">
    <div class="col">
        <button class="btn btn-primary" onclick="nova()">
            Nova Área de Conhecimento
        </button>
    </div>
</div>
<br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/sed/def/formAreaCon.php" target="frame" id="form" method="POST">
    <input type="hidden" name="id_area" id="id_area" value=""/>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 50vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function nova(id) {
        if (id) {
            id_area.value = id;
        } else {
            id_area.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>