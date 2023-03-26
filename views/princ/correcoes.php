<?php
$wperiodo = filter_input(INPUT_POST, 'idpl', FILTER_SANITIZE_NUMBER_INT);
$wescola = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$wcodclasse = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

$periodo = migraRH::periodoletivoconselho();
$escola = migraRH::escolasconselho($wperiodo);
$turma = migraRH::turmaconselho($wperiodo, $wescola);
?>
<div class="fieldTop">
    Correções
</div>
<div class="fieldBody">
    <div class="row">
        <div class="col-md-3">
            <?php echo form::select('idpl', $periodo, 'Selecione Período Letivo', $wperiodo, 1, ['idpl' => $wperiodo]) ?>
        </div>
        <div class="col-md-6">
            <?php echo form::select('id_inst', $escola, 'Escola', $wescola, 1, ['idpl' => $wperiodo, 'id_inst' => $wescola]) ?>
        </div>
        <div class="col-md-3">
            <?php echo form::select('id_turma', $turma, 'Turma', $wcodclasse, 1, ['idpl' => $wperiodo, 'id_inst' => $wescola, 'id_turma' => $wcodclasse]) ?>
        </div>
    </div>
    <br /><br /><br />

    <div class="fieldWhite text-center" style="padding: 20px">
        <div class="row">
            <div class="col-md-3">
                <form method="POST">
                    <input type="hidden" name="periodo" value="<?php echo $wperiodo ?>"/>
                    <input type="hidden" name="escola" value="<?php echo $wescola ?>"/>
                    <input type="hidden" name="turma" value="<?php echo $wcodclasse ?>"/>
                    <?php
                    if (!empty($wperiodo)) {
                        ?>
                        <input style="width: 100%;" class="art-button" type="submit" name="atzatafinal" value="Atualiza Ata Final" />               
                        <?php
                    } else {
                        ?>
                        <button class="btn btn-default" type="button" disabled style="width: 100%">Atualiza Ata Final</button> 
                        <?php
                    }
                    ?>
                </form>
            </div>
            <div class="col-md-3">
                <button name="ata1" style="width: 80%" type="submit" class="art-button" id="btnata1">
                    Escola
                </button>
            </div>
            <div class="col-md-3">
                <button name="ata2" style="width: 80%" type="submit" class="art-button" id="btnata2">
                    Turma
                </button>
            </div>
            <form method="POST">
                <div class="col-md-3">              
                    <input style="width: 100%;" class="art-button" type="submit" name="prof_ingles" value="Professor Inglês" />             
                </div> 
            </form>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-6">
            <form method="POST">
                <div>              
                    <input style="width: 100%;" class="art-button" type="submit" name="rodizio" value="Atz Rodizio" />             
                </div> 
            </form>
        </div>
        <div class="col-md-6">
            <form method="POST">
                <div>              
                    <input style="width: 100%;" class="art-button" type="submit" name="rodizioclas" value="Atz Rodizio Clas" />             
                </div> 
            </form>
        </div>
    </div>
</div>

