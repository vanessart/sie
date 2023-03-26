<?php
if (!defined('ABSPATH'))
    exit;
$polos = sql::get('maker_polo', '*', ['>' => 'id_polo']);
$esc_ = sql::get(['maker_escola', 'instancia'], 'fk_id_polo, n_inst', ['sede' => 1]);
foreach ($esc_ as $v) {
    $sede[$v['fk_id_polo']] = $v['n_inst'];
}
if ($polos) {
    foreach ($polos as $k => $v) {
        $polos[$k]['edit'] = '<button class="btn btn-primary" onclick="edit(' . $v['id_polo'] . ')">Editar</button>';
        $polos[$k]['esc'] = formErp::submit('Escolas', null, ['activeNav' => 2, 'id_polo' => $v['id_polo']]);
        $polos[$k]['t'] = formErp::submit('Turmas', null, ['activeNav' => 3, 'id_polo' => $v['id_polo']]);
        $polos[$k]['ativo'] = toolErp::simnao($v['ativo']);
        $polos[$k]['sede'] = @$sede[$v['id_polo']];
    }
    $form['array'] = $polos;
    $form['fields'] = [
        'Nome' => 'n_polo',
        'Sede' => 'sede',
        'Ativo' => 'ativo',
        '||1' => 'edit',
        '||3' => 'esc',
        '||2' => 't',
    ];
}
?>
<div class="row">
    <div class="col">
        <button class="btn btn-primary" onclick="edit()">
            Novo Polo
        </button>
    </div>
</div>
<br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/maker/def/formPolo" target="frame" id="form" method="POST">
    <input type="hidden" name="id_polo" id="id_polo" />
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
            id_polo.value = id;
        } else {
            id_polo.value = "";
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>