<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
$id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
if ($id_gr) {
    $at = 1;
    $gr = sql::get('sed_grupo', '*', ['id_gr' => $id_gr], 'fetch');
} else {
    $at = null;
    $gr = sql::get('sed_grupo', 'id_gr, n_gr, at_gr, cor', ['fk_id_inst' => $id_inst]);
}
?>
<div class="body">
    <div class="fieldTop">
        Grupos de Alunos
    </div>
    <?php
    $abas[1] = ['nome' => "Grupos de alunos", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Membros " . (empty($gr['n_gr']) ? '' : '(' . $gr['n_gr'] . ')'), 'ativo' => $at, 'hidden' => ['id_gr' => $id_gr]];
    $aba = report::abas($abas);

    include ABSPATH . "/module/sed/views/_grupo/$aba.php";
    ?>
</div>

