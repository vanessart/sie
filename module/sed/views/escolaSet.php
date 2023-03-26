<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if ($id_inst) {
    $abaAtv = 1;
    $esc = new ng_escola($id_inst);
} else {
    $abaAtv = null;
}
?>
<div class="body">
    <div class="fieldTop">
        Escolas - Configurações
    </div>
    <?php
    $abas[1] = ['nome' => "Escola".(empty($id_inst)?'':' - '.$esc->_nome), 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Dados Gerais", 'ativo' => $abaAtv, 'hidden' => ['id_inst'=>$id_inst]];
    $abas[3] = ['nome' => "Cursos", 'ativo' => $abaAtv, 'hidden' => ['id_inst'=>$id_inst]];
    $abas[4] = ['nome' => "Geolocalização", 'ativo' => $abaAtv, 'hidden' => ['id_inst'=>$id_inst]];
    $aba = report::abas($abas);
    include ABSPATH . "/module/sed/views/_escolaSet/$aba.php";
    ?>
</div>
