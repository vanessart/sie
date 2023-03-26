<?php
if (!defined('ABSPATH'))
    exit();
$at_set = $periodo = filter_input(INPUT_POST, 'at_set');
$sond = $model->sondagens($id_pl, $id_curso);

if ($sond['quant'] < 1) {
    die();
} elseif ($sond['quant'] > 1) {
    $at_sonda = $sond['at_sonda'];
    if (!$at_set) {
        $at_set = $at_sonda;
    }
    ?>
    <div class="row" style="padding-top: 20px">
        <?php
        foreach (range(1, $sond['quant']) as $v) {
            if ($at_set == $v) {
                $outline = null;
            } else {
                $outline = 'outline-';
            }
            if ($v == $at_sonda) {
                $btn = 'primary';
            } elseif ($v > $at_sonda) {
                $btn = 'outline-secondary';
            } else {
                $btn = 'warning';
            }
            if ($v <= $at_sonda) {
                ?>
                <div class="col text-center">
                    <form method="POST">
                        <button class="btn btn-<?= $outline . $btn ?> rounded-button"><?= $v ?></button>
                        <?=
                        formErp::hidden([
                            'hlo' => $hlo,
                            'atual_letiva' => $dataFiltro['atual_letiva'],
                            'id_inst' => $id_inst,
                            'id_pessoa' => $id_pessoa,
                            'data' => $data,
                            'at_set' => $v,
                        ])
                        . formErp::hidden($hidden)
                        ?>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <div class="col text-center">
                    <button class="btn btn-<?= $btn ?> rounded-button"><?= $v ?></button>
                </div>
                <?php
            }
            ?>

            <?php
        }
        ?>
    </div>
    <br />
    <?php
} else {
    $at_sonda = $sond['at_sonda'];
    if (!$at_set) {
        $at_set = $at_sonda;
    }
}

/**
 *  if (in_array($id_curso, [3, 7, 8])) {
        $hab = sql::get(['coord_hab', 'coord_campo_experiencia'], 'coord_hab.id_hab, coord_hab.descricao, coord_hab.codigo, coord_campo_experiencia.n_ce', ['coord_hab.fk_id_gh' => $sond['fk_id_gh']]);
    }elseif (in_array($id_curso, [1,5,9])) {
        $hab = sql::get('coord_hab', 'id_hab, descricao, codigo', ['fk_id_gh' => $sond['fk_id_gh']]);
    }
 */