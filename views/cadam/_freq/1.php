<?php
if (!empty($_REQUEST['uniqid'])) {
    $uniqid = $_REQUEST['uniqid'];
} else {
    $uniqid = uniqid();
}
$id_cad = @$_POST['id_cad'];
$editar = @$_POST['editar'];
$per = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'G'=>'Geral'];
$nomeDiaSemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
$id_inst = @$_REQUEST['id_inst'];
$fk_id_mot = @$_POST['fk_id_mot'];
$mesSet = @$_REQUEST['mesSet'];
$dia = @$_POST['dia'];
$periodo = @$_POST['periodo'];
$rm = @$_POST['rm'];
$aulas = @$_POST['aulas'];
$id_cargo = @$_POST['id_cargo'];
$iddisc = sql::get('cadam_cargo', 'fk_id_disc', ['id_cargo' => $id_cargo], 'fetch')['fk_id_disc'];
$hidden = ['id_inst' => $id_inst, 'mesSet' => $mesSet, 'uniqid' => $uniqid, 'aulas' => $aulas];
if (!empty($_POST['idturmas'])) {
    if (is_array($_POST['idturmas'])) {
        $idturmas = @$_POST['idturmas'];
    } else {
        $idturmas = unserialize(str_replace('|', '"', @$_POST['idturmas']));
    }
}
?>
<br />
<div style="height: 80vh; width: 95%; margin: 0 auto">
    <div class="row">
        <div class="col-sm-12">
            <?php
            formulario::selectDB('cadam_cargo', 'id_cargo', 'Cargo', @$id_cargo, ' required ', 1, $hidden);
            $hidden['id_cargo'] = @$id_cargo;
            ?>
        </div>
    </div>
    <?php
    if (!empty($id_cargo)) {
        $prof = funcionarios::profEscola($id_inst, $iddisc);
        foreach ($prof as $v) {
            $proff[$v['rm']] = $v['n_pessoa'];
        }
        $proff[3] = 'Sem Professor';
        if (!empty($dia)) {
            $disabled = "disabled";
        }
        ?>

        <form method="POST">
            <br /><br />
            <div class="row">
                <div class="col-sm-6">
                    <?php echo formulario::select('rm', @$proff, 'professor', NULL, NULL, $hidden, ' required ' . @$disabled) ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo formulario::selectDB('cadam_motivo', 'fk_id_mot', 'Motivo', @$cadam_motivo, ' required ' . @$disabled);
                    ?>
                </div>
            </div>
            <br /><br />    
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-3">  
                    <?php
                    formulario::select('periodo', $per, 'Período', @$periodo, NULL, NULL, ' required ' . @$disabled);
                    ?>
                </div>
                <div class="col-sm-3">  
                    <?php
                    $du = data::diasUteis($mesSet, date('Y'));
                    foreach ($du as $k => $v) {
                        $diasUteis[$v] = str_pad($v, 2, "0", STR_PAD_LEFT) . '/' . str_pad($mesSet, 2, "0", STR_PAD_LEFT) . '/' . date("Y");
                    }
                    echo formulario::select('dia', $diasUteis, 'Dia da Falta', @$_POST['dia'], NULL, NULL, @$disabled . ' required');
                    ?>
                </div>
            </div>
            <br /><br />
            <div style="text-align: center">
                <?php
                if (empty($dia)) {
                    echo formulario::hidden($hidden);
                    ?>
                    <input class = "btn btn-success" type = "submit" value = "Continuar" />
                    <?php
                }
                ?>
            </div>
        </form>
        <?php
        if (!empty($dia)) {
            @$hidden['idturmas'] = str_replace('"', '|', serialize($idturmas));
            $hidden['fk_id_mot'] = $fk_id_mot;
            $hidden['id_inst'] = $id_inst;
            $hidden['mesSet'] = $mesSet;
            $hidden['dia'] = $dia;
            $hidden['periodo'] = $periodo;
            $hidden['rm'] = $rm;
            $hidden['aulas'] = $aulas;
            $hidden['uniqid'] = $uniqid;
            if($id_cargo == 15){
                $id_cargo = 3;
            }
            $hidden['id_cargo'] = $id_cargo;
            $data = date("Y") . '-' . $mesSet . '-' . $dia;
            $diaSemana = date('w', strtotime($data));
            $horario = professores::horario(@$_POST['rm']);

            $confereLanc = cadamp::confereaLocado($id_cargo, $dia, $mesSet, $periodo, $id_inst, $rm);

            if (!empty($confereLanc['rm'])) {
                ?>
                <div class="alert alert-danger" style="text-align: center; font-size: 18px">
                    Este Professor Já foi substituído neste período e dia.  
                </div>
                <?php
            } else {
                ?>
                <form method="POST">
                    <div class="row grou">
                        <?php
                        echo formulario::hidden($hidden);
                        $idDisc_=explode(',', $iddisc);
                        foreach ($idDisc_ as $h){
                            $h = str_replace("'", '', $h);
                            if(!empty($horario[$h])){
                                $iddisc = $h;
                            }
                        }
                       
                        foreach (range(1, 5) as $c) {
                            if (!empty($horario[$iddisc][$diaSemana][$periodo][$c]['n_turma'])) {
                                $turma = $horario[$iddisc][$diaSemana][$periodo][$c];
                                ?>
                                <div class="col-sm-3">
                                    <div class="input-group" style="width: 100%">
                                        <label  style="width: 100%">
                                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                                <input <?php echo!empty($idturmas) ? 'disabled' : '' ?> <?php echo!empty($idturmas[$turma['id_turma']][$c]) ? 'checked' : '' ?> type="checkbox" aria-label="..." name="idturmas[<?php echo $turma['id_turma'] ?>][<?php echo $c ?>]" value="<?php echo $c ?>">
                                            </span>
                                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                                <?php echo $c . 'º Aula (' . $turma['n_turma'] . ')' ?>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <br />
                    <div style="text-align: center">
                        <?php
                        if (empty($turma)) {
                            ?>
                            <div class="alert alert-danger" style="text-align: center; font-size: 18px">
                                Este Professor não leciona esta disciplina neste dia e período.  
                            </div>
                            <?php
                        } elseif (empty($idturmas)) {
                            ?>
                            <input class = "btn btn-success" type = "submit" value = "Continuar" />
                            <?php
                        }
                        ?>
                    </div>
                </form>
                <br /><br />
                <?php
                if (!empty($idturmas)) {
                    $reservadoSet = cadamp::reservaSet($mesSet, null, $dia, $periodo);

                    $jaConvocado = cadamp::jaContato($id_inst, $id_cargo, $diaSemana . $periodo);

                    if (!empty($jaConvocado)) {


                        foreach ($jaConvocado as $k => $v) {
                            $v['id_inst'] = $id_inst;
                            $v['mesSet'] = $mesSet;
                            $v['fk_id_mot'] = $fk_id_mot;
                            $v['dia'] = $dia;
                            $v['periodo'] = $periodo;
                            $v['rm'] = $rm;
                            $v['aulas'] = $aulas;
                            $v['id_cargo'] = $id_cargo;
                            $v['editar'] = 1;
                            $v['idturmas'] = str_replace('"', '|', serialize($idturmas));
                            if ($v['contato'] == 1 && $uniqid == $v['uniqid']) {
                                $notin[] = $v['id_cad'];
                                $v['uniqid'] = $uniqid;
                                $jaConvocado[$k]['sit'] = 'Aceitou';
                                $jaConvocado[$k]['edit'] = formulario::submit('Alterar', NULL, $v);
                                $aceitou[] = $jaConvocado[$k];
                            } elseif ($v['contato'] == 1) {
                                unset($jaConvocado[$k]);
                            } elseif ($v['contato'] == 2) {
                                $notin[] = $v['id_cad'];
                                $naoEncontrado;
                                $v['uniqid'] = $uniqid;
                                $jaConvocado[$k]['sit'] = 'Não encontrado';
                                $jaConvocado[$k]['edit'] = formulario::submit('Tentar Novamente', NULL, $v);
                            } elseif ($v['contato'] == 3) {
                                $notin[] = $v['id_cad'];
                                $jaConvocado[$k]['sit'] = 'Recusou';
                            } elseif ($v['contato'] == 4) {
                                $notin[] = $v['id_cad'];
                                $jaConvocado[$k]['sit'] = 'Alocado';
                                 $jaConvocado[$k]['edit'] = formulario::submit('Tentar Novamente', NULL, $v);
                           } elseif ($v['contato'] == 5) {
                                if (!in_array($rm, $reservadoSet)) {
                                    $notin[] = $v['id_cad'];
                                }
                                $jaConvocado[$k]['sit'] = 'Reservado';
                            }
                            if (!empty($v['rm_reservado']) && !in_array($rm, $reservadoSet)) {
                                unset($jaConvocado[$k]);
                            }
                        }
                    }

                    $reserva = cadamp::reserva($id_inst, $mesSet, NULL, date("Y"), $rm, $dia, $periodo, $id_cargo);


                    if (!empty($reserva[0]['id_cad'])) {
                        if (!empty($notin)) {
                            if (in_array($reserva[0]['id_cad'], $notin)) {
                                $pula = 1;
                            }
                        }
                        if (empty($pula)) {
                            $editar = 1;
                            $id_cad = $reserva[0]['id_cad'];
                        }
                    }


                    if (empty($jaConvocado) || !empty($editar) || !empty($_POST['novo'])) {

                        include ABSPATH . '/views/cadam/_freq/1_1.php';
                    } else {

                        if (empty($aceitou)) {
                            $form['array'] = $jaConvocado;
                        } else {
                            $form['array'] = $aceitou;
                        }
                        $form['fields'] = [
                            'CAD PMB' => 'cad_pmb',
                            'Nome' => 'n_pessoa',
                            'Tel1' => 'tel1',
                            'Tel2' => 'tel2',
                            'Tel3' => 'tel3',
                            'email' => 'email',
                            'Situação' => 'sit',
                            '||' => 'edit'
                        ];

                        tool::relatSimples($form);
                        if (empty($aceitou)) {
                            ?>
                            <form style="text-align: center" method="POST">
                                <?php
                                echo formulario::hidden($hidden);
                                echo formulario::hidden($idturmas);
                                ?>
                                <input type="hidden" name="novo" value="1" />
                                <input class="btn btn-primary" type="submit" value="Tentar outro Cadampe" />
                            </form>
                            <?php
                        } else {
                            ?>
                            <br /><br />
                            <form target="_parent" action="<?php echo HOME_URI ?>/cadam/freq" method="POST">
                                <?php
                                $hidden['id_cad'] = current($aceitou)['id_cad'];
                                $hidden['idturmas'] = str_replace('"', '|', serialize($idturmas));
                                echo formulario::hidden($hidden);
                                echo DB::hiddenKey('finalizarCadampe')
                                ?>
                                <div style="text-align: center">
                                    <input name="finalizado" class="btn btn-success" type="submit" value="Finalizar Convocação" />
                                </div>
                            </form>
                            <br /><br />
                            <?php
                        }
                    }
                }
            }
        }
    }
    ?>
</div>
