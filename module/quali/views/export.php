<?php
if (!defined('ABSPATH'))
    exit;
$limit = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_cur = filter_input(INPUT_POST, 'id_cur', FILTER_SANITIZE_NUMBER_INT);
$form = filter_input(INPUT_POST, 'form', FILTER_SANITIZE_STRING);
$matr = filter_input(INPUT_POST, 'matr', FILTER_SANITIZE_STRING);
if (!empty($limit) && !empty($id_turma) && !empty($id_cur)) {
    $sql = "UPDATE quali_incritos_$form "
            . "SET fk_id_turma = $id_turma "
            . " WHERE situacao = 1 "
            . " AND fk_id_cur = $id_cur "
            . " AND fk_id_turma is null "
            . " LIMIT $limit";
    $query = pdoSis::getInstance()->query($sql);
}
?>
<div class="body">
    <br>
    <div class="alert alert-danger" style="text-align: center; font-weight: bold">
        <p>
            ATENÇÃO!!! Verifique se você está na instância (Unidade) certa.
        </p>
        <p>
            Executar este script na instância errada, comprometerá o banco de dados
        </p>
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= form::selectDB('quali_inscr', 'form', 'Inscrição', $form, null, null, null, ['at_inscr' => 1]) ?>
            </div>
            <div class="col">
                <?= form::selectDB('gt_periodo_letivo', 'id_pl', 'Período', $id_pl) ?>
            </div>
            <div class="col">
                <?= form::button('Continuar') ?>
            </div>
        </div>
        <br />
    </form>
    <div style="text-align: center">
    </div>
    <br /><br />
    <?php
    if (!empty($form) && !empty($id_pl)) {
        ?>
        <div class="fieldTop border">
            Alocar Aluno na Turma
            <br /><br />
            <?php
            $sql = "SELECT id_turma, n_turma FROM gt_turma t "
                    . " left join quali_incritos_$form ta on ta.fk_id_turma = t.id_turma "
                    . " WHERE fk_id_pl = $id_pl "
                    . " AND id_inscr is null "
                    . " order by t.n_turma ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            $turmas = tool::idName($array);
            $sql = "SELECT c.id_cur, c.n_cur, COUNT(id_inscr) as tt FROM quali_incritos_$form i JOIN gt_curso c on c.id_cur = i.fk_id_cur WHERE `situacao` = 1 AND `fk_id_turma` IS NULL GROUP BY c.id_cur order by c.n_cur";
            $query = pdoSis::getInstance()->query($sql);
            $falta = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($falta)) {
                ?>
                <div class="alert alert-info">
                    Todos os alunos foram alocados
                </div>
                <?php
            } else {
                $sql = "SELECT distinct c.n_cur, c.id_cur FROM quali_incritos_$form i JOIN gt_curso c on c.id_cur = i.fk_id_cur WHERE `situacao` = 1 AND `fk_id_turma` IS NULL GROUP BY c.id_cur order by c.n_cur";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                $cursos = tool::idName($array);
                ?>
                <form method="POST">
                    <div class="row">
                        <div class="col">
                            <?= form::select('id_cur', $cursos, 'Curso', $id_cur, 1) ?>

                        </div>
                        <div class="col">
                            <?= form::select('id_turma', $turmas, 'Turma', $id_turma) ?>
                        </div>
                        <div class="col">
                            <?= form::input('limit', 'Quantidade', $limit, null, null, 'number') ?>
                        </div>
                        <div class="col">
                            <?=
                            form::hidden(['form' => $form, 'id_pl' => $id_pl, 'AlocaTurma' => 1])
                            . form::button('Alocar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>

                <br />
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <td>
                            ID
                        </td>
                        <td>
                            Curso
                        </td>
                        <td>
                            Falta alocar
                        </td>
                    </tr>
                    <?php
                    foreach ($falta as $v) {
                        ?>
                        <tr>
                            <td style="text-align: left">
                                <?= $v['id_cur'] ?>
                            </td>
                            <td style="text-align: left">
                                <?= $v['n_cur'] ?>
                            </td>
                            <td>
                                <?= $v['tt'] ?>
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
        <br /><br />
        <div class="fieldTop border">
            Exportar para Matrícula
            <br /><br />
            <div class="row">
                <div class="col-4 text-center">
                </div>
                <div class="col">
                    <?php
                    if (empty($falta)) {
                        ?>
                        <form method="POST">
                            <?=
                            form::hidden([
                                'form' => $form,
                                'id_pl' => $id_pl
                            ]);
                            ?>
                            <button type="submit" class="btn btn-warning" value="matr" name="matr">
                                Exportar
                            </button>
                        </form>
                        <?php
                    } else {
                        ?>
                        <div class="alert alert-warning">
                            Aloque todos os alunos
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col">
                    <?php
                    if (empty($falta)) {
                        ?>
                        <div class="alert alert-danger" style="font-weight: bold; font-size: 22px">
                            <- Cuidado! Só clique se souber o que está fazendo
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <br />
        <?php
        if (!empty($matr)) {
            $sql = "SELECT * FROM `quali_incritos_$form` WHERE `situacao` = 1 AND `fk_id_turma` IS NULL";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($array)) {
                tool::alert("Todos os alunos precisam ser alocados");
            } else {
                $sql = "SELECT p.cpf, p.id_pessoa FROM pessoa p JOIN quali_incritos_$form q on q.cpf = p.cpf";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($array as $v) {
                    $ids[$v['cpf']] = $v['id_pessoa'];
                }

                $sql = "SELECT * FROM `quali_incritos_$form` WHERE `situacao` = 1 order by fk_id_turma, nome";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($array as $v) {
                    unset($ins);
                    $ins["id_pessoa"] = @$ids[$v['cpf']];
                    $ins["n_pessoa"] = strtoupper(trim($v["nome"]));
                    $ins["ativo"] = 1;
                    $ins["cpf"] = $v["cpf"];
                    $ins["rg"] = @$v["rg"];
                    $ins["dt_nasc"] = @$v["dt_nasc"];
                    $ins["civil"] = @$v["fk_id_civil"];
                    $ins["sexo"] = $v["sexo"];
                    $ins["email"] = $v["email"];
                    $ins["tel3"] = @$v["celular"];
                    $ins["whatsapp"] = @$v["whatsapp"];
                    $ins["tel2"] = @$v["recado"];

                    $id_pessoa = $model->db->ireplace('pessoa', $ins, 1);
                    unset($ins);

                    $ins["fk_id_pessoa"] = $id_pessoa;
                    $ins["logradouro"] = @$v["logradouro"];
                    $ins["num"] = @$v["num"];
                    $ins["complemento"] = @$v["complemento"];
                    $ins["bairro"] = @$v["bairro"];
                    $ins["cidade"] = @$v["cidade"];
                    $ins["uf"] = @$v["uf"];
                    $ins["cep"] = @$v["cep"];

                    try {
                        $model->db->ireplace('endereco', $ins, 1);
                    } catch (Exception $exc) {
                        
                    }

                    unset($ins);
                    if ($id_pessoa) {
                        $ins["fk_id_pessoa"] = $id_pessoa;
                        $ins["ativo"] = 1;
                        $ins["token"] = substr(uniqid(), -3);
                        $sql = "SELECT id_user FROM `users` WHERE `fk_id_pessoa` = $id_pessoa ";
                        $query = pdoSis::getInstance()->query($sql);
                        $user = $query->fetch(PDO::FETCH_ASSOC);
                        $ins['id_user'] = @$user['id_user'];
                        try {
                            $model->db->ireplace('users', $ins, 1);
                        } catch (Exception $exc) {
                            
                        }
                    }
                    unset($ins);
                    $ins["fk_id_pessoa"] = $id_pessoa;
                    $ins["fk_id_gr"] = 14;
                    $ins["fk_id_inst"] = tool::id_inst();
                    try {
                        $model->db->ireplace('acesso_pessoa', $ins, 1);
                    } catch (Exception $exc) {
                        
                    }
                    if (@$turmaOld != $v["fk_id_turma"]) {
                        $temAluno = sql::get('gt_turma_aluno', 'id_ta', ['fk_id_turma' => $v["fk_id_turma"]], 'fetch');
                        $chamada = 1;
                    } else {
                        $chamada++;
                    }
                    if (empty($temAluno)) {
                        $turmaOld = $v["fk_id_turma"];
                        unset($ins);
                        $ins["fk_id_pessoa"] = $id_pessoa;
                        $ins["fk_id_turma"] = $v["fk_id_turma"];
                        $ins["fk_id_inst"] = tool::id_inst();
                        $ins["chamada"] = $chamada;
                        $ins["fk_id_sit"] = '0';
                        $ins["dt_matricula"] = date("Y-m-d");
                        $model->db->ireplace('gt_turma_aluno', $ins, 1);
                    }
                }
            }
        }
    }
    ?>
</div>
