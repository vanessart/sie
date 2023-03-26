<?php
if (!defined('ABSPATH'))
    exit;
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_ch)) {
    $ch = sql::get(['lab_chrome', 'lab_chrome_modelo'], '*', ['id_ch' => $id_ch], 'fetch');
}
if (!empty($ch)) {
    ?>
    <div class="body">
        <div class="fieldTop">
            Ocorrência
        </div>
        <br /><br />
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Número de Série
                </td>
                <td>
                    <?= $ch['serial'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Modelo 
                </td>
                <td>
                    <?= $ch['n_cm'] ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <form action="<?= HOME_URI ?>/lab/chromeProf" target="_parent" method="POST">
            <div style="text-align: center">
                <?= formErp::selectDB('lab_chrome_critica_tp', '1[fk_id_tp]', 'Assunto') ?>
            </div>
            <br /><br />
            <?= formErp::textarea('1[obs]', null, 'Relate aqui a ocorrência') ?>
            <br /><br />
            <div style="text-align: center">
                <?=
                formErp::hiddenToken('lab_chrome_critica', 'ireplace')
                . formErp::hidden([
                    '1[serial]' => $ch['serial'],
                    '1[fk_id_pessoa]' => $ch['fk_id_pessoa'],
                    '1[email_google]' => $ch['email_google'],
                ])
                . formErp::button('Enviar')
                ?>

            </div>
        </form>
    </div>
    <?php
}
