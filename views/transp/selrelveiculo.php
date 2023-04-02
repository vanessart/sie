<?php
$id_tv = filter_input(INPUT_POST, 'id_tv', FILTER_SANITIZE_NUMBER_INT);
$a = data::meses();
$mes = filter_input(INPUT_POST, 'mes', FILTER_UNSAFE_RAW);
if (empty($mes)) {
    $mes = date("m");
}

$relatorio = 'Gerente';

$veiculos = sql::get('transp_veiculo', 'placa, id_tv, n_tv', ['>' => 'placa']);
foreach ($veiculos as $v) {
    $veiculoOption[$v['id_tv']] = $v['placa'] . ' - ' . $v['n_tv'];
}
?>
<br /><br />
<div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
    Lista Veículo
</div>

<div class="row">
    <br /><br />
    <div class="col-sm-1">
    </div>
    <div class="col-sm-5">
        <?php
        echo form::select('id_tv', $veiculoOption, 'Veículo', $id_tv, 1)
        ?>        
    </div>
    <?php ?>
    <div class="col-sm-3">
        <?php echo formulario::select('mes', $a, 'Selecionar Mês', @$mes, 1, ['id_tv' => $id_tv]) ?>
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
                $linha = $model->pegalinharelVeiculo($id_tv);

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
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/alunotransp.php" id="ex" method="POST">
                    <?php
                    foreach ($linha as $k => $v) {
                        ?>
                        <input id="excel<?php echo @$v['id_li'] ?>" type="hidden" name="sel[]" value="" />                           
                        <?php
                    }
                    ?>  
                    <input type="hidden" name="mes" value="<?php echo @$mes ?>" />
                    <input type="hidden" name="relatorio" value="<?php echo $relatorio ?>" />
                    <input type="hidden" name="idinst" value="<?php echo $id_inst ?>" />
                    <button name="lista" value="Lista Alunos" onclick="listaaluno('ex')" onmouseover="excel()" style="width: 80%" type="button" class="art-button" id="btnla">
                        Lista de Aluno em Excel
                    </button>
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
<?php ?>

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
$funcao = ['la', 'mo', 'co', 'excel'];
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
