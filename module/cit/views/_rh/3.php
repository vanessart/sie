<div class="row">
    <div class="col">
        <form method="POST">
            <?=
            formErp::hidden(['proc' => 1, 'activeNav' => 3])
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
    $sql = "TRUNCATE rh.professorTurma";
    $query = pdoSis::getInstance()->query($sql);
    $dados = rhImport::professorTurma($ano);
    if ($dados) {
        foreach ($dados as $d) {
            if (!empty($d['turmas'])) {
                foreach ($d['turmas'] as $v) {
                    $v['rm'] = $d['matricula'];
                    $v['fk_id_system_ano_escolar'] = $v['id_system_ano_escolar'];
                    unset($v['id_system_ano_escolar']);
                    $v['fk_id_system_disciplina'] = $v['id_system_disciplina'];
                    unset($v['id_system_disciplina']);
                     $model->db->ireplace('rh`.`professorTurma', $v, 1);
                }
            }
        }
    }
}
$pt = sqlErp::get('rh`.`professorTurma');
$form['array'] = $pt;
$form['fields'] = [
    'Matrícula' => 'rm',
    'id_system_ano_escolar' => 'fk_id_system_ano_escolar',
    'id_system_disciplina' => 'fk_id_system_disciplina',
    'Letra' => 'letra',
    'CIE' => 'cie',
    'Status' => 'status',
];
report::simple($form);
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
