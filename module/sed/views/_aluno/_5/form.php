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
<br /><br />
<form target="_parent" method="POST" action="<?= HOME_URI ?>/sed/aluno" enctype="multipart/form-data">
    <?php echo formErp::select('fk_id_pront', $tipoDoc, 'Documento', NULL, NULL, NULL, ' required ') ?>
    <br /><br />
    <?php echo formErp::input('n_pu', 'Obs:') ?>
    <br /><br />
    <label id="label" for='selecao-arquivo'>Selecionar Arquivo</label>
    <input required="required" id='selecao-arquivo' type='file' name="arquivo">
    <br /><br />
    <?=
    formErp::hidden([
        'activeNav' => 5,
        'id_pessoa' => $id_pessoa,
    ])
    . formErp::hiddenToken('upDoc')
    ?>
    <div style="text-align: center">
        <input class="btn btn-success" type="submit" />
    </div>
</form>