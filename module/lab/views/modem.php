<?php
if (!defined('ABSPATH'))
    exit;

$modems = $model->modems();
if ($modems) {
    foreach ($modems as $k => $v) {
        $modems[$k]['at'] = toolErp::simnao($v['at_modem']);
        $modems[$k]['ac'] = '<button class=" btn btn-info" onclick="edit(' . $v['id_modem'] . ')">Acessar</button>';
    }
    $form['array'] = $modems;
    $form['fields'] = [
        'Serial' => 'serial',
        'Modelo' => 'n_mm',
        'Escola' => 'n_inst',
        'Ativo' => 'at',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Modem
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary" onclick="edit()">
                Novo Modem
            </button>
        </div>
    </div>
    <br />
    <?php
    if ($form) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/lab/def/formModem.php" target="frame" id="form" method="POST">
    <input type="hidden" id="id_modem" name="id_modem" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim()
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById('id_modem').value = id;
        } else {
            document.getElementById('id_modem').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>