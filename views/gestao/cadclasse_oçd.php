<?php
funcionarios::autocomplete('prof');

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
    <div class="fieldTop">
        Cadastro de Classes
    </div>
    <br />
        <input class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value=" Nova Classe" />
        <br /><br />

        <div class="row">
            <div class="col-lg-12">
                <?php $model->listTurma(tool::id_inst()); ?>
            </div>
        </div>
</div>
        <?php
        
        if (empty($_POST['modal'])) {
            $modal = 1;
        }
        tool::modalInicio('width: 95%', @$modal);
        ?>
        <div style="min-height: 80vh">
            <div class="row">
                <div class="col-lg-4" style="height: ">
                    <?php
                    echo formulario::select('id_tp_ens', $model->escola['segmentos'], 'Segmento: ', @$id_tp_ens, 1, ['modal' => 1]);
                    $hidden = ['modal' => 1, 'id_tp_ens' => @$_POST['id_tp_ens']]
                    ?>
                    <br />
                </div>
                <div class="col-lg-4">
                    <?php
                    if (!empty(@$id_tp_ens)) {
                        formulario::selectDB('ge_cursos', 'id_curso', 'Curso: ', @$id_curso, NULL, '1', $hidden, NULL, ['fk_id_tp_ens' => @$id_tp_ens]);
                        $hidden['id_curso'] = @$_POST['id_curso'];
                    }
                    ?>

                </div>
                <div class="col-lg-4">
                    <?php
                    if (!empty($id_curso)) {
                        formulario::selectDB('ge_ciclos', 'id_ciclo', 'Ciclo: ', @$id_ciclo, NULL, '1', $hidden, NULL, ['fk_id_curso' => $id_curso]);
                        $hidden['id_ciclo'] = @$_POST['id_ciclo'];
                    }
                    ?>

                </div>
            </div>
            <br />
            <?php
            if (!empty(@$id_ciclo)) {
                ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-lg-3">
                            <?php
                            $grade = sql::get(['ge_grades', 'ge_curso_grade'], '*', ['fk_id_ciclo' => @$id_ciclo]);
                            foreach ($grade as $v) {
                                if ($v['padrao'] == 1) {
                                    $selected = $v['id_grade'];
                                }
                                $options[$v['id_grade']] = $v['n_grade'];
                            }
                            if (!empty($options)) {
                                formulario::select('1[fk_id_grade]', $options, 'Grade', (empty(@$turma['fk_id_grade']) ? $selected : @$turma['fk_id_grade']));
                            }
                            ?>

                        </div>
                        <div class="col-lg-3">
                            <?php formulario::select('1[periodo]', ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'I' => 'Integral', 'G' => 'Geral'], "periodo", @$turma['periodo']) ?>
                        </div>
                        <div class="col-lg-3">
                            
                            <?php @formulario::select('1[fk_id_pl]', curso::periodList($ciclo['id_ciclo']), "periodo Letivo", @$turma['fk_id_pl']) ?>
                        </div>
                        <div class="col-lg-3">
                            <?php
                            for ($c = 'A'; $c < 'Z'; $c++) {

                                @$options_[$c] = $c;
                            }
                            formulario::select('1[letra]', $options_, 'Turma', @$turma['letra']);
                            ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-lg-4">
                            <?php formulario::input('1[prodesp]', 'Código Prodesp', Null, @$turma['prodesp']) ?>
                        </div>
                        <div class="col-lg-5">
                            <?php echo formulario::input('1[professor]', 'Professor Responsável', NULL, @$turma['professor'], ' id="busca" onkeypress="complete()" ') ?>
                            <input type="hidden" name="1[status]" value="1" />
                        </div>
                        <div class="col-lg-3">
                            <?php echo formulario::input('1[rm_prof]', 'Matrícula', NULL, @$turma['rm_prof'], ' id="rm" ') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-hover table-striped" cellspacing=0 cellpadding=1 border="1">
                                <tr>
                                    <td colspan="5">
                                        <div class="btn btn-default" style="width: 100%">
                                            Selecione uma sala física a qual deseja associar a classe:
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>

                                    </td>
                                    <td>
                                        Sala
                                    </td>
                                    <td>
                                        Piso
                                    </td>
                                    <td>
                                        Capacidade
                                    </td>
                                    <td>
                                        Capacidade
                                    </td>
                                </tr>
                                <?php
                                $predios = predio::instPredio(tool::id_inst());

                                foreach ($predios as $v) {
                                    $salas = predio::salas($v['id_predio'], 'id_sala, n_sala, alunos_sala, piso, cadeirante');
                                    ?>
                                    <tr>
                                        <td colspan="5" style="background-color: wheat">
                                            Prédio:
                                            <?php echo $v['n_predio'] ?>
                                        </td>
                                    </tr>
                                    <?php
                                    foreach ($salas as $s) {
                                        ?>
                                        <tr>
                                            <td>
                                                <input <?php echo $s['id_sala'] == @$turma['fk_id_sala'] ? 'checked' : '' ?> type="radio" name="1[fk_id_sala]" value="<?php echo $s['id_sala'] ?>" />
                                            </td>
                                            <td>
                                                <?php echo $s['n_sala'] ?>
                                            </td>
                                            <td>
                                                <?php echo $s['piso'] ?>
                                            </td>
                                            <td>
                                                <?php echo $s['alunos_sala'] ?>
                                            </td>
                                            <td>
                                                <?php echo $s['cadeirante'] ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <?php echo DB::hiddenKey('ge_turmas', 'replace') ?>
                        <input type="hidden" name="1[id_turma]" value="<?php echo @$turma['id_turma'] ?>" />
                        <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
                        <input type="hidden" name="1[fk_id_ciclo]" value="<?php echo $id_ciclo ?>" />
                        <button class="btn btn-success">
                            Salvar
                        </button>
                    </div>
                </form>
                <div class="col-lg-4 text-center">
                    <form method="POST">
                        <button class="btn btn-danger">
                            Fechar
                        </button>
                    </form>              
                </div>
                <div class="col-lg-4 text-center">
                    <form method="POST">

                        <input type="hidden" name="id_tp_ens" value="<?php echo $id_tp_ens ?>" />
                        <input type="hidden" name="id_curso" value="<?php echo $id_curso ?>" />
                        <input type="hidden" name="id_ciclo" value="<?php echo $id_ciclo ?>" />
                        <input type="hidden" name="limp" value="1" />
                        <button class="btn btn-primary">
                            Limpar
                        </button>
                    </form>
                </div>
            <br /><br /><br />
            </div>
            <?php
        }
        tool::modalFim();
        ?>