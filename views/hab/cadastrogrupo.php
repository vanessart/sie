<?php
$sql = ' SELECT '
        . ' hab_disc_grupo.n_disc_grupo, hab_disc_grupo.id_disc_grupo, ge_cursos.n_curso, ge_cursos.id_curso, ge_ciclos.n_ciclo, ge_ciclos.id_ciclo, ge_disciplinas.n_disc, ge_disciplinas.id_disc '
        . ' FROM hab_disc_grupo hab_disc_grupo '
        . ' INNER JOIN ge_ciclos ON ge_ciclos.id_ciclo = hab_disc_grupo.fk_id_ciclo '
        . ' INNER JOIN ge_cursos ON ge_cursos.id_curso = ge_ciclos.fk_id_curso '
        . ' LEFT OUTER JOIN ge_disciplinas ON hab_disc_grupo.fk_id_disc = ge_disciplinas.id_disc '
        . ' ORDER BY hab_disc_grupo.n_disc_grupo';
$query = $model->db->query($sql);
$periodo = $query->fetchAll();
$sqlkey = DB::sqlKey('hab_disc_grupo', 'delete');
$hidden = [];
foreach ($periodo as $k => $v) {
    $v['novo'] = 1;
    $v['id_grade'] = @$_POST['id_grade'];
    $v['id_disc'] = @$_POST['id_disc'];
    $periodo[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_disc_grupo]' => $v['id_disc_grupo']]);
    $periodo[$k]['edit'] = formulario::submit('Editar', Null, $v);
}
?>

<div class="fieldBody">
    <div class="row">
        <div class="fieldTop">
            Cadastro de Grupos
        </div>
        <br />
        <input class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Novo Grupo" />
        <br />
        <?php
        if (empty($_POST['novo'])) {
            $modal = 1;
        }
        tool::modalInicio('width: 95%', @$modal);
        ?>
        <br />
        <div class="row">
            <div class="col-md-3">
                <?php
                $hidden['novo'] = 1;
                formulario::selectDB('ge_cursos', 'id_curso', 'Curso', @$_REQUEST['id_curso'], NULL, 1, $hidden);
                $hidden['id_curso'] = @$_POST['id_curso'];
                ?> 

            </div>             
            <div class="col-md-3">

                <?php
                if (!empty($_POST['id_curso'])) {
                    $ciclos = curso::ciclos($_POST['id_curso']);
                    foreach ($ciclos as $v) {
                        $options[$v['id_ciclo']] = $v['n_ciclo'];
                    }
                    formulario::select('id_ciclo', $options, 'Ciclo', @$_POST['id_ciclo'], 1, $hidden);
                    $hidden['id_ciclo'] = @$_POST['id_ciclo'];
                }
                ?> 
            </div>

            <div class="col-md-3">
                <?php
                if (!empty($_POST['id_ciclo'])) {
                    $grade = curso::gradeCiclo(@$_POST['id_ciclo'], 1);
                    if (!empty($grade)) {
                        formulario::select('id_grade', $grade, 'Grade', @$_POST['id_grade'], 1, $hidden);
                        $hidden['id_grade'] = @$_POST['id_grade'];
                    }
                }
                ?>
            </div>

            <div class="col-md-3">
                <?php
                if (!empty($_POST['id_grade'])) {
                    $disc_ = disciplina::gradeDisc($_POST['id_grade'], 1);
                    if (!empty($disc_)) {
                        formulario::select('id_disc', $disc_, 'Disciplina', @$_POST['id_disc'], 1, $hidden);
                        $hidden['id_disc'] = @$_POST['id_disc'];
                    }
                }
                ?>
            </div>


        </div>
        <br />
        <?php
        if (!empty($_POST['id_disc'])) {
            ?>
            <form method="POST">
                <div class="row">
                    <?php echo DB::hiddenKey('hab_disc_grupo', 'replace') ?>
                    <div class="col-md-8">
                        <?php
                        if (!empty($_POST['id_disc']) || (!empty($_POST['id_ciclo']) && in_array($_POST['id_curso'], [3, 7, 8]))) {
                            formulario::input('1[n_disc_grupo]', 'Grupo');
                            $hidden['n_disc_grupo'] = @$_POST['n_disc_grupo'];
                        }
                        ?>
                        <input type="hidden" name="1[fk_id_disc]" value="<?php echo $_POST['id_disc'] ?>" /> 
                        <input type="hidden" name="id_grade" value="<?php echo $_POST['id_grade'] ?>" /> 
                        <input type="hidden" name="1[fk_id_ciclo]" value="<?php echo$_POST['id_ciclo'] ?>" /> 
                        <input type="hidden" name="1[id_disc_grupo]" value="<?php echo @$_POST['id_disc_grupo'] ?>" /> 
                        <input type="hidden" name="id_ciclo" value="<?php echo$_POST['id_ciclo'] ?>" /> 
                        <input type="hidden" name="id_curso" value="<?php echo$_POST['id_curso'] ?>" /> 
                        <input type="hidden" name="id_disc" value="<?php echo$_POST['id_disc'] ?>" /> 
                        <input type="hidden" name="n_disc_grupo" value="<?php echo$_POST['n_disc_grupo'] ?>" /> 

                    </div>
                    <div class="col-md-4">
                        <?php
                        if (!empty($_POST['id_disc']) || (!empty($_POST['id_ciclo']) && in_array($_POST['id_curso'], [3, 7, 8]))) {
                            ?>
                            <button class="btn btn-success">Salvar</button> 
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </form>
            <br /><br />
            <?php
            tool::modalFim();
            ?>
            <br />
            <?php
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $form['array'] = $periodo;
                $form['fields'] = [
                    'Curso' => 'n_curso',
                    'Ciclo' => 'n_ciclo',
                    'Disciplina' => 'n_disc',
                    'Grupo' => 'n_disc_grupo',
                    '||1' => 'del',
                    '||2' => 'edit'
                ];
                tool::relatSimples($form);
                ?>
            </div>
        </div>

    </div>
</div>
