<?php
if (!defined('ABSPATH'))
    exit;

$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
$fk_id_area = filter_input(INPUT_POST, 'fk_id_area', FILTER_SANITIZE_NUMBER_INT);
$result = $model->getItensOcorrencia(null, $pesquisa, $status == 2 ? 0 : $status, $fk_id_area);

$areasResult = $model->getAreas();
$aAreas = [];
if (!empty($areasResult)) {
    foreach ($areasResult as $v) {
        $aAreas[$v['id_area']] = $v['n_area'];
    }
}

if ($result) {
    foreach ($result as $k => $v) {
        $result[$k]['ac'] = formErp::button('Acessar', null, 'acesso(' . $v['id_item_ocorrencia'] .')');
        $result[$k]['at_item_ocorrencia'] = !empty($v['at_item_ocorrencia']) ? 'SIM' : 'NÃO';
    }
    $form['array'] = $result;
    $form['fields'] = [
        'ID' => 'id_item_ocorrencia',
        'Nome' => 'n_item_ocorrencia',
        'Área' => 'n_area',
        'Ativo' => 'at_item_ocorrencia',
        'Atualização' => 'dt_update',
        '||1' => 'ac'
    ];
}
?>

<div class="body">
    <div class="fieldTop">
        Itens de Ocorrências
    </div>
    <div class="row">
        <div class="col">
           <button onclick="acesso()" class="btn btn-info">
               Novo item
            </button>
        </div>
    </div>
    <br />
    <form method="POST">
        <div class="row">
            <div class="col-4">
                <?=
                formErp::input('pesquisa', 'Descrição do item', $pesquisa)
                ?>
            </div>
            <div class="col-3">
                <?= formErp::select('fk_id_area', $aAreas, 'Área', $fk_id_area); ?>
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
            Item de Ocorrência não encontrado
        </div>
        <?php
    }
    ?>
</div>
<form id="formFrame" target="frame" action="<?= HOME_URI . '/supervisor/itemOcorrenciaForm' ?>" method="POST">
    <input type="hidden" id="id_item_ocorrencia" name="id_item_ocorrencia" value="" />
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
            document.getElementById('id_item_ocorrencia').value = id;
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Item de Ocorrência</div>';
        } else {
            document.getElementById('id_item_ocorrencia').value = '';
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Item de Ocorrência</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
