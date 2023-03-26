<?php
if (empty($_POST['periodoLetivo'])) {
    $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
}

#############
$esc = sql::get('ge_turmas', 'codigo, id_turma, n_turma', 'where fk_id_inst = ' . tool::id_inst() . " and fk_id_pl = '$periodoLetivo' order by codigo");

$a = "SELECT codigo, id_turma, n_turma FROM ge_turmas"
        . " RIGHT JOIN tmp_selecao on tmp_selecao.id_selecao = ge_turmas.id_turma"
        . " Where ge_turmas.fk_id_inst = " . tool::id_inst() . " order by codigo";

$query = $model->db->query($a);
$escs = $query->fetchAll();
?>

<div class="fieldBody">
    <div class="row">
        <div class="col-md-3">
            <?php
            $per = gtMain::periodosPorSituacao();
            formulario::select('periodoLetivo', $per, 'Período Letivo', @$periodoLetivo, 1);
            ?>
        </div>
    </div>
    <br /><br />
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


        <div class="col-md-4" >
            <div class="fieldWhite text-center" style="padding: 20px">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapresenca" id="presenca" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="pr<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    formulario::input('titulo', 'Título', NULL, NULL, 'style=" color: black;font-weight: bold; font-size: 20px"  onkeypress="digit()" id="txt"', 'Lista de Presença')
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <input onclick="pr('presenca')" styid="btps" style="width: 80%" type="button" class="art-button" id="btnpr" type="submit" value=" Lista de Presença" />
                </form>
                <script>
                    function digit() {
                        document.getElementById('btnpr').value = document.getElementById('txt').value;
                    }
                </script>
            </div>
            <br />
            <div class="fieldWhite text-center" style="padding: 20px">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listachamada" id="lchamada" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="lc<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>

                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" /> 
                    Selecione mês desejado   
                    <br /><br />
                    <select name="mes">
                        <?php
                        foreach (data::meses() as $k => $v) {
                            ?>
                            <option <?php echo date("m") == $k ? 'selected' : '' ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                        ?>

                    </select>
                    <br /><br />
                    <button name="lchamada" value="Lista Chamada" onclick="lc('lchamada')" style="width: 80%" type="button" class="art-button" id="btnlc">
                        Lista de Chamada
                    </button>
                </form> 
            </div>
            <br />
            <div class="fieldWhite text-center" style="padding: 20px">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapiloto" id="listpiloto" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="lp<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="listpiloto" value="Lista Piloto" onclick="lp('listpiloto')" style="width: 80%" type="button" class="art-button" id="btnlp">
                        Lista Piloto
                    </button>
                </form> 
                <br />

                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaparede" id="parede" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="pa<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="parede" value="Lista Parede" onclick="pa('parede')" style="width: 80%" type="button" class="art-button" id="btnpa">
                        Lista Piloto - Parede
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaemail" id="email" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="pe<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="email" value="Lista Email" onclick="pe('email')" style="width: 80%" type="button" class="art-button" id="btnpe">
                        Lista Email
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapaternidade" id="paternidade" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="pt<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="paternidade" value="Lista Paternidade" onclick="pt('paternidade')" style="width: 80%" type="button" class="art-button" id="btnpt">
                        Lista com Filiação
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listaidadeano" id="idadeano" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="ia<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="idadeano" value="Lista Idade" onclick="ia('idadeano')" style="width: 80%" type="button" class="art-button" id="btnia">
                        Lista Idade/Ano Escolar
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_piloto_doc" id="listadoc" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="dc<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="listadoc" value="Lista Documentos" onclick="dc('listadoc')" style="width: 80%" type="button" class="art-button" id="btndc">
                        Lista Piloto - Documentos
                    </button>
                </form> 
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_piloto_end" id="listaen" method="POST">
                    <?php
                    foreach ($esc as $k => $v) {
                        ?>
                        <input id="en<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="listaen" value="Lista Endereço" onclick="en('listaen')" style="width: 80%" type="button" class="art-button" id="btnen">
                        Lista Piloto - Endereço
                    </button>
                </form> 
                <br />
                <!--
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_conselhopdf" id="conselho" method="POST">
                <?php
                foreach ($esc as $k => $v) {
                    ?>
                                <input id="cs<?php echo @$v['id_turma'] ?>" type="hidden" name="sel[]" value="" />
                    <?php
                }
                ?>
                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />                   
                    <button name="conselho" value="Lista Idade" onclick="listpiloto('conselho')" onmouseover="cs()" style="width: 80%" type="button" class="art-button" id="btncs">
                        Lista Resultado Final
                    </button>
                </form> 
                -->
            </div>
        </div>

    </div>

</div>


<script>
    function listpilotosubmit(formulario) {

            if (confirm('Dependendo do número de classes selecionadas, este relatório poderá demorar alguns minutos para aparecer na tela.')) {
                document.getElementById(formulario).submit();
            }

    }

<?php
$funcao = ['lc', 'lp', 'pr', 'pa', 'pt', 'ia', 'cs', 'dc', 'en', 'pe'];
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
                listpilotosubmit(nome);
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


<script>
/*
$('#btnen').on('click',function(e){
    e.preventDefault();
    var checado = $("input[name='sel[]']:checked").length > 0;
    alert(checado);
    return;
});*/
</script>

