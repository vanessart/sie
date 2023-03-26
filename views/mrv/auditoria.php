<?php
if (!empty($_POST['auditoria'])) {
    $a = $model->verificastatus();
    if ($a == "Pendente") {
        $resultado = $model->auditoriaitb();
    } else {
        tool::alert("Status " . $a);
    }
}

$rel = $model->verificastatusescola();
$habi = $model->verificastatus();
?>
<div class="fieldBody">
    <div class="fieldTop">
        <!--   AUDITORIA - CÁLCULO DA MÉDIA FINAL E CLASSIFICAÇÃO ESCOLA -->
        AUDITORIA - CÁLCULO DA MÉDIA
    </div>
    <br /><br /><br />
    <div class="row">
        <?php
        if ($habi == "Pendente") {
            ?>
            <div class="col-md-12 text-center">
                <form method="POST">
                    <input type="hidden" name="resultado" value="<?php echo @$resultado ?>" />
                    <input type="submit" style="width: 45%" class ="art-button" value="Auditoria" name="auditoria" />
                </form>
            </div>
            <br /><br /><br />
            <?php
            if (@$rel != "OK") {
                ?>
                <div class="col-md-12 text-center">
                    <form target="_blank" action="<?php echo HOME_URI ?>/mrv/pendenciapdf" name="pendencia" method="POST">
                        <input type="submit" style="width: 45%" class ="art-button" value="Visualizar" name="relatorio" />                
                    </form>
                </div>
                <?php
            }
        }
        if ($habi == "Calcular") {
            ?>
            <br /><br /><br />
            <div class="col-md-12 text-center">
                <form method="POST">
                    <input type="submit" style="width: 45%" class ="art-button" value="Calcular Média" name="media" />
                </form>
            </div>

            <?php
        }
        ?>

        <br /><br /><br />

        <div class="col-md-12 text-center">  
            <?php
            if (@$rel == "OK") {
                ?>
                <input onclick="$('#def').submit();" type="button" style="width: 45%" class ="art-button" value="Deferidos" />
                <?php
            } else {
                ?>
                <input type = "button" style = "width: 45%" class = "art-button" value = "Deferidos" />
                <?php
            }
            ?>
        </div>  
        <br /><br /><br />
        <div class="col-md-12 text-center">  
            <?php
            if (@$rel == "OK") {
                ?>
                <input onclick="$('#indef').submit();" type="button" style="width: 45%" class ="art-button" value="Indeferidos" />
                <?php
            } else {
                ?>
                <input type = "button" style = "width: 45%" class = "art-button" value = "Indeferidos" />
                <?php
            }
            ?>
        </div>  

        <br /><br /><br />

        <div class="col-md-12 text-center">  
            <?php
            if (@$rel == "OK") {
                ?>
                <input onclick="$('#relclass').submit();" type="button" style="width: 45%" class ="art-button" value="Visualizar Classificação Final" />
                <?php
            } else {
                ?>
                <input type = "button" style = "width: 45%" class = "art-button" value = "Visualizar Classificação" />             
                <?php
            }
            ?>
        </div>       
        <!--
                <form target="_blank" action="<?php echo HOME_URI ?>/mrv/classificacaopdf" id="relclass" method="POST">
                    <input type="hidden" name="Visualizar" value="<?php echo $rel ?>" />                
                </form>
        -->
        <form target="_blank" action="<?php echo HOME_URI ?>/mrv/classificacaogeralpdf" id="relclass" method="POST">
            <input type="hidden" name="Visualizar" value="<?php echo $rel ?>" />                
        </form>
        <form target="_blank" action="<?php echo HOME_URI ?>/mrv/indeferidospdf" id="indef" method="POST">
            <input type="hidden" name="Visualizar" value="<?php echo $rel ?>" />                
        </form>
        <form target="_blank" action="<?php echo HOME_URI ?>/mrv/deferidospdf" id="def" method="POST">
            <input type="hidden" name="Visualizar" value="<?php echo $rel ?>" />                
        </form>

    </div> 

</div>

