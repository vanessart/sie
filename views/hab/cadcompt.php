<?php
$query = $model->db->query('SELECT hab_competencia_opcoes.id_competencia_opcoes, hab_competencia_opcoes.n_competencia_opcoes, hab_disc_grupo.n_disc_grupo, hab_competencia_opcoes.fk_id_disc_grupo, hab_disc_grupo.id_disc_grupo, ge_cursos.n_curso, ge_cursos.id_curso, ge_ciclos.n_ciclo, ge_ciclos.id_ciclo, ge_disciplinas.n_disc, ge_disciplinas.id_disc FROM hab_disc_grupo hab_disc_grupo INNER JOIN ge_cursos ON ge_cursos.id_curso = hab_disc_grupo.fk_id_curso INNER JOIN ge_ciclos ON ge_ciclos.id_ciclo = hab_disc_grupo.fk_id_ciclo left outer JOIN ge_disciplinas ON hab_disc_grupo.fk_id_disc = ge_disciplinas.id_disc left outer JOIN hab_competencia_opcoes ON hab_competencia_opcoes.fk_id_disc_grupo = hab_disc_grupo.id_disc_grupo ORDER BY hab_disc_grupo.n_disc_grupo');
$compt = $query->fetchAll();
$sqlkey = DB::sqlKey('hab_competencia_opcoes', 'delete');
$hidden = [];

foreach ($compt as $k => $v) {
    $compt[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_competencia_opcoes]' => $v['id_competencia_opcoes']]);
    $compt[$k]['edit'] = formulario::submit('Editar', Null, $v);
}
?>

<div class="fieldBody">

    <!-- Mostra Cadastro de Competencia-->
    <div id="com" class="row field">
        <div class="fieldTop">
            Cadastro de Competências
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php
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
                if (!empty($_POST['id_ciclo']) && !in_array($_POST['id_curso'], [3, 7, 8])) {
                    $sql = " select id_disc, n_disc from ge_ciclos c "
                            . "join ge_aloca_disc ad on ad.fk_id_grade = c.fk_id_grade "
                          . " join ge_disciplinas d on d.id_disc = ad.fk_id_disc  ";
                    $query = $model->db->query($sql);
                    $disciplinas = $query->fetchAll();

                    unset($options);
                    foreach ($disciplinas as $v) {
                        $options[$v['id_disc']] = $v['n_disc'];
                    }
                    asort($options);
                    formulario::select('id_disc', $options, 'Disciplina', @$_POST['id_disc'], 1, $hidden);
                    $hidden['id_disc'] = @$_POST['id_disc'];
                }
                ?>
            </div>

            <div class="col-md-3">
                <?php
                if (!empty($_POST['id_disc']) || (!empty($_POST['id_ciclo']) && in_array($_POST['id_curso'], [3, 7, 8]))) {

                    $grupos = hab::grupos($_POST['id_ciclo'], $_POST['id_curso'], @$_POST['id_disc']);

                    unset($options2);
                    foreach ($grupos as $v) {

                        $options2[$v['id_disc_grupo']] = $v['n_disc_grupo'];
                    }
                }
                if (!empty($options2)) {
                    asort($options2);

                    formulario::select('id_disc_grupo', $options2, 'Grupo', @$_POST['id_disc_grupo'], 1, $hidden);
                    $hidden['id_disc_grupo'] = @$_POST['id_disc_grupo'];
                } Else if (!empty(@$_POST['id_disc']) || (!empty($_POST['id_ciclo']) && in_array($_POST['id_curso'], [3, 7, 8]))) {
                    echo tool::alert('Não há Grupos cadastrados para este Ciclo ou Disciplina! Cadastre um Grupo em: Cadastro -> Grupo.');
                }
                ?>
            </div>

        </div>


        <form method="POST">
            <div class="row">
                <div class="col-md-9">
                    <?php if (!empty($_POST['id_disc_grupo'])) { ?>
                        <?php echo DB::hiddenKey('hab_competencia_opcoes', 'replace') ?>

                        <?php
                        echo formulario::input('1[n_competencia_opcoes]', 'Competência: ');
                        $hidden['n_competencia_opcoes'] = @$_POST['n_competencia_opcoes'];
                    }
                    ?>
                    <input type="hidden" name="1[fk_id_disc_grupo]" value="<?php echo @$_POST['id_disc_grupo'] ?>" />
                    <input type="hidden" name="id_disc_grupo" value="<?php echo @$_POST['id_disc_grupo'] ?>" />
                    <input type="hidden" name="1[id_competencia_opcoes]" value="<?php echo @$_POST['id_competencia_opcoes'] ?>" />
                    <input type="hidden" name="id_ciclo" value="<?php echo @$_POST['id_ciclo'] ?>" />
                    <input type="hidden" name="id_curso" value="<?php echo @$_POST['id_curso'] ?>" />
                    <input type="hidden" name="id_disc" value="<?php echo @$_POST['id_disc'] ?>" />
                </div>
            </div>
            <div class="row field">

                <div class="col-mg-4">
                    <?php if (!empty($_POST['id_disc_grupo'])) { ?>

                        <button class="btn btn-success">
                            Salvar
                        </button>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-12">
                <?php
                $form['array'] = $compt;
                $form['fields'] = [
                    'Grupo' => 'n_disc_grupo',
                    'Curso' => 'n_curso',
                    'Ciclo' => 'n_ciclo',
                    'Disciplina' => 'n_disc',
                    'Competêcia' => 'n_competencia_opcoes',
                    '||1' => 'del',
                    '||2' => 'edit'
                ];

                tool::relatSimples($form);
                ?>
            </div>
        </div>
    </div>
</div>
