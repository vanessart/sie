<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
$concluido = filter_input(INPUT_POST, 'concluido', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_1 = filter_input(INPUT_POST, 'id_pessoa_1', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_2 = filter_input(INPUT_POST, 'id_pessoa_2', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa1 = filter_input(INPUT_POST, 'id_pessoa1', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa2 = filter_input(INPUT_POST, 'id_pessoa2', FILTER_SANITIZE_NUMBER_INT);
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
$id_pl = $setup['id_pl'];
$diaSemana = date('w', strtotime(date("Y-m-d")));
if ($setup['transf'] == 'x') {
    $diaLiberado = ['x' => 'x'];
} else {
    $diaLiberado = explode(',', $setup['transf']);
}
if ($id_inst) {
    $a = $model->alunosEscNg($id_inst);
    if ($a) {
        foreach ($a as $v) {
            if (!empty($v['id_turma'])) {
                $alu[$v['id_pessoa']] = $v;
                $alunos[$v['id_pessoa']] = $v['id_pessoa'] . ' - ' . $v['n_pessoa'] . ' Turma: ' . $v['n_turma'];
            }
        }
    }
}

?>
<div class="body">
    <div class="fieldTop">
        Permuta Direta
    </div>
    <?php
    if (toolErp::id_nilvel() != 8) {
        ?>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
            </div>
        </div>
        <br />
        <?php
    }
    if (!empty($diaLiberado['x'])) {
        ?>
        <div class="alert alert-danger">
            Sistema Fechado para movimentações.
        </div>
        <?php
    } else {
        if (in_array($diaSemana, $diaLiberado)) {
            $alert = 'info';
        } else {
            $alert = 'danger';
        }
        foreach ($diaLiberado as $v) {
            $diasSem[] = dataErp::diasDaSemana($v);
        }
        ?>
        <div class="alert alert-<?= $alert ?>">
            Dia<?= count($diasSem) > 1 ? 's' : null ?> Liberado<?= count($diasSem) > 1 ? 's' : null ?> para movimentaç<?= count($diasSem) > 1 ? 'ões' : 'ão' ?>: <?= toolErp::virgulaE($diasSem) ?>.
        </div>
        <?php
    }
    if ($id_inst && !empty($alunos) && in_array($diaSemana, $diaLiberado)) {
        if ((empty($id_pessoa_1) || empty($id_pessoa_2) || $id_pessoa_1 == $id_pessoa_2) && empty($concluido)) {
            ?>
            <form method="POST">
                <div class="row">
                    <div class="col">
                        <?= formErp::select('id_pessoa_1', $alunos, 'Primeiro Aluno', $id_pessoa_1) ?>
                    </div>
                    <div class="col">
                        <?= formErp::select('id_pessoa_2', $alunos, 'Segundo Aluno', $id_pessoa_2) ?>
                    </div>
                </div>
                <div style="text-align: center; padding: 30px">
                    <?=
                    formErp::hidden([
                        'id_inst' => $id_inst,
                    ])
                    . formErp::button('Continuar')
                    ?>
                </div>
            </form>
            <?php
        }
        if (!empty($id_pessoa_1) && !empty($id_pessoa_2) && $id_pessoa_1 != $id_pessoa_2) {
            $pe1 = $alu[$id_pessoa_1];
            $pe2 = $alu[$id_pessoa_2];
            ?>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td></td>
                    <td>
                        Primeiro Aluno
                    </td>
                    <td>
                        Segundo Aluno
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome
                    </td>
                    <td>
                        <?= $pe1['id_pessoa'] ?> - <?= $pe1['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $pe2['id_pessoa'] ?> - <?= $pe2['n_pessoa'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Turma
                    </td>
                    <td>
                        <?= $pe1['n_turma'] ?>
                    </td>
                    <td>
                        <?= $pe2['n_turma'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Ciclo
                    </td>
                    <td>
                        <?= $pe1['n_mc'] ?>
                    </td>
                    <td>
                        <?= $pe2['n_mc'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Período
                    </td>
                    <td>
                        <?= $pe1['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                    </td>
                    <td>
                        <?= $pe1['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                    </td>
                </tr>
            </table>
            <form method="POST">
                <br /><br />
                <div class="row">
                    <div class="col-3">
                        <button type="button" class="btn btn-danger" onclick="limpar.submit()">
                            Limpar (NÃO concluir o processo)
                        </button>
                    </div>
                    <div class="col-9 text-center">
                        <?=
                        formErp::hidden([
                            'id_inst' => $id_inst,
                            'id_pessoa1' => $id_pessoa_1,
                            'id_pessoa2' => $id_pessoa_2,
                            'id_turma_1' => $pe1['id_turma'],
                            'id_turma_2' => $pe2['id_turma'],
                            'concluido' => 1
                        ])
                        . formErp::hiddenToken('permuta')
                        ?>
                        <button class="btn btn-primary">
                            Trocar os alunos de turma
                        </button>
                    </div>
                </div>
                <br />
                <div style="text-align: center; padding: 30px">

                </div>
            </form>
            <?php
        }
        if (!empty($concluido) && $id_pessoa1 && $id_pessoa2) {
            $pe1 = $alu[$id_pessoa1];
            $pe2 = $alu[$id_pessoa2];
            ?>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td></td>
                    <td>
                        Primeiro Aluno
                    </td>
                    <td>
                        Segundo Aluno
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome
                    </td>
                    <td>
                        <?= $pe1['id_pessoa'] ?> - <?= $pe1['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $pe2['id_pessoa'] ?> - <?= $pe2['n_pessoa'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Turma
                    </td>
                    <td>
                        <?= $pe1['n_turma'] ?>
                    </td>
                    <td>
                        <?= $pe2['n_turma'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Ciclo
                    </td>
                    <td>
                        <?= $pe1['n_mc'] ?>
                    </td>
                    <td>
                        <?= $pe2['n_mc'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Período
                    </td>
                    <td>
                        <?= $pe1['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                    </td>
                    <td>
                        <?= $pe1['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                    </td>
                </tr>
            </table>
            <div style="text-align: center; padding: 30px">
                <button onclick="limpar.submit();" class="btn btn-warning">
                    Voltar
                </button>
            </div>
            <?php
        }
    }
    ?>
</div>
<form id="limpar" method="POST">
    <div style="text-align: center; padding: 30px">
        <?=
        formErp::hidden([
            'id_inst' => $id_inst,
        ])
        ?>
    </div>
</form>