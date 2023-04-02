<div style="padding: 10px; width: 95%; margin: 0 auto">

    <?php
    $id_inst = tool::id_inst(filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT));
    javaScript::cpf();
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_UNSAFE_RAW);
    if (validar::Cpf($cpf)) {

        $pessoa = sql::get('pessoa', '*', ['cpf' => $cpf], 'fetch');

        if (empty($pessoa)) {
            ?>
            <form action="<?php echo HOME_URI ?>/sed/cadFunc" target="_parent" method="POST" >
                <div id="search" class="row" >
                    <div class="col-8">
                        <?= formErp::input('1[n_pessoa]', 'Nome', null, 'required')
                        ?>
                    </div>
                    <div class="col-4">
                        <?= formErp::input('1[n_social]', 'Nome Social')
                        ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo') ?>
                    </div>
                    <div class="col">
                        <?= formErp::input('1[dt_nasc]', 'D.Nasc.', null, null, null, 'date') ?>
                    </div>
                    <div class="col">
                        <?= formErp::input(null, 'CPF', $cpf, ' readonly')
                        ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?php formErp::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo') ?>
                    </div>
                    <div class="col">
                        <?php echo formErp::checkbox('prof', 'Professor', 'Professor') ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[emailgoogle]', 'E-mail', null, null, null, 'email') ?>
                    </div>
                </div>
                <br />
                <div class="col-lg-12" style="text-align: center">
                    <input type="hidden" name="1[id_pessoa]" value="<?php echo @$dados['id_pessoa'] ?>" />
                    <?php
                    echo formErp::hiddenToken('novaPessoa');
                    echo formErp::hidden(['cpf' => $cpf]);
                    echo formErp::hidden(['1[cpf]' => $cpf]);
                    ?>
                    <br />
                    <button  type="submit"class="btn btn-success">
                        Salvar
                    </button>
                </div>
            </form>
            <?php
        } else {
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
        <form method="POST">
            <div class="row">
                <div class="col-9">
                    <?php echo formErp::input('cpf', 'CPF (Só números)', $cpf, ' onblur="validarCPF(this.value);" maxlength="14" onkeypress="return SomenteNumero(event)"') ?>
                </div>
                <div class="col-3">
                    <input class="btn btn-success" type="submit" <?php echo javaScript::cfpInput() ?> value="Continuar" />
                </div>
            </div>
        </form>  
        <?php
    }
    ?>
</div>