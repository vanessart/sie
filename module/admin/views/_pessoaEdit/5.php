<?php
if (!defined('ABSPATH'))
    exit;
$user = sqlErp::get('users', '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
if ($user) {
    $permissao = user::permissao($id_pessoa);
    if ($permissao) {
        $token = formErp::token('acesso_pessoa', 'delete');
        foreach ($permissao as $k => $v) {
            $permissao[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_ac]' => $v['id_ac'], 'activeNav' => 5, 'id_pessoa' => $id_pessoa]);
        }
        $form['array'] = $permissao;
        $form['fields'] = [
            'Grupo' => 'n_gr',
            'Instância' => 'n_inst',
            '||1' => 'del'
        ];
    }
}
?>
<div class="body">
    <div class="border" style="padding-top: 20px; padding-left: 20px">
        <div class="row">
            <div class="col-3 text-center">
                <form method="POST">
                    <?php
                    if (@$user['ativo'] != 1) {
                        $button = 'Ativar Usuário';
                        $ativo = 1;
                        $btn = 'success';
                    } else {
                        $button = 'Desativar Usuário';
                        $ativo = 0;
                        $btn = 'danger';
                    }
                    echo formErp::hidden([
                        '1[id_user]' => @$user['id_user'],
                        '1[fk_id_pessoa]' => $id_pessoa,
                        'id_pessoa' => $id_pessoa,
                        '1[ativo]' => $ativo,
                        'activeNav' => 5
                    ]);
                    echo formErp::hiddenToken('users', 'ireplace');
                    ?>
                    <button class="btn btn-<?= $btn ?>">
                        <?= $button ?>
                    </button>
                </form>
            </div>
            <div class="col-9">
                <form method="POST">
                    <?=
                    formErp::hidden([
                        '1[id_user]' => @$user['id_user'],
                        '1[fk_id_pessoa]' => $id_pessoa,
                        'id_pessoa' => $id_pessoa,
                        '1[ativo]' => 1,
                        'activeNav' => 5
                    ])
                    . formErp::hiddenToken('users', 'ireplace');
                    ?>
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[user_password]', (!empty($user['user_password']) ? 'Redefinir a Senha:' : 'Definir a Senha:'), null, ' required id="ps"') ?>
                        </div>
                        <div class="col">
                            <?= formErp::button(!empty($user['user_password']) ? 'Redefinir Senha' : 'Definir Senha e Ativar Usuário') ?>
                        </div>
                        <div class="col text-center">
                            <button type="button" class="btn btn-warning" onclick="document.getElementById('ps').value = '<?php echo user::gerarSenha() ?>'">
                                Gerar Senha aleatória
                            </button>
                        </div>
                    </div>
                    <br />
                </form>
            </div>
        </div>
        <br />
    </div>
    <br /><br />
    <?php
    if ($user) {
        if (toolErp::id_pessoa() == 1) {
            $grh = ['>' => 'n_gr'];
        } else {
            $grh = ['>' => 'n_gr', 'at_gr' => 1];
        }
        ?>
        <div class="row">
            <div class="col-4">
                <form method="POST">
                    <?= formErp::selectDB('grupo', '1[fk_id_gr]', 'Grupo', null, null, null, null, $grh, ' required ') ?>
                    <br />
                    <?= formErp::selectDB('instancia', '1[fk_id_inst]', 'Instâcia', null, null, null, null, null, ' required ') ?>
                    <br />
                    <?=
                    formErp::hidden([
                        '1[fk_id_pessoa]' => $id_pessoa,
                        'id_pessoa' => $id_pessoa,
                        'activeNav' => 5
                    ])
                    . formErp::hiddenToken('acesso_pessoa', 'ireplace')
                    . formErp::button('Adicionar')
                    ?> 
                </form>
            </div>
            <div class="col-8">
                <?php
                if (!empty($form)) {
                    echo report::simple($form);
                }
                ?>
            </div>
        </div>
        <br />
    </div>
    <?php
}