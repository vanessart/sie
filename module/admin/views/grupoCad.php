<?php
if (!defined('ABSPATH'))
    exit;
$gr = sqlErp::get('grupo');
if ($gr) {
    $token = formErp::token('grupo', 'delete');
    foreach ($gr as $k => $v) {
        $gr[$k]['at_gr'] = toolErp::simnao($v['at_gr']);
        $gr[$k]['ac'] = '<button class="btn btn-info" onclick="ed(' . $v['id_gr'] . ')">Editar</button>';
        $gr[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_gr]' => $v['id_gr']]);
    }
    $form['array'] = $gr;
    $form['fields'] = [
        'ID' => 'id_gr',
        'Grupo' => 'n_gr',
        'Ativo' => 'at_gr',
        '||1' => 'del',
        '||2' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Grupos
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-info" onclick="ed()">
                Novo Grupo
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
<form action="<?= HOME_URI ?>/admin/def/formGr" target="frame" id="form" method="POST">
    <input type="hidden" name="id_gr" id="id_gr" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 40vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function ed(id) {
        if (id) {
            id_gr.value = id;
        } else {
            id_gr.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>