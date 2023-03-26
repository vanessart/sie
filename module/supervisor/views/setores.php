<?php
if (!defined('ABSPATH'))
    exit;
$id_setor = filter_input(INPUT_POST, 'id_setor', FILTER_SANITIZE_NUMBER_INT);
$fk_id_setor = filter_input(INPUT_POST, 'fk_id_setor', FILTER_SANITIZE_NUMBER_INT);

if ($id_setor) {
    $ativo = 1;
} else {
    $ativo = null;
}

$hidden = [
    'id_setor' => @$id_setor,
    'fk_id_setor' => @$fk_id_setor,
];

?>
<div class="body">
    <div class="fieldTop">
        Gerenciar Setores
    </div>

<?php
$abas[1] = ['nome' => "Setores", 'ativo' => 1];
$abas[2] = ['nome' => "Escolas", 'ativo' => $ativo, 'hidden' => $hidden];
$aba = report::abas($abas);
include ABSPATH . "/module/supervisor/views/_setores/$aba.php"
?>
</div>