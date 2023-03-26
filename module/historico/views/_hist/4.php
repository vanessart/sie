<?php
if (!defined('ABSPATH'))
    exit;
if ($model->db->tokenCheck('restauraAnosAnteriores')) {
    $model->anosAnteriores($dados->dadosPessoais, null, 1);
}
$anoCompleto = @$_SESSION['TMP']['anoCompleto'][$id_pessoa];
$id_ciclo = @$_SESSION['TMP']['id_ciclo'][$id_pessoa];
if (empty($id_ciclo)) {
    $notas = $model->notas($id_pessoa);
    $id_ciclo = $model->cicloCursando($notas);
}

$h = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
$ensinoCiclos = $model->ensinoCiclos($h['fk_id_hte']);
if (!empty($h['ciclos'])) {
    $ciclosSet = json_decode($h['ciclos'], true);
    foreach ($ensinoCiclos as $k => $v) {
        if (!in_array($k, $ciclosSet)) {
            unset($ensinoCiclos[$k]);
        }
    }
}
if (!empty($h['regime'])) {
    $regime = json_decode($h['regime'], true);
} else {
    $regime = null;
}
$ant = $model->anosAnteriores($dados->dadosPessoais, $regime);
$hidden = [
    'id_pessoa' => $id_pessoa,
    'activeNav' => 4,
];
?>
<br />
<div class="row">
    <div class="col">

    </div>
    <div class="col">

    </div>
    <div class="col " style="text-align: right; padding-right: 30px">
        <form id="restor" method="POST">
            <?=
            formErp::hidden($hidden)
            . formErp::hiddenToken('restauraAnosAnteriores')
            ?>
            <button onclick="if(confirm('Esta ação apagará as alterações. Continuar?')){restor.submit()}" type="button" class="btn btn-danger">
                Restaurar dados
            </button>
        </form>
    </div>
</div>
<br />
<br />
<form method="POST">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td colspan="6" style="text-align: center">
                <?= str_replace('Resultados dos ', '', $h['n_hte'])  ?>
            </td>
        </tr>
        <tr>
            <td style="width: 100px">
                Série/Ano
            </td>
            <td style="width: 100px">
                Regime
            </td>
            <td style="width: 100px">
                Ano
            </td>
            <td>
                Estabelecimento
            </td>
            <td>
                Município
            </td>
            <td style="width: 50px">
                Estado
            </td>
        </tr>
        <?php
        foreach ($ensinoCiclos as $k => $v) {
            echo formErp::hidden(['i[' . $k . '][id_ha]' => @$ant[$k]['id_ha']]);
            if (!empty($id_ciclo) && $id_ciclo == $k && $anoCompleto == 'x') {
                break;
            }
            ?>
            <tr>
                <td>
                    <?php
                    if (@$regime[$k] == 1 || !empty($ant[$k]['regime'])) {
                        echo str_replace('Ano', 'Série', $v);
                    } else {
                        echo $v;
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if(@$regime[$k] == 1 || !empty($ant[$k]['regime'])){
                        $ant[$k]['regime'] = 1;
                    }

?>
                    <?= formErp::select('i[' . $k . '][regime]', ['x' => 'EF9', 1 => 'EF8'], null, empty($ant[$k]['regime']) ? 'x' : $ant[$k]['regime']) ?>
                </td>
                <td>
                    <?= formErp::selectNum('i[' . $k . '][ano]', [1950, date("Y")], null, @$ant[$k]['ano']) ?>
                </td>
                <td>
                    <?= formErp::input('i[' . $k . '][escola]', null, @$ant[$k]['escola']) ?>
                </td>
                <td>
                    <?= formErp::input('i[' . $k . '][cidade]', null, @$ant[$k]['cidade']) ?>
                </td>
                <td>
                    <?= formErp::input('i[' . $k . '][uf]', null, @$ant[$k]['uf']) ?>
                </td>
            </tr>
            <?php
            /**
            if (@$ant[$k]['ano'] == date("Y")) {
                break;
            }
             * 
             */
            if ($k == $id_ciclo && $id_ciclo) {
                break;
            }
        }
        ?>
    </table>
    <div style="text-align: center; padding: 30px">
        <?=
        formErp::hidden($hidden)
        . formErp::hiddenToken('historico_anosSet')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
