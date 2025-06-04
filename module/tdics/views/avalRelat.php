<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pl = $model->pl();
$polos = $model->getPolos();

$hidden = [
    'id_polo' => $id_polo,
    'id_turma' => $id_turma
];
?>

<div class="body">
    <div class="fieldTop">
        Avaliações
    </div>
    <br />
    <?php
    $abas[1] = ['nome' => "Pesquisa por turma", 'ativo' => 1, 'hidden' => $hidden];
    $abas[2] = ['nome' => "Pesquisa por aluno", 'ativo' => 1, 'hidden' => $hidden];
    $aba = report::abas($abas);
    include ABSPATH . "/module/" . $this->controller_name . "/views/_avalRelat/$aba.php";
    ?>
</div>