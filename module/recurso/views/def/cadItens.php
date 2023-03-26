<?php
if (!defined('ABSPATH'))
    exit;
$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);

$itens = sql::get('recurso_equip_item', 'id_item, n_item', ['fk_id_equipamento' => $id_equipamento,'at_item' =>1]);

    ?>
<div class="body">
    <br><br>
    <?php
    if (!empty($itens)) {
        foreach ($itens as $v) {?>
            <div class="row">
                <form name="<?= $v['id_item'] ?>" class="col-10" action="<?= HOME_URI ?>/recurso/def/cadItens.php" method="POST">
                    <div class="row">
                        <div class="col-10">
                            <?php
                            echo formErp::input('1[n_item]', 'Item', $v['n_item'], 'required') 
                            .formErp::hidden([   
                                '1[id_item]' => $v['id_item'],
                                'id_equipamento' => $id_equipamento,    
                            ])
                            . formErp::hiddenToken('recurso_equip_item', 'ireplace');?>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-outline-info">
                                Atualizar
                            </button>
                        </div>
                    </div>
                </form>
                <form name="<?= $v['id_item'] ?>2" class="col-1" action="<?= HOME_URI ?>/recurso/def/cadItens.php" method="POST">
                    <div class="row">
                        <div class="col-10">
                        <?php
                        echo formErp::hidden([   
                            '1[id_item]' => $v['id_item'],
                            'id_equipamento' => $id_equipamento,
                            '1[at_item]' => 0
                            ])
                        . formErp::hiddenToken('recurso_equip_item', 'ireplace');?>
                        </div>
                        <div class="col-2">
                        <button class="btn btn-outline-danger">
                            X
                        </button>
                        </div>
                    </div>
                </form>
            </div>
            <br>
        <?php
        }
    }?>
    <div class="row">
        <form action="<?= HOME_URI ?>/recurso/def/cadItens.php" class="col-10" method="POST">
             <div class="row">
                <div class="col-10">
                    <?php
                    echo formErp::input('1[n_item]', 'Novo Item', null, 'required id="novo"') 
                    .formErp::hidden([   
                                    '1[fk_id_equipamento]' => $id_equipamento,    
                                    'id_equipamento' => $id_equipamento,    
                                ])
                    . formErp::hiddenToken('recurso_equip_item', 'ireplace');?>
                </div>
                <div class="col-2">
                    <button class="btn btn-success">
                        Inserir
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
window.onload = function() {
document.getElementById("novo").value = "";
};    
</script>      