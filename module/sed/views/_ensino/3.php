<?php
if (!defined('ABSPATH'))
    exit;
$ciclos = sql::get('ge_ciclos', '*', ['fk_id_curso' => $id_curso],);
if ($ciclos) {
    foreach ($ciclos as $k => $v) {
        $ciclos[$k]['ativo'] = toolErp::simnao($v['ativo']);
        $ciclos[$k]['ed'] = '<input class="btn btn-info" type="submit" onclick="edit(' . $v['id_ciclo'] . ')" value="Editar" />';
        $ciclos[$k]['ac'] = formErp::submit('grades', null, ['activeNav' => 4, 'id_curso' => $id_curso, 'id_tp_ens' => $id_tp_ens, 'id_ciclo' => $v['id_ciclo']]);
    }
    $form['array'] = $ciclos;
    $form['fields'] = [
        'ID' => 'id_ciclo',
        'Nome' => 'n_ciclo',
        'Abrev.' => 'sg_ciclo',
        'Ativo' => 'ativo',
        '||1' => 'ed',
        '||2' => 'ac'
    ];
}
?>
<br /><br /><br /><br />
<div class="Body">
    <div class="row">
        <div class="col-sm-3">
            <input class="btn btn-info" type="submit" onclick="edit()" value="Novo Ciclo" />
        </div>
    </div>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form target="frame" id="form" action="<?= HOME_URI ?>/sed/def/formCiclo.php" method="POST">
    <?=
    formErp::hidden([
        'id_tp_ens' => $id_tp_ens,
        'id_curso' => $id_curso
    ])
    ?>
    <input type="hidden" name="id_ciclo" id="id_ciclo" value="" />
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
            document.getElementById('id_ciclo').value = id;
        } else {
            document.getElementById('id_ciclo').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');

    }
</script>