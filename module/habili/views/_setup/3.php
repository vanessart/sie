<?php
if (!defined('ABSPATH'))
    exit;
$set = sqlErp::get('ge_setup', '*', null, 'fetch');
if (!empty($set['lanc_bim'])) {
    $bims = explode(',', $set['lanc_bim']);
} else {
    $bims = [];
}
?>
<div class="row">
    <div class="col-10 topo">
        Liberar as Notas para os alunos
    </div>
    <div class="col-2">
        <form method="POST">
            <button class="btn btn-primary border">
                Voltar
            </button>
        </form>
    </div>
</div>
<br />
<div class="alert alert-info" style="font-weight: bold; text-align: center; font-size: 1.2em">
    <p>
        Esta ação irá Liberar Bimestres Anteriores.
    </p>
    <p>
        O Bimestre atual é liberado altomaticamente.
    </p>
</div>
<form method="POST">
    <?=
    formErp::hidden([
        'set' => 3,
    ])
    . formErp::hiddenToken('ge_setupBimSet')
    ?>
    <div class="row">
        <?php
        foreach (range(1, 4) as $v) {
            ?>
            <div class="col">
                <div style="padding-left: 100px">
                    <?= formErp::checkbox('b[' . $v . ']', 1, $v . 'º Bimestre', in_array($v, $bims) ? 1 : 0) ?>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="col">

        </div>
    </div>
    <br />
    <div class="row">
        <div class="col text-center">
            <button class="btn btn-success" type="submit">
                Salvar
            </button>
        </div>
    </div>
    <br />
</form>
