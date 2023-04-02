<?php
if (!defined('ABSPATH'))
    exit;
$turma = $model->turmas(toolErp::id_inst());
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_UNSAFE_RAW);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_UNSAFE_RAW);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_UNSAFE_RAW);
$id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_UNSAFE_RAW);

$hidden = [
    'id_pl' => $id_pl,
    'id_turma' => $id_turma,
    'n_turma' => $n_turma,
    'n_disc' => $n_disc,
    
];

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
        Relatório de chamada <?= date('Y') ?>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <form method="post" id="id_grade">
                <select class="form-control" name="id_grade" onchange="this.options[this.selectedIndex].onclick()">
                    <option value="">Selecione a Turma</option>

                    <?php foreach ($turma as $key => $value) { ?>
                        <option onclick="mandaDados('<?= $value['id_turma'] ?>', '<?= $value['id_pl'] ?>', '<?= $value['n_turma'] ?>', '<?= $value['periodo'] ?>', '<?= $value['ano'] ?>')" value="<?= $value['fk_id_grade'] ?>"><?= $value['n_turma'] ?></option>
                    <?php } ?>

                </select>
                <input type="hidden" name="id_turma" id="id_turma">
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
            <form action="<?= HOME_URI ?>/profe/notaTabela" method="post">
                <?php foreach ($grade as $id_disc => $n_disc) { ?>
                    <tr>
                        <td><?= $n_disc ?></td>

                        <td style="width: 40px">
                            <?= formErp::hidden($hidden)?>
                            <input type="hidden" name="n_disc" value="<?= $n_disc ?>">

                            <?= formErp::submit('Acessar') ?>

                        </td>
                    </tr>

                <?php } ?>
            </form>
        </table>
    <?php } ?>
</div>

<script>
    function mandaDados(id, idPl, nome, periodoS, anoS) {
        id_turma.value = id
        id_pl.value = idPl
        n_turma.value = nome
        periodo.value = periodoS
        ano.value = anoS

        id_grade.submit()
    }
</script>