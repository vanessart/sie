<?php
if (!defined('ABSPATH'))
    exit;
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_tp_ens = filter_input(INPUT_POST, 'id_tp_ens', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/sed/ensino" method="POST">
        <div class="row">
            <div class="col-sm-6">
                <?php echo formErp::selectDB('ge_grades', '1[fk_id_grade]', 'Grade') ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::select('1[padrao]', [0 => 'Não', 1 => 'Sim'], 'Grade Padrão') ?>
            </div>
            <div class="col-sm-3 text-center">
                <?=
                formErp::hiddenToken('cicloGradeSalva')
                . formErp::hidden([
                    '1[fk_id_ciclo]' => $id_ciclo,
                    'activeNav' => 4,
                    'id_ciclo' => $id_ciclo,
                    'id_curso' => $id_curso,
                    'id_tp_ens' => $id_tp_ens
                ])
                ?>
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </div>
    </form>
</div>
