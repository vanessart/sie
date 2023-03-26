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
    $alunos = $model->alunoList($id_inst);
    if ($alunos) {
        $form['array'] = $alunos;
        $form['fields'] = [
            'RSE' => 'RSE',
            'Nome' => 'Nome',
            'Turma Maker' => 'Turma Maker',
            'Dia da Semana' => 'Dia da Semana',
            'Horário' => 'Horário',
            'Escola de Origem' => 'Escola de Origem',
            'Turma de Origem' => 'Turma de Origem'
        ];
    }
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
    <div class="row">
        <div class="col-10">
            <div class="fieldTop">
                Lista de Alunos Matrículados
                <?php
                if (!empty($alunos)) {
                    echo '<br /><br />Polo: ' . current($alunos)['Polo'];
                }
                ?>
            </div>
        </div>
        <div class="col-2">
            <form action="<?= HOME_URI ?>/maker/pdf/alunoEscPlan" target="_blank" method="POST">
                <?= formErp::hidden(['id_inst' => $id_inst]) ?>
                <button class="btn btn-info">
                    Gerar PDF
                </button>
            </form>
        </div>
    </div>

    <br /><br />
    <?php
    if (!empty($alunos)) {
        report::simple($form);
    } else {
        ?>
        <div class="alert alert-danger" style="font-weight: bold; font-size: 1.2">
            Sua escola não faz parte do Projeto Salas Maker
        </div>
        <?php
    }
    ?>
</div>