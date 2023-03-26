<?php
if (!defined('ABSPATH'))
    exit;

$lota = $model->lotacao();
if ($lota) {
    foreach ($lota as $k => $v) {
        $lota[$k]['ac'] = '<button class="btn btn-info" onclick="edit(' . $v['id_cit'] . ')">Editar</button>';
    }
    $form['array'] = $lota;
    $form['fields'] = [
        'ID da Lotação' => 'id_cit',
        'Local de Trabalho (CIT)' => 'local_trabalho',
        'ID Instância' => 'id_inst',
        'Instância' => 'n_inst',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Configuração Lotação/Instâcia
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary" onclick="edit()">
                Nova Lotação
            </button>
        </div>
    </div>
    <?php
    if ($form) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/cit/def/lotacao" id="form" target="frame" method="POST">
    <input type="hidden" name="id_cit" id="id_cit" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="border: none; width: 100%; height: 40vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>




<script>
    function edit(id) {
        if (id) {
            id_cit.value = id;
        } else {
            id_cit.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>