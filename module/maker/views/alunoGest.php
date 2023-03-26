<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
$id_mc = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$diaSem = filter_input(INPUT_POST, 'diaSem', FILTER_SANITIZE_NUMBER_INT);
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], 'n_pl, id_pl', null, 'fetch');
$polos = sql::idNome('maker_polo');

if ($id_polo) {
    $turmaForm = $model->turmasPoloNg($id_polo, $periodo, $diaSem);

    $alunos = $model->alunosNg($id_polo);
    if ($alunos) {
        foreach ($alunos as $k => $v) {
            @$qt[$v['id_turma']]++;
            if ($v['id_turma'] == $id_turma) {
                $codigoTurma = $v['n_turma'];
                $classe = 'primary';
            } else {
                $classe = 'info';
            }
            if (!empty($turmaForm[$v['id_turma']])) {
                $turmaForm[$v['id_turma']]['qt'] = @$qt[$v['id_turma']];
                $turmaForm[$v['id_turma']]['class'] = $classe;
            }
        }
        if (!empty($turmaForm)) {
            ksort($turmaForm);
        }
        $alunos = $model->alunosNg($id_polo, $id_mc, $id_turma, $periodo, $diaSem);
        if ($alunos) {
            foreach ($alunos as $k => $v) {
                $alunos[$k]['transf'] = '<button onclick="transf(' . $v['id_ta'] . ')" class="btn btn-primary" >Transferir</button>';
                $alunos[$k]['del'] = '<button onclick="fila(' . $v['id_ta'] . ', \'' . $v['n_pessoa'] . '\')" class="btn btn-danger" >Excluir</button>';
            }
            $form['array'] = $alunos;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Nome' => 'n_pessoa',
                'Ciclo' => 'n_mc',
                'Escola' => 'n_inst',
                'Período' => 'periodo',
                'Turma' => 'n_turma',
                '||3' => 'del',
                '||2' => 'transf'
            ];
        }
        $form['array'] = $alunos;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Ciclo' => 'n_mc',
            'Escola' => 'n_inst',
            'Período' => 'periodo',
            'Turma' => 'n_turma',
            '||3' => 'del',
            '||2' => 'transf'
        ];
    }
}
?>
<div class="body">
    <br /><br />
    <div class="border">
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_polo', $polos, 'Polo', $id_polo) ?>
                </div>
                <div class="col">
                    <?= formErp::select('periodo', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período', $periodo) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::selectDB('maker_ciclo', 'id_mc', 'Ciclo', $id_mc) ?>
                </div>
                <div class="col">
                    <?= formErp::select('diaSem', [2 => 'Segunda', 3 => 'Terça', 4 => 'Quarta', 5 => 'Quinta', 6 => 'Sexta'], 'Dia da Semana', $diaSem) ?>
                </div>
                <div class="col">
                    <button class="btn btn-primary">
                        Pesquisar
                    </button>
                </div>
            </div>
            <br />
        </form>
    </div>
    <br /><br />
    <?php
    if ($id_polo) {
        ?>
        <div class="border">
            <form method="POST">
                <?=
                formErp::hidden([
                    'id_polo' => $id_polo,
                    'periodo' => $periodo,
                    'id_mc' => $id_mc,
                    'diaSem' => $diaSem
                ])
                ?>
                <div class="row">
                    <div class="col">
                        <?php
                        if ($id_polo) {
                            $turmas = $model->turmasPolo($id_polo, $periodo, $diaSem);
                            if ($turmas) {
                                echo formErp::select('id_turma', $turmas, 'Turma', $id_turma);
                            }
                        }
                        ?>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary">
                            Pesquisar
                        </button>
                    </div>
                    <div class="col">
                        <?php
                        if (!empty($alunos)) {
                            ?>
                            <?= count($alunos) ?> encontrado.
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
        <br />
        <?php
        if (!empty($turmaForm)) {
            ?>
            <div class="border">
                <div class="row">
                    <?php
                    $ct = 0;
                    foreach ($turmaForm as $k => $v) {
                        ?>
                        <div class="col">
                            <form method="POST">
                                <?=
                                formErp::hidden([
                                    'id_polo' => $id_polo,
                                    'periodo' => $periodo,
                                    'id_mc' => $id_mc,
                                    'diaSem' => $diaSem,
                                    'id_turma' => $k
                                ]);
                                if (@$v['qt'] > 10) {
                                    $class = 'info';
                                } else {
                                    $class = 'danger';
                                }
                                ?>
                                <button style="width: 100%" class="btn btn-<?= $class ?>">
                                    <?= $v['n_turma'] ?>
                                    <br /> 
                                    <?= intval(@$v['qt']) ?> aluno<?= @$v['qt'] > 1 ? 's' : '' ?>
                                    <br />
                                    <?php
                                    if ($v['transporte'] == 1) {
                                        echo 'Com Transporte';
                                    } else {
                                        echo 'Sem Transporte';
                                    }
                                    ?>
                                </button>
                            </form>
                        </div>
                        <?php
                        $ct++;
                        if ($ct % 6 == 0) {
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
            </div>
            <br />
            <?php
        }
        if (!empty($form)) {
            report::simple($form);
        }
    }
    ?>
</div>


<form action="<?= HOME_URI ?>/maker/def/formAlunoTransf" target="frame" id="formTrans" method="POST">
    <?=
    formErp::hidden([
        'id_polo' => $id_polo,
        'periodo' => $periodo,
        'id_mc' => $id_mc,
        'diaSem' => $diaSem,
        'id_turma' => $id_turma
    ])
    ?>
    <input type="hidden" name="id_ta" id="id_taTransf" value="" />
</form>

<form action="<?= HOME_URI ?>/maker/def/formAlunoMatr" target="frame" id="formMatr" method="POST">
    <?=
    formErp::hidden([
        'id_polo' => $id_polo,
        'periodo' => $periodo,
        'id_mc' => $id_mc,
        'diaSem' => $diaSem,
    ])
    ?>
    <input type="hidden" name="id_ma" id="maid" value="" />
</form>

<form id="formDevolve" method="POST">
    <?=
    formErp::hidden([
        'id_polo' => $id_polo,
        'periodo' => $periodo,
        'id_mc' => $id_mc,
        'diaSem' => $diaSem,
        'id_turma' => $id_turma
    ])
    . formErp::hiddenToken('desativar')
    ?>
    <input type="hidden" name="id_ta" id="idtaExclui" value="" />
</form>

<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>


    function transf(id) {
        id_taTransf.value = id;
        formTrans.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function matr(id) {
        if (id) {
            maid.value = id;
        } else {
            maid.value = '';
        }
        formMatr.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function fila(ta, nome) {
        if (confirm('Excluir o(a) aluno(a) ' + nome + '?')) {
            idtaExclui.value = ta;
            formDevolve.submit();
        }
    }
</script>