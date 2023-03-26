<?php
if (!defined('ABSPATH'))
    exit;
$escolasGeral = $model->escolasGeral();
if (tool::id_nivel() == '10') {
    $cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_STRING);
    if ($cie) {
        $model->cie = $cie;
        $model->escola = $escolasGeral[$cie];
    }
}
if ($model->cie) {
    $requerentes = $model->requerentes($model->cie);
    if ($requerentes) {
        foreach ($requerentes as $k => $v) {
            $requerentes[$k]['ac'] = formErp::submit('Acessar', null, ['id_passelivre' => $v['id_passelivre'], 'cie' => $model->cie], HOME_URI.'/passelivre/requer');
        }
        $form['array'] = $requerentes;
        $form['fields'] = [
            'ID' => 'id_passelivre',
            'Nome' => 'nome',
            'RA' => 'ra',
            'UF-RA' => 'ra_uf',
            'Status' => 'n_status',
            '||1' => 'ac'
        ];
    }
}
?>
<div class="body">
    <?php
    if (tool::id_nivel() == '10') {
        echo formErp::select('cie', $escolasGeral, 'Escola', $cie, 1);
    }
    if (!empty($model->cie)) {
        ?>
        <br /><br />
        <div class="fieldTop">
            Escola: <?= $model->escola ?> (CIE: <?= $model->cie ?>)
        </div>
        <br />
        <div class="row">
            <div class="col">
                <button class="btn btn-info" onclick="requer()">
                    Novo Requerimento
                </button>
            </div>
        </div>
        <br />
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/passelivre/def/formRequer.php" target="frame" id="form" method="POST">
    <input type="hidden" name="cie" value="<?= $model->cie ?>" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim()
    ?>
<script>
    function requer(id) {
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>