<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_poloSet = filter_input(INPUT_POST, 'id_poloSet', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_UNSAFE_RAW);
$periodoSet = filter_input(INPUT_POST, 'periodoSet', FILTER_UNSAFE_RAW);
$id_mc = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
$id_as = filter_input(INPUT_POST, 'id_as ', FILTER_SANITIZE_NUMBER_INT);
$diaSem = filter_input(INPUT_POST, 'diaSem', FILTER_SANITIZE_NUMBER_INT);
$polos = sql::idNome('maker_polo');
$id_ma = filter_input(INPUT_POST, 'id_ma', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_poloSet)) {
    $id_poloSet = $id_polo;
}

if ($id_ma) {
    $alu = $model->candidato($id_ma);
    if (empty($periodoSet)) {
        $periodoSet = $alu['periodo'];
    }
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
                    <?= data::converteBr($alu['dt_nasc']) ?>
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
            <tr>
                <td>
                    Situação
                </td>
                <td>
                    <?= $alu['n_as'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Escola
                </td>
                <td>
                    <?= $alu['n_inst'] ?>
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
                    'id_polo' => $id_polo,
                    'periodo' => $periodo,
                    'id_mc' => $id_mc,
                    'id_as' => $id_as,
                    'diaSem' => $diaSem,
                    'id_ma' => $id_ma
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
                    'id_as' => $id_as,
                    'diaSem' => $diaSem,
                    'id_poloSet' => $id_poloSet,
                    'periodoSet' => $periodoSet,
                    '1[fk_id_pessoa]' => $alu['id_pessoa'],
                    '1[fk_id_sit]' => '0',
                    '1[dt_matricula]' => date("Y-m-d"),
                    'id_inst_sieb' => $alu['id_inst']
                    ])
                    ?>
                    <div class="row">
                        <div class="col">
                            <?php
                            $turmas = $model->turmasPolo($id_poloSet, $periodoSet);
                            if ($turmas) {
                                echo formErp::select('1[fk_id_turma]', $turmas, 'Turma');
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
