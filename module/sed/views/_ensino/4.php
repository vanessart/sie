<?php
if (!defined('ABSPATH'))
    exit;
$incl = sql::get(['ge_curso_grade', 'ge_grades'], '*', ['fk_id_ciclo' => $id_ciclo]);
if (!empty($incl)) {
    $token = formErp::token('ge_curso_grade', 'delete');
    foreach ($incl as $k => $v) {
        $incl[$k]['padrao'] = tool::simnao($v['padrao']);
        $incl[$k]['del'] = formErp::submit('Excluir', $token, ['activeNav' => 4, 'id_curso' => $id_curso, 'id_tp_ens' => $id_tp_ens, 'id_ciclo' => $id_ciclo, '1[id_cg]' => $v['id_cg']]);
    }
    $form['array'] = $incl;
    $form['fields'] = [
        'Grade Curricular' => 'n_grade',
        'Grade Padrão' => 'padrao',
        '||1' => 'del',
        '||3' => 'edit'
    ];
}
?>
<div class="row">
    <div class="col-sm-3">
        <input class="btn btn-info" type="submit" onclick="edit()" value="Nova Grade" />
    </div>
</div>
<?php
if (!empty($form)) {
    tool::relatSimples($form);
}
?>
<form action="<?= HOME_URI ?>/sed/def/formGrade.php" id="form" target="frame" method="POST">
    <?=
    formErp::hidden([
        'id_tp_ens' => $id_tp_ens,
        'id_curso' => $id_curso,
        'id_ciclo' => $id_ciclo
    ])
    ?>
    <input type="hidden" name="id_grade" id="id_grade" value="" />
</form>
<?php
toolErp::modalInicio(NULL, @$_POST['id_cg']);
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById('id_grade').value = id;
        } else {
            document.getElementById('id_grade').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');

    }
</script>
