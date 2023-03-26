<?php
if (!defined('ABSPATH'))
    exit;
$id_porte = filter_input(INPUT_POST, 'id_porte', FILTER_SANITIZE_NUMBER_INT);
$id_aluno = filter_input(INPUT_POST, 'id_aluno', FILTER_SANITIZE_NUMBER_INT);
$id_inst = toolErp::id_inst();
$alunosSel = [];
$_ativo = '1';

if ($id_aluno) {
    $apd_alunos = sql::get('apd_aluno', '*', ['id_aluno' => $id_aluno], 'fetch');
    $_ativo = $apd_alunos['at_aluno'];
    $aluno = new ng_aluno($apd_alunos['fk_id_pessoa']);
    $disabled = 'disabled';
}

if ($id_inst) {
    $alunos = alunos::alunosGet($id_inst);
    if (!empty($alunos)) {
        foreach ($alunos as $v) {
            $alunosSel[$v['id_pessoa']] = $v['n_pessoa'];
        }
    }
}
?>

<div class="body">
   <div class="fieldTop">
        Aluno - AEE
   </div>
   <form action="<?= HOME_URI ?>/apd/def/formAlunoUpload.php" method="POST">

        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_pessoa]', $alunosSel, 'Aluno', @$apd_alunos['fk_id_pessoa'], NULL, NULL, 'required'.@$disabled) ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('apd_porte','1[fk_id_porte]', 'Porte', @$apd_alunos['fk_id_porte'],NULL,NULL,NULL,NULL,'required') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::select('1[at_aluno]', ['Não','Sim'], ['Ativo', 'Selecione'], $_ativo,NULL,NULL,'required') ?>
            </div>
        </div>
       
       <div style="text-align: center; padding: 10px;">

        <?=
        formErp::hidden([
            '1[fk_id_inst]' => toolErp::id_inst(),
            '1[id_aluno]' => $id_aluno
        ])
        .formErp::hiddenToken('apd_aluno', 'ireplace')
        .formErp::button('Continuar') ?>
           
       </div>

   </form>
</div>