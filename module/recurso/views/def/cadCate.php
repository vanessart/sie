<?php
if (!defined('ABSPATH'))
    exit;
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
$categorias = sql::get('recurso_cate_equipamento', 'id_cate, n_cate', ['id_cate' => $id_cate],'fetch');
    ?>
<div class="body">
    <br><br>
    <form target="_parent" action="<?= HOME_URI ?>/recurso/cadCate" method="POST">
        <div class="row">
            <div class="col-10">
            <?= formErp::input('1[n_cate]', 'Categoria', @$categorias['n_cate'], 'required') ?>
            </div>
            <div class="col-2">
                <?php
                if (!empty($id_cate)) {
                   echo formErp::hidden([   
                    '1[id_cate]' => $id_cate,    
                    ]); 
                }
                ?>
                <?=
                    formErp::hiddenToken('recurso_cate_equipamento', 'ireplace')
                    . formErp::button('Salvar');
                ?>            
            </div>
        </div>       
    </form>
</div>
        