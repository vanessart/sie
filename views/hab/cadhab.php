<?php
$hidden = [];
?>
<div class="fieldBody">

    <!-- Mostra Cadastro de Habilidades-->

    <div id="com" class="row field">
        <div class="fieldTop">
            Cadastro de Habilidades
        </div>
        <br /><br />
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
                if (!empty($_POST['id_ciclo'])) {
                    $sql = "select g.id_grade, g.n_grade from ge_grades g "
                            . " join ge_curso_grade cg on cg.fk_id_grade = g.id_grade "
                            . " where cg.fk_id_ciclo =  " . @$_POST['id_ciclo'];
                    $query = $model->db->query($sql);
                    $array = $query->fetchAll();
                    if (!empty($array)) {
                        foreach ($array as $v) {
                            $grade[$v['id_grade']] = $v['n_grade'];
                        }
                        formulario::select('id_grade', $grade, 'Grades', @$_POST['id_grade'], 1, $hidden);
                        $hidden['id_grade'] = @$_POST['id_grade'];
                    } else {
                        ?>
                        <div class="alert alert-danger">
                            Não há grade alocada neste ciclo
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <div class="col-md-3">
                <?php
                if (!empty($_POST['id_grade'])) {
                    $sql = "select d.id_disc, d.n_disc from ge_disciplinas d "
                            . " join ge_aloca_disc ad on ad.fk_id_disc = d.id_disc "
                            . " where ad.fk_id_grade =  " . @$_POST['id_grade'];
                    $query = $model->db->query($sql);
                    $array = $query->fetchAll();

                    if (!empty($array)) {
                        foreach ($array as $v) {
                            $disc[$v['id_disc']] = $v['n_disc'];
                        }
                        formulario::select('id_disc', $disc, 'Disciplina', @$_POST['id_disc'], 1, $hidden);
                        $hidden['id_disc'] = @$_POST['id_disc'];
                    } else {
                        ?>
                        <div class="alert alert-danger">
                            Não há Disciplinas alocadas nesta grade
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

        </div>

        <?php
        if (!empty($_POST['editar'])) {
            tool::modalInicio();
            ?>
            <form method="POST">
                <style>
                    th{
                        background-color: black; color: white; font-weight: bold;
                    }
                </style>
                <br /><br />
                <table class="table table-bordered table-condensed table-hover table-responsive table-striped">
                    <thead>
                    <th style="width: 85%">
                        Habilidades
                    </th>
                    <th>
                        Peso
                    </th>
                    <th>
                        I. Mín. (meses)
                    </th>
                    <th>
                        I. Max. (meses)
                    </th>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 1; $i < 10; $i++) {
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="1[hab<?php echo $i ?>]" value="<?php echo @$_POST['hab' . $i] ?>" />
                                </td>
                                <td>
                                    <input type="number" name="1[peso<?php echo $i ?>]" value="<?php echo @$_POST['peso' . $i] ?>" />
                                </td>
                                <td>
                                    <input type="text" name="" value="" />
                                </td>
                                <td>
                                    <input type="text" name="" value="" />
                                </td>
                            </tr>
                            <?php
                            $hidden['hab' . $i] = @$_POST['hab' . $i];
                            $hidden['peso' . $i] = @$_POST['peso' . $i];
                        }
                        ?>
                    </tbody>
                </table>
                <div class="row">

                    <?php
                    echo DB::hiddenKey('hab_competencia_opcoes', 'replace');
                    ?>
                    <input type="hidden" name="1[id_competencia_opcoes]" value="<?php echo @$_POST['id_competencia_opcoes'] ?>" />
                </div>
                <br /><br />
                <div class="row">

                    <div class="col-mg-12 text-center">
                        <input type="hidden" name="id_curso" value="<?php echo @$_POST['id_curso'] ?>" />
                        <input type="hidden" name="id_ciclo" value="<?php echo @$_POST['id_ciclo'] ?>" />
                        <input type="hidden" name="id_grade" value="<?php echo @$_POST['id_grade'] ?>" />
                        <input type="hidden" name="id_disc" value="<?php echo @$_POST['id_disc'] ?>" />
                        <button class="btn btn-success">
                            Salvar
                        </button>
                    </div>
                </div>
                <br /><br />
            </form>
            <?php
            tool::modalFim();
        }
        ?>
        <br /><br />
        <div class="row">
            <div class="col-md-12">
                <?php
                if (!empty($_POST['id_disc'])) {
                    $habilidades = hab::habilidades('1');

                    $sqlkey = DB::sqlKey('hab_habilidade_opcoes', 'delete');

                    foreach ($habilidades as $k => $v) {
                        $v['editar'] = 1;
                        $v['id_grade'] = @$_POST['id_grade'];
                        $habilidades[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[n_competencia_opcoes]' => $v['n_competencia_opcoes']]);
                        $habilidades[$k]['edit'] = formulario::submit('Editar', Null, $v);
                    }
                    $form['array'] = $habilidades;
                    $form['fields'] = [
                        'Competência' => 'n_competencia_opcoes',
                        'Habilidade1' => 'hab1',
                        'Habilidade2' => 'hab2',
                        'Habilidade3' => 'hab3',
                        'Habilidade4' => 'hab4',
                        'Habilidade5' => 'hab5',
                        'Habilidade6' => 'hab6',
                        'Habilidade7' => 'hab7',
                        'Habilidade8' => 'hab8',
                        'Habilidade9' => 'hab9',
                        'Habilidade10' => 'hab10',
                        '||1' => 'del',
                        '||2' => 'edit'
                    ];

                    tool::relatSimples($form);
                }
                ?>
            </div>
        </div>

    </div>

</div>
