<?php
if (!defined('ABSPATH'))
    exit;
$id_porte = filter_input(INPUT_POST, 'id_porte', FILTER_SANITIZE_NUMBER_INT);
$fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_prof = toolErp::id_pessoa();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT); 
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_UNSAFE_RAW); 
$titulo = 'Nova Adaptação Curricular';
$activeNav = 2;

$unidade_letiva = $model->getUnidadeLetiva($fk_id_pessoa);
?>

<div class="body">
   <div class="fieldTop" style="padding-bottom: 5%;">
        <?= $titulo ?>
   </div>

<?php
if (!empty($unidade_letiva)) {?>
    <form  name="form" action="<?= HOME_URI ?>/profe/adaptCurriculo" method="POST" target='_parent'>     <div class="row">
            <div class="col-md-3">
                <div class="input-group">
                    <div class="row">
                        <div class="col"> 
                            <div class="input-group-prepend">
                                    <label class="input-group-text" for="form">Bimestre</label>
                                </div>
                            </div>
                         </div>
                         <div class="col-md-6">   
                        <select id="form" required class="selectpicker" id="1[fk_id_hab]" name="1[qt_letiva]" for="<?= $idForm ?>" style=" height: 38px;" data-width="fit"  data-style="btn-outline-info">
                            <?php foreach ($unidade_letiva as $k => $v){ 
                                $un_letiva = $unidade_letiva[$k]['un_letiva'];
                                $id_turma_aluno = $unidade_letiva[$k]['id_turma_aluno'];
                                ?>
                                <option value="<?= $unidade_letiva[$k]['qt_letiva'] ?>"><?= $unidade_letiva[$k]['qt_letiva'] ?> º <?= $unidade_letiva[$k]['un_letiva'] ?></option>
                            <?php } ?>
                        </select>
                     </div>      
                </div>
            </div>

            <div class="col">

                <?=
                formErp::hidden([
                    'activeNav' => 2,
                    'fk_id_pessoa' => $fk_id_pessoa,
                    'id_turma' => $id_turma,
                    'id_porte' => $id_porte,
                    'n_pessoa' => $n_pessoa,
                    '1[fk_id_pessoa]' => $fk_id_pessoa,
                    '1[fk_id_turma_aluno]' => $id_turma_aluno,
                    '1[un_letiva]' => $un_letiva,
                    '1[fk_id_pessoa_prof]' => $id_prof
                ])
                .formErp::hiddenToken('apd_aluno_adaptacao', 'ireplace')
                .formErp::button('Salvar');
                ?>            
            </div>
        </div>
        
    </form>
    
    <?php
}else{?>
    <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div class="col" style="font-weight: bold; text-align: center;">
                <p>É possível criar apenas uma Adaptação Curricular por Bimestre</p>
                
            </div>
        </div>
    </div>
<?php
}
?>
   
</div>