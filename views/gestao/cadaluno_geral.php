<?php
javaScript::cpf();
javaScript::somenteNumero();
if (!empty($_POST['pessoaGedae'])) {
    $ins = $model->pessoaGedae(@$_POST['ra'], @$_POST['ra_dig'], @$_POST['ra_uf']);
    if (!empty($ins)) {
        if(empty($ins['id_pessoa'])){
        $dados['n_pessoa'] = $ins['n_pessoa'];
        $dados['ra'] = $ins['ra'];
        $dados['ra_dig'] = $ins['ra_dig'];
        $dados['ra_uf'] = $ins['ra_uf'];
        $dados['sexo'] = $ins['sexo'];
        $dados['dt_nasc'] = $ins['dt_nasc'];
        $dados['rg'] = @$ins['rg'];
        $dados['rg_dig'] = @$ins['rg_dig'];
        $dados['rg_uf'] = @$ins['rg_uf'];
        $dados['dt_rg'] = @$ins['dt_rg'];
        $dados['certidao'] = $ins['certidao'];
        $dados['nacionalidade'] = $ins['nacionalidade'];
        $dados['uf_nasc'] = $ins['uf_nasc'];
        $dados['cidade_nasc'] = $ins['cidade_nasc'];
        $dados['mae'] = $ins['mae'];
        $dados['pai'] = $ins['pai'];
        $dados['tel2'] = $ins['tel2'];
        $dados['tel3'] = $ins['tel3'];
        } else {
            $dados = $ins;
        }
    }
}
?>
<script>
    function paii() {
        if (document.getElementById('pai').value == 'SPE') {
            document.getElementById('pai').checked = true;
            document.getElementById('pai').value = document.getElementById('paiold').value;
            document.getElementById('pai').readOnly = false;
        } else {
            document.getElementById('pai').checked = false;
            document.getElementById('paiold').value = document.getElementById('pai').value;
            document.getElementById('pai').value = 'SPE';
            document.getElementById('pai').readOnly = true;
        }
    }
</script>
<style>
    .descricao{
        display: none;
    }

    .item:hover .descricao{
        display: block;
    }
</style>
<br /><br />
<div class="btn-default" style="padding: 8px; border: #000000 solid 1px">
         Atenção! Os campos abaixo são apenas para importação do GDAE
         <br /><br />
        <form style="text-align: center" method="POST">
            <div class="row">
                <div class="col-sm-3">
                    <?php echo formulario::input('ra', 'RA', NULL, @$dados['ra']) ?>
                </div>
                <div class="col-sm-3">
                    <?php echo formulario::input('ra_dig', 'Digito (RA)', NULL, @$dados['ra_dig']) ?>
                </div>
                <div class="col-sm-3">
                    <?php echo formulario::input('ra_uf', 'UF (RA)', NULL, empty($dados['ra_uf']) ? 'SP' : $dados['ra_uf']) ?>
                </div>
                <div class="col-sm-3">
                    <input type="hidden" name="aba" value="geral" />
                    <input type="hidden" name="id_pessoa" value="<?php echo @$dados['id_pessoa'] ?>" />
                    <input type="hidden" name="activeNav" value="1" />
                    <input class="btn btn-info" type="submit" value="Importar do GDAE" name="pessoaGedae" />
                </div>
            </div>

        </form>
        <br />
        As informações importadas podem ser editadas e só serão efetuadas após clicar em salvar
        <br />
    </div>
<br /><br />
<form method="POST">
    <div class="panel panel-default">
        <div class="panel panel-heading">
            Identificação
        </div>
        <div class="panel panel-body">
            <div class="row" >
                <div class="col-md-3">
                    <?php echo formulario::input('a[id_pessoa]', 'RSE', NULL, @$dados['id_pessoa'], 'readonly') ?>
                </div>
                <div class="col-md-5">
                    <?php echo formulario::input('a[n_pessoa]', 'Nome', NULL, empty(@$dados['n_pessoa']) ? @$_POST['nome'] : @$dados['n_pessoa'], 'required')
                    ?>
                </div>
                <div class="col-md-4" style="display: none">
                    <?php echo formulario::input('a[n_social]', 'Nome Social', NULL, @$dados['n_social'])
                    ?>
                </div>
            </div>
            <br /><br />
            <div class="row" >           
                <div class="col-md-3">
                    <?php echo formulario::input('a[ra]', 'RA (Só número)', NULL, @$dados['ra'],'readonly', " onkeypress='return SomenteNumero(event)'")
                    ?>
                </div>          
                <div class="col-md-2">
                    <?php echo formulario::input('a[ra_dig]', 'Digito (RA)', NULL, @$dados['ra_dig'], 'readonly')
                    ?>
                </div>  
                <div class="col-md-2">
                    <?php
                    echo formulario::input('a[ra_uf]', 'UF (RA)', NULL, @$dados['ra_uf'], 'readonly');
                    ?>
                </div>
                <div class = "col-md-5">
                    <?php
                    echo formulario::input('a[email]', 'E-mail', NULL, @$dados['email']);
                    formulario::hidden(['a[ativo]' => 1])
                    ?>
                </div> 
            </div> 
            <br /><br />
            <div class="row">
                <div class="col-md-2">
                    <?php formulario::select('a[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$dados['sexo'], NULL, NULL, 'required')
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    $corPela = pessoa::corPela();
                    formulario::select('a[cor_pele]', $corPela, 'Cor', @$dados['cor_pele'], NULL, NULL, 'required')
                    ?>
                </div>
                <div class="col-md-3">
                    <?php echo formulario::input('a[dt_nasc]', 'D.Nasc.', NULL, data::converteBr(@$dados['dt_nasc']), formulario::dataConf(2) . ' required')
                    ?>
                </div>
                <div class="col-md-4">
                    <?php echo formulario::input('a[cpf]', 'CPF', NULL, @$dados['cpf'], ' onblur="validarCPF(this.value);" maxlength="14" onkeypress="return SomenteNumero(event)"')
                    ?>
                </div>
            </div>
            <br /><br />
            <div class="row">

                <div class="col-md-2">
                    <?php echo formulario::input('a[rg]', 'RG', NULL, @$dados['rg']) ?>
                </div>
                <!-- Dígito RG -->
                <div class="col-md-2">
                    <?php echo formulario::input('a[rg_dig]', 'Digito (RG)', NULL, @$dados['rg_dig']) ?>
                </div>

                <div class="col-md-2">
                    <?php echo formulario::input('a[rg_oe]', 'Orgão Emissor', NULL, @$dados['rg_oe']) ?>
                </div>
                <div class="col-md-2">
                    <?php formulario::selectDB('estados', 'a[rg_uf]', 'RG-UF:', @$dados['rg_uf'], NULL, NULL, NULL, ['sigla' => 'sigla']) ?>
                </div>
                <div class="col-md-2">
                    <?php formulario::input('a[dt_rg]', 'Data de Emissão:', NULL, @$dados['dt_rg'] == '0000-00-00' ? '' : data::converteBr($dados['dt_rg']), formulario::dataConf(1)) ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-6">
                    <?php echo formulario::input('a[certidao]', 'Certidão de Nasc.*', NULL, @$dados['certidao'], ' required ') ?>
                </div>
                <div class="col-md-6">
                    <?php echo formulario::input('a[sus]', 'Cartão SUS', NULL, @$dados['sus'], ' ') ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-3">
                    <?php echo formulario::input('a[nacionalidade]', 'Nacionalidade: ', NULL, empty(@$dados['nacionalidade']) ? 'Brasileira' : @$dados['nacionalidade'], ' required') ?>
                </div>
                <div class="col-md-2">
                    <?php formulario::selectDB('estados', 'a[uf_nasc]', 'UF:', @$dados['uf_nasc'], ' required', NULL, NULL, ['sigla' => 'sigla']) ?>
                </div>
                <div class="col-md-3">
                    <?php echo formulario::input('a[cidade_nasc]', 'Cidade de Nascimento: ', NULL, @$dados['cidade_nasc'], ' required') ?>
                </div>
                <div class="col-md-4">
                    <?php formulario::select('a[deficiencia]', [1 => 'Sim', 0 => 'Não'], 'Possui Deficiência ou Transtorno?', @$dados['deficiencia'])
                    ?>
                </div>
            </div>
        </div>
        * Se a Certidão de Nascimento for anterior a 2011(Modelo Antigo) incluir na ordem e separado com "-": termo-livro-folha
    </div>
    <div class="panel panel-default">
        <div class="panel panel-heading">
            Filiação e Responsável
        </div>
        <div class="panel panel-body">
            <div class="row">
                <div class="col-md-8">
                    <?php echo formulario::input('a[mae]', 'Nome da Mãe:', NULL, str_replace("'", '', @$dados['mae']), 'id="mae" ' . @$requiredCad) ?>
                </div>
                <div class="col-md-4">
                    <?php echo formulario::input('a[cpf_mae]', 'CPF da Mãe:', NULL, @$dados['cpf_mae'], 'id="cpf_mae" onblur="validarCPF(this.value);" maxlength="14"  onkeypress="return SomenteNumero(event)"') ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-7">
                    <?php echo formulario::input('a[pai]', 'Nome da Pai:', NULL, str_replace("'", '', @$dados['pai']), 'id="pai" ' . @$requiredCad) ?>
                    <input id="paiold" type="hidden" name="" value="" />
                </div>
                <div class="col-md-1">
                    <div class="input-group">
                        <div class="input-group-addon item">
                            <label >
                                <input type="checkbox"  onclick="paii()" />
                                <a>SPE</a>
                                <div style="position: absolute; color: red; margin-top: -50px; margin-left: -95px; z-index: 9999; background-color: yellow" class="descricao">Sem Paternidade Estabelecida</div>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <?php echo formulario::input('a[cpf_pai]', 'CPF da Pai:', NULL, @$dados['cpf_pai'], 'id="cpf_pai" onblur="validarCPF(this.value);" maxlength="14"  onkeypress="return SomenteNumero(event)"') ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="text-align: left">
                            O Responsável legal pelo aluno:
                        </span>
                        <span class="input-group-addon" style="text-align: left; background-color: white; width: 10%">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            if (!empty(@$dados['responsavel'])) {
                                if (@$dados['mae'] == @$dados['responsavel']) {
                                    $cmae = 'checked';
                                } elseif (@$dados['pai'] == @$dados['responsavel']) {
                                    $cpai = 'checked';
                                } else {
                                    $cresp = 'checked';
                                }
                            }
                            ?>
                            <label>
                                <input <?php echo @$cmae ?> onclick="document.getElementById('responsavel').value = document.getElementById('mae').value;document.getElementById('cpf_respons').value = document.getElementById('cpf_mae').value;" type="radio" name="resp" value="ON" />
                                Mãe 
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label>
                                <input <?php echo @$cpai ?> onclick="document.getElementById('responsavel').value = document.getElementById('pai').value;document.getElementById('cpf_respons').value = document.getElementById('cpf_pai').value;" type="radio" name="resp" value="ON" />
                                Pai 
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label>
                                <input <?php echo @$cresp ?> onclick="document.getElementById('responsavel').value = '';document.getElementById('cpf_respons').value = ''" type="radio" name="resp" value="ON" />
                                Outro 
                            </label>

                        </span>     
                    </div>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-8">
                    <?php echo formulario::input('a[responsavel]', 'Nome do Responsável:', NULL, str_replace("'", '', @$dados['responsavel']), 'id="responsavel" ' . @$requiredCad) ?>
                </div>
                <div class="col-md-4">
                    <?php echo formulario::input('a[cpf_respons]', 'CPF do Responsável:', NULL, @$dados['cpf_respons'], 'id="cpf_respons"  onblur="validarCPF(this.value);" maxlength="14"  onkeypress="return SomenteNumero(event)" ' . @$requiredCad) ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-12">
                    <?php echo formulario::input('a[email_respons]', 'E-mail do Responsável:', NULL, @$dados['email_respons']) ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-4">
                    <?php echo formulario::input('a[tel1]', 'Telefone 1:', NULL, @$dados['tel1']) ?>
                </div>
                <div class="col-md-4">
                    <?php echo formulario::input('a[tel2]', 'Telefone 2:', NULL, @$dados['tel2']) ?>
                </div>
                <div class="col-md-4">
                    <?php echo formulario::input('a[tel3]', 'Telefone 3:', NULL, @$dados['tel3']) ?>
                </div>
            </div>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-12 offset4" style="text-align: center">
            <?php echo formulario::hidden(['a[id_pessoa]' => @$dados['id_pessoa']]) ?>
            <input type="hidden" name="id_pessoa" value="<?php echo @$dados['id_pessoa'] ?>" />
            <?php
            echo DB::hiddenKey('pessoa', 'replace');
            ?>
            <input type="hidden" name="aba" value="end" />
            <input class="btn btn-success" type="submit" value="Salvar" id="btn" />

        </div>
    </div>
</form>
<br />
<div class="col-md-2">

</div>
