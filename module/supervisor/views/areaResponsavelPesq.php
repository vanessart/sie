<?php
if (!defined('ABSPATH'))
    exit;
$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
$result = $model->getAreas(null, $pesquisa, $status == 2 ? 0 : $status);

if ($result) {
    foreach ($result as $k => $v) {
        $result[$k]['ac'] = formErp::button('Acessar', null, 'acesso(' . $v['id_area'] .')');
        $result[$k]['at_area'] = !empty($v['at_area']) ? 'SIM' : 'NÃO';
    }
    $form['array'] = $result;
    $form['fields'] = [
        'ID' => 'id_area',
        'Nome' => 'n_area',
        'Ativo' => 'at_area',
        'Atualização' => 'dt_update',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciar Áreas
    </div>
    <div class="row">
        <div class="col-2">
            <button onclick="acesso()" class="btn btn-info">
               Nova Área
            </button>
        </div>
    </div>
    <br />
    <form method="POST">
        <div class="row">
            <div class="col-7">
                <?=
                formErp::input('pesquisa', 'Área', $pesquisa)
                ?>
            </div>
            <div class="col-3">
                <?= formErp::select('status', ['1' => 'Ativo', '2' => 'Inativo'], 'Status', $status) ?>
            </div>            
            <div class="col-2">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br />

    </form>

    <?php
    if (!empty($form)) {
        report::simple($form);
    } elseif ($pesquisa) {
        ?>
        <div class="alert alert-dark text-center">
            Área não encontrada
        </div>
        <?php
    }
    ?>
</div>
<form id="formFrame" target="frame" action="<?= HOME_URI . '/supervisor/areaForm' ?>" method="POST">
    <input type="hidden" id="id_area" name="id_area" value="" />
</form>
<?php
toolErp::modalInicio(null,'modal-xl');
?>
<iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
toolErp::modalFim();
?>
<script>
    function acesso(id) {
        if (id) {
            document.getElementById('id_area').value = id;
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Área</div>';
        } else {
            document.getElementById('id_area').value = '';
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Nova Área</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
