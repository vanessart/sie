<div class="row">
    <div class="col">
        <form method="POST">
            <?=
            formErp::hidden(['proc' => 1, 'activeNav' => 1])
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
    $dados = rhImport::segmentoAnoEscolar($ano);
    if ($dados) {
        foreach ($dados as $v) {
            $v['fk_id_system_ano_escolar_ano_anterior'] = $v['id_system_ano_escolar_ano_anterior'];
            unset($v['id_system_ano_escolar_ano_anterior']);
            $model->db->ireplace('rh`.`ano', $v, 1);
        }
    }
}
$ciclos = sqlErp::idNome('ge_ciclos');
$anos = sqlErp::get('rh`.`ano');
foreach ($anos as $k => $v) {
    $anos[$k]['ciclo'] = @$ciclos[$v['fk_id_ciclo']];
    $anos[$k]['ed'] = '<button class="btn btn-info" onclick="ed(' . $v['id_system_ano_escolar'] . ', '.$v['fk_id_ciclo'].')">Editar</button>';
}
$form['array'] = $anos;
$form['fields'] = [
    'ID' => 'id_system_ano_escolar',
    'Segmento' => 'segmento',
    'Código' => 'codigo',
    'Ciclo (RH)' => 'ano_escolar',
    'Ciclo' => 'ciclo',
    '||1'=>'ed'
];
report::simple($form);
?>
<form action="<?= HOME_URI ?>/cit/def/rhAno" id="form" target="frame" method="POST">
    <input type="hidden" name="id_system_ano_escolar" id="id_system_ano_escolar"  />
    <input type="hidden" name="fk_id_ciclo" id="fk_id_ciclo"  />
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
        id_system_ano_escolar.value = id;
        fk_id_ciclo.value = idCiclo;
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
