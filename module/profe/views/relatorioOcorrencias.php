<?php
if (!defined('ABSPATH'))
    exit;
$dt_data1 = filter_input(INPUT_POST, 'dt_data1', FILTER_SANITIZE_STRING);
$dt_data2 = filter_input(INPUT_POST, 'dt_data2', FILTER_SANITIZE_STRING);
$turmas = ng_escola::turmasSegAtiva(toolErp::id_inst());
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if (empty($dt_data1)) {
    $dt_hoje = date('Y-m-d');
    $dt_data1 = date('Y-m-d', strtotime('-15 days', strtotime($dt_hoje)));
}
if(empty($dt_data2)) {
    $dt_data2 = date('Y-m-d'); 
}
$ocorrencias = $model->getOcorrencias($dt_data1,$dt_data2,$id_turma);
?>
<style>
    .geral{
        margin: 0 auto;
        max-width:800px;
    }
</style>
<div class="body">
    <div class="fieldTop">
        Relatório de Ocorrências
    </div>
    <form id="data" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input('dt_data1', 'Data Inicial', @$dt_data1, "onChange = enviar()", null, 'date') ?>
            </div>
            <div class="col-3">
                <?= formErp::input('dt_data2', 'Data Final', @$dt_data2, "onChange = enviar()", null, 'date') ?>
            </div>
            <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turmas', $id_turma, null,null,"onChange = enviar()") ?>
        </div>
        </div>
    </form>
    <br />

    <?php
    if (!empty($ocorrencias)) {

        foreach ($ocorrencias as $k => $v) {
            $ocorrencias[$k]['n_pessoa'] = toolErp::n_pessoa($v['fk_id_pessoa']);
        }
    
        $form['array'] = $ocorrencias;
        $form['fields'] = [
            'Dia' => 'dt_data',
            'Turma' => 'n_turma',
            'Professor' => 'n_pessoa',
            'Ocorrências' => 'ocorrencia',
        ];
    }
    
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>

<script type="text/javascript">
    function enviar(){
        data1 = document.getElementsByName("dt_data1")[0].value;
        data2 = document.getElementsByName("dt_data2")[0].value;

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

        document.getElementById("data").submit();
    }
</script>
