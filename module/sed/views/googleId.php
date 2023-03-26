<?php
if (!defined('ABSPATH'))
    exit;
$search = filter_input(INPUT_POST, 'search');
?>
<div class="body">
    <div class="fieldTop">
        Resetar Google ID
    </div>
    <form method="POST">
        <div class="row">
            <div class="col-10">
                <?= formErp::input('search', ' Nome, CPF ou E-mail ', $search) ?>
            </div>
            <div class="col-2">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br />
    </form>
    <?php
    if ($search) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.cpf, u.id_user, p.emailgoogle, google_token FROM pessoa p "
                . " JOIN users u on u.fk_id_pessoa = p.id_pessoa "
                . " where p.n_pessoa like '$search%' "
                . " OR p.cpf = '$search' "
                . " OR p.emailgoogle like '$search'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            $token = formErp::token('users', 'ireplace');
            foreach ($array as $k => $v) {
                $array[$k]['re'] = formErp::submit('Resetar', $token, ['search' => $search, '1[id_user]' => $v['id_user'], '1[google_id]' => null, '1[google_token]' => null]);
                $array[$k]['google_token'] = (!empty($v['google_token']) ? 'Ativo' : 'Resetado');
            }

            $form['array'] = $array;
            $form['fields'] = [
                'ID' => 'id_pessoa',
                'Nome' => 'n_pessoa',
                'CPF' => 'cpf',
                'E-mail' => 'emailgoogle',
                'Token' => 'google_token',
                '||1' => 're'
            ];
            report::simple($form);
        } else {
            ?>
            <div class="alert alert-danger" style="text-align: center; font-weight: bold; font-size: 1.2em">
                Usuário Não Encontrado
            </div>
            <?php
        }
    }
    ?>
</div>