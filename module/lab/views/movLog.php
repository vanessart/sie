<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$ativo = filter_input(INPUT_POST, 'ativo', FILTER_SANITIZE_NUMBER_INT);
$pesq = filter_input(INPUT_POST, 'pesq', FILTER_SANITIZE_NUMBER_INT);
if ($id_inst) {
    $search['fk_id_inst'] = $id_inst;
}
if (empty($pesq)) {
    $search['lab_chrome_mov_adm.ativo'] = 1;
} else {
    $search['lab_chrome_mov_adm.ativo'] = $ativo;
}
$mov = sqlErp::get(['lab_chrome_mov_adm', 'lab_chrome_mov_adm_motivo', 'instancia'], 'lab_chrome_mov_adm.*, lab_chrome_mov_adm_motivo.n_cmam, instancia.n_inst', $search);
$inst = escolas::idEscolas();

if ($mov) {
    foreach ($mov as $k => $v) {
        $mov[$k]['data'] = data::converteBr($v['time_stamp']);
        $mov[$k]['ac'] = '<button class="btn btn-info" onclick="ac(' . $v['id_cma'] . ')">Acessar</button>';
        if (empty($v['sem_registro'])) {
            $mov[$k]['ch'] = '<button class="btn btn-primary" onclick="ch(\'' . $v['serial'] . '\')">Chromebook</button>';
        } else {
            $mov[$k]['ch'] = '<button class="btn btn-danger" >Sem Registro</button>';
        }
        $mov[$k]['ativo'] = ($v['ativo'] == 1 ? 'Pendente' : 'Resolvido');
    }
    $form['array'] = $mov;
    $form['fields'] = [
        'Data' => 'data',
        'N/S' => 'serial',
        'Motivo' => 'n_cmam',
        'Escola' => 'n_inst',
        'Situação' => 'ativo',
        '||1' => 'ch',
        '||2' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Movimentação
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('id_inst', $inst, ['Escola', 'Todas'], $id_inst) ?>
            </div>
            <div class="col">
                <?= formErp::checkbox('ativo', 1, 'Pendente', (empty($pesq) ? 1 : $ativo)) ?>
            </div>
            <div class="col">
                <?= 
        formErp::button('Pesquisar')
        .formErp::hidden(['pesq'=>1])
        ?>
            </div>
        </div>
        <br />
    </form>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/lab/def/formChromeRede.php" target="frame" id="formFrame" method="POST">
    <input id="serial" type="hidden" name="serial" value="" />
</form>
<form action="<?= HOME_URI ?>/lab/def/formMov.php" target="frame" id="formFrameMov" method="POST">
    <input id="id_cma" type="hidden" name="id_cma" value="" />
    <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function ch(serial) {
        document.getElementById('serial').value = serial;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    
    function ac(id) {
        document.getElementById('id_cma').value = id;
        document.getElementById('formFrameMov').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>