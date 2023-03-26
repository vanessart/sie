<?php
if (!defined('ABSPATH'))
    exit;
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'fk_id_projeto', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_STRING);
$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_SANITIZE_STRING);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_flex = filter_input(INPUT_POST, 'id_flex', FILTER_SANITIZE_NUMBER_INT);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_SANITIZE_STRING);
$titulo = 'Flexibilização Curricular do Projeto: ' . $n_projeto;
$id_pessoa = toolErp::id_pessoa();
$alunos = $model->ListAlunosAEE($id_turma);
if ($id_flex) {
    $flex = sql::get('profe_projeto_flex', 'id_flex, flex, fk_id_pessoa_prof, fk_id_pessoa, dt_inicio, dt_fim ', 'WHERE id_flex =' . $id_flex, 'fetch', 'left');
}?>
<style type="text/css">
    .input-group-text
    {
        width: 220px;
    }
</style>
<div class="body">
    <?= toolErp::divAlert('warning','Flexibilização Curricular para alunos com deficiência. Ajuste SE NECESSÁRIO.') ?>
    <div class="fieldTop" style="padding-bottom: 5%;">
        <?= $titulo ?>
    </div>
    <form id='form'  action="<?= HOME_URI ?>/profe/projeto" method="POST" target='_parent'>   
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', empty($flex['dt_inicio']) ? date("Y-m-d") : $flex['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', @$flex['dt_fim'], null, null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_pessoa]', $alunos, 'Aluno',@$flex['fk_id_pessoa']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[flex]', @$flex['flex'], 'Flexibilização Curricular','300') ?>
            </div>
        </div>
        <br> 
        <div class="row">
            <div class="col text-center">

                <?=
                formErp::hidden([
                    'activeNav' => 5,
                    '1[fk_id_projeto]' => $id_projeto,
                    '1[fk_id_pessoa_prof]' => $id_pessoa,
                    'fk_id_projeto' => $id_projeto,
                    'fk_id_turma' => $id_turma,
                    '1[id_flex]' => @$id_flex,
                    'fk_id_disc' => @$id_disc,
                    'fk_id_ciclo' => @$id_ciclo,
                    'n_projeto' => $n_projeto,
                    'msg_coord' => $msg_coord,
                    'n_turma' => $n_turma,
                    'id_inst' => $id_inst
                ])
                . formErp::hiddenToken('profe_projeto_flex', 'ireplace')
                . formErp::button('Salvar',null,'enviar()');
                ?>            
            </div>
        </div>     
    </form>
</div>
<form id='formBimestre' method="POST"> 
    <?=
    formErp::hidden([
        'activeNav' => 5,
        'id_projeto' => $id_projeto,
        'fk_id_projeto' => $id_projeto,
        'fk_id_turma' => $id_turma,
        'id_flex' => @$id_flex,
        'fk_id_disc' => @$id_disc,
        'fk_id_ciclo' => @$id_ciclo,
        'n_projeto' => $n_projeto,
        'msg_coord' => $msg_coord,
        'n_turma' => $n_turma,
        'id_inst' => $id_inst
    ]) ?> 
</form>
<script>
    function enviar(){
        data1 = document.getElementsByName("1[dt_inicio]")[0].value;
        data2 = document.getElementsByName("1[dt_fim]")[0].value;

        var data = new Date();
        // Guarda cada pedaço em uma variável
        var dia     = data.getDate();
        var mes     = data.getMonth()+1;
        var ano4    = data.getFullYear();
        var hoje = ano4  + '-' + String(mes).padStart(2,'0') + '-' + String(dia).padStart(2,'0');
        
        if(data1 == ""){
            alert("Informe a Data Inicial.");
            return false;
        }

        if(data2 == ""){
            alert("Informe a Data Final.");
            return false;
        }

        var iDataInicio = data1.split("-");
        var iDataInicio = parseInt(iDataInicio[0].toString() + iDataInicio[1].toString() + iDataInicio[2].toString());

        var iDataHoje = hoje.split("-");
        var iDataHoje = parseInt(iDataHoje[0].toString() + iDataHoje[1].toString() + iDataHoje[2].toString());

        if(data2 == ""){
            data2 = data1;
        }else{

            var aDataLimite = data2.split("-");
            var iDataLimite = parseInt(aDataLimite[0].toString() + aDataLimite[1].toString() + aDataLimite[2].toString()); 
                
            if(iDataInicio > iDataLimite){
                alert("A data Final não pode ser anterior à data Inicial.");
                return false;
            }
        }

        document.getElementById("form").submit();
    }
    function apaga(id) {
        delete exist[id];
        document.getElementById(id).innerHTML = '';
    }
</script>
