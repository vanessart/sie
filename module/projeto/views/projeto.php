<?php
if (!defined('ABSPATH'))
    exit;
$id_proj = filter_input(INPUT_POST, 'id_proj', FILTER_SANITIZE_NUMBER_INT);
if ($id_proj) {
    $ativo = 1;
} else {
    $ativo = null;
}
?>
<div class="body">
    <div class="fieldTop">
        Projetos
    </div>
</div>

<?php
$abas[1] = ['nome' => "Projetos", 'ativo' => 1, 'hidden' => []];
$abas[2] = ['nome' => "Tarefas", 'ativo' => $ativo, 'hidden' => ['id_proj'=>$id_proj]];
$abas[3] = ['nome' => "Uploads", 'ativo' => $ativo, 'hidden' => ['id_proj'=>$id_proj]];
$aba = report::abas($abas);
include ABSPATH . "/module/projeto/views/_projeto/$aba.php"
?>