<?php
if (!defined('ABSPATH'))
    exit;
include ABSPATH . '/module/profe/views/_chamada/6_7.php';
$id_gh = $sond['fk_id_gh'];
$id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
$hidden = $_POST;
if ($id_ce) {
    $ce = " and fk_id_ce = $id_ce";
} else {
    $ce = null;
}
$habAlu = hab::sondagem($id_curso, date("Y"), $id_pessoa, $id_disc, $at_set);
if (empty($habAlu)) {
    $habAlu = [];
}
$sql = "SELECT `id_hab`, `descricao`, `codigo`, fk_id_ce FROM coord_hab h "
        . " JOIN coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab "
        . " AND h.fk_id_gh = $id_gh "
        . " AND h.fk_id_disc = $id_disc "
        . " AND ci.fk_id_ciclo = $id_ciclo "
        . " AND at_hab = 1 "
        . " $ce "
        . " order by codigo ";
$query = pdoSis::getInstance()->query($sql);
$hab = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($hab as $k => $v) {
    $hab[$k]['descricao'] = $v['codigo'] . ' - ' . $v['descricao'];
    $hab[$k]['set'] = '<button onclick="acSonda(' . $v['id_hab'] . ', ' . $v['fk_id_ce'] . ')" class="btn btn-outline-success" style="width: 40px; height: 40px; border-radius: 50%"><img style="width: 20px" src="' . HOME_URI . '/'. INCLUDE_FOLDER .'/images/ir.png" alt="alt"/></button>';
}
$form['array'] = $hab;
$form['fields'] = [
    'Habilidades' => 'descricao',
    '||1' => 'set'
];
?>
<div class="row">
    <div class="col">
        <?php
        if (in_array($id_curso, [3, 7, 8])) {
            echo formErp::selectDB('coord_campo_experiencia', 'id_ce', 'Campo Experiência', $id_ce, 1, $hidden, null, ['fk_id_gh' => $id_gh]);
        }
        ?>
    </div>
    <div class="col">

    </div>
    <div class="col">

    </div>
</div>
<div style="margin-top: -50px">
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/profe/def/formSondaHab.php" id="formSonda" target="frame" method="POST">
    <input type="hidden" name="id_hab" id="id_hab" value="" />
    <input type="hidden" name="id_ce" id="id_ce" value="" />
    <?=
    formErp::hidden([
        'at_set' => $at_set,
        'id_gh' => $sond['fk_id_gh'],
        'id_pl' => $sond['fk_id_pl'],
        'id_ciclo' => $id_ciclo,
        'at_sonda' => $at_sonda,
        'id_curso' => $id_curso,
        'id_disc' => $id_disc,
        'id_turma' => $id_turma,
        'id_inst' => $id_inst,
        'data' => $data,
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
    function acSonda(id, idce) {
        id_hab.value = id;
        id_ce.value = idce;
        formSonda.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
