<br /><br />
<?php
@$descEve = $_POST['evento'];
?>
<div class="row">
    <div class="col-md-12">
        <form name="id_turmaForm" method="POST">
            <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
            <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
            <input type="hidden" name="evento" value="<?php echo $_POST['evento'] ?>" />
            <?php formulario::select('id_turma', turmas::option(), 'Selecione uma Classe:') ?>
        </form>
    </div>
</div>

<?php
@$descEve = $_POST['evento'];

$alunoSel = "SELECT ea.id_ea, p.id_pessoa, p.n_pessoa FROM ge_evento_aluno ea"
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ea.fk_id_pessoa"
        . " JOIN pessoa p on p.id_pessoa = ea.fk_id_pessoa"
        . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl"
        . " WHERE ea.fk_id_evento = '" . $id_eve . "' AND ta.situacao = 'Frequente' AND pl.at_pl =  1 ORDER BY p.n_pessoa";

$query = $model->db->query($alunoSel);
$dados = $query->fetchAll();

foreach ($dados as $vv) {
    $alcad[] = $vv['id_pessoa'];
}

if (!empty($id_turma)) {
    @$classe = turmas::classe($id_turma);
    ?>
    <br /><br />
    <div class="row">
        <div class="col-md-5" style="padding: 5px;">
            <div class="rowField" style="min-height: 50vh; width: 98%">

                <?php
                foreach ($classe as $key => $vv) {
                    if ($vv['situacao'] != "Frequente") {
                        unset($classe[$key]);
                    }
                    if (!empty($alcad)) {
                        if (in_array($vv['id_pessoa'], $alcad)) {
                            unset($classe[$key]);
                        }
                    }
                    $vv[$key]['tur'] = formulario::checkboxSimples('sel[]', $vv['id_pessoa'], NULL, NULL, 'id="' . $vv['id_pessoa'] . '"');
                }
                ?>

                <form method="POST" id="sa">

                    <input type="hidden" name="fk_id_evento" value="<?php echo $id_eve ?>" />
                    <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                    <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
                    <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />                
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
                                Todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />
                            </td>
                        </tr>
                        <?php
                        foreach ($classe as $k => $v) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $v['id_pessoa'] ?>
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
                    <input type="hidden" name="chave" value="<?php echo $k ?>" />
                    <input type="hidden" name ="evento" value="<?php echo @$descEve ?>" />
                </form>
            </div>
        </div>
        <div class="col-md-1">
            <br /><br /><br /><br />
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

                    <input type="hidden" name="fk_id_evento" value="<?php echo $id_eve ?>" />
                    <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                    <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>"/> 
                    <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />  
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
                        foreach ($dados as $vv) {
                            $vv[$key]['tur2'] = formulario::checkboxSimples('sel2[]', $vv['id_ea'], NULL, NULL, 'id="' . $vv['id_ea'] . '"');
                        }
                        foreach ($dados as $w) {
                            ?>
                            <tr>
                                <td style="text-align: left">
                                    <?php echo $w['id_ea'] ?>
                                </td>
                                <td style="text-align: left">
                                    <?php echo $w['id_pessoa'] ?>
                                </td>
                                <td style="text-align: left">
                                    <?php echo $w['n_pessoa'] ?>
                                </td>
                                <td>
                                    <input class="checkatz2" type="checkbox" name="as2[]" value="<?php echo $w['id_ea'] ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <input type="hidden" name="chave" value="<?php echo $k ?>" />
                    <input type="hidden" name ="evento" value="<?php echo @$descEve ?>" />

                </form>
            </div>
        </div>
    </div>
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
