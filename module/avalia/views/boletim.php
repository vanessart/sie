<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$plAtivos = ng_main::periodosAtivos();
$ciclos = sql::idNome('ge_ciclos');
$periodos = $model->plsValido();
$tur = $model->turmas($id_inst, $id_pl);
if ($tur) {
    $bimestre = current($tur)['atual_letiva'] . 'º ' . current($tur)['un_letiva'];
    foreach ($tur as $v) {
        $turmas[$v['id_ciclo']][] = $v;
        @$turQt[$v['id_ciclo']]++;
    }
    arsort($turQt);
    $colunas = current($turQt);
} else {
    $turmas = [];
}
?>
<div class="body">
    <div class="fieldTop" style="font-size: 1.6em">
        Boletins <?= (in_array($id_pl, $plAtivos) && !empty($bimestre))? '- '.$bimestre:'' ?>
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1) ?>
        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
    <br />

    <?php
    foreach ($turmas as $id_ciclo => $ar) {
        ?>
        <div class="border" style="margin-bottom: 40px">
            <div style="text-align: center; font-weight: bold; font-size: 1.3em; padding-top: 5px; padding-bottom: 20px">
                <?= $ciclos[$id_ciclo] ?>
            </div>
            <div class="row">
                <?php
                foreach (range(0, ($colunas - 1)) as $col) {
                    ?>
                    <div class="col">
                        <?php
                        if (!empty($ar[$col])) {
                            $y = $ar[$col];
                            ?>
                            <form target="_blank" action="<?= HOME_URI ?>/avalia/pdf/boletim" method="POST">
                                <?= formErp::hidden($y) ?>
                                <button class="btn btn-primary">
                                    <?= $y['n_turma'] ?>
                                </button>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>

        <?php
    }
    ?>
    <br />
</div>