<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div style="border: 1px solid; width: 50%; text-align: center">
        <br /><br />
        <div style="text-align: left; font-size: 15px; color: red ">
            Lista
        </div>
        <br />
        <div style="text-align: left; font-size: 15px; padding: 10px">
            Informe Período Desejado
        </div>
        <div style="font-size: 15px; padding: 10px" class="row">
            <div class="col-lg-4">
                <label style="font-size: 15px">
                    Data Início
                </label>       
                <input id = "data1" style="font-size: 15px; text-align: center"<?php echo formulario::dataConf(1) ?>type="text" name="datai" value="" required=""/>
            </div>
            <div class="col-lg-4">
                <label style="font-size: 15px">
                    Data Final
                </label>       
                <input id = "data2" style="font-size: 15px; text-align: center"<?php echo formulario::dataConf(2) ?>type="text" name="dataf" value="" required="" />
            </div>
        </div>    
    </div>       
    <div style="padding: 15px">
        <div class="row">
            <div class="col-lg-12">
                <form target="_blank" action="<?php echo HOME_URI ?>/cadam/pdfrelatorio" id="rel" method="POST">
                    <input id = "di2" type="hidden" name="datai" value="" />  
                    <input id = "df2" type="hidden" name= "dataf" value="" />  
                    <button onmouseover="pegadata('di2', 'df2')" style="width: 50%" type="submit" class="art-button">                
                        Visualizar
                    </button> 
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function pegadata(datain, datafim) {
        document.getElementById(datain).value = document.getElementById('data1').value;
        document.getElementById(datafim).value = document.getElementById('data2').value;
    }
</script>

