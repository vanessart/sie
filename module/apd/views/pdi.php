<?php
if (!defined('ABSPATH'))
    exit;

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_turma_AEE = filter_input(INPUT_POST, 'id_turma_AEE', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$pdi = $model->getPDI($id_pessoa_aluno);
if (!empty($pdi)) {
    $id_pdi = $pdi['id_pdi'];
}

$hidden = [
    'id_turma' => @$id_turma,
    'id_turma_AEE' => @$id_turma_AEE,
    'id_pessoa' => @$id_pessoa_aluno,
    'n_pessoa' => @$n_pessoa,
    'id_pdi' =>@$id_pdi,
    'bimestre' => @$bimestre,
    'n_turma' => @$n_turma
];

$n_bimestre = "";
if (!empty($bimestre)) {
    $ativo = 1;
    $n_bimestre = $bimestre."º Bimestre";
}

if (empty($activeNav)) {
    $activeNav = 1;
}

if ($activeNav == 1){
    $ativo = null;
    $n_bimestre = "";
    $bimestre = "";
}else{
    $ativo = 1;
}
?>
<div class="body">
    <div class="row fieldTop">   
        <div class=" col-10">
            Plano de Desenvolvimento Individual
        </div>
        <div class="col-2" style="text-align: right;padding: 10px">
            <form action="<?= HOME_URI ?>/apd/doc" method="POST">
                <button class="btn btn-info" style="margin: 0">
                    Voltar
                </button>
            </form>
        </div>
    </div>
    
</div>

<?php
$abas[1] = ['nome' => "$n_pessoa", 'ativo' => 1, 'hidden' => $hidden];
$abas[2] = ['nome' => "Habilidades $n_bimestre", 'ativo' => $ativo, 'hidden' => $hidden];
$abas[3] = ['nome' => "Atendimentos $n_bimestre", 'ativo' => $ativo, 'hidden' => $hidden];
$abas[4] = ['nome' => "Descritivo $n_bimestre", 'ativo' => $ativo, 'hidden' => $hidden];
$aba = report::abas($abas);
include ABSPATH . "/module/apd/views/_pdi/$aba.php";
?>
