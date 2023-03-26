<?php
if (!defined('ABSPATH'))
    exit;
$id_local = filter_input(INPUT_POST, 'id_local', FILTER_SANITIZE_NUMBER_INT);
$id_inst = $model->gerente(1);
$local = sql::get('recurso_local', 'id_local, n_local', ['id_local' => $id_local],'fetch');
    ?>
<div class="body">
    <br><br>
    <form target="_parent" action="<?= HOME_URI ?>/recurso/cadLocal" method="POST">
        <div class="row">
            <div class="col-10">
            <?= formErp::input('1[n_local]', 'Local / Armazenamento', @$local['n_local'], 'required') ?>
            </div>
            <div class="col-2">
                <?php
                if (!empty($id_local)) {
                   echo formErp::hidden([   
                    '1[id_local]' => $id_local,    
                    ]); 
                }
                ?>
                <?=
                    formErp::hidden([   
                    '1[fk_id_inst]' => $id_inst,    
                    ])
                    . formErp::hiddenToken('recurso_local', 'ireplace')
                    . formErp::button('Salvar');
                ?>            
            </div>
        </div>       
    </form>
</div>
        