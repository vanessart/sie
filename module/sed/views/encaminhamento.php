<br />
<?php
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_esc_d = $escola_destino = filter_input(INPUT_POST, 'escola_destino');

//$esc_d = sql::get(['instancia', 'ge_escolas'], 'id_inst, n_inst', ['>' => 'n_inst']);
$esc_d = pegaescolaterceiropre();
$selecao = sql::get(['instancia', 'ge_turmas'], 'codigo', ['id_turma' => $id_turma], 'fetch');
$selecaod = sql::get('instancia', 'n_inst', ['id_inst' => $id_esc_d], 'fetch');

if (!empty($_POST['selecao'])) {
    if (!empty($_POST['id_turma']) AND (!empty($_POST['escola_destino']))) {
        if (!empty($_POST['as'])) {
            foreach ($_POST['as'] as $v) {

                $cad['fk_id_pessoa'] = $v;
                $cad['fk_id_turma'] = $id_turma;
                $cad['escola_origem'] = tool::id_inst();
                $cad['escola_destino'] = $id_esc_d;
                $cad['ciclo_futuro'] = anofuturo($id_turma);
                $cad['status'] = 1;

                $model->db->ireplace('ge_encaminhamento', $cad, 1);
                log::logSet("Encaminhou aluno " . $v);
            }
        }
    } else {
        tool::alert("Favor Selecionar Destino");
    }
}
if (!empty($_POST['selecao2'])) {
    if (!empty($_POST['as2'])) {
        foreach ($_POST['as2'] as $v) {
            $model->db->delete('ge_encaminhamento', 'id_encam', $v, 1);
            log::logSet("Exclui aluno seleção" . $v);
        }
    }
}
?>
<div class="body">
    <div class="row">
        <div class="col-8" style="font-size: 20px; padding: 10px; text-align: center">
            <b>Encaminhamento</b>
        </div>
        <div class="col-4" style="font-size: 20px; padding: 10px; text-align: center">
            <a href="#" onclick="$('#myModal').modal('show');">
                <img src="<?= HOME_URI ?>/includes/images/video-help.png">
            </a>
        </div>

    </div>
    <br />

    <div style="padding-left: 20px" class="panel panel-default">
        <form method="POST">
            <div class="row">
                <div class="col-md-4">
                    <?= formErp::select('id_turma', ng_turmas::optionNome(), 'Turma', $id_turma) ?>
                </div>
                <div class="col-md-6">
                    <?php
                    foreach ($esc_d as $ed) {
                        $edd[$ed['id_inst']] = $ed['n_inst'];
                    }
                    $edd['17'] = 'EMEIEF ALCINO FRANCISCO DE SOUZA - PROF.';
                    $edd['69'] = 'EMEIEF MARIA MEDUNECKAS - PROFª';
                    echo formErp::select('escola_destino', $edd, 'Escola de Destino', $escola_destino);
                    ?>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary">
                        Continuar
                    </button>
                </div>
            </div>    
        </form>
        <br />
        <?php
        if (!empty($escola_destino)) {
            // $esc_s = sql::get(['pessoa', 'ge_encaminhamento'], '*', ['escola_origem' => tool::id_inst(), 'escola_destino' => $id_esc_d, 'status' => 1, '>' => 'n_pessoa']);
            $esc_s = wpegaalunoencaminhamento($id_esc_d);
            $tu = pegaaluno($id_turma);
            ?>
            <br />
            <div class="row">
                <div class="col-md-5" style="padding-left: 40px">
                    <div class="border" style="min-height: 50vh; width: 98%">
                        <?php
                        if ($id_turma) {
                            ?>
                            <div class="fieldTop">
                                Escola de Origem
                            </div>
                            <form method="POST" id="sa">
                                <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" /> 
                                <input type="hidden" name="escola_destino" value="<?php echo $id_esc_d ?>" /> 
                                <input type="hidden" name="selecao" value="1" />
                                <table class="table table-striped table-hover" style="font-weight: bold">
                                    <tr>
                                        <td>
                                            RSE
                                        </td>
                                        <td>
                                            Nome Aluno
                                        </td>
                                        <td>
                                            Bairro
                                        </td>
                                        <td>
                                            Todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />
                                        </td>
                                    </tr>
                                    <?php
                                    if (!empty($tu)) {
                                        foreach ($tu as $k => $v) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $v['id_pessoa'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $v['n_pessoa'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $v['bairro'] ?>
                                                </td>
                                                <td>
                                                    <input class="checkatz" type="checkbox" name="as[]" value="<?php echo $v['id_pessoa'] ?>" />
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </table>             
                            </form>
                            <?php
                        } else {
                            ?>
                            <div class="fieldTop">
                                Excluir
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-1">
                    <br /><br />
                    <?php
                    if ($id_turma) {
                        ?>
                        <button class="btn btn-link" name= "selecao" value="Selecao" onclick="document.getElementById('sa').submit()" type="submit">                                   
                            <img style="width: 35px" src="<?= HOME_URI ?>/includes/images/ir.png" alt="alt"/>
                        </button>
                        <?php
                    }
                    ?>
                    <br /><br /><br /><br />    
                    <?php
                    if (!empty($esc_s)) {
                        ?>
                        <button class="btn btn-link" name= "selecao2" value="Selecao2" onclick="document.getElementById('al').submit()" type="submit">   
                            <img style="width: 35px" src="<?= HOME_URI ?>/includes/images/voltar.png" alt="alt"/>       
                        </button>
                        <br /><br /><br /><br />
                        <button style="width: 100%" class="btn btn-primary" name= "lista" value="Lista" onclick="document.getElementById('lista').submit()" type="submit">   
                            Lista       
                        </button>
                        <br /><br /><br /><br />
                        <button style="width: 100%" class="btn btn-primary" name= "imprimir" value="Imprimir" onclick="document.getElementById('imprimir').submit()" type="submit">   
                            Imprimir    
                        </button>     
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-5" style="padding: 5px;">
                    <div class="border" style="min-height: 50vh; width: 98%">
                        <div class="fieldTop">
                            Escola de Destino
                        </div>
                        <form method="POST" id="al"> 
                            <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" /> 
                            <input type="hidden" name="escola_destino" value="<?php echo $id_esc_d ?>" /> 
                            <input type="hidden" name="selecao2" value="1" />
                            <table class="table table-striped table-hover" style="font-weight: bold">
                                <tr>
                                    <td>
                                        ID
                                    </td>
                                    <td>
                                        RSE
                                    </td>
                                    <td>
                                        Nome Aluno
                                    </td>
                                    <td>
                                        Todos <input type="checkbox" name="chkAll2" onClick="checkAll2(this)" />
                                    </td>
                                </tr>
                                <?php
                                foreach ($esc_s as $w) {
                                    ?>
                                    <tr>
                                        <td style="text-align: left">
                                            <?php echo $w['id_encam'] ?>
                                        </td>
                                        <td style="text-align: left">
                                            <?php echo $w['id_pessoa'] ?>
                                        </td>
                                        <td style="text-align: left">
                                            <?php echo $w['n_pessoa'] ?>
                                        </td>
                                        <td>
                                            <input class="checkatz2" type="checkbox" name="as2[]" value="<?php echo $w['id_encam'] ?>" />
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form target="_blank" action="<?php echo HOME_URI; ?>/sed/pdf/listapdf.php" id="lista" method="POST">
            <input type="hidden" name="listaaluno" value="<?php echo $id_esc_d ?>" />
        </form>

        <form target="_blank" action="<?php echo HOME_URI; ?>/sed/pdf/encaminhamentopdf.php" id="imprimir" method="POST">
            <input type="hidden" name="imprimir" value="<?php echo $id_esc_d ?>" /> 
            <input type="hidden" name="idturma" value="<?php echo $id_turma ?>" /> 
        </form>
    </div>
    <?php
}
tool::modalInicio('width: 95%', 1);
?>
<video style="width: 100%; height: 80vh" controls>
    <source src="<?= HOME_URI ?>/pub/sistema/encaminhamentoMatricula.mp4" type="video/mp4">
</video>
<?php
tool::modalFim()
?>

<script>

    function checkAll(o) {
        var boxes = document.getElementsByClassName("checkatz");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }
    function checkAll2(o) {
        var boxes = document.getElementsByClassName("checkatz2");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll2")
                    obj.checked = o.checked;
            }
        }
    }
</script>
<?php

function pegaescolaterceiropre() {
    $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i"
            . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
            . " WHERE pl.at_pl = 1  ORDER BY i.n_inst";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    return $array;
}

function wpegaalunoencaminhamento($iddestino) {

    $sql = "SELECT p.id_pessoa, p.n_pessoa, en.id_encam, en.status, ta.fk_id_turma FROM pessoa p"
            . " JOIN ge_encaminhamento en ON en.fk_id_pessoa = p.id_pessoa"
            . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = en.fk_id_turma AND ta.fk_id_pessoa = en.fk_id_pessoa"
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
            . " WHERE en.escola_origem = '" . tool::id_inst() . "' AND en.escola_destino = '" . $iddestino . "' AND en.status = 1"
            . " AND pl.at_pl = 1";

    $query = pdoSis::getInstance()->query($sql);
    $a = $query->fetchAll(PDO::FETCH_ASSOC);

    return $a;
}

function pegaaluno($idturma) {

    $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, e.bairro FROM pessoa p"
            . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa and ta.fk_id_tas = 0"
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma and t.id_turma = '" . $idturma . "' "
            . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
            . " ORDER BY p.n_pessoa";

    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

// status da tabela ge_encaminhamento 0 = encaminhar - 1 = encaminhado - 2 Matriculado
    $sql = "SELECT fk_id_pessoa FROM ge_encaminhamento WHERE fk_id_turma = '" . $idturma . "' AND status = '" . '1' . "'";
    $query = pdoSis::getInstance()->query($sql);
    $array2 = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($array2 as $v) {
        $ae[] = $v['fk_id_pessoa'];
    }

    foreach ($array as $key => $w) {
        if (!empty($ae)) {
            if (in_array($w['id_pessoa'], $ae)) {
                unset($array[$key]);
            }
        }
    }

    return $array;
}

function anofuturo($idturma) {

    $sql = "SELECT c.n_ciclo FROM ge_turmas t"
            . " JOIN ge_ciclos c ON c.id_ciclo = t.fk_id_ciclo"
            . " WHERE t.id_turma = '" . $idturma . "'";

    $query = pdoSis::getInstance()->query($sql);
    $ci = $query->fetch(PDO::FETCH_ASSOC);

    $ano = [
        'Berçário' => '1ª Fase Maternal do Ensino Infantil',
        'Maternal Fase 1' => '2ª Fase Maternal do Ensino Infantil',
        'Maternal Fase 2' => '3ª Fase Maternal do Ensino Infantil',
        'Maternal Fase 3' => '1ª Fase Pré do Ensino Infantil',
        'Pré Fase 1' => '2ª Fase Pré do Ensino Infantil',
        'Pré Fase 2' => '1º Ano do Ensino Fundamental',
        '1º Ano' => '2º Ano do Ensino Fundamental',
        '2º Ano' => '3º Ano do Ensino Fundamental',
        '3º Ano' => '4º Ano do Ensino Fundamental',
        '4º Ano' => '5º Ano do Ensino Fundamental',
        '5º Ano' => '6º Ano do Ensino Fundamental',
        '6º Ano' => '7º Ano do Ensino Fundamental',
        '7º Ano' => '8º Ano do Ensino Fundamental',
        '8º Ano' => '9º Ano do Ensino Fundamental',
        '9º Ano' => '1º Ano do Ensino Médio'
    ];

    if (!empty($ci['n_ciclo'])) {
        return $ano[$ci['n_ciclo']];
    } else {
        return $ci['n_ciclo'];
    }
}
