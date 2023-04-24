<?php
if (!defined('ABSPATH'))
    exit;

$idName = filter_input(INPUT_POST, 'idName');
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if ($idName) {
    $sql = "select id_pessoa, n_pessoa, n_turma, n_inst from pessoa p "
            . " join ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa and ta.fk_id_tas = 0 "
            . " and (id_pessoa = '$idName' or n_pessoa like '%$idName%') "
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo <> 32 "
            . " AND fk_id_ciclo in (4, 5, 6, 7, 8, 9)"
            . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1"
            . " join instancia i on i.id_inst = t.fk_id_inst ";
    $query = pdoSis::getInstance()->query($sql);
    $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($alunos)) {
        if (count($alunos) == 1) {
            $id_pessoa = current($alunos)['id_pessoa'];
        }
    }
}
if ($id_pessoa) {
    $pessoa = $model->aluno($id_pessoa);
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de aluno
    </div>
    <form method="POST">
        <div class="row">
            <div class="col-9">
                <?= formErp::input('idName', 'Nome ou RSE', $idName) ?>
            </div>
            <div class="col-3">
                <?= formErp::button('Buscar') ?>
            </div>
        </div>
        <br />
    </form>
    <?php
    if (!empty($alunos)) {
        if (count($alunos) > 1) {
            ?>
            <table class="table table-bordered table-hover table-responsive">
                <tr>
                    <td>
                        RSE
                    </td>
                    <td>
                        Nome
                    </td>
                    <td>
                        Escola
                    </td>
                    <td>
                        Turma de Origem
                    </td>
                    <td style="width: 100px">
                    </td>
                </tr>
                <?php
                foreach ($alunos as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= $v['id_pessoa'] ?>
                        </td>
                        <td>
                            <?= $v['n_pessoa'] ?>
                        </td>
                        <td>
                            <?= $v['n_inst'] ?>
                        </td>
                        <td>
                            <?= $v['n_turma'] ?>
                        </td>
                        <td>
                            <form method="POST">
                                <?=
                                formErp::hidden([
                                    'idName' => $v['id_pessoa'],
                                    'id_pessoa' => $v['id_pessoa']
                                ])
                                . formErp::button('Selecionar')
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
    }
    if (!empty($id_pessoa)) {
        if (!empty($pessoa)) {
            ?>
            <table class="table table-bordered table-hover table-responsive">
                <tr>
                    <td>
                        RSE
                    </td>
                    <td>
                        Nome
                    </td>
                    <td>
                        Núcleo
                    </td>
                    <td>
                        Turma TDICS
                    </td>
                    <td style="width: 100px">
                    </td>
                </tr>
                <?php
                foreach ($pessoa as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= $v['id_pessoa'] ?>
                        </td>
                        <td>
                            <?= $v['n_pessoa'] ?>
                        </td>
                        <td>
                            <?= $v['n_polo'] ?>
                        </td>
                        <td>
                            <?= $v['n_turma'] ?>
                        </td>
                        <td>
                            <form method="POST">
                                <?=
                                formErp::hidden([
                                    'id_pessoa' => $v['id_pessoa'],
                                    'idName' => $idName,
                                    '1[id_ta]' => $v['id_ta']
                                ])
                                . formErp::hiddenToken('tdics_turma_aluno', 'delete')
                                ?>
                                <button class="btn btn-danger" type="submit">
                                    Excluir
                                </button>
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
        <br /><br />
        <div style="text-align: center; padding: 50px">
            <form action="<?= HOME_URI ?>/tdics/def/formNovoAlunoAdm" target="frame" method="POST">
                <?= formErp::hidden(['id_pessoa' => $id_pessoa]) ?>
                <button class="btn btn-info"  onclick=" $('#myModal').modal('show');$('.form-class').val('')">
                    Nova Matrícula
                </button>
            </form>
        </div>
        <?php
    }
    ?>

    <?php
    toolErp::modalInicio();
    ?>
    <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
        <?php
        toolErp::modalFim();
        ?>
</div>
