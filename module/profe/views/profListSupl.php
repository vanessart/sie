<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = $periodo = filter_input(INPUT_POST, 'id_inst');
if ($id_inst) {
    $inst = " and a.fk_id_inst = $id_inst";
} else {
    $inst = null;
}
$disc = sql::idNome('ge_disciplinas');
$disc['nc'] = 'Polevalente';
$ciclos = [35, 36, 37, 31, 25, 26, 19, 20, 21, 22, 23, 24, 1, 2, 32];
$ciclos = " and t.fk_id_ciclo in (" . implode(', ', $ciclos) . ")";
$sql = "Select i.n_inst,  a.iddisc, a.fk_id_inst, a.rm, a.prof2, a.suplementar, a.cit, p.n_pessoa, p.id_pessoa, "
        . " t.id_turma, t.n_turma, t.fk_id_ciclo "
        . " from ge_aloca_prof a "
        . " join ge_funcionario f on f.rm = a.rm and f.at_func = 1"
        . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
        . " join ge_turmas t on t.id_turma = a.fk_id_turma $ciclos "
        . " join instancia i on i.id_inst = a.fk_id_inst "
        . " where a.cit = 1 "
        . " and suplementar = 1 "
        . $inst
        . " ORDER BY n_inst, n_pessoa";
$query = pdoSis::getInstance()->query($sql);
$alocado = $query->fetchAll(PDO::FETCH_ASSOC);

if ($alocado) {
    foreach ($alocado as $v) {
        $idt[$v['rm']][$v['id_turma']] = $v['id_turma'];
        $t[$v['rm']][$v['id_turma']] = $v['n_turma'];
        $c[$v['rm']][$v['fk_id_ciclo']] = $v['fk_id_ciclo'];
    }
    foreach ($alocado as $v) {
        $result[$v['rm'] . ' - ' . $v['n_pessoa']][$v['n_inst']] = [
            'nome' => $v['n_pessoa'],
            'rm' => $v['rm'],
            'disciplina' => $disc[$v['iddisc']],
            'id_pessoa' => $v['id_pessoa'],
            'turmas' => toolErp::virgulaE($t[$v['rm']]),
            'id_turma' => $idt[$v['rm']],
            'ciclos' => $c[$v['rm']]
        ];
    }
}
?>
<div class="body">
    <?php
    if (!empty($result)) {
        ?>
        <?php
        foreach ($result as $k => $v) {
            ?>
            <table class="table table-bordered table-hover table-striped border">
                <tr>
                    <td colspan="2" style="font-weight: bold; font-size: 1.3em;">
                        <?= $k ?>
                    </td>
                </tr>
                <?php
                foreach ($v as $ky => $y) {
                    ?>

                    <tr>
                        <td>
                            <?= $ky ?>
                        </td>
                        <td>
                            <?= $y['disciplina'] ?>
                        </td>
                        <td>
                            <?= $y['turmas'] ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br /><br />
            <?php
        }
    }
    ?>
</div>