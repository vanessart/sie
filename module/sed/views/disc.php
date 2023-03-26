<?php
if (!defined('ABSPATH'))
    exit;
$id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_SANITIZE_NUMBER_INT);
if ($id_grade) {
    $at_ag = true;
} else {
    $at_ag = null;
}
?>
<div class="body">
    <div class="fieldTop">
        Grades e Disciplinas
    </div>
    <?php
    $abas[1] = ['nome' => "Áreas de Conhecimento", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Disciplinas", 'ativo' => 1, 'hidden' => []];
    $abas[3] = ['nome' => "Grades Curriculares", 'ativo' => 1, 'hidden' => []];
    $abas[4] = ['nome' => "Alocação de Disciplinas", 'ativo' => $at_ag, 'hidden' => ['id_grade' => $id_grade]];
    $aba = report::abas($abas);
    include ABSPATH . "/module/sed/views/_disc/$aba.php";
    ?>
</div>
