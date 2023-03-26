<?php
if (!defined('ABSPATH'))
    exit;

$pront = $model->getUploads($id_pessoa);
if ($pront) {
    $token = formErp::token('sed_prontuario_up', 'delete');
    foreach ($pront as $k => $v) {
        $pront[$k]['ac'] = formErp::submit('Download', null, null, HOME_URI . '/pub/sed_doc/' . $v['end'], 1);
        $pront[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_pu]' => $v['id_pu'], 'id_pessoa' => $id_pessoa, 'activeNav' => 5]);
    }
    $form['array'] = $pront;
    $form['fields'] = [
        'Documento' => 'n_pront',
        'Obs' => 'n_pu',
        'Data' => 'dt_pu',
        '||2' => 'del',
        '||1' => 'ac'
    ];
}
?>
<div class="border">
    <div class="fieldTop">
        Entrega de Documentos
    </div>
    <div class="fieldWhite">
        <br />
        <div class="row">
            <div class="col-sm-6 text-center">
                <button class="btn btn-warning" onclick="up('doc')">
                    Upload
                </button>
            </div>
            <div class="col-sm-6 text-center">
                <button class="btn btn-warning" onclick="up('webcam')">
                    Webcam
                </button>
            </div>
        </div>
        <br />
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
        ?>
    </div>
</div>
<form id="form" action="<?= HOME_URI ?>/sed/def/formUp.php" target="frame" method="POST">
    <input id="up" type="hidden" name="up" value="" />
    <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="border: none; width: 100%; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function up(t) {
        document.getElementById('up').value = t;
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
