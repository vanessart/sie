<?php
if (!defined('ABSPATH'))
    exit;
$esc = new ng_escola();
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_inst = toolErp::id_inst($id_inst);
$periodos = ng_main::periodosPorSituacao(1);
$turmas = ng_escola::turmasSegAtiva($id_inst);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    $id_curso = sql::get(['ge_turmas', 'ge_ciclos'], 'fk_id_curso', ['id_turma' => $id_turma], 'fetch')['fk_id_curso'];
    $resut = ng_escola::alocaProf($id_turma);
    @$aloca = $resut['aloca'];
    @$prof2 = $resut['prof2'];
    @$supl = $resut['suplementar'];
    @$cit = $resut['cit'];
    @$n_pessoa = $resut['n_pessoa'];
}
?>
<div class="body">
    <div class="fieldTop">
        Alocação de Professores
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1) ?>
        </div>
    </div>
    <br />
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
                </tr>
                <?php
                $disc = turma::disciplinas($id_turma);

                foreach ($disc as $v) {
                    if ($v['nucleo_comum'] == 1) {
                        @$aulas += $v['aulas'];
                        @$nucle_comum .= $v['n_disc'] . '; ';
                    } else {
                        ?>
                        <tr>
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
                                /**
                                  if (@$cit[1][$v['id_disc']] == 1) {
                                  if (!empty($n_pessoa[key($prof2[$v['id_disc']])])) {
                                  echo key($prof2[$v['id_disc']]) . ' - ' . $n_pessoa[key($prof2[$v['id_disc']])];
                                  } else {
                                  echo '<span style="color: red">Professor Não Cadastrado</span>';
                                  }
                                  } elseif (!empty($prof_)) {
                                 * 
                                 */
                                if (!empty($prof_)) {
                                    ?>
                                    <select class="form-select" class="form-select" <?php echo @$disabled ?> name="pr[<?php echo $v['id_disc'] ?>]" style="width: 100%">
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
                                    echo formErp::hidden(['cit[1][' . $v['id_disc'] . ']' => @$cit[1][$v['id_disc']]]);
                                } else {
                                    ?>
                                    <div style="color: red">
                                        Não há PROFESSOR alocado nesta DISCIPLINA.
                                    </div>
                                    <?php
                                }
                                if (in_array($id_curso, [3, 7, 8, 10])) {
                                    if ($id_curso == 3) {
                                        $range = 3;
                                    } else {
                                        $range = 15;
                                    }
                                    /**
                                      if (@$cit[2][$v['id_disc']] == 2) {
                                      if (!empty($n_pessoa[key($prof2[$v['id_disc']])])) {
                                      echo key($prof2[$v['id_disc']]) . ' - ' . $n_pessoa[key($prof2[$v['id_disc']])];
                                      } else {
                                      echo '<span style="color: red">Professor Não Cadastrado</span>';
                                      }
                                      } elseif (!empty($prof_)) {
                                     * 
                                     */
                                    if (!empty($prof_)) {
                                        foreach (range(2, $range) as $p2) {
                                            ?>
                                            <br /><br />
                                            <select class="form-select" <?php echo @$disabled ?> name="pr<?= $p2 ?>[<?php echo $v['id_disc'] ?>]" style="width: 100%">
                                                <option></option>
                                                <?php
                                                foreach ($prof_ as $pp) {
                                                    ?>
                                                    <option <?php echo @$prof2[$v['id_disc']][$pp['rm']] == $p2 ? 'selected' : '' ?> value="<?php echo $pp['rm'] ?>"><?php echo $pp['n_pessoa'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                            echo formErp::hidden(['cit[' . $p2 . '][' . $v['id_disc'] . ']' => @$cit[$p2][$v['id_disc']]]);
                                        }
                                    } else {
                                        ?>
                                        <div style="color: red">
                                            Não há PROFESSOR alocado nesta DISCIPLINA.
                                        </div>
                                        <?php
                                    }
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
                            /**
                              if (@$cit[1]['nc'] == 1) {
                              if (!empty($n_pessoa[key($prof2['nc'])])) {
                              echo key($prof2['nc']) . ' - ' . $n_pessoa[key($prof2['nc'])];
                              } else {
                              echo '<span style="color: red">Professor Não Cadastrado</span>';
                              }
                              } elseif (!empty($prof_)) {
                             * 
                             */
                            if (!empty($prof_)) {
                                ?>
                                <select class="form-select" <?php echo @$disabled ?> name="pr[nc]" style="width: 100%">
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
                                echo formErp::hidden(['cit[1][' . $v['id_disc'] . ']' => @$cit[1][$v['id_disc']]]);
                            } else {
                                ?>
                                <div style="color: red">
                                    Não há PROFESSOR alocado nesta DISCIPLINA.
                                </div>
                                <?php
                            }
                            if (in_array($id_curso, [7, 8])) {
                                if (@$cit[2]['nc'] == 2) {
                                    if (!empty($n_pessoa[key($prof2['nc'])])) {
                                        echo key($prof2['nc']) . ' - ' . $n_pessoa[key($prof2['nc'])];
                                    } else {
                                        echo '<span style="color: red">Professor Não Cadastrado</span>';
                                    }
                                } elseif (!empty($prof_)) {
                                    ?>
                                    <br /><br />
                                    <select class="form-select" <?php echo @$disabled ?> name="pr1[nc]" style="width: 100%">
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
                                    echo formErp::hidden(['cit[2][' . $v['id_disc'] . ']' => @$cit[2][$v['id_disc']]]);
                                } else {
                                    ?>
                                    <div style="color: red">
                                        Não há PROFESSOR alocado nesta DISCIPLINA.
                                    </div>
                                    <?php
                                }
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
                    <?= formErp::hiddenToken('alocaProf') ?>
                    <div class="alert alert-danger">
                        <p>
                        Atenção!!!
                            Se sua escola não for uma terceirizada e existir um professor alocado a esta turma no DTPG, as alterações serão sobrepostas na próxima madrugada.
                        </p>
                    </div>
                    <br /><br />
                    <button class="btn btn-success" type="submit">

                        Salvar
                    </button>
                </div>
            </div>
        </form>
        <?php
    }
    ?>
</div>
