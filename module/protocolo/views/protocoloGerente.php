<?php
if (!defined('ABSPATH'))
    exit;

$id_protocolo = @$_POST['id_protocolo'];
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_status = filter_input(INPUT_POST, 'id_status', FILTER_SANITIZE_NUMBER_INT);
$id_area = filter_input(INPUT_POST, 'id_area', FILTER_SANITIZE_NUMBER_INT);
$pessoa_status = filter_input(INPUT_POST, 'pessoa_status', FILTER_SANITIZE_STRING);
$n_status = filter_input(INPUT_POST, 'n_status', FILTER_SANITIZE_STRING);
$dt_status = filter_input(INPUT_POST, 'dt_status', FILTER_SANITIZE_STRING);
$hidden = [
    'id_pessoa' => $id_pessoa,
    'id_area' => $id_area,
    'id_protocolo' => $id_protocolo
];
if ($id_protocolo) {
    $protocolo = $model->getProtocolo($id_protocolo);
    $n_protocolo = $id_protocolo."/".$protocolo['dt_protocolo'];
    $ativo = 1;
    $status = quest::getProtocoloStatus($id_protocolo,'protocolo_status_pessoa'); 
    $hidden['id_status'] = $status['fk_id_proto_status'];
    $hidden['pessoa_status'] = $status['n_pessoa'];
    $hidden['n_status'] = $status['n_status'];
    $hidden['dt_status'] = $status['dt_update'];
} else {
    $ativo = null;
    if (empty($id_protocolo)) { ?>
    <script>
        location.href = '<?= HOME_URI ?>/apd/protocoloList';
    </script>
    <?php
    exit;
}
}?>
<div class="body">
    <div class="row fieldTop">   
        <div class=" col-10">
            Protocolo <?= !empty($id_protocolo) ? $n_protocolo : '' ?><br><br><?= !empty( $status['n_status']) ?  $status['n_status'] : ''  ?>
        </div>
        <div class="col-2" style="text-align: right;padding: 10px">
            <form action="<?= HOME_URI ?>/apd/protocoloList" method="POST">
                <button class="btn btn-info" style="margin: 0">
                    Voltar
                </button>
            </form>
        </div>
    </div>
    <?php
    $abas[1] = ['nome' => "Dados Iniciais", 'ativo' => 1, 'hidden' => $hidden];
    $abas[2] = ['nome' => "Formulário de Encaminhamento", 'ativo' => $ativo, 'hidden' => $hidden];
    $abas[4] = ['nome' => "Registrar CID", 'ativo' => $ativo, 'hidden' => $hidden];
    $abas[3] = ['nome' => "Deferimento", 'ativo' => $ativo, 'hidden' => $hidden];
    $aba = report::abas($abas);
    require ABSPATH . "/module/protocolo/views/_protocoloGerente/$aba.php"
    ?>
</div>