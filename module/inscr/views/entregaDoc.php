<?php
if (!defined('ABSPATH'))
    exit;
$compacere = $periodo = filter_input(INPUT_POST, 'compacere');
if ($compacere) {
    $ins['id_cpf'] = $periodo = filter_input(INPUT_POST, 'cpf');
    $ins['presencial'] = $periodo = filter_input(INPUT_POST, 'presencial');
    $model->db->ireplace('inscr_incritos_3', $ins);
}
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
$id_sit = filter_input(INPUT_POST, 'id_sit', FILTER_SANITIZE_NUMBER_INT);
$nomeCpf = filter_input(INPUT_POST, 'nomeCpf', FILTER_SANITIZE_STRING);
$fk_id_vs = filter_input(INPUT_POST, 'fk_id_vs', FILTER_SANITIZE_NUMBER_INT);
if (!$fk_id_vs) {
    $fk_id_vs = 2;
}
$inscritos_ = $model->inscritos(3, $nomeCpf, $id_cate, $fk_id_vs);
$inscr_valida_situacao = sql::idNome('inscr_valida_situacao');
if ($inscritos_) {
    foreach ($inscritos_ as $k => $v) {
        $cate = @$inscritos[$v['id_cpf']]['n_cate'];
        $inscritos[$v['id_cpf']] = $v;
        $inscritos[$v['id_cpf']]['edit'] = '<button class="btn btn-primary" onclick="edit(\'' . $v['id_cpf'] . '\')">Acessar</button>';
        if ($fk_id_vs == 1) {
            $inscritos[$v['id_cpf']]['prot'] = formErp::submit('Protocolo', null, ['cpf' => $v['id_cpf']], HOME_URI . '/inscr/pdf/protFim', 1);
            if ($v['presencial'] == 1) {
                $inscritos[$v['id_cpf']]['pres'] = formErp::submit('Compareceu', null, ['cpf' => $v['id_cpf'], 'fk_id_vs' => 1, 'presencial' => 0, 'compacere' => 1], null, null, null, 'btn btn-success');
            } else {
                $inscritos[$v['id_cpf']]['pres'] = formErp::submit('Não Compareceu', null, ['cpf' => $v['id_cpf'], 'fk_id_vs' => 1, 'presencial' => 1, 'compacere' => 1], null, null, null, 'btn btn-danger');
            }
        }
        $inscritos[$v['id_cpf']]['n_cate'] .= (empty($cate) ? '' : '<br>' . $cate);
        $inscritos[$v['id_cpf']]['validado'] = empty($inscr_valida_situacao[$v['fk_id_vs']]) ? 'Não Enviado' : $inscr_valida_situacao[$v['fk_id_vs']];
    }
    if (!empty($inscritos)) {
        $form['array'] = $inscritos;
        $form['fields'] = [
            'Categoria' => 'n_cate',
            'Nome' => 'nome',
            'CPF' => 'id_cpf',
            'Validado' => 'validado',
            '||3' => 'pres',
            '||1' => 'prot',
            '||2' => 'edit'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Entrega de Documentos
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('inscr_categoria', 'id_cate', 'Categoria', $id_cate) ?>
            </div>
            <div class="col">
                <?= formErp::select('fk_id_vs', [1 => 'Validado', 2 => 'Em Análise', 3 => 'Devolvido', 4 => 'Não Enviado'], 'Entrega', $fk_id_vs) ?>
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
        Total: <?= !empty($inscritos) ? count($inscritos) : 0 ?>
    </div>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/inscr/def/formEntrega.php" id="form" target="frame" method="POST">
    <input type="hidden" name="id_cpf" id="id_cpf" value="" />
    <?=
    formErp::hidden([
        'fk_id_vs' => $fk_id_vs,
    ])
    ?>
</form>
<?php
toolErp::modalInicio(null, 'modal-fullscreen');
?>
<iframe name="frame" style="width: 100%; height: 220vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        id_cpf.value = id;
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
