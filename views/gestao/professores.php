<?php
$sd = $model->selecaodisciplina();
?>
<div class="fieldBody">
    <div class="fieldTop">
        Relatórios - Professores
    </div>
    <br /><br /><br />
    <div class="row">
        <div class="col-md-12 text-center">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfprof">
                <button class="art-button"  style="width: 300px">
                    Lista de Professores
                </button>
            </a>
        </div>
        <br /><br /><br />
        <div class="col-md-12 text-center">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfprofno">
                <button class="art-button" style="width: 300px">
                    Lista de Professores Não Alocados
                </button>
            </a>
        </div>     
        <br /> <br /><br />
        <div class="col-md-12 text-center">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfalocaprof">
                <button class="art-button" style="width: 300px">
                    Alocação de Professores (por professor)
                </button>
            </a>
        </div>   
        <br /><br /><br />  
        <div class="col-md-12 text-center">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfalocadisc">
                <button class="art-button" style="width: 300px">
                    Alocação de Professores (por disciplina)
                </button>
            </a>
        </div>    
        <br /> <br /><br />      
        <div class="col-md-12 text-center">
            <a target="_blank" href="<?php echo HOME_URI ?>/prof/pdfalocaclasse">
                <button class="art-button" style="width: 300px">
                    Alocação de Professores (por classe)
                </button>
            </a>
        </div>
        <br /> <br /><br />
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
    </div>
</div>