<?php
if (!defined('ABSPATH'))
    exit;
$id_iu = $_REQUEST['id_iu'];
if ($id_iu) {
    $iu = sql::get('inscr_inscr_upload', '*', ['id_iu' => $id_iu], 'fetch');
}
?>
<div class="body">
    <div style="text-align: center">
    </div>
    <form method="POST">
        <br />
        <div class="row">
            <div class="col" style="font-weight: bold">
                Nome do Arquivo: <?= $iu['nome_origin'] ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[obs]', $iu['obs'], 'Obs') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td>
                            <?= formErp::radio('1[deferido]', 1, 'Deferido', $iu['deferido']) ?>
                        </td>
                        <td>
                            <?= formErp::radio('1[deferido]', 2, 'Indeferido', $iu['deferido']) ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col">
                <?= formErp::selectDB('inscr_motivo', '1[fk_id_mot]', 'Motivo do Indeferimento', $iu['fk_id_mot']) ?>
            </div>
            <div class="col-2">
                <?=
                formErp::hidden([
                    'id_iu' => $id_iu,
                    '1[id_iu]' => $id_iu,
                    '1[dt_deferido]' => date("Y-m-d"),
                    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                    'activeNav' => 2
                ])
                . formErp::hiddenToken('inscr_inscr_upload', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </div>
        <br />
    </form>

</div>
