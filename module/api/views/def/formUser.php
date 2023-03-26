<?php
if (!defined('ABSPATH'))
    exit;
$id_user = filter_input(INPUT_POST, 'id_user', FILTER_SANITIZE_NUMBER_INT);
$cpfNome = filter_input(INPUT_POST, 'cpfNome', FILTER_SANITIZE_STRING);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
?>
<div class="body">
    <?php
    if ($id_pessoa) {
        $apaga = null;
        $metodos = get_class_methods($this);
        unset($metodos[0]);
        foreach ($metodos as $k => $v) {
            if ($v == '__construct') {
                break;
            }
            $metodosApi[$v] = $v;
        }
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    ID
                </td>
                <td>
                    Nome
                </td>
                <td>
                    CPF
                </td>
            </tr>
            <tr>
                <td>
                    <?= $id_pessoa ?>
                </td>
                <td>
                    <?= $n_pessoa ?>
                </td>
                <td>
                    <?= $cpf ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <form action="<?= HOME_URI ?>/api/pin" target="_parent" method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('1[api]', $metodosApi, 'API', null, null, null, ' required ') ?>
                </div>
            </div>
            <br />
            <div style="text-align: center; padding: 30px">
                <?=
                formErp::hidden(['1[fk_id_pessoa]' => $id_pessoa])
                . formErp::hiddenToken('api_user', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </form>
        <?php
    } elseif ($cpfNome) {
        $sql = "SELECT n_pessoa, cpf, id_pessoa FROM `pessoa` p "
                . "join users u on p.id_pessoa = u.fk_id_pessoa "
                . " WHERE (`n_pessoa` LIKE '%$cpfNome%' OR `cpf` LIKE '$cpfNome') ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $k => $v) {
                $array[$k]['ac'] = formErp::submit('Selecionar', null, $v);
            }
            $form['array'] = $array;
            $form['fields'] = [
                'ID' => 'id_pessoa',
                'Nome' => 'n_pessoa',
                'CPF' => 'cpf',
                '||1' => 'ac'
            ];
            report::simple($form);
        }
    } else {
        ?>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::input('cpfNome', 'CPF ou Nome') ?>
                </div>
            </div>
            <br />
            <div style="text-align: center; padding: 30px">
                <?= formErp::button('continuar') ?>
            </div>
        </form>
        <?php
    }
    ?>
</div>
