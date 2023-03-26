<?php
if (!defined('ABSPATH'))
    exit;

$fk_id_visita_item = filter_input(INPUT_POST, 'fk_id_visita_item', FILTER_SANITIZE_NUMBER_INT);
$fk_id_visita = filter_input(INPUT_POST, 'fk_id_visita', FILTER_SANITIZE_NUMBER_INT);

$visitaItem = !empty($fk_id_visita_item) ? current($model->getItensPorVisita($fk_id_visita_item)) : [];

$aLabelStatus = $model->getLabelStatusItem();
$aStatus = [];
foreach ($aLabelStatus as $v) {
    $aStatus[$v['id_visita_item_label_status']] = $v['n_label_status'];
}

$areasResult = $model->getAreas();
$aAreas = [];
if (!empty($areasResult)) {
    foreach ($areasResult as $v) {
        $aAreas[$v['id_area']] = $v['n_area'];
    }
}
asort($aAreas);

$resultVisitaItem = [
    'id_visita_item' => $fk_id_visita_item,
    'fk_id_visita' => $fk_id_visita,
    'fk_id_area' => @$visitaItem['fk_id_area'] ?: null,
    'fk_id_item_ocorrencia' => @$visitaItem['fk_id_item_ocorrencia'] ?: null,
    'n_visita_item' => @$visitaItem['n_visita_item'] ?: null,
    'at_visita_item' => @$visitaItem['at_visita_item'] ?: 1,
];

?>

<div class="body content">
    <form id="atr" method="POST" target="_parent" action="<?= HOME_URI ?>/supervisor/supervisorVisitasPesq" >
        <?php if (!empty($fk_id_visita_item)): ?>
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">Área: <?= $visitaItem["n_area"] ?></h5>
                <b>Descrição:</b> <?= $visitaItem["n_visita_item"] ?>
            </div>
        </div>
        <?php else: ?>
        <?= formErp::hidden([
                '1[id_visita_item]' => $resultVisitaItem['id_visita_item'],
                '1[fk_id_visita]' => $resultVisitaItem['fk_id_visita'],
                '1[at_visita_item]' => $resultVisitaItem['at_visita_item'],
                'backModal' => 1,
                'activeNav' => 2,
                'id_visita' => $fk_id_visita,
            ]);
        ?>
        <?= formErp::hiddenToken('vis_visita_item','ireplace') ?>
        <div class="row mt-3">
            <div class="col-md-4">
                <?= formErp::select('fk_id_area', $aAreas, 'Área', $resultVisitaItem['fk_id_area'], null, null, 'required'); ?>
            </div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Descrição do item</span>
                    <select name="1[fk_id_item_ocorrencia]" id="id_item" class="form-select" required>
                        <option value="">Selecione</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Descrição</span>
                    <!-- <input type="date" name="1[data_visita]" class="form-control" value="<?= $resultVisitaItem['data_visita'] ?>"/> -->
                    <textarea name="1[n_visita_item]" class="form-control" required><?= $resultVisitaItem['n_visita_item'] ?></textarea>
                </div>
            </div>
            <?php if (!$fk_id_visita_item): ?>
            <div class="col-md-4">
                <?= formErp::button('Salvar',null,null,'btn btn-info'); ?>
            </div>
            <?php endif ?>
        </div>
        <?php endif ?>
        <?php if (!empty($fk_id_visita_item)): ?>
        <hr />
        <h4>Novo Status</h4>
        <div class="row mt-3">
            <div class="col-3">
                <?= formErp::select('2[status]', $aStatus, 'Status', $resultVisitaItem['at_visita_item'], null, null, 'required') ?>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Comentário</span>
                    <textarea name="2[comentario]" class="form-control" required></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <?= formErp::hidden([
                    '1[id_visita_item]' => $resultVisitaItem['id_visita_item'],
                    '1[fk_id_visita]' => $resultVisitaItem['fk_id_visita'],
                    '1[at_visita_item]' => $resultVisitaItem['at_visita_item'],
                    'backModal' => $resultVisitaItem['at_visita_item'] ?? null,
                    'activeNav' => 2,
                    'id_visita' => $fk_id_visita,
                ]); ?>
                <?= formErp::hidden(['2[fk_id_visita_item]' => $fk_id_visita_item]); ?>
                <?= formErp::button('Salvar',null,null,'btn btn-info'); ?>
            </div>
        </div>
        <?php endif ?>
    </form>
</div>

<script>
    var itens = JSON.parse('<?= json_encode($model->getItensOcorrencia()) ?>');

    $('#fk_id_area_').change(() => {
        var fk_id_area = $( "#fk_id_area_ option:selected" ).val();
        $('#id_item')
            .find('option')
            .remove()
            .end()
            .append('<option value="">Selecione</option>')
            .val('');

        itens.map((item) => {
            if (item.fk_id_area === fk_id_area) {
                $('#id_item').append($('<option>', {
                    value: item.id_item_ocorrencia,
                    text: item.n_item_ocorrencia
                }));
            }
        })
    });
</script>