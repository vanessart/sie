<?php
if (!defined('ABSPATH'))
    exit;
$fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_porte = filter_input(INPUT_POST, 'id_porte', FILTER_SANITIZE_NUMBER_INT);
$dt_nasc = filter_input(INPUT_POST, 'dt_nasc', FILTER_SANITIZE_STRING); 
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_aluno_adaptacao = filter_input(INPUT_POST, 'id_aluno_adaptacao', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);

$id_prof = toolErp::id_pessoa();
$grade = $model->indexDBAlunosAEE($id_prof);

$ct12 = false;
$ctO = false;
$ativoAval = "";

foreach ($grade as $v) {
    if ($v['fk_id_grade'] == 12) {
        $ct12 = true;
    } else {
        $ctO = true;
    }
}
if ($fk_id_pessoa) {
    $ativo = 1;
    $ativoAval = 1;
} else {
    $ativo = null;
}

if ($activeNav == 2 || $activeNav == 4 ) {
    $ativo = null;
}

if ($ct12 && $ctO) {
    $tituloGrade = "Adaptação / Flexibilização";
} elseif( $ct12 ) {
    $tituloGrade = "Flexibilização";
} else {
    $tituloGrade = "Adaptação";
}

$hidden = [
    'fk_id_pessoa' => @$fk_id_pessoa,
    'id_aluno_adaptacao' => @$id_aluno_adaptacao,
    'id_turma' =>@$id_turma,
    'n_pessoa' =>@$n_pessoa,
    'dt_nasc' =>@$dt_nasc,
    'activeNav' => @$activeNav,
    'id_porte' => @$id_porte,
];
?>
<div class="body">
    <div class="fieldTop">
        <?= $tituloGrade ?> Curricular <br><br><?= $n_pessoa ?>
    </div>


    <?php
    $abas[1] = ['nome' => "Alunos", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => $tituloGrade ." Curricular", 'ativo' => $ativo, 'hidden' => $hidden];
    $abas[3] = ['nome' => "Componentes Curriculares", 'ativo' => $ativo, 'hidden' => $hidden];
    $abas[4] = ['nome' => "PDI", 'ativo' => $ativoAval, 'hidden' => $hidden];
    $aba = report::abas($abas);
    include ABSPATH . "/module/profe/views/_adaptCurriculo/$aba.php"
    ?>
</div>

