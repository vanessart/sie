<?php
if (!defined('ABSPATH'))
    exit;
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT);
$can = sql::get('inscr_incritos_' . $model->evento, 'nome, email, pin', ['id_cpf' => $cpf], 'fetch');
if (!empty($can['email'])) {
    $teste = mailer::enviaEmailCadampe($can['nome'], $can['email'], $can['pin']);
    $emailArr = explode('@', $can['email']);
} else {
    $SemEmail = 1;
}
?>
<div class="body">
    <?php
    if (!empty($SemEmail)) {
        ?>
        <div class="alert alert-danger">
            Não há e-mail cadastrado para o envio da SENHA;
        </div>
        <?php
    } elseif ($teste) {
        $asterisco = "";
        foreach (range(0, (strlen($emailArr[0]) - 1)) as $v) {
            $asterisco .= '*';
        }
        ?>
        <div class="alert alert-success">
            <p>
                Sua senha foi enviada para o e-mail "<?= (substr($emailArr[0], 0, 1) . $asterisco . '@' . $emailArr[1]) ?>"
            </p>
            <p>
                Verifique suas pastas de spam ou de lixo eletrônico. É possível que o e-mail que tentamos enviar a você tenha sido bloqueado por um filtro de spam ou de lixo eletrônico do seu sistema de e-mail
            </p>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger">
            O e-mail cadastrado não possibilita o envio da SENHA;
        </div>
        <?php
    }
    ?>
</div>
