<?php
if (!defined('ABSPATH'))
    exit;
if ($id_grade) {
    $grade = disciplina::grade($id_grade);
    $disc = disciplina::alocado($id_grade);
} else {
    exit();
}

if ($disc && $grade) {
    $token = formErp::token('ge_aloca_disc', 'delete');
    foreach ($disc as $k => $v) {
        if (is_numeric($k)) {
            $disc[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_aloca]' => $v['id_aloca'], 'activeNav' => 4, 'id_grade' => $id_grade]);
            $disc[$k]['ac'] = '<button class="btn btn-info" onclick="nova(' . $v['id_aloca'] . ')">Editar</button>';
            $disc[$k]['nucleo_comum'] = toolErp::simnao($v['nucleo_comum']);
        } else {
            unset($disc[$k]);
        }
    }
    $form['array'] = $disc;
    $form['fields'] = [
        'Ordem' => 'ordem',
        'Disciplina' => 'n_disc',
        'Aulas' => 'aulas',
        'Núcleo Comum' => 'nucleo_comum',
        'Base' => 'n_adb',
        '||1' => 'del',
        '||2' => 'ac',
    ];
}
?>
<div class="row">
    <div class="col-4">
        <button class="btn btn-primary" onclick="nova()">
            Nova Grade
        </button>
    </div>
    <div class="col-8" style="font-weight: bold; font-size: 1.2em; padding: 10px">
        Disciplinas da Grade <?= $grade['n_grade'] ?>
    </div>
</div>
<br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/sed/def/formAloca.php" target="frame" id="form" method="POST">
    <input type="hidden" name="id_grade" value="<?= $id_grade ?>"/>
    <input type="hidden" name="id_aloca" id="id_aloca" value="" />
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
            id_aloca.value = id;
        } else {
            id_aloca.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>