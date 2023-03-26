<?php
if (!defined('ABSPATH'))
    exit;
$id_ciclo = $periodo = filter_input(INPUT_POST, 'id_ciclo');
$anoCompleto = $periodo = filter_input(INPUT_POST, 'anoCompleto');
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
}
$baseEns = [
    'bncc' => 'Base Nacional Comum Curricular',
    'bd' => 'Base Diversificada',
    'total' => 'Carga Horária Total'
];
$carga = $model->cargaHora($id_pessoa, $id_ciclo);
foreach ($carga as $v) {
    if (@$v['ano'] == 2020) {
        $insCg['cursou_2020'] = 1;
    } elseif (@$v['ano'] == 2021) {
        $insCg['cursou_2021'] = 1;
    }
}

$cp_ = sql::get('historico_carga', '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');

if ($cp_) {
    $id_carga = $cp_['id_carga'];
    foreach ($cp_ as $k => $v) {
        $ar = explode('_', $k);
        if (is_numeric($ar[1]) && (empty($id_ciclo) || $id_ciclo >= $ar[1])) {
            $carga[$ar[1]][$ar[0]] = $v;
        } else {
            $carga[$ar[1]][$ar[0]] = null;
        }
    }
} else {

    foreach ($carga as $idci => $v) {
        if (!empty($v['ano']) && empty($anoSet)) {
            $anoSet = $v['ano'];
        } if (empty($v['ano']) && !empty($anoSet)) {
            break;
        }
        $insCg['bncc_' . $idci] = $v['bncc'];
        $insCg['bd_' . $idci] = $v['bd'];
        $insCg['total_' . $idci] = $v['total'];
    }

    $insCg['fk_id_pessoa'] = $id_pessoa;
    $id_carga = $model->db->insert('historico_carga', $insCg, 1);
    $cp_ = sql::get('historico_carga', '*', ['id_carga' => $id_carga], 'fetch');
    foreach ($cp_ as $k => $v) {
        $ar = explode('_', $k);
        if (is_numeric($ar[1]) && (empty($id_ciclo) || $id_ciclo >= $ar[1])) {
            $carga[$ar[1]][$ar[0]] = $v;
        }
    }
}
$hidden = [
    'id_pessoa' => $id_pessoa,
    'activeNav' => 3,
];
?>
<div class="body">
    <div class="row">
        <div class="col">

        </div>
        <div class="col">

        </div>
        <div class="col " style="text-align: right; padding-right: 30px">
            <form id="restor" method="POST">
                <?=
                formErp::hidden($hidden)
                . formErp::hiddenToken('apagaCarga')
                ?>
                <button onclick="if (confirm('Esta ação apagará as alterações. Continuar?')) {
                            restor.submit()
                        }" type="button" class="btn btn-danger">
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
                <td>

                </td>
                <?php
                foreach ($ensinoCiclos as $k => $v) {
                    ?>
                    <td>
                        <?php
                        if (@$regime[$k] == 1) {
                            echo str_replace('Ano', 'Série', $v);
                        } else {
                            echo $v;
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            foreach ($baseEns as $campo => $base) {
                ?>
                <tr>
                    <td>
                        <?= $base ?>
                    </td>
                    <?php
                    foreach ($ensinoCiclos as $k => $v) {
                        $cg = @$carga[$k][$campo];
                        ?>
                        <td>
                            <?= formErp::input('1[' . $campo . '_' . $k . ']', null, $cg, null, null, 'number') ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col">
                <?= formErp::checkbox('1[cursou_2020]', 1, 'Este aluno estava cursando no ano de 2020', $cp_['cursou_2020']) ?>
            </div>
            <div class="col">
                <?= formErp::checkbox('1[cursou_2021]', 1, 'Este aluno estava cursando no ano de 2021', $cp_['cursou_2021']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 50px">
            <?=
            formErp::hidden([
                '1[fk_id_pessoa]' => $id_pessoa
            ])
            . formErp::hidden([
                'id_pessoa' => $id_pessoa,
                'activeNav' => 3,
                '1[id_carga]' => $id_carga
            ])
            . formErp::hiddenToken('historico_cargaSet')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
