<?php
if (!defined('ABSPATH'))
    exit;
?>
<style type="text/css">
    .titulo{
        font-weight: bold;
        text-align: center;
        font-size: 24px;
        padding: 10px;
    }
</style>
<div class="body">
    <form id="atr" method="POST">
        <div class="row" >
            <div class="col-md-3">
                <?=  formErp::input('1[tel3]','Telefone 1', @$tel3, " onkeyup='mascara(this, mtel)' "); ?>
            </div>
            <div class="col-md-3">
                <?=  formErp::input('1[tel1]','Telefone 2', @$tel1, " onkeyup='mascara(this, mtel)' "); ?>
            </div>
            <div class="col-md-3">
                <?=  formErp::input('1[tel2]','Telefone 3', @$tel2, " onkeyup='mascara(this, mtel)' "); ?>
            </div>
        </div>
        <br>
        <div class="row" >
            <div class="col-md-6 text-center">
                <?=  formErp::input('1[email]','Email', @$email); ?>
            </div>
        </div>
        <br>
        <div class="row">  
        <div class="col-md-">
            <?php formErp::textarea('1[obs]', NULL, 'Observação') ?>
        </div>
    </div>
        <br>
        <br>
        <div class="row" >
            <div class="col text-center">
                <?php
                    echo formErp::hidden([
                        '1[id_pessoa]' => $fk_id_pessoa_cadampe,
                        'old_tel1' => @$tel1,
                        'old_tel2' => @$tel2,
                        'old_tel3' => @$tel3,
                        'old_email' => @$email,
                        'fk_id_pessoa_cadampe' => $fk_id_pessoa_cadampe,
                        'id_pessoa_cadampe_edit' => $fk_id_pessoa_cadampe,
                        'id_cadampe_pedido' => $id_pedido,
                        'fk_id_pessoa_call' => $fk_id_pessoa_call,
                        'id_ec' => $id_ec,
                        'closeModal' => true, //se for um insert, atualiza a pagina
                    ]);
                ?>
                <?= formErp::hiddenToken('pessoa','ireplace',null,null,1)
                . formErp::button('Enviar',null,null,'btn btn-info');
                ?>
            </div>
        </div>

    </form>
    <?php
    if (!empty($formlog)) {?>
        <br>
        <div class="row titulo" >
            <div class="col">Histórico de Alterações</div>
        </div>
        <?php
        report::simple($formlog);
    }?>
</div>
<script>
function closeEditCadampe(){
    $('#editCModal').modal('hide');
    parent.document.iddiscForm.submit();
}

<?php if (!empty($closeModal)){ ?>
   closeEditCadampe();
<?php } ?>
</script>
<?php
javaScript::telMascara();