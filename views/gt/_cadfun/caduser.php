<div style="padding: 10px; width: 95%; margin: 0 auto">

    <?php
    $id_inst = tool::id_inst(@$_POST['$id_inst']);
    javaScript::cpf();
    $cpf = @$_POST['cpf'];
    if (validar::Cpf($cpf)) {

        $pessoa = pessoa::get($cpf, 'cpf');
        if (empty($pessoa)) {
            ?>
            <form action="<?php echo HOME_URI ?>/gt/cadfun" target="_parent" method="POST" >
                <div id="search" class="row" >
                    <div class="col-lg-5">
                        <?php echo formulario::input('1[n_pessoa]', 'Nome', NULL, @$dados['n_pessoa'], 'required')
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php echo formulario::checkbox('prof', 'Professor', 'Professor', @$dados['Professor'])
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?php echo formulario::input('1[n_social]', 'Nome Social', NULL, @$dados['n_social'])
                        ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-lg-3">
                        <?php formulario::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$dados['sexo'])
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php echo formulario::input('1[dt_nasc]', 'D.Nasc.', NULL, data::converteBr(@$dados['dt_nasc']), formulario::dataConf(), null, 'date')
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php echo formulario::input('1[cpf]', 'CPF', NULL, @$dados['cpf'], ' readonly')
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php formulario::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', empty(@$dados['ativo']) ? 1 : @$dados['ativo'])
                        ?>
                    </div>
                </div>
                <br />
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-12 art-button">
                        <span style="color: white; width: 95%; margin: 0 auto" aria-hidden="true">
                            Telefones
                        </span>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-lg-4">
                        <?php echo formulario::input('1[tel1]', 'Telefone 1', NULL, @$dados['tel1']) ?>
                    </div>
                    <div class="col-lg-4">
                        <?php echo formulario::input('1[tel2]', 'Telefone 2', NULL, @$dados['tel2']) ?>
                    </div>
                    <div class="col-lg-4">
                        <?php echo formulario::input('1[tel3]', 'Telefone 3', NULL, @$dados['tel3']) ?>
                    </div>
                    <div class="col-lg-12" style="text-align: center">
                        <input type="hidden" name="1[id_pessoa]" value="<?php echo @$dados['id_pessoa'] ?>" />
                        <?php
                        echo DB::hiddenKey('novaPessoa');
                        ?>
                        <br />
                        <button name="salvarPessoa" value="1"  type="submit"class="btn btn-success">
                            Salvar
                        </button>
                    </div>
                </div>
            </form>
            <?php
        } else {
            $f = sql::get('ge_funcionario', 'fk_id_pessoa', ['fk_id_pessoa' => $pessoa['id_pessoa'], 'fk_id_inst'=>$id_inst], 'fetch');
            if (!empty($f)) {
                ?>
                <div class="alert alert-error text-center">
                    <?php echo $pessoa['n_pessoa'] ?> já está cadastrado
                </div>
                <?php
            } else {
                ?>
                <div class="row fieldBorder2">
                    <div class="col-sm-6">
                        Nome: <?php echo $pessoa['n_pessoa'] ?>
                    </div>
                    <div class="col-sm-3">
                        CPF: <?php echo $pessoa['cpf'] ?>
                    </div>
                    <div class="col-sm-3">
                        D. Nascimento: <?php echo data::converteBr($pessoa['dt_nasc']) ?>
                    </div>
                </div>
                <br /><br />
                <form action="<?php echo HOME_URI ?>/gt/cadfun" target="_parent" method="POST" >
                    <div style="text-align: center">
                        Professor?
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-center">
                            <label>
                                <input required type="radio" name="1[funcao]" value="Professor" /> Sim
                            </label>
                        </div>
                        <div class="col-sm-6 text-center">
                            <label>
                                <input required type="radio" name="1[funcao]" value="Outro" /> Não
                            </label>
                        </div>
                    </div>
                    <br /><br />
                    <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo $pessoa['id_pessoa'] ?>" />
                    <input type="hidden" name="1[fk_id_inst]" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="1[rm]" value="<?php echo uniqid() ?>" />
                    <input type="hidden" name="1[situacao]" value="Ativo" />
                    <?php echo DB::hiddenKey('ge_funcionario', 'replace') ?>
                    <div style="text-align: center">
                        <input class="btn btn-success" type="submit" value="Incluir" />
                    </div>
                </form>
                <?php
            }
        }
    } else {
        ?>
        <div class="row">
            <form method="POST">
                <div class="col-sm-8">
                    <?php echo formulario::input('cpf', 'CPF', NULL, @$dados['cpf'], ' onblur="validarCPF(this.value);" maxlength="14" onkeypress="return SomenteNumero(event)"') ?>
                </div>
                <div class="col-sm-2">
                    <input class="btn btn-success" type="submit" <?php echo javaScript::cfpInput() ?> value="Continuar" />
                </div>
            </form>  


        </div>
        <?php
    }
    ?>
    <br /><br />
    <div style="text-align: right">
        <form method="POST">
            <input class="btn btn-warning" type="submit" value="Limpar" />
        </form>
    </div>
</div>