<?php
@$nome = trim($_POST['nome']);
@$mae = $_POST['mae'];
?>
<div class="panel panel-default">
    <div class="panel panel-heading text-center" style="font-size: 20px; color: green">
        Para cadastrar um novo aluno, digite o RSE ou nome e verifique se ele já está cadastrado no sistema.
    </div>   
    <div class="panel panel-body">
        <form method="POST">
            <div id="search" class="row" >
                <div class="col-lg-9">
                    <?php echo formulario::input('nome', 'RSE ou Nome do Aluno', NULL, NULL, 'required')
                    ?>
                </div>
                <div class="col-lg-2">
                    <input type="hidden" name="aba" value="cad" />
                    <button class="btn btn-success">
                        Pesquisar
                    </button>
                </div>
            </div>
        </form>
        <?php
        if (!empty($nome)) {
            ?>
            <br />
            <div class="text-center">
                <form action="<?php echo HOME_URI ?>/gestao/cadaluno" method="POST">
                    <input type="hidden" name="aba" value="geral" />
                    <input type="hidden" name="nome" value="<?php echo @$nome ?>" />
                    <button class="btn btn-danger" style="font-size: 20px">
                        Cadastrar um novo(a) aluno(a)
                    </button>       
                </form>
            </div>
            <br />
            <?php
            $alunos = alunos::busca($nome, $mae);
            if (!empty($alunos)) {
                if (count($alunos) > 1) {
                    ?>
                    <br /><br />
                    <div class="alert alert-danger text-center" style="font-size: 20px">
                        Não houve resultado na sua busca. Seria um dos nomes abaixo?
                    </div>
                    <?php
                }
                ?>


                <?php
                foreach ($alunos as $k => $v) {
                    if ($v['situacao'] != 'Frequente') {
                        $alunos[$k]['acc'] = formulario::submit('Acessar', NULL, ['aba' => 'geral', 'id_pessoa' => $v['id_pessoa']], HOME_URI.'/gestao/cadaluno');
                    }
                    $alunos[$k]['situacao'] = $v['situacao'] == 'Frequente' ? '<div style="color: red; font-weight: bolder; font-size: 18px">' . $v['situacao'] . '</div>' : $v['situacao'];
                }
                
                $form['array'] = $alunos;
                $form['fields'] = [
                    'Aluno' => 'n_pessoa',
                    'Dt. Nasc.' => 'dt_nasc',
                    'Mãe' => 'mae',
                    'Escola' => 'n_inst',
                    'RSE'=> 'id_pessoa',
                    'Classe' => 'codigo_classe',
                    'Situação' => 'situacao',
                    '||' => 'acc'
                ];
                tool::relatSimples($form);
            } else {
                ?>
                <form action="<?php echo HOME_URI ?>/gestao/cadaluno" name="geral" method="POST">
                    <input type="hidden" name="nome" value="<?php echo $nome ?>" />
                    <input type="hidden" name="aba" value="geral" />
                </form>
                <script>
                   // document.geral.submit();
                </script>
                <?php
            }
        }
        ?>
    </div>
</div>
