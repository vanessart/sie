<?php
javaScript::cpf();
javaScript::somenteNumero();
if (empty($_POST['novo'])) {

    if (!empty($_POST['pessoa'])) {
        $pessoa = @$_POST['pessoa'];
    } else {
        @$cpff = sql::get('pessoa', 'cpf', ['cpf' => $_POST[1]['cpf']], 'fetch')['cpf'];
        @$pessoa = @$cpff;
    }
} else {

    if (!empty($_POST[1]['cpf'])) {
        $pessoa = @$_POST[1]['cpf'];
    } elseif (!empty($_POST[1]['email'])) {
        $pessoa = @$_POST[1]['email'];
    } elseif (!empty($_POST[1]['id_pessoa'])) {
        $pessoa = @$_POST[1]['id_pessoa'];
    }
}
if (!empty($_POST['id_pessoa']) || !empty($_POST[1]['id_pessoa']) || !empty($_POST['id'])) {
    @$id = !empty($_POST['id']) ? $_POST['id'] : (!empty($_POST['id_pessoa']) ? $_POST['id_pessoa'] : $_POST[1]['id_pessoa']);
    $dados = pessoa::get($id);
} else {
    @$dados = $_POST[1];
}
?>
<br />  
<div class="field">
    <div class="fieldTop">
        Cadastro de Pessoas
    </div>
    <div class="row">
        <form method="POST">
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-addon">
                        Nome, CPF ou E-mail
                    </span> 
                    <input class="form-control" type="text" name="pessoa" value="<?php echo @$pessoa ?>"  >
                    <span class="input-group-addon"  >
                        <button type="submit" class="btn btn-link btn-xs">
                            <span aria-hidden="true">
                                Buscar
                            </span>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </form>
        <div class="col-lg-2">
            <input class="btn btn-success" type="submit" onclick=" $('#myModal').modal('show');" value="Novo Cadastro" />
        </div>
        <div class="col-lg-3">
            <form method="POST">
                <button type="submit" class="btn btn-default">
                    Limpar
                </button>
            </form>
        </div>
    </div>
    <?php
    tool::modalInicio('width: 95%', (empty($_POST['novo']) ? 1 : NULL));
    @$dados = $_POST;
    ?>
    <br /><br />
    <form method="POST" >
        <div id="search" class="row" >
            <div class="col-lg-8">
                <?php echo formulario::input('1[n_pessoa]', 'Nome', NULL, @$dados['n_pessoa'], 'required')
                ?>
            </div>
            <div class="col-lg-4">
                <?php echo formulario::input('1[n_social]', 'Nome Social', NULL, @$dados['n_social'])
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-2">
                <?php formulario::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$dados['sexo'])
                ?>
            </div>
            <div class="col-lg-3">
                <?php echo formulario::input('1[dt_nasc]', 'D.Nasc.', NULL, data::converteBr(@$dados['dt_nasc']), formulario::dataConf())
                ?>
            </div>
            <div class="col-lg-2">
                <?php echo formulario::input('1[cpf]', 'CPF', NULL, @$dados['cpf'], ' required ')
                ?>
            </div>
            <div class="col-lg-3">
                <?php echo formulario::input('1[emailgoogle]', 'E-mail', NULL, @$dados['emailgoogle'])
                ?>
            </div>
            <div class="col-lg-2">
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
                <button  type="submit"class="btn btn-success">
                    Salvar
                </button>
            </div>
        </div>
    </form>
    <?php
    tool::modalFim();
    ?>
</div>
<br />
<?php
if (!empty($pessoa)) {
    pessoa::relat($pessoa);
}
?>