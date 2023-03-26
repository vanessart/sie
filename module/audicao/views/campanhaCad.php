<?php
if (!defined('ABSPATH'))
    exit;
$id_campanha = filter_input(INPUT_POST, 'id_campanha', FILTER_SANITIZE_NUMBER_INT);

if ($id_campanha) {
    $campanha = sql::get('audicao_campanha', 'n_campanha', 'WHERE id_campanha =' . $id_campanha, 'fetch');
}
?>
<style type="text/css">
    .input-group-text
    {
        width: 220px;
    }
    fieldset.add-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.add-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:inherit; /* Or auto */
        padding:0 10px; /* To give a bit of padding on the left and right */
        border-bottom:none;
        margin-top: -10px;
        background-color: white;
    }
</style>
<div class="body">
    <div class="fieldTop" style="padding-bottom: 5%;">
        Cadastro de Campanha
    </div>
    <form name='form' id="form" action="<?= HOME_URI ?>/audicao/campanha" method="POST" target='_parent'>   
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_campanha]', 'Nome da Campanha', @$campanha['n_campanha']) ?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[id_campanha]' => @$id_campanha,
                    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                ])
                . formErp::hiddenToken('audicao_campanha', 'ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>     
    </form>
</div>