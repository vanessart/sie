<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="border" style="font-size: 2em; text-align: center">
    <p>
        Guarde a sua Senha para que você possa acessar seu cadastro novamente.
    </p>
    <div style="text-align: center; font-size: 2.2em; color: blue">
        Senha: <?= $pin ?>
    </div>
    <form method="POST">
        <div style="padding: 20px">
            <?=
            formErp::hidden([
                'cpf' => $cpf,
                'pin' => $pin,
                'id_cate' => $id_cate
            ])
            . formErp::button('Continuar')
            ?>
        </div>
    </form>
</div>
