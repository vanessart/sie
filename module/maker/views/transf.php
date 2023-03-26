<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst_ = toolErp::id_inst();
} else {
    $id_inst_ = filter_input(INPUT_POST, 'id_inst_', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
if ($id_inst_) {
    $sql = "SELECT "
            . " p.fk_id_inst_maker, n_polo, id_polo "
            . " FROM maker_escola e "
            . " JOIN maker_polo p on p.id_polo = e.fk_id_polo "
            . " WHERE fk_id_inst = $id_inst_ ";
    $query = pdoSis::getInstance()->query($sql);
    $polo = $query->fetch(PDO::FETCH_ASSOC);

    if ($polo) {
        $id_inst = $polo['fk_id_inst_maker'];
        $nPolo = $polo['n_polo'];
        $id_polo = $polo['id_polo'];
    } else {
        ?>
        <div class="alert alert-danger">
            Sua escola não está configurada para participar das Salas Maker
        </div>
        <?php
    }
}

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$periodo = null;

$id_mc = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
$diaSem = filter_input(INPUT_POST, 'diaSem', FILTER_SANITIZE_NUMBER_INT);
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
$polos = sql::idNome('maker_polo');
if ($setup['transf'] == 'x') {
    $diaLiberado = ['x' => 'x'];
} else {
    $diaLiberado = explode(',', $setup['transf']);
}

if (!empty($id_polo)) {
    $falta = $model->falta($id_polo, $setup['id_pl']);
    $turmaForm = $model->turmasPoloNg($id_polo);

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
    }
    $alunos = $model->alunosEscNg($id_inst_, $id_mc, $id_turma, $periodo, $diaSem);
    if ($alunos) {
        foreach ($alunos as $k => $v) {
            $alunos[$k]['del'] = '<button onclick="fila(' . $v['id_ta'] . ', \'' . $v['n_pessoa'] . '\')" class="btn btn-danger" >Excluir</button>';
            //$alunos[$k]['transf'] = '<button onclick="transf(' . $v['id_ta'] . ')" class="btn btn-primary" >Transferir</button>';
            if ($v['transporte'] == 1) {
                $alunos[$k]['transporte'] = '<button onclick="trasporte(' . $v['id_ta'] . ', 0, \'' . $v['n_pessoa'] . '\')" class="btn btn-success" >Sim</button>';
            } else {
                $alunos[$k]['transporte'] = '<button onclick="trasporte(' . $v['id_ta'] . ', 1, \'' . $v['n_pessoa'] . '\')" class="btn btn-secondary" >Não</button>';
            }
            $alunos[$k]['falta'] = intval(@$falta[$v['id_pessoa']]);
        }
        $form['array'] = $alunos;

        $diaSemana = date('w', strtotime(date("Y-m-d")));

        if (in_array($diaSemana, $diaLiberado)) {
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Nome' => 'n_pessoa',
                'Ciclo' => 'n_mc',
                'Período' => 'periodo',
                'Polo' => 'n_polo',
                'Turma' => 'n_turma',
                'Faltas' => 'falta',
                'Transporte' => 'transporte',
                '||3' => 'del',
                '||2' => 'transf'
            ];
        } else {
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Nome' => 'n_pessoa',
                'Dt. Inscr' => 'data',
                'Ciclo' => 'n_mc',
                'Período' => 'periodo',
                'Dia' => 'dia',
                'Turma' => 'n_turma',
                'Faltas' => 'falta',
            ];
        }
    }
}
?>
<div class="body">
    <div class="row">
        <?php
        if (toolErp::id_nilvel() != 8) {
            ?>
            <div class="col">
                <?= formErp::select('id_inst_', $escolas, 'Escola', $id_inst_, 1) ?>
            </div>
            <?php
        }
        if (!empty($id_polo)) {
            if (!empty($id_inst)) {
                ?>
                <div class="col" style="font-weight: bold; font-size: 1.3em; text-align: center">
                    Polo: <?= $nPolo ?>
                </div>
                <?php
            }
            ?>
        </div>
        <br /><br />
        <?php
        if ($id_polo) {
            ?>
            <div class="border">
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
                                            'id_turma' => $v['id_turma'],
                                            'id_inst_' => $id_inst_
                                        ]);
                                        if (@$v['qt'] >= 30 && $v['id_turma'] != $id_turma) {
                                            $cl = 'danger';
                                        } else {
                                            $cl = (empty($v['class']) ? 'info' : $v['class']);
                                        }
                                        ?>
                                        <button style="width: 100%" class="btn btn-<?= $cl ?>">
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
                    </div>
                    <br />
                    <?php
                    if (!empty($codigoTurma)) {
                        ?>
                        <div style="text-align: center; font-weight: bold; font-size: 1.2em">
                            <?= $codigoTurma ?>
                        </div>
                        <?php
                    }
                    ?>
                    <br />
                    <?php
                }
                if (!empty($form)) {
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
                        <div class="row">
                            <div class="col text-center">
                                <button class="btn btn-primary" onclick="matr()">
                                    Matricular novo Aluno
                                </button>
                            </div>
                            <div class="col text-center">
                                <a class="btn btn-warning" href="<?= HOME_URI ?>/maker/transf">Limpar Filtro</a>
                            </div>
                        </div>
                        <br />

                        <?php
                    }
                    report::simple($form);
                }
            }
            ?>
        </div>



        <form action="<?= HOME_URI ?>/maker/def/formAlunoMatrEsc" target="frame" id="formMatr" method="POST">
            <?=
            formErp::hidden([
                'id_polo' => $id_polo,
                'periodo' => $periodo,
                'id_mc' => $id_mc,
                'diaSem' => $diaSem,
                'id_inst_' => $id_inst_
            ])
            ?>
        </form>

        <form action="<?= HOME_URI ?>/maker/def/formAlunoTransfEsc" target="frame" id="formTransf" method="POST">
            <?=
            formErp::hidden([
                'id_polo' => $id_polo,
                'periodo' => $periodo,
                'id_mc' => $id_mc,
                'diaSem' => $diaSem,
                'id_turma' => $id_turma,
                'id_inst_' => $id_inst_
            ])
            ?>
            <input type="hidden" name="id_ta" id="id_ta_transf" value="" />
        </form>

        <form id="formDevolve" method="POST">
            <?=
            formErp::hidden([
                'id_polo' => $id_polo,
                'periodo' => $periodo,
                'id_mc' => $id_mc,
                'diaSem' => $diaSem,
                'id_turma' => $id_turma,
                'id_inst_' => $id_inst_
            ])
            . formErp::hiddenToken('desativar')
            ?>
            <input type="hidden" name="id_ta" id="idtadel" value="" />
        </form>
        <form id="formtrans" method="POST">
            <?=
            formErp::hidden([
                'id_polo' => $id_polo,
                'periodo' => $periodo,
                'id_mc' => $id_mc,
                'diaSem' => $diaSem,
                'id_turma' => $id_turma,
                'id_inst_' => $id_inst_
            ])
            . formErp::hiddenToken('maker_gt_turma_aluno', 'ireplace')
            ?>
            <input type="hidden" name="1[transporte]" id="transporte" value="" />
            <input type="hidden" name="1[id_ta]" id="id_taTransp" value="" />
        </form>

        <?php
        toolErp::modalInicio();
        ?>
        <iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
            <?php
            toolErp::modalFim();
            ?>
        <script>


            function matr() {
                formMatr.submit();
                $('#myModal').modal('show');
                $('.form-class').val('');
            }

            function transf(id) {
                if (id) {
                    id_ta_transf.value = id;
                } else {
                    id_ta_transf.value = '';
                }
                formTransf.submit();
                $('#myModal').modal('show');
                $('.form-class').val('');
            }
            function fila(ta, nome) {
                if (confirm('Excluir o(a) aluno(a) ' + nome + '? Esta ação é irreversível!')) {
                    idtadel.value = ta;
                    formDevolve.submit();
                }
            }
            function trasporte(id, t, nome) {
                if (t === 1) {
                    frase = 'O(A) aluno(a) ' + nome + '  é atualmente USUÁRIO EFETIVO do transporte escolar?';
                } else {
                    frase = 'Retirar o(a) aluno(a) ' + nome + ' da lista de USUÁRIOS ATIVOS de transporte escolar?';
                }
                if (confirm(frase)) {
                    id_taTransp.value = id;
                    transporte.value = t;
                    formtrans.submit();
                }
            }
        </script>
        <?php
    }
    ?>
</div>