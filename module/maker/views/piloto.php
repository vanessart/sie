<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$polos_ = sql::get('maker_polo', 'n_polo, fk_id_inst_maker ', ['>' => 'id_polo']);
foreach ($polos_ as $v) {
    $polos[$v['fk_id_inst_maker']] = $v['n_polo'];
}
?>
<div class="body">
    <div class="fieldTop">
        Lista Piloto
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_inst', $polos, 'Polo', $id_inst, 1) ?>
        </div>
        <div class="col">
            <?php
            if ($id_inst) {
                ?>
                <form action="<?= HOME_URI ?>/maker/pdf/pilotoPoloPdf" target="_blank" method="POST">
                    <?= formErp::hidden(['id_inst' => $id_inst, 'n_polo' => $polos[$id_inst]]) ?>
                    <button style="width: 200px" class="btn btn-warning">
                        Todas as Turmas
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if ($id_inst) {
        $id_pl = $model->setup();
        $turmas = sql::get('maker_gt_turma', 'n_turma, id_turma', ['fk_id_inst' => $id_inst, '>' => 'n_turma', 'fk_id_pl' => $id_pl]);
        ?>
        <div class="row">
            <?php
            $c = 0;
            foreach ($turmas as $v) {
                ?>
                <div class="col-3 text-center">
                    <form action="<?= HOME_URI ?>/maker/pdf/pilotoPdf" target="_blank" method="POST">
                        <?= formErp::hidden(['id_turma' => $v['id_turma'], 'n_turma' => $v['n_turma'], 'n_polo' => $polos[$id_inst]]) ?>
                        <button style="width: 200px" class="btn btn-primary">
                            <?= $v['n_turma'] ?>
                        </button>
                    </form>
                </div>
                <?php
                $c++;
                if ($c % 4 == 0) {
                    ?>
                </div>
                <br />
                <div class="row">
                    <?php
                }
            }
            ?>
        </div>
        <br />
        <?php
    }
    ?>
</div>
