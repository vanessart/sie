<?php
if (!defined('ABSPATH'))
    exit;
$cursos = sqlErp::get('ge_cursos', '*', ['fk_id_tp_ens' => $id_tp_ens]);
if ($cursos) {
    foreach ($cursos as $k => $v) {
        $cursos[$k]['ed'] = '<input class="btn btn-info" type="submit" onclick="edit(' . $v['id_curso'] . ')" value="Editar" />';
        $cursos[$k]['ac'] = formErp::submit('Ciclos', null, ['activeNav' => 3, 'id_curso' => $v['id_curso'], 'id_tp_ens'=>$id_tp_ens]);
        $cursos[$k]['ativo'] = toolErp::simnao($v['ativo']);
    }
    $form['array'] = $cursos;
    $form['fields'] = [
        'ID' => 'id_curso',
        'Nome' => 'n_curso',
        'Abrev.' => 'sg_curso',
        'Ativo' => 'ativo',
        '||1' => 'ed',
        '||2' => 'ac'
    ];
}
?>
<div class="Body">
    <div class="row">
        <div class="col-3" style="padding: 20px">
            <input class="btn btn-info" type="submit" onclick="edit()" value="Novo Curso" />
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form id="form" action="<?= HOME_URI ?>/sed/def/formCursoSet.php" target="frame" method="POST">
    <input type="hidden" name="id_curso" id="id_curso" />
    <input type="hidden" name="id_tp_ens" value="<?= $id_tp_ens ?>" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="widows: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById('id_curso').value = id;
        } else {
            document.getElementById('id_curso').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>