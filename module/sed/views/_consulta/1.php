<?php
$codEscola = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
$anoLetivo = filter_input(INPUT_POST, 'anoLetivo', FILTER_SANITIZE_STRING);
if ($codEscola && $anoLetivo) {
    $dados = rest::relacaoClasses($codEscola, $anoLetivo);
}
?>
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::input('codigo', 'codigo', $codEscola) ?>
        </div>
        <div class="col">
            <?= formErp::input('anoLetivo', 'anoLetivo ', $anoLetivo) ?>
        </div>
        <div class="col" style="padding: left 46,7px;">
            <?=
            formErp::hidden([
                'activeNav' => 1
            ])
                . formErp::button('Buscar');
            ?>


        </div>

    </div>
</form>
<br />
<table class="table table-bordered table-hover table-striped">
    <?php if (!empty($dados)) : ?>
        <?php foreach ($dados['outClasses'] as $key) : ?>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;">
                    <?= "Unidade: " . $key['outCodUnidade'] ?>

                </td>
            </tr>
            <?php foreach ($key as $k => $value) : ?>
                <tr>
                    <td><?= str_replace('out', '', $k) ?></td>
                    <td><?= $value ?></td>

                </tr>
            <?php endforeach; ?>


        <?php endforeach; ?>
    <?php endif; ?>
</table>

