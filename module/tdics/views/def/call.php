<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if ($id_pessoa) {
    $id_sit = filter_input(INPUT_POST, 'id_sit', FILTER_SANITIZE_NUMBER_INT);
    $id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
    $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
    $aluno = $model->alunoCallCenter($id_pessoa);
    $sit = sql::idNome('tdics_call_center_sit');
    $sql = "SELECT * FROM `telefones` WHERE `fk_id_pessoa` = $id_pessoa AND `num` IS NOT NULL";
    $query = pdoSis::getInstance()->query($sql);
    $tel = $query->fetchAll(PDO::FETCH_ASSOC);
    $sql = "SELECT t.ddd, t.num, rt.n_rt FROM telefones t JOIN ge_aluno_responsavel r on r.fk_id_pessoa_aluno = t.fk_id_pessoa AND t.fk_id_pessoa = 1228096225 AND `num` IS NOT NULL JOIN ge_responsavel_tipo rt on rt.id_rt = r.fk_id_rt ";
    $query = pdoSis::getInstance()->query($sql);
    $telResp = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="body"><table class="table table-bordered table-hover table-responsive">
            <tr>
                <td style="width: 200px">
                    Nome
                </td>
                <td>
                    <?= $aluno[0]['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Matrícula
                </td>
                <td>
                    <?= $aluno[0]['id_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Escola de Origem
                </td>
                <td>
                    <?= $aluno[0]['n_inst'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Turma de Origem
                </td>
                <td>
                    <?= $aluno[0]['turmaEsc'] ?>
                </td>
            </tr>
        </table>
        <br /><br />       

        <?php
        foreach ($aluno as $v) {
            ?>
            <div class="border">
                <table class="table table-bordered table-hover table-responsive">
                    <tr>
                        <td>
                            Curso
                        </td>
                        <td>
                            <?= $v['n_curso'] ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Núcleo
                        </td>
                        <td>
                            <?= $v['n_polo'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 200px">
                            Período
                        </td>
                        <td>
                            <?= $v['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Horário
                        </td>
                        <td>
                            <?= $model->horario($v['fk_id_polo'], $v['periodo'], $v['horario']) ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Dia da Semana
                        </td>
                        <td>
                            <?= $v['dia_sem'] ?>ª Feira
                        </td>
                    </tr>

                </table>
            </div>
            <br /><br />       
            <?php
        }
        ?>
        <div class="border" style="width: 600px; margin: auto">
            <table class="table table-bordered table-hover table-responsive">
                <tr>
                    <td colspan="2" style="font-weight: bold; font-size: 1.2; text-align: center">
                        Telefones
                    </td>
                </tr>
                <?php
                foreach ($tel as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= $v['ddd'] ?>-<?= $v['num'] ?>
                        </td>
                        <td>
                            Principal
                        </td>
                    </tr>
                    <?php
                }
                foreach ($telResp as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= $v['ddd'] ?>-<?= $v['num'] ?>
                        </td>
                        <td>
                            <?= $v['n_rt'] ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <br /><br />
        <form target="_parent" action="<?= HOME_URI ?>/tdics/freqCall" method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('1[fk_id_sit]', $sit, 'Situação', $aluno[0]['fk_id_sit']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[obs]', $aluno[0]['obs'], 'Observação') ?>
                </div>
            </div>
            <br />
            <div style="text-align: center; padding: 30px">
                <?=
                formErp::hidden([
                    '1[contactou]' => 1,
                    '1[id_pessoa]' => $id_pessoa,
                    'id_sit' => $id_sit,
                    'id_polo' => $id_polo,
                    'id_curso' => $id_curso
                ])
                . formErp::hiddenToken('tdics_call_center', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </form>
    </div>
    <?php
}