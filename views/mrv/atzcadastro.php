<?php
javaScript::cep();
$id_pessoa = $_POST['id_pessoa'];
$aluno = sql::get('mrv_beneficiado', '*', ['id_pessoa' => @$id_pessoa], 'fetch');

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
                    <div class="col-md-2">
                        <?php echo formulario::input('1[id_pessoa]', 'RSE', null, $aluno['id_pessoa'], 'readonly') ?>
                    </div>  
                    <div class="col-md-4">
                        <?php echo formulario::input('1[n_pessoa]', 'Nome Aluno', null, addslashes($aluno['n_pessoa'])) ?>
                    </div>
                    <div class="col-md-2">
                        <?php formulario::select('1[sex_aluno_ben]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', $aluno['sex_aluno_ben']) ?>
                    </div>  
                    <div class="col-md-2">
                        <?php echo formulario::input('[dt_nasc]', 'Data Nasc.', null, data::converteBr($aluno['dt_nasc']), 'readonly') ?>
                    </div>  
                    <div class="col-md-2">
                        <?php echo formulario::input('1[ra_ben]', 'RA', null, $aluno['ra_ben'], 'readonly') ?>
                    </div>  
                </div>
                <br />
                <div class="row">
                    <div class="col-md-2">
                        <?php echo formulario::input('1[rg_aluno_ben]', 'RG', null, $aluno['rg_aluno_ben']) ?>
                    </div>   
                    <div class="col-md-2">
                        <?php echo formulario::input('1[oe_aluno_ben]', 'Orgão Exp.', null, $aluno['oe_aluno_ben']) ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo formulario::input('1[tel_ben]', 'Telefone', null, $aluno['tel_ben']) ?>
                    </div>
                    <div class="col-md-2">
                        <?php echo formulario::input('1[cel_ben]', 'Celular', null, $aluno['cel_ben']) ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo formulario::input('1[email_fieb_ben]', 'Email', null, $aluno['email_fieb_ben']) ?>
                    </div>                
                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <?php echo formulario::input('1[n_responsavel_ben]', 'Responsável Legal', null, $aluno['n_responsavel_ben']) ?>
                    </div>   
                    <div class="col-md-2">
                        <?php echo formulario::input('1[rg_responsavel_ben]', 'RG. Resp. ', null, $aluno['rg_responsavel_ben']) ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo formulario::input('1[cpf_responsavel_ben]', 'CPF Resp.', null, $aluno['cpf_responsavel_ben']) ?>
                    </div>           
                </div>
                <br />
                <div style="font-size: 15px">
                    <div style="color: red; padding-left: 15px">
                        Endereço
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?php echo formulario::input('1[logradouro]', 'Logradouro', null, $aluno['logradouro'], 'id="rua"') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[num_ben]', 'Nº. ', null, $aluno['num_ben']) ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[complemento_ben]', 'Compl. ', null, $aluno['complemento_ben']) ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-2">
                            <?php echo formulario::input('1[bairro]', 'Bairro', null, $aluno['bairro'], 'id="bairro"') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[localidade]', 'Cidade ', null, $aluno['localidade'], 'id="cidade"') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[uf]', 'Estado ', null, $aluno['uf'], 'id="uf"') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[cep]', 'CEP ', null, $aluno['cep'], 'id="cep"', 'Digite somente números') ?>
                        </div>
                        <div class="col-md-4">
                            <?php formulario::select('1[morador_barueri_ben]', ['Sim' => 'Sim', 'Não' => 'Não'], 'Morador de '.CLI_CIDADE.' ?', $aluno['morador_barueri_ben']) ?>
                        </div>
                    </div>        
                </div>
            </div>

            <div style="font-size: 15px">
                <div style="color: red; padding-left: 15px">
                    Outras Informações
                </div>
                <div class="row">
                    <div class="col-md-8"> 
                        <!--
                        <?php formulario::select('1[estudou4_ben]', ['Sim' => 'Sim', 'Não' => 'Não'], 'Estudou nas Escolas da Rede Muncipal ou nas Escolas mantidas pela FIEB do 6º ano ao 9º ano do Ensino Fundamental ?', $aluno['estudou4_ben']) ?>
                        -->
                        <?php echo formulario::input('1[obs_ben]', 'Observação: ', null, $aluno['obs_ben']) ?>
                    </div>
                    <div class="col-md-4"> 
                        <?php formulario::select('1[status_ben]', ['Ag. Def.' => 'Ag. Def.', 'Indeferida(Doc)' => 'Indeferida(Doc)', 'NI' => 'NI', 'Não Munícipe' => 'Não Munícipe'], 'Status', $aluno['status_ben']) ?>           
                        <!--
                        <?php formulario::select('1[status_ben]', ['Ag. Def.' => 'Ag. Def.', 'Deferida' => 'Deferida', 'Indeferida' => 'Indeferida', 'NI' => 'NI', 'Não Munícipe' => 'Não Munícipe'], 'Status', $aluno['status_ben']) ?>
                        -->
                    </div>
                </div>
                <br />
                <!--
                <div class="row">
                    <div class="col-md-12">           
                <?php echo formulario::input('1[obs_ben]', 'Observação: ', null, $aluno['obs_ben']) ?>
                    </div>
                </div>   
                -->
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">   
                    <?php
                    ?>
                    <?php echo formulario::hidden(['1[id_pessoa]' => $aluno['id_pessoa']]) ?>
                    <?php echo DB::hiddenKey('gravaatz') ?>

                    <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
                    <input type="hidden" name= "id_turma" value="<?php echo $aluno['fk_id_turma'] ?>" />
                    <input type="submit" style="width: 40%" class ="art-button" value="Salvar" />
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

<form id = "fecha" action="<?php echo HOME_URI ?>/mrv/selecaoatz" method="POST">  
    <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
    <input type="hidden" name= "id_turma" value="<?php echo $aluno['fk_id_turma'] ?>" />
</form>