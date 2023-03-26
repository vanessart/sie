<?php
if (!defined('ABSPATH'))
    exit;
$msg = sql::get('profe_msg_coord', '*', ' where dt_fim >= \'' . date("Y-m-d") . '\' order by dt_inicio');
if ($msg) {
    foreach ($msg as $k => $v) {
        $msg[$k]['ac'] = '<button class="btn btn-info" onclick="edit(' . $v['id_mc'] . ')">Acessar</button>';
    }
    $form['array'] = $msg;
    $form['fields'] = [
        'ID' => 'id_mc',
        'Título' => 'n_mc',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Avisos para os Coordenadores
    </div>
    <div class="row">
        <div class="col-3 text-center">
            <button class="btn btn-primary" onclick="edit()">
                Novo Aviso
            </button>
        </div>
        <div class="col-9 alert alert-info">
            <p>
                As mensagens cadastradas serão exibidas na página inicial no nível Coordenador.
            </p>
            <br />
            <p>
                Serão exibidas as mensagens com status Ativo e dentro das datas de início e fim
            </p>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form target="frameEdit" id="formEdit" method="POST" action="<?= HOME_URI ?>/profe/def/formMsgCoord.php">
    <input id="id_mc" type="hidden" name="id_mc" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frameEdit" style=" height: 80vh; border: none; width: 100%"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_mc.value = id;
        } else {
            id_mc.value = '';
        }
        formEdit.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>