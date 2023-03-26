<div style="min-height: 80vh">
    <div class="alert alert-info" style="font-size: 22px; text-align: center">
        Esta operação irá criar uma nova classe e incluirá os alunos da classe clonada.
        <br /><br />
        Esta ação não afetará a classe original.
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?php
            if (!empty(@$id_tp_ens)) {
                formulario::select('id_curso', gtMain::cursosPorSegmento(tool::id_inst()), 'Curso: ', @$_POST['id_curso'], '1', @$_REQUEST);
            }
            ?>

        </div>
        <div class="col-lg-4">
            <?php
            if (!empty($_POST['id_curso'])) {
                formulario::selectDB('ge_ciclos', 'id_ciclo', 'Ciclo: ', @$_POST['id_ciclo'], NULL, '1', @$_REQUEST, NULL, ['fk_id_curso' => @$_POST['id_curso']]);
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

                    <?php
                    $periodoLetivoClone = curso::periodList($ciclo['id_ciclo']);
                    unset($periodoLetivoClone[@$turma['fk_id_pl']]);
                    @formulario::select('1[fk_id_pl]', $periodoLetivoClone, "periodo Letivo", @$turma['fk_id_pl'], NULL, NULL, ' required ');
                    ?>
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
            <div class="col-lg-12 text-center">
                <?php echo DB::hiddenKey('clonarClasse') ?>
                <input type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
                <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
                <input type="hidden" name="1[fk_id_ciclo]" value="<?php echo @$_POST['id_ciclo'] ?>" />
                <button class="btn btn-success">
                    Salvar
                </button>
            </div>
        </form>

        <br /><br /><br />
    </div>
    <?php
}                  