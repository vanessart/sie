<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$atualLetiva = filter_input(INPUT_POST, 'atualLetiva', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$faltasTotal = filter_input(INPUT_POST, 'faltasTotal', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$hidden = [
    'id_pessoa' => $id_pessoa,
    'atualLetiva' => $atualLetiva,
    'id_turma' => $id_turma,
    'id_pl' => $id_pl,
    'id_curso' => $id_curso,
    'id_disc' => $id_disc,
    'faltasTotal' => $faltasTotal
];
$aluno = new ng_aluno($id_pessoa);
$aluno->vidaEscolar($id_pl);
if ($id_disc != 'nc') {
    $disc = sql::get('ge_disciplinas', 'n_disc', ['id_disc' => $id_disc], 'fetch')['n_disc'];
} else {
    $disc = 'Núcleo Comum';
}
$sql = "SELECT p.n_pessoa, j.* FROM profe_falta_just j "
        . " join pessoa p on p.id_pessoa = j.fk_id_pessoa_lanc  "
        . " WHERE `fk_id_pessoa` = $id_pessoa "
        . " and fk_di_curso = $id_curso "
        . " and fk_id_pl = $id_pl ";
$query = pdoSis::getInstance()->query($sql);
$justAnt = $query->fetchAll(PDO::FETCH_ASSOC);
if ($justAnt) {
    $token = formErp::token('justificaFaltasDel');
    foreach ($justAnt as $k => $v) {
        $hidden['id_fj'] = $v['id_fj'];
        $hidden['faltas'] = $v['faltas'];
        $justAnt[$k]['del'] = formErp::submit('Excluir', $token, $hidden, HOME_URI . '/profe/justificaFaltas', '_parent');
        if ($v['upload']) {
            $justAnt[$k]['up'] = formErp::submit('Atestado', null, null, HOME_URI.'/pub/justificaFaltas/'.$v['upload'], 1);
        }
    }
    $form['array'] = $justAnt;
    $form['fields'] = [
        'ID' => 'id_fj',
        'Faltas' => 'faltas',
        'Justificativas' => 'motivo',
        'Quem Lançou' => 'n_pessoa',
        '||1' => 'del',
        '||2' => 'up'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Justificativa de Faltas
    </div>
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td>
                Nome
            </td>
            <td>
                <?= $aluno->dadosPessoais['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td>
                RSE
            </td>
            <td>
                <?= $aluno->dadosPessoais['id_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Turma
            </td>
            <td>
                <?= $aluno->vidaEscolar[0]['n_turma'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Disciplina
            </td>
            <td>
                <?= $disc ?>
            </td>
        </tr>
        <tr>
            <td>
                Bimestre
            </td>
            <td>
                <?= $atualLetiva ?>
            </td>
        </tr>
        <tr>
            <td>
                Total de Faltas no Bimestre
            </td>
            <td>
                <?= $faltasTotal ?>
            </td>
        </tr>
    </table>
    <form action="<?= HOME_URI ?>/profe/justificaFaltas" target="_parent" method="POST" enctype="multipart/form-data" >
        <div class="row">
            <div class="col-9">
                <?= formErp::input('faltasJust', 'Total de faltas que serão justificadas por ATESTADO MÉDICO', null, ' required min=1 max=' . $faltasTotal . ' ', null, 'number') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('justifica', null, 'Justificativa (campo obrigatório)') ?>
            </div>
        </div>
        <br />
        <div class="alert alert-primary">
            <div class="row">
                <div class="col" style="font-weight: bold; text-align: center;padding: 10px">
                    Upload do ATESTADO MÉDICO
                </div>
                <div class="col">
                    <div class="col text-center">
                        <input class="btn btn-primary" type="file" name="arquivo" style="width: 80%" />
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div style="padding: 20px; text-align: center">
            <?=
            formErp::hidden([
                'id_turma' => $id_turma,
                'atualLetiva' => $atualLetiva,
                'id_pessoa' => $id_pessoa,
                'id_disc' => $id_disc,
                'id_pl' => $id_pl,
                'id_curso' => $id_curso,
                'faltasTotal' => $faltasTotal
            ])
            . formErp::hiddenToken('justificaFaltas')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
