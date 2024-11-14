<?php
if (!defined('ABSPATH'))
    exit;
$polos = sql::get($model::$sistema . '_polo');
foreach ($polos as $k => $v) {
    $polos[$k]['ac'] = '<button class="btn btn-info" onclick="edit(' . $v['id_polo'] . ')">Editar</button>';
    $polos[$k]['at'] = toolErp::simnao($v['ativo']);
}
$form['array'] = $polos;
$form['fields'] = [
    'ID' => 'id_polo',
    'Núcleo' => 'n_polo',
    'Ativo' => 'at',
    '||1' => 'ac'
];
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Núcleo
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary" onclick="edit()">Novo Núcleo</button>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formPolo" target="frame" id="form" method="POST">
    <input type="hidden" name="id_polo" id="id_polo" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_polo.value = id;
        } else {
            id_polo.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>