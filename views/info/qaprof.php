<?php
$sd = disciplina::selecaodisciplina();
?>
<div class="fieldBody">
    <div class="fieldTop">
        Quadro de Professores por Disciplina
    </div>
    <br /><br />
    <!--
    <div class="col-md-12 text-center">
        <div class="fieldWhite text-center" style="padding: 20px; width: 320px">
            <form name ="disciplina" target="_blank" action="<?php echo HOME_URI ?>/prof/quadroprof" id="qprof" method="POST">
                <div class="input-group-addon">
                    Quadro de Professores<br /><br />
                    Selecione Disciplina
                </div>   
                <br />
                <select name="disc" onchange="document.disciplina.submit()">
                    <option></option>
                    <?php
                    foreach ($sd as $v) {
                        ?>
                        <option <?php echo $v['n_disc'] == @$_POST['n_disc'] ? 'selected' : '' ?> value ="<?php echo $v['n_disc'] ?>">
                            <?php echo $v['n_disc'] ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
                <br /><br />              
            </form> 
        </div>
    </div>   
    -->
    <br /><br />
    <div class="col-md-12 text-center">
        <div class="fieldWhite text-center" style="padding: 20px; width: 500px">
            <form name ="escola" target="_blank" action="<?php echo HOME_URI ?>/prof/qaprofe" id="qprof" method="POST">
                <div class="input-group-addon">
                    Quadro de Professores<br /><br />
                    Selecione escola
                </div>   
                <br />
                <select name="id_inst" onchange="document.escola.submit()">
                    <option></option>
                    <?php
                    $esc = escolas::liste();
                    foreach ($esc as $k => $v) {
                        ?>
                        <option value ="<?php echo $v['id_inst'] ?>">
                            <?php echo $v['n_inst'] ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
                <br /><br />              
            </form> 
        </div>
    </div>
</div>