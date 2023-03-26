<?php
if (!gtMain::gdaeAtivo(tool::id_inst())) {

if (empty($_POST['periodoLetivo'])) {
    $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
}
if (!empty($_POST['id_turma'])) {
    $id_turma = $_POST['id_turma'];
    $turma = sql::get(['ge_turmas', 'ge_ciclos', 'ge_cursos', 'ge_tp_ensino'], '*,ge_turmas.fk_id_grade as fk_id_grade ', ['id_turma' => $id_turma], 'fetch');
    @$id_tp_ens = $turma['id_tp_ens'];
    @$id_curso = $turma['id_curso'];
    @$id_ciclo = $turma['id_ciclo'];
} else {
    @$id_tp_ens = $_POST['id_tp_ens'];
    @$id_curso = $_POST['id_curso'];
    @$id_ciclo = $_POST['id_ciclo'];
}
if (!empty(@$id_ciclo)) {
    @$ciclo = sql::get('ge_ciclos', '*', ['id_ciclo' => @$id_ciclo], 'fetch');
}
?>
<div class="fieldBody">
    <div class="row">
        <div class="col-md-3">
            <?php
            $per = gtMain::periodosPorSituacao();
            formulario::select('periodoLetivo', $per, 'Período Letivo', @$periodoLetivo, 1);
            ?>
        </div>
        <div class="col-md-3">
            <input class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Nova Classe" />
        </div>
        <div class="col-md-6" style="text-align: center; font-size: 18px">
            <div class="btni">
                Cadastro de Classes
            </div>
        </div>
    </div>
    <br /><br />

    <div class="row">
        <div class="col-lg-12">
            <?php
            if (!empty($periodoLetivo)) {
                $model->listTurma(tool::id_inst(), $periodoLetivo);
            }
            ?>
        </div>
    </div>
</div>
<?php
if (empty($_POST['clonar'])) {
    if (empty($_POST['modal'])) {
        $modal = 1;
    }
    tool::modalInicio('width: 95%', @$modal);
    include ABSPATH . '/views/gt/_classe/nova.php';
    tool::modalFim();
} else {
    tool::modalInicio('width: 95%', NULL, 'cloner');
    include ABSPATH . '/views/gt/_classe/clone.php';
    tool::modalFim();
}
}else {
    ?>
<div class="alert alert-danger" style="text-align: center; font-weight: bold; font-size: 22px">
    Página desativada
</div>
                                <?php
}
?>