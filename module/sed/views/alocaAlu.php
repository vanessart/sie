<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = tool::id_inst();
$cursos = sql::idNome('ge_cursos', null, 'n_curso');

$n_cursos = "Curso";

$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$plsArr = sql::get('ge_periodo_letivo', '*', ' where at_pl in (1,2)');
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_pessoa)) { 
    $dadosAluno = $model->getPessoa($id_pessoa);
    if (!empty($dadosAluno)) {
        $sexo = $dadosAluno['sexo'];
        $descricao2 = tool::sexoArt($sexo) .' alun'.tool::sexoArt($sexo).' <strong>'.$dadosAluno['n_pessoa'].'</strong>';
    } else {
        $descricao2 = 'Aluno';
    }
}else{
    $descricao2 = 'Aluno';
}
$hiddenFields = [];
foreach ($plsArr as $v) {
    $pls[$v['id_pl']] = $v['n_pl'];
    if (empty($id_pl) && $v['at_pl'] == 1) {
        $id_pl = $v['id_pl'];
    }
}
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

$hidden = [
    'id_pl' => $id_pl,
    'id_inst' => $id_inst,
    'id_turma' => $id_turma,
    'id_curso' => $id_curso,
    'id_pessoa' => $id_pessoa
];
$hidden2 = [
    'id_pl' => $id_pl,
    'id_inst' => $id_inst,
    'id_curso' => $id_curso,
    'id_pessoa' => $id_pessoa
];
$turmaSelecionada = [];
if ($id_inst) {
    if ($id_curso) { 
        $turmas = $model->getTurmasCurso($id_curso,null,$id_pl);
        if ($id_turma) {            
            $turmaSelecionada = array_filter($turmas, function ($turma) use ($id_turma) {
                return $turma['id_turma'] == $id_turma; 
            });
            $turmaSelecionada = array_values($turmaSelecionada);
            $turmaSelecionada = $turmaSelecionada[0];
        }
    } else {
        $turmas = [];
    }

    if ($id_curso) {

        $alunos = $model->alunoEsc($id_pl, $id_inst, $id_turma, $id_curso, null, null, null);

        if (!empty($alunos)) {
            $token = form::token('ge_turma_aluno', 'delete');
            foreach ($alunos as $k => $v) {
                // if (!empty($model->isPublicoCultura($v['id_pessoa']))) {
                    // $alunos[$k]['ne'] = $model->isNecessidadeInscricao($v['id_pessoa'],1);
                // } else {
                    $alunos[$k]['ne'] = $model->isAEE($v['id_pessoa'],null,1);
                // }
               // $alunos[$k]['ac'] = '<button onclick="transf(' . $v['id_turma_aluno'] . ',\'' . $v['n_pessoa'] . '\')" class="btn btn-outline-info">Transferir</button>';
               // $alunos[$k]['ex'] = form::submit('Excluir', $token, $hidden + ['1[id_turma_aluno]' => $v['id_turma_aluno']]);
               // $alunos[$k]['pr'] = '<button onclick="pront(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\')" class="btn btn-outline-info">Prontuário</button>';
            }
            $form['array'] = $alunos;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Nome' => 'n_pessoa',
                'NE' => 'ne',
                'Turma' => 'n_turma',
                'Escola' => 'n_inst',
                '||1' => 'ex',
                '||2' => 'pr',
                '||3' => 'ac',
            ];

            if (!$model::podeExcluirdaVaga()){
                unset($form['fields']['||1']);
            }
        }
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciamento de Alunos
    </div>
    <div class="row">
        <div class="col">
            <?= form::select('id_pl', $pls, 'Período Letivo', $id_pl, 1, $hidden2) ?>
        </div>
        <div class="col">
            <?= form::select('id_curso', $cursos, $n_cursos, $id_curso, 1, $hidden2) ?>
        </div>
        <div class="col">
            <form method="POST">
                <?=
                form::hidden($hidden)
                . form::hidden(['id_turma' => null, 'id_curso' => null])
                ?>
                <?php
                if ($id_inst && $id_turma) {
                    ?>
                    <button class="btn btn-warning">
                        Limpar o filtro de Turma e <?=$n_cursos?>
                    </button>
                    <?php
                }
                ?>
            </form>
            <form method="POST">
                <?=
                form::hidden($hidden)
                . form::hidden(['id_pessoa' => null])
                ?>
                <?php
                if ($id_pessoa) {
                    ?>
                    <br><br>
                    <button class="btn btn-warning">
                        Limpar o filtro por aluno
                    </button>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
    <br />
    <?php
    if ($id_inst) {
        ?>
        <br />
        <fieldset class="add-border">
            <legend class="add-border">Turmas <span class="text-muted" style="font-size: 12px;font-weight: normal;">( alunos na turma / qtde de vagas )</span></legend>
            <div class="row">
                <?php
                $ct = 1;
                if (!empty($turmas)) {
                    foreach ($turmas as $k => $v) {
                
                        // Verifica o estado da turma para definir a classe
                        if ($v['id_turma'] == $id_turma) {
                            $class = 'primary';
                        } elseif (@$v['quantidadeAlunos']['ct'] >= @$v['quantidadeAlunos']['qt_turma']) {
                            $class = 'danger';
                        } elseif (@$v['quantidadeAlunos']['ct'] < @$v['quantidadeAlunos']['qt_turma']) {
                            $class = 'warning';
                        } else {
                            $class = 'info';
                        }
                        ?>
                        <div class="col-3">
                            <form method="POST">
                                <?=
                                form::hidden($hidden)
                                . form::hidden([
                                    'id_turma' => $v['id_turma'] 
                                ])
                                ?>
                                <button style="width: 100%" class="btn btn-<?= $class ?>">
                                    <?= $v['botao'] ?>
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
                } else {
                    echo tool::divAlert('warning', 'Verifique se há turmas cadastradas para esta Oficina');
                } ?>

            </div>
        </fieldset>
        <br />
        <div class="row">
            <div class="col-3" style="text-align: center; padding: 10px">
                <?php
                if ($id_turma && $id_inst) {

                    $hiddenFields = [
                        'id_turma' => $id_turma,
                        'id_curso' => $id_curso,
                        'id_pl' => $id_pl,
                        'id_pessoa' => $id_pessoa,
                        'id_inst' => $turmaSelecionada['id_inst'],
                        'n_inst' => $turmaSelecionada['n_inst'],
                        'id_curso' => $turmaSelecionada['id_curso'],
                        'n_curso' => $turmaSelecionada['n_curso'],
                        'n_turma' => $turmaSelecionada['n_turma'],
                        'titulo_periodo' => $turmaSelecionada['detalhesTurma']['titulo_periodo'],
                        'periodo' => $turmaSelecionada['detalhesTurma']['periodo'],
                        'dia_hora' => $turmaSelecionada['detalhesTurma']['dia_hora'],
                        ];
                    ?>
                        <?php if (!empty($id_pessoa)) { 
                            $dadosAluno = $model->getPessoa($id_pessoa); 
                            ?>
                            <button 
                                style="width: 100%" 
                                type="button" 
                                class="btn btn-primary" 
                                data-n-pessoa="<?= $dadosAluno['n_pessoa'] ?>" 
                                onclick="novoAluno('<?= $id_pessoa ?>','<?= $dadosAluno['n_pessoa'] ?>')"
                            >
                                Incluir <strong><?= $dadosAluno['n_pessoa'] ?></strong> nesta turma
                            </button>
                            <br><br>
                        <?php 
                            $descricao = 'Incluir Outro Aluno'; 
                        } else { 
                            $descricao = 'Incluir Aluno'; 
                        } ?>
                        <button 
                            style="width: 100%" 
                            type="button" 
                            class="btn btn-info" 
                            data-n-pessoa="Novo Aluno" 
                            onclick="novoAluno('')"
                        >
                            <?= $descricao ?>
                        </button>
                    <?php
                
                    
                } elseif ($id_turma) {
                    ?>
                    <button style="width: 100%" type="button" class="btn btn-secondary" >
                        Para Incluir <?=$descricao2?>, Selecione uma Escola
                    </button>
                    <?php
                } else {
                    ?>
                    <button style="width: 100%" type="button" class="btn btn-secondary" >
                        Para Incluir <?=$descricao2?>, Selecione uma Turma
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
                            <td style="width: 20%">
                                <?= $turmaSelecionada['detalhesTurma']['n_turma'] ?>
                            </td>
                            <td style="width: 20%">
                                <?= $turmaSelecionada['detalhesTurma']['titulo_periodo'] ?> <?= $turmaSelecionada['detalhesTurma']['periodo'] ?>
                            </td>
                            <td style="width: 20%">
                                <?= $turmaSelecionada['detalhesTurma']['dia_hora'] ?>
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
        if ($id_curso) {
            if (!empty($form)) {
                report::simple($form);
            }
        }
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/sed/def/formTransf.php" id="transfx" target="frame" method="POST">
    <?=
    form::hidden($hidden);
    ?>
    <input type="hidden" name="id_ta" id="id_ta" value="" />
</form>
<form action="<?= HOME_URI ?>/sed/prontuario" id="pront" target="frame" method="POST">
    <?=
    form::hidden($hidden);
    ?>
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    <input type="hidden" name="id_curso" id="id_curso" value="<?= $id_curso?>" />
</form>
<form action="<?= HOME_URI ?>/sed/def/formNovoAluno.php" id="novoAluno" target="frame" method="POST">
<?=
    form::hidden($hidden) . 
    form::hidden($hiddenFields)
?>
    <input type="hidden" name="id_aluno" id="id_aluno" value="" />
</form>
<?php
tool::modalInicio(null, 1);
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
<?php
tool::modalFim();
?>
<script>
    function transf(id,n_pessoa) {
        if (id) {
            id_ta.value = id;
        } else {
            id_ta.value = "";
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = '<div style="text-align: center; color: #7ed8f5;">'+n_pessoa+'</div>';
        transfx.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function pront(id,n_pessoa) {
        if (id) {
            id_pessoa.value = id;
        } else {
            id_pessoa.value = "";
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = '<div style="text-align: center; color: #7ed8f5;">'+n_pessoa+'</div>';
        document.getElementById('pront').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function novoAluno(id,n_pessoa) {
        if (id) {
            id_aluno.value = id;
        } else {
            id_aluno.value = "";
            n_pessoa = 'Incluir Aluno';
        }
        document.getElementById('novoAluno').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
