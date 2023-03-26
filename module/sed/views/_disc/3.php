<?php
if (!defined('ABSPATH'))
    exit;
$grades = disciplina::grade();
if ($grades) {
    $token = formErp::token('ge_grades', 'delete');
    foreach ($grades as $k => $v) {
        $grades[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_grade]' => $v['id_grade'], 'activeNav' => 3]);
        $grades[$k]['ac'] = '<button class="btn btn-info" onclick="nova(' . $v['id_grade'] . ')">Editar</button>';
        $grades[$k]['aloca'] = formErp::submit('Alocar Disciplinas', null, ['id_grade' => $v['id_grade'], 'activeNav' => 4]);
    }
    $form['array'] = $grades;
    $form['fields'] = [
        'ID' => 'id_grade',
        'Área' => 'n_grade',
        '||1' => 'del',
        '||2' => 'ac',
        '||3' => 'aloca'
    ];
}
?>
<div class="row">
    <div class="col">
        <button class="btn btn-primary" onclick="nova()">
            Nova Grade
        </button>
    </div>
</div>
<br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/sed/def/formGradeSet.php" target="frame" id="form" method="POST">
    <input type="hidden" name="id_grade" id="id_grade" value=""/>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 50vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function nova(id) {
        if (id) {
            id_grade.value = id;
        } else {
            id_grade.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>