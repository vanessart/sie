<?php
if (!defined('ABSPATH'))
    exit;
$pls = ng_main::periodosPorSituacao();
$pls[''][0] = 'Período Padrão';
$id_pl = intval(filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT));
$carg = sql::get('sed_carga_horaria_pl', '*', ['fk_id_pl' => $id_pl]);
if ($carg) {
    foreach ($carg as $v) {
        $cargaPl[$v['fk_id_ciclo']] = $v;
    }
}
if ($id_pl == 0) {
    $n_pl = 'Padrão';
} else {
    $n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];
}
$ciclos =  [1 => '1º Ano', 2 => '2º Ano', 3 => '3º Ano', 4 => '4º Ano', 5 => '5º Ano', 6 => '6º Ano', 7 => '7º Ano', 8 => '8º Ano', 9 => '9º Ano', 
    25 => 'Termo 1 (EJA I)', 26 => 'Termo 2 1 (EJA I)', 27 => 'Termo 1 (EJA II)', 28 => 'Termo 2 (EJA II)', 29 => 'Termo 3 (EJA II)', 30 => 'Termo 4 (EJA II)'];

?>
<div class="body">
    <div class="fieldTop">
        Carga Horária / Período Letivo
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl, 1) ?>
        </div>
    </div>
    <br />
    <form method="POST">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td colspan="4">
                    Período Letivo: <?= $n_pl ?>
                </td>
            </tr>
            <tr>
                <td>
                    Curso
                </td>
                <td>
                    BNCC
                </td>
                <td>
                    BD
                </td>
                <td>
                    Total
                </td>
            </tr>
            <?php
            foreach ($ciclos as $k => $v) {
                ?>
                <tr>
                    <td>
                        <?= $v ?>
                    </td>
                    <td>
                        <?= formErp::input($k . '[bncc]', null, @$cargaPl[$k]['bncc'], null, null, 'number') ?>
                    </td>
                    <td>
                        <?= formErp::input($k . '[bd]', null, @$cargaPl[$k]['bd'], null, null, 'number') ?>
                    </td>
                    <td>
                        <?= formErp::input($k . '[total]', null, @$cargaPl[$k]['total'], null, null, 'number') ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                'id_pl' => $id_pl
            ])
            . formErp::hiddenToken('sed_carga_horaria_plSet')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
