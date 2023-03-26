<?php
$sisAberto = $model->sistemaAberto();
if (!empty($sisAberto)) {
?>
    <div class="alert alert-danger" style="text-align: center; font-weight: bold;font-size: 18px">
        <?php echo $sisAberto ?>
    </div>
    <?php
    return;
}

if (user::session('id_nivel') != 10) {
    $id_inst = $id_inst1 = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $id_inst1 = filter_input(INPUT_POST, 'id_inst1', FILTER_SANITIZE_NUMBER_INT);
}
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
$id_li1 = filter_input(INPUT_POST, 'id_li1', FILTER_SANITIZE_NUMBER_INT);

if (!empty($id_inst)) {
    $transLinha = toolErp::idName(transporteErp::search($id_inst), 'id_li', 'n_li');
}
$listaBranco = array_column(sqlErp::get('transporte_lita_branca'), 'id_pessoa');
?>
<div class="body">
    <div class="fieldTop">
        Trocar de Ônibus    
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-5">
            <div>
                <?php
                if (user::session('id_nivel') == 10) {
                    echo formErp::select('id_inst1', escolas::idInst(NULL, NULL, 1), 'Escola', $id_inst1, 1, ['id_inst' => $id_inst, 'id_li' => $id_li, 'id_li1' => $id_li1]);
                }
                ?>
            </div>
            <br />
            <?php
            if (!empty($id_inst1)) {
                $transLinha1 = transporteErp::nomeLinha($id_inst1);
                if (!empty($transLinha1['Escola'])) {
                    echo formErp::select('id_li1', $transLinha1, 'Linha', $id_li1, 1, ['id_inst' => $id_inst, 'id_li' => $id_li, 'id_inst1' => $id_inst1,]);
                } else {
                    ?>
                    <div class="alert alert-danger">
                        Não há ônibus alocado nesta Escola
                    </div>
                    <?php
                    exit();
                }
                if (!empty($id_li1)) {
                    $linhaDados = transporteErp::linhaGet($id_li1);

                    $alunos = transporteErp::LinhaAlunos($id_li1, $id_inst);
                    $utilizados = 0;
                    $espera = 0;
                    foreach ($alunos as $v) {
                        if ($v['fk_id_sa'] == 1) {
                            $utilizados++;
                        } elseif ($v['fk_id_sa'] == 0) {
                            $espera++;
                        }
                    }

                    $t = transporteErp::getTotaisStatus($alunos);
                    ?>
                    <br /><br />
                    <form id="linha1" method="POST" action="<?php echo HOME_URI ?>/transporte/troca#topo">
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <tr>
                                <td colspan="7">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            Linha: <?php echo $linhaDados['n_li'] ?> (Viagem: <?php echo $linhaDados['viagem'] ?>)
                                            <br /><br />
                                            Motorista: <?php echo $linhaDados['motorista'] ?>
                                            <br /><br />
                                            Período: <?php echo dataErp::periodoDoDia($linhaDados['periodo']) ?>
                                            <br /><br />
                                            Acessibilidade: <?php echo toolErp::simnao($linhaDados['acessibilidade']) ?>
                                            <br /><br />
                                            Monitor: <?php echo $linhaDados['monitor'] ?>
                                            <br /><br />
                                            Abrangência: <?php echo $linhaDados['abrangencia'] ?>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <br /><br />
                                            <button class="btn btn-info" type="button" onclick=" $('#modalO').modal('show'); document.getElementById('verO1').submit();">
                                                Ver no Mapa
                                            </button>
                                        </div>
                                    </div>
                                    <div id="topo" ></div>
                                    <table class="table table-bordered table-hover table-responsive table-striped">
                                        <tr>
                                            <td align="center">
                                                Vagas
                                            </td>
                                            <td align="center">
                                                Utilizados
                                            </td>
                                            <td align="center">
                                                Espera
                                            </td>
                                            <td align="center">
                                                Capacidade
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $vagas = intval(@$linhaDados['capacidade']) - intval($utilizados);
                                            ?>
                                            <td align="center" <?php echo $vagas < 1 ? 'style="background-color: red; color: white"' : '' ?>>
                                                <?php echo intval($vagas) ?>
                                            </td>
                                            <td align="center">
                                                <?php echo intval($utilizados) ?>
                                            </td>
                                            <td align="center">
                                                <?php echo intval($espera) ?>
                                            </td>
                                            <td align="center">
                                                <?php echo intval(@$linhaDados['capacidade']) ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td>
                                                *
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-success" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[1] ?></button> Deferido
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-warning" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[0] ?></button> Aguardando Deferimento
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-danger" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[2] ?></button> Indeferido
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-primary" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[3] ?></button> Em espera
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-secondary" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[6] ?></button> Encerrado
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Distância
                                </td>
                                <td>
                                    Nº
                                </td>
                                <td>
                                    RA
                                </td>
                                <td>
                                    Nome
                                </td>
                                <td>
                                    Turma
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                            </tr>
                            <?php
                            foreach ($alunos as $a) {
                                ?>
                                <tr>
                                    <td style="width: 100px">
                                        <?php
                                        $nBtn = NULL;
                                        if (!empty($a['distancia_esc'])) {
                                            $medida = explode(' ', $a['distancia_esc'])[1];
                                            if ($medida == 'm') {
                                                $btn = "warning";
                                            } else {
                                                $setDist = str_replace(',', '.', explode(' ', $a['distancia_esc'])[0]);
                                                if (in_array($a['fk_id_ciclo'], [1, 2, 3, 4, 19, 20])) {
                                                    $dist = 0.8;
                                                } else {
                                                    $dist = 1.1;
                                                }

                                                if ($setDist < $model->_setup['distancia_min'] || $setDist > $model->_setup['distancia']) {
                                                    $nBtn = 'data-balloon="Distância: de ' . $model->_setup['distancia_min'] . ' a ' . $model->_setup['distancia'] . ' KM"';
                                                    $btn = "danger";
                                                } elseif ($setDist <= $dist) {
                                                    $btn = "warning";
                                                } else {
                                                    $btn = "info";
                                                }
                                            }
                                        } else {
                                            $nBtn = 'data-balloon="Distância: de ' . $model->_setup['distancia_min'] . ' a ' . $model->_setup['distancia'] . ' KM"';
                                            $btn = "danger";
                                        }
                                        if (in_array($a['id_pessoa'], $listaBranco)) {
                                            $nBtn = NULL;
                                        }
                                        ?>
                                        <button <?php echo $nBtn ?> style="width: 100px" onclick="$('#myModal').modal('show');acesso('<?php echo $a['id_pessoa'] ?>', '<?php echo $a['fk_id_inst'] ?>')" type="button" class=" btn btn-<?php echo @$btn ?>">
                                            &#8634; <?php echo $a['distancia_esc'] ?>
                                        </button>
                                    </td>
                                    <td>
                                        <?php echo $a['chamada'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['ra'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['n_pessoa'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['n_turma'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (user::session('id_nivel') == 10) {
                                            $onclick = "onclick=\"status('" . $a['id_pessoa'] . "', " . intval($a['fk_id_sa']) . ")\"";
                                        }
                                        ?>
                                        <button <?php echo @$onclick ?> type="button" class="btn btn-<?php echo $a['fk_id_sa'] == 1 ? 'success' : 'warning' ?>" style="width: 20px"></button>
                                    </td>
                                    <td>
                                        <?php
                                        if ((user::session('id_nivel') == 10) || toolErp::id_inst() == $a['fk_id_inst']) {
                                            ?>
                                            <input onclick="document.getElementById('lin_lin1').style.display = 'none';document.getElementById('lin1_lin').style.display = '';" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                        </table>
                        <input type="hidden" name="id_li_destino" value="<?php echo $id_li ?>" />
                        <?php
                        echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_inst1' => $id_inst1, 'id_li1' => $id_li1]);
                        echo formErp::hiddenToken('trocaLinha');
                        ?>
                    </form>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-sm-2" style="text-align: center; padding-top: 600px">
            <?php
            if (!empty($id_li1) && !empty($id_li)) {
                ?>
                <button id="lin1_lin" onclick="document.getElementById('linha1').submit()" style="font-weight: bold; display: none" class="btn btn-warning">&#8674;</button>
                <br /><br />
                <button id="lin_lin1" onclick="document.getElementById('linha').submit()" style="font-weight: bold; display: none" class="btn btn-warning">&#8592;</button>
                <?php
            }
            ?>
        </div>
        <div class="col-sm-5">
            <div>
                <?php
                if (user::session('id_nivel') == 10) {
                    echo formErp::select('id_inst', escolas::idInst(NULL, NULL, 1), 'Escola', $id_inst, 1, ['id_li' => $id_li, 'id_inst1' => $id_inst1, 'id_li1' => $id_li1]);
                }
                ?>
            </div>
            <br />
            <?php
            if (!empty($id_inst)) {
                $transLinha = transporteErp::nomeLinha($id_inst);
                if (!empty($transLinha['Escola'])) {
                    echo formErp::select('id_li', $transLinha, 'Linha', $id_li, 1, ['id_inst' => $id_inst, 'id_inst1' => $id_inst1, 'id_li1' => $id_li1]);
                } else {
                    ?>
                    <div class="alert alert-danger">
                        Não há ônibus alocado nesta Escola
                    </div>
                    <?php
                    exit();
                }
                if (!empty($id_li)) {
                    $linhaDados = transporteErp::linhaGet($id_li);
                    $alunos = transporteErp::LinhaAlunos($id_li, $id_inst);
                    $utilizados = 0;
                    $espera = 0;
                    foreach ($alunos as $v) {
                        if ($v['fk_id_sa'] == 1) {
                            $utilizados++;
                        } elseif ($v['fk_id_sa'] == 0) {
                            $espera++;
                        }
                    }

                    $t = transporteErp::getTotaisStatus($alunos);
                    ?>
                    <br /><br />
                    <form id="linha" method="POST" action="<?php echo HOME_URI ?>/transporte/troca#topo">
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <tr>
                                <td colspan="7">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            Linha: <?php echo $linhaDados['n_li'] ?> (Viagem: <?php echo $linhaDados['viagem'] ?>)
                                            <br /><br />
                                            Motorista: <?php echo $linhaDados['motorista'] ?>
                                            <br /><br />
                                            Período: <?php echo dataErp::periodoDoDia($linhaDados['periodo']) ?>
                                            <br /><br />
                                            Acessibilidade: <?php echo toolErp::simnao($linhaDados['acessibilidade']) ?>
                                            <br /><br />
                                            Monitor: <?php echo $linhaDados['monitor'] ?>
                                            <br /><br />
                                            Abrangência: <?php echo $linhaDados['abrangencia'] ?>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <br /><br />
                                            <button class="btn btn-info" type="button" onclick=" $('#modalO').modal('show'); document.getElementById('verO').submit();">
                                                Ver no Mapa
                                            </button>
                                        </div>
                                    </div>
                                    <div id="topo" ></div>
                                    <table class="table table-bordered table-hover table-responsive table-striped">
                                        <tr>
                                            <td align="center">
                                                Vagas
                                            </td>
                                            <td align="center">
                                                Utilizados
                                            </td>
                                            <td align="center">
                                                Espera
                                            </td>
                                            <td align="center">
                                                Capacidade
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $vagas = intval(@$linhaDados['capacidade']) - intval(@$utilizados);
                                            ?>
                                            <td align="center" <?php echo $vagas < 1 ? 'style="background-color: red; color: white"' : '' ?>>
                                                <?php echo intval($vagas) ?>
                                            </td>
                                            <td align="center">
                                                <?php echo intval(@$utilizados) ?>
                                            </td>
                                            <td align="center">
                                                <?php echo intval(@$espera) ?>
                                            </td>
                                            <td align="center">
                                                <?php echo intval(@$linhaDados['capacidade']) ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td>
                                                *
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-success" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[1] ?></button> Deferido
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-warning" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[0] ?></button> Aguardando Deferimento
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-danger" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[2] ?></button> Indeferido
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-primary" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[3] ?></button> Em espera
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-secondary" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[6] ?></button> Encerrado
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>
                                    Distância
                                </td>
                                <td>
                                    Nº
                                </td>
                                <td>
                                    RA
                                </td>
                                <td>
                                    Nome
                                </td>
                                <td>
                                    Turma
                                </td>
                                <td>

                                </td>
                            </tr>
                            <?php
                            foreach ($alunos as $a) {
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        if ((user::session('id_nivel') == 10) || toolErp::id_inst() == $a['fk_id_inst']) {
                                            ?>
                                            <input onclick="document.getElementById('lin1_lin').style.display = 'none';document.getElementById('lin_lin1').style.display = '';" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 100px">
                                        <?php
                                        $nBtn = NULL;
                                        if (!empty($a['distancia_esc'])) {
                                            $medida = explode(' ', $a['distancia_esc'])[1];
                                            if ($medida == 'm') {
                                                $btn = "warning";
                                            } else {
                                                $setDist = str_replace(',', '.', explode(' ', $a['distancia_esc'])[0]);
                                                if (in_array($a['fk_id_ciclo'], [1, 2, 3, 4, 19, 20])) {
                                                    $dist = 0.8;
                                                } else {
                                                    $dist = 1.1;
                                                }

                                                if ($setDist > $model->_setup['distancia']) {
                                                    $nBtn = 'data-balloon="Distância máxima: ' . $model->_setup['distancia'] . ' KM"';
                                                    $btn = "danger";
                                                } elseif ($setDist <= $dist) {
                                                    $btn = "warning";
                                                } else {
                                                    $btn = "info";
                                                }
                                            }
                                        } else {
                                            $nBtn = 'data-balloon="Distância máxima: ' . $model->_setup['distancia'] . ' KM"';
                                            $btn = "danger";
                                        }
                                        if (in_array($a['id_pessoa'], $listaBranco)) {
                                            $nBtn = NULL;
                                        }
                                        ?>
                                        <button <?php echo $nBtn ?> style="width: 100px" onclick="$('#myModal').modal('show');acesso('<?php echo $a['id_pessoa'] ?>', '<?php echo $a['fk_id_inst'] ?>')" type="button" class=" btn btn-<?php echo @$btn ?>">
                                            &#8634; <?php echo $a['distancia_esc'] ?>
                                        </button>
                                    </td>
                                    <td>
                                        <?php echo $a['chamada'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['ra'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['n_pessoa'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['n_turma'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (user::session('id_nivel') == 10) {
                                            $onclick = "onclick=\"status('" . $a['id_pessoa'] . "', " . intval($a['fk_id_sa']) . ")\"";
                                        }
                                        ?>
                                        <button <?php echo @$onclick ?> type="button" class="btn btn-<?php echo $a['fk_id_sa'] == 1 ? 'success' : 'warning' ?>" style="width: 20px"></button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                        </table>
                        <input type="hidden" name="id_li_destino" value="<?php echo $id_li1 ?>" />
                        <?php
                        echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_inst1' => $id_inst1, 'id_li1' => $id_li1]);
                        echo formErp::hiddenToken('trocaLinha');
                        ?>
                    </form>
                    <?php
                }
            }
            ?>
        </div>

    </div>
    <br /><br />
    <form id="sta" method="POST" action="<?php echo HOME_URI ?>/transporte/troca#topo">
        <?php echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_inst1' => $id_inst1, 'id_li1' => $id_li1]); ?>
        <input id="idP" type="hidden" name="id_pessoa" value="" />
        <input id="idSt" type="hidden" name="fk_id_sa" value="" />
        <input type="hidden" name="mudaStatus" value="1" />
    </form>
    <form id="mp" method="POST"  action="<?php echo HOME_URI ?>/transporte/troca#topo">
        <?php echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_inst1' => $id_inst1, 'id_li1' => $id_li1]); ?>
        <input id="idPe" type="hidden" name="id_pessoa" value="" />
        <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
        <input type="hidden" name="mapaReflech" value="1" />
    </form>
    <form id="verO" target="verOnibus" action="<?php echo HOME_URI ?>/transporte/veronibus" method="POST">
        <input id="idpes" type="hidden" name="id_li" value="<?php echo $id_li ?>" />
        <input id="idpes" type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
    </form>
    <form id="verO1" target="verOnibus" action="<?php echo HOME_URI ?>/transporte/veronibus" method="POST">
        <input id="idpes" type="hidden" name="id_li" value="<?php echo $id_li1 ?>" />
        <input id="idpes" type="hidden" name="id_inst" value="<?php echo $id_inst1 ?>" />
    </form>
    <script>
        function status(idPessoa, status) {
            document.getElementById('idP').value = idPessoa;
            document.getElementById('idSt').value = status;
            document.getElementById('sta').submit();
        }

        function mapa(idPessoa) {
            document.getElementById('idPe').value = idPessoa;
            document.getElementById('mp').submit();
        }

        function acesso(idPessoa, idInst) {
            document.getElementById('idpesVer').value = idPessoa;
            document.getElementById('idinsVer').value = idInst;
            document.getElementById('ver').submit();
        }
    </script>

</div>
<form id="ver" target="verpage" action="<?php echo HOME_URI ?>/transporte/ver" method="POST">
    <input id="idpesVer" type="hidden" name="id_pessoa" value="" />
    <input id="idinsVer" type="hidden" name="id_inst" value="" />
</form>
<?php
toolErp::modalInicio(0);
?>
<iframe name="verpage" style="border: none; width: 100%; height: 80vh"></iframe>
<?php
toolErp::modalFim();
toolErp::modalInicio(0, null, 'modalO');
?>
<iframe name="verOnibus" style="border: none; width: 100%; height: 80vh"></iframe>
<?php
toolErp::modalFim();
?>