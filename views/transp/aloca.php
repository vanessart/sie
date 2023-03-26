<?php
if (($model->_setup['aberto'] == 1) || user::session('id_nivel') == 10) {
    $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
    if (!empty($id_li)) {
        $linhaDados = transporte::linhaGet($id_li);
    }
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    if (user::session('id_nivel') != 10) {
        $id_inst = tool::id_inst();
    } else {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    }
    $listaBranco = array_column(sql::get('transp_lita_branca'), 'id_pessoa');
    ?>
    <div class="fieldTop">
        Alocação de Alunos
    </div>
    <br /><br />
    <div class="row">
        <?php
        if (user::session('id_nivel') == 10) {
            ?>
            <div class="col-sm-6">
                <?php echo form::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1) ?>
                <br /><br />
            </div>
            <?php
        }
        ?>
    </div>

    <?php
    if (!empty($id_inst)) {
        ?>
        <div class="row">
            <div class="col-sm-5" >
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                        if (!empty($id_inst)) {
                            $turma = turmas::optionNome(NULL, NULL, NULL, NULL, NULL, $id_inst);
                            echo form::select('id_turma', $turma, 'Turma', $id_turma, 1, ['id_inst' => $id_inst, 'id_li' => $id_li]);
                        }
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        if (!empty($id_turma)) {
                            $turmaDados = sql::get('ge_turmas', '*', ['id_turma' => $id_turma], 'fetch');
                            echo 'Período: ' . ($turmaDados['periodo'] == 'T' ? 'Tarde' : ($turmaDados['periodo'] == 'M' ? 'Manhã' : 'Noite'));
                        }
                        ?>
                    </div>

                </div>
                <?php
                if (!empty($id_turma)) {
                    $alunos = $model->AlunoTurma($id_turma, $id_inst);
                    ?>
                    <br /><br />
                    <form id="alunos" method="POST" action="<?php echo HOME_URI ?>/transp/aloca">
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <tr>
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
                                    Distância
                                </td>
                                <td>

                                </td>
                            </tr>
                            <?php
                            foreach ($alunos as $a) {
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
                                <tr>
                                    <td>
                                        <?php echo $a['chamada'] ?> 
                                    </td>
                                    <td>
                                        <?php echo $a['ra'] ?> 
                                    </td>
                                    <td>
                                        <?php echo $a['n_pessoa'] ?>
                                    </td>
                                    <td style="width: 100px">
                                        <button <?php echo $nBtn ?> style="width: 100px" onclick="$('#myModal').modal('show');acesso('<?php echo $a['id_pessoa'] ?>', '<?php echo $a['fk_id_inst'] ?>')" type="button" class=" btn btn-<?php echo @$btn ?>">
                                            <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
                                            <?php echo $a['distancia_esc'] ?>
                                        </button>
                                    </td>
                                    <td>
                                        <?php
                                        if (1) {
//                                      if (!empty($id_li) && (empty($nBtn) || user::session('id_nivel') == 10)) {
                                            ?>
                                            <input onclick="document.getElementById('tur').style.display = '';document.getElementById('lin').style.display = 'none';" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
                        <?php
                        echo form::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]);
                        echo DB::hiddenKey('cadAluno');
                    }
                    ?>
                </form>
            </div>
            <div class="col-sm-1" style="padding-top: 200px; text-align: center">
                <?php
                if (!empty($turma) && !empty($id_li)) {
                    ?>
                    <button id="tur" onclick="document.getElementById('alunos').submit()" style="font-weight: bold; display: none" class="btn btn-warning">
                        <span class="glyphicon glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                    </button>
                    <br /><br />
                    <button id="lin" onclick="document.getElementById('alunosTransp').submit()" style="font-weight: bold; display: none" class="btn btn-warning">
                        <span class="glyphicon glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                    </button>
                    <?php
                }
                ?>
            </div>
            <div class="col-sm-6" >
                <?php
                if (!empty($id_inst) && !empty($id_turma)) {

                    $transLinha = transporte::nomeLinha($id_inst);

                    if (!empty($transLinha['Escola'])) {
                        echo form::select('id_li', $transLinha, 'Linha', $id_li, 1, ['id_inst' => $id_inst, 'id_turma' => $id_turma]);
                    } else {
                        ?>
                        <div class="alert alert-danger">
                            Não há ônibus alocado nesta Escola
                        </div>
                        <?php
                    }
                }
                if (!empty($id_li)) {
                    $alunos = transporte::LinhaAlunos($id_li, $id_inst);

                    foreach ($alunos as $v) {
                        if ($v['fk_id_sa'] == 1) {
                            @$utilizados++;
                        } elseif ($v['fk_id_sa'] == 0) {
                            @$espera++;
                        }
                    }
                    ?>
                    <br /><br />
                    <form id="alunosTransp" method="POST" action="<?php echo HOME_URI ?>/transp/aloca">
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <tr>
                                <td colspan="7">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            Linha: <?php echo $linhaDados['n_li'] ?> (Viagem: <?php echo $linhaDados['viagem'] ?>)
                                            <br /><br />
                                            Motorista: <?php echo $linhaDados['motorista'] ?>
                                            <br /><br />
                                            Período: <?php echo $linhaDados['periodo'] ?>
                                            <br /><br />
                                            Acessibilidade: <?php echo tool::simnao($linhaDados['acessibilidade']) ?>
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

                                    <br /><br />
                                    <table class="table table-bordered table-hover table-responsive table-striped">
                                        <tr>
                                            <td>
                                                Vagas
                                            </td>
                                            <td>
                                                Utilizados
                                            </td>
                                            <td>
                                                Espera
                                            </td>
                                            <td>
                                                Capacidade
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $vagas = intval(@$linhaDados['capacidade']) - intval(@$utilizados);
                                            ?>
                                            <td <?php echo $vagas < 1 ? 'style="background-color: red; color: white"' : '' ?>>
                                                <?php echo intval($vagas) ?>
                                            </td>
                                            <td>
                                                <?php echo intval(@$utilizados) ?>
                                            </td>
                                            <td>
                                                <?php echo intval(@$espera) ?>
                                            </td>
                                            <td>
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
                                                <button type="button" class="btn btn-success" style="width: 20px"></button> Deferido
                                            </td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-warning" style="width: 20px"></button> Aguardando Deferimento
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
                                        if (user::session('id_nivel') == 10) {
                                            if ($a['fk_id_sa'] == 0 && ((user::session('id_nivel') == 10) || tool::id_inst() == $a['fk_id_inst'])) {
                                                ?>
                                                <input onclick="document.getElementById('tur').style.display = 'none';document.getElementById('lin').style.display = '';" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
                                                <?php
                                            }
                                        } else {
                                            if ($a['fk_id_sa'] == 1) {
                                                if (empty($a['fk_id_mot']) && ((user::session('id_nivel') == 10) || tool::id_inst() == $a['fk_id_inst'])) {
                                                    ?>
                                                    <button type="button" class="btn btn-danger" onclick="Cancela('<?php echo $a['id_alu'] ?>')">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                    </button>
                                                    <?php
                                                } elseif((user::session('id_nivel') == 10) || tool::id_inst() == $a['fk_id_inst']) {
                                                    ?>
                                                    <button type="button" class="btn btn-default" >
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                    </button>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <input onclick="document.getElementById('tur').style.display = 'none';document.getElementById('lin').style.display = '';" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 100px">
                                        <?php
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
                                            $btn = "warning";
                                        }
                                        ?>
                                        <button style="width: 100px" onclick="$('#myModal').modal('show');acesso('<?php echo $a['id_pessoa'] ?>', '<?php echo $a['fk_id_inst'] ?>')" type="button" class=" btn btn-<?php echo $btn ?>">
                                            <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
                                            <?php echo $a['distancia_esc'] ?>
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
                        <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
                        <?php
                        echo form::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]);
                        echo DB::hiddenKey('exclAluno');
                        ?>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
    <br /><br />
    <form id="sta" method="POST" action="<?php echo HOME_URI ?>/transp/aloca">
        <?php echo form::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]); ?>
        <input id="idP" type="hidden" name="id_pessoa" value="" />
        <input id="idSt" type="hidden" name="fk_id_sa" value="" />
        <input type="hidden" name="mudaStatus" value="1" />
    </form>
    <form id="cancelalu" method="POST" action="<?php echo HOME_URI ?>/transp/aloca">
        <?php echo form::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]); ?>
        <input id="idAl" type="hidden" name="id_alu" value="" />
        <input type="hidden" name="cancelaLinha" value="1" />
    </form>
    <form id="mp" method="POST" action="<?php echo HOME_URI ?>/transp/aloca">
        <?php echo form::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]); ?>
        <input id="idPe" type="hidden" name="id_pessoa" value="" />
        <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
        <input type="hidden" name="mapaReflech" value="1" />
    </form>

    <form id="ver" target="verpage" action="<?php echo HOME_URI ?>/transp/ver" method="POST">
        <input id="idpes" type="hidden" name="id_pessoa" value="" />
        <input id="idins" type="hidden" name="id_inst" value="" />
    </form>
    <form id="verO" target="verOnibus" action="<?php echo HOME_URI ?>/transp/veronibus" method="POST">
        <input id="idpes" type="hidden" name="id_li" value="<?php echo $id_li ?>" />
        <input id="idpes" type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
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
            document.getElementById('idpes').value = idPessoa;
            document.getElementById('idins').value = idInst;
            document.getElementById('ver').submit();
        }
        function Cancela(idAlu) {
            document.getElementById('idAl').value = idAlu;
            document.getElementById('cancelalu').submit();
        }
    </script>
    <?php
    tool::modalInicio('width: 100%', 1);
    ?>
    <iframe name="verpage" style="border: none; width: 100%; height: 80vh"></iframe>
    <?php
    tool::modalFim();
    tool::modalInicio('width: 100%', 1, 'modalO');
    ?>
    <iframe name="verOnibus" style="border: none; width: 100%; height: 80vh"></iframe>
    <?php
    tool::modalFim();
    if (!empty(@$_POST['cancelaLinha'])) {
        tool::modalInicio('width: 60%', NULL, 'modelCancel');
        $id_alu = filter_input(INPUT_POST, 'id_alu', FILTER_SANITIZE_NUMBER_INT);
        $aluno = transporte::aluAluno($id_alu);
        ?>
        <div style="text-align: center; font-weight: bold">
            Cancelar o Transporte d<?php echo tool::sexoArt($aluno['sexo']) ?> alun<?php echo tool::sexoArt($aluno['sexo']) ?> 
            <br />
            <span style="font-size: 18px; font-weight: bold">
                <?php echo $aluno['n_pessoa'] ?>
            </span>
        </div>
        <br /><br />
        <form method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <?php echo form::selectDB('transp_motivo', '1[fk_id_mot]', 'Motivo', NULL, NULL, NULL, NULL, NULL, 'required') ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo form::hidden([
                        '1[id_alu]' => $id_alu,
                        '1[dt_solicita_fim]' => date("Y-m-d"),
                        'id_inst' => $_POST['id_inst'],
                        'id_li' => $_POST['id_li'],
                        'id_turma' => $_POST['id_turma'],
                        'DBDegub' => 1
                    ]);
                    echo DB::hiddenKey('gt_aluno', 'replace');
                    ?>
                    <button class="btn btn-success">
                        Solicitar
                    </button>
                </div>
            </div>
            <br /><br />
        </form>
        <?php
        tool::modalFim();
    }
} else {
    ?>
    <div class="alert alert-danger" style="text-align: center; font-weight: bold;font-size: 18px">
        Esta operação está fechada
    </div>
    <?php
}
?>


