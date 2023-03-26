<?php
if (!defined('ABSPATH'))
    exit;
$id_setor = filter_input(INPUT_POST, 'id_setor', FILTER_SANITIZE_NUMBER_INT);

if ($id_setor) {
    $ativo = 1;
} else {
    $ativo = null;
}

$hidden = [
    'id_setor' => @$id_setor,
];

?>
<div class="body">
    <div class="fieldTop">
        Gerenciar Visitas
    </div>

<?php
$abas[1] = ['nome' => "Visitas", 'ativo' => 1];
$abas[2] = ['nome' => "Ocorrencias", 'ativo' => $ativo, 'hidden' => $hidden];
$aba = report::abas($abas);
include ABSPATH . "/module/supervisor/views/_ocorrencias/$aba.php"
?>
</div>