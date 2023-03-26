<?php
if (!defined('ABSPATH'))
    exit;
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
$resp = filter_input(INPUT_POST, 'resp', FILTER_SANITIZE_NUMBER_INT);
$nomeCpf = filter_input(INPUT_POST, 'nomeCpf', FILTER_SANITIZE_STRING);
$inscritos = $model->recursos($resp, $nomeCpf, $id_cate);

if ($inscritos) {
    foreach ($inscritos as $k => $v) {
      //  if (!empty($v['concluido'])) {
            $inscritos[$k]['edit'] = '<button class="btn btn-primary" onclick="edit(' . $v['id_ec'] . ')">Acessar</button>';
      //  }
        $inscritos[$k]['resp'] = empty($v['deferido']) ? 'Não' : 'Sim';
        $inscritos[$k]['concluido'] = empty($v['concluido']) ? 'Não' : 'Sim';
    }
    $form['array'] = $inscritos;
    $form['fields'] = [
        'Inscrição' => 'id_ec',
        'Categoria' => 'n_cate',
        'Nome' => 'nome',
        'CPF' => 'id_cpf',
        'Analisado' => 'resp',
        'Concluido' => 'concluido',
        'Situação' => 'n_sit',
        '||1' => 'edit'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Recursos
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('inscr_categoria', 'id_cate', 'Categoria', $id_cate) ?>
            </div>
            <div class="col">
                <?= formErp::select('resp', [null => 'Não', 1 => 'Sim'], ['Respondido', 'Não'], $resp) ?>
            </div>
            <div class="col">
                <?= formErp::input('nomeCpf', 'Nome ou CPF', $nomeCpf, null, null, 'search') ?>
            </div>
            <div class="col">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/inscr/def/formRecurso.php" id="form" target="frame" method="POST">
    <input type="hidden" name="id_ec" id="id_ec" value="" />
    <?=
    formErp::hidden([
        'id_cate' => $id_cate,
        'nomeCpf' => $nomeCpf,
        'resp' => $resp,
        'recurso = 1',
        'activeNav' => 3
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
