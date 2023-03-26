<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
$id_pessoa_prof = toolErp::id_pessoa();

if ($id_inst) {
    //$alunos = alunos::alunosGet($id_inst);
    $ap_aluno = $model->indexDBAlunosAEE($id_pessoa_prof);
    if ($ap_aluno) {

        foreach ($ap_aluno as $k => $v) {

            $ap_aluno[$k]['hab'] = formErp::submit('Acessar', null, ['fk_id_pessoa' => $v['id_pessoa'], 'activeNav' => 2,'n_turma' => $v['n_turma'], 'n_pessoa' => $v['n_pessoa'], 'dt_nasc' => $v['dt_nasc'], 'id_turma' => $v['id_turma'], 'id_porte' => $v['id_porte']]);
        }

        $form['array'] = $ap_aluno;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Aluno' => 'n_pessoa',
            'Turma' => 'n_turma',
            'Escola' => 'n_inst',
            'Deficiência' => 'n_ne',
            'Porte' => 'n_porte',
            '||1' => 'hab',
        ];
    }
}
?>

<div class="body">
<?php
if (!empty($form)) {
    report::simple($form);
} else {
    ?>
        <div class="alert alert-warning" style="text-align: center; font-weight: bold; padding: 30px">
            Não há Alunos cadastrados
        </div>
    <?php
}
?>
</div>

