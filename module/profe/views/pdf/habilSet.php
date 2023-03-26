<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
header("Access-Control-Allow-Origin: *");
$op = filter_input(INPUT_POST, 'op', FILTER_SANITIZE_STRING);
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if ($op == 'i') {
    $mongo = new mongoCrude('Diario');
    $criterion = [
        'data' => $data,
        'id_turma' => $id_turma,
        'id_disc' => $id_disc,
        'id_hab' => $id_hab,
        'id_pessoa' => $id_pessoa
    ];
    $mongo->update('habilidades.' . $id_pl, $criterion, ['id_hab' => $id_hab]);
} elseif ($op == 'd') {
    $criterion = [
        'data' => $data,
        'id_turma' => $id_turma,
        'id_disc' => $id_disc,
        'id_hab' => $id_hab,
    ];
    $mongo = new mongoCrude('Diario');
    $mongo->delete('habilidades.' . $id_pl, $criterion);
}
$criterion = [
    'data' => $data,
    'id_turma' => $id_turma,
    'id_disc' => $id_disc,
];
$mongo = new mongoCrude('Diario');
$habs = $mongo->query('habilidades.' . $id_pl, $criterion);
foreach ($habs as $v) {
    $v = (array) $v;

    $id_habs[$v['id_hab']] = $v['id_hab'];
}
if (!empty($id_habs)) {
    $sql = "SELECT "
            . " h.id_hab, h.codigo, h.descricao, disc.n_disc "
            . " FROM coord_hab h "
            . " left join ge_disciplinas disc on disc.id_disc = h.fk_id_disc "
            . " WHERE h.id_hab IN (" . implode(',', $id_habs) . ") "
            . " ORDER BY disc.n_disc, h.codigo ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        ?>
        <div class="alert alert-info" style="width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify">
            <div class="row">
                <div class="col-11">
                    <?= $v['codigo'] ?> (<?= $v['n_disc'] ?>) - <?= $v['descricao'] ?>
                </div>
                <div class="col-1">
                    <button class="btn btn-dark" onclick="habDel(<?= $v['id_hab'] ?>)">
                        X
                    </button>
                </div>
            </div>
        </div>
        <br />
        <?php
    }
}
?>
