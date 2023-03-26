<?php
if (!defined('ABSPATH'))
    exit;

if ( empty($dados)) {
    $msg = "Não há HTPC disponível nesta data.";
} else {
    foreach ($dados as $key => $value) {
        $dt_ata = $dados[$key]['dt_ata'];
        $btn = $dados[$key]['presenca_prof'];
        $view = $dados[$key]['view'];
    }
}
?>

<div class="body container">
    <?php if (!empty($msg)) { ?>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin: 20px auto;">
            <div class="alert alert-info"><?= $msg ?></div>
        </div>
    </div>
    <?php } else { ?>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin: 20px auto;">
            <h2>HTPC do dia <?= dataErp::converteBr($dt_ata) ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin: 20px auto;">
            <p><?= $btn ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin: 20px auto;">
            <p><?= $view ?></p>
        </div>
    </div>
    <?php if (!empty($pautas)) { ?>
    <div class="row">
        <div class="col-sm-12" style="margin: 20px auto;">
            <table class="table">
                <tr>
                    <th>Pauta</th>
                </tr>
                <?php foreach ($pautas as $key => $value) { ?>
                <tr>
                    <td style="white-space: pre-wrap;"><?= $value['n_pauta'] ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php } else { ?>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin: 20px auto;">
            <div class="alert alert-info">Ainda não há Pautas para este HTPC</div>
        </div>
    </div>
    <?php } 
    } ?>
</div>
<form id="form" target="frame" action="" method="POST">
    <input type="hidden" id="id_ata" name="id_ata">
</form>
<form id="formPresencaProf" action="<?= HOME_URI ?>/htpc/presenca" method="POST">
    <input type="hidden" id="id_ata_pres_prof" name="id_ata">
    <input type="hidden" id="id_pessoa_pres_prof" name="fk_id_pessoa">
    <input type="hidden" id="rm_pres_prof" name="rm">
    <input type="hidden" id="back" name="back" value="indexATA">
</form>

<?php toolErp::modalInicio(); ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
<?php toolErp::modalFim(); ?>
<script type="text/javascript">
function view(id_ata){
    document.getElementById('id_ata').value = id_ata;
    var titulo= document.getElementById('myModalLabel');
    titulo.innerHTML  = "Visualizar ATA";
    document.getElementById("form").action = '<?= HOME_URI ?>/htpc/ataVisualizar?'+Date.now();
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
</script>