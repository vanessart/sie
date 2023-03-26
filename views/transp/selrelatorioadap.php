<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);

$a = data::meses();
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
if (empty($mes)) {
    $mes = date("m");
}

?>
<br />
<div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
    Lista Escola
</div>

<div class="row">
    <br />
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
        <br /><br />
    </div>
    <div class="row">
        <div class="col-sm-1">

        </div>
        <div class="col-sm-8">
            <div style="overflow: auto; height: 250px">
                <div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
                    Selecionar Linha
                </div>
                <br />
                <?php
                $linha = transporte::nomeLinha(NULL, 1);
                echo form::select('id_li', $linha, 'Linha', @$id_li, 1, ['id_li' => @$id_li, 'id_inst' => $id_inst]);
                ?>            
            </div>
        </div>
        <div class="col-sm-3">
            <div class="fieldWhite text-center" style="padding: 20px">
                <form target="_blank" action="<?php echo HOME_URI ?>/transp/listaalunoadappdf" id="lista" method="POST">              
                    <input type="hidden" name="mes" value="<?php echo @$mes ?>" />  
                    <input type="hidden" name="idlinha" value="<?php echo @$id_li ?>" /> 
                    <input type="hidden" name="idinst" value="<?php echo $id_inst ?>" />
                    <button name="lista" value="Lista Alunos" onclick="listaaluno('lista')" style="width: 80%" type="button" class="art-button" id="btnla">
                        Lista de Alunos
                    </button>
                </form> 
                <br />
            </div>
        </div>
    </div> 
</div>
<script>

    function listaaluno(formulario) {
        document.getElementById(formulario).submit();
    }

</script>