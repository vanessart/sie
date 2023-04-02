<?php
if (!defined('ABSPATH'))
    exit;
$sistema = $model->getSistema('22','48,2,18,53,54,55,24,56');
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'fk_id_projeto', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$n_status_log = filter_input(INPUT_POST, 'n_status', FILTER_UNSAFE_RAW);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_UNSAFE_RAW);
$autores = filter_input(INPUT_POST, 'autores', FILTER_UNSAFE_RAW);
$data = filter_input(INPUT_POST, 'data', FILTER_UNSAFE_RAW);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_UNSAFE_RAW);
$hidden = filter_input(INPUT_POST, 'hidden', FILTER_REQUIRE_ARRAY);

$id_pessoa = toolErp::id_pessoa();
if (empty($id_projeto)) {
    $id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
}

if (!empty($n_status_log)) {
    log::logSet($n_status_log." o Projeto: " . $id_projeto);
}

$hidden = [
    'fk_id_ciclo' => $id_ciclo,
    'fk_id_disc' => $id_disc,
    'id_turma' => $id_turma,
    'id_projeto' => $id_projeto,
    'n_turma' => $n_turma,
    'n_status_log' => $n_status_log,
    'id_inst' => $id_inst,
    'n_projeto' => $n_projeto,
    'autores' => $autores,
    'data' => $data,
    'msg_coord' => $msg_coord,
    'fk_id_projeto' => $id_projeto, 
];
$fixo = [
    'fk_id_projeto' => @$id_projeto, 
    'n_projeto' => @$n_projeto, 
    'fk_id_disc' => @$id_disc,
    'fk_id_ciclo' => @$id_ciclo, 
    'msg_coord' => @$msg_coord
];

if ($id_projeto) {
    $ativo = 1;
} else {
    $ativo = null;
}

if ($activeNav == 1) {
    $ativo = null;
    $n_projeto = "";
}
?>
<div class="body">
    <div class="fieldTop">
        <?= $n_turma ?>
    </div>
    <?php
    $abas[1] = ['nome' => "Todos os Projetos", 'ativo' => 1, 'hidden' => $hidden+ $fixo];
    $abas[2] = ['nome' => "Projeto". (!empty($n_projeto) ? ' - ' . $n_projeto : ''), 'ativo' => $ativo, 'hidden' => $hidden+ $fixo];
    $abas[3] = ['nome' => "Registro Quinzenal", 'ativo' => $ativo, 'hidden' => $hidden + $fixo];
    $aba = report::abas($abas);
    include ABSPATH . "/module/profe/views/_projetoCoord/$aba.php"
    ?>
</div>

