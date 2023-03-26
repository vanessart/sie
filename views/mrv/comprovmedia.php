<br /><br />

<?php
$id_turma = @$_POST['id_turma'];
?>

<div class="row">
    <!--
    <div style="font-size: 15px; text-align: right" class="col-md-3">
        <b>Situação do Aluno</b>
    </div>
    -->
    <div class="col-md-2">

    </div>
    <div style="font-size: 15px" class="col-md-6">
        <b>Selecione a Classe e o(s) Aluno(s) e Clique no botão Calcular para atualizar Dados</b>
    </div>
    <div class="col-md-3">
        <?php
        //Para acessar ano anterior
        // Inicio
        //  $turmas = turma::option(tool::id_inst(), NULL, 'fk_id_inst', '9');
        // ano anterior o nome ta errado
        $turmas = $model->anoanterior();
        //fim
        formulario::select('id_turma', $turmas, 'Selecione a Classe', $id_turma, 1);
        ?>
    </div>  
    <div class="col-md-1">

    </div>
</div>

<?php
$alunoSel = sql::get('mrv_beneficiado', 'id_pessoa', "WHERE status_ben != '" . 'Indeferida' . "' ORDER BY num_chamada_ben");

foreach ($alunoSel as $vv) {
    $alcad[] = $vv['id_pessoa'];
}

if (!empty($id_turma)) {
    @$classe = sql::get('mrv_beneficiado', '*', "WHERE fk_id_turma = '" . $id_turma . "' AND categoria IN('1','2','3')" . " ORDER BY num_chamada_ben");
    ?>
    <div class="row">
        <div class="col-md-8" style="padding-left: 150px; padding-top: 30px">
            <div class="rowField" style="min-height: 50vh; width: 98%">
                <?php
                foreach ($classe as $key => $vv) {
                    $vv[$key]['tur'] = formulario::checkboxSimples('sel[]', $vv['id_pessoa'], NULL, NULL, 'id="' . $vv['id_pessoa'] . '"');
                }
                ?>
                <form method="POST" id="sa">                  
                    <input type="hidden" name="selecao" value="1" />
                    <table class="table table-striped table-hover" style="font-weight: bold">
                        <tr>
                            <td>
                                Chamada
                            </td>
                            <td>
                                RSE
                            </td>
                            <td>
                                Nome Aluno
                            </td>
                            <td>
                                Status
                            </td>
                            <td>
                                Média
                            </td>
                            <td>
                                Todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />
                            </td>
                        </tr>
                        <?php
                        foreach ($classe as $k => $v) {
                            if ($v['media_final_ben'] < 6.5) {
                                $cor = '#FF0000';
                            } else {
                                $cor = '#000000';
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php echo $v['num_chamada_ben'] ?>
                                </td>
                                <td>
                                    <?php echo $v['id_pessoa'] ?>
                                </td>
                                <td>
                                    <?php echo $v['n_pessoa'] ?>
                                </td>
                                <td style="color: <?php echo $cor ?>">
                                    <?php echo $v['status_ben'] ?>
                                </td>
                                <td style="color: <?php echo $cor ?>">
                                    <?php echo $v['media_final_ben'] ?>
                                </td>
                                <td>
                                    <input class="checkatz" type="checkbox" name="as[]" value="<?php echo $v['id_pessoa'] ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>  
                    <input type="hidden" name= "id_turma" value="<?php echo $id_turma ?>" />
                </form>
            </div>
        </div>
        <div style="padding: 30px" class="col-md-4">
            <div class="row">
                <div>
                    <?php
                    if ($model->verificastatus() != 'FinalizadoFinal') {
                        ?>
                        <button name= "selecao" value="Selecao" onclick="document.getElementById('sa').submit()" type="submit" style="width: 65%" class="art-button">                                   
                            Calcular
                        </button>  
                        <?php
                    }
                    ?>
                </div>
                <br />
                <div>
                    <input style="width: 65%" class="art-button" type="submit" onclick=" $('#myModal').modal('show');" value="Resumo Status" />
                </div>
                <br />
                <div>
                    <button name = "vis" type="button" onclick="visualiza()" style="width: 65%" class="art-button">
                        Visualizar Média
                    </button> 
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<form id = "resumo" method="POST">  
    <?php
    $modalAct = empty($_REQUEST['l']) ? 1 : null;
    tool::modalInicio('width: 100%', $modalAct);
    $resumo = $model->resumoinscricao();

    foreach ($resumo as $key => $v) {
        ?>
        <div>
            <br />
            <div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 5px">
                <b>Resumo</b>
            </div>
            <table class="table table-striped table-hover" style="font-weight: bold">
                <thead>
                    <tr>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Núm. Vagas
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Frequente
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Ag. Def.
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Deferida
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Indeferida
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            NI
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Não Munícipe
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Indeferida(Doc)
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            Pendente
                        </td>    
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo '-' //$v['Num. Vagas'] ?>
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['Frequente'] ?>
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['Ag. Def.'] ?>
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['Deferida'] ?>
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['Indeferida'] ?>
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['NI'] ?>
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['Não Munícipe'] ?>
                        </td>
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['Indeferida(Doc)'] ?>
                        </td>
                        <!--
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                        <?php echo $v['Frequente'] - $v['Ag. Def.'] - $v['Deferida'] - $v['Indeferida'] - $v['NI'] - $v['Indeferida(Doc)']  ?>
                        </td>
                        -->
                        <td style="font-size: 15px; text-align: center; font-weight: bold; border-style: border; padding: 5px">
                            <?php echo $v['Frequente'] - $v['Ag. Def.'] - $v['Deferida'] - $v['Indeferida'] - $v['NI'] - $v['Não Munícipe']- $v['Indeferida(Doc)'] ?>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
    tool::modalFim();
    ?>
</form>
<!--
<form id = "fecha" action="<?php echo HOME_URI ?>/mrv/comprovmedia" method="POST">  
    <input type="hidden" name= "id_turma" value="<?php echo $id_turma ?>" />
</form>
-->
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
    function visualiza() {
        document.getElementById("sa").action = "<?php echo HOME_URI ?>/mrv/comprovmediapdf";
        document.getElementById("sa").target = "_blank";

        $('#sa').submit();
    }

</script>
