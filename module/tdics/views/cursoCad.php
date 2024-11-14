<?php
if (!defined('ABSPATH'))
    exit;
$cursos = sql::get($model::$sistema . '_curso');
if ($cursos) {
    foreach ($cursos as $k => $v) {
        $cursos[$k]['ac'] = '<button class="btn btn-info" onclick="edit(' . $v['id_curso'] . ')">Editar</button>';
    }
    $form['array'] = $cursos;
    $form['fields'] = [
        'ID' => 'id_curso',
        'Polo' => 'n_curso',
        'Sigla' => 'abrev',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Cursos
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary" onclick="edit()">Novo Curso</button>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formCurso" target="frame" id="form" method="POST">
    <input type="hidden" name="id_curso" id="id_curso" value="" />
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
            id_curso.value = id;
        } else {
            id_curso.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>