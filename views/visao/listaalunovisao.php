<?php
$periodo = date('Y');
$esc = sql::get('ge_turmas', 'codigo, id_turma, n_turma', 'WHERE fk_id_inst = ' . tool::id_inst() . " AND periodo_letivo = '$periodo' AND fk_id_ciclo IN (1) ORDER BY codigo");
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
                    'Código Classe' => 'codigo',
                    'Descrição' => 'n_turma',
                    'Todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />' => 'tur'
                ];
                tool::relatSimples($form);
                ?>            
            </div>        
        </div> 


        <div class="col-md-4" >
            <br />
            <div class="fieldWhite text-center" style="padding: 20px">
                <form target="_blank" action="<?php echo HOME_URI ?>/visao/testepdf" id="teste" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="pt<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="proteste" value="Protocolo Teste" onclick="protocolo('proteste')" onmouseover="pt()" style="width: 80%" type="submit" class="art-button" id="btnpt">
                        Protocolo Resultados - Teste (Branco)
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/visao/retestepdf" id="reteste" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="rt<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="reproteste" value="Protocolo Reteste" onclick="protocolo('reproteste')" onmouseover="rt()" style="width: 80%" type="submit" class="art-button" id="btnrt">
                        Protocolo Resultados - Reteste (Branco)
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/visao/testepdfresu" id="testeresu" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="ptr<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="protesteresu" value="Protocolo Teste Resultado" onclick="protocolo('protesteresu')" onmouseover="ptr()" style="width: 80%" type="submit" class="art-button" id="btnptr">
                        Protocolo Resultados - Teste 
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/visao/retestepdfresu" id="reteste" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="rtr<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="reprotesteresu" value="Protocolo Reteste Resultado" onclick="protocolo('reprotesteresu')" onmouseover="rtr()" style="width: 80%" type="submit" class="art-button" id="btnrtr">
                        Protocolo Resultados - Reteste
                    </button>
                </form> 
                <br />
            </div>
        </div>
    </div>
</div>

<script>

<?php
$funcao = ['pt', 'rt', 'ptr', 'rtr'];
foreach ($funcao as $f) {
    ?>
        function <?php echo $f ?>() {

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


