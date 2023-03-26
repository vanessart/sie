<?php
@$nome = trim($_POST['nome']);
@$mae = $_POST['mae'];
?>
<div class="fieldBody">

    <form method="POST">
        <div class="row">
            <div class="col-md-12 text-center" style="font-size: 20px;">
                Para cadastrar um novo aluno, digite o RSE ou nome e verifique se ele já está cadastrado no sistema.
            </div>   
        </div>
        <br />
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
            <form action="<?php echo HOME_URI ?>/gt/aluno" method="POST">
                <input type="hidden" name="aba" value="geral" />
                <input type="hidden" name="nome" value="<?php echo @$nome ?>" />
                <button class="btn btn-primary" style="font-size: 20px">
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
                if (!empty($v['cursoAtivo'])) {
                    foreach ($v['cursoAtivo'] as $ati) {
                        @$alunos[$k]['escola'] .= $ati['n_curso'] . ' na ' . $ati['n_inst'] . '<br />';
                    }
                }
                if (!empty($v['cursoInativo'])) {
                    foreach ($v['cursoInativo'] as $ati) {
                        @$alunos[$k]['escola'] .= $ati['n_curso'] . ' na ' . $ati['n_inst'] . '<br />';
                    }
                }

                $alunos[$k]['acc'] = formulario::submit('Acessar', NULL, ['aba' => 'geral', 'id_pessoa' => $v['id_pessoa']], HOME_URI . '/gt/aluno');
            }

            $form['array'] = $alunos;
            $form['fields'] = [
                'Aluno' => 'n_pessoa',
                'Dt. Nasc.' => 'dt_nasc',
                'Mãe' => 'mae',
                'RSE' => 'id_pessoa',
                'Frequente' => 'escola',
                '||' => 'acc'
            ];
            tool::relatSimples($form);
        } else {
            ?>
            <form action="<?php echo HOME_URI ?>/gt/aluno" name="geral" method="POST">
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
