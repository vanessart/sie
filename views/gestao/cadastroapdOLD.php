<br />
<?php
$id_turma = @$_POST['id_turma'];
$selecao = sql::get(['instancia', 'ge_turmas'], 'codigo', ['id_turma' => $id_turma], 'fetch');

?>

<div style="font-size: 20px; padding: 10px; color: red; text-align: center">
    <b>CADASTRO DE ALUNOS APD</b>
</div>
<div style="padding-left: 20px" class="panel panel-default">
    <div class="row">
        <form method="POST">
            <div class="col-md-6">
                <?php formulario::select('id_turma', turmas::option(), 'Selecione uma Classe:') ?>
            </div>          
            <div class="col-md-6">
                <input type="submit" class="art-button" value="Continuar" />
            </div>
        </form>
    </div>    
    <br />
    <?php
    if (!empty($id_turma)) {
        $lista = $model->cadastraalunoapd($id_turma);
        ?>
        <div class="row">
            <div class="col-md-5" style="padding-left: 40px">
                <div class="rowField" style="min-height: 50vh; width: 98%">
                    <form method="POST" id="sa">
                        <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" 
                               <input type="hidden" name="selecao" value="1" />
                        <span style="color: red; font-weight:bolder; padding-left: 10px; font-size: 14px"> Classe Selecionada => <?php echo $selecao['codigo'] ?></span>
                        <table class="table table-striped table-hover" style="font-weight: bold">
                            <tr>
                                <td>
                                    RSE
                                </td>
                                <td>
                                    Data Nasc.
                                </td>
                                <td>
                                    Nome Aluno
                                </td>
                                <td>
                                    Todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />
                                </td>
                            </tr>
                            <?php
                            foreach ($lista as $v) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $v['id_pessoa'] ?>
                                    </td>
                                    <td>
                                        <?php echo data::converteBr($v['dt_nasc']) ?>
                                    </td>
                                    <td>
                                        <?php echo $v['n_pessoa'] ?>
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
            </div>
            <div class="col-md-5" style="padding: 5px;">
                <div class="rowField" style="min-height: 50vh; width: 98%">
                    <form method="POST" id="al"> 
                        <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />
                        <input type="hidden" name="selecao2" value="1" />
                        <span style="color: red; font-weight:bolder; padding-left: 10px; font-size: 14px"> Escola Selecionada => <?php echo $selecaod['n_inst'] ?></span>
                        <table class="table table-striped table-hover" style="font-weight: bold">
                            <tr>
                                <td>
                                    RSE
                                </td>
                                <td>
                                    Data Nasc.
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
