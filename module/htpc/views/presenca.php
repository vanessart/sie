<?php
if (!defined('ABSPATH'))
    exit;
?>
<style type="text/css">
    .alert-success {
        padding: 3px;
    }
    .alert-danger {
        padding: 3px;
    }
</style>
<div class="body">
    <div class="fieldTop">
        HTPC do dia <?= dataErp::converteBr($dt_ata) ?>
    </div>
    <form id="form" method="POST">
    <?php
    if (!empty($formDados)) {
        report::simple($formDados);
    }
    ?>
    <div class="row">
        <div class="col col-form-label text-center">
            <input type="hidden" name="fk_id_pessoa" id="fk_id_pessoa_add">
            <input type="hidden" name="rm" id="rm_add">
            <?=
            formErp::hidden([
                'id_ata' => $id_ata,
                'fk_id_ata' => $id_ata,
                'fk_id_pessoa_reg' => toolErp::id_pessoa(),
            ]);
            ?>
        </div>
    </div>
    </form>
</div>
<form id="formRP" target="frame" method="POST" action="<?= HOME_URI ?>/htpc/presencaRemover">
    <input type="hidden" name="id_ata" id="id_ata_remove">
    <input type="hidden" name="fk_id_pessoa" id="fk_id_pessoa_remove">
    <input type="hidden" name="rm" id="rm_remove">
    <input type="hidden" name="acao" id="acao_">
</form>
<?php toolErp::modalInicio(0, 'modal-md', 'modalJustAusencia'); ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
<?php toolErp::modalFim(); ?>

<script type="text/javascript">
    $('.check-todos').click(function(){
        $('.check-prof').attr('checked', $(this).is(':checked'));
    });
    function remove(id_ata, fk_id_pessoa, rm, title, act){
        if (!title) {
            title = "Remover Presença";
        }
        if (!act){
            act = 'R';
        }
        document.getElementById('id_ata_remove').value = id_ata;
        document.getElementById('fk_id_pessoa_remove').value = fk_id_pessoa;
        document.getElementById('rm_remove').value = rm;
        document.getElementById('acao_').value = act;
        var titulo= document.getElementById('modalJustAusenciaLabel');
        titulo.innerHTML = title;
        document.getElementById("formRP").submit();
        $('#modalJustAusencia').modal('show');
    }
    function presente(id_ata, fk_id_pessoa, rm){
        remove(id_ata, fk_id_pessoa, rm, "Atribuir Presença", "A");
    }
</script>