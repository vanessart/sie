<?php
if (!defined('ABSPATH'))
    exit;
$set = sql::get('ge_setup', '*', ['id_set' => 1], 'fetch');
?>
<div class="row">
    <div class="col-10 topo">
        Liberar Conselho de classe
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
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::input('1[dt_inicio_conselho]', 'Inicio do Conselho', $set['dt_inicio_conselho'], null, null, 'date') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[dt_fim_conselho]', 'Término do Conselho', $set['dt_fim_conselho'], null, null, 'date') ?>
        </div>
        <div class="col">
            <?= formErp::checkbox('1[at_conselho]', 1, 'Abrir o conselho', $set['at_conselho']) ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 30px">
        <?=
        formErp::hidden([
            '1[id_set]' => 1,
            'set'=>5
            ])
        . formErp::hiddenToken('ge_setup', 'ireplace')
        . formErp::button('Salvar')
        ?>
    </div>
</form>

