<?php
if (!defined('ABSPATH'))
    exit;

?>

<div class="body">
    <div class="fieldTop">
        CADAMPES
    </div>
    <div class="row" >
        <div class="col-md-6">
            <?= formErp::select('iddisc', $disc_, ['Categoria', 'Todas'], @$id_categoria, 1); ?>
        </div>
        <div class="col-md-2">
            <form method="POST">
                <input type="hidden" name="iddisc" id="iddisc" value="<?= @$id_categoria ?>" />
                <?= formErp::button('Atualizar Lista',null,null,'warning'); ?>
                
            </form>
        </div>
    </div>
    <?php
    if (!empty($formCadampe)) {
        report::simple($formCadampe);
    }?>
</div>

<form id="editC" method="POST" target="frameeditC" action="<?= HOME_URI ?>/cadampe/editCadampe">
    <input type="hidden" name="id_pessoa_cadampe_edit" id="id_pessoa_cadampe_edit" value="" />
    <input type="hidden" name="id_ec_tel" id="id_ec_tel" value="" />
</form>
<?php
toolErp::modalInicio(0, 'modal-md', 'editCModal', 'Editar Telefone')
?>
<iframe name="frameeditC" id="frameeditC" style="width: 100%; height: 50vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>

<form id="hist" method="POST" target="framehist" action="<?= HOME_URI ?>/cadampe/historicoCadampeList">
    <input type="hidden" name="id_pessoa_cadampe_hist" id="id_pessoa_cadampe_hist" value="" />
    <input type="hidden" name="n_pessoa_cadampe" id="n_pessoa_cadampe" value="" />
</form>
<?php
toolErp::modalInicio(0, 'modal-md', 'histModal', '')
?>
<iframe name="framehist" id="framehist" style="width: 100%; height: 50vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>

<script>

    function hist(id_pessoa_cadampe, n_pessoa) {
        document.getElementById("id_pessoa_cadampe_hist").value = id_pessoa_cadampe;
        document.getElementById("n_pessoa_cadampe").value = n_pessoa;
        var titulo= document.getElementById('histModalLabel');
            titulo.innerHTML  = "Histórico de "+n_pessoa;
        $('#histModal').modal('show');
        document.getElementById('hist').submit();
    }

    function editC(id_pessoa,n_pessoa) {
        document.getElementById("id_pessoa_cadampe_edit").value = id_pessoa;
        var titulo= document.getElementById('editCModalLabel');
            titulo.innerHTML  = "Editar Telefone de "+n_pessoa;
        $('#editCModal').modal('show');
        document.getElementById('editC').submit();
    }

    function sobe() {
        window.scrollTo(0, 0);
    }

</script>