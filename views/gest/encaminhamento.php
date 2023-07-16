<br />
<?php
$id_turma = @$_POST['id_turma'];
$id_esc_d = @$_POST['escola_destino'];

//$esc_d = sql::get(['instancia', 'ge_escolas'], 'id_inst, n_inst', ['>' => 'n_inst']);
$esc_d = $model->pegaescolaterceiropre();
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
                $cad['ciclo_futuro'] = $model->anofuturo($id_turma);
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
            $model->db->delete('ge_encaminhamento', 'id_encam', $v);
            log::logSet("Exclui aluno seleção" . $v);
        }
    }
}
?>

<div class="row">
    <div class="col-8" style="font-size: 20px; padding: 10px; color: red; text-align: center">
        <b>ENCAMINHAMENTO</b>
    </div>
    <div class="col-4" style="font-size: 20px; padding: 10px; color: red; text-align: center">
            <a href="#" onclick="$('#myModal').modal('show');">
                <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/video-help.png">
            </a>
    </div>

</div>
<br />

<div style="padding-left: 20px" class="panel panel-default">
    <div class="row">
        <form method="POST">
            <div class="col-md-4">
                <?php formulario::select('id_turma', turmas::option(), 'Selecione uma Classe:') ?>
            </div>
            <div class="col-md-6">
                <?php
                foreach ($esc_d as $ed) {
                    $edd[$ed['id_inst']] = $ed['n_inst'];
                }
                //Não tem pre no ano de 2019
                // $edd['120'] = 'EMMEI PROFª. MARIA JOSÉ DE BARROS';
                //Não tem pre no ano de 2022
                $edd['17'] = 'EMEIEF ALCINO FRANCISCO DE SOUZA - PROF.';
                $edd['69'] = 'EMEIEF MARIA MEDUNECKAS - PROFª';
                
                formulario::select('escola_destino', $edd, 'Selecione Escola de Destino:');
                ?>
            </div>
            <div class="col-md-2">
                <input type="submit" class="art-button" value="Continuar" />
            </div>
        </form>
    </div>    
    <br />
    <?php
    if (!empty($id_turma)) {
        // $esc_s = sql::get(['pessoa', 'ge_encaminhamento'], '*', ['escola_origem' => tool::id_inst(), 'escola_destino' => $id_esc_d, 'status' => 1, '>' => 'n_pessoa']);
        $esc_s = $model->wpegaalunoencaminhamento($id_esc_d);
        $tu = $model->pegaaluno($id_turma);
        ?>
        <div class="row">
            <div class="col-md-5" style="padding-left: 40px">
                <div class="rowField" style="min-height: 50vh; width: 98%">
                    <form method="POST" id="sa">
                        <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" /> 
                        <input type="hidden" name="escola_destino" value="<?php echo $id_esc_d ?>" /> 
                        <input type="hidden" name="selecao" value="1" />
                        <span style="color: red; font-weight:bolder; padding-left: 10px; font-size: 14px"> Classe Selecionada => <?php echo $selecao['codigo'] ?></span>
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
                            ?>
                        </table>             
                    </form>
                </div>
            </div>
            <div class="col-md-1">
                <br /><br />
                <button name= "selecao" value="Selecao" onclick="document.getElementById('sa').submit()" type="submit">                                   
                    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
                </button>
                <br /><br /><br /><br />         
                <button name= "selecao2" value="Selecao2" onclick="document.getElementById('al').submit()" type="submit">   
                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>       
                </button>                     
                <br /><br /><br /><br />
                <button name= "lista" value="Lista" onclick="document.getElementById('lista').submit()" type="submit">   
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <br /> Lista       
                </button>
                <br /><br /><br /><br />
                <button name= "imprimir" value="Imprimir" onclick="document.getElementById('imprimir').submit()" type="submit">   
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir    
                </button>
            </div>
            <div class="col-md-5" style="padding: 5px;">
                <div class="rowField" style="min-height: 50vh; width: 98%">
                    <form method="POST" id="al"> 
                        <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" /> 
                        <input type="hidden" name="escola_destino" value="<?php echo $id_esc_d ?>" /> 
                        <input type="hidden" name="selecao2" value="1" />
                        <span style="color: red; font-weight:bolder; padding-left: 10px; font-size: 14px"> Escola Selecionada => <?php echo $selecaod['n_inst'] ?></span>
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

    <form target="_blank" action="<?php echo HOME_URI; ?>/gest/listapdf" id="lista" method="POST">
        <input type="hidden" name="listaaluno" value="<?php echo $id_esc_d ?>" />
    </form>

    <form target="_blank" action="<?php echo HOME_URI; ?>/gest/encaminhamentopdf" id="imprimir" method="POST">
        <input type="hidden" name="imprimir" value="<?php echo $id_esc_d ?>" /> 
        <input type="hidden" name="idturma" value="<?php echo $id_turma ?>" /> 
    </form>
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
