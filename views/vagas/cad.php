<?php
@$nome = trim($_POST['nome']);
@$mae = $_POST['mae'];

?>
<div class="fieldBody">
    <div class="fieldTop text-center" style="font-size: 20px; color: green">
        Para inscrever um novo aluno, digite o nome e verifique se ele já está cadastrado no sistema.
    </div>  
    <br /><br /><br /><br />
    <div style="text-align: center">
        <form method="POST">
            <div class="row" >
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <?php echo formulario::input('nome', 'RSE ou Nome do Aluno', NULL, NULL, 'required')
                    ?>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="aba" value="cad" />
                    <button class="btn btn-success">
                        Pesquisar
                    </button>
                </div>
            </div>
        </form>
        <br /><br />
        <?php
        if (!empty($nome)) {
            $nome = trim($nome);
            $n = explode(' ', $nome);
            if (count($n) > 1) {
                $sql = "SELECT * FROM `vagas` "
                        . " WHERE `n_aluno` LIKE '" . current($n) . "%' "
                        . " and  `n_aluno` LIKE '%" . end($n) . "%' "
                        . " ORDER BY `n_aluno` ASC ";
                $query = $model->db->query($sql);
                $form['array'] = $query->fetchAll();
                $form['fields'] = [
                    'Criança' => 'n_aluno',
                    'D.Nasc.' => 'dt_aluno',
                    'Mãe' => 'mae',
                    'Certidão' => 'cn_matricula'
                ];
                $form['titulo']="Crianças com nome parecido já inscritos";
                tool::relatSimples($form);
            }
            ?>
            <br />
            <div class="text-center">
                <form action="<?php echo HOME_URI ?>/vagas/cada" method="POST">
                    <input type="hidden" name="aba" value="0" />
                    <input type="hidden" name="nome" value="<?php echo @$nome ?>" />
                    <button class="btn btn-danger" style="font-size: 20px">
                        Inscrever um novo(a) aluno(a)
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
                    <div class="alert alert-danger text-center" style="font-size: 20px;">
                        Não houve resultado na sua busca. Seria um dos nomes abaixo?
                    </div>
                    <?php
                }
                ?>


                <?php
                if (is_array($alunos)) {
                    foreach ($alunos as $k => $v) {

                        if (!in_array(infantil::setSerie($v['dt_nasc'])[0], [21, 22, 23, 24])) {
                            $alunos[$k]['acc'] = '<div style="color: red; font-weight: bolder; font-size: 18px;white-space: nowrap;padding: 5px">Fora da Idade</div>';
                        } elseif (@$v['situacao'] == 'Frequente') {
                            $alunos[$k]['acc'] = '<div style="color: blue; font-weight: bolder; font-size: 18px;white-space: nowrap;padding: 5px">Frequente</div>';
                        } else {
                            $alunos[$k]['acc'] = formulario::submit('Acessar', NULL, ['aba' => '0', 'id_pessoa' => $v['id_pessoa']], HOME_URI . '/vagas/cada');
                        }
                        $alunos[$k]['situacao'] = infantil::setSerie($v['dt_nasc'])[0];
                    }

                    $form['array'] = $alunos;
                    $form['fields'] = [
                        'Aluno' => 'n_pessoa',
                        'Dt. Nasc.' => 'dt_nasc',
                        'Mãe' => 'mae',
                        'Escola' => 'n_inst',
                        'RSE' => 'id_pessoa',
                        'Classe' => 'codigo_classe',
                        'Situação' => 'situacao',
                        '||' => 'acc'
                    ];
                    tool::relatSimples($form);
                }
            }
        }
        ?>
    </div>
</div>