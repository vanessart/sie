<?php
if (!defined('ABSPATH'))
    exit;
$options = ng_main::periodosPorSituacao([1, 2]);
$dados = sql::get('maker_setup', '*', null, 'fetch');
if (!empty($dados['libera_matr'])) {
    $libera_matr = explode(',', $dados['libera_matr']);
} else {
    $libera_matr = [];
}
if (!empty($dados['transf'])) {
    $transf = explode(',', $dados['transf']);
} else {
    $transf = [];
}
?>
<div class="body">
    <div class="fieldTop">
        Configurações
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_pl]', $options, 'Período Letivo', $dados['fk_id_pl']) ?>
            </div>
            <div class="col">
                <?= formErp::select('1[fk_id_pl_matr]', $options, 'Período Letivo para Matrícula', $dados['fk_id_pl_matr']) ?>
            </div>
            <div class="col">
                <?= formErp::select('1[fila_espera]', [0 => 'Não', 1 => 'Sim'], 'Liberar Fila de Espera', $dados['fila_espera']) ?>
            </div>
        </div>
        <br />
        <div class="border">
            <div class="row">
                <div class="col">
                    Libera Matrícula
                </div>
                <div class="col">
                    <?= formErp::checkbox('libera_matr[1]', 1, 'Matriculado', in_array(1, $libera_matr)) ?>
                </div>
                <div class="col">
                    <?= formErp::checkbox('libera_matr[3]', 3, 'Fila de Espera', in_array(3, $libera_matr)) ?>
                </div>
            </div>
            <br />
        </div>
        <br /><br />
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td colspan="7" style="text-align: center">
                    Liberar Transfêrencia
                </td>
            </tr>
            <tr>
                <?php
                $sem = dataErp::diasDaSemana();
                foreach ($sem as $k => $v) {
                    ?>
                    <td>
                        <?= formErp::checkbox('transf[' . $k . ']', 1, $v, in_array($k, $transf) ? 1 : null) ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden(['1[id_setup]' => 1])
            . formErp::hiddenToken('maker_setupSet')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
    <?php
    $options = ng_main::periodosPorSituacao();
    ?>
    <div class="border">
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('1[fk_id_pl_certificado]', $options, 'Liberar Certificado', $dados['fk_id_pl_certificado']) ?>
                </div>
                <div class="col">
                    <?= formErp::input('1[dt_certif]', 'Data dos Certificados', $dados['dt_certif'], null, null, 'date') ?>
                </div>
                <div class="col">
                    <?=
                    formErp::hidden(['1[id_setup]' => 1])
                    . formErp::hiddenToken('maker_setupSet')
                    . formErp::button('Salvar')
                    ?>
                </div>
            </div>
            <br />
        </form>
    </div>
</div>
