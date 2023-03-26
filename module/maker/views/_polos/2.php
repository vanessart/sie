<?php
if (!defined('ABSPATH'))
    exit;
$esc = sqlErp::get(['maker_escola', 'instancia'], '*', ['>' => 'sede, n_inst', 'fk_id_polo' => $id_polo]);

if ($esc) {
    foreach ($esc as $k => $v) {
        $esc[$k]['edit'] = '<button class="btn btn-primary" onclick="edit(' . $v['id_me'] . ')">Editar</button>';
        $esc[$k]['sede'] = toolErp::simnao($v['sede']);
        $esc[$k]['ativo'] = toolErp::simnao($v['ativo']);
    }
    $form['array'] = $esc;
    $form['fields'] = [
        'Escola' => 'n_inst',
        'Sede' => 'sede',
        'Manhã' => 'cota_m',
        'Tarde' => 'cota_t',
        'Noite' => 'cota_n',
        'Ativo' => 'ativo',
        '||1' => 'edit',
    ];
}
?>
<div class="row">
    <div class="col">
        <button class="btn btn-primary" onclick="edit()">
            Incluir Escola
        </button>
    </div>
</div>
<br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/maker/def/formEsc" target="frame" id="form" method="POST">
    <input type="hidden" name="id_polo" value="<?= $id_polo ?>" />
    <input type="hidden" name="id_me" id="id_me" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 40vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_me.value = id;
        } else {
            id_me.value = "";
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>