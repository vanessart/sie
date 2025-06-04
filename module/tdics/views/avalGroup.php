<?php
if (!defined('ABSPATH'))
    exit;
$per = $model->periodoLetivos(1);

$ag = sqlErp::get('tdics_aval_group');
if ($ag) {
    foreach ($ag as $k => $v) {
        $ag[$k]['ed'] = '<button class="btn btn-primary" onclick="edit(' . ($v['id_ag']) . ')">Editar</button>';
        $ag[$k]['at_ag'] = toolErp::simnao($v['at_ag']);
        $ag[$k]['per'] = $per[$v['fk_id_pl']];
    }
    $form['array'] = $ag;
    $form['fields'] = [
        'ID' => 'id_ag',
        'Agrupamento' => 'n_ag',
        'Período Letivo' => 'per',  
        'Ativo' => 'at_ag',
        '||1' => 'ed'
    ];
}
?>

<div class="body">
    <div class="fieldTop">
        Cadastro de Grupo de Avaliações
    </div>
    <button class="btn btn-success" onclick="edit()">
        Novo Grupo
    </button>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/avalGr" target="frame" id="form" method="POST">
    <input type="hidden" name="id_ag" id="id_ag" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>

<script>
    function edit(id) {
        if (id) {
            id_ag.value = id;
        } else {
            id_ag.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>