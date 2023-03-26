<?php

$ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_STRING);
$uf = filter_input(INPUT_POST, 'uf', FILTER_SANITIZE_STRING);
$anoletvo = filter_input(INPUT_POST, 'anoletivo', FILTER_SANITIZE_STRING);

if($ra && $uf){
    $result = rest::listarInscricoesAluno($ra, $uf, null, $anoletvo);
}
?>
<form method="POST">

    <div class="row">
        <div class="col">
            <?= formErp::input('ra', 'RA', $ra)?>
        </div>
        <div class="col">
            <?= formErp::input('uf', 'UF', $uf)?>
        </div>
        <div class="col">
            <?= formErp::input('anoletivo', 'Ano Letivo', $anoletvo)?>
        </div>
        <div class="col">
        
        
            <?=
             formErp::hidden([
                'activeNav' => 4
            ]) .
                formErp::button('Buscar');
            ?>
        </div>
    </div>
    </form>

    <table class="table table-bordered table-hover table-striped">
    <?php if (!empty($result)) : ?>
        <?php foreach ($result['outInscricoes'] as $key => $value) : ?>
            <tr>

                <td><?= str_replace('out', '', $key) ?></td>
                <td><?= $value ?></td>


            </tr>


        <?php endforeach ?>
    <?php endif ?>

</table>



