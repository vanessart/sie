<?php
if (!defined('ABSPATH'))
    exit;

$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
$fk_id_tp = filter_input(INPUT_POST, 'fk_id_tp', FILTER_SANITIZE_NUMBER_INT);
$result = $model->getEscolaParticular(null, $pesquisa, $status == 2 ? 0 : $status, $fk_id_tp);

$tipoResult = $model->getTipoInstancia();
$aTipo = [];
if (!empty($tipoResult)) {
    foreach ($tipoResult as $v) {
        $aTipo[$v['id_tp']] = $v['n_tp'];
    }
    asort($aTipo);
}

if ($result) {
    foreach ($result as $k => $v) {
        $result[$k]['ac'] = formErp::button('Acessar', null, 'acesso(' . $v['id_inst'] .')');
        $result[$k]['at_inst'] = !empty($v['at_inst']) ? 'SIM' : 'NÃO';
    }
    $form['array'] = $result;
    $form['fields'] = [
        'ID' => 'id_inst',
        'Tipo' => 'n_tp',
        'Nome' => 'n_inst',
        'Ativo' => 'at_inst',
        'E-Mail' => 'email',
        'Atualização' => 'dt_update',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciar Instituição Particular
    </div>
    <div class="row">
        <div class="col-2">
            <button onclick="acesso()" class="btn btn-info">
                Nova Instituição
            </button>
        </div>
    </div>
    <br />
    <form method="POST">
        <div class="row">
            <div class="col-6">
                <?= formErp::input('pesquisa', 'Nome da instituição', $pesquisa) ?>
            </div>
            <div class="col-2">
                <?= formErp::select('fk_id_tp', $aTipo, 'Tipo', $fk_id_tp); ?>
            </div>
            <div class="col-2">
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
            Instituição Particular não encontrada
        </div>
        <?php
    }
    ?>
</div>
<form id="formFrame" target="frame" action="<?= HOME_URI . '/supervisor/escolaParticularForm' ?>" method="POST">
    <input type="hidden" id="id_inst" name="id_inst" value="" />
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
            document.getElementById('id_inst').value = id;
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Instituição</div>';
        } else {
            document.getElementById('id_inst').value = '';
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Nova Instituição</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
