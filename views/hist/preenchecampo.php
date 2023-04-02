<?php
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$tabela = filter_input(INPUT_POST, 'AlunoSit', FILTER_UNSAFE_RAW);
$salvar = filter_input(INPUT_POST, 'liberabotao', FILTER_SANITIZE_NUMBER_INT);
$aluno = new aluno($id_pessoa);

?>

<div class="fieldBody">
    <div class="row fieldWhite">
        <div class="col-lg-10">
            <form method="POST">
                <div class="row" >
                    <div class="col-md-3">
                        <?php //echo formulario::input(NULL, 'RSE', NULL, $id_pessoa, 'readonly') ?>
                        <?php echo formulario::input('a[id_pessoa]', 'RSE', NULL, @$aluno->_rse, 'disabled') ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo form::input(NULL, 'RA', @$aluno->_ra, 'disabled') ?>
                    </div>  
                    <div class="col-md-3">
                        <?php echo form::input(NULL, 'RA Digito', @$aluno->_ra_dig, 'disabled') ?>
                    </div> 
                    <div class="col-md-3">
                        <?php echo form::input(NULL, 'RA UF', @$aluno->_ra_uf, 'disabled') ?>
                    </div> 
                </div>
                <div class="row" >
                    <div class="col-md-6">
                        <?php echo formulario::input('a[n_pessoa]', 'Nome', NULL, @$aluno->_nome, 'required') ?>
                    </div>  
                    <div class="col-md-3">
                        <?php formulario::select('a[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$aluno->_sexo, NULL, NULL, '') ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo formulario::input('a[dt_nasc]', 'D.Nasc.', NULL, data::converteBr(@$aluno->_nasc), formulario::dataConf(2), 'required') ?>
                    </div>
                </div>          
                <div class="row">
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[rg]', 'RG', NULL, @$aluno->_rg, "onkeypress='return SomenteNumero(event)'", 'required')
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[rg_dig]', 'RG Digito', NULL, @$aluno->_rgDig, 'required')
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[rg_oe]', 'Orgão Emissor', NULL, @$aluno->_rgOe, 'required')
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        formulario::input('a[dt_rg]', 'D.Emissão:', NULL, @$aluno->_rgDt == '0000-00-00' ? '' : data::converteBr($aluno->_rgDt), formulario::dataConf(1), 'required')
                        ?>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-12">
                        &nbsp&nbspCertidão Antiga
                        <?php
                        echo formulario::input('a[certidao]', 'Cert. Nascimento (antiga:Termo-Livro-Folha):', NULL, @$aluno->_certidao)
                        ?>
                    </div>
                </div>
                <div>
                    &nbsp&nbspCertidão Nova
                </div>
                <div class="row"> 
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_cartorio]', 'Cartório(6 dígitos):', NULL, @$aluno->_novacert_cartorio)
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_acervo]', 'Acervo(2 dígitos):', NULL, @$aluno->_novacert_acervo)
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_regcivil]', 'Matrícula:(2 dígitos)', NULL, @$aluno->_novacert_regcivil)
                        ?>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_ano]', 'Ano(4 dígitos):', NULL, @$aluno->_novacert_ano)
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_tipolivro]', 'Tipo(1 dígito):', NULL, @$aluno->_novacert_tipolivro)
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_numlivro]', 'Livro(5 dígitos):', NULL, @$aluno->_novacert_numlivro)
                        ?>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_folha]', 'Folha(3 dígitos):', NULL, @$aluno->_novacert_folha)
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_termo]', 'Termo(7 dígitos):', NULL, @$aluno->_novacert_termo)
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo formulario::input('a[novacert_controle]', 'Controle(2 dígitos):', NULL, @$aluno->_novacert_controle)
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <?php
                        echo formulario::input('a[nacionalidade]', 'Nacionalidade: ', NULL, @$aluno->_nascionalidade, 'required')
                        ?>
                    </div>
                    <div class="col-md-2">
                        <?php
                        formulario::selectDB('estados', 'a[uf_nasc]', 'UF:', @$aluno->_nascUf, ' required ', NULL, NULL, ['sigla' => 'sigla'])
                        ?>
                    </div>
                    <div class="col-md-5">
                        <?php
                        echo formulario::input('a[cidade_nasc]', 'Cidade de Nascimento: ', NULL, @$aluno->_nascCidade, 'required')
                        ?>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-md-6 text-center">
                        <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                        <input type="hidden" name="liberabotao" value="<?php echo true ?>" />
                        <?php
                        if ($tabela == 'Regular') {
                            echo formulario::hidden(['a[id_pessoa]' => $id_pessoa]);
                            echo DB::hiddenKey('pessoa', 'replace');
                        } else {
                            echo formulario::hidden(['a[id_nsdp]' => $aluno->_aluno_nsdp]);
                            echo DB::hiddenKey('ge_aluno_nsdp', 'replace');
                        }
                        ?>
                        <input class="btn btn-success" type="submit" value="Salvar" />
                    </div>  
                    <div class="col-md-6 text-center">
                        <?php
                        if ($salvar == true) {
                            $js = 'onclick="document.getElementById(\'histset\').submit()"';
                            ?>
                            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                            <button class="btn btn-primary" type="button" <?php echo @$js ?> >
                                Prosseguir
                            </button>
                            <?php
                        } else {
                            ?>
                            <button class="btn btn-default" type="button" <?php echo @$js ?> >
                                Prosseguir
                            </button>
                            <?php
                        }
                        ?>
                    </div> 
                </div>   
            </form>
        </div>
    </div>
</div>

<form id="histset" action="<?php echo HOME_URI ?>/hist/histset" method="POST">
    <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
</form>
<?php
javaScript::somenteNumero();
?>
