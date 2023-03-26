<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = toolErp::id_inst();
$turmas = turmas::optionNome(null, 1);
$disc = sql::idNome('ge_disciplinas');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    $turma = sql::get(['ge_turmas', 'ge_ciclos'], 'ge_turmas.*, ge_ciclos.fk_id_curso ', ['id_turma' => $id_turma], 'fetch');
    $id_pl = $turma['fk_id_pl'];
    $id_curso = $turma['fk_id_curso'];
    $d = sql::get('sed_letiva_data', 'atual_letiva id_let, dias n_let', ['fk_id_pl' => $id_pl, 'fk_id_curso' => $id_curso]);
    $dadas = toolErp::idName($d);
    $table = 'hab.aval_mf_' . $id_curso . '_' . $id_pl;
    $sql = "SELECT p.id_pessoa, p.n_pessoa, ta.chamada, tas.id_tas, tas.n_tas, a.* FROM $table a "
            . " join pessoa p on p.id_pessoa = a.fk_id_pessoa and a.media_9 is not null and a.media_6 is not null "
            . " join ge_turma_aluno ta on ta.fk_id_pessoa = a.fk_id_pessoa "
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_pl = $id_pl and t.id_turma = $id_turma "
            . " join ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas "
            . " order by ta.chamada";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($array) {
        if (in_array($turma['fk_id_ciclo'], [6, 7, 8, 9, 27, 28, 29, 30, 34])) {
            $width = '100%';
            $g = sql::get(['ge_aloca_disc', 'ge_disciplinas'], 'id_disc, n_disc, aulas ', ['fk_id_grade' => $turma['fk_id_grade']]);
            foreach ($g as $v) {
                $aulas[$v['id_disc']] = $v['aulas'];
                $grade[$v['id_disc']] = $v['n_disc'];
            }
            foreach ($array as $c) {
                $idsPessoa[$c['fk_id_pessoa']] = $c['fk_id_pessoa'];
                $faltas[$c['chamada']]['id_pessoa'] = $c['id_pessoa'];
                $faltas[$c['chamada']]['nome'] = $c['n_pessoa'];
                $faltas[$c['chamada']]['chamada'] = $c['chamada'];
                $faltas[$c['chamada']]['id_tas'] = $c['id_tas'];
                $faltas[$c['chamada']]['n_tas'] = $c['n_tas'];
                $bimestres[$c['atual_letiva']] = $c['atual_letiva'];
                foreach ($c as $k => $v) {
                    if (substr($k, 0, 6) == 'falta_') {
                        $faltas[$c['chamada']]['faltas'][$c['atual_letiva']][substr($k, 6)] = $v;
                    }
                }
            }
        } elseif (in_array($turma['fk_id_ciclo'], [1, 2, 3, 4, 5, 25, 26])) {
            $width = '800px';
            $grade = ['nc' => 'Núcleo Comum'];
            $aulas = ['nc' => 5];
            foreach ($array as $c) {
                $idsPessoa[$c['fk_id_pessoa']] = $c['fk_id_pessoa'];
                $faltas[$c['chamada']]['id_pessoa'] = $c['id_pessoa'];
                $bimestres[$c['atual_letiva']] = $c['atual_letiva'];
                $faltas[$c['chamada']]['nome'] = $c['n_pessoa'];
                $faltas[$c['chamada']]['chamada'] = $c['chamada'];
                $faltas[$c['chamada']]['id_tas'] = $c['id_tas'];
                $faltas[$c['chamada']]['n_tas'] = $c['n_tas'];
                $faltas[$c['chamada']]['faltas'][$c['atual_letiva']]['nc'] = $c['falta_nc'];
            }
        }
    }
    if (!empty($idsPessoa)) {
        $sql = "SELECT * FROM `profe_falta_just` "
                . " WHERE `fk_id_pessoa` IN (" . implode(', ', $idsPessoa) . ") "
                . " and fk_di_curso = $id_curso "
                . " and fk_id_pl = $id_pl ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            @$justificativas[$v['fk_id_pessoa']][$v['iddisc']][$v['atual_letiva']] += $v['faltas'];
        }
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Justificativa de Faltas
    </div>
    <div class="row">
        <div class="col-3">
            <?= formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1) ?>
        </div>
        <div class="col-9">
            <div class="alert alert-info" style="text-align: center; font-weight: bold">
                Para justificar, clique sobre o botão com as faltas
            </div>
        </div>
    </div>
    <br />

    <?php
    if ($id_turma && @$faltas) {
        ksort($faltas);
        foreach ($faltas as $v) {
            $idPessoa = $v['id_pessoa'];
            ?>
            <table class="table table-bordered table-hover table-responsive border" style="<?= $v['id_tas'] == 0 ? '' : 'background-color: wheat' ?>; width: <?= $width ?>; margin: auto">
                <tr>
                    <td>
                        Nº: <?= $v['chamada'] ?>
                    </td>
                    <td>
                        Nome: <?= $v['nome'] ?>
                    </td>
                    <td>
                        RSE: <?= $idPessoa ?>
                    </td>
                    <td>
                        Situação: <?= $v['n_tas'] ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table style="width: 100%">
                            <tr>
                                <?php
                                foreach ($grade as $idDisc => $nDisc) {
                                    ?>
                                    <td style="padding: 6px">
                                        <table style="width: 100%; text-align: center" class="border">
                                            <tr>
                                                <td colspan="<?= count($bimestres) ?>">
                                                    <p style="padding: 5px">
                                                        <?= $nDisc ?>
                                                    </p>
                                                    <?php
                                                    foreach (range(1, count($bimestres)) as $b) {
                                                        $aulasDadas = intval((($dadas[$b] / 5) * $aulas[$idDisc]) * 0.75);
                                                        if ($aulasDadas < intval(@$v['faltas'][$b][$idDisc])) {
                                                            $btn = 'danger';
                                                            $s = 's';
                                                            $onclick = 'onclick="alterar(' . $idPessoa . ', ' . $b . ', \'' . $idDisc . '\', ' . intval(@$v['faltas'][$b][$idDisc]) . ')"';
                                                        } elseif (intval(@$v['faltas'][$b][$idDisc] > 1)) {
                                                            $btn = 'success';
                                                            $s = 's';
                                                            $onclick = 'onclick="alterar(' . $idPessoa . ', ' . $b . ', \'' . $idDisc . '\', ' . intval(@$v['faltas'][$b][$idDisc]) . ')"';
                                                        } elseif (intval(@$v['faltas'][$b][$idDisc] > 0)) {
                                                            $btn = 'success';
                                                            $s = '';
                                                            $onclick = 'onclick="alterar(' . $idPessoa . ', ' . $b . ', \'' . $idDisc . '\', ' . intval(@$v['faltas'][$b][$idDisc]) . ')"';
                                                        } else {
                                                            $onclick = null;
                                                            $btn = 'secondary';
                                                            $s = '';
                                                        }
                                                        if (!empty($justificativas[$idPessoa][$idDisc][$b])) {
                                                            $onclick = 'onclick="alterar(' . $idPessoa . ', ' . $b . ', \'' . $idDisc . '\', ' . intval(@$v['faltas'][$b][$idDisc]) . ')"';
                                                            $btn = 'warning';
                                                        }
                                                        ?>
                                                        <div style="padding: 5px">
                                                            <button style="width: 100%" <?= $onclick ?> class="btn btn-<?= $btn ?>">
                                                                <?= $b ?>º Bim.    <?= intval(@$v['faltas'][$b][$idDisc]) . ' Falta' . $s ?>
                                                                <?php
                                                                if (!empty($justificativas[$idPessoa][$idDisc][$b])) {
                                                                    $j = $justificativas[$idPessoa][$idDisc][$b];
                                                                    echo '<br>(' . (intval(@$v['faltas'][$b][$idDisc]) + $j) . ' menos ' . $j . ' Justificada' . ($j > 1 ? 's' : '') . ')';
                                                                }
                                                                ?>
                                                            </button>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
            <br /><br />
            <?php
        }
    } else {
        ?>
        <div class="alert alert-warning">
            Esta funcionalidade será disponíbilizada a partir do segundo bimestre.
        </div>
        <?php
    }
    if (!empty($id_curso)) {
        ?>
    </div>
    <form action="<?= HOME_URI ?>/profe/def/formJust.php" id="formJust" target="frame" method="POST">
        <input type="hidden" name="id_pessoa" id="id_pessoa" />
        <input type="hidden" name="atualLetiva" id="atualLetiva" />
        <input type="hidden" name="id_disc" id="id_disc" />
        <input type="hidden" name="faltasTotal" id="faltasTotal" />
        <?=
        formErp::hidden([
            'id_turma' => $id_turma,
            'id_pl' => $turma['fk_id_pl'],
            'id_curso' => $id_curso
        ])
        ?>
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    <script>
        function alterar(idPessoa, bim, idDisc, faltas) {
            id_pessoa.value = idPessoa;
            atualLetiva.value = bim;
            id_disc.value = idDisc;
            faltasTotal.value = faltas;
            formJust.submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    </script>
    <?php
}    