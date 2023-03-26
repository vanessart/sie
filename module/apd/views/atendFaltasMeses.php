<?php
if (!defined('ABSPATH'))
    exit();

$turmas = $model->getTurmasProf();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_inst = toolErp::id_inst();
$arrFaltas = 0;
$form=[];
if (!empty($id_turma)) {
    $arrFaltas = $model->getAtendFaltasRel($id_turma);
}else{
    foreach ($turmas as $v) {
        $id_turma = $v["id_turma"];
        if ($id_turma) {
            break;
        }
    }
   if (!empty($id_turma)) {
        $arrFaltas = $model->getAtendFaltasRel($id_turma);
    } 
}?>
<div class="body">
<?php

if (!empty($turmas)) {
    foreach ($turmas as $v){
            $n_turmas[$v['id_turma']]= $v['n_turma'];
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
if ($arrFaltas) {

    $Meses = $model->getAtendFaltasRel_MESES($arrFaltas);
    
    foreach ($turmas as $v){
        $n_turmas[$v['id_turma']]= $v['n_turma'];
    }?>
     <div class="row">
        <div class="col col-form-label">
            <div><strong>ANEXOS</strong></div> 
            <table class="table">
                <tr>
                    <th align="center" style="width:8%">Nome</th>
                    <?php 
                    foreach ($Meses as $v) {?>
                        <th colspan="3" width="8%" style="padding-right: 50px;"><?= $v ?></th>
                        <?php
                    }?>
                </tr>
                <?php
                foreach ($arrFaltas as $k => $v) {?>  
                    <tr>
                        <td>ARTHUR GAEL BALBINO DE OLIVEIRA</td>
                        <?php 
                        foreach ($Meses as $k => $v) {
                            $outline = "";
                            if ($k % 2 <> 0) {
                                $outline = "-outline";
                            }
                            ?>
                            <td>
                                <?= formErp::submit(1, null, null, null, 1,null, 'btn btn'.$outline.'-success');  ?>
                            </td>
                            <td>
                                <?= formErp::submit(2, null, null ,null,null,null,'btn btn'.$outline.'-danger'); ?>
                            </td>
                            <td>
                                <?= formErp::submit(3, null, null, null, 1,null, 'btn btn'.$outline.'-info');  ?> 
                            </td>
                            <?php
                        }?>
                    </tr>         
                    <?php
                }?>
            </table>
        </div>
    </div>
    <?php
}
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

    ?>
 </div>   
    
    