<?php
if (!defined('ABSPATH'))
    exit;
$serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
$id_ms = filter_input(INPUT_POST, 'id_ms', FILTER_SANITIZE_NUMBER_INT);
$manuts = $model->manuts($id_ms, $serial);

foreach ($manuts as $k => $v) {
    $manuts[$k]['data'] = substr($v['time_stamp'], 0, 10);
    $manuts[$k]['ac'] = '<button type="button" onclick="edit(' . $v['id_manut'] . ')" class="btn btn-info">Acessar</button>';
}
if (!empty($manuts)) {
    $form['array'] = $manuts;
    $form['fields'] = [
        'Serial' => 'serial',
        'Modelo' => 'n_cm',
        'Data' => 'data',
        'Status' => 'n_ms',
        '||' => 'ac'
    ];
}
?>

<div class="body">
    <div class="fieldTop">
        Manutenção e Baixa
    </div>
    <div class="row">
        <div class="col">
            <button type="button" onclick="edit()" class="btn btn-info">
                Nova Ocorrência
            </button>
        </div>
    </div>
    <br />
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('serial', 'Nº de Série', $serial) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('lab_chrome_manutencao_status', 'id_ms', ['Status', 'Em Aberto'], $id_ms) ?>
            </div>
            <div class="col">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br />
    </form>
    <?php
    if (!empty($form)) {
        report::simple($form);
    } else {
        ?>
        <div class="alert alert-danger">
            Não há resultado para esta pesquisa
        </div>
        <?php
    }
    ?>
</div>
<form target="frame" id="form" action="<?= HOME_URI ?>/lab/def/formManut.php" method="POST">
    <input type="hidden" id="id_manut" name="id_manut" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById('id_manut').value = id;
        } else {
            document.getElementById('id_manut').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>