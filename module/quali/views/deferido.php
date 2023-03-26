<?php
$form = filter_input(INPUT_POST, 'form', FILTER_SANITIZE_NUMBER_INT);
if (empty($form)) {
   $form = $formulario = $model->_formAdm;
}

$formVersao = sql::get('quali_inscr', '*', ['id_inscr' => $form], 'fetch');
$verAprovados = $formVersao['ver_aprovados'];
?>
<br />
<div class="row" style="width: 90%; margin: 0 auto">
    <div class="col-4 text-center">
        <?= form::selectDB('quali_inscr', 'form', 'Inscrição', $form, 1, null, null, ['at_inscr' => 1]) ?>
    </div>
    <?php
    if (!empty($form)) {
        ?>
        <div class="col-4 text-center">
            <form id="verResult" method="POST">
                <div class="row border4" style="padding: 15px">
                    <div class="col">
                        <?= form::select('1[ver_aprovados]', [0 => 'Não', 1 => 'Sim'], 'Publicar Aprovados', $verAprovados) ?>
                    </div>
                    <div class="col">
                        <?=
                        form::hidden(['1[id_inscr]' => $form, 'form' => $form])
                        . form::hiddenToken('quali_inscr', 'ireplace')
                        ?>
                        <button type="button" onclick="if ($('#ver_aprovados_').val() == 1) {
                                    if (confirm('Está ação irá liberar a visualização dos aprovados. Enviar?')) {
                                        $('#verResult').submit()
                                    }
                                } else
                                    ($('#verResult').submit())" class="btn btn-success">
                            Enviar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-4 text-right">
            <form target="_blank" action="<?= HOME_URI ?>/quali/pdf/aprovados.php" method="POST">
                <?= form::hidden(['id_inscr' => $form]) ?>
                <button type="submit" class="btn btn-warning">
                    Gerar PDF
                </button>
            </form>
        </div>
        <?php
    }
    ?>
</div>
<br />
<div style="text-align: center">
    <img style="width: 300px" src="<?= HOME_URI ?>/includes/images/assinco/MeuFuturo_logo2.png"/>
</div>
<div style="text-align: center; font-weight: bold; font-size: 20px; padding: 10px; background-color: #ACC84D">
    Centro de Qualificação Profissional
    <br />
    Secretaria de Indústria, Comércio e Trabalho
</div>
<br />
<?php
if (!empty($form)) {
    $web = 1;
    include ABSPATH . '/module/quali/views/_deferido/body.php';
}