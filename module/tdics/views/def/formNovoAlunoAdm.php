<?php
if (!defined('ABSPATH'))
    exit;

$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
if (!$id_pessoa) {
    exit();
}
if ($id_polo) {
    $turmas = $model->turmaPolo($id_polo);
}
$sql = "SELECT "
        . " p.n_pessoa, p.sexo, t.id_turma, t.n_turma, t.periodo, i.n_inst "
        . " from ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " and ta.fk_id_pessoa = $id_pessoa "
        . " AND t.fk_id_ciclo <> 32 "
        . " and ta.fk_id_tas = 0 "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
        . " AND pl.at_pl = 1 "
        . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
        . " JOIN instancia i on i.id_inst = t.fk_id_inst ";
$query = pdoSis::getInstance()->query($sql);
$dados = $query->fetch(PDO::FETCH_ASSOC);
?>
<div class="body">
    <div class="fieldTop">
        <?= $id_pessoa ?> - <?= $dados['n_pessoa'] ?>
    </div>
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td style="width: 300px">
                Matrícula
            </td>
            <td>
                <?= $id_pessoa ?>
            </td>
        </tr>
        <tr>
            <td>
                Nome
            </td>
            <td>
                <?= $dados['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Escola
            </td>
            <td>
                <?= $dados['n_inst'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Turma de Origem
            </td>
            <td>
                <?= $dados['n_turma'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Período da turma de Origem
            </td>
            <td>
                <?= $dados['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
            </td>
        </tr>
    </table>
    <br /><br />
    <?= formErp::selectDB('tdics_polo', 'id_polo', 'Núcleo', $id_polo, 1, ['id_pessoa' => $id_pessoa]) ?>
    <br /><br />
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td>
                ID
            </td>
            <td>
                Turma
            </td>
            <td>
                Curso
            </td>
            <td>
                Período
            </td>
            <td>
                Dia
            </td>
            <td>
                Horário
            </td>
            <td></td>
        </tr>
        <?php
        if ($id_polo && !empty($turmas)) {
            foreach ($turmas as $v) {
                if ($v['periodo'] <> $dados['periodo']) {
                    $color = 'blue';
                } else {

                    $color = 'red';
                }
                ?>
                <tr>
                    <td>
                        <?= $v['id_turma'] ?>
                    </td>
                    <td>
                        <?= $v['n_turma'] ?>
                    </td>
                    <td>
                        <?= $v['n_curso'] ?>
                    </td>
                    <td>
                        <span style="font-weight: bold; color: <?= $color ?>">
                            <?= $v['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                        </span>
                    </td>
                    <td>
                        <?= $model->diaSemana($v['dia_sem']) ?>
                    </td>
                    <td>
                        <?= $model->horario($v['fk_id_polo'], $v['periodo'], $v['horario']) ?>
                    </td>
                    <td>
                        <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/alunoCad" target="_parent" method="POST">
                            <?=
                            formErp::hidden([
                                'id_pessoa' => $id_pessoa,
                                '1[fk_id_pessoa]' => $id_pessoa,
                                '1[fk_id_turma]' => $v['id_turma']
                            ])
                            . formErp::hiddenToken('tdics_turma_aluno', 'ireplace')
                            . formErp::button('Matricular')
                            ?>
                        </form>  
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>

        <?php
    }
    ?>
</div>