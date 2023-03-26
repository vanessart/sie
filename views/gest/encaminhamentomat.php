<br />
<?php
$id_turma = @$_POST['id_turma'];

$turmaOptions = turma::option(tool::id_inst(), 27);
?>
<div style="font-size: 20px; padding: 10px; color: red; text-align: center">
    <b>MATRICULAR ALUNO</b>
</div>
<div style="padding-left: 20px" class="panel panel-default">
    <div class="row">
        <div style="padding-left: 40px; padding-top: 10px; padding-bottom: 10px" class="col-md-4">         
            <?php formulario::select('id_turma', $turmaOptions, 'Selecione Código da Classe:', @$id_turma, 1) ?>
        </div>
    </div>    

    <?php
    if (!empty($id_turma)) {

        $tu = $model->matriculaencam($id_turma, 1);
        $tus = $model->classeselecionada($id_turma);
        ?>
        <div class="row">
            <div class="col-md-5" style="padding-left: 40px">
                <div class="rowField" style="min-height: 50vh; width: 98%">
                    <form method="POST" id="sa">
                        <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" /> 
                        <input type="hidden" name="selecao3" value="1" />

                        <table class="table table-striped table-hover" style="font-weight: bold">
                            <tr>
                                <td>
                                    RSE
                                </td>
                                <td>
                                    RA
                                </td>
                                <td>
                                    Nome Aluno
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
                                        <?php echo $v['ra'] ?>
                                    </td>
                                    <td>
                                        <?php echo addslashes($v['n_pessoa']) ?>
                                    </td>
                                    <td>
                                        <input class="checkatz" type="checkbox" name="as[]" value="<?php echo $v['id_encam'] ?>" />
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>    
                        <input type="hidden" name="escolaorigem" value="<?php echo $v['escola_origem'] ?>" /> 
                    </form>
                </div>
            </div>
            <div class="col-md-1">
                <br /><br />
                <button name= "selecao3" value="Selecao3" onclick="document.getElementById('sa').submit()" type="submit">                                   
                    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span> Matricular
                </button>
                <br /><br /><br /><br />         
                <button name= "selecao4" value="Selecao4" onclick="document.getElementById('al').submit()" type="submit">   
                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Excluir Matrícula      
                </button>                     
                <br /><br /><br /><br />
                <button name= "lista" value="Lista" onclick="document.getElementById('lista').submit()" type="submit">   
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <br /> Lista Piloto      
                </button>
            </div>
            <div class="col-md-5" style="padding: 5px;">
                <div class="rowField" style="min-height: 50vh; width: 98%">
                    <form method="POST" id="al"> 
                        <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" /> 
                        <input type="hidden" name="selecao4" value="1" />
                        <table class="table table-striped table-hover" style="font-weight: bold">
                            <tr>
                                <td>
                                    ID
                                </td>
                                <td>
                                    RSE
                                </td>
                                <td>
                                    RA
                                </td>
                                <td>
                                    Ch
                                </td>
                                <td>
                                    Nome Aluno
                                </td>
                                <td>
                                    Todos <input type="checkbox" name="chkAll2" onClick="checkAll2(this)" />
                                </td>
                            </tr>
                            <?php
                            foreach ($tus as $w) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $w['id_turma_aluno'] ?>
                                    </td>
                                    <td style="text-align: left">
                                        <?php echo $w['id_pessoa'] ?>
                                    </td>
                                    <td style="text-align: left">
                                        <?php echo $w['ra'] ?>
                                    </td>
                                    <td style="text-align: left">
                                        <?php echo $w['chamada'] ?>
                                    </td>
                                    <td style="text-align: left">
                                        <?php echo addslashes($w['n_pessoa']) ?>
                                    </td>
                                    <td>
                                        <input class="checkatz2" type="checkbox" name="as2[]" value="<?php echo $w['id_turma_aluno'] . '|' . $w['id_pessoa'] ?>" />
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

    <form target="_blank" action="<?php echo HOME_URI; ?>/gestao/lista_piloto" id="lista" method="POST">
        <input type="hidden" name="sel[]" value="<?php echo $id_turma ?>" />
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
