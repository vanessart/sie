<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst_ = toolErp::id_inst();
} else {
    $id_inst_ = filter_input(INPUT_POST, 'id_inst_', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
if ($id_inst_) {
    $sql = "SELECT "
            . " p.fk_id_inst_maker, n_polo "
            . " FROM maker_escola e "
            . " JOIN maker_polo p on p.id_polo = e.fk_id_polo "
            . " WHERE fk_id_inst = $id_inst_ ";
    $query = pdoSis::getInstance()->query($sql);
    $polo = $query->fetch(PDO::FETCH_ASSOC);
    $id_inst = $polo['fk_id_inst_maker'];
    $nPolo = $polo['n_polo'];
}
?>
<div class="body">
    <div class="fieldTop">
        Lista de Parede
    </div>
    <div class="row">
        <?php
        if (toolErp::id_nilvel() != 8) {
            ?>
            <div class="col">
                <?= formErp::select('id_inst_', $escolas, 'Escola', $id_inst_, 1) ?>
            </div>
            <?php
        }
        ?>

        <?php
        if (!empty($id_inst)) {
            ?>
            <div class="col-2">
                <form action="<?= HOME_URI ?>/maker/pdf/alunoEscPlanPolo" target="_blank" method="POST">
                    <?= formErp::hidden(['id_inst' => $id_inst, 'n_polo' => $nPolo]) ?>
                    <button style="width: 200px" class="btn btn-warning">
                        Todas as Turmas
                    </button>
                </form>
            </div>
        <div class="col" style="font-weight: bold; font-size: 1.3em; text-align: center">
                Polo: <?= $nPolo ?>
            </div>
            <?php
        }
        ?>
    </div>
    <br />
    <?php
    if (!empty($id_inst)) {
        $id_pl = $model->setup();
        $turmas = sql::get('maker_gt_turma', 'n_turma, id_turma', ['fk_id_inst' => $id_inst, '>' => 'n_turma', 'fk_id_pl'=>$id_pl]);
        ?>
        <div class="row">
            <?php
            $c = 0;
            foreach ($turmas as $v) {
                ?>
                <div class="col-3 text-center">
                    <form action="<?= HOME_URI ?>/maker/pdf/alunoEscPlan" target="_blank" method="POST">
                        <?= formErp::hidden(['id_turma' => $v['id_turma'], 'n_turma' => $v['n_turma'], 'n_polo' => $nPolo]) ?>
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
