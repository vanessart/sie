<?php
if (!defined('ABSPATH'))
    exit;
ini_set('memory_limit', '20000M');
if (toolErp::id_nilvel() == 55) {
    $id_instSet = toolErp::id_inst();
    $instSql = " AND t.fk_id_inst = $id_instSet ";
} elseif (toolErp::id_nilvel() == 22) {
    $escolasSupervisor = ng_escolas::idEscolasSupervisor(toolErp::id_pessoa());
    if (empty($escolasSupervisor)) {
        toolErp::divAlert("info", "Não há escolas habilitadas para este supervisor");
        exit();
    }

    $id_instSet = array_keys($escolasSupervisor);
    $instSql = " AND t.fk_id_inst IN( ".implode(',', $id_instSet)." ) ";

} elseif ( toolErp::id_nilvel() != 54) {
    exit();
} else {
    $id_instSet = null;
    $instSql = null;
}

$disc = sql::idNome('ge_disciplinas');
$id_pl = ng_main::periodoSet();
$bim = sql::get('ge_cursos', 'atual_letiva', ['id_curso' => 1], 'fetch')['atual_letiva'];
foreach (range(1, $bim) as $v) {
    $let[] = $v;
}
$letivas = implode(', ', $let);
$sql = "SELECT SUM(dias) tdias FROM `sed_letiva_data` WHERE fk_id_pl = $id_pl AND atual_letiva in ($letivas) AND fk_id_curso = 1 ";
$query = pdoSis::getInstance()->query($sql);
$tdias = $query->fetch(PDO::FETCH_ASSOC)['tdias'];
$podeFaltar = $tdias / 4;
$sql = "SELECT p.n_pessoa, t.id_turma, t.n_turma,  a.fk_id_ciclo, a.fk_id_pessoa, a.atual_letiva, ci.n_ciclo, t.fk_id_inst, "
        . " SUM(a.falta_nc) faltas "
        . " FROM hab.aval_mf_1_$id_pl a "
        . " join pessoa p on p.id_pessoa = a.fk_id_pessoa "
        . " join ge_turma_aluno ta on ta.fk_id_turma = a.fk_id_turma and a.fk_id_pessoa = ta.fk_id_pessoa and ta.fk_id_tas = 0 "
        . " JOIN ge2.ge_turmas t on t.id_turma = a.fk_id_turma and a.fk_id_ciclo in (1, 2, 3, 4, 5) $instSql "
        . " JOIN ge2.ge_ciclos ci on ci.id_ciclo = a.fk_id_ciclo "
        . " GROUP by t.id_turma, a.fk_id_ciclo, a.fk_id_pessoa "
        . " HAVING faltas > $podeFaltar ";
$query = pdoSis::getInstance()->query($sql);
$peb1 = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT fk_id_disc, aulas FROM `ge_aloca_disc` WHERE `fk_id_grade` = 1 ORDER BY `ge_aloca_disc`.`fk_id_disc` ASC ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    if (empty($where)) {
        $or = 'HAVING';
        $where = 1;
    } else {
        $or = 'OR';
    }
    $campos[] = " SUM(a.falta_" . $v['fk_id_disc'] . ") faltas_" . $v['fk_id_disc'];
    $podeFaltarDisc[] = " $or faltas_" . $v['fk_id_disc'] . " > " . intval((($tdias / 5) * $v['aulas']) / 4);
}

$sql = "SELECT p.n_pessoa, t.id_turma, t.n_turma, a.fk_id_ciclo, a.fk_id_pessoa, ci.n_ciclo, t.fk_id_inst, "
        . implode(', ', $campos)
        . " FROM hab.aval_mf_1_$id_pl a "
        . " join ge_turma_aluno ta on ta.fk_id_turma = a.fk_id_turma and a.fk_id_pessoa = ta.fk_id_pessoa and ta.fk_id_tas = 0 "
        . " join pessoa p on p.id_pessoa = a.fk_id_pessoa "
        . " JOIN ge2.ge_turmas t on t.id_turma = a.fk_id_turma and a.fk_id_ciclo in (6, 7, 8, 9) $instSql "
        . " JOIN ge2.ge_ciclos ci on ci.id_ciclo = a.fk_id_ciclo "
        . " GROUP by t.id_turma, a.fk_id_ciclo, a.fk_id_pessoa ";
$sql .= implode('', $podeFaltarDisc);
$query = pdoSis::getInstance()->query($sql);

$peb2 = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($peb2 as $v) {
    $df = [];
    foreach ($array as $y) {
        if (!empty($v['faltas_' . $y['fk_id_disc']])) {
            if ($v['faltas_' . $y['fk_id_disc']] > intval((($tdias / 5) * $y['aulas']) / 4)) {
                $df[$y['fk_id_disc']]['faltas'] = $v['faltas_' . $y['fk_id_disc']];
                $df[$y['fk_id_disc']]['aulas'] = intval(($tdias / 5) * $y['aulas']);
                $df[$y['fk_id_disc']]['porc'] = intval($v['faltas_' . $y['fk_id_disc']] / intval(($tdias / 5) * $y['aulas']) * 100);
            }
        }
    }
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc'] = $df;
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['turma'] = [$v['id_turma'], $v['n_turma']];
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['nome'] = $v['n_pessoa'];
}

foreach ($peb1 as $v) {

    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['turma'] = [$v['id_turma'], $v['n_turma']];
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc']['nc']['faltas'] = $v['faltas'];
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc']['nc']['aulas'] = $tdias;
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc']['nc']['porc'] = intval(($v['faltas'] / $tdias) * 100);
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['nome'] = $v['n_pessoa'];
}
if (empty($id_instSet)) {
    $escolas = ng_escolas::idEscolas([1]);
} else {
    $escolas = sqlErp::idNome('instancia', ['id_inst' => $id_instSet]);
}
?>
<div class="body">
    <div class="fieldTop">
        <?php if (toolErp::id_nilvel() != 22) { ?>
        <div class="row">
            <div class="col-10">
                <div class="border">
                    <form action="<?= HOME_URI ?>/profe/pdf/faltas.php" target="_blank" method="POST">
                        <div class="row">
                            <div class="col">
                                <?= formErp::select('id_inst', $escolas, 'Escola') ?>
                            </div>
                            <div class="col">
                                <button class="btn btn-success" type="submit">
                                    Gerar PDF
                                </button>
                            </div>
                        </div>
                    </form>   
                </div>
            </div>
            <div class="col-2">
                <form action="<?= HOME_URI ?>/profe/pdf/relatFaltas.php" target="_blank" method="POST">
                    <button class="btn btn-primary" type="submit">
                        Exportar Planilha
                    </button>
                </form>
            </div>
        </div>
        <br />
        <?php
        }

        $id = 1;
        foreach ($escolas as $id_inst => $n_inst) {
            if (!empty($faltas[$id_inst])) {
                ?>
                <table class="table table-bordered table-hover table-responsive border">
                    <tr>
                        <td colspan="3">
                            <div class="row">
                                <div class="col-9" style="text-align: center">
                                    <?= $n_inst ?>
                                </div>
                                <div class="col-3">
                                    <form action="<?= HOME_URI ?>/profe/pdf/faltas.php" target="_blank" method="POST">
                                        <?= formErp::hidden(['id_inst'=>$id_inst]) ?>
                                        <button class="btn btn-success" type="submit">
                                            Gerar PDF
                                        </button>
                                    </form> 
                                </div>
                            </div>

                        </td>
                    </tr>
                    <?php
                    ksort($faltas[$id_inst]);
                    foreach ($faltas[$id_inst] as $n_ciclo => $a) {
                        ?>
                        <tr>
                            <td style="width: 100px; text-align: left">
                                <?= $n_ciclo ?>
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= count($a) * 5 ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"><?= count($a) ?></div>
                                </div>
                            </td>
                            <td style="width: 100px; text-align: left">
                                <button onclick="verAlu(<?= $id ?>)" class="btn btn-info">
                                    Alunos
                                </button>
                            </td>
                        </tr>
                        <tr class="alunos" id="<?= $id ?>" style="display: none">
                            <td colspan="3">
                                <?php
                                foreach ($a as $id_pessoa => $d) {
                                    ?>
                                    <div style="text-align: left;" class="alert alert-info">
                                        <p>
                                            <?= $d['nome'] ?> RSE: <?= $id_pessoa ?> Turma: <?= $d['turma'][1] ?>
                                        </p>
                                        <?php
                                        foreach ($d['disc'] as $id_disc => $dc) {
                                            ?>
                                            <table  onclick="consolidado('<?= $d['turma'][0] ?>', '<?= $id_disc ?>', '<?= $id_disc == 'nc' ? 'Núcleo Comum' : $disc[$id_disc] ?>', '<?= $n_inst ?>')" style="width: 100%; cursor: pointer">
                                                <tr>
                                                    <td style="width: 300px">
                                                        <?= $id_disc == 'nc' ? 'Núcleo Comum' : $disc[$id_disc] ?>: Faltou <?= $dc['faltas'] ?> das <?= $dc['aulas'] ?> aulas
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $dc['porc'] ?>%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><?= $dc['porc'] ?></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $id++;
                    }
                    ?>
                </table>
                <br /><br />
                <?php
            }
        }
        ?>  
    </div>
</div>

<form action="<?= HOME_URI ?>/profe/consolidado" target="frame" id="cons" method="POST">
    <?=
    formErp::hidden([
        'id_curso' => 1,
        'id_pl' => $id_pl
    ])
    ?>
    <input type="hidden" name="n_disc" id="n_disc" value="" />
    <input type="hidden" name="id_turma" id="id_turma" value="" />
    <input type="hidden" name="id_disc" id="id_disc" value="" />
    <input type="hidden" name="n_inst" id="n_inst" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    /**
     function consolidado(idTurma, idDisc, nDisc, nInst) {
     n_disc.value = nDisc;
     id_turma.value = idTurma;
     id_disc.value = idDisc;
     n_inst.value = nInst;
     cons.submit();
     $('#myModal').modal('show');
     $('.form-class').val('');
     }
     * 
     * @param {type} id
     * @returns {undefined}
     */
    function verAlu(id) {
        $(".alunos").hide();
        document.getElementById(id).style.display = '';
    }
</script>