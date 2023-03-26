<?php
$profe = professores::listar(tool::id_inst());
foreach ($profe as $v){
    $prof[$v['rm']]= $v['n_pessoa'];
}
asort($prof);
@$id_curso = empty(@$turma['id_curso']) ? @$_REQUEST['id_curso_'] : @$turma['id_curso'];
@$id_ciclo = empty(@$turma['id_ciclo']) ? @$_REQUEST['id_ciclo_'] : @$turma['id_ciclo'];
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
            <?php formulario::select('1[periodo]', ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'G' => 'Geral'], "periodo", @$turma['periodo'], NULL, NULL, ' required disabled ') ?>
        </div>
        <div class="col-lg-3">
            <?php @formulario::select('1[fk_id_pl]', gtMain::periodosCiclos(@$id_ciclo), "periodo Letivo", @$turma['fk_id_pl'], NULL, NULL, ' required disabled ') ?>
        </div>
        <div class="col-lg-3">
            <?php
            for ($c = 'A'; $c < 'Z'; $c++) {

                @$options_[$c] = $c;
            }
            formulario::select('1[letra]', $options_, 'Turma', @$turma['letra'], NULL, NULL, ' required disabled ');
            ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-4">
            <?php formulario::input('1[prodesp]', 'Código Prodesp', Null, @$turma['prodesp'], ' disabled ') ?>
        </div>
        <div class="col-lg-8">
            Professor Responsável: <?php echo form::select('1[rm_prof]', $prof, 'Professor Responsável', @$turma['rm_prof']) ?>
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

    <br /><br />
    <div class="row">
        <div class="col-md-6 text-center">
            <button onclick="$('#lim').submit();" class="btn btn-primary">
                Limpar
            </button>
        </div>
        <div class="col-md-6 text-center">
            <?php echo DB::hiddenKey('ge_turmas', 'replace') ?>
            <input type="hidden" name="1[id_turma]" value="<?php echo @$turma['id_turma'] ?>" />
            <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
            <input type="hidden" name="1[fk_id_ciclo]" value="<?php echo $id_ciclo ?>" />
            <button class="btn btn-success">
                Salvar
            </button>
        </div>
    </div>
</form>

<form id="lim" method="POST">

    <input type="hidden" name="id_curso" value="<?php echo $id_curso ?>" />
    <input type="hidden" name="id_ciclo" value="<?php echo $id_ciclo ?>" />
    <input type="hidden" name="limp" value="1" />

</form>
<br /><br /><br />