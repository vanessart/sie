<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT); 
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_UNSAFE_RAW); 
$id_aluno_adaptacao = filter_input(INPUT_POST, 'id_aluno_adaptacao', FILTER_SANITIZE_NUMBER_INT);
$n_parecer = '<i>'. (toolErp::id_nilvel()==24 ? "Escreva aqui" : "O Professor irá descrever aqui").' o Parecer Descritivo ao longo do Bimestre</i>';

if ($id_aluno_adaptacao) {

    $aluno = sql::get(['apd_aluno_adaptacao','pessoa'], 'pessoa.n_pessoa, pessoa.id_pessoa', 'WHERE apd_aluno_adaptacao.id_aluno_adaptacao ='.$id_aluno_adaptacao, 'fetch', 'left');

    $parecer = sql::get('apd_parecer', 'n_parecer, apd_parecer.id_parecer', 'WHERE apd_parecer.fk_id_aluno_adaptacao ='.$id_aluno_adaptacao, 'fetch', 'left');  
        $comp = $model->getComponentesAEE($id_aluno_adaptacao);
        
    if ($aluno) {
        $n_pessoa = $aluno['n_pessoa']; 
        $fk_id_pessoa = $aluno['id_pessoa']; 
    }

    if ($parecer) {
        $n_parecer = $parecer['n_parecer']; 
        $id_parecer = $parecer['id_parecer'];
    }

    if ($comp){
        $sqlkey = formErp::token('apd_componente', 'delete');
        foreach ($comp as $k => $v){
            $comp[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_componente'] . ')">Editar</button>'; 
            $comp[$k]['del'] = formErp::submit('Apagar', $sqlkey, ['1[id_componente]' => $v['id_componente'],'id_aluno_adaptacao' => $id_aluno_adaptacao,'fk_id_pessoa' => $fk_id_pessoa,'activeNav' => 3,'n_pessoa'=>$n_pessoa,'id_turma'=>$id_turma]);
        }
        $form['array']=$comp;
        if (toolErp::id_nilvel()==24) {
            if ($id_porte == 3) {
                $form['fields']=[
                    'Componente Curricular'=>'n_componente',
                    'Unidade Temática'=>'unidade',
                    'Objeto de Conhecimento'=>'objeto',
                    'Habilidade'=>'habilidade',
                    'Classificação'=>'n_nota',
                    //'||2' => 'del',
                    '||1' => 'edit'
                ];
            }else{
                $form['fields']=[
                    'Unidade Temática'=>'unidade',
                    'Objeto de Conhecimento'=>'objeto',
                    'Situação Didática'=>'sit_didatica',
                    'Recursos'=>'recurso',
                    //'||2' => 'del',
                    '||1' => 'edit'
                ];
            }
            
        }else{
            if ($id_porte == 3) {
                $form['fields']=[
                    'Componente Curricular'=>'n_componente',
                    'Unidade Temática'=>'unidade',
                    'Objeto de Conhecimento'=>'objeto',
                    'Habilidade'=>'habilidade',
                    'Classificação'=>'n_nota',
                ];
            }else{
                $form['fields']=[
                    'Unidade Temática'=>'unidade',
                    'Objeto de Conhecimento'=>'objeto',
                    'Situação Didática'=>'sit_didatica',
                    'Recursos'=>'recurso'
                ];
            }
        }
    }?>

    <style type="text/css">
        td {
            vertical-align: middle;
        }
    </style>
    <div class="body"> 
        <?php 
        if ($id_porte == 3) {?>
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th colspan="2" class="table-active" style="text-align:center;">
                                PARECER DESCRITIVO DE <?= $n_pessoa ?> - RSE: <?= $fk_id_pessoa ?>
                            </th>
                        </tr>
                        <tr>
                            <td style="width:90% ;text-align:justify;">
                                <?= $n_parecer ?>
                            </td>
                            <?php 
                                if (toolErp::id_nilvel()==24) {?>
                                <td style="text-align:center; vertical-align: middle;">
                                    <?php echo '<button class="btn btn-info" onclick="parecer(\'n_parecer\',' . @$id_parecer . ')">Editar</button>'; 
                                    ?>
                                </td>
                                <?php 
                            } ?>
                        </tr>
                    </table>
                </div>
            </div>
            <?php 
        }?>
        <div class="row">
            <?php 
            if (toolErp::id_nilvel()==24) {?>
                <div class="col">
                    <button class="btn btn-outline-info" onclick="edit()">
                       Adicionar Componente Curricular
                    </button>
                </div>
                <?php
                if ($id_porte == 2) {?>
                    <div class="col">
                        <button class="btn btn-outline-info" onclick="parecer('atvd_estudo',<?= @$id_parecer ?>)">
                           Adicionar Atividades de Estudo
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn btn-outline-info" onclick="parecer('instr_avaliacao',<?= @$id_parecer ?>)">
                           Adicionar Instrumentos de Avaliação
                        </button>
                    </div>
                    <?php
                }
            }?>
        </div>
        <br><br>
        <?php
    }else{?>
        <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
            <div class="row">
                <div class="col" style="font-weight: bold; text-align: center;">
                    <p> Escolha um bimestre na aba "Adaptação Curricular"</p>
                    
                </div>
            </div>
        </div>
        <?php
    }

    if(!empty($form)){
        report::simple($form);
    }?>
    <form id="formComp" target="frame" action="<?= HOME_URI ?>/profe/def/componenteCurricular.php" method="POST">
        <input type="hidden" name="id_aluno_adaptacao" id="id_aluno_adaptacao" value="<?= $id_aluno_adaptacao ?>" />
        <input type="hidden" name="id_componente" id="id_componente" value="" />
        <input type="hidden" name="fk_id_pessoa" id="fk_id_pessoa" value="<?= $fk_id_pessoa ?>" />
        <input type="hidden" name="n_pessoa" id="n_pessoa" value="<?= $n_pessoa ?>" />
        <input type="hidden" name="id_turma" id="id_turma" value="<?= $id_turma ?>" />
        <input type="hidden" name="id_porte" id="id_porte" value="<?= $id_porte ?>" />
    </form>

    <form id="formPar" target="frameP" action="<?= HOME_URI ?>/profe/def/parecerDescritivo.php" method="POST">
        <input type="hidden" name="id_aluno_adaptacao" id="id_aluno_adaptacao" value="<?= $id_aluno_adaptacao ?>" />
        <input type="hidden" name="id_parecer" id="id_parecer" value="" />
        <input type="hidden" name="fk_id_pessoa" id="fk_id_pessoa" value="<?= $fk_id_pessoa ?>" />
        <input type="hidden" name="n_pessoa" id="n_pessoa" value="<?= $n_pessoa ?>" />
        <input type="hidden" name="id_turma" id="id_turma" value="<?= $id_turma ?>" />
        <input type="hidden" name="campo" id="campo" value="" />
        <input type="hidden" name="id_porte" id="id_porte" value="<?= $id_porte ?>" />
        
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>

    <?php
    toolErp::modalInicio(null, null, 'pModal');
    ?>
    <iframe name="frameP" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim(null, null, 'pModal');
    ?>
</div>

<script>
    $('#myModal').on('hidden.bs.modal', function () {
        document.getElementById("formComp").action = '<?= HOME_URI ?>/profe/def/componenteCurricular.php';
    });

    $('#pModal').on('hidden.bs.modal', function () {
        document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/parecerDescritivo.php';
    });

    function edit(id){
        if (id){
            document.getElementById("id_componente").value = id;
        }else{
            document.getElementById("id_componente").value = "";
        }
        document.getElementById("formComp").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function parecer(campo,id){
        if (id){
            document.getElementById("id_parecer").value = id;
        }else{
            document.getElementById("id_parecer").value = "";
        }
        document.getElementById("campo").value = campo;
        document.getElementById("formPar").submit();
        $('#pModal').modal('show');
        $('.form-class').val('');
    }
</script>


