<?php
if (!defined('ABSPATH'))
    exit;
$atrib = '';
?>
<button class="btn btn-info" onclick="ed()" >
    Novo Telefone
</button>
<br /><br />
<?php
$tel = sqlErp::get(['telefones', 'telefones_tipo'], '*', ['fk_id_pessoa' => $id_pessoa]);
if ($tel) {
    $token = formErp::token('telefones', 'delete');
    foreach ($tel as $k => $v) {
        $tel[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_tel]' => $v['id_tel'], 'id_pessoa' => $id_pessoa, 'activeNav' => 2]);
        $tel[$k]['ac'] = '<button class="btn btn-info" onclick="ed(' . $v['id_tel'] . ')" >Editar</button>';
    }
    $form['array'] = $tel;
    $form['fields'] = [
        'Número' => 'num',
        'DDD' => 'ddd',
        'Tipo' => 'n_tt',
        'Complemento' => 'complemento',
        '||2' => 'del',
        '||1' => 'ac'
    ];
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/admin/def/formTel" target="frame" id="form" method="POST">
    <input type="hidden" name="id_tel" id="id_tel" value="" />
    <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
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
            id_tel.value = id;
        } else {
            id_tel.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>