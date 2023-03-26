<?php
if (!defined('ABSPATH'))
    exit;
$id_campanha = filter_input(INPUT_POST, 'id_campanha_ativa', FILTER_SANITIZE_NUMBER_INT);
$campanha = sql::get('audicao_campanha', 'id_campanha,n_campanha');
$campanhas = toolErp::idName($campanha);
?>
<div class="body">
    <?= toolErp::divAlert('danger','ATENÇÃO!!!<br><br>Ativar uma Campanha faz com que todo o sistema seja reconfigurado para a campanha escolhida. Certifique-se de que não haja alguma campanha em vigor.'); ?>
    <div class="fieldTop" style="padding-bottom: 5%;">
        Ativar Campanha
    </div>
    <form name='form'  action="<?= HOME_URI ?>/audicao/campanha" method="POST" target='_parent'>   
        <div class="row">
            <div class="col">
                <?= formErp::select('id_campanha', $campanhas,'Escolha a Campanha',@$id_campanha) ?>
            </div>
        </div>
        <br> 
        <br> 
        <br> 
        <div class="row">
            <div class="col text-center">

                <?=
                formErp::hiddenToken('ativar_campanha')
                . formErp::button('Salvar e Reconfigurar Sistema',null,null,'danger');
                ?>            
            </div>
        </div>     
    </form>
</div>