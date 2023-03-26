<?php
$id_pessoa = $_POST['id_pessoa'];
$idturma = $_POST['id_turma'];

$aluno = (array) new aluno($id_pessoa);

@$end = sql::get('endereco', '*', ['fk_id_pessoa' => @$id_pessoa], 'fetch');
?>

<br />
<div style="padding: 20px">
    <div class="row">
        <form method="POST">
            <div class="panel panel-default">
                <div style="font-size: 18px; color: red" class="panel panel-heading">
                    Dados do Aluno
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?php echo formulario::input('[n_pessoa]', 'Nome Aluno', null, addslashes($aluno['_nome']), 'readonly') ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo formulario::input('[id_pessoa]', 'RSE', null, $aluno['_rse'], 'readonly') ?>
                    </div>                     
                    <div class="col-md-2">
                        <?php echo formulario::input('[dt_aluno]', 'Data Nasc.', null, data::converteBr($aluno['_nasc']), 'readonly') ?>
                    </div>  
                    <div class="col-md-2">
                        <?php echo formulario::input('[ra_ben]', 'RA', null, $aluno['_ra'], 'readonly') ?>
                    </div>  
                    <div class="col-md-2">
                        <?php echo formulario::input('[rg_aluno_ben]', 'RG', null, $aluno['_rg'], 'readonly') ?>
                    </div>                  
                </div>
                <br />
                <div style="font-size: 15px">
                    <div style="color: red; padding-left:15px">
                        Endereço
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?php echo formulario::input('[logradouro]', 'Logradouro', null, $end['logradouro_gdae'], 'readonly') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('[num_ben]', 'Nº. ', null, $end['num_gdae'], 'readonly') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('[complemento_ben]', 'Compl. ', null, $end['complemento'], 'readonly') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo formulario::input('[bairro]', 'Bairro', null, $end['bairro'], 'readonly') ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo formulario::input('[localidade]', 'Cidade ', null, $end['cidade'], 'readonly') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('[cep]', 'CEP ', null, $end['cep'], 'readonly') ?>
                        </div>
                        <div class="col-md-4">
                            <?php formulario::input('[_emailGoogle]', 'Email', null, @$aluno['_emailGoogle'], 'readonly') ?>
                        </div>
                        <!--
                        <div class="col-md-4">
                        <?php formulario::select('mora4', ['Sim' => 'Sim', 'Não' => 'Não'], 'Morador de Barueri há mais de 4 anos ?', @$mora4, NULL, NULL, 'required') ?>
                        </div>
                        -->
                    </div>        
                </div>
            </div>
            <!--
                        <div style="font-size: 15px">
                            <div style="color: red">
                                Escolaridade
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                     <?php formulario::select('estuda', ['Sim' => 'Sim', 'Não' => 'Não'], 'Estudou nas Escolas da Rede Muncipal ou nas Escolas mantidas pela FIEB do 6º ano ao 9º ano do Ensino Fundamental ?', @$estuda, NULL, NULL, 'required') ?>           
                                </div>
                            </div>
                        </div>
            -->
            <div class="panel panel-default">
                <div style="font-size: 18px; color: red" class="panel panel-heading">
                    Conferir os Dados do Aluno, as alterações deverão ser feitas no SED e sincronizado no Gestão Educacional(novo). 
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">   
                    <?php
                    $morador = 'Não';
                    $def = 'Indeferida';

                    if (($end['cidade'] == 'Barueri') or ( $end['cidade'] == 'BARUERI')) {
                        $morador = 'Sim';
                        $def = 'Ag. Def.';
                    }
                    ?>
                    <?php echo DB::hiddenKey('inscricaoitb') ?>
                    <?php echo formulario::hidden(['morador' => $morador, 'deferimento' => $def]) ?>

                    <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
                    <input type="hidden" name= "id_turma" value="<?php echo $idturma ?>" />
                    <input type="hidden" name= "cidade" value="<?php echo $end['cidade'] ?>" />
                    <input type="hidden" name= "id_inst" value="<?php echo tool::id_inst() ?>" />

                    <input type="submit" style="width: 40%" class ="art-button" value="Confirmar Inscrição" />
                </div>
                <div class="col-md-6">                           
                    <button type="button" onclick="$('#fecha').submit()" style="width: 40%" class="art-button">
                        Retornar
                    </button>   
                </div>      
            </div>
        </form>
    </div>
</div>

<form id = "fecha" action="<?php echo HOME_URI ?>/mrv/cadastro" method="POST">  
    <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
    <input type="hidden" name= "id_turma" value="<?php echo $idturma ?>" />
</form>