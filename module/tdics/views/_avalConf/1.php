<?php
if (!defined('ABSPATH'))
    exit;
$ci = sqlErp::idNome($model::$sistema . '_curso');
if(!empty($aval['fk_id_curso'])){
    $cursos = explode(',', $aval['fk_id_curso']);
} else {
    $cursos = [];
}
?>

<form method="POST">
    <div class="row"> 
        <div class="col-3"> 
            <?= formErp::input(null, 'ID', $id_aval, ' readonly') ?>
        </div>
        <div class="col-9"> 
            <?= formErp::input('1[n_aval]', 'Nome da Avaliação', @$aval['n_aval'], ' required') ?>
        </div>
    </div>
    <br />
    <div class="row"> 
        <div class="col-3"> 
            <?php
            foreach ($ci as $k => $v) {
                echo formErp::checkbox('id_curso['.$k.']', 1, $v, (in_array($k, $cursos)?1:0));
            }
            ?>
        </div>
        <div class="col-9"> 
            <?= formErp::textarea('1[descri]', @$aval['descri'], 'Descrição', null, 1) ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 50px">
        <?=
        formErp::hidden([
            '1[id_aval]' => $id_aval,
            '1[fk_id_pl]' => $id_pl,
            '1[fk_id_ag]' => $id_ag,
            'id_pl' => $id_pl,
            'id_ag' => $id_ag,
            'avalCad' => 1
        ])
        . formErp::hiddenToken('tdics_avalx')
        . formErp::button('Salvar')
        ?>
    </div>
</form>