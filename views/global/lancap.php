<div class="fieldBody">
    <div class="fieldTop">
        Lançamento
    </div>
    <br /><br />
    <?php
    $disc = disciplina::discId();

    $aval = sql::get(['global_aval', 'global_agrupamento'], 'id_gl, n_gl, ciclos, fk_id_disc', ['prof_lanca' => 1]);
  
    foreach ($aval as $v) {
        $ci = explode(',', $v['ciclos']);
        foreach ($ci as $c) {
            $id_gl[$v['fk_id_disc']][$c] = $v['id_gl'];
        }
    }

    $id_pessoa = tool::id_pessoa();
    if ($escolas = professores::classesDisc($id_pessoa)) { //está configurado no constructor  
        foreach ($escolas as $id_inst => $turma) {
            if (empty($instancia) || @$instancia == $id_inst) {
                ?>
                <br />
                <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
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
                            if (!empty($id_gl)) {
                                foreach ($id_gl as $ky => $y) {
                                    if (!empty($id_gl[$ky][$ciclos]) && (!empty($turma['disciplinas'][$id_turma][$ky]) || !empty($turma['nucleoComum'][$id_turma][$ky])) || ($ky == 29 && (!empty($turma['disciplinas'][$id_turma][9]) || !empty($turma['nucleoComum'][$id_turma][9])))) {
                                        $temAval = 1;
                                        ?>
                                        <div class="col-md-4" style="text-align: center; padding: 8px">
                                            <form method="POST" action="<?php echo HOME_URI ?>/global/lanca">
                                                <input type="hidden" name="id_gl" value="<?php echo $y[$ciclos] ?>" />
                                                <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                                                <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                                                <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                                                <input type="hidden" name="professor" value="1" />
                                                <button class="btn btn-info">
                                                    <?php echo $t . ' - ' . $disc[$ky] ?>
                                                </button>
                                            </form>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        if (empty($temAval)) {
                            ?>
                            <div class="alert alert-warning">
                                <div class="row">
                                    <div class="col-sm-8 text-center">
                                        Você não tem avaliações para lançar
                                    </div>
                                    <div class="col-sm-4">
                                        <img src="<?php echo HOME_URI ?>/views/_images/sossego.png"/>
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <?php
            }
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

</div>