<?php

if (!defined('ABSPATH'))
    exit;
$id_pl = gtMain::periodoSet(filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT));
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$prodesp = filter_input(INPUT_POST, 'prodesp', FILTER_SANITIZE_STRING);
$periodos = gtMain::periodosPorSituacao();
$escolas = escolas::idInst();
if ($id_pl && $id_inst) {
    $n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];

    $sql = "SELECT "
            . " n_curso, n_turma, prodesp "
            . " FROM `ge_turmas` "
            . " join ge_ciclos on ge_ciclos.id_ciclo =  ge_turmas.fk_id_ciclo "
            . " join ge_cursos on ge_cursos.id_curso = ge_ciclos.fk_id_curso "
            . " WHERE fk_id_pl = $id_pl "
            . " and fk_id_inst = $id_inst "
            . " order by  n_turma";

    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        foreach ($array as $v) {
            $tumas[$v['n_curso']][$v['prodesp']] = $v['n_turma'];
        }
    }

    $tumas[$v['n_curso']][$v['prodesp']] = $v['n_turma'];
}
if (!empty($prodesp) && !empty($id_pl)) {
    $esc = new escola($id_inst);
    $form['array'] = $esc->alunos($prodesp, $id_pl, 'prodesp');
    $form['fields'] = [
        'Turma' => 'n_turma',
        'Chamada' => 'chamada',
        'RM' => 'id_pessoa',
        'Nome' => 'n_pessoa'
    ];
}
?>
<div class="body">
    <?php
    if (empty($id_inst) || empty($id_pl)) {
        ?>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl) ?>
                </div>
                <div class="col">
                    <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst) ?>
                </div>
                <div class="col">
                    <?=
                    formErp::hidden(['activeNav' => 2])
                    . formErp::button('Continuar')
                    ?>
                </div>
            </div>
            <br />
        </form>
        <?php
    } else {
        ?>
        <table class="table table-bordered table-hover table-striped" style="font-weight: bold">
            <tr>
                <td>
                    Período Letivo <?= $n_pl ?>
                </td>
                <td>
                    Escola: <?= $escolas[$id_inst] ?>
                </td>
                <td>
                    <form method="POST">
                        <?=
                        formErp::hidden(['activeNav' => 2])
                        . formErp::button('Limpar')
                        ?>
                    </form>
                </td>
            </tr>
        </table>
        <?php
        if (!empty($tumas)) {
            ?>
            <form method="POST">
                <div class="row">
                    <div class="col-sm-4">
                        <?= formErp::select('prodesp', $tumas, ['Turma', 'Todas as Turmas'], $prodesp) ?>
                    </div>
                    <div class="col-sm-4">
                        <?=
                        formErp::hidden([
                            'id_inst' => $id_inst,
                            'id_pl' => $id_pl,
                            'activeNav' => 2,
                            'baixarTurmaAluno' => 1
                        ])
                        . formErp::hiddenToken('baixarTurmaAluno')
                        . formErp::button('Importar')
                        ?>
                    </div>
                </div>
                <br />
            </form>
            <?php
        } else {
            ?>
            <div class="alert alert-danger">
                Não há Classes neste período
            </div>
            <?php
        }
        if (!empty($form)) {
            report::simple($form);
        }
    }
    ?>
</div>
