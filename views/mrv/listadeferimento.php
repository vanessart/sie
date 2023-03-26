<?php
$ano = date('Y');
//$ano = '2020';
$esc = sql::get('ge_turmas', 'codigo, id_turma, n_turma', 'WHERE fk_id_inst = ' . tool::id_inst() . " AND fk_id_ciclo = '" . 9 . "' AND periodo_letivo like '%" . $ano . "%' ORDER BY codigo");

?>

<div class="fieldBody">
    <div class="row">
        <div class ="col-md-8">
            Selecionar Classe
            <div style="overflow: auto; height: 350px">  
                <?php
                foreach ($esc as $k => $v) {
                    $esc[$k]['tur'] = formulario::checkboxSimples('sel[]', $v['id_turma'], NULL, NULL, 'id="' . $v['id_turma'] . '"');
                }
                $form['array'] = $esc;
                $form['fields'] = [
                    'Codigo Classe' => 'codigo',
                    'Descrição' => 'n_turma',
                    'todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />' => 'tur'
                ];
                tool::relatSimples($form);
                ?>            
            </div>
        </div> 
        <div class ="col-md-4">
            <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/mrv/listadeferimentopdf" id="listadef" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="ld<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" /> 
                    <br />
                    <button name="deferimento" value="Lista Deferimento" onclick="ld('listadef')" style="width: 80%" type="button" class="art-button" id="btnld">
                        Visualizar - Lista Status
                    </button>
                </form> 
            </div>
            <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/mrv/fichaembrancopdf" id="fichab" method="POST">
                    <br />
                    <button name="incricaob" value="Inscrição" style="width: 80%" type="submit" class="art-button" id="btnfb">
                        Ficha Inscrição em Branco
                    </button>
                </form> 
            </div>
            <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/mrv/fichainscricaopdf" id="listafi" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="fi<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" /> 
                    <br />
                    <button name="ficha" value="Ficha Inscrição" onclick="fi('listafi')"  style="width: 80%" type="button" class="art-button" id="btnfi">
                        Ficha de Inscrição
                    </button>
                </form> 
            </div>
             <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/mrv/listacontrolepdf" id="listaco" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="co<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" /> 
                    <br />
                    <button name="controle" value="Controle de Entrega" onclick="co('listaco')" style="width: 80%" type="button" class="art-button" id="btnco">
                        Controle de Entrega
                    </button>
                </form> 
            </div>
        </div>
    </div>

</div>

<script>

    function lista(formulario) {
        document.getElementById(formulario).submit();
    }

<?php
$funcao = ['ld','fi','co'];
foreach ($funcao as $f) {
    ?>
    function <?php echo $f ?>(nome) {
        teste = 0;
        <?php
        foreach ($esc as $k => $v) {
            ?>
                    if (document.getElementById("<?php echo $v['id_turma'] ?>").checked) {
                        teste = 1;
                        document.getElementById("<?php echo $f . $v['id_turma'] ?>").value = '<?php echo $v['id_turma'] ?>';
                    } else {
                        document.getElementById("<?php echo $f . $v['id_turma'] ?>").value = '';
                    }
            <?php
        }
        ?>
        if (teste == 0) {
            alert('Favor selecionar pelo menos uma Classe');
        } else {
            lista(nome);
        }
    }
    <?php
}
?>
    
    function checkAll(o) {
        var boxes = document.getElementsByTagName("input");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }
</script>

