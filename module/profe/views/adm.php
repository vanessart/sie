<?php
if (!defined('ABSPATH'))
      exit;
  exit;

$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
if ($id_ciclo) {
    ini_set('memory_limit', '2000M');

    $id_pl = ng_main::periodoSet();
    $hab = $model->habilidades([$id_ciclo]);
    foreach ($hab as $v) {
        $disciplinas[$v['id_disc']] = $v['n_disc'];
        $h[$v['id_ciclo']][$v['id_disc']][$v['codigo']] = $v;
    }
    ksort($h);
    $habPlan = $model->habPlan();
    $contaTurmas = $model->contaTurmas([$id_ciclo]);
    $habDiario = $model->habDiario($id_pl);
}
?>

<div class="body">
    <div class="fieldTop">
        Controle das Habilidades Aplicadas
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::selectNum('id_ciclo', [1, 9, 'º Ano'], 'Ciclo', $id_ciclo, 1, ['id_disc' => $id_disc]) ?>
        </div>
        <div class="col">
            <?php
            if ($id_ciclo) {
                ?>
                <?= formErp::select('id_disc', $disciplinas, 'Disciplina', $id_disc, 1, ['id_ciclo' => $id_ciclo]) ?>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if ($id_ciclo) {
        foreach ($h as $id_ciclo => $disc) {
            ?>
            <div class="border" style="background-color: white">
                <div class="fieldTop">
                    <?= current(current($disc))['n_ciclo'] ?> (<?= $contaTurmas[$id_ciclo] ?> Turmas)
                </div>
                <br /><br />

                <?php
                foreach ($disc as $v) {
                    if (empty($id_disc) || $id_disc == current($v)['id_disc']) {
                        ?>
                        <table class="table border">
                            <tr>
                                <td colspan="2" style="width: 250px; text-align: center; font-weight: bold">
                                    <?= current($v)['n_disc'] ?>
                                </td>
                            </tr>
                            <?php
                            foreach ($v as $codigo => $y) {
                                if (!empty($habPlan[$y['id_hab']])) {
                                    $plan = round(( ($habPlan[$y['id_hab']] / $contaTurmas[$id_ciclo]) * 100), 2);
                                } else {
                                    $plan = 0;
                                }
                                if (!empty($habDiario[$y['id_hab']])) {
                                    $diario = round(( ($habDiario[$y['id_hab']] / $contaTurmas[$id_ciclo]) * 100), 2);
                                } else {
                                    $diario = 0;
                                }
                                ?>
                                <tr>
                                    <td style="width: 100px">
                                        <div style="width: 400px" class="alert alert-warning"  >
                                            <?= $codigo ?> - <?= $y['descricao'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <br />
                                        Lançado no Plano de aula
                                        <br /><br />
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $plan ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $plan ?>%</div>
                                        </div>
                                        <br />
                                        Lançado no Diário de Classe
                                        <br /><br />
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $diario ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $diario ?>%</div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php
                    }
                }
                ?>

            </div>
            <br /><br /><br />
            <?php
        }
    }
    ?>
</div>
