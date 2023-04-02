<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_poloSet = filter_input(INPUT_POST, 'id_poloSet', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_UNSAFE_RAW);
$periodoSet = filter_input(INPUT_POST, 'periodoSet', FILTER_UNSAFE_RAW);
$id_mc = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
$diaSem = filter_input(INPUT_POST, 'diaSem', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_ta = filter_input(INPUT_POST, 'id_ta', FILTER_SANITIZE_NUMBER_INT);
$polos = sql::idNome('maker_polo');
$escolas = $model->escolasMaker();

if (empty($id_poloSet)) {
    $id_poloSet = $id_polo;
}

if ($id_ta) {
    $alu = $model->alunoTurmaNg($id_ta);
    if (empty($periodoSet)) {
        $periodoSet = $alu['periodo'];
    }
    $escOr = $model->escolaAlunofrequ($alu['id_pessoa']);
}
?>
<div class="body">
    <?php
    if (!empty($alu)) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    RSE
                </td>
                <td>
                    <?= $alu['id_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Dt. Nasc
                </td>
                <td>
                    <?= $alu['dt_nasc'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    <?= $alu['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Ciclo
                </td>
                <td>
                    <?= $alu['n_mc'] ?>
                </td>
            </tr>
            <?php
            if (!empty($escOr['n_inst'])) {
                ?>
                <tr>
                    <td>
                        Escola de Origem
                    </td>
                    <td>
                        <?= $escOr['n_inst'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td>
                    Turma
                </td>
                <td>
                    <?= $alu['n_turma'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Período
                </td>
                <td>
                    <?= $alu['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                </td>
            </tr>
            <tr>
                <td>
                    Polo
                </td>
                <td>
                    <?= $alu['n_polo'] ?>
                </td>
            </tr>
            </tr>
        </table>
        <br />
        <div class="border">
            <form method="POST">
                <div class="row">
                    <div class="col">
                        <?= formErp::select('id_poloSet', $polos, 'Polo', $id_poloSet) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::select('periodoSet', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período', $periodoSet) ?>
                    </div>
                    <div class="col">
                        <button class="btn btn-info">
                            Trocar
                        </button>
                    </div>
                </div>
                <br />
                <?=
                formErp::hidden([
                    'id_pessoa' => $alu['id_pessoa'],
                    'id_polo' => $id_polo,
                    'periodo' => $periodo,
                    'id_mc' => $id_mc,
                    'diaSem' => $diaSem,
                    'id_turma' => $id_turma,
                    'id_ta' => $id_ta
                ])
                ?>

            </form>
        </div>
        <br />
        <?php
        if ($id_poloSet) {
            $fk_id_inst_maker = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
            ?>
            <div class="border">
                <form action="<?= HOME_URI ?>/maker/alunoGest" target="_parent" method="POST">
                    <?=
                    formErp::hidden([
                        'id_pessoa' => $alu['id_pessoa'],
                        'id_polo' => $id_polo,
                        'periodo' => $periodo,
                        'id_mc' => $id_mc,
                        'diaSem' => $diaSem,
                        'id_turma' => $id_turma,
                        'id_ta' => $id_ta,
                        'id_poloSet' => $id_poloSet,
                        'periodoSet' => $periodoSet,
                        '1[id_ta]' => $id_ta,
                    ])
                    ?>
                    <div class="row">
                        <div class="col">
                            <?php
                            $turmas = $model->turmasPolo($id_poloSet, $periodoSet);
                            if ($turmas) {
                                echo formErp::select('1[fk_id_turma]', $turmas, 'Turma', $id_turma);
                            }
                            ?>
                        </div>
                        <div class="col">
                            <?=
                            formErp::hiddenToken('transferirAluno')
                            . formErp::button('Salvar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
            </div>
            <?php
        }
    }
    ?>
</div>
