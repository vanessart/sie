<?php
$escola = new escola();

if (empty($escola->_ato_cria) || empty($escola->_ato_municipa)) {
    ?>
    <div class="alert alert-danger" style="font-size: 20px;  line-height: 30px">
        <a href="<?php echo HOME_URI ?>/gestao/escola">
            Para utilizar o sistema de histório será necessário cadastrar o <strong style="color: blue">Ato de Criação</strong> e o <strong style="color: blue">Ato de Municipalização</strong> da escola, acessando <strong style="color: blue">CADASTRO</strong> >> <strong style="color: blue">ESCOLA</strong>
        </a>
    </div>
    <?php
} else {
    if (empty($_POST['last_id'])) {
        $id_pessoa = @$_POST['id_pessoa'];
    } else {
        $id_pessoa = @$_POST['last_id'];
    }
    if (is_numeric($id_pessoa)) {
        $aluno = new aluno($id_pessoa);
    }

    function teste($dado) {
        if (empty($dado)) {
            return 1;
        }
    }
    ?>
    <div class="fieldBody">
        <br />
        <div class="fieldTop">
            <?php echo user::session('nome') ?> verifique os dados, e se for necessário alterar os dados: 
            <br />
            Acesse GESTÃO EDUCACIONAL PRINCIPAL - Dados Gerais - Clique no Botão Importar do SED ou clique no botão Editar
        </div>
        <br />  
        <?php
        /*
        $aluno_apd = $model->verificaalunoapd($id_pessoa);
        if (!empty($aluno_apd)) {
            ?>
            <br />
            <p style="text-align: center; color: red">Aluno APD favor gerar o Histórico Manualmente</p>
            <?php
        } else {
         * 
         */
            ?>
            <div class="row fieldWhite">
                <div class="col-md-10">
                    <form method="POST">
                        <div class="row" >
                            <div class="col-md-2">
                                <?php echo formulario::input(NULL, 'RSE', NULL, $id_pessoa, 'disabled') ?>
                            </div>
                            <div class="col-md-2">
                                <?php
                                @$teste += teste(@$aluno->_ra);
                                echo form::input(NULL, 'RA', @$aluno->_ra . '-' . @$aluno->_ra_dig, 'disabled');
                                ?>
                            </div>   
                            <div class="col-md-4">
                                <?php
                                @$teste += teste(@$aluno->_nasc);
                                echo formulario::input('a[dt_nasc]', 'D.Nasc.', NULL, data::converteBr(@$aluno->_nasc), formulario::dataConf(2) . 'disabled')
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                $wsexo = ['F' => 'Feminino', 'M' => 'Masculino'];
                                echo form::input(NULL, 'Sexo', @$wsexo[@$aluno->_sexo], 'disabled')
                                ?>
                            </div> 
                        </div>
                        <div class="row" >
                            <div class="col-md-12">
                                <?php
                                @$teste += teste(@$aluno->_nome);
                                echo formulario::input('a[n_pessoa]', 'Nome', NULL, @$aluno->_nome, 'disabled')
                                ?>
                            </div>      
                        </div>          
                        <div class="row">                  
                            <div class="col-md-5">
                                <?php
                                echo form::input(NULL, 'Cert. Nasc.', (@$aluno->_certidao), 'disabled');
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                //@$teste += teste(@$aluno->_rg);
                                echo form::input(NULL, 'RG', intval(@$aluno->_rg) . '-' . @$aluno->_rgDig . '-' . @$aluno->_rgOe, 'disabled');
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                //@$teste += teste(@$aluno->_rgDt);
                                echo form::input(NULL, 'Data de Emissão:', @$aluno->_rgDt == '0000-00-00' ? '' : data::converteBr($aluno->_rgDt), 'disabled');
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <?php
                                @$teste += teste(@$aluno->_nascionalidade);
                                echo formulario::input('a[nacionalidade]', 'Nacionalidade: ', NULL, @$aluno->_nascionalidade, 'disabled')
                                ?>
                            </div>
                            <div class="col-md-2">
                                <?php
                                @$teste += teste(@$aluno->_nascUf);
                                echo form::input(NULL, 'UF', @$aluno->_nascUf, 'disabled');
                                ?>
                            </div>
                            <div class="col-md-5">
                                <?php
                                @$teste += teste(@$aluno->_nascCidade);
                                echo formulario::input('a[cidade_nasc]', 'Cidade de Nascimento: ', NULL, @$aluno->_nascCidade, 'disabled')
                                ?>
                            </div>

                        </div>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-4 text-right">
                                <?php
                                if (@$teste == 0) {
                                    $js = 'onclick="document.getElementById(\'histset\').submit()"';
                                    $texto = "Prosseguir";
                                    $class = "btn-info";
                                } else {
                                    $texto = "Preencha todos os campos";
                                }
                                ?>
                                <button class="btn <?php echo $class ?>" type="button" <?php echo @$js ?> >
                                    <?php echo $texto ?>
                                </button>
                            </div>
                            <div class="col-md-4 text-right">
                                <?php
                                $js = 'onclick="document.getElementById(\'preenchecampo\').submit()"';
                                $texto = "Editar";
                                $class = "btn-danger";
                                $aSit = 'Regular';
                                ?>
                                <button class="btn <?php echo $class ?>" type="button" <?php echo @$js ?> >
                                    <?php echo $texto ?>
                                </button>
                            </div>
                            <div class="col-md-4 text-right">
                                <?php
                                $s = $model->verificasituacaoaluno($id_pessoa);
                                if (!empty($s)) {
                                    $js = 'onclick="document.getElementById(\'preenchecampo\').submit()"';
                                    ?>
                                    $aSit = 'NSDP';
                                    <button class="btn btn-primary" type="button" <?php echo @$js ?> >
                                        Atualizar Dados
                                    </button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>   
                    </form>
                </div>
                <div class="col-md-2 text-center">
                    <?php
                    if (file_exists(ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg')) {
                        ?>
                        <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $id_pessoa; ?>.jpg" width="134" height="157" alt="360306"/>
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="150" height="180" alt="foto"/>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
       // }
        ?>
        <form id="histset" action="<?php echo HOME_URI ?>/hist/histset" method="POST">
            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
        </form>

        <form id="preenchecampo" action="<?php echo HOME_URI ?>/hist/preenchecampo" method="POST">
            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
            <input type="hidden" name="AlunoSit" value="<?php echo $aSit ?>" />
        </form>

    </div>
    <?php
}
javaScript::somenteNumero();
?>
