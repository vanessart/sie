<?php
if (!defined('ABSPATH'))
    exit;
$id_porte = filter_input(INPUT_POST, 'id_porte', FILTER_SANITIZE_NUMBER_INT);
$id_aluno = filter_input(INPUT_POST, 'id_aluno', FILTER_SANITIZE_NUMBER_INT);
$id_inst = toolErp::id_inst();
$alunosSel = [];
$titulo = 'Cadastrar Aluno - AEE';
$activeNav = 2;

if ($id_aluno) {
    $apd_alunos = sql::get('apd_aluno', '*', ['id_aluno' => $id_aluno], 'fetch');
    $fk_id_pessoa = $apd_alunos['fk_id_pessoa'];
    $aluno = new ng_aluno($apd_alunos['fk_id_pessoa']);
    $titulo = $aluno->dadosPessoais['n_pessoa'];
    $activeNav = 1;
}

if ($id_inst) {
    $alunos = $model->alunosGet($id_inst);
    if (!empty($alunos)) {
        foreach ($alunos as $v) {
            $alunosSel[$v['id_pessoa']] = $v['n_pessoa'];
        }
    }
}
?>

<div class="body">
   <div class="fieldTop" style="padding-bottom: 5%;">
        <?= $titulo ?>
   </div>
   <form action="<?= HOME_URI ?>/apd/apd" method="POST" target='_parent'>

        <?php if ($id_aluno) { ?>
            <div class="row">
        <?php } else { ?>
        <div class="row">
                <div class="col">
                    <?= formErp::select('1[fk_id_pessoa]', $alunosSel, 'Aluno', @$apd_alunos['fk_id_pessoa'],NULL,NULL,'required') ?>
                </div>
        <?php } ?>
        
            <div class="col">
                <?= formErp::selectDB('apd_porte','1[fk_id_porte]', 'Porte', @$apd_alunos['fk_id_porte'],NULL,NULL,NULL,NULL,'required') ?>
            </div>

            <div class="col">

                <?=
                formErp::hidden([
                    '1[fk_id_inst]' => toolErp::id_inst(),
                    '1[id_aluno]' => $id_aluno
                ])
                .formErp::hiddenToken('apd_aluno', 'ireplace')
               // .formErp::button('Salvar') ,
                .formErp::submit('Salvar', null, ['activeNav' => @$activeNav, 'fk_id_pessoa' => @$fk_id_pessoa]);
                ?>            
            </div>
        </div>
       
       

   </form>
</div>