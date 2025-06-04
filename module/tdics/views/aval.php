<?php
if (!defined('ABSPATH'))
    exit;

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_ag = filter_input(INPUT_POST, 'id_ag', FILTER_SANITIZE_NUMBER_INT);
$id_aval = filter_input(INPUT_POST, 'id_aval', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if ($model->db->tokenCheck('registraNota')) {
    if (!empty($_POST['resp'])) {
        $sql = "SELECT `id_quest`, `valor_1`, `valor_2`, `valor_3`, `valor_4`, `valor_5` FROM `{$model::$sistema}_aval_quest` "
                . " WHERE `fk_id_aval` = $id_aval";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                foreach (range(1, 5) as $y) {
                    $n[$v['id_quest']][$y] = $v['valor_' . $y];
                }
            }
        }
        $nota = 0;
        foreach ($_POST['resp'] as $k => $v) {
            $nota += $n[$k][$v];
        }
        $_id_ar = filter_input(INPUT_POST, 'id_ar', FILTER_SANITIZE_NUMBER_INT);
        if (empty($_id_ar)) {
            $sql = "SELECT `id_ar` FROM `{$model::$sistema}_aval_resp` "
                . " WHERE `fk_id_aval` = $id_aval"
                . " AND `fk_id_pessoa` = $id_pessoa"
                . " AND `fk_id_turma` = $id_turma";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($array)) {
                $_id_ar = $array['id_ar'];
            }
        }

        $in['id_ar'] = $_id_ar;
        $in['fk_id_pessoa'] = $id_pessoa;
        $in['fk_id_aval'] = $id_aval;
        $in['fk_id_turma'] = $id_turma;
        $in['respostas'] = json_encode($_POST['resp']);
        $in['nota'] = $nota;
        $in['fk_id_pessoa_prof'] = toolErp::id_pessoa();
        $model->db->ireplace($model::$sistema .'_aval_resp', $in, 1);
    }
}

$polo = $model->getPolos();
$ag = sqlErp::idNome($model::$sistema . '_aval_group', ['at_ag' => 1]);
$av = [];
if ($id_ag) {
    $sql = "SELECT `id_aval`, `n_aval`, fk_id_curso FROM `{$model::$sistema}_aval` WHERE `fk_id_ag` = $id_ag ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        foreach ($array as $v) {
            $idCursos[$v['id_aval']] = $v['fk_id_curso'];
        }
        $av = toolErp::idName($array);
    }
}
if ($id_aval && !empty($id_polo)) {
    $id_pl = sql::get($model::$sistema . '_aval_group', 'fk_id_pl', ['id_ag' => $id_ag], 'fetch')['fk_id_pl'];
    $cursos = $idCursos[$id_aval];
    $sql = "SELECT id_turma, n_turma FROM `{$model::$sistema}_turma` "
            . " WHERE `fk_id_polo` = $id_polo "
            . " AND `fk_id_curso` IN ($cursos) "
            . " AND `fk_id_pl` = $id_pl "
            . " ORDER BY n_turma ASC ";
    $query = pdoSis::getInstance()->query($sql);
    $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
}
if ($id_turma) {
    $sql = " SELECT p.n_pessoa, p.id_pessoa, n.nota FROM {$model::$sistema}_turma_aluno ta "
            . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " and ta.fk_id_turma = $id_turma "
            . " LEFT JOIN {$model::$sistema}_aval_resp n on n.fk_id_pessoa = ta.fk_id_pessoa "
            . " and n.fk_id_aval = $id_aval "
            . " ORDER BY p.n_pessoa ";
    $query = pdoSis::getInstance()->query($sql);
    $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="body">
    <div class="fieldTop">
        Ficha de Avaliação
    </div>
    <div class="row"> 
        <div class="col">
            <?= formErp::select('id_polo', $polo, 'Polo', $id_polo, 1, ['id_ag' => $id_ag, 'id_aval' => $id_aval]) ?>
        </div>
        <div class="col"> 
            <?php
            if ($id_polo) {
                echo formErp::select('id_ag', $ag, 'Agrupamento', $id_ag, 1, ['id_polo' => $id_polo]);
            }
            ?>
        </div>
        <div class="col"> 
            <?php
            if ($id_ag && $id_polo) {
                echo formErp::select('id_aval', $av, 'Avaliação', $id_aval, 1, ['id_ag' => $id_ag, 'id_polo' => $id_polo]);
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($turmas)) {
        ?>
        <div class="row"> 
            <?php
            $c = 1;
            foreach ($turmas as $v) {
                if ($v['id_turma'] == $id_turma) {
                    $class = 'success';
                } else {
                    $class = 'warning';
                }
                ?>
                <div class="col-2 text-center"> 
                    <form method="POST">
                        <?=
                        formErp::hidden([
                            'id_polo' => $id_polo,
                            'id_ag' => $id_ag,
                            'id_aval' => $id_aval,
                            'id_turma' => $v['id_turma']
                        ])
                        ?>
                        <button class="btn btn-<?= $class ?>">
                            <?= $v['n_turma'] ?> 
                        </button>
                    </form>
                </div>
                <?php
                if ($c++ % 6 == 0) {
                    ?>
                </div>
                <br />
                <div class="row"> 
                    <?php
                }
            }
            ?>
        </div>
        <br />
        <?php
    }
    if (!empty($alunos)) {
        ?>
        <table class="table table-bordered table-responsive" style="width: 60%; margin: auto; font-weight: bold; font-size: 1.2em">
            <tr>
                <td>
                    rse
                </td>
                <td>
                    Nome
                </td>
                <td>
                    Nota
                </td>
                <td style="width: 10px">

                </td>
            </tr>
            <?php
            foreach ($alunos as $v) {
                if ($v['nota'] > 0) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }
                ?>
                <tr>
                    <td>
                        <?= $v['id_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?php
                        if ($v['nota']) {
                            echo $v['nota'];
                        } else {
                            echo 'NL';
                        }
                        ?>
                    </td>
                    <td>
                        <button class="btn btn-<?= $class ?>" onclick="avalia(<?= $v['id_pessoa'] ?>, '<?= $v['n_pessoa'] ?>')">
                            Avaliar
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    } elseif ($id_turma) {
        ?>
        <div class="alert alert-danger">
            Turma vazia
        </div>
        <?php
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formResp" target="frame" id="form" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    <input type="hidden" name="n_pessoa" id="n_pessoa" value="" />
    <?=
    formErp::hidden([
        'id_polo' => $id_polo,
        'id_ag' => $id_ag,
        'id_aval' => $id_aval,
        'id_turma' => $id_turma
    ])
    ?>
</form>
<?php
toolErp::modalInicio(null, 'modal-fullscreen');
?>
<iframe style="width: 100%; height: 600vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function avalia(id, nome) {
        n_pessoa.value = nome;
        id_pessoa.value = id;
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>