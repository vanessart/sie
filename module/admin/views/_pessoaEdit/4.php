<?php
if (!defined('ABSPATH'))
    exit;
$func = sqlErp::get(['ge_funcionario', 'instancia'], 'id_func, rm, funcao, situacao, at_func, id_inst, n_inst', ['fk_id_pessoa' => $id_pessoa]);
if ($func) {
    $token = formErp::token('ge_funcionario', 'delete');
    foreach ($func as $k => $v) {
        $func[$k]['at_func'] = toolErp::simnao($v['at_func']);
        $func[$k]['ac'] = '<button class="btn btn-primary" onclick="novo(' . $v['id_func'] . ')">editar</button>';
        if (!is_numeric($v['rm'])) {
            $func[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_func]' => $v['id_func'], 'activeNav'=>4, 'id_pessoa'=>$id_pessoa]);
        } else {
            $func[$k]['del'] = '<button type="button" class="btn btn-secondary">Apagar</button>';
        }
    }
    $form['array'] = $func;
    $form['fields'] = [
        'ID' => 'id_func',
        'Matrícula' => 'rm',
        'Função' => 'funcao',
        'Instância' => 'n_inst',
        'Situação' => 'situacao',
        'Ativo' => 'at_func',
        '||2' => 'del',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <button class="btn btn-primary" onclick="novo()">
        Nova Matrícula
    </button>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/admin/def/formFunc" target="frame" id="form" method="POST">
    <input type="hidden" name="id_func" id="id_func" value="" />
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
    function novo(id) {
        if (id) {
            id_func.value = id;
        } else {
            id_func.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>