<?php
if (!defined('ABSPATH'))
    exit;
if (in_array(user::session('id_nivel'), [10])) {
    $escola = 1;
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    if (empty($id_inst)) {
        $id_inst = 13; 
    }  
} else {
    $id_inst = tool::id_inst();
}
$ocorrencias = $model->ocorrenciasGet($id_inst);
?>
<div class="fieldTop">
    <div class="fieldTop">
        Registro de Ocorrências
    </div>
    <div class="row">
        <?php   
        if (!empty($escola)) {?>
            <div class="col-8 text-center">
                <?= formErp::selectDB('instancia','id_inst', 'Escola', $id_inst, 1);?>
            </div>
            <?php 
        }?>
        <div class="col-3">
            <button onclick="acesso()" class="btn btn-warning">
                Registrar Ocorrência
            </button>
        </div>
    </div>
</div>
<br />
<?php
if (!empty($ocorrencias)) {
    foreach ($ocorrencias as $k => $v) {
         
            $equipamento = $v['n_equipamento']." - ".$v['n_serial'];
            $ocorrencias[$k]['edit'] = formErp::button('Acessar', null, 'acesso(' . $v['id_move'] .',' . $v['id_situacao'] .',' . $v['id_serial'] .','. ' \'' . $equipamento . '\' )', 'info');
            $ocorrencias[$k]['data'] = $v['dt_update'];
    }
    $form['array'] = $ocorrencias;
    $form['fields'] = [
        'Situação' => 'n_situacao',
        'Modelo/Lote' => 'n_equipamento',
        'Número de Série' => 'n_serial',
        'Data de Entrada' => 'data',
        '||2' => 'edit'
    ];
}

if (!empty($ocorrencias)) {
    report::simple($form);
}

?>
<form id="formFrame" target="frame" action="" method="POST">
    <input type="hidden" id="id_move" name="id_move" value="" />
    <input type="hidden" id="id_serial" name="id_serial" value="" />
    <input type="hidden" id="vizualizar" name="vizualizar" value="" />
    <input type="hidden" id="id_situacao" name="id_situacao" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acesso(id,id_situacao,id_serial,equipamento) {
        if (id) {
            document.getElementById('id_move').value = id;
            document.getElementById('id_serial').value = id_serial;
            document.getElementById('id_situacao').value = id_situacao;
            document.getElementById('vizualizar').value = 1;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/devolver.php";
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Vizualizar Ocorrência</div>';
        } else {
            document.getElementById('id_move').value = '';
            document.getElementById('id_serial').value = '';
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/ocorrencia.php";
            texto = '<div style="text-align: center; color: #7ed8f5;">Registrar Ocorrência</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>