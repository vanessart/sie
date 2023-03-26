<?php

$periodo = filter_input(INPUT_POST, 'periodo_letivo', FILTER_SANITIZE_STRING);
$idinst = filter_input(INPUT_POST, 'fk_id_inst', FILTER_SANITIZE_NUMBER_INT);
$tipoteste = filter_input(INPUT_POST, 'teste', FILTER_SANITIZE_STRING);
$turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

$tipo = [0 => 'Selecionar', 1 => 'Computador', 2 => 'Papel'];
$comp = [1 => 'NS', 2 => '0,06', 3 => '0,15', 4 => '0,3', 5 => '0,4', 6 => '0,5', 7 => '0,6', 8 => '0,8', 9 => '1', 10 => '1,2'];
$papel = [1 => 'NS', 2 => '0,1', 3 => '0,15', 4 => '0,2', 5 => '0,3', 6 => '0,4', 7 => '0,5', 8 => '0,6', 9 => '0,7', 10 => '0,8', 11 => '0,9', 12 => '1'];
$sin = [1 => 'Não Possui', 2 => 'Ardência', 3 => 'Coceira', 4 => 'Fadiga Visual', 5 => 'Franzir a Testa', 6 => 'Lacrijamento', 7 => 'Tontura', 8 => 'Outros'];


if (!empty($tipoteste)) {
    $anosanteriores = $model->pegaanovisao();
}
if (!empty($periodo)) {
    $escola = $model->pegaescolavisao($periodo);
}
?>

<div style="width: 100%; margin-left: 5px; margin-right: 5px" class="Body">
    <br />
    <div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
        Relatórios - Resumo
    </div>
    <br />

    <div class="row">
        <div class="col-md-3">
            <?php
            echo formErp::select('teste', @$tipo, 'Tipo Teste', $tipoteste, 1)
            ?>
        </div>
        <div class="col-md-2">
            <?php
            echo formErp::select('periodo_letivo', @$anosanteriores, 'Período Letivo', @$periodo, 1, ['teste' => @$tipoteste]);
            ?>
        </div>
        <div class="col-md-5">
            <?php
            echo formErp::select('fk_id_inst', @$escola, 'Nome Escola', @$idinst, 1, ['teste' => @$tipoteste, 'periodo_letivo' => @$periodo]);
            ?>
        </div> 
        <div class="col-md-1">
            <?php
            if (!empty($idinst)) {
                if ($tipoteste == 1) {
                    $rel = 'testepdfestat';
                } else {
                    $rel = 'retestepdfestat';
                }
                ?>
                <form target = "_blank" action = "<?php echo HOME_URI ?>/visao/<?php echo $rel ?>" method = "POST">
                    <input type="hidden" name="idinst" value="<?php echo $idinst ?>" />
                    <input type="hidden" name="periodo" value="<?php echo $periodo ?>" />
                    <button class = "btn btn-info">
                        Imprimir
                    </button>
                </form>

                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Imprimir" name="imprimir" />
                <?php
            }
            ?>

        </div>
    </div>  
    <br />
</div>

