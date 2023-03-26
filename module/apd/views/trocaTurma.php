<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
//$turma_oficial = sql::get('ge_turmas', 'n_turma', ['id_turma' => $id_turma ], 'fetch');
$turmas = $model->getTurmasAEE($id_inst);
foreach ($turmas as $v){
    if ($id_turma <> $v['id_turma']) {
        $n_turmas[$v['id_turma']]= $v['n_turma'];
    }     
}?>
<style type="text/css">
    .input-group-text
    {
        display: none;
    }
    .titulo{
        padding-top: 14px;
        font-family: inherit;
        line-height: inherit;
        font-size: 18px;
        color: #999999;
    }
</style>
<div class="body">
    <br><br>
    <form target="_parent" action="<?= HOME_URI ?>/apd/contraturno" method="POST">
        <div class="row">
            <div class="col-2 titulo">
                Remanejar para: 
            </div>
            <div class="col-2">
                <?= formErp::select('1[fk_id_turma_turno]', $n_turmas, 'Turmas') ?>
            </div>
        </div>
        <br><br><br>
        <div class="row">
            <div class="col col-md-12 text-center">
                <?php
                   echo formErp::hidden([   
                    'id_turma' => $id_turma,    
                    'id_inst' => $id_inst,    
                    '1[fk_id_turma_AEE]' => $id_turma,    
                    '1[fk_id_pessoa_aluno]' => $id_pessoa,    
                    '1[fk_id_pessoa_cadastra]' => toolErp::id_pessoa(),    
                    ]); 
                ?>
                <?=
                    formErp::hiddenToken('apd_turma_aluno', 'ireplace')
                    . formErp::button('Salvar');
                ?>            
            </div>
        </div>       
    </form>
</div>
        