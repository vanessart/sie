<div style="padding: 10px; width: 95%; margin: 0 auto">

    <?php
    $id_inst = tool::id_inst(@$_POST['$id_inst']);
    javaScript::cpf();
    $cpf = @$_POST['cpf'];
    if (empty(validar::Cpf($cpf))) {

        $pessoa = pessoa::get($cpf, 'cpf');
        if (!empty($pessoa)) {
            $f = sql::get('ge_funcionario', 'fk_id_pessoa', ['fk_id_pessoa' => $pessoa['id_pessoa'], 'fk_id_inst' => $id_inst], 'fetch');
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
                <form action="<?php echo HOME_URI ?>/gt/cadfunfund" target="_parent" method="POST" >
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
        } else {
            echo 'Funcionário não relacionado';
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