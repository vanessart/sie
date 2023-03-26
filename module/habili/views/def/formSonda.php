<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$curso = sql::get(['ge_cursos', 'ge_tp_ensino'], 'n_curso, n_tp_ens', ['id_curso' => $id_curso], 'fetch');
$habGr = sql::idNome('coord_grup_hab');
$son = sql::get('profe_sodagem', '*', ['fk_id_pl' => $id_pl, 'fk_id_curso' => $id_curso], 'fetch');
if(empty($son['quant'])){
    $sonAt=[0=>0];
} else {
    foreach (range(0, $son['quant']) as $v) {
            $sonAt[$v] = $v;
        }
}
?>
<div class="body">
    <div class="fieldTop">
        <?= $curso['n_curso'] ?> - <?= $curso['n_tp_ens'] ?>
    </div>
</div>
<form action="<?= HOME_URI ?>/profe/setup" target="_parent" method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::select('1[fk_id_gh]', $habGr, 'Grupo de Habilidades', @$son['fk_id_gh']) ?>
        </div>
        <div class="col">
            <?= formErp::selectNum('1[quant]', [0, 4], 'Quantidade de Sondagem', @$son['quant'], null, null, null, null, ['sondaAt', 'at_sonda']) ?>
        </div>
        <div class="col">
            <?= formErp::select('1[at_sonda]', $sonAt, 'Sondagem Ativa', @$son['at_sonda']) ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 20px">
        <?=
        formErp::hidden([
            'set' => 4,
            'id_pl' => $id_pl,
            '1[id_son]' => @$son['id_son'],
            '1[fk_id_pl]' => $id_pl, 
            '1[fk_id_curso]' => $id_curso
        ])
        .formErp::hiddenToken('profe_sodagem', 'ireplace')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
