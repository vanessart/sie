<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Controle de Chromebook
    </div>
    <div class="alert alert-info">
        <?= toolErp::bom() ?> <?= toolErp::n_pessoa() ?>
        <br /><br />
        <p>
            Esta área é destinada ao registro do dispositivo Chromebook, que será feito através do número de série.
        </p>
        <br />
        <p style="font-weight: bold">
            Como encontrar o Número de Série: 
        </p>
        <br />
        <p>
            Na parte de baixo do Chromebook você pode encontrar o número de série num autocolante que está indicado com as iniciais S/N.
        </p>
        <br />
                <img style="height: 116px" src="<?= HOME_URI ?>/includes/images/chrome.png"/>
                <br /><br />
                <p>
                    Ou ligue o Chromebook, antes de realizar o login (na área de Login) utilize o atalho, apertando a tecla “alt + v”, que o número de série aparecerá no campo superior direito da tela. 
                </p>
                <br />
                                <img style="height: 116px" src="<?= HOME_URI ?>/includes/images/atalho.jpg"/>
    </div>
</div>
