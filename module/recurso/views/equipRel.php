<?php
if (!defined('ABSPATH'))
    exit;
if (in_array(user::session('id_nivel'), [10])) {
    $escola = $model->escolasOpt();
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = tool::id_inst();
}
if (!empty($id_inst)) {
    $emprestaList = $model->emprestimoGet($id_inst);
}?>
<div class="fieldTop">
    <div class="fieldTop">
        Devolução de Equipamentos
    </div>
    <div class="row">
        <div class="col-8 text-center">
            <?php
                echo formErp::select('id_serial', $serial, 'Número de Série', $id_serial, 1);
            ?>
        </div>
        <div class="col-4 text-center">
            <?php
            if (!empty($id_inst)) {
                ?>
                <button onclick="acesso()" class="btn btn-warning">
                    Novo empréstimo
                </button>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<br />
<?php
if (!empty($id_inst)) {
    if (!empty($emprestaList)) {
        report::simple($form);
    }
}
?>
<form id="formFrame" target="frame" action="" method="POST">
    <input type="hidden" id="id_move" name="id_move" value="" />
    <input type="hidden" id="id_pessoa" name="id_pessoa" value="" />
    <input type="hidden" id="n_pessoa" name="n_pessoa" value="" />
    <input type="hidden" id="id_pessoa_rm" name="id_pessoa_rm" value="" />
    <?=
    formErp::hidden(['id_inst' => $id_inst])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acesso(id,id_pessoa_rm,id_pessoa,n_pessoa) {
        if (id) {
            document.getElementById('id_move').value = id;
            document.getElementById('id_pessoa').value = id_pessoa;
            document.getElementById('n_pessoa').value = n_pessoa;
            document.getElementById('id_pessoa_rm').value = id_pessoa_rm;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/emprestimoHist.php";
            texto = '<div style="text-align: center; color: #7ed8f5;;">'+n_pessoa+' - '+id_pessoa_rm+'</div>'
        } else {
            document.getElementById('id_move').value = '';
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/emprestar.php";
            texto = "Novo Empréstimo";
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>