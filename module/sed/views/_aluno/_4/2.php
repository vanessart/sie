<style>
    /* Esconde o input */
    input[type='file'] {
        display: none
    }

    /* Aparência que terá o seletor de arquivo */
    #label {
        background-color: #3498db;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        margin: 0 auto;
        padding: 6px;
        width: 120px;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="border" style="padding: 15px">
    <div class="row">
        <div class="col" style="text-align: center;padding-top: 30px">
            <form id="trocaFoto" method="POST" enctype="multipart/form-data">
                <label id="label" for='selecao-arquivo'>Selecionar uma foto</label>
                <input onchange="document.getElementById('trocaFoto').submit();" required="required" id='selecao-arquivo' type='file' name="arquivo">
                <br />
                <?=
                formErp::hidden([
                    'id_pessoa' => $id_pessoa,
                    'activeNav' => 4
                ])
                . formErp::hiddenToken('fotoUp')
                ?>
            </form>
            <br /><br />
            <div id="label" onclick="webcam()">
                Usar a Webcam
            </div>
        </div>
        <div class="col" style="text-align: right">
            <img style="width: 140px" src="<?= $model->fotoEnd($id_pessoa) ?>" alt = "aluno"/>
        </div>
    </div>
</div>
<div class="border fichas" style="padding: 15px">
    <div class="row">
        <div class="col">
            <form id="fCad" target="_blank" action="<?php echo HOME_URI ?>/gestao/fichacadastral" name="alunos" method="POST">
                <input id="fc" type="hidden" name="sel[]" value="<?= $id_pessoa ?>" />
                <input type="hidden" name="id_turma" value="<?php echo $id_turma_atual ?>" /> 
                <button class="btn btn-info">
                    Ficha Cadastral
                </button>
            </form>  
        </div>
        <div class="col">
            <form id="pAlu" target="_blank" action="<?php echo HOME_URI ?>/sed/pdf/perfildoalunopdf.php" name="perfil" method="POST">
                <input id="pa" type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
                <input type="hidden" name="id_turma" value="<?php echo $id_turma_atual ?>" /> 
                <button class="btn btn-info">
                    Perfil do Aluno
                </button>                  
            </form>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <form action="<?php echo HOME_URI ?>/sed/hist" name="perfil" method="POST">
                <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
                <button class="btn btn-info">
                    Histórico Escolar
                </button>                  
            </form>
        </div>
        <div class="col">

        </div>
    </div>
    <br />
</div>







<form action="<?= HOME_URI ?>/sed/def/fotoWeb.php" target="frame" id="formFrame" method="POST">
    <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function webcam() {
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>