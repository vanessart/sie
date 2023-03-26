<?php
if (!defined('ABSPATH'))
    exit;
$id_pedido = filter_input(INPUT_POST, 'id_cadampe_pedido', FILTER_SANITIZE_NUMBER_INT);      
$pedido = $model->getPedido($id_pedido);
$periodo_ = str_split($pedido[0]['periodoProto']);
$N = $T = $M = '';
foreach ($periodo_ as $v) {
   if ($v == 'N') {
       $N = 'checked';
   }
   if ($v == 'T') {
       $T = 'checked';
   }
   if ($v == 'M') {
       $M = 'checked';
   } 
}
$periodoProtocolo = $model->getPeriodo($pedido[0]['periodoProto']);
?>
<style type="text/css">
    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: #888;
        font-size: 16px;
        margin-bottom: 7px;
        padding-left: 4px;
    }
    .tituloG { 
        font-weight: bold;
        color: #888;
        font-size: 18px;
        margin-bottom: 5px;
        text-align: center;
        padding: 10px;
        padding-bottom: 20px;
    }
    .info{
        margin-bottom: 5px;
    }
</style>
<div class="body">
    <div class="fieldTop">Editar Protocolo <?= $id_pedido ?></div>
    
    <form id="formEnvia" target="_parent" action="<?= HOME_URI ?>/cadampe/protocolosList" method="POST">
         
        <div class="row">
            <div class="col">
                <div class="titulo"> Início </div>
                <?= formErp::input('1[dt_inicio]', 'Início', $pedido[0]['dt_inicio'], ' required', null, 'date') ?>
            </div>
            <div class="col">
                 <div class="titulo"> Término </div>
                <?= formErp::input('1[dt_fim]', 'Término', $pedido[0]['dt_fim'],  ' required', null, 'date') ?>
            </div>
        </div>
        <br /><br />

        <div class="row">
                <div class="titulo" style="padding-left: 13px;"> Período </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Manhã</span>
                    <input  type="checkbox" id='M' name="periodo_" value="M" <?= $M ?>>
                    <span class="checkmark"></span>
                </label> 
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Tarde</span>
                    <input  type="checkbox" name="periodo_" id='T' value="T" <?= $T ?>>
                    <span class="checkmark"></span>
                </label> 
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Noite</span>
                    <input  type="checkbox" name="periodo_" id='N' value="N" <?= $N ?>>
                    <span class="checkmark"></span>
                </label> 
            </div>
        </div>
        
        <div class="row">
            <div class="col text-center">
                <input type="hidden" name="1[periodo]" id='periodo' value="">
                <?=
                formErp::hidden([
                    '1[id_cadampe_pedido]' => $id_pedido,
                ])
                . formErp::hiddenToken('cadampe_pedido', 'ireplace', null, null, 1)
                . formErp::button('Salvar', null, 'data()', null, 'salvar');
                ?>            
            </div>
        </div>
    </form>     
</div>

<script type="text/javascript">
  
    function data(){ 

        data1 = document.getElementsByName('1[dt_inicio]')[0].value;
        data2 = document.getElementsByName('1[dt_fim]')[0].value;
        var resp = 0;
        var periodo = '';
        let checkboxM = document.getElementById('M');
        let checkboxT = document.getElementById('T');
        let checkboxN = document.getElementById('N');
        if (checkboxM.checked == true) {
             resp = 1;
             periodo = periodo+'M';
        }
        if(checkboxT.checked == true) {
             resp = 1;
             periodo = periodo+'T';
        } 
        if (checkboxN.checked == true) {
            resp = 1;
            periodo = periodo+'N';
        } 
        if (resp == 0) {
            alert("É obrigatório informar no mínimo um período (Manhã, Tarde, Noite)");
            return false;
        }else{
            document.getElementById('periodo').value = periodo;
        }
        
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
            alert("Informe a Data de término.");
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

        var el = document.getElementsByName('salvar')[0];
        document.getElementById('formEnvia').submit();
    }
</script>