<?php
$perAtivo = gt_main::periodoAtivo();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = empty($id_pl) ? key($perAtivo) : $id_pl;
$hidden['id_pl'] = $id_pl;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_turma)) {
    $hidden['id_turma'] = $id_turma;
}
$turmas = gt_escolas::turmaIdName(tool::id_inst(), NULL, @$id_pl);
$periodos = gt_main::periodosPorAno();
?>

<div class="row">
    <div class="col-sm-3">
        <?php
        echo form::select('id_pl', $periodos, 'Período Letivo', @$id_pl, 1, @$hidden);
        ?>
    </div>
    <div class="col-sm-3" style="text-align: center; font-size: 18px">
        <?php echo form::select('id_turma', $turmas, ['Classe', 'Todas as Classes'], @$id_turma, 1, @$hidden) ?>
    </div>
</div>