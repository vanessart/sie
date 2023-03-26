<?php
if (!defined('ABSPATH'))
    exit;
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
$ano = empty($ano) ? date("Y") : $ano;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$inst = escolas::idEscolas();
$range = range((date("Y") + 1), 2000);
$anoList = array_combine($range, $range);
if (!empty($model->return['turmas'])) {
    $form['array'] = $model->return['turmas'];
    $form['fields'] = [
        'Escola' => 'Escola',
        'Turma' => 'Turma',
        'IdTurma' => 'id_turma',
        'Nº Prodesp' => 'prodesp'
    ];
} elseif (!empty($ano) && !empty($id_inst)) {
    $sql = " SELECT t.n_turma, t.id_turma, t.prodesp, i.n_inst, pl.n_pl FROM ge_turmas t "
            . " JOIN instancia i on i.id_inst = t.fk_id_inst "
            . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
            . " WHERE i.id_inst = $id_inst "
            . " AND t.periodo_letivo LIKE '$ano'";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        $form['array'] = $array;
        $form['fields'] = [
            'Escola' => 'n_inst',
            'Turma' => 'n_turma',
            'Período Letivo' => 'n_pl',
            'IdTurma' => 'id_turma',
            'Nº Prodesp' => 'prodesp'
        ];
    }
}
//unset($_SESSION['rest']);
?>
<div class="border">
    <div class="row">
        <div class="col-3">
            <?= formErp::select('ano', $anoList, 'Ano', $ano, 1, ['id_inst' => $id_inst]) ?>
        </div>
        <div class="col-6">
            <?= formErp::select('id_inst', $inst, ['Escola', 'Todas as Escolas'], $id_inst, 1, ['ano' => $ano]) ?>
        </div>
        <div class="col-3">
            <form method="POST">
                <?=
                formErp::hidden([
                    'activeNav' => 1,
                    'ano' => $ano,
                    'id_inst' => $id_inst
                ])
                . formErp::hiddenToken('sincronizarTurmas')
                . formErp::button('Enviar');
                ?>
            </form>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>


