<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$plAtivos = ng_main::periodosAtivos();

$periodos = $model->plsValido();
$turmas = $model->turmas($id_inst, $id_pl)
?>
<div class="body">
    <div class="fieldTop">
        Atas de Classe
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
    if ($turmas) {
        foreach ($turmas as $id_turma => $v) {
            $n_turma = $v['n_turma']
            ?>
            <div class="border">
                <div class="row">
                    <div class="col" style="text-align: center; font-weight: bold; font-size: 1.4em; padding-top: 5px; padding-bottom: 15px">
                        <?= $n_turma ?> 
                    </div>
                    <div class="col">
                        <form action="<?= HOME_URI ?>/avalia/ataEdit" method="POST">
                            <?=
                            formErp::hidden([
                                'id_turma' => $id_turma,
                            ])
                            ?>
                            <button class="btn btn-dark">
                                Abrir Atas
                            </button>
                        </form>
                    </div>
                    <?php
                    foreach (range(1, ($v['qt_letiva'] + 1)) as $b) {
                        if ($v['atual_letiva'] >= $b || !in_array($id_pl, $plAtivos) || ($v['atual_letiva'] == $v['qt_letiva'])) {
                            $outline = null;
                            $type = "submit";
                        } else {
                            $outline = '-outline';
                            $type = "button";
                        }
                        ?>
                        <div class="col">
                            <?php
                            if ($v['qt_letiva'] >= $b) {
                                ?>
                                <form target="_blank" action="<?= HOME_URI ?>/avalia/pdf/ataBim" method="POST">
                                    <?=
                                    formErp::hidden([
                                        'id_inst' => $id_inst,
                                        'id_turma' => $id_turma,
                                        'id_pl' => $id_pl,
                                        'bim' => $b
                                    ])
                                    ?>
                                    <button type="<?= $type ?>" class="btn btn<?= $outline ?>-info">
                                        <?= $b ?>º Bimestre
                                    </button>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form target="_blank" action="<?= HOME_URI ?>/avalia/pdf/ataBimFinal" method="POST">
                                    <?=
                                    formErp::hidden([
                                        'id_inst' => $id_inst,
                                        'id_turma' => $id_turma,
                                        'id_pl' => $id_pl,
                                    ])
                                    ?>
                                    <button type="<?= $type ?>" class="btn btn<?= $outline ?>-primary">
                                        Ata Final
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
            <br /><br />
            <?php
        }
    }
    ?>
</div>