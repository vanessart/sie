<?php
if (!defined('ABSPATH'))
    exit;
$equips = sql::get(['recurso_equipamento','recurso_cate_equipamento'], 'id_equipamento, n_equipamento, n_cate', ['at_equipamento' => 1]);
?>
<div class="fieldTop">
    <div class="fieldTop">
        Cadastro de Modelo/Lote
    </div>
    <div class="row">
        <div class="col-2">
            <button onclick="acesso()" class="btn btn-warning">
                Novo Modelo/Lote
            </button>
        </div>
    </div>
</div>
<br />
<?php
$info = $model->info("Para alterar a Categoria, utilize a página 'Início' no menu");
$cate = "Cateoria".$info;
if (!empty($equips)) {
    $token = formErp::token('recurso_equipamento', 'ireplace');
    foreach ($equips as $k => $v) {
            $equips[$k]['edit'] = formErp::button('Acessar', null, 'acesso(' . $v['id_equipamento'] .','. ' \'' . $v['n_equipamento']. '\' )');
            $equips[$k]['itens'] = formErp::button('Incluir/Editar Itens', null, 'itens(' . $v['id_equipamento'] .','. ' \'' . $v['n_equipamento']. '\' )');
             $equips[$k]['del'] = formErp::submit('Inativar', $token, ['1[id_equipamento]' => $v['id_equipamento'],'1[at_equipamento]'=> 0],null,null,'Deseja Inativar este modelo?','btn btn-outline-danger');
    }
    $form['array'] = $equips;
    $form['fields'] = [
        'ID' => 'id_equipamento',
        "$cate" =>'n_cate' ,
        'Modelo/Lote' => 'n_equipamento',
        '||3' => 'del',
        '||1' => 'itens',
        '||2' => 'edit'
    ];
}
if (!empty($equips)) {
    report::simple($form);
}?>

<form id="formFrame" target="frame" action="" method="POST">
    <input type="hidden" id="id_equipamento" name="id_equipamento" value="" />
</form>
<?php
toolErp::modalInicio(null, 'modal-xl');
?>
<iframe style=" width: 100%; height: 500px; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acesso(id,n_equipamento) {
        if (id) {
            document.getElementById('id_equipamento').value = id;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/cadEquipamento.php";
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Modelo/Lote: '+n_equipamento+'</div>';
        } else {
            document.getElementById('id_equipamento').value = '';
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/cadEquipamento.php";
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Novo Modelo/Lote</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function itens(id,n_equipamento) {
        if (id) {
            document.getElementById('id_equipamento').value = id;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/cadItens.php";
            texto = texto = '<div style="text-align: center; color: #7ed8f5;"> Itens do modelo: '+n_equipamento+'</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>