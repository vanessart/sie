<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_polo)) {
    $ativo = null;
} else {
    $ativo = 1;
    $polo = sql::get('maker_polo', '*', ['id_polo' => $id_polo], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciamento de Polos/Escolas
    </div>
    <?php
    $abas[1] = ['nome' => "Polo" . (empty($id_polo) ?'s': ' - '. $polo['n_polo']), 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Escolas", 'ativo' => $ativo, 'hidden' => ['id_polo' => $id_polo]];
    $abas[3] = ['nome' => "Turmas", 'ativo' => $ativo, 'hidden' => ['id_polo' => $id_polo]];
    $aba = report::abas($abas);
    include ABSPATH . "/module/maker/views/_polos/$aba.php";
    ?>
</div>
