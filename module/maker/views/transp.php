<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = null;
}
$ver = filter_input(INPUT_POST, 'ver', FILTER_SANITIZE_NUMBER_INT);
if ($ver) {
    $alunos = $model->transporte($id_inst);
    if ($alunos) {

        $form['array'] = $alunos;
        $form['fields'] = [
            'Polo' => 'Polo',
            'Escola de Origem' => 'Escola de Origem',
            'Turma de Origem' => 'Turma de Origem',
            'Dia da Semana' => 'Dia da Semana',
            'Horário' => 'Horário',
            'Período' => 'Período',
            'Turma Maker' => 'Turma Maker',
            'RSE' => 'RSE',
            'Nome' => 'Nome',
            'Endereço' => 'Endereço',
            'Transporte' => 'Transporte'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Transporte
    </div>
    <div class="row">
        <div class="col text-center">
            <form method="POST">
                <?= formErp::hidden(['ver' => 1]) . formErp::button('Ver relação de alunos online') ?>
            </form>
        </div>
        <div class="col text-center">
            <form action="<?= HOME_URI ?>/maker/pdf/alunotransp" target="_blank" method="POST">
                <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
                <button class="btn btn-primary">
                    Exportar Planilha
                </button>
            </form>
        </div>
    </div>
    <br />
    <?php
    if (!empty($alunos)) {
        report::simple($form);
    }
    ?>
</div>
