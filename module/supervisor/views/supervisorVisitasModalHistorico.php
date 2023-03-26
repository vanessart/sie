<?php
if (!defined('ABSPATH'))
    exit;

$fk_id_visita_item = filter_input(INPUT_POST, 'fk_id_visita_item', FILTER_SANITIZE_NUMBER_INT);
$fk_id_visita = filter_input(INPUT_POST, 'fk_id_visita', FILTER_SANITIZE_NUMBER_INT);

$visitaItem = current($model->getItensPorVisita($fk_id_visita_item));
$resultHistorico = $model->getItensPorVisitaHistorico(null, null, $fk_id_visita_item);
$aCoordenadores = $model->getCoordenadores();
$aStatus = $model->getItensStatusPorVisita($fk_id_visita_item);

$arr[] = [
    'fk_id_pessoa' => null,
    'n_pessoa' => null,
    'n_visita_item_historico' => $visitaItem['n_visita_item'],
    'dt_update' => $visitaItem['dt_update']
];

if (count($resultHistorico)) {
    $resultHistorico = array_merge($arr, $resultHistorico);
}
else {
    $resultHistorico = $arr;
}

$resultHistorico = array_merge($resultHistorico, $aStatus);

$dtUpdate = array();
foreach ($resultHistorico as $key => $row)
{
    $dtUpdate[$key] = $row['dt_update'];
}
array_multisort($dtUpdate, SORT_ASC, $resultHistorico);

function getClass($id_pessoa) {
    $c = 'right';
    if (in_array($resultHistorico, $id_pessoa)
        || toolErp::id_nilvel() == 22 && tool::id_pessoa() == $id_pessoa) {
        $c = 'left';
    }
    return $c;
}

?>
<div class="col-inside-lg decor-default">
    <div class="card mb-2">
        <div class="card-body">
            <h5 class="card-title">Área: <?= $visitaItem["n_area"] ?></h5>
            <b>Descrição:</b> <?= $visitaItem["n_visita_item"] ?>
        </div>
    </div>

    <div class="chat-body">
        <h6>Histórico do item da ocorrência</h6>
        <?php foreach ($resultHistorico as $hist): ?>
        <div class="answer <?= getClass($hist['fk_id_pessoa']) ?>">
            <div class="name"><?= $hist['n_pessoa'] ?></div>
            <div class="text"><?= $hist['n_visita_item_historico'] ?></div>
            <div class="time"><?= date('d/m/Y H:i:s', strtotime($hist['dt_update'])) ?></div>
        </div>
        <?php endforeach ?>
        <form id="atr" method="POST" target="_parent" action="<?= HOME_URI ?>/supervisor/supervisorVisitasPesq">
        <div class="answer-add">
            <?= formErp::hidden([
                    '1[id_visita_item_historico]' => null,
                    '1[fk_id_pessoa]' => tool::id_pessoa(),
                    'fk_id_visita' => $fk_id_visita,
                    '1[fk_id_visita_item]' => $fk_id_visita_item,
                    '1[at_visita_item]' => 1,
                    'backModal' => 1,
                    'activeNav' => 2,
                ]);
            ?>
            <?= formErp::hiddenToken('vis_visita_item_historico','ireplace') ?>
            <input name="1[n_visita_item_historico]" placeholder="Escreva uma nova mensagem" required>
            <?= formErp::button(' > ',null,null,'btn btn-success'); ?>
        </div>
        </form>
    </div>
</div>

<style>
    .row.row-broken {
        padding-bottom: 0;
    }

    .col-inside-lg {
        padding: 20px;
    }

    .chat {
        height: calc(100vh - 180px);
    }

    .decor-default {
        background-color: #ffffff;
    }

    .chat-users h6 {
        font-size: 20px;
        margin: 0 0 20px;
    }

    .chat-users .user {
        position: relative;
        padding: 0 0 0 50px;
        display: block;
        cursor: pointer;
        margin: 0 0 20px;
    }

    .chat-users .user .avatar {
        top: 0;
        left: 0;
    }

    .chat .avatar {
        width: 40px;
        height: 40px;
        position: absolute;
    }

    .chat .avatar img {
        display: block;
        border-radius: 20px;
        height: 100%;
    }

    .chat .avatar .status.off {
        border: 1px solid #5a5a5a;
        background: #ffffff;
    }

    .chat .avatar .status.online {
        background: #4caf50;
    }

    .chat .avatar .status.busy {
        background: #ffc107;
    }

    .chat .avatar .status.offline {
        background: #ed4e6e;
    }

    .chat-users .user .status {
        bottom: 0;
        left: 28px;
    }

    .chat .avatar .status {
        width: 10px;
        height: 10px;
        border-radius: 5px;
        position: absolute;
    }

    .chat-users .user .name {
        font-size: 14px;
        font-weight: bold;
        line-height: 20px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-users .user .mood {
        font: 200 14px/20px "Raleway", sans-serif;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /*****************CHAT BODY *******************/
    .chat-body h6 {
        font-size: 20px;
        margin: 0 0 20px;
    }

    .chat-body .answer.left {
        padding: 0 0 0 58px;
        text-align: left;
        float: left;
    }

    .chat-body .answer {
        position: relative;
        max-width: 600px;
        overflow: hidden;
        clear: both;
    }

    .chat-body .answer.left .avatar {
        left: 0;
    }

    .chat-body .answer .avatar {
        bottom: 36px;
    }

    .chat .avatar {
        width: 40px;
        height: 40px;
        position: absolute;
    }

    .chat .avatar img {
        display: block;
        border-radius: 20px;
        height: 100%;
    }

    .chat-body .answer .name {
        font-size: 14px;
        line-height: 20px;
    }

    .chat-body .answer.left .avatar .status {
        right: 4px;
    }

    .chat-body .answer .avatar .status {
        bottom: 0;
    }

    .chat-body .answer.left .text {
        background: #ebebeb;
        color: #333333;
        border-radius: 8px 8px 8px 0;
    }

    .chat-body .answer .text {
        padding: 12px;
        font-size: 16px;
        line-height: 26px;
        position: relative;
    }

    .chat-body .answer.left .text:before {
        left: -30px;
        border-right-color: #ebebeb;
        border-right-width: 12px;
    }

    .chat-body .answer .text:before {
        content: '';
        display: block;
        position: absolute;
        bottom: 0;
        border: 18px solid transparent;
        border-bottom-width: 0;
    }

    .chat-body .answer.left .time {
        padding-left: 12px;
        color: #333333;
    }

    .chat-body .answer .time {
        font-size: 12px;
        line-height: 36px;
        position: relative;
        padding-bottom: 1px;
    }

    /*RIGHT*/
    .chat-body .answer.right {
        padding: 0 58px 0 0;
        text-align: right;
        float: right;
    }

    .chat-body .answer.right .avatar {
        right: 0;
    }

    .chat-body .answer.right .avatar .status {
        left: 4px;
    }

    .chat-body .answer.right .text {
        background: #7266ba;
        color: #ffffff;
        border-radius: 8px 8px 0 8px;
    }

    .chat-body .answer.right .text:before {
        right: -30px;
        border-left-color: #7266ba;
        border-left-width: 12px;
    }

    .chat-body .answer.right .time {
        padding-right: 12px;
        color: #333333;
    }

    /**************ADD FORM ***************/
    .chat-body .answer-add {
        clear: both;
        position: relative;
        margin: 20px -20px -20px;
        padding: 20px;
        background: #46be8a;
    }

    .chat-body .answer-add input {
        border: none;
        background: none;
        display: block;
        width: 90%;
        float: left;
        font-size: 22px;
        line-height: 20px;
        padding: 5px;
        color: #ffffff;
    }

    .chat-body .answer-add input::placeholder {
        color: #fff;
    }

    .chat input {
        -webkit-appearance: none;
        border-radius: 0;
    }

    .chat-body .answer-add .answer-btn-1 {
        right: 56px;
    }

    .chat-body .answer-add .answer-btn {
        display: block;
        cursor: pointer;
        width: 36px;
        height: 36px;
        position: absolute;
        top: 50%;
        margin-top: -18px;
    }

    .chat-body .answer-add .answer-btn-2 {
        right: 20px;
    }

    .chat input::-webkit-input-placeholder {
        color: #fff;
    }

    .chat input:-moz-placeholder {
        /* Firefox 18- */
        color: #fff;
    }

    .chat input::-moz-placeholder {
        /* Firefox 19+ */
        color: #fff;
    }

    .chat input:-ms-input-placeholder {
        color: #fff;
    }

    .chat input {
        -webkit-appearance: none;
        border-radius: 0;
    }
</style>
