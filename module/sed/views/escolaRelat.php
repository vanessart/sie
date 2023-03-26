
<style>
    .grd button{
        width: 100%;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao();
?>
<div class="body">
    <div class="fieldTop">
        Relatórios - Escola
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1) ?>
        </div>
    </div>
    <br />
    <?php
    include ABSPATH . "/module/sed/views/_turmaRelat/escola.php";
    ?>
</div>
