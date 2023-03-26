<?php
if (!defined('ABSPATH'))
    exit;
$id_tp_ens = filter_input(INPUT_POST, 'id_tp_ens', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
if ($id_tp_ens) {
    $n_seg = sql::get('ge_tp_ensino', 'n_tp_ens', ['id_tp_ens' => $id_tp_ens], 'fetch')['n_tp_ens'];
}
if ($id_curso) {
    $n_curso = sql::get('ge_cursos', 'n_curso', ['id_curso' => $id_curso], 'fetch')['n_curso'];
}
if ($id_ciclo) {
    $n_ciclo = sql::get('ge_ciclos', 'n_ciclo', ['id_ciclo' => $id_ciclo], 'fetch')['n_ciclo'];
}
?>
<div class="body">
    <div class="fieldTop">
        Segmentos, Cursos e Ciclos
    </div>
    <?php
    $abas[1] = ['nome' => "Segmentos" . (empty($n_seg) ? '' : ' (' . $n_seg . ')'), 'ativo' => 1];
    $abas[2] = ['nome' => "Cursos" . (empty($n_curso) ? '' : ' (' . $n_curso . ')'), 'ativo' => null, 'hidden' => ['id_tp_ens' => $id_tp_ens]];
    $abas[3] = ['nome' => "Ciclos" . (empty($n_ciclo) ? '' : ' (' . $n_ciclo . ')'), 'ativo' => null, 'hidden' => ['id_tp_ens' => $id_tp_ens, 'id_curso' => $id_curso]];
    $abas[4] = ['nome' => "Grades Curriculares", 'ativo' => null, 'hidden' => ['id_tp_ens' => $id_tp_ens, 'id_curso' => $id_curso, 'id_ciclo' => $id_ciclo]];
    $aba = report::abas($abas);
    include ABSPATH . "/module/sed/views/_ensino/$aba.php";
    ?>
</div>
