<?php
if (!defined('ABSPATH'))
    exit();
    
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$arrFaltas = 0;
$form=[];
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_inst = $id_inst ?? toolErp::id_inst();

$hidden = 
[
    'id_inst' => @$id_inst, 
    'id_turma' => @$id_turma
];
$turmas = [];
if (toolErp::id_nilvel() == 48) {
    $turmas = $model->getTurmasAEE($id_inst);
} elseif (toolErp::id_nilvel() == 10) {
    $turmas = $model->getTurmasAEE($id_inst);
    $escolas = $model->getEscolaAEE();
}elseif (toolErp::id_nilvel() == 24) {
    $turmas = $model->getTurmasProf();
}

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
    <div class="fieldTop">Relatório de Presenças e Faltas</div>
    <div class="row">
        <?php
        if (toolErp::id_nilvel() == 10) {
            ?>
                <div class="col">
                    <?php   //echo formErp::select('id_inst', escolas::idEscolas(), 'Escolas', @$id_inst, null,null,null,null,['turmaList', 'id_turma'])   ?>
                     
                    <?= formErp::select('id_inst', $escolas, 'Escolas', @$id_inst, 1) ?>
                </div>

            <?php
        }
        if (!empty($turmas)) {
            foreach ($turmas as $v){
                if (toolErp::id_nilvel()==24) {
                    $n_turmas[$v['id_turma']]= $v['n_inst'].' - '.$v['n_turma'];
                }else{
                    $n_turmas[$v['id_turma']]= $v['n_turma'];
                }
            } ?>
            <div class="col">
                <?= formErp::select('id_turma', $n_turmas, 'Turmas', $id_turma, 1, $hidden) ?>
            </div>
            <div class="col" style="text-align:right;">
                <button  class="btn btn-outline-success" style="width: 20px; height: 20px;"></button> Presença
                <button  class="btn btn-outline-danger" style="width: 20px; height: 20px;"></button> Falta
                <button  class="btn btn-outline-info" style="width: 20px; height: 20px;"></button> Total
                <button  class="btn btn-danger" style="width: 20px; height: 20px;"></button> Nº de Faltas maior que presença
            </div>
            <?php
        }else{?>
            <div class="alert alert-warning">
                Verifique com a secretaria Escolar se há alunos cadastrados nesta turma
            </div>
            <?php
        }?> 
    </div>
    <br /> 
    <?php
    if ($arrFaltas) { ?>
         <div class="row">
            <div class="col col-form-label">
                <table class="table">
                    <tr>
                        <th align="center" style="width:50%">NOME</th>
                        <?php for ($i=1; $i < 5; $i++) {?>
                        <th colspan="4" width="10%"><?= $i ?>º Bimestre</th>
                        <?php } ?>
                    </tr>
                    <?php
                    foreach ($arrFaltas as $k => $v) {
                        $nome = $v['nome'];
                    ?>
                        <tr>
                            <td><?= $nome ?></td>
                            <?php for ($i=1; $i < 5; $i++) {?>
                                <td>
                                    <?php
                                    $qtdeP = $qtdeF = $total = 0; 
                                    
                                    if (isset($v[$i]))
                                    {
                                        if ( isset( $v[$i][1] ) ) {
                                            $qtdeP = $v[$i][1];
                                        }
                                        if ( isset( $v[$i][0] ) ) {
                                            $qtdeF = $v[$i][0];
                                        }
                                        if ( isset( $v[$i][-1] ) ) {
                                            $total = $v[$i][-1];
                                        }
                                    }
                                    $outline = "-outline";
                                    if ($qtdeF > $qtdeP) {
                                        $outline = "";
                                    }
                                    ?>
                                    <button class="btn btn-outline-success" onclick='hist(<?= $i ?>,1,<?= $v["id_pdi"] ?>,"<?= $nome ?>","ATENDIMENTOS")'><?= $qtdeP ?></button>
                                </td>
                                <td>
                                    <button class="btn btn<?= $outline ?>-danger" onclick='hist(<?= $i ?>,"0",<?= $v["id_pdi"] ?>,"<?= $nome ?>","FALTAS")'><?= $qtdeF ?></button>
                                </td>
                                <td>
                                    <button class="btn btn-outline-info" onclick='hist(<?= $i ?>,null,<?= $v["id_pdi"] ?>,"<?= $nome ?>","ATENDIMENTOS E FALTAS")'><?= $total ?></button>
                                </td>
                                <td></td>
                                <?php
                            }?>
                        </tr>         
                        <?php
                    }?>
                </table>
            </div>
        </div>
        <form id="form" target="frame" action="" method="POST">
            <input type="hidden" name="id_pdi" id="id_pdi" value="" />
            <input type="hidden" name="presenca" id="presenca" value="" />
            <input type="hidden" name="bimestre" id="bimestre" value="" /> 
            <input type="hidden" name="presenca_texto" id="presenca_texto" value="" /> 
        </form>
        <?php
        toolErp::modalInicio();
        ?>
        <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
    }else{?>
        <div class="alert alert-warning">
            Verifique se o professor AEE preencheu o PDI<br>Verifique se o Professor registrou Presença e faltas
        </div>
        <?php
    }?>
 </div> 
 <script>
    function hist(bimestre,presenca,id_pdi,n_pessoa,texto){
        document.getElementById("id_pdi").value = id_pdi;
        document.getElementById("presenca").value = presenca;
        document.getElementById("bimestre").value = bimestre;
        document.getElementById("presenca_texto").value = texto;

        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = n_pessoa;
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/histAtend';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>  
    
    