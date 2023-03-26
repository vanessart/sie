
<div class="fieldBody">
    <div class="row">
        <form method="POST">
            <div class="col-md-8">
                <?php formulario::input('nome', 'Aluno') ?>
            </div>
            <div class="col-md-2">
                <input class="btn btn-success" type="submit" value="Buscar" />
            </div>
        </form>
        <form method="POST">
            <div class="col-md-2">
                <input class="btn btn-warning" type="submit" value="Limpar" />
            </div>
        </form>
    </div>
    <br /><br />
    <?php
    if (!empty($_POST['nome'])) {
        $alunos = alunos::busca($_POST['nome']);
        foreach ($alunos as $k => $v) {
            $alunos[$k]['acc'] = formulario::submit('Acessar', NULL, ['aba' => 'geral', 'id_pessoa' => $v['id_pessoa']], HOME_URI . '/gestao/cadaluno');
        }
        $form['array'] = $alunos;
        $form['fields'] = [
            'Nome' => 'n_pessoa',
            'RSE' => 'id_pessoa',
            'Mãe' => 'mae',
            'Escola' => 'n_inst',
            'Situação' => 'situacao',
            'Classe' => 'codigo_classe',
            '||' => 'acc'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>