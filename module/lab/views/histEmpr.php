<?php
if (!defined('ABSPATH'))
    exit;

$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (in_array(user::session('id_nivel'), [10])) {
    $escola = $model->escolasOpt();
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = tool::id_inst();
}
if (!empty($id_inst)) {
    $alunos = $model->alunoEscola($id_inst);
    $prof = $model->funcionarios($id_inst);
    $alunos = $alunos + $prof;
}
$hist = $model->histEmprestimo($id_pessoa, $id_inst);
if ($hist) {
    foreach ($hist as $k => $v) {
        $hist[$k]['data'] = data::converteBr($v['time_stamp']);
        $hist[$k]['hora'] = substr($v['time_stamp'], 11, 5);
        if ($v['devolucao'] == 1) {
            $hist[$k]['prot'] = formErp::submit('Protocolo de devolução', null, ['id_hist' => $v['id_hist']], HOME_URI . '/lab/protDevEsc', 1);
        }
    }
    $form['array'] = $hist;
    $form['fields'] = [
        'data' => 'data',
        'Hora' => 'hora',
        'Escola' => 'n_inst',
        'Quem Lançou' => 'n_pessoa',
        'Chrombook' => 'serial',
        'Descrição' => 'obs',
        '||1' => 'prot'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Histórico de Empréstimo
    </div>
    <div class="row">
        <?php
        if (!empty($escola)) {
            ?>
            <div class="col">
                <?php
                echo formErp::select('id_inst', $escola, 'Escola', $id_inst, 1);
                ?>
            </div>
            <?php
        }
        ?>
        <div class="col">
            <?php
            if (!empty($id_inst)) {
                echo formErp::select('id_pessoa', $alunos, 'Aluno/Funcionário', @$id_pessoa, 1, ['id_inst' => $id_inst]);
            }
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    } else {
        ?>
        <div class="alert alert-warning">
            Não há hitórico <?= empty($alunos[$id_pessoa])?'':'('.$alunos[$id_pessoa].')' ?>
        </div>
        <?php
    }
    ?>
</div>
