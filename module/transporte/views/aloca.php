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

$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_li)) {
    $linhaDados = transporteErp::linhaGet($id_li);
}
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if (user::session('id_nivel') != 10) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}
$listaBranco = array_column(sqlErp::get('transporte_lita_branca'), 'id_pessoa');
?>
<div class="body">
    <div class="fieldTop">
        Alocação de Alunos
    </div>
    <br /><br />
    <div class="row">
        <?php
        if (user::session('id_nivel') == 10) {
            ?>
            <div class="col-sm-6">
                <?php echo formErp::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1) ?>
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
                <div class="col-sm-7">
                    <?php
                    if (!empty($id_inst)) {
                        $turma = ng_turmas::optionNome(NULL, NULL, NULL, NULL, $id_inst);
                        if (empty($turma)) {
                            ?>
                            <div class="alert alert-danger">
                                Não há turmas disponíveis nesta Escola
                            </div>
                        <?php
                        } else {
                            echo formErp::select('id_turma', $turma, 'Turma', $id_turma, 1, ['id_inst' => $id_inst, 'id_li' => $id_li]);
                        }
                    }
                    ?>
                </div>
                <div class="col-sm-5">
                    <?php
                    if (!empty($id_turma)) {
                        $turmaDados = sqlErp::get('ge_turmas', 'periodo', ['id_turma' => $id_turma], 'fetch');
                        echo 'Período: ' . dataErp::periodoDoDia($turmaDados['periodo']);
                    }
                    ?>
                </div>
            </div>
            <?php
            if (!empty($id_turma)) {
                $alunos = $model->AlunoTurma($id_turma, $id_inst);
                ?>
                <br /><br />
                <form id="alunos" method="POST" action="<?php echo HOME_URI ?>/transporte/aloca">
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
                                $medida = explode(' ', $a['distancia_esc']);
                                $medida = (!isset($medida[1]) ) ? 'm' : $medida[1];
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
                                        $nBtn = 'data-balloon="Distância: de ' . $model->_setup['distancia_min'] . ' KM a ' . $model->_setup['distancia'] . ' KM"';
                                        $btn = "danger";
                                    } elseif ($setDist <= $dist) {
                                        $btn = "warning";
                                    } else {
                                        $btn = "info";
                                    }

                                }
                            } else {
                                $nBtn = 'data-balloon="Distância: de ' . $model->_setup['distancia_min'] . ' KM a ' . $model->_setup['distancia'] . ' KM"';
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
                                    <button <?php echo $nBtn ?> style="width: 100px" onclick="acesso('<?php echo $a['id_pessoa'] ?>', '<?php echo $a['fk_id_inst'] ?>')" type="button" class=" btn btn-<?php echo @$btn ?>">
                                        &#8634; <?php echo $a['distancia_esc'] ?>
                                    </button>
                                </td>
                                <td>
                                    <?php
                                    if (1) {
                                    //if (!empty($id_li) && (empty($nBtn) || user::session('id_nivel') == 10)) {
                                        ?>
                                        <input onclick="_ckeck()" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <?php
                    echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]);
                    echo formErp::hiddenToken('cadAluno');
                }
                ?>
            </form>
        </div>
        <div class="col-sm-1" style="padding-top: 200px; text-align: center">
            <?php
            if (!empty($turma) && !empty($id_li)) {
                ?>
                <button id="tur" onclick="document.getElementById('alunos').submit()" style="font-weight: bold; display: none" class="btn btn-warning">&#8674;</button>
                <br /><br />
                <button id="lin" onclick="document.getElementById('alunosTransp').submit()" style="font-weight: bold; display: none" class="btn btn-warning">&#8592;</button>
                <?php
            }
            ?>
        </div>
        <div class="col-sm-6" >
            <?php
            if (!empty($id_inst) && !empty($id_turma)) {

                $transLinha = transporteErp::nomeLinha($id_inst);

                if (!empty($transLinha['Escola'])) {
                    echo formErp::select('id_li', $transLinha, 'Linha', $id_li, 1, ['id_inst' => $id_inst, 'id_turma' => $id_turma]);
                } else {
                    ?>
                    <div class="alert alert-danger">
                        Não há ônibus alocado nesta Escola
                    </div>
                    <?php
                }
            }
            if (!empty($id_li)) {
                $alunos = transporteErp::LinhaAlunos($id_li, $id_inst, '6');
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
                <form id="alunosTransp" method="POST" action="<?php echo HOME_URI ?>/transporte/aloca">
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

                                <br /><br />
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
                                        <!--td style="text-align: center">
                                            <button type="button" class="btn btn-secondary" style="width: 20px;line-height: 3px;font-size: smaller;padding-left: 5px;"><?= $t[6] ?></button> Encerrado
                                        </td-->
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
                        <?php foreach ($alunos as $a) { ?>
                        <tr>
                            <td>
                                <?php
                                if (user::session('id_nivel') == 10) {
                                    if ($a['fk_id_sa'] == 0 && ((user::session('id_nivel') == 10) || toolErp::id_inst() == $a['fk_id_inst'])) {
                                        ?>
                                        <input onclick="_unckeck()" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
                                        <?php
                                    }
                                } else {
                                    if ($a['fk_id_sa'] == 1) {
                                        if (empty($a['fk_id_mot']) && ((user::session('id_nivel') == 10) || toolErp::id_inst() == $a['fk_id_inst'])) {
                                            ?>
                                            <button type="button" class="btn btn-danger" onclick="Cancela('<?php echo $a['id_alu'] ?>')">
                                                &#x2718;
                                            </button>
                                            <?php
                                        } elseif((user::session('id_nivel') == 10) || toolErp::id_inst() == $a['fk_id_inst']) {
                                            ?>
                                            <button type="button" class="btn btn-default" >
                                                &#x2718;
                                            </button>
                                            <?php
                                        }
                                    } elseif ($a['fk_id_sa'] == 2) {
                                        ?>
                                            <button type="button" class="btn btn-warning" onclick="novoDeferimento('<?php echo $a['id_alu'] ?>')">
                                                &#8634;
                                            </button>
                                            <?php
                                    } else {
                                        ?>
                                        <input onclick="_unckeck()" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['id_alu'] ?>" />
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
                                        if ($setDist < $model->_setup['distancia_min'] || $setDist > $model->_setup['distancia']) {
                                            $nBtn = 'data-balloon="Distância: de ' . $model->_setup['distancia_min'] . ' KM a ' . $model->_setup['distancia'] . ' KM"';
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
                                <button style="width: 100px" onclick="acesso('<?php echo $a['id_pessoa'] ?>', '<?php echo $a['fk_id_inst'] ?>')" type="button" class="btn btn-<?php echo $btn ?>">
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
                                <button <?php echo @$onclick ?> type="button" class="btn btn-<?php echo transporteErp::getClassStatus($a['fk_id_sa']) ?>" style="width: 20px"></button>
                            </td>
                        </tr>
                        <?php } ?>

                    </table>
                    <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
                    <?php
                    echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]);
                    echo formErp::hiddenToken('exclAluno');
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
<?php toolErp::modalInicio(); ?>
<iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
<?php toolErp::modalFim(); ?>

<form id="sta" method="POST" action="<?php echo HOME_URI ?>/transporte/aloca">
    <?php echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]); ?>
    <input id="idP" type="hidden" name="id_pessoa" value="" />
    <input id="idSt" type="hidden" name="fk_id_sa" value="" />
    <input type="hidden" name="mudaStatus" value="1" />
</form>
<form id="cancelalu" target="frame" method="POST" action="<?php echo HOME_URI ?>/transporte/alocamodal">
    <?php echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]); ?>
    <input id="idAl" type="hidden" name="id_alu" value="" />
    <input type="hidden" name="cancelaLinha" value="1" />
</form>
<form id="novodef" target="frame" method="POST" action="<?php echo HOME_URI ?>/transporte/alocamodal">
    <?php echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]); ?>
    <input id="idAlDef" type="hidden" name="id_alu" value="" />
    <input type="hidden" name="novoDeferimento" value="1" />
</form>
<form id="mp" method="POST" action="<?php echo HOME_URI ?>/transporte/aloca">
    <?php echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'id_turma' => $id_turma]); ?>
    <input id="idPe" type="hidden" name="id_pessoa" value="" />
    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
    <input type="hidden" name="mapaReflech" value="1" />
</form>

<form id="ver" target="frame" action="<?php echo HOME_URI ?>/transporte/ver" method="POST">
    <input id="idpes" type="hidden" name="id_pessoa" value="" />
    <input id="idins" type="hidden" name="id_inst" value="" />
</form>
<form id="verO" target="verOnibus" action="<?php echo HOME_URI ?>/transporte/veronibus" method="POST">
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
        var my_content = document.getElementById('fframe').contentWindow.document;
        my_content.body.innerHTML="";
        document.getElementById('ver').submit();
        $('#myModal').modal('show');
    }
    function Cancela(idAlu) {
        document.getElementById('idAl').value = idAlu;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML = "Cancelamento de Transporte";
        var my_content = document.getElementById('fframe').contentWindow.document;
        my_content.body.innerHTML="";
        document.getElementById("cancelalu").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function novoDeferimento(idAlu) {
        document.getElementById('idAlDef').value = idAlu;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML = "Solicitar Deferimento";
        document.getElementById('novodef').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function _ckeck() {
        if (!document.getElementById('tur')) {
            alert("É preciso informar a Turma e a Linha");
            return false;
        }

        document.getElementById('tur').style.display='';
        document.getElementById('lin').style.display='none';
    }
    function _unckeck() {
        document.getElementById('tur').style.display='none';
        document.getElementById('lin').style.display='';
    }
</script>
<?php
toolErp::modalInicio(0, null, 'modalO');
?>
<iframe name="verOnibus" style="border: none; width: 100%; height: 80vh"></iframe>
<?php toolErp::modalFim(); ?>
</div>
