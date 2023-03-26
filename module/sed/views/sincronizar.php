<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Sincronização SED/ERP
    </div>
    <br />
    <?php
    $abas[1] = ['nome' => "Baixar Classes", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Baixar Alunos", 'ativo' => 1, 'hidden' => []];
    $abas[3] = ['nome' => "Robot", 'ativo' => 1, 'hidden' => []];
    $abas[4] = ['nome' => "Baixar Alunos por Lote", 'ativo' => 1, 'hidden' => []];
    $aba = report::abas($abas);
    include ABSPATH . "/module/sed/views/_sincronizar/$aba.php";
    ?>
</div>
