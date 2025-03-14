<?php
if (!defined('ABSPATH'))
    exit;

$travaPorEscola = in_array(toolErp::id_nilvel(), [8, 24]);

if ($travaPorEscola) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}
$cursos = sql::idNome($model::$sistema . '_curso', ['ativo' => 1]);
$escolas = ng_escolas::idEscolas([1]);
if ($id_inst && empty($escolas[$id_inst])) {
    ?>
    <div class="alert alert-danger">
        Esta escola não pertence ao projeto
    </div>
    <?php
    die();
}
$setup = sql::get($model::$sistema . '_setup', '*', null, 'fetch');
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$plsArr = sql::get($model::$sistema . '_pl', '*', ' where ativo in (1,2)');
$libera = [];
$pls = [];
foreach ($plsArr as $v) {
    $pls[$v['id_pl']] = $v['n_pl'];
    if (empty($id_pl) && $v['ativo'] == 1) {
        $id_pl = $v['id_pl'];
    }
    if ($setup['matri'] == 1 && $v['ativo'] == 1) {
        $libera[] = $v['id_pl'];
    }
    if ($setup['matri_prev'] == 1 && $v['ativo'] == 2) {
        $libera[] = $v['id_pl'];
    }
}
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    $turmaCurso = sql::get([$model::$sistema .'_turma', $model::$sistema .'_curso'], '*', ['id_turma' => $id_turma], 'fetch');
}
$hidden = [
    'id_pl' => $id_pl,
    'id_polo' => $id_polo,
    'id_inst' => $id_inst,
    'id_turma' => $id_turma,
    'id_curso' => $id_curso
];
$polos = sql::idNome($model::$sistema . '_polo', ['ativo' => 1]);
if ($id_polo) {
    $qtAlunos = $model->countAlunos($id_polo, $id_pl);
    if ($id_curso) {
        $where = ['fk_id_polo' => $id_polo, 'fk_id_pl' => $id_pl, 'fk_id_curso' => $id_curso];
    } else {
        $where = ['fk_id_polo' => $id_polo, 'fk_id_pl' => $id_pl];
    }
    $turmas = sqlErp::get($model::$sistema . '_turma', '*', $where);
    if ($turmas) {
        $turmas = toolErp::idName($turmas);
    } else {

        $turmas = [];
    }

    $alunos = $model->alunoEsc($id_pl, $id_inst, $id_polo, $id_turma);

    if (!empty($alunos)) {
        $token = formErp::token($model::$sistema . '_turma_aluno', 'delete');
        foreach ($alunos as $k => $v) {
            if (in_array($id_pl, $libera) || !$travaPorEscola) {
                $alunos[$k]['ac'] = '<button onclick="transf(' . $v['id_ta'] . ')" class="btn btn-info">Transferir</button>';
            }
            $alunos[$k]['ex'] = formErp::submit('Excluir', $token, $hidden + ['1[id_ta]' => $v['id_ta']]);
            if (!empty($id_polo) && !empty($id_turma)) {
                $v['n_polo'] = @$polos[@$id_polo];
                $v['n_curso'] = @$cursos[$turmaCurso['fk_id_curso']];
                $v['dia'] = $model->diaSemana($turmaCurso['dia_sem']);
             //   $alunos[$k]['termo'] = formErp::submit('Termo de Matrícula', null, $v, HOME_URI . '/'.$this->controller_name.'/pdf/termo', 1);
            }
        }
        $form['array'] = $alunos;
        $form['fields'] = [
            'Matrícula' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Turma' => 'n_turma',
            'Escola' => 'n_inst',
            'Turma Origem' => 'turmaEsc',
            '||1' => 'ex',
            '||2' => 'ac',
         //   '||3' => 'termo'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciamento de Alunos
    </div>
    <?php
    if (!$travaPorEscola) {
        echo formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1, $hidden) . '<br>';
    }
    ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl, 1, ['id_polo' => $id_polo, 'id_inst' => $id_inst, 'id_curso' => $id_curso]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_polo', $polos, 'Núcleo', $id_polo, 1, ['id_pl' => $id_pl, 'id_inst' => $id_inst, 'id_curso' => $id_curso]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_curso', $cursos, 'Curso', $id_curso, 1, ['id_polo' => $id_polo, 'id_pl' => $id_pl, 'id_inst' => $id_inst]) ?>
        </div>
        <div class="col">
            <form method="POST">
                <?=
                formErp::hidden($hidden)
                . formErp::hidden(['id_turma' => null, 'id_curso' => null])
                ?>
                <?php
                if ($id_polo && $id_turma) {
                    ?>
                    <button class="btn btn-warning">
                        Limpar o filtro de Turma e Curso
                    </button>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
    <br />
    <?php
    if ($id_polo) {
        ?>
        <div class="row">
            <?php
            $ct = 1;
            foreach ($turmas as $k => $v) {

                if ($k == $id_turma) {
                    $class = 'primary';
                } elseif (@$qtAlunos[$k] < 10) {
                    $class = 'warning';
                } elseif (@$qtAlunos[$k] >= $setup['qt_turma']) {
                    $class = 'danger';
                } else {
                    $class = 'info';
                }
                ?>
                <div class="col-3">
                    <form method="POST">
                        <?=
                        formErp::hidden($hidden)
                        . formErp::hidden([
                            'id_turma' => $k
                        ])
                        ?>
                        <button style="width: 100%" class="btn btn-<?= $class ?>">
                            <?= $v ?> (<?= empty($qtAlunos[$k]) ? 'Sem aluno' : @$qtAlunos[$k] . ' aluno' . (@$qtAlunos[$k] > 1 ? 's' : '') ?>)
                        </button>
                    </form>
                </div>
                <?php
                if ($ct++ >= 4) {
                    $ct = 1;
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
        <div class="row">
            <div class="col-3" style="text-align: center; padding: 10px">
                <?php
                if ($id_turma && $id_inst) {
                    if (!$travaPorEscola || @$qtAlunos[$id_turma] < $setup['qt_turma']) {
                        if (in_array($id_pl, $libera) || !$travaPorEscola) {
                            ?>
                            <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formNovoAluno" id="novoAluno" target="frame" method="POST">
                                <?=
                                formErp::hidden($hidden)
                                . formErp::hidden([
                                    'id_turma' => $id_turma,
                                    'escola' => $escolas[$id_inst],
                                    'turma' => $turmas[$id_turma],
                                    'polo' => $polos[$id_polo]
                                ])
                                ?>
                                <button style="width: 100%" type="submit" class="btn btn-info" onclick=" $('#myModal').modal('show');$('.form-class').val('')">
                                    Incluir Aluno
                                </button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <button style="width: 100%" type="button" class="btn btn-warning" >
                                Movimentação suspensa
                            </button>
                            <?php
                        }
                    } else {
                        ?>
                        <button style="width: 100%" type="button" class="btn btn-dark" >
                            Turma Completa
                        </button>
                        <?php
                    }
                } elseif (!$travaPorEscola && $id_turma) {
                    ?>
                    <button style="width: 100%" type="button" class="btn btn-secondary" >
                        Para Incluir Aluno, Selecione uma Escola
                    </button>
                    <?php
                } else {
                    ?>
                    <button style="width: 100%" type="button" class="btn btn-secondary" >
                        Para Incluir Aluno, Selecione uma Turma
                    </button>
                    <?php
                }
                ?>
            </div>
            <div class="col-9">
                <?php
                if ($id_turma) {
                    ?>
                    <table style="font-weight: bold" class="table table-bordered table-hover table-responsive">
                        <tr>
                            <td style="width: 40%">
                                <?= $turmaCurso['n_curso'] ?>
                            </td>
                            <td style="width: 20%">
                                Período da <?= $turmaCurso['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                            </td>
                            <td style="width: 20%">
                                <?= $model->diaSemana($turmaCurso['dia_sem']) ?>
                            </td>
                            <td style="width: 20%">
                                <?= $turmaCurso['horario'] ?>º horário
                            </td>
                        </tr>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
        <br />
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formTransf" id="transfx" target="frame" method="POST">
    <?=
    formErp::hidden($hidden);
    ?>
    <input type="hidden" name="id_ta" id="id_ta" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function transf(id) {
        if (id) {
            id_ta.value = id;
        } else {
            id_ta.value = "";
        }
        transfx.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
