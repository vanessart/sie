<div class="fieldBody">
    <?php
    $id_agrup = filter_input(INPUT_POST, 'id_agrup', FILTER_SANITIZE_NUMBER_INT);
    echo form::selectDB('global_agrupamento', 'id_agrup', 'Agrupamento', $id_agrup, 1, NULL, NULL, ['prof_relat' => 1, '>' => 'n_agrup']);

    if (!empty($id_agrup)) {
        $disc = disciplina::discId();

        $aval = sql::get('global_aval', 'id_gl, n_gl, ciclos, fk_id_disc', ['fk_id_agrup'=>$id_agrup]);
        foreach ($aval as $v) {
            $ci = explode(',', $v['ciclos']);
            foreach ($ci as $c) {
                $id_gl[$v['fk_id_disc']][$c] = $v['id_gl'];
            }
        }
        $id_pessoa = tool::id_pessoa();
        if ($escolas = professores::classesDisc($id_pessoa)) { //está configurado no constructor  
            foreach (['Devolutivas' => 'devolprof', 'Devolutiva por Aluno' => 'diaga', 'Resumo' => 'resumo'] as $k => $v) {
//        foreach (['Gráficos'=>'diag', 'Habilidades por Aluno'=>'diaga'] as $k => $v) {
                ?>
                <div class="fieldBorder2">
                    <div style="text-align: center;font-weight: bold; font-size: 25px">
                        <?php echo $k ?>
                    </div>
                    <?php
                    foreach ($escolas as $id_inst => $turma) {
                        if (empty($instancia) || @$instancia == $id_inst) {
                            ?>
                            <br />
                            <div role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php echo $turma['escola'] ?>
                                    </div>
                                </div>
                                <br /><br />
                                <div class="row">
                                    <?php
                                    asort($turma['turmas']);
                                    foreach ($turma['turmas'] as $id_turma => $t) {
                                        $turmas[$id_inst][] = $id_turma;
                                        $ciclos = sql::get('ge_turmas', 'fk_id_ciclo', ['id_turma' => $id_turma], 'fetch')['fk_id_ciclo'];
                                        foreach ($id_gl as $ky => $y) {
                                            if (!empty($id_gl[$ky][$ciclos]) && (!empty($turma['disciplinas'][$id_turma][$ky]) || !empty($turma['nucleoComum'][$id_turma][$ky])) || ($ky == 29 && (!empty($turma['disciplinas'][$id_turma][9]) || !empty($turma['nucleoComum'][$id_turma][9])))) {
                                                $temAval = 1;
                                                ?>
                                                <div class="col-md-4" style="text-align: center; padding: 8px">
                                                    <form method="POST" action="<?php echo HOME_URI ?>/global/<?php echo $v ?>">
                                                        <input type="hidden" name="id_gl" value="<?php echo $y[$ciclos] ?>" />
                                                        <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                                                        <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                                                        <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                                                        <input type="hidden" name="classeDisc" value="<?php echo $t . ' - ' . $disc[$ky] ?>" />
                                                        <input type="hidden" name="escola" value="<?php echo $turma['escola'] ?>" />
                                                        <input type="hidden" name="professor" value="1" />
                                                        <button class="btn btn-info">
                                                            <?php echo $t ?>
                                                        </button>
                                                    </form>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    ?>
                </div>
                <br /><br /><br />
                <?php
            }
        } else {
            ?>
            <br /><br /><br />
            <div class="alert alert-danger" style="font-size: 18px; text-align: center">
                Você não tem classes alocadas. Para alocar, procure a secretaria de sua escola.
            </div>
            <?php
        }
        ?>
        <br /><br />
        <br /><br />
        <?php
    }
    ?>
</div>