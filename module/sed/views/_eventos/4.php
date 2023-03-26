<br /><br />

<?php
@$id_eve = $_POST['id_eve'];
$grupo = sql::get('ge_grupo_evento', '*', ['fk_id_inst' => tool::id_inst(), 'fk_id_evento' => @$id_eve]);
?>

<div class="row">
    <div class="col-md-4">
        <form name="id_turmaForm" method="POST">
            <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
            <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
            <input type="hidden" name="evento" value="<?php echo @$_POST['evento'] ?>" />
<?php formulario::select('id_turma', turmas::option(), 'Selecione Classe:') ?>
        </form>
    </div>
    <div class="col-md-8">
        <form name="id_grupoForm" method="POST">
            <div>
<?php
foreach ($grupo as $a) {
    $aa[$a['id_grupo_e']] = $a['descricao_grupo'];
}
formulario::select('id_grupo', $aa, 'Descrição Grupo:')
?>
            </div>
            <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
            <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
            <input type="hidden" name="evento" value="<?php echo @$_POST['evento'] ?>" />
            <input type="hidden" name ="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />

        </form>
    </div>
</div>
<br /><br />
<div class="row">
<?php
$sql = "select fk_id_grupo_e from ge_evento_aluno "
        . "where fk_id_evento = $id_eve 
            and fk_id_grupo_e is not NULL ";
$query = $model->db->query($sql);
$onibus = $query->fetch()['fk_id_grupo_e'];
if (!empty($onibus)) {
    ?>
        <div class="col-md-6 text-center">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapersonalizadapdf" method="POST">
                <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
                <input type="hidden" name="evento" value="<?php echo @$_POST['evento'] ?>" />
                <input type="hidden" name ="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
                <input type="hidden" name ="id_grupo" value="<?php echo @$_POST['id_grupo'] ?>" />
                <button class="btn btn-primary"  style="width: 300px">
                    <span class="glyphicon glyphicon-bed" aria-hidden="true"></span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Lista Personalizada
                </button>
            </form>
        </div>  
        <input type="hidden" name="evento" value="<?php echo @$_POST['evento'] ?>" />

        <div class="col-md-6 text-center">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_perassinatura" method="POST">
                <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
                <input type="hidden" name="evento" value="<?php echo @$_POST['evento'] ?>" />
                <input type="hidden" name ="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
                <input type="hidden" name ="id_grupo" value="<?php echo @$_POST['id_grupo'] ?>" />
                <button class="btn btn-primary"  style="width: 300px">
                    <span class="glyphicon glyphicon-bed" aria-hidden="true"></span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Lista Personalizada c/Assinatura
                </button>
            </form>
        </div>  
    <?php
} else {
    ?>
        <div class="col-md-6 text-center">
            <button type="button" class="btn btn-default"  style="width: 300px">
                <span class="glyphicon glyphicon-bed" aria-hidden="true"></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Lista Personalizada
            </button>
        </div>
        <div class="col-md-6 text-center">
            <button type="button" class="btn btn-default"  style="width: 300px">
                <span class="glyphicon glyphicon-bed" aria-hidden="true"></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Lista Personalizada c/Assinatura
            </button> 
        </div>
    <?php
}
?>
</div> 
    <?php
    if (!empty($id_turma)) {

        $selec = "SELECT p.id_pessoa, p.n_pessoa, ea.fk_id_evento, ea.id_ea FROM ge_evento_aluno ea"
                . " JOIN pessoa p on p.id_pessoa = ea.fk_id_pessoa"
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ea.fk_id_pessoa"
                . " WHERE ta.situacao = 'Frequente' AND ea.fk_id_evento = '" . $id_eve . "'"
                . " AND fk_id_turma = '" . $id_turma . "' AND ea.fk_id_grupo_e IS NULL order by p.n_pessoa";

        $query = pdoSis::getInstance()->query($selec);
        $classe = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($_POST['id_grupo'])) {
            $alunoSel = "SELECT ea.id_ea, p.id_pessoa, p.n_pessoa FROM ge_evento_aluno ea"
                    . " JOIN pessoa p on p.id_pessoa = ea.fk_id_pessoa"
                    . " WHERE ea.fk_id_grupo_e = '" . $_POST['id_grupo'] . "'"
                    . " ORDER BY p.n_pessoa";

            $query = $model->db->query($alunoSel);
            $dados = $query->fetchAll();
            ?>
        <br /><br />
        <div class="row">
            <div class="col-md-5" style="padding: 5px;">
                <div class="rowField" style="min-height: 50vh; width: 98%">
        <?php
        foreach ($classe as $key => $vv) {
            $vv[$key]['tur'] = formulario::checkboxSimples('sel[]', $vv['id_pessoa'], NULL, NULL, 'id="' . $vv['id_pessoa'] . '"');
        }
        ?>
                    <form method="POST" id="sa">

                        <input type="hidden" name="fk_id_evento" value="<?php echo $id_eve ?>" />
                        <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                        <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
                        <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />                
                        <input type="hidden" name="selecao_4" value="1" />
                        <input type="hidden" name="evento" value="<?php echo @$_POST['evento'] ?>" />
                        <input type="hidden" name="id_grupo" value="<?php echo @$_POST['id_grupo'] ?>" />  

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
                                        <input class="checkboxa" type="checkbox" name="as[]" value="<?php echo $v['id_ea'] ?>" />
                                    </td>
                                </tr>
            <?php
        }
        ?>
                        </table>
                        <input type="hidden" name="chave" value="<?php echo $k ?>" />
                    </form>
                </div>
            </div>
            <div class="col-md-1">
                <br /><br /><br /><br />
                <button name= "selecao"  onclick="document.getElementById('sa').submit()" type="submit">                                   
                    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
                </button>
                <br /><br /><br /><br />
                <button name= "selecao2"  onclick="document.getElementById('al').submit()" type="submit">   
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
                        <input type="hidden" name="selecao2_4" value="1" />
                        <input type="hidden" name="id_grupo" value="<?php echo @$_POST['id_grupo'] ?>" />
                        <input type="hidden" name="evento" value="<?php echo @$_POST['evento'] ?>" />

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
                                        <input class="checkboxb" type="checkbox" name="as2[]" value="<?php echo $w['id_ea'] ?>" />
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
        <?php
    }
}
?>
<script>

    function checkAll(o) {
        var boxes = document.getElementsByClassName("checkboxa");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }

    function checkAll2(o) {
        var boxes = document.getElementsByClassName("checkboxb");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll2")
                    obj.checked = o.checked;
            }
        }
    }

</script>
