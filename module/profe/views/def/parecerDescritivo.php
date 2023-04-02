<?php
if (!defined('ABSPATH'))
    exit;

$id_parecer = filter_input(INPUT_POST, 'id_parecer', FILTER_SANITIZE_NUMBER_INT);
$id_porte = filter_input(INPUT_POST, 'id_porte', FILTER_SANITIZE_NUMBER_INT);
$dt_nasc = filter_input(INPUT_POST, 'dt_nasc', FILTER_UNSAFE_RAW); 
$id_aluno_adaptacao = filter_input(INPUT_POST, 'id_aluno_adaptacao', FILTER_SANITIZE_NUMBER_INT);
$fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_UNSAFE_RAW);
$campo = filter_input(INPUT_POST, 'campo', FILTER_UNSAFE_RAW);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$titulo = 'Componente Curricular';
$id_prof = toolErp::id_pessoa();

if ($id_parecer) {
    $apd_parecer = sql::get('apd_parecer', '*', ['fk_id_aluno_adaptacao' => $id_aluno_adaptacao], 'fetch');
}?>
<style type="text/css">
    .input-group-text
    {
    display: none;
    }
    textarea {height: 320px !important;}
</style>

<div class="body">
   <div class="fieldTop" style="padding-bottom: 5%;">
        <?= $model->tituloParecer($campo); ?>
   </div>
   <form action="<?= HOME_URI ?>/profe/adaptCurriculo" method="POST" target='_parent'>       
        <div class="row">
            <div class="col">
                <?php
                if ($campo == 'atvd_estudo') { 
                    formErp::textarea('1[atvd_estudo]', @$apd_parecer['atvd_estudo'], 'Atividades de Estudo', '320px');
                }elseif ($campo == 'instr_avaliacao') {
                    formErp::textarea('1[instr_avaliacao]', @$apd_parecer['instr_avaliacao'], 'Instrumentos de Avaliação', '320px');
                }else{
                    formErp::textarea('1[n_parecer]', @$apd_parecer['n_parecer'], 'Parecer Descritivo', '320px');
                }?>
            </div>
        </div>
        <br> <br>
        <div class="row">
            <div class="col text-center">

                <?=
                formErp::hidden([
                    'activeNav' => 3,
                    '1[fk_id_aluno_adaptacao]' => $id_aluno_adaptacao,
                    '1[id_parecer]' => @$id_parecer,
                    'id_aluno_adaptacao' => $id_aluno_adaptacao,
                    'fk_id_pessoa' => $fk_id_pessoa,
                    'n_pessoa' => $n_pessoa,
                    'id_turma' => $id_turma,
                    'id_porte' => $id_porte,
                    'dt_nasc' => $dt_nasc
                ])
                .formErp::hiddenToken('apd_parecer', 'ireplace')
                .formErp::button('Salvar');
                ?>            
            </div>
        </div>
        
    </form>
</div>