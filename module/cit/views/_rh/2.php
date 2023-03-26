<div class="row">
    <div class="col">
        <form method="POST">
            <?=
            formErp::hidden(['proc' => 1, 'activeNav' => 2])
            . formErp::button('Processar')
            ?>
        </form>
    </div>
    <div class="col">

    </div>
    <div class="col">

    </div>
</div>
<br />
<?php
$proc = filter_input(INPUT_POST, 'proc', FILTER_SANITIZE_NUMBER_INT);
if ($proc) {
    $dados = rhImport::disciplinas($ano);
    if ($dados) {
        foreach ($dados as $v) {
            $v['fk_id_system_disciplina_ano_anterior'] = $v['id_system_disciplina_ano_anterior'];
            unset($v['id_system_disciplina_ano_anterior']);
            $model->db->ireplace('rh`.`disciplinas', $v, 1);
        }
    }
}
$disc = sqlErp::idNome('ge_disciplinas');
$disc['nc']='Núcleo Comum';
$anos = sqlErp::get('rh`.`disciplinas');
foreach ($anos as $k => $v) {
    $anos[$k]['disc'] = @$disc[$v['fk_id_disc']];
    $anos[$k]['ed'] = '<button class="btn btn-info" onclick="ed(' . $v['id_system_disciplina'] . ', ' . $v['fk_id_disc'] . ')">Editar</button>';
}
$form['array'] = $anos;
$form['fields'] = [
    'ID' => 'id_system_disciplina',
    'Disciplina (RH)' => 'disciplina',
    'Disciplina' => 'disc',
    '||1' => 'ed'
];
?>
<div class="row">
    <div class="col-6">
        <?php
        report::simple($form);
        ?>
    </div>
</div>
<br />
<?php
?>
<form action="<?= HOME_URI ?>/cit/def/rhDisc" id="form" target="frame" method="POST">
    <input type="hidden" name="id_system_disciplina" id="id_system_disciplina"  />
    <input type="hidden" name="fk_id_disc" id="fk_id_disc"  />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 40vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function ed(id, idCiclo) {
        id_system_disciplina.value = id;
        fk_id_disc.value = idCiclo;
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
