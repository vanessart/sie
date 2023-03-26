<?php
if (!defined('ABSPATH'))
    exit;
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
$id_sit = filter_input(INPUT_POST, 'id_sit', FILTER_SANITIZE_NUMBER_INT);
$nomeCpf = filter_input(INPUT_POST, 'nomeCpf', FILTER_SANITIZE_STRING);
$validado = filter_input(INPUT_POST, 'validado', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_sit)) {
    $id_sit = 2;
}
$inscritos = $model->inscritos($id_sit, $nomeCpf, $id_cate, $validado);
if ($inscritos) {
    foreach ($inscritos as $k => $v) {
        $inscritos[$k]['edit'] = '<button class="btn btn-primary" onclick="edit(' . $v['id_ec'] . ')">Acessar</button>';
    }
    $form['array'] = $inscritos;
    $form['fields'] = [
        'Inscrição' => 'id_ec',
        'Categoria' => 'n_cate',
        'Nome' => 'nome',
        'CPF' => 'id_cpf',
        'Senha'=>'pin',
        'Nasc' => 'dt_nasc',
        'Situação' => 'n_sit',
        'Uploads'=>'docs',
        '||1' => 'edit'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Deferimento
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('inscr_categoria', 'id_cate', 'Categoria', $id_cate) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('inscr_situacao', 'id_sit', 'Situação', $id_sit) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('nomeCpf', 'Nome ou CPF', $nomeCpf, null, null, 'search') ?>
            </div>
            <div class="col">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
    </form>
    <br /><br />
    <div class="alert alert-primary">
        Total: <?= count($inscritos) ?>
    </div>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/inscr/def/formDeferir.php" id="form" target="frame" method="POST">
    <input type="hidden" name="id_ec" id="id_ec" value="" />
    <?=
    formErp::hidden([
        'id_cate' => $id_cate,
        'nomeCpf' => $nomeCpf,
        'id_sit' => $id_sit
    ])
    ?>
</form>
<?php
toolErp::modalInicio(null, 'modal-fullscreen');
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        id_ec.value = id;
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
