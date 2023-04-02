<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);

$a = data::meses();
$mes = filter_input(INPUT_POST, 'mes', FILTER_UNSAFE_RAW);
if (empty($mes)) {
    $mes = date("m");
}

if (user::session('id_nivel') == 10) {
    $relatorio = 'Gerente';
} else {
    $id_inst = tool::id_inst();
    $relatorio = 'Escola';
}
?>
<br /><br />
<div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
    Lista Escola
</div>

<div class="row">
    <br /><br />
    <div class="col-sm-1">
    </div>
    <div class="col-sm-5">
        <?php
        if (user::session('id_nivel') == 10) {
            echo form::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1);
        } else {
            ?>
            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
            <?php
        }
        ?>        
    </div>
    <?php
    ?>
    <div class="col-sm-3">
        <?php echo formulario::select('mes', $a, 'Selecionar Mês', @$mes, 1, ['id_inst' => $id_inst]) ?>
    </div>
    <div class="col-sm-3">
        <br /><br /><br />
    </div>
    <div class="row">
        <div class="col-sm-1">

        </div>
        <div class="col-sm-8">
            <div style="overflow: auto; height: 250px">
                <div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
                    Selecionar Linha
                </div>

                <?php
                $linha = $model->pegalinharel($id_inst);

                foreach ($linha as $k => $v) {
                    $linha[$k]['s'] = formulario::checkboxSimples('sel[]', $v['id_li'], NULL, NULL, 'id="' . $v['id_li'] . '"');
                }

                $form['array'] = $linha;
                $form['fields'] = [
                    'Código Linha' => 'id_li',
                    'Nome Linha' => 'n_li',
                    'todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />' => 's'
                ];
                tool::relatSimples($form);
                ?>            
            </div>
        </div>
        <div class="col-sm-3">
            <div class="fieldWhite text-center" style="padding: 20px">
                <form target="_blank" action="<?php echo HOME_URI ?>/transp/listaalunopdf" id="lista" method="POST">
                    <?php
                    foreach ($linha as $k => $v) {
                        ?>
                        <input id="la<?php echo @$v['id_li'] ?>" type="hidden" name="sel[]" value="" />                           
                        <?php
                    }
                    ?>  
                    <input type="hidden" name="mes" value="<?php echo @$mes ?>" />
                    <input type="hidden" name="relatorio" value="<?php echo $relatorio ?>" />
                    <input type="hidden" name="idinst" value="<?php echo $id_inst ?>" />
                    <button name="lista" value="Lista Alunos" onclick="listaaluno('lista')" onmouseover="la()" style="width: 80%" type="button" class="art-button" id="btnla">
                        Lista de Alunos
                    </button>
                </form> 
                <br /><br />
                <form target="_blank" action="<?php echo HOME_URI ?>/transp/movimentacaoalunopdf" id="movi" method="POST">
                    <?php
                    if (!empty($mes)) {
                        ?>
                        <input type="hidden" name="mes" value="<?php echo $mes ?>" />
                        <input type="hidden" name="idinst" value="<?php echo $id_inst ?>" />
                        <button name="movi" value="Movimentação" onclick="listaaluno('movi')" onmouseover="mo()" style="width: 80%" type="button" class="art-button" id="btnmo">
                            Movimentação Exclusão/Inclusão
                        </button>
                        <?php
                    }
                    ?>
                </form> 
                <br /><br />
                <form target="_blank" action="<?php echo HOME_URI ?>/transp/frequenciapdf" id="controle" method="POST">
                    <?php
                    foreach ($linha as $k => $v) {
                        ?>
                        <input id="co<?php echo @$v['id_li'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="mes" value="<?php echo @$mes ?>" />
                    <input type="hidden" name="idinst" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="relatorio" value="<?php echo $relatorio ?>" />
                    <button name="controle" value="Controle Freq" onclick="listaaluno('controle')" onmouseover="co()" style="width: 80%" type="button" class="art-button" id="btnco">
                        Controle de Frequência
                    </button>  

                </form> 
            </div>
        </div>
    </div> 
</div>
<?php
?>

<script>

    function listaaluno(formulario) {
        document.getElementById(formulario).submit();
    }

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

<?php
$funcao = ['la', 'mo', 'co'];
foreach ($funcao as $f) {
    ?>
        function <?php echo $f ?>() {
    <?php
    foreach ($linha as $k => $v) {
        ?>
                if (document.getElementById("<?php echo $v['id_li'] ?>").checked) {
                    document.getElementById("<?php echo $f . $v['id_li'] ?>").value = '<?php echo $v['id_li'] ?>';
                } else {
                    document.getElementById("<?php echo $f . $v['id_li'] ?>").value = '';
                }
        <?php
    }
    ?>
        }
    <?php
}
?>
</script>
