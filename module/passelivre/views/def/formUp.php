<style>
    /* Esconde o input */
    input[type='file'] {
        display: none
    }

    /* Aparência que terá o seletor de arquivo */
    #label {
        background-color: #3498db;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        margin: 0 auto;
        padding: 6px;
        width: 200px;
        text-align: center
    }    
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_STRING);
$id_passelivre = filter_input(INPUT_POST, 'id_passelivre', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="body">
    <form method="POST" enctype="multipart/form-data" target="_parent" action="<?= HOME_URI ?>/passelivre/requer">
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('pl_doc_tipo', '1[fk_id_dt]', 'Documento', null, null, null, null, null, ' required ') ?>
            </div>
            <div class="col">
                <label id="label" for='selecao-arquivo'>Selecionar Arquivo</label>
                <input required="required" id='selecao-arquivo' type='file' name="arquivo">
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 20px">
            <?=
            formErp::hidden([
                'cie' => $cie,
                'id_passelivre' => $id_passelivre,
                '1[fk_id_passelivre]' => $id_passelivre,
                'activeNav' => 2
            ])
            . formErp::hiddenToken('docUp')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
