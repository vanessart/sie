<?php
if (!defined('ABSPATH'))
    exit();

$turmas = $model->getTurmasProf();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_inst = toolErp::id_inst();
$ap_aluno = 0;
$form=[];
if (!empty($id_turma)) {
    $ap_aluno = $model->getAlunosProf($id_turma);
}else{
    foreach ($turmas as $v) {
        $id_turma = $v["id_turma"];
        if ($id_turma) {
            break;
        }
    }
   if (!empty($id_turma)) {
        $ap_aluno = $model->getAlunosProf($id_turma);
    } 
}

if (!empty($turmas)) {
    foreach ($turmas as $v){
            $n_turmas[$v['id_turma']]= $v['n_inst'].' - '.$v['n_turma'];
        }
    ?>
    <div class="col">
        <?= formErp::select('id_turma', $n_turmas, 'Turmas', $id_turma, 1, ["id_turma" => $id_turma]) ?>
    </div>
     <?php
}else{?>
    <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div class="col" style="font-weight: bold; text-align: center;">
                Verifique com a secretaria Escolar se há alunos cadastrados nesta turma
            </div>
        </div>
    </div>
<?php
}
    
if ($ap_aluno) {
    foreach ($turmas as $v){
        $n_turmas[$v['id_turma']]= $v['n_turma'];
    }
    
    if ($ap_aluno) {
        foreach ($ap_aluno as $k => $v) {
            $hidden = [
                'id_pessoa' => $v['id_pessoa'], 
                'id_pessoa_apd' => $v['id_pessoa'], 
                'activeNav' => 2,
                'n_pessoa' => $v['n_pessoa'],
                'id_turma' => $v['id_turma'],
                'n_turma' => $v['n_turma'],
            ];
            $hidden2 = [
                'id_pessoa' => $v['id_pessoa'], 
                'id_pessoa_apd' => $v['id_pessoa'], 
                'activeNav' => 3,
                'n_pessoa' => $v['n_pessoa'],
                'id_turma' => $v['id_turma'],
                'n_turma' => $v['n_turma'],
            ];
            $ap_aluno[$k]['form'] = formErp::submit('Documentação', null, $hidden,null,null,null,'btn btn-outline-info');
            $ap_aluno[$k]['anexo'] = formErp::submit('Anexos', null, $hidden2,null,null,null,'btn btn-outline-info');
        }
        
        $form['array'] = $ap_aluno;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Aluno' => 'n_pessoa',
            'Turma' => 'n_turma',
            'Deficiência' => 'def',
            '||1' => 'form',
            '||2' => 'anexo',
        ];
    }
}
?>

<?php
if (toolErp::id_nilvel() == 10) {
    ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_inst', escolas::idEscolas(), 'Escolas', @$id_inst, 1, ['id_inst' => @$id_inst, 'id_pessoa' => @$id_pessoa]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_pessoa', $Listaluno, 'Alunos', @$id_pessoa, 1, ['id_inst' => @$id_inst, 'id_pessoa' => @$id_pessoa]) ?>
        </div>
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
        <?=
        formErp::hidden(['id_inst' => $id_inst])
        . formErp::hiddenToken('ge_aluno_necessidades_especiais', 'ireplace')
        ?>
    </form>
    <script>
        function porte(id, idDef) {
            id_porte.value = id;
            id_def.value = idDef;
            formPorte.submit();
        }
    </script>
    <?php
}