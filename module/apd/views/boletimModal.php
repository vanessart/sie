<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_porte = filter_input(INPUT_POST, 'id_porte', FILTER_SANITIZE_NUMBER_INT);
$id_adapt = filter_input(INPUT_POST, 'id_adapt', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$dt_nasc = filter_input(INPUT_POST, 'dt_nasc', FILTER_SANITIZE_STRING);
$prof_turma = $model->profePEBI($id_pessoa);
$porte = $model->getPorte($id_pessoa);
$n_inst = toolErp::n_inst();
$adaptacao = sql::get('apd_aluno_adaptacao', 'id_aluno_adaptacao, fk_id_pessoa_prof, fk_id_turma_aluno', ['id_aluno_adaptacao' => $id_adapt, 'qt_letiva' => $bimestre], 'fetch');
//$prof_AEE = toolErp::n_pessoa($adaptacao["fk_id_pessoa_prof"]);
$prof_AEEArray = $model->getProfAEE($id_pessoa);

if (!empty($prof_AEEArray)) {
   $prof_AEE = $prof_AEEArray['n_pessoa']; 
}else{
    $prof_AEE = '';
}
$apd_componente = sql::get('apd_componente', 'sit_didatica, recurso, n_componente, unidade, objeto, habilidade, fk_id_nota', ['fk_id_aluno_adaptacao' => $adaptacao["id_aluno_adaptacao"]]);
$apd_parecer = sql::get('apd_parecer', 'n_parecer, atvd_estudo, instr_avaliacao', ['fk_id_aluno_adaptacao' => $adaptacao["id_aluno_adaptacao"]], 'fetch');
?>
<style type="text/css">
    .titulo_anexo{
        color: grey;
        font-weight: bold;
        text-align: center;
    }
    .sub_anexo{
        font-weight: bold;
        text-align: center;
    }
    .tit_parecer{
        font-weight: bold;
        text-align: center;
        font-size: 12px;
    }
    .comp{
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        color: red;
    }
    .tit_table{
        font-weight: bold;]
    }
    .tabela{
        width: 100%;
        border: solid 1px;
        font-size: 12px;
    }
    tr{
        border: solid 1px; 
    }
    td{
        border: solid 1px; 
    }
    .tabela td{
        padding: 4px;
    }
</style>
<?php
if ($id_porte == 3) {//gd porte
    include ABSPATH . "/module/apd/views/def/adptGdPorte.php";
}else{ 
    include ABSPATH . "/module/apd/views/def/adptPqPorte.php";
}
    
    
    