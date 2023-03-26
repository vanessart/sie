<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="border" style="width: 400px; padding: 20px; margin: auto; margin-top: 100px">
    <form action=" " method="POST">
        <p>
            <?= formErp::input('cpf', 'CPF', null, 'id="cpfOring" required') ?>
        </p>
        <p>
            <?= formErp::input('pin', 'Senha', null, 'required', null, 'password') ?>
        </p>
        <div style="padding: 30px; text-align: center">
            <button class="btn btn-primary">
                Enviar
            </button>
            <br /><br /><br />
            <input class="btn btn-link" type="button" onclick="senha()" value="Esqueceu a Senha? Clique aqui" />
        </div>
    </form>
</div>
<form action="<?= HOME_URI ?>/inscr/recuperaSenha" id="senhaForm" target="frame" method="POST">
    <input type="hidden" name="cpf" id="cpf" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="border: none; width: 100%; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function  senha() {
        cpf.value = cpfOring.value;
        senhaForm.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>