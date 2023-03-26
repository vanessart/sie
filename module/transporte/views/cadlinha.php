<?php
if (!defined('ABSPATH'))
    exit;

$ativo = filter_input(INPUT_POST, 'ativo', FILTER_SANITIZE_NUMBER_INT);
if (is_null($ativo)) {
    $ativo = 1;
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Linha
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-3">
            <form id="formNew" method="POST" action="<?= HOME_URI ?>/transporte/cadlinhamodal" target="frame">
                <input type="hidden" name="modal" value="1" />
                <input type="hidden" name="id_li" id="id_li" value="" />
                <button class="btn btn-success" onclick="novo()">
                   Cadastrar Linha
                </button>
            </form>
        </div>
        <div class="col-sm-3">
            <form method="POST" >
                <input type="hidden" name="ativo" value="<?= $ativo == 1 ? 0 : 1 ?>" />
                <button class="btn btn-<?php if ($ativo == 1) { ?>danger<?php } else { ?>primary<?php } ?>">
                   Exibir <?php if ($ativo == 1) { ?>Inativos<?php } else { ?>Ativos<?php } ?>
                </button>
            </form>
        </div>
    </div>
    <form method="POST" id="formDel" >
        <input type="hidden" name="id_li" id="id_li_del" />
        <input type="hidden" name="inativar" value="1" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
    <br /><br />
    <?php
    $form = $model->listaLinhas($ativo);

    toolErp::relatSimples($form);
    ?>
</div>   
<script type="text/javascript">
    function novo(){
        document.getElementById("id_li").value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Cadastrar Linha";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function editar(id_li){
        document.getElementById("id_li").value = id_li;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Editar Linha";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function inativar(id_li){
        if (!confirm("Deseja realmente Inativar esta Linha?")) {
            return false;
        }
        document.getElementById("id_li_del").value = id_li;
        document.getElementById("formDel").submit();
    }
</script>