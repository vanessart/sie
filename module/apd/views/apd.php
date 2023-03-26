<?php
if (!defined('ABSPATH'))
    exit;

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_nilvel() == 55) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}

$id_pessoa_apd = filter_input(INPUT_POST, 'id_pessoa_apd', FILTER_SANITIZE_NUMBER_INT);
if (empty($_POST[1]['fk_id_pessoa'])) {
    $fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
} else {
    $post = $_POST[1];
    $fk_id_pessoa = $post['fk_id_pessoa'];
}
if ($id_pessoa_apd) {
    $ativo = 1;
} else {
    $ativo = null;
}

$hidden = [
    'id_pessoa' => @$id_pessoa,
    'n_pessoa' => @$n_pessoa,
    'id_turma' =>@$id_turma,
    'id_inst' =>@$id_inst,
    'id_pessoa_apd' => @$id_pessoa_apd,
    'n_turma' => @$n_turma
];

?>
<div class="body">
    <div class="fieldTop">
        Aluno - AEE
    </div>

<?php
$abas[1] = ['nome' => "Alunos", 'ativo' => 1, 'hidden' => ['id_inst'=>$id_inst]];
if (!in_array(toolErp::id_nilvel(),[18,22])) {
    $abas[2] = ['nome' => "Documentos", 'ativo' => $ativo, 'hidden' => $hidden];
}
$abas[3] = ['nome' => "Formulários", 'ativo' => $ativo, 'hidden' => $hidden];
$aba = report::abas($abas);
include ABSPATH . "/module/apd/views/_abas/$aba.php"
?>
</div>