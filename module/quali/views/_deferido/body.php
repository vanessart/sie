<?php
if (!defined('ABSPATH'))
    exit;
$setup = sql::get('curso_setup', ' `url`,`background`,`color`,`button`, img, tel, whatsApp ', ['id_setup' => tool::id_inst()], 'fetch');

$aprovados = $model->aprovados($form);
if (empty($formVersao)) {
    $formVersao = sql::get('quali_inscr', '*', ['id_inscr' => $form], 'fetch');
}
?>
<style>
    .whats{
        width: 400px;
        margin: 0 auto;
        text-align: center;
    }
</style>
<div style="text-align: center; font-weight: bold; font-size: 20px; padding: 15px">
    <p>
        <?= $formVersao['n_inscr'] ?>
        EAD (on-line) Lista de Aprovados
    </p>
</div>
<div style="text-align: center; font-weight: bold; font-size: 16px; padding: 15px">
    Caso seu nome esteja na lista e você não tenha recebido o token/acesso em seu email,
    por favor, entrar em contato através do WhatsApp abaixo:
</div>
<?php
if (!empty($web)) {
    if (tool::mobile()) {
        ?>
        <div class="whats">
            <a target="_blank" href="https://api.whatsapp.com/send?1=pt_BR&phone=55011942960853">
                <button class="btn btn-success" style="width: 80%">
                    <img style="width: 60px" src="<?= HOME_URI ?>/includes/images/whatsappBranco.png"> 
                    <br /><br />
                    <p style="font-size: 25px; color: white">
                        WhatsApp
                    </p>
                    <p style="font-size: 20px; color: white">
                        (<?= substr($setup['whatsApp'], 0, 2) ?>)  <?= substr($setup['whatsApp'], 2, 5) ?> - <?= substr($setup['whatsApp'], 7) ?>
                    </p>
                </button>
            </a>
        </div>
        <?php
    } else {
        ?>
        <div class="whats">
            <a target="_blank" href="https://web.whatsapp.com/send?1=pt_BR&phone=55011942960853">
                <button class="btn btn-success" style="width: 80%">
                    <img style="width: 60px" src="<?= HOME_URI ?>/includes/images/whatsappBranco.png"> 
                    <br /><br />
                    <p style="font-size: 25px; color: white">
                        WhatsApp
                    </p>
                    <p style="font-size: 20px; color: white">
                        (<?= substr($setup['whatsApp'], 0, 2) ?>)  <?= substr($setup['whatsApp'], 2, 5) ?> - <?= substr($setup['whatsApp'], 7) ?>
                    </p>
                </button>
            </a>
        </div>
        <?php
    }
} else {
    ?>
    <div style="text-align: center">
        <img src="<?= HOME_URI ?>/includes/images/quali/whats.png">
    </div>
    <?php
}
?>
<br /><br />
<div style="max-width: 1000px; margin: 0 auto">
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr style="background-color: #0044cc; color: white">
            <td>
                Nome
            </td>
            <td>
                Curso
            </td>
        </tr>
        <?php
        foreach ($aprovados as $v) {
            ?>
            <tr>
                <td>
                    <?= strtoupper($v['nome']) ?>
                </td>
                <td>
                    <?= $v['n_cur'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
