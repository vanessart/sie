<?php
if (!defined('ABSPATH'))
    exit;
$escolasGeral = $model->escolasGeral();
$requerentes = $model->requerentes();
$chamaescola = $model->escolasGeral();

if (tool::id_nivel() == '10') {
    $cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_STRING);
    if ($cie) {
        $model->cie = $cie;
        $model->escola = $escolasGeral[$cie];
    }
}
if ($model->cie) { // Filtros do select escola
    $requerentes = $model->requerentes($model->cie, 1);
    if ($requerentes) {
        foreach ($requerentes as $k => $v) {
            $requerentes[$k]['escola'] = $chamaescola[$v['cie']];
            $requerentes[$k]['ac'] = formErp::submit('Acessar', null, ['id_passelivre' => $v['id_passelivre'], 'cie' => $model->cie, 'activeNav' => 3], HOME_URI . '/passelivre/requer');
        }
        $form['array'] = $requerentes;
        $form['fields'] = [
            'ID' => 'id_passelivre',
            'Escola'=> 'escola',
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
        echo formErp::select('cie', $escolasGeral, ['Escola', 'TODAS'], $cie, 1);
    }
    if (!empty($model->cie)) {
        ?>
        <br /><br />
        <div class="fieldTop">
            Escola: <?= $model->escola ?> (CIE: <?= $model->cie ?>)
        </div>
        <br />

        <br />
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
    } else { // todas as escolas
        $requerentes = $model->requerentes();
       
        foreach ($requerentes as $k => $v) {
            $requerentes[$k]['escola'] = $chamaescola[$v['cie']]; 
            $requerentes[$k]['ac'] = formErp::submit('Acessar', null, ['id_passelivre' => $v['id_passelivre'], 'cie' => $v['cie'], 'activeNav' => 3, 'con'=> 1], HOME_URI . '/passelivre/requer') ;
        }
        $form['array'] = $requerentes;
        $form['fields'] = [
            'ID' => 'id_passelivre',
            'Escola' => 'escola',
            'Nome' => 'nome',
            'RA' => 'ra',
            'UF-RA' => 'ra_uf',
            'Status' => 'n_status',
            '||1' => 'ac'
        ];
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