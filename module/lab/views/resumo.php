<?php
if (!defined('ABSPATH'))
    exit;
$destino = sql::idNome('lab_chrome_destino');
$destino[0] = 'Indefinido';
$par['limit'] = 0;
$par['tuplas'] = 100000;
$fields = " distinct c.id_ch, c.serial, c.fk_id_inst, p.n_pessoa, s.id_cs, p.id_pessoa, fk_id_ms ";
$instancia = sql::idNome('instancia');
$resumo = $model->chromeRede($par, $fields);
if ($resumo) {
    if ($resumoTp == 1) {
        foreach ($resumo as $v) {

            if (in_array($v['fk_id_ms'], [1, 2, 3])) {
                @$dest[4]++;
                $v['btn'] = 4;
            } elseif (!empty($v['id_pessoa'])) {
                @$dest[3]++;
                $v['btn'] = 3;
            } elseif ($v['id_cs'] == 0) {
                @$dest[0]++;
                $v['btn'] = 0;
            } else {
                @$dest[1]++;
                $v['btn'] = 1;
            }
            @$local[$v['fk_id_inst']][] = $v;
        }
    } else {
           foreach ($resumo as $v) {

            if (in_array($v['fk_id_ms'], [1, 2, 3])) {
                @$dest[4]++;
                $v['btn'] = 4;
            } elseif ($v['fk_id_ms'] == 4) {
                @$dest[7]++;
                $v['btn'] = 7;
            } elseif ($v['fk_id_ms'] == 6) {
                @$dest[6]++;
                $v['btn'] = 6;
            } elseif (!empty($v['id_pessoa'])) {
                @$dest[2]++;
                $v['btn'] = 2;
            } elseif ($v['id_cs'] == 0) {
                @$dest[0]++;
                $v['btn'] = 0;
            } else {
                @$dest[5]++;
                $v['btn'] = 5;
            }
            @$local[$v['fk_id_inst']][] = $v;
        }
    }
    $btn = [
        0 => 'outline-success',
        1 => 'success',
        2 => 'warning',
        3 => 'info',
        4 => 'secondary',
        5 => 'primary',
        6 => 'danger',
        7 => 'dark',
        8 => 'outline-danger',
        9 => 'outline-primary',
        10 => 'outline-warning',
    ];
    ?>
    <div class="border">
        <div class="row">
            <?php
            $mostra = [
                1 => [0, 1, 3, 4],
                2 => [5, 2, 4, 6, 7]
            ];
            foreach ($mostra[$resumoTp] as $v) {
                if ($v == 0) {
                    $hidden = ['situacao' => 'x'];
                } else {
                    $hidden = ['destino' => $v];
                }
                $hidden['resumoTp'] = $resumoTp;
                $hidden['activeNav'] = $resumoTp;
                ?>
                <div class="col" style="max-width: 400px; padding: 4px">
                    <form method="POST">
                        <?= formErp::hidden($hidden) ?>
                        <button type="submit" class="btn btn-<?= $btn[$v] ?>" style="width: 100%">
                            <?= $destino[$v] ?> (<?= intval(@$dest[$v]) ?>)
                        </button>
                    </form>
                </div>
                <?php
            }
            if ($_POST['activeNav'] == 2) {
                $hidden['situacao'] = 'y';
                $hidden['destino'] = 5;
                $hidden['resumoTp'] = $resumoTp;
                $hidden['activeNav'] = $resumoTp;
                ?>
                <div class="col" style="max-width: 400px; padding: 4px">
                    <form method="POST">
                        <?= formErp::hidden($hidden) ?>
                        <button type="submit" class="btn btn-outline-primary" style="width: 100%">
                            <?= $destino[0] ?> (<?= intval(@$dest[0]) ?>)
                        </button>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($local)) {
        ksort($local);
        if (!empty($local[13])) {
            $se = $local[13];
            unset($local[13]);
            $local[13] = $se;
        }
        foreach ($local as $k => $v) {
            ?>
            <div class="border">
                <?= @$instancia[$k]; ?>
                <br /><br />
                <?php
                foreach ($v as $c) {
                    ?>
                    <div style="float: left; width: 34px; padding: 2px">
                        <button onclick="ch(<?= $c['id_ch'] ?>)" class="btn btn-<?= $btn[$c['btn']] ?>" style="width: 10px" data-toggle="tooltip" data-placement="top" title="N/S: <?php echo $c['serial'] ?>" >

                        </button>
                    </div>
                    <?php
                }
                ?>
                <div style="clear: left"></div>
            </div>
            <br /><br />
            <?php
        }
    }
} else {
    ?>
    <br /><br />
    <div class="alert alert-danger" style="text-align: center; width:  300px; margin: 0 auto">
        Não Encontrado
    </div>
    <?php
}