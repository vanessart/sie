<?php
if (!defined('ABSPATH'))
    exit;
$fk_id_visita_item = filter_input(INPUT_POST, 'fk_id_visita_item', FILTER_SANITIZE_NUMBER_INT);
$fk_id_visita = filter_input(INPUT_POST, 'fk_id_visita', FILTER_SANITIZE_NUMBER_INT);
$visitaItem = !empty($fk_id_visita_item) ? current($model->getItensPorVisita($fk_id_visita_item)) : [];

$visitaDocumentos = $model->getDocumentosPorVisita(null, $fk_id_visita);
if ($visitaDocumentos) {
    foreach ($visitaDocumentos as $k => $v) {
        $visitaDocumentos[$k]['1'] = ' <button onclick="openModalDocumento(\''.$model->path_upload_documentos.$v['n_visita_documento_disco'].'\',\''.$v['n_visita_documento'].'\')" class="btn btn-outline-info">Visualizar</button>';
    }
}
$formDocumentos['array'] = $visitaDocumentos;
$formDocumentos['fields'] = [
    'ID' => 'id_visita_documento',
    'Nome' => 'n_visita_documento',
    'Data Atualização' => 'dt_update',
    '||1' => '1',
];
?>
<div class="body">
    <form method="POST" enctype="multipart/form-data">
        <div class="row my-3">
            <div class="col-6">
                <div class="custom-file">
                    <input type="file" class="form-control" name="arquivo" required />
                </div>
            </div>
            <div class="col-6">
            <?=
                formErp::hidden([
                    '1[id_visita_documento]' => null,
                    '1[fk_id_visita]' => $fk_id_visita,
                    'fk_id_visita' => $fk_id_visita,
                ])
                . formErp::hiddenToken('supervisorFotoSalvar')
                . formErp::button('Enviar Arquivo')
            ?>
            </div>
        </div>
    </form>
    <div>
        <?php report::simple($formDocumentos);?>
    </div>
</div>