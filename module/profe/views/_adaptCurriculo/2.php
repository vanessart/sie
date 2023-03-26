<?php
if (!defined('ABSPATH'))
    exit;
$fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT); 
$id_aluno_adaptacao = filter_input(INPUT_POST, 'id_aluno_adaptacao', FILTER_SANITIZE_NUMBER_INT); 
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT); 
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING); 
$dt_nasc = filter_input(INPUT_POST, 'dt_nasc', FILTER_SANITIZE_STRING); 
$id_inst = toolErp::id_inst();
$id_pessoa = toolErp::id_pessoa();
$n_aluno = toolErp::n_pessoa($fk_id_pessoa);

$hidden =[
'activeNav'=>2,
'fk_id_pessoa'=>$fk_id_pessoa, 
'n_pessoa'=>$n_pessoa,
'dt_nasc'=>$dt_nasc,
'id_turma'=>$id_turma,
'id_porte'=>$id_porte,
];
$o_a = toolErp::sexoArt(toolErp::sexo_pessoa($fk_id_pessoa));
$adapt_cur = sql::get('apd_aluno_adaptacao', 'id_aluno_adaptacao, apd_aluno_adaptacao.fk_id_pessoa, apd_aluno_adaptacao.qt_letiva, apd_aluno_adaptacao.un_letiva','WHERE apd_aluno_adaptacao.fk_id_pessoa ='.$fk_id_pessoa.' ORDER BY qt_letiva', NULL, 'left');


    if ($adapt_cur){
            
            $token = formErp::token('apd_aluno_adaptacao', 'delete');
            foreach ($adapt_cur as $k => $v){ 
                $adapt_cur[$k]['id_aluno_adaptacao'] = formErp::submit('Acessar', null, ['id_aluno_adaptacao' => $v['id_aluno_adaptacao'],'fk_id_pessoa' => $v['fk_id_pessoa'], 'activeNav'=>3,'n_pessoa'=>$n_pessoa,'id_turma'=>$id_turma,'id_porte'=>$id_porte]); 
                $adapt_cur[$k]['qt_letiva'] = $adapt_cur[$k]['qt_letiva'].'º '.$adapt_cur[$k]['un_letiva'];
                $adapt_cur[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_aluno_adaptacao]' => $v['id_aluno_adaptacao'],'activeNav'=>2, 'fk_id_pessoa'=>$fk_id_pessoa, 'n_pessoa'=>$n_pessoa,'id_turma'=>$id_turma,'id_porte'=>$id_porte]);
                $adapt_cur[$k]['boletim'] = '<button class="btn btn-outline-info" onclick="boletim(' . $v['id_aluno_adaptacao'] . ',' . $v['qt_letiva'] . ',' . $v['fk_id_pessoa'] . ', \'' . $id_turma . '\' ,\'' . $n_aluno . '\' )">Visualizar</button>';
        }

        $form['array']=$adapt_cur;
        $form['fields']=[
            'Adaptação Curricular'=>'qt_letiva',
            '||3' => 'boletim',
            //'||2' => 'del',
            '||1' => 'id_aluno_adaptacao'
        ];
    }

?>

<div class="body">
 <?php 
 if (toolErp::id_nilvel()==24) {?>
    <button class="btn btn-info" onclick="edit(<?= $fk_id_pessoa ?>)">
       Nova Adaptação Curricular
    </button>
    <br><br>
     <?php 
 } ?>
    <div>
        <form id="form" target="frame" action="" method="POST">
            <input type="hidden" name="fk_id_pessoa" id="fk_id_pessoa" value="<?= $fk_id_pessoa ?>" />
            <input type="hidden" name="id_pessoa" id="id_pessoa"  />
            <input type="hidden" name="id_adapt" id="id_adapt"  />
            <input type="hidden" name="n_pessoa" id="n_pessoa" value="<?= $n_pessoa ?> "/>
            <input type="hidden" name="id_turma" id="id_turma" value="<?= $id_turma ?>" />
            <input type="hidden" name="id_porte" id="id_porte" value="<?= $id_porte ?>" 
            />
             <input type="hidden" name="dt_nasc" id="dt_nasc" value="<?= $dt_nasc ?>" />
            <input type="hidden" name="bimestre" id="bimestre"  />
        </form>

        <?php
        toolErp::chat(1,toolErp::id_pessoa(),$fk_id_pessoa,$hidden,"Neste espaço os professores podem trocar informações ou sugerir ações para $o_a ".explode(' ', $n_pessoa)[0]);
        if(!empty($form)){
            report::simple($form);
        }
        toolErp::modalInicio();
        ?>
        <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    </div>
</div>
<script>

    function edit(id){
        if (id){
            document.getElementById("fk_id_pessoa").value = id;
        }else{
            document.getElementById("fk_id_pessoa").value = "";
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/adpt_curricular.php';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function boletim(id_adapt,bimestre,id,id_turma,n_pessoa){
        if (id){
            document.getElementById("id_adapt").value = id_adapt;
            document.getElementById("id_pessoa").value = id;
            document.getElementById("bimestre").value = bimestre;
            document.getElementById("id_turma").value = id_turma;
            document.getElementById("n_pessoa").value = n_pessoa;
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = "Boletim de <label class='modal_blue'>"+n_pessoa+"</label";
            document.getElementById("form").action = '<?= HOME_URI ?>/apd/boletimModal';
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    }

</script>
