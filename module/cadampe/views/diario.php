<?php
if (!defined('ABSPATH'))
    exit;
?>

<style>
    .wpp {
        padding: 10px !important;
        font-weight: bold;
    }
</style>

<div class="body">
    <?php if (!empty(@$tel1) || !empty(@$tel2) || !empty(@$tel3)) {
    ?>

        <form id='whats' target="_parent" action="<?= HOME_URI ?>/cadampe/solicitarList" method="POST">
            <div class="row">
                <div class="col-md-12 wpp">
                    Escolha um número para enviar o link do Diário de Classe
                </div>
            </div>
            <div class="row">

                <div class="col-md-3">
                    <?= formErp::radio('1[tel]', @$tel1, @$tel1); ?>
                </div>

                <div class="col-md-3">
                    <?= formErp::radio('1[tel]', @$tel2, @$tel2); ?>
                </div>

                <div class="col-md-3">
                    <?= formErp::radio('1[tel]', @$tel3, @$tel3); ?>
                </div>

                <div class="col-md-3">
                    <?= formErp::button('ENVIAR LINK', null, 'whats()', 'btn btn-warning'); ?>
                </div>

            </div>

        </form>
    <?php } ?>

    <div class="row mt-3">
        <div class="col-6">
            <input type="text" name="numeroWpp" id="numWpp" placeholder="EX: (xx) xxxxx-xxxx" class="form-control">
        </div>
        <div class="col-6">
            <?= formErp::button('Enviar Link', null, 'enviar()', 'btn btn-success') ?>
        </div>
    </div>
</div>


<script>
    function enviar(num) {
        num = document.getElementById('numWpp')
        if (num.value) {
            var win = window.open("https://web.whatsapp.com/send?1=pt_BR&phone=55" + num.value + "&text=<?= urlencode($texto) ?>", 'novo');
            win.focus();
        } else {
            alert("Inclua um número")
        }

    }
</script>