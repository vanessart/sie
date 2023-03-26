<?php
if (!defined('ABSPATH'))
    exit;
?>
<br />
<div class="border" style="width: 500px; margin: auto; padding: 20px">
    <form method="POST">
        <?php
        if (!empty($erroPin)) {
            ?>
            <div class="alert alert-danger text-center">
                Senha inválido
            </div>
            <br /><br />
            <?php
        }
        ?>
        <div class="alert alert-primary">
            CPF: <?= $cpf ?>
        </div>
        <?= formErp::input('pin', 'Digite sua Senha', null, ' required ') ?>
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                'id_cate' => $id_cate,
                'cpf' => $cpf
            ])
            . formErp::button('Continuar')
            ?>
        </div>
    </form>
    <div style="text-align: center">
        <form action="<?= HOME_URI ?>/inscr/recuperaSenha" target="frame" method="POST">
            <?=
            formErp::hidden([
                'id_cate' => $id_cate,
                'cpf' => $cpf
            ])
            ?>
            <input class="btn btn-link" type="submit" onclick=" $('#myModal').modal('show');$('.form-class').val('')" value="Esqueceu a Senha? Clique aqui" />
        </form>
    </div>
</div>
<?php
toolErp::modalInicio();
?>
<iframe style="border: none; width: 100%; height: 80vh" name="frame"></iframe>
<?php
toolErp::modalFim();
?>