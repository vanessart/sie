<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_inst_turma = filter_input(INPUT_POST, 'id_inst_turma', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$escola = $periodo = filter_input(INPUT_POST, 'escola');
$n_turma = $periodo = filter_input(INPUT_POST, 'n_turma');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pessoaAlu = filter_input(INPUT_POST, 'id_pessoaAlu', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$hidden = [
    'id_inst_turma' => $id_inst_turma,
    'id_curso' => $id_curso,
    'escola' => $escola,
    'n_turma' => $n_turma,
    'id_turma' => $id_turma,
    'id_pl' => $id_pl,
    'id_ciclo' => $id_ciclo,
    'id_inst' => $id_inst
];

if ($id_turma) {
    $active = 1;
} else {
    $active = null;
}
if ($id_pessoaAlu) {
    $activeAluno = 1;
} else {
    $activeAluno = null;
}
if (in_array(toolErp::id_nilvel(), [24])) {
    $id_pessoa = toolErp::id_pessoa();
} elseif (in_array(toolErp::id_nilvel(), [53, 54])) {
    if ($id_inst) {
        $n_inst = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
    }
} else {
    $id_inst = toolErp::id_inst();
    $n_inst = toolErp::n_inst();
}
?>
<div class="body">
    <div class="fieldTop">
        Acompanhamento de Aprendizagem
    </div>
    <?php
    if (in_array(toolErp::id_nilvel(), [53, 54])) {
        $escolas = ng_escolas::idEscolas([3, 7, 8]);
        echo formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1);
    }
    if (!empty($id_inst) || !empty($id_pessoa)) {
        $abas[1] = ['nome' => "Turmas", 'ativo' => 1, 'hidden' => ['id_inst' => $id_inst]];
        $abas[2] = ['nome' => "Alunos", 'ativo' => $active, 'hidden' => $hidden];
        $hidden['id_pessoaAlu'] = $id_pessoaAlu;
        $abas[3] = ['nome' => "Pauta de Observação", 'ativo' => $activeAluno, 'hidden' => $hidden];
        $abas[4] = ['nome' => "Acompanhamento Semestral", 'ativo' => $activeAluno, 'hidden' => $hidden];
        $aba = report::abas($abas);
        include ABSPATH . "/module/profe/views/_acompApr/$aba.php";
        ?>
    </div>
    <?php
}