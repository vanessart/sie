<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = $model->campanha('id_campanha');
//$campanha = sql::get('ge_periodo_letivo', 'id_pl,n_pl', 'WHERE at_pl IN (1,2) AND semestre = 0');
$campanha = sql::get('ge_periodo_letivo', 'id_pl,n_pl', 'WHERE preferencial = 1');
$campanhas = toolErp::idName($campanha);
?>
<div class="body">
    <?= toolErp::divAlert('danger','ATENÇÃO!!!<br><br>Ativar um Período Letivo faz com que todo o sistema seja reconfigurado para este período.'); ?>
    <div class="fieldTop" style="padding-bottom: 5%;">
       Configurar Período Letivo
    </div>
    <form name='form' method="POST">   
        <div class="row">
            <div class="col">
                <?= formErp::select('id_pl', $campanhas,'Período Letivo',@$id_pl) ?>
            </div>
        </div>
        <br><br><br> 
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