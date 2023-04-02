<?php
if (!defined('ABSPATH'))
    exit;
$turma = $model->turmas(toolErp::id_inst());
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_UNSAFE_RAW);
$ano = filter_input(INPUT_POST, 'ano', FILTER_UNSAFE_RAW);
$id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_UNSAFE_RAW);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_UNSAFE_RAW);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_UNSAFE_RAW);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_UNSAFE_RAW);
if ($id_grade) {
    $grade = $model->grade($id_grade);
}
?>

<style>
    .centro {
        margin: 0 auto;
        max-width: 800px;
    }
</style>

<div class="body centro">
    <div class="fieldTop">
        Relatório de Habilidades <?= date('Y') ?>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <form method="post" id="id_grade">
                <select class="form-control" name="id_grade" onchange="this.options[this.selectedIndex].onclick()">
                    <option value="">Selecione a Turma</option>

                    <?php foreach ($turma as $key => $value) { ?>
                        <option onclick="mandaDados('<?= $value['id_turma'] ?>', '<?= $value['id_pl'] ?>', '<?= $value['n_turma'] ?>', '<?= $value['periodo'] ?>', '<?= $value['ano'] ?>', '<?= $value['fk_id_ciclo']?>')" value="<?= $value['fk_id_grade'] ?>"><?= $value['n_turma'] ?></option>
                    <?php } ?>

                </select>
                <input type="hidden" name="id_turma" id="id_turma">
                <input type="hidden" name="id_ciclo" id="id_ciclo">
                <input type="hidden" name="id_pl" id="id_pl">
                <input type="hidden" name="n_turma" id="n_turma">
                <input type="hidden" name="periodo" id="periodo">
                <input type="hidden" name="ano" id="ano">



            </form>
        </div>
    </div>
    <br />
    
    <!-- TABELA DE GRADE -->
    <?php if (!empty($id_grade)) { ?>
        <table class="table table-bordered table-hover table-striped shadow-lg p-3">
            <form action="<?= HOME_URI ?>/profe/habTabela" method="post">
                <?php foreach ($grade as $id_disc => $n_disc) { ?>
                    <tr>
                        <td><?= $n_disc ?></td>

                        <td style="width: 40px">
                            <input type="hidden" name="id_disc" value="<?= $id_disc ?>">
                            <input type="hidden" name="id_pl" value="<?= $id_pl ?>">
                            <input type="hidden" name="id_turma" value="<?= $id_turma ?>">
                            <input type="hidden" name="n_turma" value="<?= $n_turma ?>">
                            <input type="hidden" name="n_disc" value="<?= $n_disc ?>">
                            <input type="hidden" name="ano" value="<?= $ano ?>">
                            <input type="hidden" name="id_ciclo" value="<?= $id_ciclo ?>">

                            <?= formErp::submit('Acessar') ?>

                        </td>
                    </tr>

                <?php } ?>
            </form>
        </table>
    <?php } ?>
</div>

<script>
    function mandaDados(id, idPl, nome, periodoS, anoS, idCicloS) {
        id_turma.value = id
        id_pl.value = idPl
        n_turma.value = nome
        periodo.value = periodoS
        ano.value = anoS
        id_ciclo.value = idCicloS

        id_grade.submit()
    }
</script>