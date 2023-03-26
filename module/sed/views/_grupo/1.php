<?php
if (!defined('ABSPATH'))
    exit;
if ($gr) {
    $token = formErp::token('sed_grupo', 'delete');
    foreach ($gr as $k => $v) {
        $gr[$k]['at_gr'] = toolErp::simnao($v['at_gr']);
        $gr[$k]['cor'] = '<div style="background-color: ' . $v['cor'] . '; width: 100px; height: 20px"></div>';
        $gr[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_gr]' => $v['id_gr']]);
        $gr[$k]['edit'] = '<button class="btn btn-info" onclick="ng(' . $v['id_gr'] . ')">Editar</button>';
        $gr[$k]['ac'] = formErp::submit('Membros', null, ['id_gr' => $v['id_gr'], 'activeNav' => 2]);
    }
    $form['array'] = $gr;
    $form['fields'] = [
        'ID' => 'id_gr',
        'Nome do Grupo' => 'n_gr',
        'Ativo' => 'at_gr',
        'Cor' => 'cor',
        '||3' => 'del',
        '||2' => 'edit',
        '||1' => 'ac'
    ];
}
?>
<br />
<button class="btn btn-info" onclick="ng()">
    Novo Grupo
</button>
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form id="form" action="<?= HOME_URI ?>/sed/def/formGr.php" target="frame" method="POST">
    <input type="hidden" id="id_gr" name="id_gr" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; border: none; height: 80vh"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function ng(id) {
        if (id) {
            document.getElementById('id_gr').value = id;
        } else {
            document.getElementById('id_gr').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>