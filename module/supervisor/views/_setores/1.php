<?php
if (!defined('ABSPATH'))
    exit;

$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
$fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$result = $model->getSetorAtribuicaoEscola(null, $pesquisa, $status == 2 ? 0 : $status, $fk_id_pessoa);

$coordResult = $model->getCoordenadores();
$aPessoas = [];
if (!empty($coordResult)) {
    foreach ($coordResult as $v) {
        $aPessoas[$v['id_pessoa']] = $v['n_pessoa'];
    }
    asort($aPessoas);
}

if ($result) {
    foreach ($result as $k => $v) {
        $result[$k]['ac'] = formErp::button('Editar', null, 'acesso(' . $v['id_setor'] .')');
        $result[$k]['ac2'] = formErp::submit('Escolas', null, ['id_setor' => $v['id_setor'],'activeNav' => 2],null, null, null, 'btn btn-info');
        $result[$k]['at_setor'] = !empty($v['at_setor']) ? 'SIM' : 'NÃO';
    }
    $form['array'] = $result;
    $form['fields'] = [
        'ID' => 'id_setor',
        'Supervisor' => 'n_pessoa',
        'Stor' => 'n_setor',
        'Ativo' => 'at_setor',
        'Atualização' => 'dt_update',
        '||1' => 'ac',
        '||3' => 'ac2',
    ];
}
?>

    <div class="row">
        <div class="col">
            <button onclick="acesso()" class="btn btn-info">
               Novo setor
            </button>
        </div>
    </div>
    <br />
    <form method="POST">
        <div class="row">
            <div class="col-4">
                <?= formErp::input('pesquisa', 'Setor', $pesquisa) ?>
            </div>
            <div class="col-4">
                <?= formErp::select('fk_id_pessoa', $aPessoas, 'Supervisores', $fk_id_pessoa); ?>
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
        <div class="alert alert-danger text-center">
            Setor não encontrado
        </div>
        <?php
    }
    ?>

<form target="frame" id="form" action="<?= HOME_URI ?>/supervisor/setoresAtribuicaoEscolaModal" method="POST">
    <input type="hidden" name="id_setor" id="id_setor" />
</form>

<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function openModal(id) {
        if (id) {
            id_setor.value = id;
        } else {
            id_setor.value = "";
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>


<form id="formFrame" target="frame" action="<?= HOME_URI . '/supervisor/setoresAtribuicaoEscolaForm' ?>" method="POST">
    <input type="hidden" id="_id_setor" name="id_setor" value="" />
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
            document.getElementById('_id_setor').value = id;
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Setor</div>';
        } else {
            document.getElementById('_id_setor').value = '';
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Setor</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>