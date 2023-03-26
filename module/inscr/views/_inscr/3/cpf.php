<?php
if (!defined('ABSPATH'))
    exit;
?>
<form method="POST">
    <br />
    <div class="border" style="width: 500px; margin: auto; padding: 20px">
        <?php
        if (!empty($cpf_)) {
            ?>
            <div class="alert alert-danger text-center">
                CPF inválido
            </div>
            <br /><br />
            <?php
        }
        ?>
        <?= formErp::input('cpf', 'Digite seu CPF', null, ' required ') ?>
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden(['id_cate' => $id_cate])
            . formErp::button('Continuar')
            ?>
        </div>
    </div>
</form>
