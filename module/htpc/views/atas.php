<?php
if (!defined('ABSPATH'))
    exit;?>
<div class="body">
    <div class="fieldTop">
        ATAS
    </div>
    <?php
    if ( empty($dados) ) { ?>
        <div class="row">
            <div class="col-sm-12 text-center" style="margin: 20px auto;">
                <div class="alert alert-info">Não há ATAS disponíveis</div>
            </div>
        </div>
    <?php }

    if ( empty($model::isProfessor()) ) { ?>
        <div class="row">
            <div class="col-md-3">
                <button class="btn btn-info" onclick="novo()">
                   Nova ATA
                </button>
            </div>
        </div>
        <?php } ?>
        <br>
        <?php
        if (!empty($formDados)) {
            report::simple($formDados);
        }
        ?>
        <form id="formEdit" method="POST" action="<?= HOME_URI ?>/htpc/atasCadastro">
            <input type="hidden" id="id_ataEdit" name="id_ata">
            <input type="hidden" id="action" name="action" value="edit">
        </form>
        <form id="form" target="frame" action="" method="POST">
            <input type="hidden" id="id_ata" name="id_ata">
        </form>
        <form id="formPresenca" action="<?= HOME_URI ?>/htpc/presenca" method="POST">
            <input type="hidden" id="id_ata_presenca" name="id_ata">
        </form>
        <form id="formPresencaProf" action="<?= HOME_URI ?>/htpc/presenca" method="POST">
            <input type="hidden" id="id_ata_pres_prof" name="id_ata">
            <input type="hidden" id="id_pessoa_pres_prof" name="fk_id_pessoa">
            <input type="hidden" id="rm_pres_prof" name="rm">
            <input type="hidden" id="back" name="back" value="listATA">
        </form>
        <?php
        toolErp::modalInicio();
        ?>
        <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
        toolErp::modalInicio(0, 'modal-sm', 'modalFecharATA');
        ?>
        <div class="alert alert-danger">
            <strong>ATENÇÃO: </strong> <br><br>Após fechar a ATA não será possível reabri-la.
        </div>
        <div class="row" style="text-align:center;">
            <div class="col col-md-12 col-form-label">Deseja continuar mesmo assim?</div>
        </div>
        <div class="row" style="text-align:center;">
            <div class="col col-md-6 col-form-label">
                <button class="btn btn-outline-info close" data-bs-dismiss="modal" aria-label="Close">Não</button>
            </div>
            <div class="col col-md-6 col-form-label">
                <form name="formCloseATA" id="formCloseATA" method="POST">
                    <input type="hidden" name="1[id_ata]" id="id_ata_close">
                    <?=
                    formErp::hidden([
                        '1[status]' => 'F',
                        '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                    ])
                    . formErp::hiddenToken('htpc_ata', 'ireplace');
                    ?>
                    <button class="btn btn-outline-danger">Sim</button>
                </form>
            </div>
        </div>
        <?php
        toolErp::modalFim();
        ?>
</div>
<script type="text/javascript">
    function novo(){
        document.getElementById('id_ata').value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Cadastrar Nova ATA";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/atasCadastro?'+Date.now();
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function view(id_ata){
        document.getElementById('id_ata').value = id_ata;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Visualizar ATA";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/ataVisualizar?'+Date.now();
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function addEmenta(id_ata){
        document.getElementById('id_ata').value = id_ata;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Adicionar Ementa";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/ementaCadastro?'+Date.now();
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function presencaProf(id_ata, id_pessoa, rm){
        if(confirm('Deseja realmente marcar presença neste HTPC ?')) {
            document.getElementById('id_ata_pres_prof').value = id_ata;
            document.getElementById('id_pessoa_pres_prof').value = id_pessoa;
            document.getElementById('rm_pres_prof').value = rm;
            document.getElementById("formPresencaProf").submit();
        }
    }
    function editATA(id_ata){
        document.getElementById('id_ataEdit').value = id_ata;
        document.getElementById("formEdit").submit();
    }
    function pdf(id_ata){
        document.getElementById('id_ata').value = id_ata;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Visualizar PDF";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/ataPDF?'+Date.now();
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
