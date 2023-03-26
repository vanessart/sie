<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$status = @$_POST['status'];

$option = [
    'A' => 'Aguardando Deferimento',
    'T' => 'Todos',
    'SE' => 'Solicitações de Encerramento',
    'E' => 'Encerrados'
];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Deferimento
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-6">
                <?php echo form::select('id_inst', escolas::idInst(), 'Escola', $id_inst) ?>
            </div>
            <div class="col-sm-4">
                <?php echo form::select('status', $option, 'Status', $status) ?>
            </div>
            <div class="col-sm-2">
                <input type="hidden" name="pesq" value="1" />
                <?php echo form::button('Pesquisar') ?>
            </div>
        </div>
        <br /><br />
    </form>
    <?php
    if (!empty($status)) {
        $model->alunoRelat($id_inst, $status);
    }
    tool::modalInicio('width: 100%', 1);
    ?>
    <iframe name="verpage" style="border: none; width: 100%; height: 80vh"></iframe>
    <?php
    tool::modalFim();
    ?>
</div>
<form id="ver" target="verpage" action="<?php echo HOME_URI ?>/transp/ver" method="POST">
    <input id="idpes" type="hidden" name="id_pessoa" value="" />
    <input id="idins" type="hidden" name="id_inst" value="" />
</form>
<script>
    function acesso(idPessoa, idInst) {
        document.getElementById('idpes').value = idPessoa;
        document.getElementById('idins').value = idInst;
        document.getElementById('ver').submit();
    }
</script>
