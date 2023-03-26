<?php
if (!defined('ABSPATH'))
    exit;
$mot = sql::get('inscr_motivo');
if ($mot) {
    foreach ($mot as $k => $v) {
        $mot[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_mot'] . ')">Editar</button>';
    }
    $form['array'] = $mot;
    $form['fields'] = [
        'ID' => 'id_mot',
        'Motivo' => 'n_mot',
        '||1'=>'edit'
    ];
}

?>
<div class="body">
    <div class="fieldTop">
        Cadastro de motivos de não deferimento
    </div>
    <button class="btn btn-info" onclick="edit()">
        Novo Motivo
    </button>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form id="form" target="frame" action="<?= HOME_URI ?>/inscr/def/formMot.php" method="POST">
    <input type="hidden" name="id_mot" id="id_mot" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 60vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_mot.value = id;
        } else {
            id_mot.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>