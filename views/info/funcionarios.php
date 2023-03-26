<?php
inst::autocomplete();
$nome = @$_POST['nome'];
$id_inst = @$_POST['id_inst'];
$funcao = @$_POST['funcao'];
$situacao = @$_POST['situacao'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Pesquisa de Funcionários
    </div>
    <br />
    <form method="POST">
        <div class="fieldWhite">
            <div class="row">
                <div class="col-lg-6">
                    <?php echo formulario::input('instancia', 'Instâcia (escola)', NULL, @$_POST['instancia'], 'id="n_inst" onkeypress="completeInst()" ') ?>
                    <input type="hidden" name="id_inst" id="id_inst" value="<?php echo @$id_inst ?>" />
                </div>
                <div class="col-lg-6">
                    <?php
                    $f = sql::get('ge_funcionario', 'distinct funcao', ['>' => 'funcao']);
                    ?>
                    <!--
                    <select style="width: 100%" name="funcao">
                        <option value="">Todas as Funções</option>
                        <option <?php echo $funcao == 'profess' ? 'selected' : '' ?> value="profess">Professores</option>
                        <option <?php echo $funcao == 'instrut' ? 'selected' : '' ?> value="instrut">Instrutores</option>
                        <option <?php echo $funcao == 'libra' ? 'selected' : '' ?> value="libra">Intérprete de Libra</option>
                    <?php
                    foreach ($f as $v) {
                        if ($v['funcao'] != 'Professor' && $v['funcao'] != 'Professora' && $v['funcao'] != '') {
                            ?>
                                        <option <?php echo $funcao == $v['funcao'] ? 'selected' : '' ?>><?php echo $v['funcao'] ?></option>
                            <?php
                        }
                    }
                    ?>

                    </select>
                    -->

                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?php echo formulario::input('nome', 'Nome') ?>
                </div>
                <div class="col-lg-4">
                    <?php
                    $f = sql::get('ge_funcionario', 'distinct situacao', ['>' => 'situacao']);
                    ?>
                    <select style="width: 100%" name="situacao">
                        <option value="">Todas as Situações</option>
                        <?php
                        foreach ($f as $v) {
                            if ($v['situacao'] != '') {
                                ?>
                                <option <?php echo $situacao == $v['situacao'] ? 'selected' : '' ?>><?php echo $v['situacao'] ?></option>
                                <?php
                            }
                        }
                        ?>

                    </select>
                </div>
                <div class="col-lg-1">
                    <input class="btn btn-success" name="pesq" type="submit" value="Pesquisar" />
                </div>
                <div class="col-lg-1">
                    <a class="btn btn-info" href="">
                        Limpar
                    </a>
                </div>
            </div>
        </div>
    </form>

    <br /><br />
    <?php
    if (!empty($_POST['pesq'])) {
        $func = funcionarios::rh(@$id_inst, @$funcao, @$situacao, @$nome);

        $form['array'] = $func;
        $form['fields'] = [
            '||a' => 'ct',
            'Nome' => 'n_pessoa',
            'Matrícula' => 'rm',
            'Instância' => 'n_inst',
            // 'Função' => 'funcao',
            'E-mail' => 'emailgoogle',
            'Situação' => 'situacao'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>

