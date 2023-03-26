<?php
if (!defined('ABSPATH'))
    exit;
$id_up = @$_REQUEST['id_up'];
$id_ec = @$_REQUEST['id_ec'];
if ($id_up) {
    $up = sql::get('inscr_upload', '*', ['id_up' => $id_up], 'fetch');
    $cert = sql::get('inscr_certificado_deferimento', '*', ['fk_id_ec' => $id_ec, 'fk_id_up' => $id_up], 'fetch');
    if (@$cert['deferido'] == 2) {
        $certPontos = 0;
    } else {
        $certPontos = @$cert['pontos'];
    }
}
if (empty($up['obrigatorio']) && empty($up['fk_id_cate'])) {
    $deferimentoFinal = 0;
} else {
    $deferimentoFinal = 1;
}
?>
<div class="body">
    <br />
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td style="width: 25%">
                Obrigatório: <?= toolErp::simnao($up['obrigatorio']) ?>
            </td>
            <td style="width: 25%">
                Quantidade Máxima: <?= $up['quant'] ?>
            </td>
            <td style="width: 25%">
                Pontos: <?= $up['pontos'] ?>
            </td>

        </tr>
    </table>
    <form method="POST">
        <div class="row">
            <div class="col-3">
                <?php
                if (empty($deferimentoFinal)) {
                    unset($pontos);
                    for ($c = $up['pontos']; $c <= ($up['pontos'] * $up['quant']); $c += $up['pontos']) {
                        $pontos[$c] = $c;
                    }
                    echo formErp::select('1[pontos]', $pontos, ['Pontos', 0], $certPontos);
                } else {
                    echo formErp::input('1[pontos]', 'Pontos', $certPontos, ' id="pontos_" min="0" max="' . ($up['pontos'] * $up['quant']) . '" ', null, 'number');
                }
                ?>
            </div>
            <div class="col-9">
                <?= formErp::textarea('1[obs_cd]', @$cert['obs_cd'], 'Obs') ?>
                <br />
                <div class="row">
                    <div class="col-8">
                        <?php
                        if (empty($deferimentoFinal)) {
                            ?>
                            <table>
                                <tr>
                                    <td>
                                        <?= formErp::radio('1[deferido]', 1, 'Deferido', @$cert['deferido']) ?>
                                    </td>
                                    <td>
                                        <?= formErp::radio('1[deferido]', 2, 'Indeferido', @$cert['deferido']) ?>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        } else {
                            ?>
                            <table>
                                <tr>
                                    <td>
                                        <?= formErp::radio('1[deferido]', 1, 'Deferido', @$cert['deferido'], ' onclick="pont(1)" ') ?>
                                    </td>
                                    <td>
                                        <?= formErp::radio('1[deferido]', 2, 'Indeferido', @$cert['deferido'], ' onclick="pont()" ') ?>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="col-4">
                        <?=
                        formErp::hidden([
                            'id_up' => $id_up,
                            'id_ec' => $id_ec,
                            '1[id_cd]' => @$cert['id_cd'],
                            '1[fk_id_up]' => $id_up,
                            '1[fk_id_ec]' => $id_ec,
                            '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                            'deferimentoFinal'=>$deferimentoFinal
                        ])
                        . formErp::hiddenToken('inscr_certificado_deferimentoSet')
                        . formErp::button('Salvar')
                        ?>
                    </div>
                </div>
                <br />

            </div>
        </div>
        <br />
    </form>
</div>
<script>
    function pont(id) {
        if (id) {
            pontos_.value = 1;
        } else {
            pontos_.value = 0;
        }
    }
</script>