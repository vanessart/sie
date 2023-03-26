<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], 'n_pl, id_pl', null, 'fetch');
$id_pl = $setup['id_pl'];
if ($id_inst) {
    $diaSem = [
        2 => 'Segunda',
        3 => 'Terça',
        4 => 'Quarta',
        5 => 'Quinta',
        6 => 'Sexta'
    ];
    $maker = $model->escolaPolo($id_inst);
}
?>
<div class="body">
    <?php
    if (toolErp::id_nilvel() != 8) {
        ?>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
            </div>
        </div>
        <br />
        <?php
    }
    ?>
    <div class="fieldTop">
        Quadro de Alunos
    </div>
    <?php
    if ($id_inst) {
        if (!empty($maker['fk_id_inst_maker'])) {
            $n_polo = $maker['n_polo'];
            ?>
            <div class="fieldTop">
                Polo: <?= $n_polo ?>
            </div>
            <?php
            $a = $model->alimento($maker['fk_id_inst_maker']);
            include ABSPATH . '/module/maker/views/_lache/tabela.php';
        }
    }
    ?>
</div>