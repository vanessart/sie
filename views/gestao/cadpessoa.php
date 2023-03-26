<?php
javaScript::cpf();
javaScript::somenteNumero();
if (empty($_POST['cadp'])) {
?>
<br /><br /><br /><br />
<form method="POST" style="text-align: center">
    <input class="btn btn-success" name="cadp" type="submit" value="Cadastrar Pessoa" />
</form>
<?php
}else{
    ?>
    <form method="POST">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Identificação
            </div>
            <div class="panel panel-body">
                <div class="row" >
                   
                    <div class="col-lg-12">
                        <?php echo formulario::input('a[n_pessoa]', 'Nome', NULL, empty(@$dados['n_pessoa']) ? @$_POST['nome'] : @$dados['n_pessoa'], 'required')
                        ?>
                    </div>
                </div>
                <br />
                <div class="row" >

                    <div class="col-lg-12">
                        <?php
                        echo formulario::input('a[email]', 'E-mail', NULL, @$dados['email']);
                        formulario::hidden(['a[ativo]' => 1])
                        ?>
                    </div>           
                </div>  
                <br />
                <div class="row">
                    <div class="col-lg-2">
                        <?php formulario::select('a[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$dados['sexo'], NULL, NULL, 'required')
                        ?>
                    </div>

                    <div class="col-lg-3">
                        <?php echo formulario::input('a[dt_nasc]', 'D.Nasc.', NULL, data::converteBr(@$dados['dt_nasc']), formulario::dataConf(2) . ' required')
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?php echo formulario::input('a[cpf]', 'CPF', NULL, @$dados['cpf'], ' onblur="validarCPF(this.value);" maxlength="14" onkeypress="return SomenteNumero(event)"')
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-12 offset4" style="text-align: center">
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
    <div class="col-lg-2">

    </div>
    <?php
}
?>
