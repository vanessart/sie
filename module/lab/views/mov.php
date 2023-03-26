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
    $mov = sql::get(['lab_chrome_mov_adm', 'lab_chrome_mov_adm_motivo'], '*', ['fk_id_inst'=>$id_inst]);
    foreach ($mov as $k => $v){
        $mov[$k]['data']= data::converteBr($v['time_stamp']);
        $mov[$k]['ativo']= ($v['ativo']==1?'Pendente':'Resolvido');
    }
    $form['array'] = $mov;
    $form['fields'] = [
        'data' => 'data',
        'Nº de Série' => 'serial',
        'Motivo' => 'n_cmam',
        'Obs' => 'obs',
        'Situação'=>'ativo'
    ];
}
/**
if (!empty($id_inst)) {
    $hist = $model->movHist($id_inst);
    foreach ($hist as $k => $v){
        $hist[$k]['data']= data::converteBr($v['times_stamp']);
    }
    $form['array'] = $hist;
    $form['fields'] = [
        'data' => 'data',
        'Nº de Série' => 'serial',
        'Modelo' => 'n_cm',
        'Usuário'=>'n_pessoa',
        'Obs' => 'obs'
    ];
}
 * 
 */
?>
<div class="body">
    <div class="fieldTop">
        Movimentação de Chromebooks
    </div>

    <div class="row">
        <div class="col-8 text-center">
            <?php
            if (!empty($escola)) {
                echo formErp::select('id_inst', $escola, 'Escola', $id_inst, 1);
            }
            ?>
        </div>
        <div class="col-3" style="padding: 20px">
            <?php
            if (!empty($id_inst)) {
                ?>
                <button class="btn btn-warning" onclick="chrome()">
                    Selecionar Chromebook
                </button>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/lab/defProf/formMov.php" id="formFrame" target="frame" method="POST">
    <input type="hidden" name="id_mov" id="id_mov" value="" />
    <?=
    formErp::hidden([
        'id_inst' => $id_inst
    ])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function chrome(id) {
        if (id) {
            document.getElementById("id_mov").value = id;
        } else {
            document.getElementById("id_mov").value = '';
        }
        document.getElementById("formFrame").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>