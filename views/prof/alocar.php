<?php
if (user::session('id_nivel') == 14) {
    $id_inst = @$_POST['id_inst'];
    echo formulario::select('id_inst', escolas::idInst(), 'Escola', @$id_inst, 1);
} else {
    $id_inst = tool::id_inst(@$_POST['id_inst']);
}
if (!empty($id_inst)) {
    $esc = new escola($id_inst);
    @$id_turma = $_POST['id_turma'];
    @$id_curso = $_POST['id_curso'];
    if (!empty($id_turma)) {
        $sql = "Select a.fk_id_turma, a.iddisc, a.fk_id_inst, a.rm, a.prof2, a.suplementar, p.n_pessoa from ge_aloca_prof a "
                . " join ge_funcionario f on f.rm = a.rm "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " where a.fk_id_turma = $id_turma ";
        $query = $model->db->query($sql);
        $alocado = $query->fetchAll();
        foreach ($alocado as $v) {
            $aloca[$v['iddisc']] = $v['rm'];
            $prof2[$v['iddisc']][$v['rm']] = $v['prof2'];
            $supl[$v['prof2']][$v['iddisc']] = $v['suplementar'];
        }

    }
    if (user::session('id_nivel') != 14) {
       // $fechado = cadamp::escolaFechaAbre($id_inst)['professor'];
        $fechado = 0;
        
        if ($fechado == 1) {
            @$disabled = 'disabled';
            ?>
            <div class="alert alert-danger noprint" style="text-align: center; font-size: 18px">
                Alocação Fechada
            </div>
            <?php
        }
    }
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            Alocar Professores
        </div>
        <br /><br />
        <div style="padding-left: 30px">
            <div class="row">

                <?php
                $ensino = $esc->cursos();

                if (count($ensino) > 1) {
                    foreach ($ensino as $e) {
                        $ep[$e['id_curso']] = $e['n_curso'];
                    }
                    ?>
                    <div class="col-md-6">
                        <?php
                        formulario::select('id_curso', $ep, 'Segmento: ', NULL, 1, ['id_inst' => $id_inst]);
                        ?>
                    </div>
                    <?php
                }
                if (count($ensino) == 1 || !empty($id_curso)) {
                    $classes = $esc->turmas(NULL, @$id_curso);

                    if (!empty($classes)) {
                        foreach ($classes as $v) {
                            $options[$v['id_turma']] = $v['codigo'] . ' (' . $v['n_turma'] . ')';
                        }
                        ?>
                        <div class="col-md-6">
                            <?php
                            formulario::select('id_turma', $options, 'Classe: ', NULL, 1, ['id_curso' => $id_curso, 'id_inst' => $id_inst]);
                            ?>
                        </div>
                        <?php
                    } else {
                        tool::alert("Não há Classes nesse segmento");
                    }
                }
                ?>
            </div>
        </div>
        <br /><br />
        <?php
        if (!empty($id_turma)) {
            ?>
            <form method="POST">
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>
                            Disciplina
                        </td>
                        <td>
                            Aulas
                        </td>
                        <td>
                            Professor
                        </td>
                        <td>Carga Suplementar</td>
                    </tr>
                    <?php
                    $disc = turma::disciplinas($id_turma);

                    foreach ($disc as $v) {
                        if ($v['nucleo_comum'] == 1) {
                            @$aulas += $v['aulas'];
                            @$nucle_comum .= $v['n_disc'] . '; ';
                        } else {
                            ?>
                            <tr class="fieldWhite">
                                <td style="width: 40%">
                                    <?php echo $v['n_disc'] ?>
                                </td>
                                <td>
                                    <?php echo $v['aulas'] ?>
                                </td>
                                <td>
                                    <?php
                                    $prf = NULL;
                                    $prof_ = $esc->professores('|' . $v['id_disc'] . '|');
                                    if (!empty($prof_)) {
                                        ?>
                                        <select <?php echo @$disabled ?> name="pr[<?php echo $v['id_disc'] ?>]" style="width: 100%">
                                            <option></option>
                                            <?php
                                            foreach ($prof_ as $pp) {
                                                ?>
                                                <option <?php echo @$prof2[$v['id_disc']][$pp['rm']] == 1 ? 'selected' : '' ?> value="<?php echo $pp['rm'] ?>"><?php echo $pp['n_pessoa'] ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                        <?php
                                        if (in_array($id_curso, [7, 8])) {
                                            ?>
                                            <br /><br />
                                            <select <?php echo @$disabled ?> name="pr1[<?php echo $v['id_disc'] ?>]" style="width: 100%">
                                                <option></option>
                                                <?php
                                                foreach ($prof_ as $pp) {
                                                    ?>
                                                    <option <?php echo @$prof2[$v['id_disc']][$pp['rm']] == 2 ? 'selected' : '' ?> value="<?php echo $pp['rm'] ?>"><?php echo $pp['n_pessoa'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="">
                                            Não há PROFESSOR alocado nesta DISCIPLINA.
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="width: 30px">
                                    <input <?php echo @$supl[1][$v['id_disc']]['suplementar'] == 1 ? 'checked' : '' ?> type="checkbox" name="supl[1][<?php echo $v['id_disc'] ?>]" value="1" />
                                    <?php
                                    if (in_array($id_curso, [7, 8])) {
                                        ?>
                                        <br /><br />
                                        <input <?php echo @$supl[2][$v['id_disc']]['suplementar'] == 1 ? 'checked' : '' ?> type="checkbox" name="supl[2][<?php echo $v['id_disc'] ?>]" value="1" />

                                        <?php
                                    }
                                    ?>

                                </td>
                            </tr>
                            <?php
                        }
                    }
                    if (!empty($nucle_comum)) {
                        ?>
                        <tr>
                            <td>
                                Professor Polivalente (<?php echo substr(@$nucle_comum, 0, -2) ?>)
                            </td>
                            <td>
                                <?php echo $aulas ?>
                            </td>
                            <td>
                                <?php
                                $prf = NULL;
                                $prof_ = $esc->professores('|nc|');
                                if (!empty($prof_)) {
                                    ?>
                                    <select <?php echo @$disabled ?> name="pr[nc]" style="width: 100%">
                                        <option></option>
                                        <?php
                                        foreach ($prof_ as $pp) {
                                            ?>
                                            <option <?php echo @$prof2['nc'][$pp['rm']] == 1 ? 'selected' : '' ?> value="<?php echo $pp['rm'] ?>"><?php echo $pp['n_pessoa'] ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                    <?php
                                    if (in_array($id_curso, [7, 8])) {
                                        ?>
                                        <br /><br />
                                        <select <?php echo @$disabled ?> name="pr1[nc]" style="width: 100%">
                                            <option></option>
                                            <?php
                                            foreach ($prof_ as $pp) {
                                                ?>
                                                <option <?php echo @$prof2['nc'][$pp['rm']] == 2 ? 'selected' : '' ?> value="<?php echo $pp['rm'] ?>"><?php echo $pp['n_pessoa'] ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="">
                                        Não há PROFESSOR alocado nesta DISCIPLINA.
                                    </div>
                                    <?php
                                }
                                ?>
                            </td>
                            <td style="width: 30px">
                                <input <?php echo @$supl[1]['nc']['suplementar'] == 1 ? 'checked' : '' ?> type="checkbox" name="supl[1][nc]" value="1" />

                                <?php
                                if (in_array($id_curso, [7, 8])) {
                                    ?>
                                    <br /><br />
                                    <input <?php echo @$supl[2]['nc']['suplementar'] == 1 ? 'checked' : '' ?> type="checkbox" name="supl[2][nc]" value="1" />

                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-12">

                        </div>
                    </div>
                </table>
                <div class="row">
                    <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />
                    <input type="hidden" name="id_curso" value="<?php echo @$id_curso ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo @$id_inst ?>" />
                    <div class="col-md-12 text-center">
                        <?php if (@$fechado != 1) { ?>
                            <?php echo DB::hiddenKey('alocaProf') ?>
                            <button class="btn btn-success" type="submit">
                                Salvar
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </form>
            <?php
        }
    }
    ?>
</div>
