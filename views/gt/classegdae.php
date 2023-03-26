<?php
if (!defined('ABSPATH'))
    exit;
//seta periodo
$id_pl = gtMain::periodoSet(@$_POST['id_pl']);
//lista os periodos por situaçao
$periodos = gtMain::periodosPorSituacao();
?>
<div class="Body">
    <br /><br />
    <div class="row">
        <div class="col-md-6">
            <?php echo formulario::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1);
            ?>
        </div>
        <div class="col-md-6">
            <div>
                <?php
/**
?>
                <form method="POST">
                    <?php echo DB::hiddenKey('importarClasses') ?>
                    <input type="hidden" name="id_pl" value="<?php echo $id_pl ?>" />
                    <input name="importarClasses" class="btn btn-success" type="submit" value="Importar Classes do SED" />
                </form>
                <?php
 * 
 */
?>
            </div>
        </div>
    </div>
    <br /><br />

    <div class="row">
        <div class="col-lg-12">
            <?php
            if (!empty($id_pl)) {
                $model->listTurmaGdae(tool::id_inst(), $id_pl);
            }
            ?>
        </div>
    </div>
</div>
<?php
if (empty($_POST['modal'])) {
    $modal = 1;
}
if(!empty($_POST['id_turma'])){
$turma = sql::get(['ge_turmas', 'ge_ciclos', 'ge_cursos', 'ge_tp_ensino'], '*,ge_turmas.fk_id_grade as fk_id_grade ', ['id_turma' => $_POST['id_turma']], 'fetch');
}
tool::modalInicio('width: 95%', @$modal);
include ABSPATH . '/views/gt/_classe/nova.php';
tool::modalFim();
