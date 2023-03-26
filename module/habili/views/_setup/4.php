<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
if (!$id_pl) {
    $id_pl = ng_main::periodoSet();
}
$pls = ng_main::periodosPorSituacao([1, 2]);
$cursos = ng_main::cursosSeg();
$son = sql::get(['profe_sodagem', 'coord_grup_hab'], '*', ['fk_id_pl' => $id_pl]);
foreach ($son as $v) {
     $cursos[$v['fk_id_curso']]['n_gh'] = $v['n_gh'];
    $cursos[$v['fk_id_curso']]['quant'] = $v['quant'];
  $cursos[$v['fk_id_curso']]['at_sonda'] = $v['at_sonda'];
}
foreach ($cursos as $k => $v) {
    $cursos[$k]['ac'] = '<button type="button" class="btn btn-info" onclick="acesso(' . $v['id_curso'] . ')">Editar</button>';
}
$form['array'] = $cursos;
$form['fields'] = [
    'ID' => 'id_curso',
    'Curso' => 'n_curso',
    'Grupo de Habilidade' => 'n_gh',
    'Quant. de Sondagem'=>'quant',
    'Sondagem Ativa'=>'at_sonda',
    'Segmento' => 'n_seg',
    '||1' => 'ac'
        ]
?>
<div class="body">
    <div class="fieldTop">
        Configurar Sondagem de Habilidades
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $pls, 'Períodos Letivos', $id_pl, 1, ['set' => $set]) ?>
        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
    <br />
    <?php
    report::simple($form);
    ?>
</div>
<form target="frame" action="<?= HOME_URI ?>/habili/def/formSonda.php" id="acForm" method="POST">
    <input type="hidden" id="id_curso" name="id_curso" value="" />
    <?=
    formErp::hidden([
        'id_pl' => $id_pl,
    ])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acesso(id) {
        id_curso.value = id;
        acForm.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
