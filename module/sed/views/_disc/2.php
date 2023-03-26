<?php
if (!defined('ABSPATH'))
    exit;
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$disc = disciplina::disc();
if ($disc) {
    $token = formErp::token('ge_disciplinas', 'delete');
    foreach ($disc as $k => $v) {
        $disc[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_disc]' => $v['id_disc'], 'activeNav'=>2]);
        $disc[$k]['ac'] = '<button class="btn btn-info" onclick="nova(' . $v['id_disc'] . ')">Editar</button>';
    }
    $form['array'] = $disc;
    $form['fields'] = [
        'ID' => 'id_disc',
        'Disciplina' => 'n_disc',
        'Sigla' => 'sg_disc',
        'Área do Conhecimneto' => 'n_area',
        '||1' => 'del',
        '||2' => 'ac'
    ];
}
?>
<div class="row">
    <div class="col">
        <button class="btn btn-primary" onclick="nova()">
            Nova Disciplina
        </button>
    </div>
</div>

<br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/sed/def/formDisc.php" target="frame" id="form" method="POST">
    <input type="hidden" name="id_disc" id="id_disc" value=""/>
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
            id_disc.value = id;
        } else {
            id_disc.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>