
<br />

<div style="height: 300px;border: 1px solid">
    <br /><br />
    <div style="text-align: left; font-size: 25px; color: red ">
        Quadro de Alunos
    </div>
    <br /><br /><br />
    <div style="text-align: left; font-size: 25px; padding: 10px">
        Informe Período Desejado
    </div>
    <div style="font-size: 18px; padding: 10px" class="row">
        <div class="col-lg-4">
            <label style="font-size: 25px">
                Data Início
            </label>       
            <input id = "data1" style="font-size: 25px; text-align: center"<?php echo formulario::dataConf(1) ?>type="text" name="datai" value="" required=""/>
        </div>
        <div class="col-lg-4">
            <label style="font-size: 25px">
                Data Final
            </label>       
            <input id = "data2" style="font-size: 25px; text-align: center"<?php echo formulario::dataConf(2) ?>type="text" name="dataf" value="" required="" />
        </div>
    </div>    


</div>       

<div style="padding: 15px">
    <div class="row">
        <div class="col-lg-3">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/quadroalunopdf" id="qaf" method="POST">
                <input id = "di2" type="hidden" name="datai" value="" />  
                <input id = "df2" type="hidden" name= "dataf" value="" />  
                <input type="hidden" name= "escola" value="<?php echo "Fundamental" ?>" />
                <input type="hidden" name="tipo" value ="<?php echo "EF" ?>"/>
                <button onmouseover="pegadata('di2', 'df2')" style="width: 80%" type="submit" class="art-button">                
                    Visualizar Fundamental
                </button> 
            </form>
        </div>
        <div class="col-lg-3">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/quadroalunopdf" id="qap" method="POST">
                <input id = "di3" type="hidden" name="datai" value="" />  
                <input id = "df3" type="hidden" name= "dataf" value="" />  
                <input type="hidden" name= "escola" value="<?php echo "Infantil" ?>" />  
                <input type="hidden" name="tipo" value ="<?php echo "EI" ?>"/>
                <button onmouseover="pegadata('di3', 'df3')" style="width: 80%" type="submit" class="art-button">                
                    Visualizar Infantil - Pré
                </button> 
            </form>
        </div> 
        <div class="col-lg-3">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/quadroalunopdf" id="qam" method="POST">
                <input id = "di4" type="hidden" name="datai" value="" />  
                <input id = "df4" type="hidden" name= "dataf" value="" />  
                <input type="hidden" name= "escola" value="<?php echo "Infantil" ?>" />  
                <input type="hidden" name="tipo" value ="<?php echo "EM" ?>"/>
                <button onmouseover="pegadata('di4', 'df4')" style="width: 80%" type="submit" class="art-button">                
                    Visualizar Infantil - Maternal
                </button> 
            </form>
        </div> 
        <div class="col-lg-3">
            <form target="_blank" action="<?php echo HOME_URI ?>/gestao/quadroalunopdf" id="qae" method="POST">
                <input type="hidden" name="datai" value="<?php echo @$_POST['datai'] ?>" />  
                <input type="hidden" name= "dataf" value="<?php echo @$_POST['dataf'] ?>" /> 
                <input type="hidden" name= "escola" value="<?php echo "EJA" ?>" />  
                <input type="hidden" name="tipo" value ="<?php echo "EJ" ?>"/>
                <button style="width: 80%" type="submit" class="art-button">
                    Visualizar Eja - AEE - Multisseriada
                </button> 
            </form>
        </div> 
    </div>
</div>

<script>
    function pegadata(datain, datafim) {
        document.getElementById(datain).value = document.getElementById('data1').value;
        document.getElementById(datafim).value = document.getElementById('data2').value;


    }
</script>

