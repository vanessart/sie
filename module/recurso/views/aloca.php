<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = toolErp::id_inst();
$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$id_local = filter_input(INPUT_POST, 'id_local', FILTER_SANITIZE_NUMBER_INT);
$hoje = date("Y-m-d");
$equipamento = $model->getEquipamento($id_inst);
$mensagem = $model->mensagens;
if (empty($id_local)) {
    $id_local = -1;
}
if (empty($id_local_destino)) {
    $id_local_destino = -1;
}
$hidden = [
    'id_local' => $id_local,
    'id_equipamento' => $id_equipamento,
];
if (!empty($id_equipamento)) {      
    $origem = $model->getLocal($id_inst,$id_equipamento);
    $destino = $model->getLocal($id_inst);
    $serial = sql::get('recurso_serial', 'id_serial,n_serial,fk_id_situacao', ['fk_id_equipamento' => $id_equipamento,'fk_id_local' => $id_local, 'fk_id_inst' => $id_inst ]);
}?>
<div class="body">
    <div class="fieldTop">
        Alocar Objeto/Serial
    </div>
    <?php 
    if (!empty($mensagem)) {
        if ($mensagem > 1) {
            $text = "foram alocados";
        }else{
            $text = "foi alocado";
        }
        ?>
        <div class="alert alert-success">
            <?= $mensagem ?> N/S <?= $text ?> com sucesso       
        </div>  
    <?php 
    } ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_equipamento', $equipamento, 'Modelo/Lote', $id_equipamento, 1, $hidden, ' required ') ?>
        </div>
        <?php 
        if (!empty($id_equipamento)) {?>
            <div class="col">
                <?= formErp::select('id_local', $origem, 'Local Atual', $id_local, 1, $hidden, ' required ') ?>
            </div>
        <?php 
        } ?>
    </div>
    <br />
    <?php 
    if (!empty($id_equipamento)){?>
        <form id='formEnvia' method="POST">
            <div class="row">
            <?php 
            foreach ($serial as $v) {?>
                    <div class="col">
                        <label onclick="sel('<?= $v["id_serial"] ?>')" class="btn btn-outline-secondary <?= ($v['fk_id_situacao'] == 2)?'disabled':"" ?>" id="b_<?= $v['id_serial'] ?>" style="text-align: center; width: 100%">N/S: <?= $v['n_serial'] ?> <?= ($v['fk_id_situacao'] == 2)?' - Emprestado':"" ?></label>
                    </div> 
                    <input style="display:none;" type="checkbox" name="1[serial][]" id="s_<?= $v['id_serial'] ?>" value="<?= $v['id_serial'] ?>">
                <?php
                 } ?>
            </div>
            <br>
            <div class="row">
                <div class="col-3">
                    <?= formErp::select('fk_id_local', $destino, 'Local de Destino', $id_local_destino, null, null, ' required ') ?>
                </div>
                <div class="col-2">
                    <?=
                    formErp::hidden([
                        'fk_id_pessoa_aloca' => toolErp::id_pessoa(),
                        'dt_update' => $hoje,
                    ])
                    .formErp::hiddenToken('alocar')
                    .formErp::button('Alocar', null, 'salvar()' );?>
                </div>
            </div>

        </form>
    <?php 
    }?>
    <br />
</div>

<script type="text/javascript">
     function sel(id_serial){
        id_botao = 'b_'+id_serial;
        id_check = 's_'+id_serial;
        btn_check = document.getElementById(id_check).checked;
        if (!btn_check) {
            document.getElementById(id_botao).classList.remove('btn-outline-secondary');
            document.getElementById(id_botao).classList.add('btn-secondary');
            document.getElementById(id_check).checked = true;

        }else{
            document.getElementById(id_botao).classList.add('btn-outline-secondary');
            document.getElementById(id_botao).classList.remove('btn-secondary');
            document.getElementById(id_check).checked = false;

        }
     }
     function salvar(){
         serial = document.getElementsByName('1[serial][]');
            if(serial.length > 0){
                valida_serial = false;
                for (var i = 0; i < serial.length; i++) {
                    if(serial[i].checked){
                        valida_serial = true;
                        break;
                    }
                }
                if(!valida_serial){
                    alert("Escolha um número serial ou mais.");
                    return false;
                }
            }
        document.getElementById('formEnvia').submit();
    }  
</script>