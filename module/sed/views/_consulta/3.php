<?php

$idEscola = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
$tipoInscricao = filter_input(INPUT_POST, 'tipoInscricao', FILTER_SANITIZE_STRING);
$anoLetivo = filter_input(INPUT_POST, 'anoletivo', FILTER_SANITIZE_STRING);
if($idEscola && $tipoInscricao && $anoLetivo){
    $result = rest::listarInscricoesEscola($idEscola, $tipoInscricao, $anoLetivo);
}
?>
<form method="POST">

    <div class="row">
        <div class="col">
            <?= formErp::input('id', 'ID', $idEscola)?>
        </div>
        <div class="col">
            <?= formErp::input('tipoInscricao', 'Tipo de Inscrição', $tipoInscricao)?>
        </div>
        <div class="col">
            <?= formErp::input('anoletivo', 'Ano Letivo', $anoLetivo)?>
        </div>
        <div class="col" style="padding: left 52,7px;">
        
            <?=
             formErp::hidden([
                'activeNav' => 3
            ]) 
            .formErp::button('Buscar') . "<br>";
            ?>
        </div>

        <table class="table table-bordered table-hover table-striped">
            <?php foreach($result['outInscricoes'] as $key => $value):?>
                <tr>
                    <td><?= str_replace('out', '', $key) ?></td>
                    <td><?= $value ?></td>
                
                </tr>

            <?php endforeach; ?>

        </table>

        <?php if(!empty($result)):?>
            <?= "<pre>" . print_r($result)?>
        <?php endif ?>
    </div>
    </form>


