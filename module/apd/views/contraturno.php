<?php
if (!defined('ABSPATH'))
    exit();
    
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$alunos = 0;
$form=[];
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
//$id_inst = $id_inst ?? toolErp::id_inst();
$hidden = [];
$hidden = 
[
    'id_inst' => @$id_inst, 
    'id_turma' => @$id_turma
];
$turmas = [];
if (!empty($id_inst)) {
   $turmas = $model->getTurmasAEE($id_inst); 
}

$escolas = $model->getEscolaAEE();

if (!empty($id_turma)) {
    $alunos = $model->getAlunosTurmaTurno($id_turma);
}else{
    foreach ($turmas as $v) {
        $id_turma = $v["id_turma"];
        if ($id_turma) {
            break;
        }
    }
   if (!empty($id_turma)) {
        $alunos = $model->getAlunosTurmaTurno($id_turma);
    } 
}?>
<div class="body">
    <div class="fieldTop">Remanejar Aluno</div>
        <div class="row">
            <div class="col">     
                <?= formErp::select('id_inst', $escolas, 'Escolas', @$id_inst, 1) ?>
            </div>
        <?php
    if (!empty($turmas)) {
        foreach ($turmas as $v){
                $n_turmas[$v['id_turma']]= $v['n_turma'];
            }
        ?>
    <div class="col">
        <?= formErp::select('id_turma', $n_turmas, 'Turmas', $id_turma, 1, $hidden) ?>
    </div>
 </div>
<br />
     <?php
}elseif (!empty($id_inst)){?>
    <div class="alert alert-warning">
        Verifique com a secretaria Escolar se há alunos cadastrados nesta turma
    </div>
    <?php
}   
if ($alunos) {
    foreach ($alunos as $k => $v) {
        $btn = 0;
        if (!empty($v['fk_id_turma_AEE'])) {
            $btn = 1;
            $turma_oficial = sql::get('ge_turmas', 'n_turma', ['id_turma' => $v['fk_id_turma_AEE'] ], 'fetch');
            if (!empty($turma_oficial)) {
               $alunos[$k]['turma_oficial'] = $turma_oficial ['n_turma']; 
            }      
        }
        if ($btn == 0) {
           $alunos[$k]['edit'] = '<button class="btn btn-outline-info" onclick="edit(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\')" style="width: 107px;">Remanejar</button>';
        }else{
            $o_a = toolErp::sexoArt(toolErp::sexo_pessoa($v['id_pessoa']));
            $alun = $v['n_pessoa'];
            $msg = "Deseja Remover $o_a $alun desta turma e retorná-l$o_a à sua turma Oficial?";
           $alunos[$k]['edit'] = '<button class="btn btn-outline-danger" onclick="del(' . $v['id_pessoa'] . ',\'' . $msg . '\')" style="width: 107px;">Remover</button>'; 
        } 
    }
    $form['array'] = $alunos;
    $form['fields'] = [
        'RSE' => 'id_pessoa',
        'Aluno' => 'n_pessoa',
        'Turma Oficial' => 'turma_oficial',
        'Turma' => 'n_turma',
        'Deficiência' => 'n_ne',
        'Porte' => 'n_porte',
        '||1' => 'edit',
    ];
    report::simple($form);?>
    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" name="id_inst" id="id_inst" value="<?= $id_inst ?>" />
        <input type="hidden" name="id_turma" id="id_turma" value="<?= $id_turma ?>" />
        <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    </form>
    <form id="Formdel" action="" method="POST">
        <input type="hidden" name="fk_id_pessoa_aluno" id="fk_id_pessoa_aluno" value="" />
        <?php
           echo formErp::hidden([   
            'id_turma' => $id_turma,       
            'id_inst' => $id_inst,       
            ])
            . formErp::hiddenToken('removeAluno');
        ?>            
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 30vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
}elseif (!empty($id_inst)){?>
    <div class="alert alert-warning">
        Verifique se há alunos Cadastrados nesta turma
    </div>
    <?php
}?>
 </div> 
 <script>
    function edit(id_pessoa,n_pessoa){
        document.getElementById("id_pessoa").value = id_pessoa;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = n_pessoa;
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/trocaTurma';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function del(id_pessoa,msg){
        if (confirm(msg) == true) {
            document.getElementById("fk_id_pessoa_aluno").value = id_pessoa;
            document.getElementById("Formdel").action = '<?= HOME_URI ?>/apd/contraturno';
            document.getElementById("Formdel").submit();
        } 
    }
</script>  
    
    