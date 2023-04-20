<?php
if (!defined('ABSPATH'))
    exit;
$pesq = filter_input(INPUT_POST, 'pesq');
$func = filter_input(INPUT_POST, 'func', FILTER_SANITIZE_NUMBER_INT);
$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_NUMBER_INT);
if ($pesq) {
    $pessoas = $model->pesqPessoas($pesq, $func, $user);
}
?>
<div class="body">
    <div class="fieldTop">
        Gestão de  Pessoas
    </div>
    <br /><br />
    <form method="POST">
        <div class="border">
            <div class="row">
                <div class="col">
                    <?= formErp::input('pesq', 'CPF, Nome, Matrícula, Email ou Id', $pesq, null, 'Obs: Pesquisa limitada a 100 resultados') ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-5" style="padding-left: 15px">
                    <button type="button" class="btn btn-warning" onclick="novo.submit()">
                        Novo Cadastro
                    </button>
                </div>
                <div class="col-2">
                    <?= formErp::checkbox('func', 1, 'Só Funcionário', $func) ?>
                </div>
                <div class="col-2">
                    <?= formErp::checkbox('user', 1, 'Só Usuários', $user) ?>
                </div>
                <div class="col-3">
                    <button class="btn btn-primary">
                        Pesquisar
                    </button>
                </div>
            </div>
            <br />
        </div>
    </form>
    <form action="<?= HOME_URI ?>/admin/pessoaEdit" id="novo"></form>
    <?php
    if (!empty($pessoas)) {
        foreach ($pessoas as $k => $v) {
            $pessoas[$k]['ac'] = formErp::submit('Acessar', null, ['id_pessoa' => $v['id_pessoa']], HOME_URI . '/admin/pessoaEdit');
            $pessoas[$k]['emailgoogle'] = !is_numeric($v['emailgoogle']) ? $v['emailgoogle'] : '';
            $pessoas[$k]['rm'] = !empty($v['rm']) ? (toolErp::virgulaE(explode(',', $v['rm']))) : null;
        }
        $form['array'] = $pessoas;
        $form['fields'] = [
            'ID' => 'id_pesoa',
            'Nome' => 'n_pessoa',
            'Matrícula' => 'rm',
            'CPF' => 'cpf',
            'E-mail Google' => 'emailgoogle',
            '||1' => 'ac'
        ];
        report::simple($form);
    }
    ?>
</div>
