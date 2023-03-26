<?php
if (!defined('ABSPATH'))
    exit();
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_nilvel() == 22) {
    $escolas = $model->getEscolasSupervisor();
} else {
    $escolas = $model->idEscolas();
}
$list_todos = 0;
if ($id_inst == -1) {
    $list_todos = 1;
}
if (empty($id_inst) || $id_inst == -1) {
    if (!empty($escolas)) {
        foreach ($escolas as $k => $v) {
            $id_inst = $k;
            if ($id_inst) {
                break;
            }
        }
    } else {
        echo toolErp::divAlert('warning', 'Não há escolas ou setor cadastrados para este usuário.');
        exit;
    }
}

if (toolErp::id_nilvel() == 48) {
    $id_inst = toolErp::id_inst();
} else {
    $Listaluno = $model->ListAlunoAEE();
}
$porte = sqlErp::idNome('ge_aluno_necessidades_especiais_porte');
if ($list_todos == 1) {//somente para gerente ou in loco
    $ap_aluno = $model->alunosEsc(null, $id_pessoa);
    foreach ($ap_aluno as $k => $v) {
        $id_inst = $v['id_inst'];
        if ($id_inst) {
            break;
        }
    }
} else {
    $ap_aluno = $model->alunosEsc($id_inst, $id_pessoa);
}
if ($ap_aluno) {
    $clas = [
        1 => 'warning',
        2 => 'primary',
        3 => 'info'
    ];
    foreach ($ap_aluno as $k => $v) {
        $n_turma = $v['turmaRegular'] . ' / ' . $v['turmaAEE'];
        $hidden = [
            'id_pessoa' => $v['id_pessoa'],
            'id_pessoa_apd' => $v['id_pessoa'],
            'activeNav' => 3,
            'n_pessoa' => $v['n_pessoa'],
            'id_turma' => $v['id_turma'],
            'n_turma' => $n_turma,
            'id_inst' => $id_inst
        ];

        $hidden2 = [
            'id_pessoa' => $v['id_pessoa'],
            'id_pessoa_apd' => $v['id_pessoa'],
            'activeNav' => 2,
            'n_pessoa' => $v['n_pessoa'],
            'id_turma' => $v['id_turma'],
            'n_turma' => $n_turma,
        ];

        $fm = '<select class="btn btn-' . $clas[$v['id_porte']] . '" onchange="this.options[this.selectedIndex].onclick()">';
        foreach ($porte as $kp => $vp) {
            if ($v['id_porte'] == $kp) {
                $selected = 'selected';
            } else {
                $selected = null;
            }
            $fm .= '<option ' . $selected . '  onclick="porte(' . $kp . ', ' . $v['id_def'] . ', ' . $v['id_pessoa'] . ', ' . $v['ISalunoAEE'] . ')" >' . $vp . '</option>';
        }
        $fm .= '</select>';
        $ap_aluno[$k]['n_turma'] = $n_turma;
        $ap_aluno[$k]['porte'] = $fm;
        $ap_aluno[$k]['doc'] = formErp::submit('Documentos', null, $hidden2);
        $ap_aluno[$k]['form'] = formErp::submit('Formulários', null, $hidden);
    }

    $form['array'] = $ap_aluno;
    if (!in_array(toolErp::id_nilvel(), [18, 22])) {
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Aluno' => 'n_pessoa',
            'Período' => 'periodo',
            'Turma' => 'n_turma',
            'Deficiência' => 'n_ne',
            'Porte' => 'porte',
            '||3' => 'doc',
            '||1' => 'form',
        ];
    } else {
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Aluno' => 'n_pessoa',
            'Período' => 'periodo',
            'Deficiência' => 'n_ne',
            'Turma' => 'n_turma',
            '||1' => 'form',
        ];
    }
}

if (toolErp::id_nilvel() <> 48) { ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_inst', $escolas, 'Escolas', @$id_inst, 1, ['id_inst' => @$id_inst, 'id_pessoa' => @$id_pessoa]) ?>
        </div>
        <?php if (toolErp::id_nilvel() == 10) { ?>
            <div class="col">
                <?= formErp::select('id_pessoa', $Listaluno, 'Alunos', @$id_pessoa, 1, ['id_inst' => -1, 'id_pessoa' => @$id_pessoa]) ?>
            </div>
            <div class="col">
                <form method="POST">
                    <button class="btn btn-warning">
                        Limpar Filtro
                    </button>
                </form>
            </div>
        <?php }
        ?>

    </div>
    <br />

    <?php
}

if (!empty($form)) {
    report::simple($form);
    ?>
    <form id="formPorte" method="POST">
        <input type="hidden" name="1[fk_id_porte]" id="id_porte"  />
        <input type="hidden" name="1[id_def]" id="id_def"  />
        <input type="hidden" name="1[id_pessoa]" id="id_pessoa" />
        <?=
        formErp::hidden(['id_inst' => $id_inst])
        . formErp::hiddenToken('ge_aluno_necessidades_especiaisSet')
        ?>
    </form>
    <script>
        function porte(id, idDef, idPessoa, ISalunoAEE) {
            id_pessoa.value = idPessoa;
            id_porte.value = id;
            id_def.value = idDef;
            if ((id == 3) && (ISalunoAEE == 0)) {
                alert ("Anexe o laudo do aluno na aba 'Documentos'");
            }
            formPorte.submit();
        }
    </script>
    <?php
}